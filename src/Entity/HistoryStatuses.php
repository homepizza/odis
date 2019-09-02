<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryStatusesRepository")
 */
class HistoryStatuses
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $task;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Statuses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStatus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $asignee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?Tasks
    {
        return $this->task;
    }

    public function setTask(?Tasks $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getStatus(): ?Statuses
    {
        return $this->status;
    }

    public function setStatus(?Statuses $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateStatus(): ?\DateTimeInterface
    {
        return $this->dateStatus;
    }

    public function setDateStatus(\DateTimeInterface $dateStatus): self
    {
        $this->dateStatus = $dateStatus;

        return $this;
    }

    public function getAsignee(): ?User
    {
        return $this->asignee;
    }

    public function setAsignee(?User $asignee): self
    {
        $this->asignee = $asignee;

        return $this;
    }
}
