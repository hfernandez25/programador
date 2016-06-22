<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TmMateriaprimaRepository extends EntityRepository
{
    
    public function allProveedoresActivosPorEmpresa($empId)
    {
        $sql="SELECT M.id, M.nombre, M.presentacion, U.unisigla, M.costo, ES.estid, ES.estnombre "
            ." FROM CenMez\ProgBundle\Entity\TmMateriaprima M "
            ." JOIN M.estid ES "
            ." JOIN M.uniid U "
            ." WHERE ES.estid = 1 ";
        
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('empresa', $empId)
                ->getResult();
    }
    
    
    public function allMateriasPrimasPaginados($page,$rp,$sortname,$sortorder,$query, $qtype)
    { 
        $dql="SELECT M.id, M.nombre, M.presentacion, U.uniid, U.unisigla, M.costo, ES.estid, ES.estnombre "
            ." FROM CenMez\ProgBundle\Entity\TmMateriaprima M "
            ." JOIN M.estid ES "
            ." JOIN M.uniid U "
            ." WHERE ES.estid IN (0,1) ";
        if ($query)
        {
            $dql.=" AND ".$qtype." LIKE '%".$query."%' ";
        }
        if (!$sortname) 
        {
           $dql.= " ORDER BY M.id ".$sortorder;
        }
        else
        {
           $dql.= " ORDER BY ".$sortname." ". $sortorder;
        }
        
        $start = (($page-1) * $rp);
        
        return $this->getEntityManager()                
                ->createQuery($dql)
                ->setFirstResult($start)
                ->setMaxResults($rp)
                ->getResult();
    }
    
    
    
    public function findCantidadMateriasPrimas($query, $qtype)
    { 
        $dql="SELECT count(M.id) AS cant "
            ." FROM CenMez\ProgBundle\Entity\TmMateriaprima M "
            ." JOIN M.estid ES "
            ." JOIN M.uniid U "
            ." WHERE ES.estid IN (0,1) ";
        if ($query)
        {
            $dql.=" AND ".$qtype." LIKE '%".$query."%' ";
        }        
        
        $query = $this->getEntityManager()                
                ->createQuery($dql);
        try 
        {
            $result = $query->getSingleResult();
        } 
        catch (\Doctrine\Orm\NoResultException $e) {
            $result = null;
        }
        
        return $result;
    }
    
    public function MateriaPrimaPorId($id)
    { 
        $dql="SELECT M.id, M.nombre, M.presentacion, U.uniid, U.unisigla, M.costo, ES.estid, ES.estnombre "
            ." FROM CenMez\ProgBundle\Entity\TmMateriaprima M "
            ." JOIN M.estid ES "
            ." JOIN M.uniid U "
            ." WHERE M.id = :id ";
        return $this->getEntityManager()                
                ->createQuery($dql)
                ->setParameter('id', $id)
                ->getResult();
    }
    
    public function ValidarCodigo($id, $codigo)
    { 
        $dql="SELECT p.proid, p.procodigo "
            ." FROM CenMez\ProgBundle\Entity\TmProductoquimico p "
            ." WHERE p.proid <> :id "
            ." AND p.procodigo= :cod ";
        return $this->getEntityManager()                
                ->createQuery($dql)
                ->setParameter('id', $id)
                ->setParameter('cod', $codigo)
                ->getResult();
    }
}