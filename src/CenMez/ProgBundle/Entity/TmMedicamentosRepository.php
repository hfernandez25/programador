<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TmMedicamentosRepository extends EntityRepository
{
    
    public function findMedicamentoId($medId)
    {
        $sql=" SELECT M.id, M.nombre, M.presentacion, M.volreconstitucion, M.vehreconstitucion, M.concentacion, "
            ." M.vehdilucion, M.volvehdilucion, M.estabprodreconstituido, M.condalmacenamiento, M.estabilidadpreparacion, "
            ." M.valorligadopreparacion, M.presentacionfarmaceutica, M.observacion, ES.estid, ES.estnombre, "
            ." UC.uniid AS unidconcentacionId, UC.unisigla AS unidconcentacion, U.uniid, U.unisigla "
            ." FROM CenMez\ProgBundle\Entity\TmMedicamentos M "
            ." JOIN M.estid ES "
            ." JOIN M.unidconcentacion UC "
            ." JOIN M.unidid U "
            ." WHERE M.id = :id ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('id', $medId)
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    
    }
    
    public function allMedicamentosPaginados($page,$rp,$sortname,$sortorder,$query, $qtype)
    { 
        $dql=" SELECT M.id, M.nombre, M.presentacion, M.volreconstitucion, M.vehreconstitucion, M.concentacion, "
            ." M.vehdilucion, M.volvehdilucion, M.estabprodreconstituido, M.condalmacenamiento, M.estabilidadpreparacion, "
            ." M.valorligadopreparacion, M.presentacionfarmaceutica, M.observacion, ES.estid, ES.estnombre, "
            ." UC.uniid AS unidconcentacionId, UC.unisigla AS unidconcentacion, U.uniid, U.unisigla "
            ." FROM CenMez\ProgBundle\Entity\TmMedicamentos M "
            ." JOIN M.estid ES "
            ." JOIN M.unidconcentacion UC "
            ." JOIN M.unidid U "
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
    
    
    
    public function findCantidadMedicamentos($query, $qtype)
    { 
        $dql="SELECT count(M.id) AS cant "
            ." FROM CenMez\ProgBundle\Entity\TmMedicamentos M "
            ." JOIN M.estid ES "
            ." JOIN M.unidconcentacion UC "
            ." JOIN M.unidid U "
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
    
}
