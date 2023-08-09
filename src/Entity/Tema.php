<?php

namespace App\Entity;

use App\Repository\TemaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemaRepository::class)]
class Tema
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $numero_unidad = null;

    #[ORM\Column(length: 45)]
    private ?string $nombre_unidad = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $subtemas = null;

    #[ORM\ManyToOne(inversedBy: 'temas')]
    private ?Materia $materia = null;

    #[ORM\OneToMany(mappedBy: 'tema', targetEntity: Seguimiento::class)]
    private Collection $seguimientos;

    public function __construct()
    {
        $this->seguimientos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroUnidad(): ?int
    {
        return $this->numero_unidad;
    }

    public function setNumeroUnidad(int $numero_unidad): self
    {
        $this->numero_unidad = $numero_unidad;

        return $this;
    }

    public function getNombreUnidad(): ?string
    {
        return $this->nombre_unidad;
    }

    public function setNombreUnidad(string $nombre_unidad): self
    {
        $this->nombre_unidad = $nombre_unidad;

        return $this;
    }

    public function getSubtemas(): ?string
    {
        return $this->subtemas;
    }

    public function setSubtemas(string $subtemas): self
    {
        $this->subtemas = $subtemas;

        return $this;
    }

    public function getMateria(): ?Materia
    {
        return $this->materia;
    }

    public function setMateria(?Materia $materia): self
    {
        $this->materia = $materia;

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
            $seguimiento->setTema($this);
        }

        return $this;
    }

    public function removeSeguimiento(Seguimiento $seguimiento): self
    {
        if ($this->seguimientos->removeElement($seguimiento)) {
            // set the owning side to null (unless already changed)
            if ($seguimiento->getTema() === $this) {
                $seguimiento->setTema(null);
            }
        }

        return $this;
    }

  
}
