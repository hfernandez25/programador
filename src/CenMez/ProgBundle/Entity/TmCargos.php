<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TmCargos
 *
 * @ORM\Table(name="tm_cargos")
 * @ORM\Entity
 */
class TmCargos
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
     * @ORM\Column(name="nomCargo", type="string", length=45, nullable=true)
     */
    private $nomcargo;

    /**
     * @var string
     *
     * @ORM\Column(name="desCargo", type="string", length=255, nullable=true)
     */
    private $descargo;

    /**
     * @var integer
     *
     * @ORM\Column(name="estId", type="smallint", nullable=true)
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
     * Set nomcargo
     *
     * @param string $nomcargo
     * @return TmCargos
     */
    public function setNomcargo($nomcargo)
    {
        $this->nomcargo = $nomcargo;

        return $this;
    }

    /**
     * Get nomcargo
     *
     * @return string 
     */
    public function getNomcargo()
    {
        return $this->nomcargo;
    }

    /**
     * Set descargo
     *
     * @param string $descargo
     * @return TmCargos
     */
    public function setDescargo($descargo)
    {
        $this->descargo = $descargo;

        return $this;
    }

    /**
     * Get descargo
     *
     * @return string 
     */
    public function getDescargo()
    {
        return $this->descargo;
    }

    /**
     * Set estid
     *
     * @param integer $estid
     * @return TmCargos
     */
    public function setEstid($estid)
    {
        $this->estid = $estid;

        return $this;
    }

    /**
     * Get estid
     *
     * @return integer 
     */
    public function getEstid()
    {
        return $this->estid;
    }
}
