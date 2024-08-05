<?php

namespace App\Entity;

use App\Repository\ModeficationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModeficationRepository::class)]
class Modefication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'modefications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipment $numeroEqp = null;

    #[ORM\ManyToOne(inversedBy: 'modefications')]
    private ?User $username = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_modefecation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroEqp(): ?Equipment
    {
        return $this->numeroEqp;
    }

    public function setNumeroEqp(?Equipment $numeroEqp): static
    {
        $this->numeroEqp = $numeroEqp;

        return $this;
    }

    public function getUsername(): ?User
    {
        return $this->username;
    }

    public function setUsername(?User $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getDateModefecation(): ?\DateTimeInterface
    {
        return $this->date_modefecation;
    }

    public function setDateModefecation(\DateTimeInterface $date_modefecation): static
    {
        $this->date_modefecation = $date_modefecation;

        return $this;
    }
}
