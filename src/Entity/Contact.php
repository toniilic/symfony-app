<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PhoneNumber", inversedBy="contacts")
     */
    private $PhoneNumber;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Location", inversedBy="contacts")
     */
    private $location;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Task", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $task;

    public function __construct()
    {
        $this->PhoneNumber = new ArrayCollection();
        $this->location = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|PhoneNumber[]
     */
    public function getPhoneNumber(): Collection
    {
        return $this->PhoneNumber;
    }

    public function addPhoneNumber(PhoneNumber $phoneNumber): self
    {
        if (!$this->PhoneNumber->contains($phoneNumber)) {
            $this->PhoneNumber[] = $phoneNumber;
        }

        return $this;
    }

    public function removePhoneNumber(PhoneNumber $phoneNumber): self
    {
        if ($this->PhoneNumber->contains($phoneNumber)) {
            $this->PhoneNumber->removeElement($phoneNumber);
        }

        return $this;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocation(): Collection
    {
        return $this->location;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->location->contains($location)) {
            $this->location[] = $location;
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->location->contains($location)) {
            $this->location->removeElement($location);
        }

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(Task $task): self
    {
        $this->task = $task;

        // set the owning side of the relation if necessary
        if ($this !== $task->getContact()) {
            $task->setContact($this);
        }

        return $this;
    }
}
