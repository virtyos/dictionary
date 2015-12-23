<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Word;
use AppBundle\Entity\TranslateError;
use AppBundle\Component\Utility;

class DictController extends Controller
{
    
    /**
     * @Route("/dict/getQuestion", name="dict_quest")
     */
    public function getQuestionAction(Request $request) {
      $response = new Response;
      $response->headers->set('Content-Type', 'application/json');
      
      $data = json_decode($request->getContent(), true);
      $request->request->replace($data);
       
      //fetching word id from last question 
      if (!$lastWordId = $request->request->get('word_id')) {
        $lastWordId = 0;
      }
      
      //find a word next to the word from the last question
      $repository = $this->getDoctrine()
        ->getRepository('AppBundle:Word');
      $query = $repository->createQueryBuilder('w')
        ->where('w.id > :id')
        ->setParameter('id', $lastWordId)
        ->setMaxResults(1)
        ->getQuery();
      $nextWord = $query->getSingleResult();
      
      //if it is no more words
      if (!$nextWord) {
        $response->setContent(json_encode(array('finish' => true)));
        return $response;
      } 

      //find three random translations
      $randWords = $repository->findRandomWords($nextWord->getId());  
      
      $translations = array_merge(array_map(function($w) { return $w->getTranslate();}, $randWords),
          array($nextWord->getTranslate()));
      shuffle($translations);
      
      $response->setContent(json_encode(array(
        'word_id' => $nextWord->getId(),
        'word' => $nextWord->getWord(),
        'translations' => $translations
      )));
      return $response;
    }
    
    
    
    /**
     * @Route("/dict/saveQuestionResult", name="dict_save_result")
     */
    public function saveQuestionResultAction(Request $request) {
      $response = new Response;
      $response->headers->set('Content-Type', 'application/json');
      
      $data = json_decode($request->getContent(), true);
      $request->request->replace($data);
      
      //fetching word id and translate from request
      if (
        !($wordId = $request->request->get('word_id')) 
        || !($translate = $request->request->get('translate')) 
      ) {
        $response->setContent(json_encode(array('error' => 'wrong data')));
        return $response;
      }
      
      //start sesssion 
      $session = $request->getSession();
      $session->start(); 
      
      $score = $session->get('score');
      $errorsCount = $session->get('errors_count');
      
      $word = $this->getDoctrine()
        ->getRepository('AppBundle:Word')
        ->find($wordId);
        
        
      if (!$word) {
        throw $this->createNotFoundException('The word does not exist');
      }
        
      //incorrect translation
      if ($word->getTranslate() != $translate) {
        $session->set('errors_count', $errorsCount + 1);
        $response->setContent(json_encode(array('error' => 'invalid answer', 'errors_count' => $errorsCount)));
        
        $errorHash = Utility::crc32($word->getWord() . $translate);
        //find such error in DB

        try {
          $translateError = $this->getDoctrine()
          ->getRepository('AppBundle:TranslateError')
          ->createQueryBuilder('t')
          ->where('t.hash = ' . $errorHash)
          ->setMaxResults(1)
          ->getQuery()
          ->getSingleResult(); 
        } catch (\Doctrine\ORM\NoResultException $e) {
          $translateError = new TranslateError();
          $translateError->setWord($word->getWord());     
          $translateError->setTranslate($translate);        
          $translateError->setHash($errorHash); 
          $translateError->setCount(0);          
        }

        $translateError->setCount($translateError->getCount() + 1);
        
        //saving error
        $em = $this->getDoctrine()->getManager();
        $em->persist($translateError);
        $em->flush();        
        
      } else {
        $session->set('score', $score + 1);
        $response->setContent(json_encode(array('success' => true)));
      }
   
      
      return $response;
    }
}
