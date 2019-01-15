<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TaskFixtures extends Fixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        //$this->makeTask(&$manager, $this->getReference(UserFixtures::PUBLISHER_REFERENCE));

        $manager->flush();
    }

    private function makeTask($manager, User $user)
    {
        $task = new Task();
        $task->setUser($user);
        $task->setDescription($description);
        $task->setTitle($title);
        $task->setBudget($budget);
        $task->setCategory($category);
        $task->setDueDate($dueDate);
        $task->setLevelOfExpertise($levelOfExpertise);
        $task->setDuration($duration);
        $task->setLocation($user->getLocation());
        $task->setPhoneNumber($user->getPhoneNumbers()[0]);
        $task->

        $manager->persist($task);
        $manager->flush();
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 5;
    }
}
