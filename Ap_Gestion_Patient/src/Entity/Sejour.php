<?php

namespace App\Entity;

use App\Repository\SejourRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SejourRepository::class)]
class Sejour
{
    #[ORM\Column(type: 'boolean')]
    private bool $arrivee_etat = false;

    #[ORM\Column(type: 'boolean')]
    private bool $sortie_etat = false;

    public function isArriveeEtat(): bool
    {
        return $this->arrivee_etat;
    }

    public function setArriveeEtat(bool $etat): static
    {
        $this->arrivee_etat = $etat;
        return $this;
    }

    public function isSortieEtat(): bool
    {
        return $this->sortie_etat;
    }

    public function setSortieEtat(bool $etat): static
    {
        $this->sortie_etat = $etat;
        return $this;
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $date_entree = null;

    #[ORM\Column]
    private ?\DateTime $date_sortie = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    #[ORM\Column(length: 50)]
    private ?string $statut_du_jour = null;

    #[ORM\ManyToOne(inversedBy: 'sejours')]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'sejours')]
    private ?Chambre $chambre = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEntree(): ?\DateTime
    {
        return $this->date_entree;
    }

    public function setDateEntree(\DateTime $date_entree): static
    {
        $this->date_entree = $date_entree;

        return $this;
    }

    public function getDateSortie(): ?\DateTime
    {
        return $this->date_sortie;
    }

    public function setDateSortie(\DateTime $date_sortie): static
    {
        $this->date_sortie = $date_sortie;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getStatutDuJour(): ?string
    {
        return $this->statut_du_jour;
    }

    public function setStatutDuJour(string $statut_du_jour): static
    {
        $this->statut_du_jour = $statut_du_jour;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): static
    {
        $this->chambre = $chambre;

        return $this;
    }

}
