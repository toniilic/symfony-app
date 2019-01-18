<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskApplicationRepository")
 */
class TaskApplication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Task", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $task;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hourlyRate;

    /**
     * @ORM\Column(type="text", length=1500)
     */
    private $coverLetter;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TaskApplicationMessage", mappedBy="taskApplication")
     */
    private $taskApplicationMessages;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $submitter;

    public function __construct()
    {
        $this->taskApplicationMessages = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getHourlyRate(): ?int
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate(?int $hourlyRate): self
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
    }

    public function getCoverLetter(): ?string
    {
        return $this->coverLetter;
    }

    public function setCoverLetter(string $coverLetter): self
    {
        $this->coverLetter = $coverLetter;

        return $this;
    }

    /**
     * @return Collection|TaskApplicationMessage[]
     */
    public function getTaskApplicationMessages(): Collection
    {
        return $this->taskApplicationMessages;
    }

    public function addTaskApplicationMessage(TaskApplicationMessage $taskApplicationMessage): self
    {
        if (!$this->taskApplicationMessages->contains($taskApplicationMessage)) {
            $this->taskApplicationMessages[] = $taskApplicationMessage;
            $taskApplicationMessage->setTaskApplication($this);
        }

        return $this;
    }

    public function removeTaskApplicationMessage(TaskApplicationMessage $taskApplicationMessage): self
    {
        if ($this->taskApplicationMessages->contains($taskApplicationMessage)) {
            $this->taskApplicationMessages->removeElement($taskApplicationMessage);
            // set the owning side to null (unless already changed)
            if ($taskApplicationMessage->getTaskApplication() === $this) {
                $taskApplicationMessage->setTaskApplication(null);
            }
        }

        return $this;
    }

    public function getSubmitter(): ?User
    {
        return $this->submitter;
    }

    public function setSubmitter(User $submitter): self
    {
        $this->submitter = $submitter;

        return $this;
    }
}
