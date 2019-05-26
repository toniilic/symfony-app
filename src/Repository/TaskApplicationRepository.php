<?php

namespace App\Repository;

use App\Entity\TaskApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TaskApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskApplication[]    findAll()
 * @method TaskApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskApplicationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TaskApplication::class);
    }

    // /**
    //  * @return TaskApplication[] Returns an array of TaskApplication objects
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
    public function findTaskApplicationsByTask($task): ?array
    {
        return $this->createQueryBuilder('ta')
            ->where('ta.task = :task')
            //->andWhere('ta.user = :user')
            ->setParameter('task', $task)
            //->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getTaskApplicationsCount($task)
    {
        return $this->createQueryBuilder('t')
                    ->andWhere('t.task = :task')
                    ->setParameter('task', $task)
                    ->select('count(t.id)')
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function findOneBySomeField($value): ?TaskApplication
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
