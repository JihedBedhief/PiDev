<?php

namespace App\Entity;

use App\Repository\ContractRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContractRepository::class)
 */
class Contract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @Assert\NotBlank(message=" type cannot be empty ")
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @Assert\NotBlank(message=" duration cannot be empty ")
     * @Assert\GreaterThan(0)
   
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @Assert\NotBlank(message=" salary cannot be empty ")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" min length 3 caracteres "
     *     )
      * @Assert\Length(
     *      max = 4,
     *      maxMessage=" max length 4 caracteres "
     *     )
     * @Assert\GreaterThan(400)
     * @ORM\Column(type="float")
     */
    private $salary;

    /**
     * @Assert\NotBlank(message=" salary cannot be empty ")
    
     * @ORM\OneToOne(targetEntity=Rh::class, inversedBy="contract", cascade={"persist", "remove"})
     */
    private $employer;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getEmployer(): ?Rh
    {
        return $this->employer;
    }

    public function setEmployer(?Rh $employer): self
    {
        $this->employer = $employer;

        return $this;
    }
}
