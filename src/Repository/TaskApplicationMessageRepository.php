<?php

namespace App\Repository;

use App\Entity\TaskApplicationMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TaskApplicationMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskApplicationMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskApplicationMessage[]    findAll()
 * @method TaskApplicationMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskApplicationMessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TaskApplicationMessage::class);
    }

    // /**
    //  * @return TaskApplicationMessage[] Returns an array of TaskApplicationMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TaskApplicationMessage
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
