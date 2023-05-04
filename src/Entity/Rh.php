<?php

namespace App\Entity;

use App\Repository\RhRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RhRepository::class)
 */
class Rh
{
    /**
     * @ORM\Id
    * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message=" Name cannot be empty ")
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
    private $name;

    /**
     * @Assert\NotBlank(message=" fonction cannot be empty ")
    * @Assert\Regex(
 *      pattern="/^[a-zA-Z]*$/",
 *      message="fonction must start with a letter and contain only letters"
 * )
     * @ORM\Column(type="string", length=255)
     */
    private $fonction;

    /**
     * @Assert\NotBlank(message=" departement cannot be empty ")
     * @Assert\Regex(
 *      pattern="/^[a-zA-Z][a-zA-Z0-9]*$/",
 *      message="Departement must start with a letter and contain only letters and numbers"
 * )
     * @ORM\Column(type="string", length=255)
     */
    private $departement;

    /**
     * @ORM\OneToOne(targetEntity=Contract::class, mappedBy="employer", cascade={"persist", "remove"})
     */
    private $contract;

    /**
 * @Assert\Email(
 *     message="The email is not a valid email address."
 * )
 * @Assert\NotBlank(message=" Email  cannot be empty ")
 * @Assert\Regex(
 *     pattern="/@gmail\.com$/i",
 *     message="The email address must belong to the gmail.com domain."
 * )
 * @ORM\Column(type="string", length=255)
 */
    private $email;

    /**
      * @Assert\Positive
    * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^(9[0-9]|5[0-9]|2[0-9]|7[0-9])/i",
     *     match=true,
     *     message="The number must start with one of the designated ranges"
     * )
 * @Assert\Length(
     *      min = 8,
     *      minMessage=" min length 8 caracteres "
     *     )
      * @Assert\Length(
     *      max = 8,
     *      maxMessage=" max length 8 caracteres "
     *     )
 * @ORM\Column(type="integer")
 */
    private $phone_number;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rhs" , cascade={"persist", "remove"})
     */
    private $id_company;

    

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

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        // unset the owning side of the relation if necessary
        if ($contract === null && $this->contract !== null) {
            $this->contract->setEmployer(null);
        }

        // set the owning side of the relation if necessary
        if ($contract !== null && $contract->getEmployer() !== $this) {
            $contract->setEmployer($this);
        }

        $this->contract = $contract;

        return $this;
    }
    public function __toString(){
        return  $this->name; 
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

    public function getPhoneNumber(): ?int
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(int $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getIdCompany(): ?User
    {
        return $this->id_company;
    }

    public function setIdCompany(?User $id_company): self
    {
        $this->id_company = $id_company;

        return $this;
    }

    
}
