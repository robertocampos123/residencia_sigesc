<?php

namespace App\Entity;

use App\Repository\ReporteFinalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReporteFinalRepository::class)]
class ReporteFinal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reporte_final = null;

    #[ORM\ManyToOne(inversedBy: 'reporteFinals')]
    private ?Empleado $empleado = null;

    #[ORM\Column(length: 10)]
    private ?string $Periodo = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $estado = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReporteFinal(): ?string
    {
        return $this->reporte_final;
    }

    public function setReporteFinal(string $reporte_final): self
    {
        $this->reporte_final = $reporte_final;

        return $this;
    }

    public function getEmpleado(): ?Empleado
    {
        return $this->empleado;
    }

    public function setEmpleado(?Empleado $empleado): self
    {
        $this->empleado = $empleado;

        return $this;
    }

    public function getPeriodo(): ?string
    {
        return $this->Periodo;
    }

    public function setPeriodo(string $Periodo): self
    {
        $this->Periodo = $Periodo;

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
