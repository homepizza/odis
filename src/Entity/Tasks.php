<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TasksRepository")
 */
class Tasks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", fetch="EAGER")
     */
    private $asignee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Priorities", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Statuses", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Types", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DomainAreas", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $area;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $due_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $body;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Attachments", mappedBy="task", fetch="EAGER")
     */
    private $attachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPriority(): ?Priorities
    {
        return $this->priority;
    }

    public function setPriority(?Priorities $priority): self
    {
        $this->priority = $priority;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(?\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return Collection|Attachments[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachments $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setTask($this);
        }

        return $this;
    }

    public function removeAttachment(Attachments $attachment): self
    {
        if ($this->attachments->contains($attachment)) {
            $this->attachments->removeElement($attachment);
            // set the owning side to null (unless already changed)
            if ($attachment->getTask() === $this) {
                $attachment->setTask(null);
            }
        }

        return $this;
    }
}
