<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SysGrupopermisosRepository extends EntityRepository
{
    
    public function allPermisosUsuario($grupo)
    {
        $sql="SELECT p.id, g.gusuid, m.menu AS modid, p.lectura, p.escritura, p.update, p.delete "
            ." FROM CenMez\ProgBundle\Entity\SysGrupopermisos p "                
            ." JOIN p.gusuid g "
            ." JOIN p.modid m "
            ." WHERE m.modulo = 100 "
            ." AND g.gusuid = :grupo ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('grupo', $grupo)
                ->getResult();
    }
    
    public function PermisosPorGurpoModulo($grupo,$modulo)
    {
        $sql="SELECT p.lectura, p.escritura, p.update, p.delete "
            ." FROM CenMez\ProgBundle\Entity\SysGrupopermisos p "                
            ." JOIN p.gusuid g "
            ." JOIN p.modid m "
            ." WHERE g.gusuid = :grupo "
            ." AND m.modid = :modulo "
            ." AND (p.lectura =1 OR p.escritura=1 OR p.update =1 OR p.delete=1) ";
        return $this->getEntityManager()
                ->createQuery($sql)
                ->setParameter('grupo', $grupo)
                ->setParameter('modulo', $modulo)
                ->getResult();
    }
    
    public function ValidarExistenciaGrupoPermiso($grupo,$modulo, $empid)
    {
        $sql="SELECT p.id "
            ." FROM CenMez\ProgBundle\Entity\SysGrupopermisos p "                
            ." JOIN p.gusuid g "
            ." JOIN p.modid m "
            ." WHERE g.gusuid = :grupo "
            ." AND m.modid = :modulo "
            ." AND g.empid = :empid ";
        return $this->getEntityManager()
                ->createQuery($sql)
                ->setParameter('grupo', $grupo)
                ->setParameter('modulo', $modulo)
                ->setParameter('empid', $empid)
                ->getResult();
    }
    
    public function findAllPermisosUsuario($grupo)
    {
        $sql="SELECT p.id, g.gusuid, m.modid, p.lectura, p.escritura, p.update, p.delete "
            ." FROM CenMez\ProgBundle\Entity\SysGrupopermisos p "                
            ." JOIN p.gusuid g "
            ." JOIN p.modid m "
            ." WHERE g.gusuid = :grupo ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('grupo', $grupo)
                ->getResult();
    }
}