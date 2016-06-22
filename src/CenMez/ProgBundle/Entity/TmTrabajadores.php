<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TmTrabajadores
 *
 * @ORM\Table(name="tm_trabajadores", indexes={@ORM\Index(name="fk_cargos_idx", columns={"idCargo"})})
 * @ORM\Entity
 */
class TmTrabajadores
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
     * @ORM\Column(name="nombre", type="string", length=65, nullable=true)
     */
    private $nombre;

    /**
     * @var \TmCargos
     *
     * @ORM\ManyToOne(targetEntity="TmCargos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCargo", referencedColumnName="id")
     * })
     */
    private $idcargo;



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
     * @return TmTrabajadores
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
     * Set idcargo
     *
     * @param \CenMez\ProgBundle\Entity\TmCargos $idcargo
     * @return TmTrabajadores
     */
    public function setIdcargo(\CenMez\ProgBundle\Entity\TmCargos $idcargo = null)
    {
        $this->idcargo = $idcargo;

        return $this;
    }

    /**
     * Get idcargo
     *
     * @return \CenMez\ProgBundle\Entity\TmCargos 
     */
    public function getIdcargo()
    {
        return $this->idcargo;
    }
}
