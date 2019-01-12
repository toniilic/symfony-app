<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->makeCategory($manager, 'Delivery');
        $this->makeCategory($manager,'Cleaning');
        $this->makeCategory($manager,'Repair and construction');
        $this->makeCategory($manager,'Domestic services');
        $this->makeCategory($manager,'Work on the Internet');
        $this->makeCategory($manager,'Computer help');
        $this->makeCategory($manager,'Animals');
        $this->makeCategory($manager,'Design');
        $this->makeCategory($manager,'Health and Beauty');
        $this->makeCategory($manager,'Education');
        $this->makeCategory($manager,'Auto services');
        $this->makeCategory($manager,'Accounting and legal');
        $this->makeCategory($manager,'Other Services');

        $manager->flush();
    }

    protected function makeCategory(&$manager, $title, $description = null)
    {
        $category = new Category();
        $category->setTitle($title);
        !$description || $category->setDescription($description);

        $manager->persist($category);

    }
}
