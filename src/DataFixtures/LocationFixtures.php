<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LocationFixtures extends Fixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const USER_REFERENCE = 'user-user';
    public const USER2_REFERENCE = 'user2-user';
    public const MODERATOR_REFERENCE = 'moderator-user';

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
        $this->makeLocation($manager, 'Croatia', 'Primorsko-Goranska', 'Rijeka', 'Address 34',
            51000, 'HRK', $this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $this->makeLocation($manager, 'Croatia', 'Primorsko-Goranska', 'Rijeka', 'Address 35',
            51000, 'HRK', $this->getReference(UserFixtures::USER_REFERENCE));


        $manager->flush();
    }

    protected function makeLocation(&$manager, $country, $region, $city, $address, $postalCode, $currency, $user)
    {
        $location = new Location();
        $location->setCountry($country);
        $location->setRegion($region);
        $location->setCity($city);
        $location->setAddress($address);
        $location->setPostalCode($postalCode);
        $location->setCurrency($currency);
        $location->setUser($user);

        $manager->persist($location);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 2;
    }
}
