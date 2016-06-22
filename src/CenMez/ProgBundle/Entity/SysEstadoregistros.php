<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SysEstadoregistros
 *
 * @ORM\Table(name="sys_estadoregistros")
 * @ORM\Entity
 */
class SysEstadoregistros
{
    /**
     * @var integer
     *
     * @ORM\Column(name="estId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $estid;

    /**
     * @var string
     *
     * @ORM\Column(name="estNombre", type="string", length=10, nullable=true)
     */
    private $estnombre;



    /**
     * Get estid
     *
     * @return integer 
     */
    public function getEstid()
    {
        return $this->estid;
    }

    /**
     * Set estnombre
     *
     * @param string $estnombre
     * @return SysEstadoregistros
     */
    public function setEstnombre($estnombre)
    {
        $this->estnombre = $estnombre;

        return $this;
    }

    /**
     * Get estnombre
     *
     * @return string 
     */
    public function getEstnombre()
    {
        return $this->estnombre;
    }
}
