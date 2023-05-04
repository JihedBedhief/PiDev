<?php

namespace App\Entity;

use App\Entity\PointVente;
use App\Entity\Product;
use App\Repository\VenteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Ignore;
use Doctrine\DBAL\Types\Type;



/**
 * @ORM\Entity(repositoryClass=VenteRepository::class)
 */
class Vente
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"vente:read"})
     */
    private ?int $id;

   

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"vente:read"})
     * @Assert\GreaterThan(value=0)
     * @Assert\Positive
     */
    private  $quantite;


    /**
     * @ORM\Column(type="float",nullable=true)
     * @Groups({"vente:read"})
     */
    private  $prix_unite;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"vente:read"})
     */
    private  $taxe;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"vente:read"})
     */
    private  $prix_totale;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\LessThanOrEqual("today", message="date incorrecte")
     * @Assert\NotBlank(message="vous devez entrer une date")
     * @Groups({"vente:read"})
     */
    private  $date_vente;


    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="PointVente", inversedBy="ventes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"vente:read"})
     */
    private ?PointVente $PointVente;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="vente")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="ventes",cascade={"persist", "remove"})
     */
    private ?Product $produit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Product
    {
        return $this->produit;
    }

    public function setProduit(?Product $produit): self
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

    

    public function getPointVente(): ?PointVente
    {
        return $this->PointVente;
    }

    public function setPointVente(?PointVente $PointVente): self
    {
        $this->PointVente = $PointVente;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
