<?php

namespace CenMez\ProgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CombosController extends Controller
{
    
    public function estadoRegistroAction()
    {           
        $em = $this->getDoctrine()->getManager();
        $dql="SELECT e.estid AS id, e.estnombre as name "
            ." FROM CenMez\ProgBundle\Entity\SysEstadoregistros e ";
        
        $query = $em->createQuery($dql);
        $respuesta= $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        
        return new Response(json_encode($respuesta), 200, array('Content-Type'=>'application/json'));
    }
    
    
    public function GrupoUsuariosAction()
    {           
        $em = $this->getDoctrine()->getManager();
        
        $dql="SELECT g.gusuid AS id, g.gusunombre as name "
            ." FROM CenMez\ProgBundle\Entity\SysGrupousuarios g ";
        $query = $em->createQuery($dql);
        $respuesta= $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        
        return new Response(json_encode($respuesta), 200, array('Content-Type'=>'application/json'));
    }
    
    public function UnidadesDeMedidaAction()
    {           
        $em = $this->getDoctrine()->getManager();
        
        $dql="SELECT U.uniid AS id, U.unisigla as name "
            ." FROM CenMez\ProgBundle\Entity\TmUnidadmedida U ";
        $query = $em->createQuery($dql);
        $respuesta= $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        
        return new Response(json_encode($respuesta), 200, array('Content-Type'=>'application/json'));
    }
}
