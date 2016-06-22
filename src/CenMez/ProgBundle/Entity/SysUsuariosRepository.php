<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SysUsuariosRepository extends EntityRepository
{
    
    public function allUsuariosActivosPorEmpresa($empId)
    {
        $sql="SELECT u.usuid, u.updalogin, u.ptw, e.empid, t.traid, g.gusuid, u.activoGPS, u.seguimiento, u.tipoInicio "
            ." FROM CenMez\ProgBundle\Entity\SysUsuarios u "
            ." JOIN u.gusuid g "
            ." JOIN u.empid e "
            ." JOIN u.traid t "
            ." WHERE e.empid = :empid ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('empid', $empId)
                ->getResult();
    }
    
    public function allUsuariosConModulosPDA($empId)
    {
        $sql="SELECT u.usuid, u.updalogin, e.empid, t.traid, t.tranombre, "
            ." t.tracodigo, u.activoGPS, u.seguimiento "
            ." FROM CenMez\ProgBundle\Entity\SysUsuarios u "
            ." JOIN u.gusuid g "
            ." JOIN u.empid e "
            ." JOIN u.traid t "
            ." LEFT JOIN sigaind\siagBundle\Entity\SysGrupopermisos GP WHERE (g.gusuid = GP.gusuid) "
            ." LEFT JOIN GP.modid m "
            ." WHERE m.modulo = 100 "
            ." AND e.empid = :empid "
            ." GROUP BY u.usuid, u.updalogin, e.empid, t.traid, t.tranombre, t.tracodigo ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('empid', $empId)
                ->getResult();
    }
    
    public function findEstadoUsuarioSeguimiento($traid)
    {
        $sql="SELECT u.usuid, u.updalogin, u.ptw, e.empid, t.traid, g.gusuid, u.activoGPS, u.seguimiento "
            ." FROM CenMez\ProgBundle\Entity\SysUsuarios u "
            ." JOIN u.gusuid g "
            ." JOIN u.empid e "
            ." JOIN u.traid t "
            ." WHERE t.traid=:traid ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('traid', $traid)
                ->getResult();
    }
    
    public function allUsuariosPaginados($page,$rp,$sortname,$sortorder,$query, $qtype, $empId)
    { 
        $dql="SELECT u.usuid, u.updalogin, u.ptw, e.empid, t.traid, g.gusuid, g.gusunombre, t.tranombre, et.estnombre, u.tipoInicio "
            ." FROM CenMez\ProgBundle\Entity\SysUsuarios u "
            ." JOIN u.gusuid g "
            ." JOIN u.empid e "
            ." JOIN u.traid t "
            ." JOIN u.estid et "
            ." WHERE e.empid = :empresa AND u.updalogin<> 'sigaind' ";
        if ($query)
        {
            $dql.=" AND ".$qtype." LIKE '%".$query."%' ";
        }
        if (!$sortname) 
        {
           $dql.= " ORDER BY u.usuid ".$sortorder;
        }
        else
        {
           $dql.= " ORDER BY ".$sortname." ". $sortorder;
        }
        
        $start = (($page-1) * $rp);
        
        return $this->getEntityManager()                
                ->createQuery($dql)
                ->setParameter('empresa', $empId)
                ->setFirstResult($start)
                ->setMaxResults($rp)
                ->getResult();
    }
    
    public function findCantidadUsuarios($query, $qtype, $empId)
    { 
        $dql="SELECT count(u.usuid) AS cant "
            ." FROM CenMez\ProgBundle\Entity\SysUsuarios u "
            ." JOIN u.gusuid g "
            ." JOIN u.empid e "
            ." JOIN u.traid t "
            ." WHERE e.empid = :e AND u.updalogin<> 'sigaind' ";
        if ($query)
        {
            $dql.=" AND ".$qtype." LIKE '%".$query."%' ";
        }        
        
        $query = $this->getEntityManager()                
                ->createQuery($dql)
                ->setParameter('e', $empId);
        try 
        {
            $result = $query->getSingleResult();
        } 
        catch (\Doctrine\Orm\NoResultException $e) {
            $result = null;
        }
        
        return $result;
    }
    
    
    public function findUsuarioId($usuid)
    {
        $sql="SELECT u.usuid, u.updalogin, u.ptw, e.empid, t.traid, g.gusuid, g.gusunombre, t.tranombre, et.estnombre, et.estid, u.tipoInicio "
            ." FROM CenMez\ProgBundle\Entity\SysUsuarios u "
            ." JOIN u.gusuid g "
            ." JOIN u.empid e "
            ." JOIN u.traid t "
            ." JOIN u.estid et "
            ." WHERE u.usuid=:usu ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('usu', $usuid)
                ->getResult();
    }
    
    public function ValidarLogonExiste($usuid, $updalogin)
    {
        $sql="SELECT u.usuid "
            ." FROM CenMez\ProgBundle\Entity\SysUsuarios u "
            ." WHERE u.updalogin=:logon AND u.usuid<>:usu ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('logon', $updalogin)
                ->setParameter('usu', $usuid)
                ->getResult();
    }
    
     //LISTADO DE USUARIOS QUE PERTENECEN A UN GRUPO ESPECIFICO
    public function UsuariosPorGrupo($grupo)
    {
        $em = $this->getEntityManager();
        $dql="SELECT u.usuid, u.updalogin, u.ptw, e.empid, t.traid, g.gusuid, g.gusunombre, t.tranombre, et.estnombre, et.estid "
            ." FROM sigaind\siagBundle\Entity\SysUsuarios u "
            ." JOIN u.gusuid g "
            ." JOIN u.empid e "
            ." JOIN u.traid t "
            ." JOIN u.estid et "
            ." WHERE g.gusuid=:gru ";
        $query = $em->createQuery($dql);
        $query->setParameter('gru', $grupo);
        $datos = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        
        return $datos;
    }
    
}
