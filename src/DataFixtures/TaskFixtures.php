<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
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
        $description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
        Aliquam sagittis accumsan nisi ac tempor. Cras arcu ex, bibendum dapibus 
        dui et, fringilla laoreet lacus.';

        $dueDate = new Datetime('now + 18 days');

        $this->makeTask($manager, $this->getReference(UserFixtures::PUBLISHER_REFERENCE), $description,
            'Lorem Lipsum Dolor', 100, $this->getReference(CategoryFixtures::CLEANING_CATEGORY_REFERENCE),
                $dueDate, Task::LEVEL_OF_EXPERTIES_NOVICE, 20);
        $this->makeTask($manager, $this->getReference(UserFixtures::PUBLISHER2_REFERENCE), $description,
            'Lorem Lipsum Dolor', 100, $this->getReference(CategoryFixtures::CLEANING_CATEGORY_REFERENCE),
            $dueDate, Task::LEVEL_OF_EXPERTIES_NOVICE, 20);

        $manager->flush();
    }

    private function makeTask(&$manager, User $user, $description, $title, $budget, $category, $dueDate,
                              $levelOfExpertise, $duration, $approved = true)
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
        $task->setPhoneNumber($user->getPhoneNumbers()[0]);
        $task->setApproved($approved);

        $manager->persist($task);
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
