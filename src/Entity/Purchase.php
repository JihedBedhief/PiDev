<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 */
class Purchase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
      * @Assert\NotBlank(message=" Product cannot be empty ")
     * @Assert\Length(
     *      min = 4,
     *      minMessage=" min length 4 caracteres "
     *     )
      * @Assert\Length(
     *      max = 10,
     *      maxMessage=" max length 10 caracteres "
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $product;

   /**
     * @Assert\NotNull(message="Quantity cannot be empty")
     * @Assert\GreaterThan(value=0, message="Quantity should be greater than 0")
     * @ORM\Column(type="integer")
     */
    private $qte;

   /**
     * @Assert\NotNull(message="Unit price cannot be empty")
     * @Assert\GreaterThan(value=0, message="Unit price should be greater than 0")
     * @ORM\Column(type="float")
     */
    private $unitPrice;

    /**
     * @Assert\NotNull(message="Purchase date cannot be empty")
     * @Assert\LessThanOrEqual("today", message="Purchase date should be less than or equal to today")
     * @ORM\Column(type="date")
     */
    private $puchaseDate;

    /**
     * @Assert\NotNull(message="Tax rate cannot be empty")
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      notInRangeMessage = "Tax rate should be between {{ min }}% and {{ max }}%",
     * )
     * @ORM\Column(type="float")
     */
    private $taxeRate;
    /**
     * @Assert\NotNull(message="Supplier cannot be empty")
     * @ORM\ManyToOne(targetEntity=Supplier::class, inversedBy="purchases")
     */
    private $supplier;
    public function __toString(){
        return  $this->supplier; 
      }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getPuchaseDate(): ?\DateTimeInterface
    {
        return $this->puchaseDate;
    }

    public function setPuchaseDate(\DateTimeInterface $puchaseDate): self
    {
        $this->puchaseDate = $puchaseDate;

        return $this;
    }

    public function getTaxeRate(): ?float
    {
        return $this->taxeRate;
    }

    public function setTaxeRate(float $taxeRate): self
    {
        $this->taxeRate = $taxeRate;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }
}
