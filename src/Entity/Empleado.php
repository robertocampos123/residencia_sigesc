<?php

namespace App\Entity;

use App\Repository\EmpleadoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpleadoRepository::class)]
class Empleado
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 13, unique: true)]
    private ?string $RFC = null;

    #[ORM\Column(length: 30)]
    private ?string $nombre_empleado = null;

    #[ORM\Column(length: 30)]
    private ?string $apellido_paterno = null;

    #[ORM\Column(length: 30)]
    private ?string $apellido_materno = null;

    #[ORM\Column(length: 1)]
    private ?string $genero = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firma_empleado = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fotografia_empleado = null;

    #[ORM\OneToOne(inversedBy: 'empleado', cascade: ['persist', 'remove'])]
    private ?Usuario $mail = null;

    #[ORM\ManyToOne(inversedBy: 'empleados')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Departamento $departamento = null;

    #[ORM\ManyToOne(inversedBy: 'empleados')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cargo $cargo = null;

    #[ORM\OneToMany(mappedBy: 'docente', targetEntity: Grupo::class)]
    private Collection $grupos;

    #[ORM\OneToMany(mappedBy: 'empleado', targetEntity: ReporteFinal::class)]
    private Collection $reporteFinals;

    public function __construct()
    {
        $this->grupos = new ArrayCollection();
        $this->reporteFinals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRFC(): ?string
    {
        return $this->RFC;
    }

    public function setRFC(string $RFC): self
    {
        $this->RFC = $RFC;

        return $this;
    }

    public function getNombreEmpleado(): ?string
    {
        return $this->nombre_empleado;
    }

    public function setNombreEmpleado(string $nombre_empleado): self
    {
        $this->nombre_empleado = $nombre_empleado;

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

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getFirmaEmpleado(): ?string
    {
        return $this->firma_empleado;
    }

    public function setFirmaEmpleado(?string $firma_empleado): self
    {
        $this->firma_empleado = $firma_empleado;

        return $this;
    }

    public function getFotografiaEmpleado(): ?string
    {
        return $this->fotografia_empleado;
    }

    public function setFotografiaEmpleado(?string $fotografia_empleado): self
    {
        $this->fotografia_empleado = $fotografia_empleado;

        return $this;
    }

    public function getMail(): ?Usuario
    {
        return $this->mail;
    }

    public function setMail(?Usuario $mail): self
    {
        $this->mail = $mail;

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

    public function getCargo(): ?Cargo
    {
        return $this->cargo;
    }

    public function setCargo(?Cargo $cargo): self
    {
        $this->cargo = $cargo;

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
            $grupo->setDocente($this);
        }

        return $this;
    }

    public function removeGrupo(Grupo $grupo): self
    {
        if ($this->grupos->removeElement($grupo)) {
            // set the owning side to null (unless already changed)
            if ($grupo->getDocente() === $this) {
                $grupo->setDocente(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->RFC;
    }

    /**
     * @return Collection<int, ReporteFinal>
     */
    public function getReporteFinals(): Collection
    {
        return $this->reporteFinals;
    }

    public function addReporteFinal(ReporteFinal $reporteFinal): self
    {
        if (!$this->reporteFinals->contains($reporteFinal)) {
            $this->reporteFinals->add($reporteFinal);
            $reporteFinal->setEmpleado($this);
        }

        return $this;
    }

    public function removeReporteFinal(ReporteFinal $reporteFinal): self
    {
        if ($this->reporteFinals->removeElement($reporteFinal)) {
            // set the owning side to null (unless already changed)
            if ($reporteFinal->getEmpleado() === $this) {
                $reporteFinal->setEmpleado(null);
            }
        }

        return $this;
    }
}
