<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TtMateriaprimaUtilizada
 *
 * @ORM\Table(name="tt_materiaprima_utilizada", indexes={@ORM\Index(name="fk_detalleorden_fk_orden_materiautilizada_idx", columns={"detOrdId"}), @ORM\Index(name="mpId_idx", columns={"mpId"}), @ORM\Index(name="usuId_idx", columns={"usuId"}), @ORM\Index(name="fk_estado_materiautilizada_idx", columns={"estId"})})
 * @ORM\Entity(repositoryClass="CenMez\ProgBundle\Entity\TtMateriaprimaUtilizadaRepository")
 */
class TtMateriaprimaUtilizada
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="cantTotal", type="float", precision=10, scale=0, nullable=true)
     */
    private $canttotal;

    /**
     * @var float
     *
     * @ORM\Column(name="cantRequerida", type="float", precision=10, scale=0, nullable=true)
     */
    private $cantrequerida;

    /**
     * @var float
     *
     * @ORM\Column(name="cantAprovechamiento", type="float", precision=10, scale=0, nullable=true)
     */
    private $cantaprovechamiento;

    /**
     * @var float
     *
     * @ORM\Column(name="costo", type="float", precision=10, scale=0, nullable=true)
     */
    private $costo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCreate", type="datetime", nullable=true)
     */
    private $fechacreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaUpdate", type="datetime", nullable=true)
     */
    private $fechaupdate;

    /**
     * @var \TtDetalleOrdenproduccion
     *
     * @ORM\ManyToOne(targetEntity="TtDetalleOrdenproduccion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="detOrdId", referencedColumnName="id")
     * })
     */
    private $detordid;

    /**
     * @var \SysEstadoregistros
     *
     * @ORM\ManyToOne(targetEntity="SysEstadoregistros")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estId", referencedColumnName="estId")
     * })
     */
    private $estid;

    /**
     * @var \TmMateriaprima
     *
     * @ORM\ManyToOne(targetEntity="TmMateriaprima")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mpId", referencedColumnName="id")
     * })
     */
    private $mpid;

    /**
     * @var \SysUsuarios
     *
     * @ORM\ManyToOne(targetEntity="SysUsuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuId", referencedColumnName="usuId")
     * })
     */
    private $usuid;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set canttotal
     *
     * @param float $canttotal
     * @return TtMateriaprimaUtilizada
     */
    public function setCanttotal($canttotal)
    {
        $this->canttotal = $canttotal;

        return $this;
    }

    /**
     * Get canttotal
     *
     * @return float 
     */
    public function getCanttotal()
    {
        return $this->canttotal;
    }

    /**
     * Set cantrequerida
     *
     * @param float $cantrequerida
     * @return TtMateriaprimaUtilizada
     */
    public function setCantrequerida($cantrequerida)
    {
        $this->cantrequerida = $cantrequerida;

        return $this;
    }

    /**
     * Get cantrequerida
     *
     * @return float 
     */
    public function getCantrequerida()
    {
        return $this->cantrequerida;
    }

    /**
     * Set cantaprovechamiento
     *
     * @param float $cantaprovechamiento
     * @return TtMateriaprimaUtilizada
     */
    public function setCantaprovechamiento($cantaprovechamiento)
    {
        $this->cantaprovechamiento = $cantaprovechamiento;

        return $this;
    }

    /**
     * Get cantaprovechamiento
     *
     * @return float 
     */
    public function getCantaprovechamiento()
    {
        return $this->cantaprovechamiento;
    }

    /**
     * Set costo
     *
     * @param float $costo
     * @return TtMateriaprimaUtilizada
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float 
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set fechacreate
     *
     * @param \DateTime $fechacreate
     * @return TtMateriaprimaUtilizada
     */
    public function setFechacreate($fechacreate)
    {
        $this->fechacreate = $fechacreate;

        return $this;
    }

    /**
     * Get fechacreate
     *
     * @return \DateTime 
     */
    public function getFechacreate()
    {
        return $this->fechacreate;
    }

    /**
     * Set fechaupdate
     *
     * @param \DateTime $fechaupdate
     * @return TtMateriaprimaUtilizada
     */
    public function setFechaupdate($fechaupdate)
    {
        $this->fechaupdate = $fechaupdate;

        return $this;
    }

    /**
     * Get fechaupdate
     *
     * @return \DateTime 
     */
    public function getFechaupdate()
    {
        return $this->fechaupdate;
    }

    /**
     * Set detordid
     *
     * @param \CenMez\ProgBundle\Entity\TtDetalleOrdenproduccion $detordid
     * @return TtMateriaprimaUtilizada
     */
    public function setDetordid(\CenMez\ProgBundle\Entity\TtDetalleOrdenproduccion $detordid = null)
    {
        $this->detordid = $detordid;

        return $this;
    }

    /**
     * Get detordid
     *
     * @return \CenMez\ProgBundle\Entity\TtDetalleOrdenproduccion 
     */
    public function getDetordid()
    {
        return $this->detordid;
    }

    /**
     * Set estid
     *
     * @param \CenMez\ProgBundle\Entity\SysEstadoregistros $estid
     * @return TtMateriaprimaUtilizada
     */
    public function setEstid(\CenMez\ProgBundle\Entity\SysEstadoregistros $estid = null)
    {
        $this->estid = $estid;

        return $this;
    }

    /**
     * Get estid
     *
     * @return \CenMez\ProgBundle\Entity\SysEstadoregistros 
     */
    public function getEstid()
    {
        return $this->estid;
    }

    /**
     * Set mpid
     *
     * @param \CenMez\ProgBundle\Entity\TmMateriaprima $mpid
     * @return TtMateriaprimaUtilizada
     */
    public function setMpid(\CenMez\ProgBundle\Entity\TmMateriaprima $mpid = null)
    {
        $this->mpid = $mpid;

        return $this;
    }

    /**
     * Get mpid
     *
     * @return \CenMez\ProgBundle\Entity\TmMateriaprima 
     */
    public function getMpid()
    {
        return $this->mpid;
    }

    /**
     * Set usuid
     *
     * @param \CenMez\ProgBundle\Entity\SysUsuarios $usuid
     * @return TtMateriaprimaUtilizada
     */
    public function setUsuid(\CenMez\ProgBundle\Entity\SysUsuarios $usuid = null)
    {
        $this->usuid = $usuid;

        return $this;
    }

    /**
     * Get usuid
     *
     * @return \CenMez\ProgBundle\Entity\SysUsuarios 
     */
    public function getUsuid()
    {
        return $this->usuid;
    }
}
