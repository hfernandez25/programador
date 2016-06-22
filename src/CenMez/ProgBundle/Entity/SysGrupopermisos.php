<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SysGrupopermisos
 *
 * @ORM\Table(name="sys_grupopermisos", indexes={@ORM\Index(name="fk_grupo_idx", columns={"gusuId"}), @ORM\Index(name="fk_modulo_idx", columns={"modId"})})
 * @ORM\Entity(repositoryClass="CenMez\ProgBundle\Entity\SysGrupopermisosRepository")
 */
class SysGrupopermisos
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
     * @var boolean
     *
     * @ORM\Column(name="lectura", type="smallint", nullable=true)
     */
    private $lectura;

    /**
     * @var boolean
     *
     * @ORM\Column(name="escritura", type="smallint", nullable=true)
     */
    private $escritura;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actualizar", type="smallint", nullable=true)
     */
    private $update;

    /**
     * @var boolean
     *
     * @ORM\Column(name="borrar", type="smallint", nullable=true)
     */
    private $delete;

    /**
     * @var \SysGrupousuarios
     *
     * @ORM\ManyToOne(targetEntity="SysGrupousuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gusuId", referencedColumnName="gusuId")
     * })
     */
    private $gusuid;

    /**
     * @var \SysModulo
     *
     * @ORM\ManyToOne(targetEntity="SysModulo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modId", referencedColumnName="modId")
     * })
     */
    private $modid;



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
     * Set lectura
     *
     * @param smallint $lectura
     * @return SysGrupopermisos
     */
    public function setLectura($lectura)
    {
        $this->lectura = $lectura;

        return $this;
    }

    /**
     * Get lectura
     *
     * @return smallint 
     */
    public function getLectura()
    {
        return $this->lectura;
    }

    /**
     * Set escritura
     *
     * @param smallint $escritura
     * @return SysGrupopermisos
     */
    public function setEscritura($escritura)
    {
        $this->escritura = $escritura;

        return $this;
    }

    /**
     * Get escritura
     *
     * @return smallint 
     */
    public function getEscritura()
    {
        return $this->escritura;
    }

    /**
     * Set update
     *
     * @param smallint $update
     * @return SysGrupopermisos
     */
    public function setUpdate($update)
    {
        $this->update = $update;

        return $this;
    }

    /**
     * Get update
     *
     * @return smallint 
     */
    public function getUpdate()
    {
        return $this->update;
    }

    /**
     * Set delete
     *
     * @param smallint $delete
     * @return SysGrupopermisos
     */
    public function setDelete($delete)
    {
        $this->borrar = $delete;

        return $this;
    }

    /**
     * Get delete
     *
     * @return smallint 
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * Set gusuid
     *
     * @param \CenMez\ProgBundle\Entity\SysGrupousuarios $gusuid
     * @return SysGrupopermisos
     */
    public function setGusuid(\CenMez\ProgBundle\Entity\SysGrupousuarios $gusuid = null)
    {
        $this->gusuid = $gusuid;

        return $this;
    }

    /**
     * Get gusuid
     *
     * @return \CenMez\ProgBundle\Entity\SysGrupousuarios 
     */
    public function getGusuid()
    {
        return $this->gusuid;
    }

    /**
     * Set modid
     *
     * @param \CenMez\ProgBundle\Entity\SysModulo $modid
     * @return SysGrupopermisos
     */
    public function setModid(\CenMez\ProgBundle\Entity\SysModulo $modid = null)
    {
        $this->modid = $modid;

        return $this;
    }

    /**
     * Get modid
     *
     * @return \CenMez\ProgBundle\Entity\SysModulo 
     */
    public function getModid()
    {
        return $this->modid;
    }
}
