<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TtDetalleOrdenproduccion
 *
 * @ORM\Table(name="tt_detalle_ordenproduccion", indexes={@ORM\Index(name="fk_orden_enca_idx", columns={"idOrden"}), @ORM\Index(name="fk_estado_detalle_ord_idx", columns={"estId"}), @ORM\Index(name="fk_usu_detalle_orden_idx", columns={"usuId"})})
 * @ORM\Entity(repositoryClass="CenMez\ProgBundle\Entity\TtDetalleOrdenproduccionRepository")
 */
class TtDetalleOrdenproduccion
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
     * @ORM\Column(name="fechaVencimiento", type="datetime", nullable=true)
     */
    private $fechavencimiento;
    
    
    /**
     * @var \TmMedicamentos
     *
     * @ORM\ManyToOne(targetEntity="TmMedicamentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="medicamento", referencedColumnName="id")
     * })
     */
    private $medicamento;
    

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="lote", type="string", length=45, nullable=true)
     */
    private $lote;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="string", length=255, nullable=true)
     */
    private $observacion;

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
     * @var \TtOrdenProduccion
     *
     * @ORM\ManyToOne(targetEntity="TtOrdenProduccion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idOrden", referencedColumnName="id")
     * })
     */
    private $idorden;

    /**
     * @var \TmTrabajadores
     *
     * @ORM\ManyToOne(targetEntity="TmTrabajadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuId", referencedColumnName="id")
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
     * Set fechacreate
     *
     * @param \DateTime $fechacreate
     * @return TtDetalleOrdenproduccion
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
     * @return TtDetalleOrdenproduccion
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
     * Set fechavencimiento
     *
     * @param \DateTime $fechavencimiento
     * @return TtDetalleOrdenproduccion
     */
    public function setFechavencimiento($fechavencimiento)
    {
        $this->fechavencimiento = $fechavencimiento;

        return $this;
    }

    /**
     * Get fechavencimiento
     *
     * @return \DateTime 
     */
    public function getFechavencimiento()
    {
        return $this->fechavencimiento;
    }
    
    
    /**
     * Set medicamento
     *
     * @param \CenMez\ProgBundle\Entity\TmMedicamentos $medicamento
     * @return TtDetalleOrdenproduccion
     */
    public function setMedicamento(\CenMez\ProgBundle\Entity\TmMedicamentos $medicamento = null)
    {
        $this->medicamento = $medicamento;

        return $this;
    }

    /**
     * Get medicamento
     *
     * @return \CenMez\ProgBundle\Entity\TmMedicamentos 
     */
    public function getMedicamento()
    {
        return $this->medicamento;
    }
    

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return TtDetalleOrdenproduccion
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set lote
     *
     * @param string $lote
     * @return TtDetalleOrdenproduccion
     */
    public function setLote($lote)
    {
        $this->lote = $lote;

        return $this;
    }

    /**
     * Get lote
     *
     * @return string 
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return TtDetalleOrdenproduccion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set estid
     *
     * @param \CenMez\ProgBundle\Entity\SysEstadoregistros $estid
     * @return TtDetalleOrdenproduccion
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
     * Set idorden
     *
     * @param \CenMez\ProgBundle\Entity\TtOrdenProduccion $idorden
     * @return TtDetalleOrdenproduccion
     */
    public function setIdorden(\CenMez\ProgBundle\Entity\TtOrdenProduccion $idorden = null)
    {
        $this->idorden = $idorden;

        return $this;
    }

    /**
     * Get idorden
     *
     * @return \CenMez\ProgBundle\Entity\TtOrdenProduccion 
     */
    public function getIdorden()
    {
        return $this->idorden;
    }

    /**
     * Set usuid
     *
     * @param \CenMez\ProgBundle\Entity\TmTrabajadores $usuid
     * @return TtDetalleOrdenproduccion
     */
    public function setUsuid(\CenMez\ProgBundle\Entity\TmTrabajadores $usuid = null)
    {
        $this->usuid = $usuid;

        return $this;
    }

    /**
     * Get usuid
     *
     * @return \CenMez\ProgBundle\Entity\TmTrabajadores 
     */
    public function getUsuid()
    {
        return $this->usuid;
    }
}
