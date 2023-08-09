<?php

namespace App\Entity;

use App\Repository\AlumnoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlumnoRepository::class)]
class Alumno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8,  unique: true)]
    private ?string $numero_control = null;

    #[ORM\Column(length: 30)]
    private ?string $nombre_alumno = null;

    #[ORM\Column(length: 30)]
    private ?string $apellido_paterno = null;

    #[ORM\Column(length: 30)]
    private ?string $apellido_materno = null;

    #[ORM\ManyToOne(inversedBy: 'alumnos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carrera $carrera = null;

    #[ORM\ManyToMany(targetEntity: Grupo::class, mappedBy: 'alumnos')]
    private Collection $grupos;

    #[ORM\OneToMany(mappedBy: 'alumno', targetEntity: Calificaciones::class, orphanRemoval: true)]
    private Collection $calificaciones;

    public function __construct()
    {
        $this->grupos = new ArrayCollection();
        $this->calificaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroControl(): ?string
    {
        return $this->numero_control;
    }

    public function setNumeroControl(string $numero_control): self
    {
        $this->numero_control = $numero_control;

        return $this;
    }

    public function getNombreAlumno(): ?string
    {
        return $this->nombre_alumno;
    }

    public function setNombreAlumno(string $nombre_alumno): self
    {
        $this->nombre_alumno = $nombre_alumno;

        return $this;
    }

    public function getApellidoPaterno(): ?string
    {
        return $this->apellido_paterno;
    }

    public function setApellidoPaterno(string $apellido_paterno): self
    {
        $this->apellido_paterno = $apellido_paterno;

        return $this;
    }

    public function getApellidoMaterno(): ?string
    {
        return $this->apellido_materno;
    }

    public function setApellidoMaterno(string $apellido_materno): self
    {
        $this->apellido_materno = $apellido_materno;

        return $this;
    }

    public function getCarrera(): ?Carrera
    {
        return $this->carrera;
    }

    public function setCarrera(?Carrera $carrera): self
    {
        $this->carrera = $carrera;

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
            $grupo->addAlumno($this);
        }

        return $this;
    }

    public function removeGrupo(Grupo $grupo): self
    {
        if ($this->grupos->removeElement($grupo)) {
            $grupo->removeAlumno($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Calificaciones>
     */
    public function getCalificaciones(): Collection
    {
        return $this->calificaciones;
    }

    public function addCalificacione(Calificaciones $calificacione): self
    {
        if (!$this->calificaciones->contains($calificacione)) {
            $this->calificaciones->add($calificacione);
            $calificacione->setAlumno($this);
        }

        return $this;
    }

    public function removeCalificacione(Calificaciones $calificacione): self
    {
        if ($this->calificaciones->removeElement($calificacione)) {
            // set the owning side to null (unless already changed)
            if ($calificacione->getAlumno() === $this) {
                $calificacione->setAlumno(null);
            }
        }

        return $this;
    }
}
