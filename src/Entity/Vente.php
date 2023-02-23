<?php

namespace App\Entity;
use App\Entity\PointVente;
use App\Repository\VenteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: VenteRepository::class)]
class Vente
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank (message:"Vous devez entrer un produit")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $produit = null;
    
    #[Assert\GreaterThan(0)]
    #[Assert\Positive]
    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;


    #[ORM\Column(nullable: true)]
    private ?float $prix_unite = null;

    #[ORM\Column(nullable: true)]
    private ?float $taxe = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_totale = null;

    
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]    
    #[Assert\LessThanOrEqual('today',message:"date incorrecte") ]
    #[Assert\NotBlank (message:"vous devez entrer une date")]
    private ?\DateTimeInterface $date_vente = null;
    

    
    #[Assert\NotBlank (message:"Vous devez entrer un client")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $client = null;

    #[ORM\ManyToOne(inversedBy: 'Vente', cascade:["persist", "remove"])]
    private ?PointVente $PointVente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?string
    {
        return $this->produit;
    }

    public function setProduit(?string $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixUnite(): ?float
    {
        return $this->prix_unite;
    }

    public function setPrixUnite(?float $prix_unite): self
    {
        $this->prix_unite = $prix_unite;

        return $this;
    }

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(?float $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getPrixTotale(): ?float
    {
        return $this->prix_totale;
    }

    public function setPrixTotale(?float $prix_totale): self
    {
        $this->prix_totale = $prix_totale;

        return $this;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->date_vente;
    }

    public function setDateVente(?\DateTimeInterface $date_vente): self
    {
        $this->date_vente = $date_vente;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(?string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getPointVente(): ?PointVente
    {
        return $this->PointVente;
    }

    public function setPointVente(?PointVente $PointVente): self
    {
        $this->PointVente = $PointVente;

        return $this;
    }
}
