<?php

namespace CenMez\ProgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CenMez\ProgBundle\Entity\TmRequerimientosproduccion;

class RequerimientosProduccionController extends Controller
{
    public function RequerimientosProduccionIndexAction()
    {
        $em = $this->getDoctrine()->getManager();         
        $usuario = $this->get('security.context')->getToken()->getUser();
        $grupo= $usuario->getGusuid()->getGusuid();
        $permisos = $em->getRepository('ProgBundle:SysGrupopermisos')->PermisosPorGurpoModulo($grupo, 4);
        $traId= $usuario->getTraid()->getId();
        if(!$permisos)
        {
            return $this->render('ProgBundle:Default:SinPermisos.html.twig', array(
                'NombreModulo' => "Maestro Requerimientos Producción",
                'grupo' => $usuario->getGusuid()->getGusunombre(),
                'modulo' => 4
            ));
        }
        else
        {
            $traNombre= $usuario->getTraid()->getNombre();
            
            $respuesta = $this->render('ProgBundle:Operacionales:RequerimientosProduccion/RequerimientosProduccion.html.twig', array(
                'permisos' => $permisos,
                'traid' => $traId,
                'tranombre' => $traNombre,
                'f1' => \date('Y-m-d')
             ));
            
            $etag = md5($respuesta);
            $respuesta->setEtag($etag);
            $respuesta->isNotModified($this->getRequest());
            return $respuesta;
        }
    }
    
    public function allRequerimientosProduccionAction()
    {
        $peticion = $this->getRequest();
        $page = $peticion->request->get('page', 0);
        $rp = $peticion->request->get('rp', 15);
        $sortname = $peticion->request->get('sortname');
        $sortorder = $peticion->request->get('sortorder', 'Desc');        
        $query = $peticion->request->get('query');
        $qtype = $peticion->request->get('qtype', '0');
        $json = array();
        $tmp = array();
        $cell = array();
        $em = $this->getDoctrine()->getManager();
        
        
        $registros= $em->getRepository('ProgBundle:TmRequerimientosproduccion')->allRequerimientosProduccionPaginados($page,$rp,$sortname,$sortorder,$query, $qtype );
        $regis= $em->getRepository('ProgBundle:TmRequerimientosproduccion')->findCantidadRequerimientosProduccion($query, $qtype );
                       
        $contar2 = $regis['cant'];
        $i=0;
        foreach($registros as $row) 
        {
            $cell[0]=$row['id'];
            $cell[1]=$row['medicamento'];
            $cell[2]=$row['materiaprima'];
            $cell[3]=$row['cantrequerida'];
            $cell[4]=$row['unisigla'];
            
            $tmp[$i]["id"]=$row['id'];
            $tmp[$i]["cell"]=$cell;
            $i++;
        }
        $json["page"]=$page;
        $json["total"]=$contar2;
        $json["rows"]=$tmp;        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    
    public function dmlRequerimientosProduccionAction()
    {   
        $params = array();
        $json = array();
        $content = $this->get("request")->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, true); 
        }
        
        $i=0;
        foreach($params as $item)
        {
            $json[$i] = $this->GuardarRequerimientoProduccionAction($item);
            $i++;
        }

        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    public function GuardarRequerimientoProduccionAction($item)
    {
        $json = array();
        $em = $this->getDoctrine()->getManager();
        if((int)$item["id"]>0)
        { 
            $json = $this->UpdateRequerimientoProduccionAction($item["id"], $item);
        }
        else
        {
            $req = $em->getRepository('ProgBundle:TmRequerimientosproduccion')->findRequerimientoProduccionMpIdMedId($item["medId"], $item["mpId"]);
            if(\count($req)>0)
                $json = $this->UpdateRequerimientoProduccionAction($req[0]["id"], $item);
            else
                $json = $this->insertRequerimientoProduccionAction($item);
        }
        
        return $json;
    }
    
    public function UpdateRequerimientoProduccionAction($id, $item)
    {
        $json = array();
        $em = $this->getDoctrine()->getManager();
        
        $reg = $em->getRepository('ProgBundle:TmRequerimientosproduccion')->find($id);
        $reg->setCantrequerida($item["cant"]);
        $reg->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find(1));
        $reg->setMedid($em->getRepository('ProgBundle:TmMedicamentos')->find((int)$item["medId"]));
        $reg->setMpid($em->getRepository('ProgBundle:TmMateriaprima')->find((int)$item["mpId"]));
        $em->persist($reg);
        $em->flush();

        $json["error"]=0;
        $json["mensaje"]="Se inserto el registro con exito";
        $json["id"]=$reg->getId();
        
        return $json;
    }
    
    public function insertRequerimientoProduccionAction($item)
    {
        $json = array();
        $em = $this->getDoctrine()->getManager();
        
        $reg = new TmRequerimientosproduccion();
        $reg->setCantrequerida($item["cant"]);
        $reg->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find(1));
        $reg->setMedid($em->getRepository('ProgBundle:TmMedicamentos')->find((int)$item["medId"]));
        $reg->setMpid($em->getRepository('ProgBundle:TmMateriaprima')->find((int)$item["mpId"]));
        $em->persist($reg);
        $em->flush();

        $json["error"]=0;
        $json["mensaje"]="Se inserto el registro con exito";
        $json["id"]=$reg->getId();
        
        return $json;
    }
    
    public function findRequerimientosProduccionAction()
    {
        $peticion = $this->getRequest();
        $id = $peticion->query->get('id', 0);
        
        $em = $this->getDoctrine()->getManager();
        $json= $em->getRepository('ProgBundle:TmRequerimientosproduccion')->findRequerimientoProduccionId($id);
        
        if(\count($json)>0) 
        {
            $det= $em->getRepository('ProgBundle:TmRequerimientosproduccion')->findRequerimientosProduccionPorMedId($json[0]["medId"]);
            $json[1]=$det;
        }
        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    public function delectRequerimientoProduccionAction()
    {
        $peticion = $this->getRequest();
        $id = $peticion->request->get('id', 0);
        
        $em = $this->getDoctrine()->getManager();
        $reg = $em->getRepository('ProgBundle:TmRequerimientosproduccion')->find($id);
        
        if(\count($reg)>0)
        {
            $reg->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find(0));
            
            $em->persist($reg);
            $em->flush();
            $json["error"]=0;
            $json["mensaje"]="Se borro con exito el registro";               
            $json["id"]=$id;
        }
        else
        {
            $json["error"]=-220;
            $json["mensaje"]="No se pudo eliminar el registro porque no se encontro en la base de datos";               
            $json["id"]=$id;
        }
        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    /*
     * FUNCION PARA IMPRIMIR TIQUETE DE COSECHA
     */
    public function ImprimirOrdenProduccionMagistralAction() 
    {
        $peticion = $this->getRequest();
        $id = $peticion->query->get('id', 0);

        $now = new \DateTime("now");
        $usuario = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $OrdenProduccion= $em->getRepository('ProgBundle:TtOrdenProduccion')->findOrdenesProduccionId($id);
        $detalle= $em->getRepository('ProgBundle:TtDetalleOrdenproduccion')->findDetallesOrdenPorIdEncabezado($id);
        
        $empresa["empnombre"] = "SANTA SOFIA";
        $empresa["empnit"] = "Hospital Departamental Universitario de Caldas";

        return $this->render('ProgBundle:Operacionales:OrdenProduccion/ImpresionOrdenProduccion.html.twig', array(
                    'empresa' => $empresa,
                    'OrdenProduccion' => $OrdenProduccion,
                    'detalle' => $detalle
             ));

    }
    
    /*
     * FUNCION PARA IMPRIMIR EL ALISTAMIENTO DE LA ORDEN
     */
    public function ImprimirAlistamientoOrdenMagistralAction() 
    {
        $peticion = $this->getRequest();
        $id = $peticion->query->get('id', 0);

        $now = new \DateTime("now");
        $usuario = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $OrdenProduccion= $em->getRepository('ProgBundle:TtOrdenProduccion')->findOrdenesProduccionId($id);
        $detalle= $em->getRepository('ProgBundle:TtDetalleOrdenproduccion')->findDetallesOrdenPorIdEncabezado($id);
        
        $empresa["empnombre"] = "SANTA SOFIA";
        $empresa["empnit"] = "Hospital Departamental Universitario de Caldas";

        return $this->render('ProgBundle:Operacionales:OrdenProduccion/ImpresionOrdenProduccion.html.twig', array(
                    'empresa' => $empresa,
                    'OrdenProduccion' => $OrdenProduccion,
                    'detalle' => $detalle
             ));

    }
}
