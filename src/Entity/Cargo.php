<?php

namespace App\Entity;

use App\Repository\CargoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CargoRepository::class)]
class Cargo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $clave_cargo = null;

    #[ORM\Column(length: 30)]
    private ?string $nombre_cargo = null;

    #[ORM\OneToMany(mappedBy: 'cargo', targetEntity: Empleado::class)]
    private Collection $empleados;

    public function __construct()
    {
        $this->empleados = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClaveCargo(): ?string
    {
        return $this->clave_cargo;
    }

    public function setClaveCargo(string $clave_cargo): self
    {
        $this->clave_cargo = $clave_cargo;

        return $this;
    }

    public function getNombreCargo(): ?string
    {
        return $this->nombre_cargo;
    }

    public function setNombreCargo(string $nombre_cargo): self
    {
        $this->nombre_cargo = $nombre_cargo;

        return $this;
    }

    /**
     * @return Collection<int, Empleado>
     */
    public function getEmpleados(): Collection
    {
        return $this->empleados;
    }

    public function addEmpleado(Empleado $empleado): self
    {
        if (!$this->empleados->contains($empleado)) {
            $this->empleados->add($empleado);
            $empleado->setCargo($this);
        }

        return $this;
    }

    public function removeEmpleado(Empleado $empleado): self
    {
        if ($this->empleados->removeElement($empleado)) {
            // set the owning side to null (unless already changed)
            if ($empleado->getCargo() === $this) {
                $empleado->setCargo(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->nombre_cargo;
    }
}
