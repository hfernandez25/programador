<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TtOrdenProduccionRepository extends EntityRepository
{
    
    public function findOrdenesPorCodigoOrFecha($orden, $f)
    {
        $sql="SELECT O.id "
            ." FROM CenMez\ProgBundle\Entity\TtOrdenProduccion O "
            ." WHERE O.estid = 1 "
            ." AND ((O.ordenproduccion = :orden) OR (O.fecha BETWEEN :f1 AND :f2) ) ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('orden', $orden)
                ->setParameter('f1', $f." 00:00:00")
                ->setParameter('f2', $f." 23:59:59")
                ->getResult();
    }
    
    /*
     * consulta para la tabla flexigrid 
     */
    public function allOrdenesProduccionPaginados($page,$rp,$sortname,$sortorder,$query, $qtype)
    { 
        $dql=" SELECT O.id, O.fecha, O.ordenproduccion, O.observaciones, Q.nombre AS qf, T.nombre AS digitador "
            ." FROM CenMez\ProgBundle\Entity\TtOrdenProduccion O "
            ." LEFT JOIN O.idqf Q "
            ." LEFT JOIN O.usuid U  "
            ." LEFT JOIN U.traid T  "
            ." LEFT JOIN O.estid ES "
            ." WHERE ES.estid = 1";
        if ($query)
        {
            $dql.=" AND ".$qtype." LIKE '%".$query."%' ";
        }
        if (!$sortname) 
        {
           $dql.= " ORDER BY O.fecha ".$sortorder;
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
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
    
    public function findCantidadOrdenesProduccion($query, $qtype)
    {
         $dql=" SELECT count(O.id) AS cant "
            ." FROM CenMez\ProgBundle\Entity\TtOrdenProduccion O "
            ." LEFT JOIN O.idqf Q "
            ." LEFT JOIN O.usuid U  "
            ." LEFT JOIN U.traid T  "
            ." LEFT JOIN O.estid ES "
            ." WHERE ES.estid = 1";
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
    
    /*
     * consulta para la tabla flexigrid 
     */
    public function findOrdenesProduccionId($id)
    { 
        $dql=" SELECT O.id, O.fecha, O.ordenproduccion, O.observaciones, Q.nombre AS qf, Q.id AS idQf "
            ." FROM CenMez\ProgBundle\Entity\TtOrdenProduccion O "
            ." LEFT JOIN O.idqf Q "
            ." LEFT JOIN O.usuid U  "
            ." LEFT JOIN U.traid T  "
            ." LEFT JOIN O.estid ES "
            ." WHERE ES.estid = 1"
            ." AND O.id = :id ";
        
        return $this->getEntityManager()                
                ->createQuery($dql)
                ->setParameter('id', $id)
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
}
