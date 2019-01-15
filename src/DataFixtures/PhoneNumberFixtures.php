<?php

namespace App\DataFixtures;

use App\Entity\PhoneNumber;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PhoneNumberFixtures extends Fixture implements ContainerAwareInterface, OrderedFixtureInterface
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
        $this->makePhoneNumber($manager, $this->getReference(UserFixtures::PUBLISHER_REFERENCE), 'mobile',
            false);
        $this->makePhoneNumber($manager, $this->getReference(UserFixtures::PUBLISHER2_REFERENCE), 'home',
            false);


        $manager->flush();
    }

    private function makePhoneNumber($manager, $user, $type, $isHidden)
    {
        $phoneNumber = new PhoneNumber();
        $phoneNumber->setUser($user);
        $phoneNumber->setType($type);
        $phoneNumber->setNumber(mt_rand());
        $phoneNumber->setIsHidden($isHidden);
        $phoneNumber->setUser($user);
        $manager->persist($phoneNumber);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 3;
    }
}
