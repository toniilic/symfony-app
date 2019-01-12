<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LocationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->makeLocation($manager, 'Croatia', 'Primorsko-Goranska', 'Rijeka', 'Braće Bačić 34', 51000, 'HRK');

        $manager->flush();
    }

    protected function makeLocation(&$manager, $country, $region, $city, $address, $postalCode, $currency)
    {
        $location = new Location();
        $location->setCountry($country);
        $location->setRegion($region);
        $location->setCity($city);
        $location->setAddress($address);
        $location->setPostalCode($postalCode);
        $location->setCurrency($currency);

        $manager->persist($location);
    }
}
