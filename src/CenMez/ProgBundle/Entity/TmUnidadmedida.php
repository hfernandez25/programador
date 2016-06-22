<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TmUnidadmedida
 *
 * @ORM\Table(name="tm_unidadmedida")
 * @ORM\Entity
 */
class TmUnidadmedida
{
    /**
     * @var integer
     *
     * @ORM\Column(name="uniId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $uniid;

    /**
     * @var string
     *
     * @ORM\Column(name="uniCodigo", type="string", length=10, nullable=true)
     */
    private $unicodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="uniNombre", type="string", length=20, nullable=false)
     */
    private $uninombre;

    /**
     * @var string
     *
     * @ORM\Column(name="uniSigla", type="string", length=5, nullable=false)
     */
    private $unisigla;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaUpdate", type="datetime", nullable=true)
     */
    private $fechaupdate;



    /**
     * Get uniid
     *
     * @return integer 
     */
    public function getUniid()
    {
        return $this->uniid;
    }

    /**
     * Set unicodigo
     *
     * @param string $unicodigo
     * @return TmUnidadmedida
     */
    public function setUnicodigo($unicodigo)
    {
        $this->unicodigo = $unicodigo;

        return $this;
    }

    /**
     * Get unicodigo
     *
     * @return string 
     */
    public function getUnicodigo()
    {
        return $this->unicodigo;
    }

    /**
     * Set uninombre
     *
     * @param string $uninombre
     * @return TmUnidadmedida
     */
    public function setUninombre($uninombre)
    {
        $this->uninombre = $uninombre;

        return $this;
    }

    /**
     * Get uninombre
     *
     * @return string 
     */
    public function getUninombre()
    {
        return $this->uninombre;
    }

    /**
     * Set unisigla
     *
     * @param string $unisigla
     * @return TmUnidadmedida
     */
    public function setUnisigla($unisigla)
    {
        $this->unisigla = $unisigla;

        return $this;
    }

    /**
     * Get unisigla
     *
     * @return string 
     */
    public function getUnisigla()
    {
        return $this->unisigla;
    }

    /**
     * Set fechaupdate
     *
     * @param \DateTime $fechaupdate
     * @return TmUnidadmedida
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
}
