<?php

namespace App\Entity;

use App\Repository\SeguimientoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeguimientoRepository::class)]
class Seguimiento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha_prog_inicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha_prog_fin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha_real_inicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha_real_fin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $evaluacion_programada = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $evaluacion_real = null;

    #[ORM\Column(nullable: true)]
    private ?float $porcentaje_aprobacion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $observaciones = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $evidencia = null;

    #[ORM\ManyToOne(inversedBy: 'seguimientos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Grupo $grupo = null;

    #[ORM\ManyToOne(inversedBy: 'seguimientos')]
    private ?Periodo $parcial = null;

    #[ORM\ManyToOne(inversedBy: 'seguimientos')]
    private ?Tema $tema = null;

    #[ORM\Column(length: 14, nullable: true)]
    private ?string $estado = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaProgInicio(): ?\DateTimeInterface
    {
        return $this->fecha_prog_inicio;
    }

    public function setFechaProgInicio(\DateTimeInterface $fecha_prog_inicio): self
    {
        $this->fecha_prog_inicio = $fecha_prog_inicio;

        return $this;
    }

    public function getFechaProgFin(): ?\DateTimeInterface
    {
        return $this->fecha_prog_fin;
    }

    public function setFechaProgFin(\DateTimeInterface $fecha_prog_fin): self
    {
        $this->fecha_prog_fin = $fecha_prog_fin;

        return $this;
    }

    public function getFechaRealInicio(): ?\DateTimeInterface
    {
        return $this->fecha_real_inicio;
    }

    public function setFechaRealInicio(\DateTimeInterface $fecha_real_inicio): self
    {
        $this->fecha_real_inicio = $fecha_real_inicio;

        return $this;
    }

    public function getFechaRealFin(): ?\DateTimeInterface
    {
        return $this->fecha_real_fin;
    }

    public function setFechaRealFin(\DateTimeInterface $fecha_real_fin): self
    {
        $this->fecha_real_fin = $fecha_real_fin;

        return $this;
    }

    public function getEvaluacionProgramada(): ?\DateTimeInterface
    {
        return $this->evaluacion_programada;
    }

    public function setEvaluacionProgramada(\DateTimeInterface $evaluacion_programada): self
    {
        $this->evaluacion_programada = $evaluacion_programada;

        return $this;
    }

    public function getEvaluacionReal(): ?\DateTimeInterface
    {
        return $this->evaluacion_real;
    }

    public function setEvaluacionReal(\DateTimeInterface $evaluacion_real): self
    {
        $this->evaluacion_real = $evaluacion_real;

        return $this;
    }

    public function getPorcentajeAprobacion(): ?float
    {
        return $this->porcentaje_aprobacion;
    }

    public function setPorcentajeAprobacion(float $porcentaje_aprobacion): self
    {
        $this->porcentaje_aprobacion = $porcentaje_aprobacion;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getEvidencia(): ?string
    {
        return $this->evidencia;
    }

    public function setEvidencia(?string $evidencia): self
    {
        $this->evidencia = $evidencia;

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

    public function getParcial(): ?Periodo
    {
        return $this->parcial;
    }

    public function setParcial(?Periodo $parcial): self
    {
        $this->parcial = $parcial;

        return $this;
    }

    public function getTema(): ?Tema
    {
        return $this->tema;
    }

    public function setTema(?Tema $tema): self
    {
        $this->tema = $tema;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }
}
