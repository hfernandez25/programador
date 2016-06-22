<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TmRequerimientosproduccionRepository extends EntityRepository
{
    
    public function findRequerimientoProduccionId($id)
    {
        $sql=" SELECT R.id, R.cantrequerida, M.nombre AS medicamento, MP.nombre AS materiaprima, U.uniid, U.unisigla, ES.estid, ES.estnombre, "
            ." M.id AS medId, MP.id AS mpId, MP.presentacion, MP.costo "
            ." FROM CenMez\ProgBundle\Entity\TmRequerimientosproduccion R "
            ." JOIN R.estid ES "
            ." JOIN R.medid M "
            ." JOIN R.mpid MP "
            ." JOIN MP.uniid U "
            ." WHERE R.id = :id "
            ." AND ES.estid=1 ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('id', $id)
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    
    }
    
    public function allRequerimientosProduccionPaginados($page,$rp,$sortname,$sortorder,$query, $qtype)
    { 
        $dql=" SELECT R.id, R.cantrequerida, M.nombre AS medicamento, MP.nombre AS materiaprima, U.uniid, U.unisigla, ES.estid, ES.estnombre "
            ." FROM CenMez\ProgBundle\Entity\TmRequerimientosproduccion R "
            ." JOIN R.estid ES "
            ." JOIN R.medid M "
            ." JOIN R.mpid MP "
            ." JOIN MP.uniid U "
            ." WHERE ES.estid =1 ";
        if ($query)
        {
            $dql.=" AND ".$qtype." LIKE '%".$query."%' ";
        }
        if (!$sortname) 
        {
           $dql.= " ORDER BY R.id ".$sortorder;
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
    
    
    
    public function findCantidadRequerimientosProduccion($query, $qtype)
    { 
        $dql="SELECT count(R.id) AS cant "
           ." FROM CenMez\ProgBundle\Entity\TmRequerimientosproduccion R "
            ." JOIN R.estid ES "
            ." JOIN R.medid M "
            ." JOIN R.mpid MP "
            ." JOIN MP.uniid U "
            ." WHERE ES.estid =1";
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
    
    public function findRequerimientoProduccionMpIdMedId($medId, $mpId)
    {
        $sql=" SELECT R.id "
            ." FROM CenMez\ProgBundle\Entity\TmRequerimientosproduccion R "
            ." JOIN R.estid ES "
            ." JOIN R.medid M "
            ." JOIN R.mpid MP "
            ." WHERE M.id = :medId "
            ." AND MP.id = :mp"
            ." AND ES.estid=1 ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('medId', $medId)
                ->setParameter('mp', $mpId)
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    
    }
    
    public function findRequerimientosProduccionPorMedId($medId)
    {
        $sql=" SELECT R.id, R.cantrequerida, M.nombre AS medicamento, MP.nombre AS materiaprima, U.uniid, U.unisigla, ES.estid, ES.estnombre, "
            ." M.id AS medId, MP.id AS mpId, MP.presentacion, MP.costo "
            ." FROM CenMez\ProgBundle\Entity\TmRequerimientosproduccion R "
            ." JOIN R.estid ES "
            ." JOIN R.medid M "
            ." JOIN R.mpid MP "
            ." JOIN MP.uniid U "
            ." WHERE M.id = :id "
            ." AND ES.estid=1 ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('id', $medId)
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    
    }
    
}
