<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhoneNumber", mappedBy="user")
     */
    private $phoneNumbers;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Location", mappedBy="user")
     */
    private $location;


    public function __construct()
    {
        parent::__construct();
        $this->phoneNumbers = new ArrayCollection();
    }

    /**
     * @return Collection|PhoneNumber[]
     */
    public function getPhoneNumbers(): Collection
    {
        return $this->phoneNumbers;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocation()
    {
        return $this->location;
    }
}