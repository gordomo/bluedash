<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellido;

    /**
     * @ORM\OneToOne(targetEntity=Balance::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $balance;

    /**
     * @ORM\OneToMany(targetEntity=Movimientos::class, mappedBy="user", orphanRemoval=true)
     */
    private $movimientos;

    /**
     * @ORM\OneToMany(targetEntity=Participaciones::class, mappedBy="user", orphanRemoval=true)
     */
    private $participaciones;

    public function __construct()
    {
        $this->movimientos = new ArrayCollection();
        $this->participaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getBalance(): ?Balance
    {
        return $this->balance;
    }

    public function setBalance(Balance $balance): self
    {
        // set the owning side of the relation if necessary
        if ($balance->getUser() !== $this) {
            $balance->setUser($this);
        }

        $this->balance = $balance;

        return $this;
    }

    /**
     * @return Collection<int, Movimientos>
     */
    public function getMovimientos(): Collection
    {
        return $this->movimientos;
    }

    public function addMovimiento(Movimientos $movimiento): self
    {
        if (!$this->movimientos->contains($movimiento)) {
            $this->movimientos[] = $movimiento;
            $movimiento->setUser($this);
        }

        return $this;
    }

    public function removeMovimiento(Movimientos $movimiento): self
    {
        if ($this->movimientos->removeElement($movimiento)) {
            // set the owning side to null (unless already changed)
            if ($movimiento->getUser() === $this) {
                $movimiento->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participaciones>
     */
    public function getParticipaciones(): Collection
    {
        return $this->participaciones;
    }

    public function addParticipacione(Participaciones $participacione): self
    {
        if (!$this->participaciones->contains($participacione)) {
            $this->participaciones[] = $participacione;
            $participacione->setUser($this);
        }

        return $this;
    }

    public function removeParticipacione(Participaciones $participacione): self
    {
        if ($this->participaciones->removeElement($participacione)) {
            // set the owning side to null (unless already changed)
            if ($participacione->getUser() === $this) {
                $participacione->setUser(null);
            }
        }

        return $this;
    }
}
