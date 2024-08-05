<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nucleos\UserBundle\Model\User as BaseUser;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User extends BaseUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected $id;

    /**
     * @var Collection<int, Modefication>
     */
    #[ORM\OneToMany(targetEntity: Modefication::class, mappedBy: 'username')]
    private Collection $modefications;

    /**
     * @var Collection<int, Notefecation>
     */
    #[ORM\OneToMany(targetEntity: Notefecation::class, mappedBy: 'who')]
    private Collection $notefecations;

    #[ORM\Column(nullable: true)]
    private ?int $countNTF = 0;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->modefications = new ArrayCollection();
        $this->notefecations = new ArrayCollection();
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
            $modefication->setUsername($this);
        }

        return $this;
    }

    public function removeModefication(Modefication $modefication): static
    {
        if ($this->modefications->removeElement($modefication)) {
            // set the owning side to null (unless already changed)
            if ($modefication->getUsername() === $this) {
                $modefication->setUsername(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notefecation>
     */
    public function getNotefecations(): Collection
    {
        return $this->notefecations;
    }

    public function addNotefecation(Notefecation $notefecation): static
    {
        if (!$this->notefecations->contains($notefecation)) {
            $this->notefecations->add($notefecation);
            $notefecation->setWho($this);
        }

        return $this;
    }

    public function removeNotefecation(Notefecation $notefecation): static
    {
        if ($this->notefecations->removeElement($notefecation)) {
            // set the owning side to null (unless already changed)
            if ($notefecation->getWho() === $this) {
                $notefecation->setWho('');
            }
        }

        return $this;
    }

    public function getCountNTF(): ?int
    {
        return $this->countNTF;
    }

    public function setCountNTF(?int $countNTF): static
    {
        $this->countNTF = $countNTF;

        return $this;
    }
}
