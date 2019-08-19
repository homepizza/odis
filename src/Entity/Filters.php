<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FiltersRepository")
 */
class Filters
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $asignee;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dueFrom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dueTo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Priorities")
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Types")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DomainAreas")
     */
    private $area;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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

    public function getDueFrom(): ?\DateTimeInterface
    {
        return $this->dueFrom;
    }

    public function setDueFrom(?\DateTimeInterface $dueFrom): self
    {
        $this->dueFrom = $dueFrom;

        return $this;
    }

    public function getDueTo(): ?\DateTimeInterface
    {
        return $this->dueTo;
    }

    public function setDueTo(?\DateTimeInterface $dueTo): self
    {
        $this->dueTo = $dueTo;

        return $this;
    }

    public function getPriority(): ?Priorities
    {
        return $this->priority;
    }

    public function setPriority(?Priorities $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getType(): ?Types
    {
        return $this->type;
    }

    public function setType(?Types $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getArea(): ?DomainAreas
    {
        return $this->area;
    }

    public function setArea(?DomainAreas $area): self
    {
        $this->area = $area;

        return $this;
    }
}
