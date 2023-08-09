<?php

namespace App\Entity;

use App\Repository\PeriodoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodoRepository::class)]
class Periodo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6)]
    private ?string $clave_periodo = null;

    #[ORM\Column(length: 30)]
    private ?string $nombre_periodo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $inicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fin = null;

    #[ORM\OneToMany(mappedBy: 'parcial', targetEntity: Seguimiento::class)]
    private Collection $seguimientos;

    public function __construct()
    {
        $this->seguimientos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClavePeriodo(): ?string
    {
        return $this->clave_periodo;
    }

    public function setClavePeriodo(string $clave_periodo): self
    {
        $this->clave_periodo = $clave_periodo;

        return $this;
    }

    public function getNombrePeriodo(): ?string
    {
        return $this->nombre_periodo;
    }

    public function setNombrePeriodo(string $nombre_periodo): self
    {
        $this->nombre_periodo = $nombre_periodo;

        return $this;
    }

    public function getInicio(): ?\DateTimeInterface
    {
        return $this->inicio;
    }

    public function setInicio(\DateTimeInterface $inicio): self
    {
        $this->inicio = $inicio;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * @return Collection<int, Seguimiento>
     */
    public function getSeguimientos(): Collection
    {
        return $this->seguimientos;
    }

    public function addSeguimiento(Seguimiento $seguimiento): self
    {
        if (!$this->seguimientos->contains($seguimiento)) {
            $this->seguimientos->add($seguimiento);
            $seguimiento->setParcial($this);
        }

        return $this;
    }

    public function removeSeguimiento(Seguimiento $seguimiento): self
    {
        if ($this->seguimientos->removeElement($seguimiento)) {
            // set the owning side to null (unless already changed)
            if ($seguimiento->getParcial() === $this) {
                $seguimiento->setParcial(null);
            }
        }

        return $this;
    }
}
