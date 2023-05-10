<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmployeesRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EmployeesRepository::class)
 */
class Employees
{
    /**
     * @ORM\Id
     * @Groups("employe")
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("employe")
     */
    private $cin;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("employe")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("employe")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("employe")
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     * @Groups("employe")
     */
    private $phoneNum;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $idComp;




    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getphoneNum(): ?int
    {
        return $this->phoneNum;
    }

    public function setphoneNum(int $phoneNum): self
    {
        $this->phoneNum = $phoneNum;

        return $this;
    }





public function __toString()
{
    return $this->getNom();
}

public function getIdComp(): ?User
{
    return $this->idComp;
}

public function setIdComp(?User $idComp): self
{
    $this->idComp = $idComp;

    return $this;
}

}
