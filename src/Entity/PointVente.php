<?php

namespace App\Entity;

use App\Repository\PointVenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PointVenteRepository::class)
 */

class PointVente
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"pointVente:read"})
     */
    private $id;

    /**
     * @Assert\NotBlank(message="donner le nom du point de vente")
     * @ORM\Column(type="string",length=255, nullable=true)
     * @Groups({"pointVente:read"})
     */
    private $name;


    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     * @Groups({"pointVente:read"})
     */
    private $region;

    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     * @Groups({"pointVente:read"})
     */
    private  $ville;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"pointVente:read"})
     */
    private  $code_postal;

    /**
     * @Assert\Length(
     *     min=8,
     *     max=8,
     *     minMessage="Your first name must be at least {{ limit }} characters long",
     *     maxMessage="Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern="/^(9[0-9]|5[0-9]|2[0-9]|7[0-9])/i",
     *     match=true,
     *     message="le numero de telephone doit etre valide pour les operatueurs tunisiens"
     * )
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"pointVente:read"})
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity=Vente::class,mappedBy="PointVente", cascade={"persist", "remove"})
     * @Groups({"pointVente:read"})
     */
    private $ventes;

    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     * @Assert\Email(message="le format de mail est incorrecte")
     * @Groups({"pointVente:read"})
     */
    private  $email ;

    public function __construct()
    {
        $this->ventes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): self
    {
        $this->telephone = $telephone;

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
            $this->ventes->add($vente);
            $vente->setPointVente($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): self
    {
        if ($this->ventes->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getPointVente() === $this) {
                $vente->setPointVente(null);
            }
        }
        return $this;
    }
    public function __toString()
    {
        return  $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
}
