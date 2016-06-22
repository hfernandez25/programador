<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TtMateriaprimaUtilizadaRepository extends EntityRepository
{
    public function findMateriaPrimaUtilizadaPorIdDetalle($detId)
    {
        $sql="SELECT U.id, U.canttotal, U.cantrequerida, U.cantaprovechamiento, U.costo, "
            ." MP.id as mpId, M.id AS medId, M.nombre "
            ." FROM CenMez\ProgBundle\Entity\TtMateriaprimaUtilizada U "
            ." LEFT JOIN U.detordid D  "
            ." LEFT JOIN U.mpid MP  "
            ." LEFT JOIN D.medicamento M  "
            ." WHERE U.estid = 1 "
            ." AND (D.id = :id)  ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('id', $detId)
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
    
    
    public function findMateriaPrimaUtilizadaPorMedMPAndEncabezado($medId, $mpId, $encId)
    {
        $sql="SELECT U.id, U.canttotal, U.cantrequerida, U.cantaprovechamiento, U.costo, "
            ." MP.id as mpId, M.id AS medId, M.nombre "
            ." FROM CenMez\ProgBundle\Entity\TtMateriaprimaUtilizada U "
            ." LEFT JOIN U.detordid D  "
            ." LEFT JOIN U.mpid MP  "
            ." LEFT JOIN D.medicamento M  "
            ." WHERE U.estid = 1 "
            ." AND (M.id = :medId)  "
            ." AND (MP.id = :mpId)  "
            ." AND (D.idorden = :encId)  ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('medId', $medId)
                ->setParameter('mpId', $mpId)
                ->setParameter('encId', $encId)
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
    
    public function findMateriaPrimaUtilizadaPorEncId($encId)
    {
        $sql="SELECT SUM(U.canttotal) AS canttotal, SUM(U.cantrequerida) AS cantrequerida, SUM(U.cantaprovechamiento) AS cantaprovechamiento, "
            ." MP.id as mpId, MP.nombre "
            ." FROM CenMez\ProgBundle\Entity\TtMateriaprimaUtilizada U "
            ." LEFT JOIN U.detordid D  "
            ." LEFT JOIN U.mpid MP  "
            ." LEFT JOIN D.medicamento M  "
            ." WHERE U.estid = 1 "
            ." AND (D.idorden = :encId)  "
            ." GROUP BY MP.id, MP.nombre ";
        return $this->getEntityManager()
                ->createQuery($sql)
                ->setParameter('encId', $encId)
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    
        
    }
    
    public function findMateriaPrimaUtilizadaPorRangoFechas($f1, $f2)
    {
        $sql="SELECT SUM(U.canttotal) AS canttotal, SUM(U.cantrequerida) AS cantrequerida, SUM(U.cantaprovechamiento) AS cantaprovechamiento, "
            ." MP.id as mpId, MP.nombre "
            ." FROM CenMez\ProgBundle\Entity\TtMateriaprimaUtilizada U "
            ." LEFT JOIN U.detordid D  "
            ." LEFT JOIN U.mpid MP  "
            ." LEFT JOIN D.idorden E  "
            ." WHERE U.estid = 1 "
            ." AND E.estid = 1 "
            ." AND (E.fecha BETWEEN :f1 AND :f2)  "
            ." GROUP BY MP.id, MP.nombre ";
        return $this->getEntityManager()
                ->createQuery($sql)
                ->setParameter('f1', $f1." 00:00:00")
                ->setParameter('f2', $f2." 23:59:59")
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    
        
    }
    
    
    public function findMateriaPrimaUtilizadaPorRangoFechas2($f1, $f2)
    {
        $sql="SELECT SUM(U.canttotal) AS canttotal, SUM(U.cantrequerida) AS cantrequerida, SUM(U.cantaprovechamiento) AS cantaprovechamiento, "
            ." MP.id as mpId, MP.nombre "
            ." FROM CenMez\ProgBundle\Entity\TtMateriaprimaUtilizada U "
            ." LEFT JOIN U.detordid D  "
            ." LEFT JOIN U.mpid MP  "
            ." LEFT JOIN D.idorden E  "
            ." WHERE U.estid = 1 "
            ." AND E.estid = 1 "
            ." AND (E.fecha BETWEEN :f1 AND :f2)  "
            ." GROUP BY MP.id, MP.nombre ";
        return $this->getEntityManager()
                ->createQuery($sql)
                ->setParameter('f1', $f1." 00:00:00")
                ->setParameter('f2', $f2." 23:59:59")
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    
        
    }
    
    
}
