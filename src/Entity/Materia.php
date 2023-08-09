<?php

namespace App\Entity;

use App\Repository\MateriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MateriaRepository::class)]
class Materia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12,  unique: true)]
    private ?string $clave_materia = null;

    #[ORM\Column(length: 35)]
    private ?string $nombre_materia = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $objetivo_materia = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $caracterizacion_materia = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $intencion_didactica = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $horas_teoricas = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $horas_practicas = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $creditos = null;

    #[ORM\Column(length: 10)]
    private ?string $plan_academico = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $semestre = null;

    #[ORM\ManyToMany(targetEntity: Carrera::class, inversedBy: 'materias')]
    private Collection $carrera;

    #[ORM\OneToMany(mappedBy: 'materia', targetEntity: Tema::class)]
    private Collection $temas;

    #[ORM\OneToMany(mappedBy: 'materia', targetEntity: Grupo::class)]
    private Collection $grupos;

    public function __construct()
    {
        $this->carrera = new ArrayCollection();
        $this->temas = new ArrayCollection();
        $this->grupos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClaveMateria(): ?string
    {
        return $this->clave_materia;
    }

    public function setClaveMateria(string $clave_materia): self
    {
        $this->clave_materia = $clave_materia;

        return $this;
    }

    public function getNombreMateria(): ?string
    {
        return $this->nombre_materia;
    }

    public function setNombreMateria(string $nombre_materia): self
    {
        $this->nombre_materia = $nombre_materia;

        return $this;
    }

    public function getObjetivoMateria(): ?string
    {
        return $this->objetivo_materia;
    }

    public function setObjetivoMateria(string $objetivo_materia): self
    {
        $this->objetivo_materia = $objetivo_materia;

        return $this;
    }

    public function getCaracterizacionMateria(): ?string
    {
        return $this->caracterizacion_materia;
    }

    public function setCaracterizacionMateria(string $caracterizacion_materia): self
    {
        $this->caracterizacion_materia = $caracterizacion_materia;

        return $this;
    }

    public function getIntencionDidactica(): ?string
    {
        return $this->intencion_didactica;
    }

    public function setIntencionDidactica(?string $intencion_didactica): self
    {
        $this->intencion_didactica = $intencion_didactica;

        return $this;
    }

    public function getHorasTeoricas(): ?int
    {
        return $this->horas_teoricas;
    }

    public function setHorasTeoricas(int $horas_teoricas): self
    {
        $this->horas_teoricas = $horas_teoricas;

        return $this;
    }

    public function getHorasPracticas(): ?int
    {
        return $this->horas_practicas;
    }

    public function setHorasPracticas(int $horas_practicas): self
    {
        $this->horas_practicas = $horas_practicas;

        return $this;
    }

    public function getCreditos(): ?int
    {
        return $this->creditos;
    }

    public function setCreditos(int $creditos): self
    {
        $this->creditos = $creditos;

        return $this;
    }

    public function getPlanAcademico(): ?string
    {
        return $this->plan_academico;
    }

    public function setPlanAcademico(string $plan_academico): self
    {
        $this->plan_academico = $plan_academico;

        return $this;
    }

    public function getSemestre(): ?int
    {
        return $this->semestre;
    }

    public function setSemestre(int $semestre): self
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * @return Collection<int, Carrera>
     */
    public function getCarrera(): Collection
    {
        return $this->carrera;
    }

    public function addCarrera(Carrera $carrera): self
    {
        if (!$this->carrera->contains($carrera)) {
            $this->carrera->add($carrera);
        }

        return $this;
    }

    public function removeCarrera(Carrera $carrera): self
    {
        $this->carrera->removeElement($carrera);

        return $this;
    }

    /**
     * @return Collection<int, Tema>
     */
    public function getTemas(): Collection
    {
        return $this->temas;
    }

    public function addTema(Tema $tema): self
    {
        if (!$this->temas->contains($tema)) {
            $this->temas->add($tema);
            $tema->setMateria($this);
        }

        return $this;
    }

    public function removeTema(Tema $tema): self
    {
        if ($this->temas->removeElement($tema)) {
            // set the owning side to null (unless already changed)
            if ($tema->getMateria() === $this) {
                $tema->setMateria(null);
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
            $grupo->setMateria($this);
        }

        return $this;
    }

    public function removeGrupo(Grupo $grupo): self
    {
        if ($this->grupos->removeElement($grupo)) {
            // set the owning side to null (unless already changed)
            if ($grupo->getMateria() === $this) {
                $grupo->setMateria(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->nombre_materia;
        //return $this->clave_materia;
    }
}
