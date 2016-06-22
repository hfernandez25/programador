<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SysGrupousuarios
 *
 * @ORM\Table(name="sys_grupousuarios")
 * @ORM\Entity
 */
class SysGrupousuarios
{
    /**
     * @var integer
     *
     * @ORM\Column(name="gusuId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $gusuid;

    /**
     * @var string
     *
     * @ORM\Column(name="gusuNombre", type="string", length=45, nullable=true)
     */
    private $gusunombre;



    /**
     * Get gusuid
     *
     * @return integer 
     */
    public function getGusuid()
    {
        return $this->gusuid;
    }

    /**
     * Set gusunombre
     *
     * @param string $gusunombre
     * @return SysGrupousuarios
     */
    public function setGusunombre($gusunombre)
    {
        $this->gusunombre = $gusunombre;

        return $this;
    }

    /**
     * Get gusunombre
     *
     * @return string 
     */
    public function getGusunombre()
    {
        return $this->gusunombre;
    }
}
