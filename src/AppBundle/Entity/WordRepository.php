<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class WordRepository extends EntityRepository
{
    public function findRandomWords($exWordId)
    {
      $wordsCount = $this->createQueryBuilder('w')
        ->select('COUNT(w)')
        ->getQuery()
        ->getSingleScalarResult(); 
        
      $randWords = $this->createQueryBuilder('w')
        ->addSelect('RAND() as HIDDEN rand')
        ->where('w.id <> :id AND (w.id > :max_count - 5 AND w.id < :max_count + 5)')
        ->setParameter('id', $exWordId)
        ->setParameter('max_count', rand(1, $wordsCount))
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();  
        
      return $randWords;
    }
}