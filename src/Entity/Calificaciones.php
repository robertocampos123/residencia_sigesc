<?php

namespace App\Entity;

use App\Repository\CalificacionesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalificacionesRepository::class)]
class Calificaciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $periodo_uno = null;

    #[ORM\Column(nullable: true)]
    private ?float $periodo_dos = null;

    #[ORM\Column(nullable: true)]
    private ?float $periodo_tres = null;

    #[ORM\ManyToOne(inversedBy: 'calificaciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Alumno $alumno = null;

    #[ORM\ManyToOne(inversedBy: 'calificaciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Grupo $grupo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeriodoUno(): ?float
    {
        return $this->periodo_uno;
    }

    public function setPeriodoUno(?float $periodo_uno): self
    {
        $this->periodo_uno = $periodo_uno;

        return $this;
    }

    public function getPeriodoDos(): ?float
    {
        return $this->periodo_dos;
    }

    public function setPeriodoDos(?float $periodo_dos): self
    {
        $this->periodo_dos = $periodo_dos;

        return $this;
    }

    public function getPeriodoTres(): ?float
    {
        return $this->periodo_tres;
    }

    public function setPeriodoTres(?float $periodo_tres): self
    {
        $this->periodo_tres = $periodo_tres;

        return $this;
    }

    public function getAlumno(): ?Alumno
    {
        return $this->alumno;
    }

    public function setAlumno(?Alumno $alumno): self
    {
        $this->alumno = $alumno;

        return $this;
    }

    public function getGrupo(): ?Grupo
    {
        return $this->grupo;
    }

    public function setGrupo(?Grupo $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }
}
