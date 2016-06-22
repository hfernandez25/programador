<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TmMateriaprima
 *
 * @ORM\Table(name="tm_materiaprima", indexes={@ORM\Index(name="fk_estado_matprima_idx", columns={"estId"}), @ORM\Index(name="fk_mp_unidades_idx", columns={"uniId"})})
 * @ORM\Entity(repositoryClass="CenMez\ProgBundle\Entity\TmMateriaprimaRepository")
 */
class TmMateriaprima
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
     * @ORM\Column(name="costo", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $costo;

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
     * Set nombre
     *
     * @param string $nombre
     * @return TmMateriaprima
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
     * @return TmMateriaprima
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
     * Set costo
     *
     * @param string $costo
     * @return TmMateriaprima
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return string 
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set uniid
     *
     * @param \CenMez\ProgBundle\Entity\TmUnidadmedida $uniid
     * @return TmMateriaprima
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
     * @return TmMateriaprima
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
