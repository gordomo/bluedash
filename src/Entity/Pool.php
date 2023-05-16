<?php

namespace App\Entity;

use App\Repository\PoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PoolRepository::class)
 */
class Pool
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $inversionTotal;

    /**
     * @ORM\Column(type="float")
     */
    private $inversionActual;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaInicio;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="float")
     */
    private $inversionMinima;

    /**
     * @ORM\OneToMany(targetEntity=Participaciones::class, mappedBy="pool", orphanRemoval=true)
     */
    private $participaciones;

    public function __construct()
    {
        $this->participaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInversionTotal(): ?float
    {
        return $this->inversionTotal;
    }

    public function setInversionTotal(float $inversionTotal): self
    {
        $this->inversionTotal = $inversionTotal;

        return $this;
    }

    public function getInversionActual(): ?float
    {
        return $this->inversionActual;
    }

    public function setInversionActual(float $inversionActual): self
    {
        $this->inversionActual = $inversionActual;

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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fechaInicio): self
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getInversionMinima(): ?float
    {
        return $this->inversionMinima;
    }

    public function setInversionMinima(float $inversionMinima): self
    {
        $this->inversionMinima = $inversionMinima;

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
            $participacione->setPool($this);
        }

        return $this;
    }

    public function removeParticipacione(Participaciones $participacione): self
    {
        if ($this->participaciones->removeElement($participacione)) {
            // set the owning side to null (unless already changed)
            if ($participacione->getPool() === $this) {
                $participacione->setPool(null);
            }
        }

        return $this;
    }
}
