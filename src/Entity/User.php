<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true , nullable=false) 
     * @Groups("post:read")
     
     */

    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Assert\Regex(
     *     pattern="/^(?=.*[A-Z])(?=.*[a-z])(?=.*\W).+$/",
     *     message="The password must contain at least one uppercase letter, one lowercase letter, and one special character." )
      * @Groups("post:read")
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
      * @Groups("post:read")
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255)
    * @Groups("post:read")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
      * @Groups("post:read")
     */
    private $domaine;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $pays;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     * @Assert\NotBlank(message="Vous devez insérer votre numéro de téléphone.")
     * @Assert\Regex(
     *     pattern="/^(9[0-9]|5[0-9]|2[0-9]|7[0-9])/i",
     *     match=true,
     *     message="le numero de telephone doit etre valide pour les operatueurs tunisiens "
     * )
     * @Assert\Length(
    *      min = 8,
    *      minMessage=" min length 8 caracteres " )
    * @Assert\Length(
    *      max = 8,
    *      maxMessage=" max length 8 caracteres "
    *
    *     )
    * @Groups("post:read")
     */
    private $telephone;
    
    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message=" name cannot be empty ")
    * @Assert\Length(
    *      min = 2,
    *      minMessage=" min length 2 caracteres ")
    * @Assert\Regex(
    *    pattern ="/\d/",
    *   match=false,
    *  message="Your name cannot contain a number",
    *     )
     * @Groups("post:read")
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Abonnement::class, cascade={"persist", "remove"})
     * @Groups("post:read")
     */
    private $abonnement;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $statuts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $activationToken;


     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $resetToken;

    public function getId(): ?int
    {
        return $this->id;
    }

      /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Rh::class, mappedBy="id_company")
     */
    private $rhs;

    public function __construct()
    {
        $this->rhs = new ArrayCollection();
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAbonnement(): ?abonnement
    {
        return $this->abonnement;
    }

    public function setAbonnement(?abonnement $abonnement): self
    {
        $this->abonnement = $abonnement;

        return $this;
    }

    public function getStatuts(): ?string
    {
        return $this->statuts;
    }

    public function setStatuts(string $statuts): self
    {
        $this->statuts = $statuts;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    
    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    public function setActivationToken(?string $activationToken): self
    {
        $this->activationToken = $activationToken;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Rh>
     */
    public function getRhs(): Collection
    {
        return $this->rhs;
    }

    public function addRh(Rh $rh): self
    {
        if (!$this->rhs->contains($rh)) {
            $this->rhs[] = $rh;
            $rh->setIdCompany($this);
        }

        return $this;
    }

    public function removeRh(Rh $rh): self
    {
        if ($this->rhs->removeElement($rh)) {
            // set the owning side to null (unless already changed)
            if ($rh->getIdCompany() === $this) {
                $rh->setIdCompany(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return  $this->nom; 
      }
}