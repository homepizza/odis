<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkflowRepository")
 */
class Workflow
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Statuses")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Statuses")
     */
    private $access;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAccess(): ?Statuses
    {
        return $this->access;
    }

    public function setAccess(?Statuses $access): self
    {
        $this->access = $access;

        return $this;
    }
}
