<?php

namespace App\Entity;

use App\Repository\GrupoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrupoRepository::class)]
class Grupo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $clave_grupo = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $cupo = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $inscritos = null;

    #[ORM\Column(length: 5)]
    private ?string $aula = null;

    #[ORM\Column(length: 85)]
    private ?string $horario = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instrumentacion_didactica = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avance_programatico = null;

    #[ORM\ManyToOne(inversedBy: 'grupos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Empleado $docente = null;

    #[ORM\ManyToOne(inversedBy: 'grupos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materia $materia = null;

    #[ORM\OneToMany(mappedBy: 'grupo', targetEntity: Seguimiento::class, orphanRemoval: true)]
    private Collection $seguimientos;

    #[ORM\ManyToMany(targetEntity: Alumno::class, inversedBy: 'grupos')]
    private Collection $alumnos;

    #[ORM\OneToMany(mappedBy: 'grupo', targetEntity: Calificaciones::class, orphanRemoval: true)]
    private Collection $calificaciones;

    #[ORM\ManyToOne(inversedBy: 'grupos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carrera $carrera = null;

    #[ORM\OneToOne(mappedBy: 'grupo', cascade: ['persist', 'remove'])]
    private ?ReporteGrupo $reporteGrupo = null;

    public function __construct()
    {
        $this->seguimientos = new ArrayCollection();
        $this->alumnos = new ArrayCollection();
        $this->calificaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClaveGrupo(): ?string
    {
        return $this->clave_grupo;
    }

    public function setClaveGrupo(string $clave_grupo): self
    {
        $this->clave_grupo = $clave_grupo;

        return $this;
    }

    public function getCupo(): ?int
    {
        return $this->cupo;
    }

    public function setCupo(int $cupo): self
    {
        $this->cupo = $cupo;

        return $this;
    }

    public function getInscritos(): ?int
    {
        return $this->inscritos;
    }

    public function setInscritos(int $inscritos): self
    {
        $this->inscritos = $inscritos;

        return $this;
    }

    public function getAula(): ?string
    {
        return $this->aula;
    }

    public function setAula(string $aula): self
    {
        $this->aula = $aula;

        return $this;
    }

    public function getHorario(): ?string
    {
        return $this->horario;
    }

    public function setHorario(string $horario): self
    {
        $this->horario = $horario;

        return $this;
    }

    public function getInstrumentacionDidactica(): ?string
    {
        return $this->instrumentacion_didactica;
    }

    public function setInstrumentacionDidactica(?string $instrumentacion_didactica): self
    {
        $this->instrumentacion_didactica = $instrumentacion_didactica;

        return $this;
    }

    public function getAvanceProgramatico(): ?string
    {
        return $this->avance_programatico;
    }

    public function setAvanceProgramatico(?string $avance_programatico): self
    {
        $this->avance_programatico = $avance_programatico;

        return $this;
    }

    public function getDocente(): ?Empleado
    {
        return $this->docente;
    }

    public function setDocente(?Empleado $docente): self
    {
        $this->docente = $docente;

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
            $seguimiento->setGrupo($this);
        }

        return $this;
    }

    public function removeSeguimiento(Seguimiento $seguimiento): self
    {
        if ($this->seguimientos->removeElement($seguimiento)) {
            // set the owning side to null (unless already changed)
            if ($seguimiento->getGrupo() === $this) {
                $seguimiento->setGrupo(null);
            }
        }

        return $this;
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
        }

        return $this;
    }

    public function removeAlumno(Alumno $alumno): self
    {
        $this->alumnos->removeElement($alumno);

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
            $calificacione->setGrupo($this);
        }

        return $this;
    }

    public function removeCalificacione(Calificaciones $calificacione): self
    {
        if ($this->calificaciones->removeElement($calificacione)) {
            // set the owning side to null (unless already changed)
            if ($calificacione->getGrupo() === $this) {
                $calificacione->setGrupo(null);
            }
        }

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

    public function getReporteGrupo(): ?ReporteGrupo
    {
        return $this->reporteGrupo;
    }

    public function setReporteGrupo(ReporteGrupo $reporteGrupo): self
    {
        // set the owning side of the relation if necessary
        if ($reporteGrupo->getGrupo() !== $this) {
            $reporteGrupo->setGrupo($this);
        }

        $this->reporteGrupo = $reporteGrupo;

        return $this;
    }
}
