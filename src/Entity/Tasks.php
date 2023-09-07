<?php

namespace App\Entity;

use App\Entity\Status;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TasksRepository;

#[ORM\Entity(repositoryClass: TasksRepository::class)]
class Tasks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    // #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    // private ?\DateTimeInterface $deadLine = null;
    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTime $deadLine = null;

    // #[ORM\Column(length: 50)]
    // private ?string $etat = null;

    #[ORM\ManyToOne]
    private ?TodoLists $tasksTodolists = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getDeadLine(): ?\DateTime
    {
        return $this->deadLine;
    }

    public function setDeadLine(?\DateTime $deadLine): static
    {
        $this->deadLine = $deadLine;

        return $this;
    }

    // public function getEtat(): ?string
    // {
    //     return $this->etat;
    // }

    // public function setEtat(string $etat): static
    // {
    //     $this->etat = $etat;

    //     return $this;
    // }

    public function getTasksTodolists(): ?TodoLists
    {
        return $this->tasksTodolists;
    }

    public function setTasksTodolists(?TodoLists $tasksTodolists): static
    {
        $this->tasksTodolists = $tasksTodolists;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }
}
