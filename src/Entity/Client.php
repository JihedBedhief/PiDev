<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity(fields={"cin"},message="un client dèja existe avec cet cin")
 */
class Client
{
    /**
     * @ORM\Id
     
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     
     * @Assert\NotBlank(message=" name cannot be empty ")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" min length 5 caracteres "
     *     )
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $name;

    /**
     
     * @Assert\NotBlank(message=" telephone cannot be empty ")
     * @Assert\Length(
     *      min = 8,
     *      minMessage=" min length 8 caracteres "
     *     )
     * @Assert\Length(
     *      max = 8,
     *      maxMessage=" max length 8 caracteres "
     *     )
     * @Assert\Regex(
     *     pattern="/^(9[0-9]|5[0-9]|2[0-9]|7[0-9])/i",
     *     match=true,
     *     message="le numero de telephone doit etre valide pour les operatueurs tunisiens ")
     * @ORM\Column(type="integer", length=255)
     * @Groups("post:read")
     */
    private $telephone;

    /**
     
     * @Assert\NotBlank(message=" email cannot be empty ")
     * @Assert\Length(
     *      min = 8,
     *      minMessage=" min length 8 caracteres "
     *     )
     * @Assert\Email( message=" email should be valid ")
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $email;

    /**
     * @Assert\NotBlank(message=" ville cannot be empty ")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" min length 5 caracteres "
     *     )
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $ville;
    
    /**
     
     * @Assert\NotBlank(message=" code postal cannot be empty ")
     * @Assert\Length(
     *      min = 4,
     *      minMessage=" min length 4 caracteres "
     *     )
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $code_postal;

    /**
     
     * @Assert\NotBlank(message=" cin cannot be empty ")
     * @Assert\Length(
     *      min = 8,
     *      minMessage=" length should be equal to 8 "
     *     )
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $cin;
    
    /**
     * @Assert\LessThanOrEqual("today",message="date incorrecte")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("post:read")
     */
    private $DateAjout;

    /**
     * @Assert\NotBlank(message=" division cannot be empty ")
     * @ORM\ManyToOne(targetEntity=Division::class, inversedBy="clients")
     * @Groups("post:read")
     */
    private $division;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $Status;

    /**
     * @ORM\OneToMany(targetEntity=Vente::class, mappedBy="client",cascade={"persist", "remove"})
     * @Groups("post:read")
     */
    private $vente;

    public function __construct()
    {
        $this->vente = new ArrayCollection();
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getDivision(): ?Division
    {
        return $this->division;
    }

    public function setDivision(?Division $division): self
    {
        $this->division = $division;
        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->DateAjout;
    }

    public function setDateAjout(?\DateTimeInterface $DateAjout): self
    {
        $this->DateAjout = $DateAjout;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }
    

    /**
     * @return Collection<int, Vente>
     */
    public function getVente(): Collection
    {
        return $this->vente;
    }

    public function addVente(Vente $vente): self
    {
        if (!$this->vente->contains($vente)) {
            $this->vente[] = $vente;
            $vente->setClient($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): self
    {
        if ($this->vente->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getClient() === $this) {
                $vente->setClient(null);
            }
        }

        return $this;
    }
public function __toString(){
        return  $this->name; 
      }
}
