<?php

namespace CenMez\ProgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CenMez\ProgBundle\Entity\TmMateriaprima;
//use sigaind\ProgBundle\Util\Util;


class MaestrosMateriasPrimasController extends Controller
{
    /*
     * 
     * MATERIAS PRIMAS
     */
    public function MateriasPrimasIndexAction()
    {       
        $em = $this->getDoctrine()->getManager();
        
        $usuario = $this->get('security.context')->getToken()->getUser();
        $grupo= $usuario->getGusuid()->getGusuid();
        $permisos = $em->getRepository('ProgBundle:SysGrupopermisos')->PermisosPorGurpoModulo($grupo, 2);
        
        if(!$permisos)
        {
            return $this->render('ProgBundle:Default:SinPermisos.html.twig', array(
                'NombreModulo' => "Maestro Materias Primas",
                'grupo' => $grupo,
                'modulo' => 2
            ));
        }
        else
        {
            return $this->render('ProgBundle:Maestros:MateriasPrimas/MateriasPrimas.html.twig', array('permisos' => $permisos));
        }
        
    }
    
    public function allMateriasPrimasAction()
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
        
        $registros= $em->getRepository('ProgBundle:TmMateriaprima')->allMateriasPrimasPaginados($page,$rp,$sortname,$sortorder,$query, $qtype );
        $regis= $em->getRepository('ProgBundle:TmMateriaprima')->findCantidadMateriasPrimas($query, $qtype );
        
        $contar2 = $regis['cant'];
        $i=0;
        foreach($registros as $row) 
        {
                        
            $cell[0]=$row['id'];
            $cell[1]=$row['nombre'];
            $cell[2]=$row['presentacion'];
            $cell[3]=$row['unisigla'];
            $cell[4]=$row['costo'];
            $cell[5]=$row['estnombre'];
            
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
    public function dmlMateriaPrimaAction()
    {
        $json = array();
        $peticion = $this->getRequest();
        $id = $peticion->request->get('id', 0);
        $nom= $peticion->request->get('nom', "");
        $costo = $peticion->request->get('costo', 0);
        $present = $peticion->request->get('present', 0);
        $uniId = $peticion->request->get('uniId', 0);
        $est = $peticion->request->get('est', "");
        $borrar = $peticion->request->get('del', 0);
                        	                
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();
        
        if((int)$id>0 && (int)$borrar>0)
        {
            // validar si existen censos con esta enfermedad
            $tt = $em->getRepository('ProgBundle:TmRequerimientosproduccion')->findOneBy(array('mpid' => $id));
            if(\count($tt)>0)
            {                
                $json["error"]=-220;
                $json["mensaje"]="No se pudo eliminar el registro porque existen ordenes de producción relacionados a esta materia prima";
                $json["id"]=$id;
            }
            else
            {
                $reg = $em->getRepository('ProgBundle:TmMateriaprima')->find($id);
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
            $json= $this->InsertUpdateMateriaPrimaAction($id, $nom, $costo, $present, $uniId, $est);
        }
        
      
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    
    
    public function InsertUpdateMateriaPrimaAction($id, $nom, $costo, $present, $uniId, $est)
    {
       $json = array();
       $em = $this->getDoctrine()->getManager();
       if((int)$id==0)
       {
           // validar si ya existe un registro con el mismo nombre o codigo
           $tt = $em->getRepository('ProgBundle:TmMateriaprima')->findOneBy(array('nombre' => $nom));
           if(\count($tt)>0)
           {
               $json["error"]=-120;
               $json["mensaje"]="Ya existe una materia prima con el mismo nombre";               
               $json["id"]=$tt->getId();
           }
           else
           {
               $reg= new TmMateriaprima();
               $reg->setNombre($nom);
               $reg->setCosto($costo);
               $reg->setPresentacion($present);
               $reg->setUniid($em->getRepository('ProgBundle:TmUnidadmedida')->find((int)$uniId));
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
               $reg = $em->getRepository('ProgBundle:TmMateriaprima')->find($id);
               $reg->setNombre($nom);
               $reg->setCosto($costo);
               $reg->setPresentacion($present);
               $reg->setUniid($em->getRepository('ProgBundle:TmUnidadmedida')->find((int)$uniId));
               $reg->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find((int)$est));
               $em->persist($reg);
               $em->flush();
               
               $json["error"]=0;
               $json["mensaje"]="Se actualizo con exito el registro.";               
               $json["id"]=$id;
       }
       return $json;
    }
    
    public function consultaMateriaPrimaIdAction()
    {
        $peticion = $this->getRequest();
        $id = $peticion->request->get('id', 0);
        $em = $this->getDoctrine()->getManager();
        $json= $em->getRepository('ProgBundle:TmMateriaprima')->MateriaPrimaPorId($id);
        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    /*
     * funcion para exportar elementos del plan de fertilizacion
     */
    public function exportarProveedoresAction() {
        $peticion = $this->getRequest();
        $string = $peticion->query->get('StringConsulta', '');
        $campo = $peticion->query->get('CampoConsulta', '0');
        $tipo = $peticion->query->get('tipo', 'Excel');

        $em = $this->getDoctrine()->getManager();

        $usuario = $this->get('security.context')->getToken()->getUser();
        $empresaId = $usuario->getEmpid()->getEmpid();
        $empresa["empnombre"] = $usuario->getEmpid()->getEmpnombre();
        $empresa["empnit"] = $usuario->getEmpid()->getEmpnit();
     
        //CONSULTA        
        $dt= $em->getRepository('ProgBundle:TmProveedor')->allProveedoresPaginados(1,10000,null,null,$string, $campo, $empresaId );
               
        $titulo = "LISTADO MAESTRO PROVEEDORES";
        $NomArchivo = "Listado_Proveedores";
        if($tipo==="PDF")
            $f = $this->get('slik_dompdf');
        else
            $f=$this->get('xls.service_xls2007');
        
        $arrayTitulos=array("ID", "NOMBRE", "NIT", "REPRESENTANTE", "TEL CONTACTO", "ESTADO");
        $camp=array();
        $camp[0]= array("campo"=>"id", "tipo"=>"int");
        $camp[1]= array("campo"=>"pronombre", "tipo"=>"string");
        $camp[2]= array("campo"=>"pronit", "tipo"=>"string");
        $camp[3]= array("campo"=>"prorepresentante", "tipo"=>"string");
        $camp[4]= array("campo"=>"procelular", "tipo"=>"string");
        $camp[5]= array("campo"=>"estnombre", "tipo"=>"string");
       
        $response= Export2Excel::TipoExport($dt, $empresa, $titulo, $NomArchivo, $arrayTitulos, $camp, $f, $tipo);
        return $response;
    }
}
