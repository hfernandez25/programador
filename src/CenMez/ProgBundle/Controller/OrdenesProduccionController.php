<?php

namespace CenMez\ProgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CenMez\ProgBundle\Entity\TtOrdenProduccion;
use CenMez\ProgBundle\Entity\TtDetalleOrdenproduccion;
use CenMez\ProgBundle\Entity\TtMateriaprimaUtilizada;

class OrdenesProduccionController extends Controller
{
    public function magistralesIndexAction()
    {
        $em = $this->getDoctrine()->getManager();         
        $usuario = $this->get('security.context')->getToken()->getUser();
        $grupo= $usuario->getGusuid()->getGusuid();
        $permisos = $em->getRepository('ProgBundle:SysGrupopermisos')->PermisosPorGurpoModulo($grupo, 1);
        $traId= $usuario->getTraid()->getId();
        if(!$permisos)
        {
            return $this->render('ProgBundle:Default:SinPermisos.html.twig', array(
                'NombreModulo' => "Orden de Producciób Magistrales",
                'grupo' => $usuario->getGusuid()->getGusunombre(),
                'modulo' => 1
            ));
        }
        else
        {
            $traNombre= $usuario->getTraid()->getNombre();
            
            $respuesta = $this->render('ProgBundle:Operacionales:OrdenProduccion/OrdenProduccionMagistrales.html.twig', array(
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
    
    
    public function insertOrdenProduccionMagistralAction()
    {   
        $params = array();
        $json = array();
        $content = $this->get("request")->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, true); 
        }
        
        $usuario = $this->get('security.context')->getToken()->getUser();
        $usuId= $usuario->getUsuid();
        
        $encId = $this->GuardarEncabezadoOrdenAction($params[0], $usuId);
        if($encId>0)
        {
            $fechaProduccion= $params[0]["fe"];
            unset($params[0]);
            $i=0;
            foreach($params as $item)
            {
                $json[$i] = $this->GuardarDetalleOrdenAction($item, $usuId, $encId, $fechaProduccion);
                $i++;
            }
            
            $this->GuardarConsumoRequeridoAction($encId, $usuId);
        }
        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    
    public function GuardarConsumoRequeridoAction($encId, $usuId)
    {
        $em = $this->getDoctrine()->getManager();
        $det= $em->getRepository('ProgBundle:TtDetalleOrdenproduccion')->findDetallesOrdenPorIdEncabezado($encId);
        
        foreach ($det as $item) 
        {
            $materiales= $em->getRepository('ProgBundle:TmRequerimientosproduccion')->findRequerimientosProduccionPorMedId($item["medId"]);
            foreach ($materiales as $mat) 
            {
                $vali= $em->getRepository('ProgBundle:TtMateriaprimaUtilizada')->findMateriaPrimaUtilizadaPorMedMPAndEncabezado($item["medId"], $mat["mpId"], $encId);
                $cantReqe=($mat["cantrequerida"]/$mat["presentacion"])*$item["cantidad"];
                $requeri=0;
                if(($item["cantidad"]-$cantReqe)>=0)
                    $requeri=($item["cantidad"]-$cantReqe);
                
                if(\count($vali)>0)
                {
                    $reg = $em->getRepository('ProgBundle:TtMateriaprimaUtilizada')->find($vali[0]["id"]);
                    $reg->setCosto($mat["costo"]);
                    $reg->setCanttotal($item["cantidad"]);
                    $reg->setCantrequerida($cantReqe);
                    $reg->setCantaprovechamiento($requeri);
                    $reg->setFechaupdate(new \DateTime());
                    $reg->setDetordid($em->getRepository('ProgBundle:TtDetalleOrdenproduccion')->find($item["id"]));
                    $reg->setMpid($em->getRepository('ProgBundle:TmMateriaprima')->find($mat["mpId"]));
                    $reg->setUsuid($em->getRepository('ProgBundle:SysUsuarios')->find($usuId));

                    $em->persist($reg);
                    $em->flush();
                }
                else
                {
                    $reg = new TtMateriaprimaUtilizada();
                    $reg->setCosto($mat["costo"]);
                    $reg->setCanttotal($item["cantidad"]);
                    $reg->setCantrequerida($cantReqe);
                    $reg->setCantaprovechamiento($requeri);
                    $reg->setFechacreate(new \DateTime());
                    $reg->setFechaupdate(new \DateTime());
                    $reg->setDetordid($em->getRepository('ProgBundle:TtDetalleOrdenproduccion')->find($item["id"]));
                    $reg->setMpid($em->getRepository('ProgBundle:TmMateriaprima')->find($mat["mpId"]));
                    $reg->setUsuid($em->getRepository('ProgBundle:SysUsuarios')->find($usuId));
                    $reg->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find(1));

                    $em->persist($reg);
                    $em->flush();
                }
            }
        }
    }
    
    
    public function GuardarDetalleOrdenAction($item, $usuId, $encId, $fechaProduccion)
    {
        $json = array();
        $em = $this->getDoctrine()->getManager();
        if((int)$item["id"]>0)
        { 
            $json = $this->UpdateDetalleOrdenAction($item["id"], $item, $usuId, $fechaProduccion);
        }
        else
        {
            $detalle = $em->getRepository('ProgBundle:TtDetalleOrdenproduccion')->findDetallePorMedAndOrden($item["medId"], $encId);
            if(\count($detalle)>0)
                $json = $this->UpdateDetalleOrdenAction($detalle[0]["id"], $item, $usuId, $fechaProduccion);
            else
                $json = $this->insertDetalleOrdenAction($item, $usuId, $encId, $fechaProduccion);
        }
        
        return $json;
    }
    
    public function UpdateDetalleOrdenAction($id, $item, $usuId,  $fechaProduccion)
    {
        $json = array();
        $em = $this->getDoctrine()->getManager();
        $medicamento = $em->getRepository('ProgBundle:TmMedicamentos')->findMedicamentoId($item["medId"]);
        if(\count($medicamento)>0)
        {
            $fechavencimiento = strtotime ( '+'.$medicamento[0]["estabilidadpreparacion"].' hour' , strtotime ( $fechaProduccion ) );
            $fechavencimiento = date ( 'Y-m-d' , $fechavencimiento );
            
            $det = $em->getRepository('ProgBundle:TtDetalleOrdenproduccion')->find($id);
            $det->setLote($item["lote"]);
            $det->setCantidad($item["cant"]);
            $det->setFechaupdate(new \DateTime());
            $det->setFechavencimiento(new \DateTime($fechavencimiento));
            $det->setMedicamento($em->getRepository('ProgBundle:TmMedicamentos')->find($item["medId"]));
            $det->setUsuid($em->getRepository('ProgBundle:TmTrabajadores')->find($usuId));
            $det->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find(1));

            $em->persist($det);
            $em->flush();
            
            $json["error"]=0;
            $json["mensaje"]="Se inserto el registro con exito";
            $json["id"]=$det->getId();
        }
        else
        {
            $json["error"]=-15;
            $json["mensaje"]="No se encontro el medicamento en la base de datos";
            $json["id"]=0; 
        }
        
        return $json;
    }
    
    public function insertDetalleOrdenAction($item, $usuId, $encId, $fechaProduccion)
    {
        $json = array();
        $em = $this->getDoctrine()->getManager();
        $medicamento = $em->getRepository('ProgBundle:TmMedicamentos')->findMedicamentoId($item["medId"]);
        if(\count($medicamento)>0)
        {
            $fechavencimiento = strtotime ( '+'.$medicamento[0]["estabilidadpreparacion"].' hour' , strtotime ( $fechaProduccion ) );
            $fechavencimiento = date ( 'Y-m-d' , $fechavencimiento );
            
            $det = new TtDetalleOrdenproduccion();
            $det->setLote($item["lote"]);
            $det->setCantidad($item["cant"]);
            $det->setFechacreate(new \DateTime());
            $det->setFechaupdate(new \DateTime());
            $det->setFechavencimiento(new \DateTime($fechavencimiento));
            $det->setIdorden($em->getRepository('ProgBundle:TtOrdenProduccion')->find($encId));
            $det->setMedicamento($em->getRepository('ProgBundle:TmMedicamentos')->find($item["medId"]));
            $det->setUsuid($em->getRepository('ProgBundle:TmTrabajadores')->find($usuId));
            $det->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find(1));

            $em->persist($det);
            $em->flush();
            
            $json["error"]=0;
            $json["mensaje"]="Se inserto el registro con exito";
            $json["id"]=$det->getId();
        }
        else
        {
            $json["error"]=-15;
            $json["mensaje"]="No se encontro el medicamento en la base de datos";
            $json["id"]=0; 
        }
        
        return $json;
    }
    
    
    public function GuardarEncabezadoOrdenAction($item, $usuId)
    {
        $id=0;
        $em = $this->getDoctrine()->getManager();
        if((int)$item["id"]>0)
        { 
            $id = $this->UpdateEncabezadoOrdenAction($item["id"], $item, $usuId);
        }
        else
        {
            $orden = $em->getRepository('ProgBundle:TtOrdenProduccion')->findOrdenesPorCodigoOrFecha($item["orden"], $item["fe"]);
            if(\count($orden)>0)
                $id = $this->UpdateEncabezadoOrdenAction($orden[0]["id"], $item, $usuId);
            else
                $id = $this->insertEncabezadoOrdenAction($item, $usuId);
                
        }
        
        return $id;
    }
    
    public function UpdateEncabezadoOrdenAction($id, $item, $usuId)
    {
        $em = $this->getDoctrine()->getManager();
        $prog = $em->getRepository('ProgBundle:TtOrdenProduccion')->find($id);
        $prog->setFecha(new \DateTime($item["fe"]));
        $prog->setFechaupdate(new \DateTime());
        $prog->setOrdenproduccion($item["orden"]);
        $prog->setObservaciones($item["obs"]);
        $prog->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find(1));
        $prog->setUsuid($em->getRepository('ProgBundle:SysUsuarios')->find($usuId));
        $prog->setIdqf($em->getRepository('ProgBundle:TmTrabajadores')->find($item["traId"]));

        $em->persist($prog);
        $em->flush();
        return $prog->getId();
    }
    
    public function insertEncabezadoOrdenAction($item, $usuId)
    {
        $em = $this->getDoctrine()->getManager();
       
        $prog = new TtOrdenProduccion();
        $prog->setFecha(new \DateTime($item["fe"]));
        $prog->setFechaupdate(new \DateTime());
        $prog->setFechacreate(new \DateTime());
        $prog->setOrdenproduccion($item["orden"]);
        $prog->setObservaciones($item["obs"]);
        $prog->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find(1));
        $prog->setUsuid($em->getRepository('ProgBundle:SysUsuarios')->find($usuId));
        $prog->setIdqf($em->getRepository('ProgBundle:TmTrabajadores')->find($item["traId"]));
        
        $em->persist($prog);
        $em->flush();
        return $prog->getId();
    }
    
    
    
    
    public function allOrdenesProduccionMagistralesAction()
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
        
        
        $registros= $em->getRepository('ProgBundle:TtOrdenProduccion')->allOrdenesProduccionPaginados($page,$rp,$sortname,$sortorder,$query, $qtype );
        $regis= $em->getRepository('ProgBundle:TtOrdenProduccion')->findCantidadOrdenesProduccion($query, $qtype );
                       
        $contar2 = $regis['cant'];
        $i=0;
        foreach($registros as $row) 
        {
            $cell[0]=$row['id'];
            $cell[1]=$row['ordenproduccion'];
            $cell[2]=$row['fecha']->format("Y-m-d");
            $cell[3]=$row['qf'];
            $cell[4]=$row['digitador'];
            $cell[5]=$row['observaciones'];
            
            $tmp[$i]["id"]=$row['id'];
            $tmp[$i]["cell"]=$cell;
            $i++;
        }
        $json["page"]=$page;
        $json["total"]=$contar2;
        $json["rows"]=$tmp;        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    public function consultaOrdenProduccionMagistralesAction()
    {
        $peticion = $this->getRequest();
        $id = $peticion->query->get('id', 0);
        
        $em = $this->getDoctrine()->getManager();
        $json= $em->getRepository('ProgBundle:TtOrdenProduccion')->findOrdenesProduccionId($id);
        
        if(\count($json)>0) 
        {
            $det= $em->getRepository('ProgBundle:TtDetalleOrdenproduccion')->findDetallesOrdenPorIdEncabezado($json[0]["id"]);
            $json[0]["fecha"]=$json[0]["fecha"]->format("Y-m-d");
            $json[1]=$det;
        }
        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    public function delectDetalleOrdenMagistralesAction()
    {
        $peticion = $this->getRequest();
        $id = $peticion->request->get('id', 0);
        
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();
        $usuId= $usuario->getUsuid();
        $reg = $em->getRepository('ProgBundle:TtDetalleOrdenproduccion')->find($id);
        
        if(\count($reg)>0)
        {
            $reg->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find(0));
            $reg->setUsuid($em->getRepository('ProgBundle:TmTrabajadores')->find($usuId));
            $reg->setFechaupdate(new \DateTime());

            $em->persist($reg);
            $em->flush();
            $json["error"]=0;
            $json["mensaje"]="Se borro con exito el registro";
            $json["id"]=$id;
            
            
            $MPU= $em->getRepository('ProgBundle:TtMateriaprimaUtilizada')->findMateriaPrimaUtilizadaPorIdDetalle($id);
            foreach ($MPU as $m) {
                $reg = $em->getRepository('ProgBundle:TtMateriaprimaUtilizada')->find($m["id"]);
                $reg->setFechaupdate(new \DateTime());
                $reg->setUsuid($em->getRepository('ProgBundle:SysUsuarios')->find($usuId));
                $reg->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find(0));

                $em->persist($reg);
                $em->flush();
            }
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
        $detalle= $em->getRepository('ProgBundle:TtMateriaprimaUtilizada')->findMateriaPrimaUtilizadaPorEncId($id);
        
        $empresa["empnombre"] = "SANTA SOFIA";
        $empresa["empnit"] = "Hospital Departamental Universitario de Caldas";

        return $this->render('ProgBundle:Operacionales:OrdenProduccion/ImpresionAlistamiento.html.twig', array(
                    'empresa' => $empresa,
                    'OrdenProduccion' => $OrdenProduccion,
                    'detalle' => $detalle
             ));

    }
    
    /*
     * FUNCION PARA IMPRIMIR EL CONTROL DE CALIDAD DE LA ORDEN
     */
    public function ImprimirControlCalisdadOrdenMagistralAction() 
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

        return $this->render('ProgBundle:Operacionales:OrdenProduccion/ImpresionControlCalidad.html.twig', array(
                    'empresa' => $empresa,
                    'OrdenProduccion' => $OrdenProduccion,
                    'detalle' => $detalle
             ));

    }
}
