<?php

namespace CenMez\ProgBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TtDetalleOrdenproduccionRepository extends EntityRepository
{
    
    public function findDetallePorMedAndOrden($medId, $encId)
    {
        $sql="SELECT D.id "
            ." FROM CenMez\ProgBundle\Entity\TtDetalleOrdenproduccion D "
            ." WHERE D.estid = 1 "
            ." AND (D.medicamento = :med) AND (D.idorden = :orden)  ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('med', $medId)
                ->setParameter('orden', $encId)
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
    
    public function findDetallesOrdenPorIdEncabezado($encId)
    {
        $sql="SELECT D.id, D.cantidad, D.lote, M.id AS medId, M.nombre, M.presentacion, M.concentacion, M.vehreconstitucion, "
            ." M.volreconstitucion, M.vehdilucion, M.volvehdilucion, M.condalmacenamiento, D.fechavencimiento "
            ." FROM CenMez\ProgBundle\Entity\TtDetalleOrdenproduccion D "
            ." LEFT JOIN D.medicamento M  "
            ." WHERE D.estid = 1 "
            ." AND (D.idorden = :orden)  "
            ." ORDER BY D.id asc ";
        return $this->getEntityManager()                
                ->createQuery($sql)
                ->setParameter('orden', $encId)
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
    
    
}
