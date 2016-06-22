<?php

namespace CenMez\ProgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CenMez\ProgBundle\Entity\TmMedicamentos;
//use sigaind\ProgBundle\Util\Util;


class MaestrosMedicamentosController extends Controller
{
    /*
     * 
     * MATERIAS PRIMAS
     */
    public function MedicamentosIndexAction()
    {       
        $em = $this->getDoctrine()->getManager();
        
        $usuario = $this->get('security.context')->getToken()->getUser();
        $grupo= $usuario->getGusuid()->getGusuid();
        $permisos = $em->getRepository('ProgBundle:SysGrupopermisos')->PermisosPorGurpoModulo($grupo, 3);
        
        if(!$permisos)
        {
            return $this->render('ProgBundle:Default:SinPermisos.html.twig', array(
                'NombreModulo' => "Maestro Medicamentos",
                'grupo' => $grupo,
                'modulo' => 3
            ));
        }
        else
        {
            return $this->render('ProgBundle:Maestros:Medicamentos/Medicamentos.html.twig', array('permisos' => $permisos));
        }
        
    }
    
    public function allMedicamentosAction()
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
        $usuario = $this->get('security.context')->getToken()->getUser();
        
        $registros= $em->getRepository('ProgBundle:TmMedicamentos')->allMedicamentosPaginados($page,$rp,$sortname,$sortorder,$query, $qtype );
        $regis= $em->getRepository('ProgBundle:TmMedicamentos')->findCantidadMedicamentos($query, $qtype );
        
        $contar2 = $regis['cant'];
        $i=0;
        foreach($registros as $row) 
        {
                        
            $cell[0]=$row['id'];
            $cell[1]=$row['nombre'];
            $cell[2]=$row['presentacion'];
            $cell[3]=$row['unisigla'];
            $cell[4]=$row['volreconstitucion'];
            $cell[5]=$row['vehreconstitucion'];
            $cell[6]=$row['concentacion'];
            $cell[7]=$row['vehdilucion'];
            $cell[8]=$row['volvehdilucion'];
            $cell[9]=$row['estabprodreconstituido'];
            $cell[10]=$row['estabilidadpreparacion'];
            $cell[11]=$row['unidconcentacion'];
            $cell[12]=$row['valorligadopreparacion'];
            $cell[13]=$row['presentacionfarmaceutica'];
            $cell[14]=$row['observacion'];
            $cell[15]=$row['condalmacenamiento'];
            $cell[16]=$row['estnombre'];
            
            $tmp[$i]["id"]=$row['id'];
            $tmp[$i]["cell"]=$cell;
            $i++;
        }
        $json["page"]=$page;
        $json["total"]=$contar2;
        $json["rows"]=$tmp;        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    /*
     * funciones para insertar, eliminar y actualizar las materias primas
     */
    public function dmlMedicamentosAction()
    {
        $json = array();
        $peticion = $this->getRequest();
        $id = $peticion->request->get('id', 0);
        $nom= $peticion->request->get('nom', "");
        $present = $peticion->request->get('present', 0);
        $volreconstitucion = $peticion->request->get('volreconstitucion', 0);
        $vehreconstitucion = $peticion->request->get('vehreconstitucion', 0);
        $concentacion= $peticion->request->get('concentacion', "");
        $vehdilucion = $peticion->request->get('vehdilucion', 0);
        $volvehdilucion = $peticion->request->get('volvehdilucion', 0);
        $estabprodreconstituido = $peticion->request->get('estabprodreconstituido', 0);
        $condalmacenamiento= $peticion->request->get('condalmacenamiento', 0);
        $estabilidadpreparacion= $peticion->request->get('estabilidadpreparacion', "");
        $valorligadopreparacion = $peticion->request->get('valorligadopreparacion', 0);
        $presentacionfarmaceutica = $peticion->request->get('presentacionfarmaceutica', 0);
        $obs = $peticion->request->get('obs', "");
        $unidconcentacion = $peticion->request->get('unidconcentacion', 0);
        $uniId = $peticion->request->get('uniId', 0);
        $est = $peticion->request->get('est', "");
        $borrar = $peticion->request->get('del', 0);
                        	                
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();
        
        if((int)$id>0 && (int)$borrar>0)
        {
            // validar si existen censos con esta enfermedad
            $tt = $em->getRepository('ProgBundle:TtDetalleOrdenproduccion')->findOneBy(array('medicamento' => $id));
            if(\count($tt)>0)
            {                
                $json["error"]=-220;
                $json["mensaje"]="No se pudo eliminar el registro porque existen ordenes de producción relacionados a este medicamento";
                $json["id"]=$id;
            }
            else
            {
                $reg = $em->getRepository('ProgBundle:TmMedicamentos')->find($id);
                if(\count($reg)>0)
                {
                        $em->remove($reg);
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
            }            
        }
        else
        {
            $json= $this->InsertUpdateMedicamentoAction($id, $nom, $present, $volreconstitucion, $vehreconstitucion, $concentacion, $vehdilucion, $volvehdilucion, $estabprodreconstituido, $condalmacenamiento, $estabilidadpreparacion, $valorligadopreparacion, $presentacionfarmaceutica, $obs, $unidconcentacion, $uniId, $est);
        }
        
      
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    
    
    public function InsertUpdateMedicamentoAction($id, $nom, $present, $volreconstitucion, $vehreconstitucion, $concentacion, $vehdilucion, $volvehdilucion, $estabprodreconstituido, $condalmacenamiento, $estabilidadpreparacion, $valorligadopreparacion, $presentacionfarmaceutica, $obs, $unidconcentacion, $uniId, $est)
    {
       $json = array();
       $em = $this->getDoctrine()->getManager();
       if((int)$id==0)
       {
           // validar si ya existe un registro con el mismo nombre o codigo
           $tt = $em->getRepository('ProgBundle:TmMedicamentos')->findOneBy(array('nombre' => $nom));
           if(\count($tt)>0)
           {
               $json["error"]=-120;
               $json["mensaje"]="Ya existe un medicamento con el mismo nombre";               
               $json["id"]=$tt->getId();
           }
           else
           {
               $reg= new TmMedicamentos();
               $reg->setNombre($nom);
               $reg->setPresentacion($present);
               $reg->setConcentacion($concentacion);
               $reg->setCondalmacenamiento($condalmacenamiento);
               $reg->setEstabilidadpreparacion($estabilidadpreparacion);
               $reg->setEstabprodreconstituido($estabprodreconstituido);
               $reg->setObservacion($obs);
               $reg->setPresentacionfarmaceutica($presentacionfarmaceutica);
               $reg->setValorligadopreparacion($valorligadopreparacion);
               $reg->setVehdilucion($vehdilucion);
               $reg->setVehreconstitucion($vehreconstitucion);
               $reg->setVolreconstitucion($volreconstitucion);
               $reg->setVolvehdilucion($volvehdilucion);
               $reg->setUnidconcentacion($em->getRepository('ProgBundle:TmUnidadmedida')->find((int)$unidconcentacion));
               $reg->setUnidid($em->getRepository('ProgBundle:TmUnidadmedida')->find((int)$uniId));
               $reg->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find((int)$est));
               $em->persist($reg);
               $em->flush();
               
               $json["error"]=0;
               $json["mensaje"]="Se inserto con exito el registro.";
               $json["id"]=$reg->getId();
           }
       }
       else
       {
               $reg = $em->getRepository('ProgBundle:TmMedicamentos')->find($id);
               $reg->setNombre($nom);
               $reg->setPresentacion($present);
               $reg->setConcentacion($concentacion);
               $reg->setCondalmacenamiento($condalmacenamiento);
               $reg->setEstabilidadpreparacion($estabilidadpreparacion);
               $reg->setEstabprodreconstituido($estabprodreconstituido);
               $reg->setObservacion($obs);
               $reg->setPresentacionfarmaceutica($presentacionfarmaceutica);
               $reg->setValorligadopreparacion($valorligadopreparacion);
               $reg->setVehdilucion($vehdilucion);
               $reg->setVehreconstitucion($vehreconstitucion);
               $reg->setVolreconstitucion($volreconstitucion);
               $reg->setVolvehdilucion($volvehdilucion);
               $reg->setUnidconcentacion($em->getRepository('ProgBundle:TmUnidadmedida')->find((int)$unidconcentacion));
               $reg->setUnidid($em->getRepository('ProgBundle:TmUnidadmedida')->find((int)$uniId));
               $reg->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find((int)$est));
               $em->persist($reg);
               $em->flush();
               
               $json["error"]=0;
               $json["mensaje"]="Se actualizo con exito el registro.";               
               $json["id"]=$id;
       }
       return $json;
    }
    
    public function consultaMedicamentoIdAction()
    {
        $peticion = $this->getRequest();
        $id = $peticion->request->get('id', 0);
        $em = $this->getDoctrine()->getManager();
        $json= $em->getRepository('ProgBundle:TmMedicamentos')->findMedicamentoId($id);
        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
}
