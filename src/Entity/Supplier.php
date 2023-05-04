<?php

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SupplierRepository::class)
 */
class Supplier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * @Assert\NotBlank(message="Name cannot be empty")
     * @Assert\Length(max=255, maxMessage="Name cannot be longer than {{ limit }} characters")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

   /**
     * @Assert\NotBlank(message="Type cannot be empty")
     * @Assert\Length(max=255, maxMessage="Type cannot be longer than {{ limit }} characters")
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @Assert\NotBlank(message="Address cannot be empty")
     * @Assert\Length(max=255, maxMessage="Address cannot be longer than {{ limit }} characters")
     * @ORM\Column(type="string", length=255)
     */
    private $address;
    /**
     * @Assert\NotBlank(message="Phone number cannot be empty")
     * @Assert\Length(max=10, maxMessage="Phone number cannot be longer than {{ limit }} digits")
     * @ORM\Column(type="integer")
     */
    private $phoneNumber;

   /**
     * @Assert\NotBlank(message="Email cannot be empty")
     * @Assert\Email(message="The email is not a valid email address.")
     * @Assert\Length(max=255, maxMessage="Email cannot be longer than {{ limit }} characters")
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Purchase::class, mappedBy="supplier")
     */
    private $purchases;

    public function __construct()
    {
        $this->purchases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Purchase>
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): self
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases[] = $purchase;
            $purchase->setSupplier($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): self
    {
        if ($this->purchases->removeElement($purchase)) {
            // set the owning side to null (unless already changed)
            if ($purchase->getSupplier() === $this) {
                $purchase->setSupplier(null);
            }
        }

        return $this;
    }
}
