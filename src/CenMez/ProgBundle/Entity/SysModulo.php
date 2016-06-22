<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SysModulo
 *
 * @ORM\Table(name="sys_modulo")
 * @ORM\Entity
 */
class SysModulo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="modId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $modid;

    /**
     * @var string
     *
     * @ORM\Column(name="modNombre", type="string", length=60, nullable=true)
     */
    private $modnombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="menu", type="smallint", nullable=true)
     */
    private $menu;

    /**
     * @var boolean
     *
     * @ORM\Column(name="modulo", type="boolean", nullable=true)
     */
    private $modulo;

    /**
     * @var string
     *
     * @ORM\Column(name="ruta", type="string", length=45, nullable=true)
     */
    private $ruta;



    /**
     * Get modid
     *
     * @return integer 
     */
    public function getModid()
    {
        return $this->modid;
    }

    /**
     * Set modnombre
     *
     * @param string $modnombre
     * @return SysModulo
     */
    public function setModnombre($modnombre)
    {
        $this->modnombre = $modnombre;

        return $this;
    }

    /**
     * Get modnombre
     *
     * @return string 
     */
    public function getModnombre()
    {
        return $this->modnombre;
    }

    /**
     * Set menu
     *
     * @param integer $menu
     * @return SysModulo
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return integer 
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set modulo
     *
     * @param boolean $modulo
     * @return SysModulo
     */
    public function setModulo($modulo)
    {
        $this->modulo = $modulo;

        return $this;
    }

    /**
     * Get modulo
     *
     * @return boolean 
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     * @return SysModulo
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta
     *
     * @return string 
     */
    public function getRuta()
    {
        return $this->ruta;
    }
}
