<?php

namespace App\Entity;

use App\Repository\ChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
class Chambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $etage = null;

    #[ORM\Column]
    private ?int $nombre_lit = null;

    #[ORM\ManyToOne(inversedBy: 'chambres')]
    #[ORM\JoinColumn(name: 'chambre_id')]
    private ?Service $service = null;

    /**
     * @var Collection<int, Lit>
     */
    #[ORM\OneToMany(targetEntity: Lit::class, mappedBy: 'chambre')]
    #[Ignore]
    private Collection $lits;

    /**
     * @var Collection<int, Sejour>
     */
    #[ORM\OneToMany(targetEntity: Sejour::class, mappedBy: 'chambre')]
    #[Ignore]
    private Collection $sejours;


    public function __construct()
    {
        $this->lits = new ArrayCollection();
        $this->sejours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(int $etage): static
    {
        $this->etage = $etage;

        return $this;
    }

    public function getNombreLit(): ?int
    {
        return $this->nombre_lit;
    }

    public function setNombreLit(int $nombre_lit): static
    {
        $this->nombre_lit = $nombre_lit;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Collection<int, Lit>
     */
    public function getLits(): Collection
    {
        return $this->lits;
    }

    public function addLit(Lit $lit): static
    {
        if (!$this->lits->contains($lit)) {
            $this->lits->add($lit);
            $lit->setChambre($this);
        }

        return $this;
    }

    public function removeLit(Lit $lit): static
    {
        if ($this->lits->removeElement($lit)) {
            // set the owning side to null (unless already changed)
            if ($lit->getChambre() === $this) {
                $lit->setChambre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sejour>
     */
    public function getSejours(): Collection
    {
        return $this->sejours;
    }

    public function addSejour(Sejour $sejour): static
    {
        if (!$this->sejours->contains($sejour)) {
            $this->sejours->add($sejour);
            $sejour->setChambre($this);
        }

        return $this;
    }

    public function removeSejour(Sejour $sejour): static
    {
        if ($this->sejours->removeElement($sejour)) {
            // set the owning side to null (unless already changed)
            if ($sejour->getChambre() === $this) {
                $sejour->setChambre(null);
            }
        }

        return $this;
    }

}
