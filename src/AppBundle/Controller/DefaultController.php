<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
    
    /**
     * @Route("/startTest", name="start_test")
     */
    public function startTestAction(Request $request) {
      $response = new Response;
      $response->headers->set('Content-Type', 'application/json');
      
      $data = json_decode($request->getContent(), true);
      $request->request->replace($data);
      
      //fetching user name
      if (!$userName = $request->request->get('user_name')) {
        $response->setContent(json_encode(array('error' => 'Invalid name')));
        return $response;
      }
      
      //saving user 
      $user = new User();
      $user->setName($userName);
      $user->setScore(0);
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();   
          
      //start sesssion 
      $session = $request->getSession();
      $session->start();
      $session->set('user_id', $user->getId());
      $session->set('name', $user->getName());
      $session->set('score', $user->getScore());
      $session->set('errors_count', 0);
      
      $response->setContent(json_encode(array('success' => true)));
      return $response;      
    }
    
    /**
     * @Route("/finishTest", name="finish_test")
     */
    public function finishTestAction(Request $request) {
      $response = new Response;
      $response->headers->set('Content-Type', 'application/json');
      
      //start sesssion 
      $session = $request->getSession();
      $session->start(); 
      
      //finding user
      $em = $this->getDoctrine()->getManager();
      $user = $em->getRepository('AppBundle:User')->find($session->get('user_id'));

      if (!$user) {
        $response->setContent(json_encode(array('error' => 'Invalid session. Cant find user')));
        return $response;
      }
      
      //saving user data
      $user->setScore($session->get('score'));
      $em->flush();

      $response->setContent(json_encode(array(
        'score' => $session->get('score'),
        'errors_count' => $session->get('errors_count'),
      )));
      
      
      $session->invalidate();
      
      return $response;        
    }
}
