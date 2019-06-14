<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @return Task[] Returns an array of Task objects
    */
    public function findByExampleField($value = null)
    {
        return $this->createQueryBuilder('t')
            //->andWhere('t.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getTaskLocation($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findTaskOwner($user)
    {
      return $this->createQueryBuilder('t')
                  ->where('t.user = :user')
                  ->setParameter('user', $user)
                  ->getQuery()
                  ->getOneOrNullResult();
    }

    public function findTasksByUser($user)
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findTaskApplicationsByTaskOwner($user, $task): ?array
    {
        return $this->createQueryBuilder('t')
            ->addSelect('t', 'u', 'ta')
            // get tasks applications by user and task
            ->innerJoin('ta.user', 'u')
            ->leftJoin('ta.task', 't')
            ->where('u = :user')
            ->andWhere('t = :task' )
            //->orderBy('ta.publishedAt', 'DESC')
            ->setParameter('user', $user)
            ->setParameter('task', $task)
            ->getQuery()
            ->getResult()
            ;
    }

}
