<?php

namespace App\Entity;

use App\Repository\DepartamentoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartamentoRepository::class)]
class Departamento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5, unique: true)]
    private ?string $clave_departamento = null;

    #[ORM\Column(length: 40)]
    private ?string $nombre_departamento = null;

    #[ORM\OneToMany(mappedBy: 'departamento', targetEntity: Empleado::class)]
    private Collection $empleados;

    #[ORM\OneToMany(mappedBy: 'departamento', targetEntity: Carrera::class)]
    private Collection $carreras;

    public function __construct()
    {
        $this->empleados = new ArrayCollection();
        $this->carreras = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClaveDepartamento(): ?string
    {
        return $this->clave_departamento;
    }

    public function setClaveDepartamento(string $clave_departamento): self
    {
        $this->clave_departamento = $clave_departamento;

        return $this;
    }

    public function getNombreDepartamento(): ?string
    {
        return $this->nombre_departamento;
    }

    public function setNombreDepartamento(string $nombre_departamento): self
    {
        $this->nombre_departamento = $nombre_departamento;

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
            $empleado->setDepartamento($this);
        }

        return $this;
    }

    public function removeEmpleado(Empleado $empleado): self
    {
        if ($this->empleados->removeElement($empleado)) {
            // set the owning side to null (unless already changed)
            if ($empleado->getDepartamento() === $this) {
                $empleado->setDepartamento(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Carrera>
     */
    public function getCarreras(): Collection
    {
        return $this->carreras;
    }

    public function addCarrera(Carrera $carrera): self
    {
        if (!$this->carreras->contains($carrera)) {
            $this->carreras->add($carrera);
            $carrera->setDepartamento($this);
        }

        return $this;
    }

    public function removeCarrera(Carrera $carrera): self
    {
        if ($this->carreras->removeElement($carrera)) {
            // set the owning side to null (unless already changed)
            if ($carrera->getDepartamento() === $this) {
                $carrera->setDepartamento(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->nombre_departamento;
    }
}
