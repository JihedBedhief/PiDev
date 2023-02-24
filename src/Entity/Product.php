<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
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
     * @Assert\NotBlank(message=" quantite doit etre non vide")
     * @Assert\GreaterThan(0)
     * @ORM\Column(type="string", length=1000)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @Assert\NotBlank(message="prix doit etre non vide")
     * @Assert\GreaterThan(0)
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

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
}
