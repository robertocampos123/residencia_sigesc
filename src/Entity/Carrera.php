<?php

namespace App\Entity;

use App\Repository\CarreraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarreraRepository::class)]
class Carrera
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5,unique: true)]
    private ?string $clave_carrera = null;

    #[ORM\Column(length: 40)]
    private ?string $nombre_carrera = null;

    #[ORM\ManyToOne(inversedBy: 'carreras')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Departamento $departamento = null;

    #[ORM\ManyToMany(targetEntity: Materia::class, mappedBy: 'carrera')]
    private Collection $materias;

    #[ORM\OneToMany(mappedBy: 'carrera', targetEntity: Alumno::class)]
    private Collection $alumnos;

    #[ORM\OneToMany(mappedBy: 'carrera', targetEntity: Grupo::class, orphanRemoval: true)]
    private Collection $grupos;

    public function __construct()
    {
        $this->materias = new ArrayCollection();
        $this->alumnos = new ArrayCollection();
        $this->grupos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClaveCarrera(): ?string
    {
        return $this->clave_carrera;
    }

    public function setClaveCarrera(string $clave_carrera): self
    {
        $this->clave_carrera = $clave_carrera;

        return $this;
    }

    public function getNombreCarrera(): ?string
    {
        return $this->nombre_carrera;
    }

    public function setNombreCarrera(string $nombre_carrera): self
    {
        $this->nombre_carrera = $nombre_carrera;

        return $this;
    }

    public function getDepartamento(): ?Departamento
    {
        return $this->departamento;
    }

    public function setDepartamento(?Departamento $departamento): self
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * @return Collection<int, Materia>
     */
    public function getMaterias(): Collection
    {
        return $this->materias;
    }

    public function addMateria(Materia $materia): self
    {
        if (!$this->materias->contains($materia)) {
            $this->materias->add($materia);
            $materia->addCarrera($this);
        }

        return $this;
    }

    public function removeMateria(Materia $materia): self
    {
        if ($this->materias->removeElement($materia)) {
            $materia->removeCarrera($this);
        }

        return $this;
    }
    public function __toString() {
        return $this->nombre_carrera;
    }

    /**
     * @return Collection<int, Alumno>
     */
    public function getAlumnos(): Collection
    {
        return $this->alumnos;
    }

    public function addAlumno(Alumno $alumno): self
    {
        if (!$this->alumnos->contains($alumno)) {
            $this->alumnos->add($alumno);
            $alumno->setCarrera($this);
        }

        return $this;
    }

    public function removeAlumno(Alumno $alumno): self
    {
        if ($this->alumnos->removeElement($alumno)) {
            // set the owning side to null (unless already changed)
            if ($alumno->getCarrera() === $this) {
                $alumno->setCarrera(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Grupo>
     */
    public function getGrupos(): Collection
    {
        return $this->grupos;
    }

    public function addGrupo(Grupo $grupo): self
    {
        if (!$this->grupos->contains($grupo)) {
            $this->grupos->add($grupo);
            $grupo->setCarrera($this);
        }

        return $this;
    }

    public function removeGrupo(Grupo $grupo): self
    {
        if ($this->grupos->removeElement($grupo)) {
            // set the owning side to null (unless already changed)
            if ($grupo->getCarrera() === $this) {
                $grupo->setCarrera(null);
            }
        }

        return $this;
    }
}
