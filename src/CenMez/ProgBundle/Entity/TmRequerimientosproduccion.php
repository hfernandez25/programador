<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TmRequerimientosproduccion
 *
 * @ORM\Table(name="tm_requerimientosproduccion", indexes={@ORM\Index(name="fk_med_requerimiento_idx", columns={"medId"}), @ORM\Index(name="fk_mp_requerimiento_idx", columns={"mpId"}), @ORM\Index(name="fk_unidmedida_requerimiento_idx", columns={"uniId"}), @ORM\Index(name="fk_estado_requerimiento_idx", columns={"estId"})})
 * @ORM\Entity(repositoryClass="CenMez\ProgBundle\Entity\TmRequerimientosproduccionRepository")
 */
class TmRequerimientosproduccion
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
     * @ORM\Column(name="cantRequerida", type="float", precision=10, scale=0, nullable=true)
     */
    private $cantrequerida;

    /**
     * @var \TmMedicamentos
     *
     * @ORM\ManyToOne(targetEntity="TmMedicamentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="medId", referencedColumnName="id")
     * })
     */
    private $medid;

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
     * @var \TmUnidadmedida
     *
     * @ORM\ManyToOne(targetEntity="TmUnidadmedida")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uniId", referencedColumnName="uniId")
     * })
     */
    private $uniid;

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
     * Set cantrequerida
     *
     * @param float $cantrequerida
     * @return TmRequerimientosproduccion
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
     * Set medid
     *
     * @param \CenMez\ProgBundle\Entity\TmMedicamentos $medid
     * @return TmRequerimientosproduccion
     */
    public function setMedid(\CenMez\ProgBundle\Entity\TmMedicamentos $medid = null)
    {
        $this->medid = $medid;

        return $this;
    }

    /**
     * Get medid
     *
     * @return \CenMez\ProgBundle\Entity\TmMedicamentos 
     */
    public function getMedid()
    {
        return $this->medid;
    }

    /**
     * Set mpid
     *
     * @param \CenMez\ProgBundle\Entity\TmMateriaprima $mpid
     * @return TmRequerimientosproduccion
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
     * Set uniid
     *
     * @param \CenMez\ProgBundle\Entity\TmUnidadmedida $uniid
     * @return TmRequerimientosproduccion
     */
    public function setUniid(\CenMez\ProgBundle\Entity\TmUnidadmedida $uniid = null)
    {
        $this->uniid = $uniid;

        return $this;
    }

    /**
     * Get uniid
     *
     * @return \CenMez\ProgBundle\Entity\TmUnidadmedida 
     */
    public function getUniid()
    {
        return $this->uniid;
    }

    /**
     * Set estid
     *
     * @param \CenMez\ProgBundle\Entity\SysEstadoregistros $estid
     * @return TmRequerimientosproduccion
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
