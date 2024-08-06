<?php

namespace App\Entity;

use App\Repository\NotefecationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotefecationRepository::class)]
class Notefecation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    

    #[ORM\Column(length: 255)]
    private ?string $in_table = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_notefecation = null;

    #[ORM\Column]
    private ?bool $seen = null;

    #[ORM\Column(length: 255)]
    private ?string $in_line = null;

    #[ORM\Column(length: 255)]
    private ?string $did_what = null;

    #[ORM\Column(length: 255)]
    private ?string $who = null;

    public function getId(): ?int
    {
        return $this->id;
    }

   
    public function getInTable(): ?string
    {
        return $this->in_table;
    }

    public function setInTable(string $in_table): static
    {
        $this->in_table = $in_table;

        return $this;
    }

    public function getDateNotefecation(): ?\DateTimeInterface
    {
        return $this->date_notefecation;
    }

    public function setDateNotefecation(\DateTimeInterface $date_notefecation): static
    {
        $this->date_notefecation = $date_notefecation;

        return $this;
    }

    public function isSeen(): ?bool
    {
        return $this->seen;
    }

    public function setSeen(bool $seen): static
    {
        $this->seen = $seen;

        return $this;
    }

    public function getInLine(): ?string
    {
        return $this->in_line;
    }

    public function setInLine(string $in_line): static
    {
        $this->in_line = $in_line;

        return $this;
    }

    public function getDidWhat(): ?string
    {
        return $this->did_what;
    }

    public function setDidWhat(string $did_what): static
    {
        $this->did_what = $did_what;

        return $this;
    }

    public function getWho(): ?string
    {
        return $this->who;
    }

    public function setWho(string $who): static
    {
        $this->who = $who;

        return $this;
    }
}
