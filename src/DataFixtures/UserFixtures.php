<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends Fixture implements ContainerAwareInterface, OrderedFixtureInterface
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

        $admin = $this->makeUser($manager, 'admin', 'admin@domain.com', 'admin', ['ROLE_ADMIN']);
        $this->addReference(self::ADMIN_USER_REFERENCE, $admin);
        $user = $this->makeUser($manager, 'user', 'user@domain.com', 'user', ['ROLE_USER']);
        $this->addReference(self::USER_REFERENCE, $user);
        $user2 = $this->makeUser($manager, 'user2', 'user2@domain.com', 'user2', ['ROLE_USER']);
        $this->addReference(self::USER2_REFERENCE, $user2);
        $moderator = $this->makeUser($manager, 'moderator', 'moderator@domain.com', 'moderator', ['ROLE_MODERATOR']);
        $this->addReference(self::MODERATOR_REFERENCE, $moderator);
    }

    private function makeUser(&$manager, $username, $email, $password, $roles)
    {
        // Get our userManager, you must implement `ContainerAwareInterface`
        $userManager = $this->container->get('fos_user.user_manager');

        // Create our user and set details
        $user = $userManager->createUser();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $user->setRoles($roles);

        // Update the user
        $userManager->updateUser($user, true);

        $manager->flush();

        return $user;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }
}
