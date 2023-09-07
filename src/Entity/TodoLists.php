<?php

namespace App\Entity;

use App\Repository\TodoListsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TodoListsRepository::class)]
class TodoLists
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $nameList = null;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTime $deadLine = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameList(): ?string
    {
        return $this->nameList;
    }

    public function setNameList(string $nameList): static
    {
        $this->nameList = $nameList;

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

    /**
     * Get the value of owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @return  self
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }
}
