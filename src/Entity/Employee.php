<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 40)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthday = null;

  



    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_de_creetion = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?float $salaire = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $birth_certeficat = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $residence = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $profile_image = null;

    /**
     * @var Collection<int, Utilisation>
     */
    #[ORM\OneToMany(targetEntity: Utilisation::class, mappedBy: 'idEmp')]
    private Collection $utilisations;

    public function __construct()
    {
        $this->utilisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }




    public function getDateDeCreetion(): ?\DateTimeInterface
    {
        return $this->date_de_creetion;
    }

    public function setDateDeCreetion(\DateTimeInterface $date_de_creetion): static
    {
        $this->date_de_creetion = $date_de_creetion;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(?float $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getBirthCerteficat(): ?string
    {
        return $this->birth_certeficat;
    }

    public function setBirthCerteficat(?string $birth_certeficat): static
    {
        $this->birth_certeficat = $birth_certeficat;

        return $this;
    }

    public function getResidence(): ?string
    {
        return $this->residence;
    }

    public function setResidence(?string $residence): static
    {
        $this->residence = $residence;

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profile_image;
    }

    public function setProfileImage(?string $profile_image): static
    {
        $this->profile_image = $profile_image;

        return $this;
    }

    /**
     * @return Collection<int, Utilisation>
     */
    public function getUtilisations(): Collection
    {
        return $this->utilisations;
    }

    public function addUtilisation(Utilisation $utilisation): static
    {
        if (!$this->utilisations->contains($utilisation)) {
            $this->utilisations->add($utilisation);
            $utilisation->setIdEmp($this);
        }

        return $this;
    }

    public function removeUtilisation(Utilisation $utilisation): static
    {
        if ($this->utilisations->removeElement($utilisation)) {
            // set the owning side to null (unless already changed)
            if ($utilisation->getIdEmp() === $this) {
                $utilisation->setIdEmp(null);
            }
        }

        return $this;
    }
}
