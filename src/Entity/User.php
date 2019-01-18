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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="user")
     */
    private $tasks;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TaskApplication", inversedBy="submitter")
     */
    private $taskApplication;


    public function __construct()
    {
        parent::__construct();
        $this->phoneNumbers = new ArrayCollection();
        $this->tasks = new ArrayCollection();
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


    /**
     *
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setUser($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }

    public function getTaskApplication(): ?TaskApplication
    {
        return $this->taskApplication;
    }

    public function setTaskApplication(?TaskApplication $taskApplication): self
    {
        $this->taskApplication = $taskApplication;

        return $this;
    }
}