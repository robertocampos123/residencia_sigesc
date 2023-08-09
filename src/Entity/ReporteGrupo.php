<?php

namespace App\Entity;

use App\Repository\ReporteGrupoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReporteGrupoRepository::class)]
class ReporteGrupo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\OneToOne(inversedBy: 'reporteGrupo', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Grupo $grupo = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $ac_ordinario = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $ac_regularizacion = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $ac_extraordinario = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $no_acreditado = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $desertados = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrupo(): ?Grupo
    {
        return $this->grupo;
    }

    public function setGrupo(Grupo $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }

    public function getAcOrdinario(): ?int
    {
        return $this->ac_ordinario;
    }

    public function setAcOrdinario(?int $ac_ordinario): self
    {
        $this->ac_ordinario = $ac_ordinario;

        return $this;
    }

    public function getAcRegularizacion(): ?int
    {
        return $this->ac_regularizacion;
    }

    public function setAcRegularizacion(?int $ac_regularizacion): self
    {
        $this->ac_regularizacion = $ac_regularizacion;

        return $this;
    }

    public function getAcExtraordinario(): ?int
    {
        return $this->ac_extraordinario;
    }

    public function setAcExtraordinario(?int $ac_extraordinario): self
    {
        $this->ac_extraordinario = $ac_extraordinario;

        return $this;
    }

    public function getNoAcreditado(): ?int
    {
        return $this->no_acreditado;
    }

    public function setNoAcreditado(?int $no_acreditado): self
    {
        $this->no_acreditado = $no_acreditado;

        return $this;
    }

    public function getDesertados(): ?int
    {
        return $this->desertados;
    }

    public function setDesertados(?int $desertados): self
    {
        $this->desertados = $desertados;

        return $this;
    }
}
