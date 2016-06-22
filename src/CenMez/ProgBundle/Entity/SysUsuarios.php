<?php

namespace CenMez\ProgBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * SysUsuarios
 *
 * @ORM\Table(name="sys_usuarios", indexes={@ORM\Index(name="FK_SYS_usuarios_SYS_GrupoUsuarios", columns={"gusuId"}), @ORM\Index(name="f_trabajador_usuario_idx", columns={"traId"}), @ORM\Index(name="fk_esta_usuario_idx", columns={"estId"})})
 * @ORM\Entity(repositoryClass="CenMez\ProgBundle\Entity\SysUsuariosRepository")
 */
class SysUsuarios implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="usuId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $usuid;

    /**
     * @var string
     *
     * @ORM\Column(name="updaLogin", type="string", length=20, nullable=true)
     */
    private $updalogin;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=200, nullable=true)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="cenId", type="integer", nullable=true)
     */
    private $cenid;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=200, nullable=true)
     */
    private $salt;

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
     * @var \SysGrupousuarios
     *
     * @ORM\ManyToOne(targetEntity="SysGrupousuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gusuId", referencedColumnName="gusuId")
     * })
     */
    private $gusuid;

    /**
     * @var \TmTrabajadores
     *
     * @ORM\ManyToOne(targetEntity="TmTrabajadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="traId", referencedColumnName="id")
     * })
     */
    private $traid;



    /**
     * Get usuid
     *
     * @return integer 
     */
    public function getUsuid()
    {
        return $this->usuid;
    }

    /**
     * Set updalogin
     *
     * @param string $updalogin
     * @return SysUsuarios
     */
    public function setUpdalogin($updalogin)
    {
        $this->updalogin = $updalogin;

        return $this;
    }

    /**
     * Get updalogin
     *
     * @return string 
     */
    public function getUpdalogin()
    {
        return $this->updalogin;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return SysUsuarios
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set cenid
     *
     * @param integer $cenid
     * @return SysUsuarios
     */
    public function setCenid($cenid)
    {
        $this->cenid = $cenid;

        return $this;
    }

    /**
     * Get cenid
     *
     * @return integer 
     */
    public function getCenid()
    {
        return $this->cenid;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return SysUsuarios
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set estid
     *
     * @param \CenMez\ProgBundle\Entity\SysEstadoregistros $estid
     * @return SysUsuarios
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
     * Set gusuid
     *
     * @param \CenMez\ProgBundle\Entity\SysGrupousuarios $gusuid
     * @return SysUsuarios
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
     * Set traid
     *
     * @param \CenMez\ProgBundle\Entity\TmTrabajadores $traid
     * @return SysUsuarios
     */
    public function setTraid(\CenMez\ProgBundle\Entity\TmTrabajadores $traid = null)
    {
        $this->traid = $traid;

        return $this;
    }

    /**
     * Get traid
     *
     * @return \CenMez\ProgBundle\Entity\TmTrabajadores 
     */
    public function getTraid()
    {
        return $this->traid;
    }
    
    
    function eraseCredentials()
    {
    }
    function getRoles()
    {
        return array('ROLE_USUARIO');
    }
    function getUsername()
    {
        return $this->getUpdalogin();
    }
    
    public function serialize()
    {
       return serialize($this->getUsuid());
    }
 
    public function unserialize($data)
    {
        $this->usuid = unserialize($data);
    }
}
