<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TtOrdenProduccion
 *
 * @ORM\Table(name="tt_orden_produccion", indexes={@ORM\Index(name="fk_trabajdor_ord_idx", columns={"idQf"}), @ORM\Index(name="fk_usuario_regi_idx", columns={"usuId"}), @ORM\Index(name="f_estado_orden_idx", columns={"estId"})})
 * @ORM\Entity(repositoryClass="CenMez\ProgBundle\Entity\TtOrdenProduccionRepository")
 */
class TtOrdenProduccion
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora", type="time", nullable=true)
     */
    private $hora;

    /**
     * @var string
     *
     * @ORM\Column(name="ordenProduccion", type="string", length=15, nullable=true)
     */
    private $ordenproduccion;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @var \TmTrabajadores
     *
     * @ORM\ManyToOne(targetEntity="TmTrabajadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idQf", referencedColumnName="id")
     * })
     */
    private $idqf;

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
     * @var \SysEstadoregistros
     *
     * @ORM\ManyToOne(targetEntity="SysEstadoregistros")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estId", referencedColumnName="estId")
     * })
     */
    private $estid;



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
     * Set fechacreate
     *
     * @param \DateTime $fechacreate
     * @return TtOrdenProduccion
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
     * @return TtOrdenProduccion
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return TtOrdenProduccion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     * @return TtOrdenProduccion
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime 
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set ordenproduccion
     *
     * @param string $ordenproduccion
     * @return TtOrdenProduccion
     */
    public function setOrdenproduccion($ordenproduccion)
    {
        $this->ordenproduccion = $ordenproduccion;

        return $this;
    }

    /**
     * Get ordenproduccion
     *
     * @return string 
     */
    public function getOrdenproduccion()
    {
        return $this->ordenproduccion;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return TtOrdenProduccion
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set idqf
     *
     * @param \CenMez\ProgBundle\Entity\TmTrabajadores $idqf
     * @return TtOrdenProduccion
     */
    public function setIdqf(\CenMez\ProgBundle\Entity\TmTrabajadores $idqf = null)
    {
        $this->idqf = $idqf;

        return $this;
    }

    /**
     * Get idqf
     *
     * @return \CenMez\ProgBundle\Entity\TmTrabajadores 
     */
    public function getIdqf()
    {
        return $this->idqf;
    }

    /**
     * Set usuid
     *
     * @param \CenMez\ProgBundle\Entity\SysUsuarios $usuid
     * @return TtOrdenProduccion
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

    /**
     * Set estid
     *
     * @param \CenMez\ProgBundle\Entity\SysEstadoregistros $estid
     * @return TtOrdenProduccion
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
}
