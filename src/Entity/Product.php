<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * Groups("products")
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *  Groups("products")
     * @Assert\NotBlank(message=" nom doit etre non vide")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" Entrer un nom au mini de 3 caracteres"
     *     )
     *  @Assert\Regex(
     *     pattern="/^[A-Z][a-z]*$/",
     *     message="Le nom doit commencer par une majuscule."
     *   )
     * @ORM\Column(type="string", length=255)
     */
    private $name_product;

    /**
     * Groups("products")
     * @Assert\NotBlank(message=" quantite doit etre non vide")
     * @Assert\GreaterThan(0)
     * @ORM\Column(type="string", length=1000)
     */
    private $quantite;

    /**
    * Groups("products")
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * Groups("products")
     * @Assert\NotBlank(message="prix doit etre non vide")
     * @Assert\GreaterThan(0)
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * Groups("products")
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * Groups("products")
     * @Assert\NotBlank(message="taxe rate doit etre non vide")
     * @Assert\GreaterThan(0)
     * @ORM\Column(type="float")
     */
    private $taxe;

    /**
     * @ORM\OneToMany(targetEntity=Vente::class, mappedBy="produit")
     */
    private $ventes;

    public function __construct()
    {
        $this->ventes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameProduct(): ?string
    {
        return $this->name_product;
    }

    public function setNameProduct(string $name_product): self
    {
        $this->name_product = $name_product;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(float $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    /**
     * @return Collection<int, Vente>
     */
    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(Vente $vente): self
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes[] = $vente;
            $vente->setProduit($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): self
    {
        if ($this->ventes->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getProduit() === $this) {
                $vente->setProduit(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return  $this->name_product; 
      }
}
