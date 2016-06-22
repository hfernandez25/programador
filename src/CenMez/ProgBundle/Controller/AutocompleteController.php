<?php

namespace CenMez\ProgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AutocompleteController extends Controller
{
    public function TrabajadoresAction()
    {
        //OBTENER EL ID DE LA LABOR A ELIMINAR
        $peticion = $this->getRequest();
        $data = $peticion->query->get('term', '');
        //INVOCAR LA CLASE DE DOCTRINE Y ELIMINAR LA LABOR
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a.id AS id, a.nombre AS value FROM ProgBundle:TmTrabajadores a WHERE (a.nombre like :data) ";
        $query = $em->createQuery($dql);
        $query->setParameter('data', '%'.$data.'%');
        $datos = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                
        //ENVIAR LA RESPUESTA
        return new Response(json_encode($datos), 200, array('Content-Type'=>'application/json'));
    }
    
    public function medicamentosFullAction()
    {
        //OBTENER EL ID DE LA LABOR A ELIMINAR
        $peticion = $this->getRequest();
        $data = $peticion->query->get('term', '');
        //INVOCAR LA CLASE DE DOCTRINE Y ELIMINAR LA LABOR
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT M.id AS id, M.nombre AS value, M.presentacion, M.concentacion, M.vehreconstitucion, "
              ." M.volreconstitucion, M.vehdilucion, M.volvehdilucion, M.condalmacenamiento "
              ." FROM ProgBundle:TmMedicamentos M  "
              ." JOIN M.estid ES "
              ." WHERE (M.nombre like :data) AND (ES.estid = 1) ";
        $query = $em->createQuery($dql);
        $query->setParameter('data', '%'.$data.'%');
        $datos = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                
        //ENVIAR LA RESPUESTA
        return new Response(json_encode($datos), 200, array('Content-Type'=>'application/json'));
    }
    
    public function materiaPrimaFullAction()
    {
        //OBTENER EL ID DE LA LABOR A ELIMINAR
        $peticion = $this->getRequest();
        $data = $peticion->query->get('term', '');
        //INVOCAR LA CLASE DE DOCTRINE Y ELIMINAR LA LABOR
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT M.id AS id, M.nombre AS value, U.unisigla "
              ." FROM CenMez\ProgBundle\Entity\TmMateriaprima M "
              ." JOIN M.estid ES "
              ." JOIN M.uniid U "
              ." WHERE ES.estid = 1 AND (M.nombre like :data)";
        
        $query = $em->createQuery($dql);
        $query->setParameter('data', '%'.$data.'%');
        $datos = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                
        //ENVIAR LA RESPUESTA
        return new Response(json_encode($datos), 200, array('Content-Type'=>'application/json'));
    }
    
}
