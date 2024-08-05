<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'numeroEqp',type:'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_ajoute = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(length: 34)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 20)]
    private ?string $state = 'disponible';

    /**
     * @var Collection<int, Reparation>
     */
    #[ORM\OneToMany(targetEntity: Reparation::class, mappedBy: 'numeroEqp')]
    private Collection $reparations;

    /**
     * @var Collection<int, Utilisation>
     */
    #[ORM\OneToMany(targetEntity: Utilisation::class, mappedBy: 'numeroEqp')]
    private Collection $utilisations;

    /**
     * @var Collection<int, Modefication>
     */
    #[ORM\OneToMany(targetEntity: Modefication::class, mappedBy: 'numeroEqp')]
    private Collection $modefications;

    public function __construct()
    {
        $this->reparations = new ArrayCollection();
        $this->utilisations = new ArrayCollection();
        $this->modefications = new ArrayCollection();
    }

   

 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateAjoute(): ?\DateTimeInterface
    {
        return $this->date_ajoute;
    }

    public function setDateAjoute(\DateTimeInterface $date_ajoute): static
    {
        $this->date_ajoute = $date_ajoute;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, Reparation>
     */
    public function getReparations(): Collection
    {
        return $this->reparations;
    }

    public function addReparation(Reparation $reparation): static
    {
        if (!$this->reparations->contains($reparation)) {
            $this->reparations->add($reparation);
            $reparation->setNumeroEqp($this);
        }

        return $this;
    }

    public function removeReparation(Reparation $reparation): static
    {
        if ($this->reparations->removeElement($reparation)) {
            // set the owning side to null (unless already changed)
            if ($reparation->getNumeroEqp() === $this) {
                $reparation->setNumeroEqp(null);
            }
        }

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
            $utilisation->setNumeroEqp($this);
        }

        return $this;
    }

    public function removeUtilisation(Utilisation $utilisation): static
    {
        if ($this->utilisations->removeElement($utilisation)) {
            // set the owning side to null (unless already changed)
            if ($utilisation->getNumeroEqp() === $this) {
                $utilisation->setNumeroEqp(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Modefication>
     */
    public function getModefications(): Collection
    {
        return $this->modefications;
    }

    public function addModefication(Modefication $modefication): static
    {
        if (!$this->modefications->contains($modefication)) {
            $this->modefications->add($modefication);
            $modefication->setNumeroEqp($this);
        }

        return $this;
    }

    public function removeModefication(Modefication $modefication): static
    {
        if ($this->modefications->removeElement($modefication)) {
            // set the owning side to null (unless already changed)
            if ($modefication->getNumeroEqp() === $this) {
                $modefication->setNumeroEqp(null);
            }
        }

        return $this;
    }

   
}
