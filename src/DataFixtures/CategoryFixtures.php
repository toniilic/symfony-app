<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CategoryFixtures extends Fixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    public const CLEANING_CATEGORY_REFERENCE = 'cleaning-category';

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
        $category = $this->makeCategory($manager, 'Delivery');
        $this->addReference(self::CLEANING_CATEGORY_REFERENCE, $category);
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

        return $category;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 4;
    }
}
