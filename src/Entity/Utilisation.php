<?php

namespace App\Entity;

use App\Repository\UtilisationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisationRepository::class)]
class Utilisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'utilisations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipment $numeroEqp = null;

    #[ORM\ManyToOne(inversedBy: 'utilisations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $idEmp = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_utilisation = null;

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

    public function getIdEmp(): ?Employee
    {
        return $this->idEmp;
    }

    public function setIdEmp(?Employee $idEmp): static
    {
        $this->idEmp = $idEmp;

        return $this;
    }

    public function getDateUtilisation(): ?\DateTimeInterface
    {
        return $this->date_utilisation;
    }

    public function setDateUtilisation(\DateTimeInterface $date_utilisation): static
    {
        $this->date_utilisation = $date_utilisation;

        return $this;
    }
}
