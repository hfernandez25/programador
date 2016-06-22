<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TmMedicamentos
 *
 * @ORM\Table(name="tm_medicamentos", indexes={@ORM\Index(name="FK_Unidad_Medicamento_idx", columns={"unidId"}), @ORM\Index(name="FK_Unidad_Presentacion_idx", columns={"unidConcentacion"}), @ORM\Index(name="fk_estado_medicamento_idx", columns={"estId"})})
 * @ORM\Entity(repositoryClass="CenMez\ProgBundle\Entity\TmMedicamentosRepository")
 */
class TmMedicamentos
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=55, nullable=true)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="presentacion", type="integer", nullable=true)
     */
    private $presentacion;

    /**
     * @var string
     *
     * @ORM\Column(name="VolReconstitucion", type="string", length=45, nullable=true)
     */
    private $volreconstitucion;

    /**
     * @var string
     *
     * @ORM\Column(name="VehReconstitucion", type="string", length=45, nullable=true)
     */
    private $vehreconstitucion;

    /**
     * @var integer
     *
     * @ORM\Column(name="Concentacion", type="integer", nullable=true)
     */
    private $concentacion;

    /**
     * @var string
     *
     * @ORM\Column(name="VehDilucion", type="string", length=45, nullable=true)
     */
    private $vehdilucion;

    /**
     * @var integer
     *
     * @ORM\Column(name="VolVehDilucion", type="integer", nullable=true)
     */
    private $volvehdilucion;

    /**
     * @var integer
     *
     * @ORM\Column(name="EstabProdReconstituido", type="integer", nullable=true)
     */
    private $estabprodreconstituido;

    /**
     * @var string
     *
     * @ORM\Column(name="CondAlmacenamiento", type="string", length=100, nullable=true)
     */
    private $condalmacenamiento;

    /**
     * @var integer
     *
     * @ORM\Column(name="EstabilidadPreparacion", type="integer", nullable=true)
     */
    private $estabilidadpreparacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="ValorLigadoPreparacion", type="integer", nullable=true)
     */
    private $valorligadopreparacion;

    /**
     * @var string
     *
     * @ORM\Column(name="PresentacionFarmaceutica", type="string", length=55, nullable=true)
     */
    private $presentacionfarmaceutica;

    /**
     * @var string
     *
     * @ORM\Column(name="Observacion", type="string", length=250, nullable=true)
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
     * @var \TmUnidadmedida
     *
     * @ORM\ManyToOne(targetEntity="TmUnidadmedida")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unidConcentacion", referencedColumnName="uniId")
     * })
     */
    private $unidconcentacion;

    /**
     * @var \TmUnidadmedida
     *
     * @ORM\ManyToOne(targetEntity="TmUnidadmedida")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unidId", referencedColumnName="uniId")
     * })
     */
    private $unidid;



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
     * Set nombre
     *
     * @param string $nombre
     * @return TmMedicamentos
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set presentacion
     *
     * @param integer $presentacion
     * @return TmMedicamentos
     */
    public function setPresentacion($presentacion)
    {
        $this->presentacion = $presentacion;

        return $this;
    }

    /**
     * Get presentacion
     *
     * @return integer 
     */
    public function getPresentacion()
    {
        return $this->presentacion;
    }

    /**
     * Set volreconstitucion
     *
     * @param string $volreconstitucion
     * @return TmMedicamentos
     */
    public function setVolreconstitucion($volreconstitucion)
    {
        $this->volreconstitucion = $volreconstitucion;

        return $this;
    }

    /**
     * Get volreconstitucion
     *
     * @return string 
     */
    public function getVolreconstitucion()
    {
        return $this->volreconstitucion;
    }

    /**
     * Set vehreconstitucion
     *
     * @param string $vehreconstitucion
     * @return TmMedicamentos
     */
    public function setVehreconstitucion($vehreconstitucion)
    {
        $this->vehreconstitucion = $vehreconstitucion;

        return $this;
    }

    /**
     * Get vehreconstitucion
     *
     * @return string 
     */
    public function getVehreconstitucion()
    {
        return $this->vehreconstitucion;
    }

    /**
     * Set concentacion
     *
     * @param integer $concentacion
     * @return TmMedicamentos
     */
    public function setConcentacion($concentacion)
    {
        $this->concentacion = $concentacion;

        return $this;
    }

    /**
     * Get concentacion
     *
     * @return integer 
     */
    public function getConcentacion()
    {
        return $this->concentacion;
    }

    /**
     * Set vehdilucion
     *
     * @param string $vehdilucion
     * @return TmMedicamentos
     */
    public function setVehdilucion($vehdilucion)
    {
        $this->vehdilucion = $vehdilucion;

        return $this;
    }

    /**
     * Get vehdilucion
     *
     * @return string 
     */
    public function getVehdilucion()
    {
        return $this->vehdilucion;
    }

    /**
     * Set volvehdilucion
     *
     * @param integer $volvehdilucion
     * @return TmMedicamentos
     */
    public function setVolvehdilucion($volvehdilucion)
    {
        $this->volvehdilucion = $volvehdilucion;

        return $this;
    }

    /**
     * Get volvehdilucion
     *
     * @return integer 
     */
    public function getVolvehdilucion()
    {
        return $this->volvehdilucion;
    }

    /**
     * Set estabprodreconstituido
     *
     * @param integer $estabprodreconstituido
     * @return TmMedicamentos
     */
    public function setEstabprodreconstituido($estabprodreconstituido)
    {
        $this->estabprodreconstituido = $estabprodreconstituido;

        return $this;
    }

    /**
     * Get estabprodreconstituido
     *
     * @return integer 
     */
    public function getEstabprodreconstituido()
    {
        return $this->estabprodreconstituido;
    }

    /**
     * Set condalmacenamiento
     *
     * @param string $condalmacenamiento
     * @return TmMedicamentos
     */
    public function setCondalmacenamiento($condalmacenamiento)
    {
        $this->condalmacenamiento = $condalmacenamiento;

        return $this;
    }

    /**
     * Get condalmacenamiento
     *
     * @return string 
     */
    public function getCondalmacenamiento()
    {
        return $this->condalmacenamiento;
    }

    /**
     * Set estabilidadpreparacion
     *
     * @param integer $estabilidadpreparacion
     * @return TmMedicamentos
     */
    public function setEstabilidadpreparacion($estabilidadpreparacion)
    {
        $this->estabilidadpreparacion = $estabilidadpreparacion;

        return $this;
    }

    /**
     * Get estabilidadpreparacion
     *
     * @return integer 
     */
    public function getEstabilidadpreparacion()
    {
        return $this->estabilidadpreparacion;
    }

    /**
     * Set valorligadopreparacion
     *
     * @param integer $valorligadopreparacion
     * @return TmMedicamentos
     */
    public function setValorligadopreparacion($valorligadopreparacion)
    {
        $this->valorligadopreparacion = $valorligadopreparacion;

        return $this;
    }

    /**
     * Get valorligadopreparacion
     *
     * @return integer 
     */
    public function getValorligadopreparacion()
    {
        return $this->valorligadopreparacion;
    }

    /**
     * Set presentacionfarmaceutica
     *
     * @param string $presentacionfarmaceutica
     * @return TmMedicamentos
     */
    public function setPresentacionfarmaceutica($presentacionfarmaceutica)
    {
        $this->presentacionfarmaceutica = $presentacionfarmaceutica;

        return $this;
    }

    /**
     * Get presentacionfarmaceutica
     *
     * @return string 
     */
    public function getPresentacionfarmaceutica()
    {
        return $this->presentacionfarmaceutica;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return TmMedicamentos
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
     * @return TmMedicamentos
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
     * Set unidconcentacion
     *
     * @param \CenMez\ProgBundle\Entity\TmUnidadmedida $unidconcentacion
     * @return TmMedicamentos
     */
    public function setUnidconcentacion(\CenMez\ProgBundle\Entity\TmUnidadmedida $unidconcentacion = null)
    {
        $this->unidconcentacion = $unidconcentacion;

        return $this;
    }

    /**
     * Get unidconcentacion
     *
     * @return \CenMez\ProgBundle\Entity\TmUnidadmedida 
     */
    public function getUnidconcentacion()
    {
        return $this->unidconcentacion;
    }

    /**
     * Set unidid
     *
     * @param \CenMez\ProgBundle\Entity\TmUnidadmedida $unidid
     * @return TmMedicamentos
     */
    public function setUnidid(\CenMez\ProgBundle\Entity\TmUnidadmedida $unidid = null)
    {
        $this->unidid = $unidid;

        return $this;
    }

    /**
     * Get unidid
     *
     * @return \CenMez\ProgBundle\Entity\TmUnidadmedida 
     */
    public function getUnidid()
    {
        return $this->unidid;
    }
}
