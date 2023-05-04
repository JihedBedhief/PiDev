<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DivisionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DivisionRepository::class)
 */
class Division
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("div")
     */
    private $id;

    /**
     * @Assert\NotBlank(message=" type cannot be empty ")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" min length 3 caractéres  "
     *     )
     * @ORM\Column(type="string", length=255)
     * @Groups("div")
     */
    private $type;

    /**
     * @Assert\NotBlank(message=" taux remis cannot be empty ")
     * @Assert\Length(
     *      min = 1,
     *      minMessage=" min length 1 chiffre "
     *     )
     * @ORM\Column(type="float")
     * @Groups("div")
     */
    private $taux_remise;

    /**
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="division",cascade={"persist", "remove"})
     * @Groups("div")
     */
    private $clients;

    
    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

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

    public function getTauxRemise(): ?float
    {
        return $this->taux_remise;
    }

    public function setTauxRemise(float $taux_remise): self
    {
        $this->taux_remise = $taux_remise;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setDivision($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getDivision() === $this) {
                $client->setDivision(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return  $this->type; 
      }
}
