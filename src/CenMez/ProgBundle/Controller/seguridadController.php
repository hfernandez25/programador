<?php

namespace CenMez\ProgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CenMez\ProgBundle\Entity\SysUsuarios;
use CenMez\ProgBundle\Entity\SysGrupousuarios;
use CenMez\ProgBundle\Entity\SysGrupopermisos;
use CenMez\ProgBundle\Util\Util;

class seguridadController extends Controller
{
    /*
     * INICIO FUNCIONES PARA EL MODULO DE USUARIOS
     */
    public function registroUsuariosAction()
    {
        $em = $this->getDoctrine()->getManager(); 
        $usuario = $this->get('security.context')->getToken()->getUser();
//        $grupo= $usuario->getGusuid()->getGusuid();
//        $empId = $usuario->getEmpid()->getEmpid();
//        $permisos = $em->getRepository('ProgBundle:SysGrupopermisos')->PermisosPorGurpoModulo($grupo, 49, $empId);
//        
//        if(\count($permisos)>0)
//        {
//            return $this->render('ProgBundle:Seguridad:registroUsuarios.html.twig', array(
//                                            'permisos' =>$permisos
//                                        ));
//        }
//        else
//        {
//           $respuesta = $this->render('ProgBundle:Default:SinPermisos.html.twig', array(
//                'NombreModulo' => "Administrar Usuarios",
//                'grupo' => $grupo,
//                'modulo' => 49
//            ));
//           $etag = md5($respuesta);
//           $respuesta->setEtag($etag);
//           $respuesta->isNotModified($this->getRequest());
//           return $respuesta;
//        }
        
        return $this->render('ProgBundle:Seguridad:registroUsuarios.html.twig');
    }        
    
    public function dmlUsuariosAction()
    {
        $json = array();
        $peticion = $this->getRequest();
        $id = $peticion->request->get('id', 0);
        $login = $peticion->request->get('usu', '0');
        $clave = $peticion->request->get('clave', '0');
        $traId = $peticion->request->get('traid', '0');
        $grupo = $peticion->request->get('grupo', '0');
        $estid = $peticion->request->get('estado', '0');
        $borrar = $peticion->request->get('del', '0');
                        	                
        $em = $this->getDoctrine()->getManager();
//        $usuario = $this->get('security.context')->getToken()->getUser();
//        $empId = $usuario->getEmpid()->getEmpid();
        
        if((int)$id>0 && (int)$borrar>0)
        {
            $reg = $em->getRepository('ProgBundle:SysUsuarios')->find($id);
            if(\count($reg)>0)
            {
                    $em->remove($reg);
                    $em->flush();
                    $json["error"]=0;
                    $json["mensaje"]="Se borro con exito el usuario con id: ".$id;               
                    $json["id"]=$id;
            }
            else
            {
                    $json["error"]=-220;
                    $json["mensaje"]="No se pudo eliminar el registro porque no se encontro en la base de datos";               
                    $json["id"]=$id;
            } 
        }
        else
        {
            $json= $this->insertUsuariosAction($id, $login, $clave, $traId, $grupo, $estid);
        }
        
      
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    public function insertUsuariosAction($id, $login, $clave, $traId, $grupo, $estid)
    {
        $json = array();
        $em = $this->getDoctrine()->getManager();
        if($id>0)
        {
           $reg = $em->getRepository('ProgBundle:SysUsuarios')->ValidarLogonExiste($id, $login);
           if(!$reg)
           {
                $usuario = $em->getRepository('ProgBundle:SysUsuarios')->find($id);
                $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
                $usuario->setUpdalogin($login);
                $usuario->setGusuid($em->getRepository('ProgBundle:SysGrupousuarios')->find((int)$grupo));
                $usuario->setTraid($em->getRepository('ProgBundle:TmTrabajadores')->find((int)$traId));
                $usuario->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find((int)$estid));
                $usuario->setSalt(md5(time()));
                $passwordCodificado = $encoder->encodePassword(
                            $clave, $usuario->getSalt()
                   );
                $usuario->setPassword($passwordCodificado);
                $em->persist($usuario);
                $em->flush();

                $json["error"]=0;
                $json["mensaje"]="Se actualizo con exito el registro: ".$usuario->getUsuid();               
                $json["id"]=$usuario->getUsuid();
           }
           else
           {
               $json["error"]=-120;
               $json["mensaje"]="Ya existe un registro con el mismo nombre de usuario ";               
               $json["id"]=$id;
           }
               
        }
        else
        {
           $reg = $em->getRepository('ProgBundle:SysUsuarios')->ValidarLogonExiste($id, $login);
           if(!$reg)
           {
                $usuario = new SysUsuarios();
                $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
                $usuario->setUpdalogin($login);
                $usuario->setGusuid($em->getRepository('ProgBundle:SysGrupousuarios')->find((int)$grupo));
                $usuario->setTraid($em->getRepository('ProgBundle:TmTrabajadores')->find((int)$traId));
                $usuario->setEstid($em->getRepository('ProgBundle:SysEstadoregistros')->find((int)$estid));
                $usuario->setSalt(md5(time()));
                $passwordCodificado = $encoder->encodePassword(
                           $clave, $usuario->getSalt()
                  );
                $usuario->setPassword($passwordCodificado);
                $em->persist($usuario);
                $em->flush();

                $json["error"]=0;
                $json["mensaje"]="Se inserto con exito el registro: ".$usuario->getUsuid();               
                $json["id"]=$usuario->getUsuid();
           }
           else
           {
               $json["error"]=-120;
               $json["mensaje"]="Ya existe un registro con el mismo nombre de usuario ";               
               $json["id"]=$id;
           }
        }                        
        
        return $json;
    }
    
    
    public function allUsuariosAction()
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
        $empId = $usuario->getEmpid()->getEmpid();
        
        $registros= $em->getRepository('ProgBundle:SysUsuarios')->allUsuariosPaginados($page,$rp,$sortname,$sortorder,$query, $qtype, $empId );
        $regis= $em->getRepository('ProgBundle:SysUsuarios')->findCantidadUsuarios($query, $qtype, $empId );
        
        $contar2 = $regis['cant'];
        $i=0;
        foreach($registros as $row) 
        {
            $cell[0]=$row['usuid'];
            $cell[1]=$row['tranombre'];
            $cell[2]=$row['updalogin'];
            $cell[3]=$row['gusunombre'];
            $cell[4]=$row['estnombre'];
            
            $tmp[$i]["id"]=$row['usuid'];
            $tmp[$i]["cell"]=$cell;
            $i++;
        }
        $json["page"]=$page;
        $json["total"]=$contar2;
        $json["rows"]=$tmp;        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    public function consultaUsuarioIdAction()
    {
        $peticion = $this->getRequest();
        $id = $peticion->request->get('id', 0);
        $em = $this->getDoctrine()->getManager();
        $json= $em->getRepository('ProgBundle:SysUsuarios')->findUsuarioId($id);
        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    public function exportarExcelUsuariosAction() {
        $peticion = $this->getRequest();
        $string = $peticion->query->get('StringConsulta', '');
        $campo = $peticion->query->get('CampoConsulta', '0');        

        $fila = 6;
        $maxColumnas = 5;

        $em = $this->getDoctrine()->getManager();

        $usuario = $this->get('security.context')->getToken()->getUser();
        $empresaId = $usuario->getEmpid()->getEmpid();
        $empresa["empnombre"] = $usuario->getEmpid()->getEmpnombre();
        $empresa["empnit"] = $usuario->getEmpid()->getEmpnit();
        //CONSEGUIR LOS DATOS DE LOS LOTES CON PLAGA E INGRESARLOS A LAS CELDAS
        $dataTable= $em->getRepository('ProgBundle:SysUsuarios')->allUsuariosPaginados(1,100000,null,null,$string, $campo, $empresaId );
               
        $informe = "LISTADO MAESTRO USUARIOS ";

        $excelService = $this->get('xls.service_xls2007');
        $borders = array('alignment' => array('horizontal' => "center"), 'font' => array('bold' => true),
            'borders' => array(
                'allborders' => array(
                    'style' => 'thin'
                )
            )
        );
        $borders2 = array('alignment' => array('horizontal' => "center"), 'borders' => array('allborders' => array('style' => 'thin')));
 
        $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue('A' . $fila, 'ID Usuario')
                    ->setCellValue('B' . $fila, 'Nombre Trabajador')
                    ->setCellValue('C' . $fila, 'Usuario')
                    ->setCellValue('D' . $fila, 'Grupo')
                    ->setCellValue('E' . $fila, 'Estado');
        $excelService->excelObj->getActiveSheet()->getStyle('A' . $fila . ':E' . $fila)->applyFromArray($borders);
        //INGRESA LOS VALORES DE LAS CELDAS
        ++$fila;
        foreach ($dataTable as $key => $dat) 
        {
            $excelService->excelObj->getActiveSheet()->setCellValue('A' . $fila, $dat['usuid'])
                    ->setCellValue('B' . $fila, $dat['tranombre'])
                    ->setCellValue('C' . $fila, $dat['updalogin'])
                    ->setCellValue('D' . $fila, $dat['gusunombre'])
                    ->setCellValue('E' . $fila, $dat['estnombre']);
            $excelService->excelObj->getActiveSheet()->getStyle('A' . $fila . ':E' . $fila)->applyFromArray($borders2);
            ++$fila;
        }
        $response = Util::generarExel($excelService, $informe, $maxColumnas, $empresa);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=listado_Maestro_Usuarios.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        return $response;
    }
    /*
     * FIN FUNCIONES PARA EL MODULO DE USUARIOS
     */
    
    /*
     * INICIO FUNCIONES PARA EL MODULO DE GRUPOS
     */
    public function indexGruposAction()
    {
        $em = $this->getDoctrine()->getManager(); 
        $usuario = $this->get('security.context')->getToken()->getUser();
        $grupo= $usuario->getGusuid()->getGusuid();
        $empId = $usuario->getEmpid()->getEmpid();
        $permisos = $em->getRepository('ProgBundle:SysGrupopermisos')->PermisosPorGurpoModulo($grupo, 75, $empId);
        
        if(\count($permisos)>0)
        {
            $maes = $em->getRepository('ProgBundle:SysModulo')->ModulosPorSeccion(1);
            $ope = $em->getRepository('ProgBundle:SysModulo')->ModulosPorSeccion(2);
            $inf = $em->getRepository('ProgBundle:SysModulo')->ModulosPorSeccion(3);
            $sig = $em->getRepository('ProgBundle:SysModulo')->ModulosPorSeccion(4);
            $ger = $em->getRepository('ProgBundle:SysModulo')->ModulosPorSeccion(10);
            $mov = $em->getRepository('ProgBundle:SysModulo')->ModulosPDA(100);
            $respuesta = $this->render('ProgBundle:Seguridad:indexGrupos.html.twig', array(
                                            'permisos' =>$permisos,
                                            'maestros' =>$maes,
                                            'operacional' =>$ope,
                                            'informes' =>$inf,
                                            'sig' =>$sig,
                                            'movil' =>$mov,
                                            'gerencial' =>$ger
                                        ));
            $etag = md5($respuesta);
            $respuesta->setEtag($etag);
            $respuesta->isNotModified($this->getRequest());
            return $respuesta;
        }
        else
        {
           return $this->render('ProgBundle:Default:SinPermisos.html.twig', array(
                'NombreModulo' => "Administrar Grupos",
                'grupo' => $grupo,
                'modulo' => 75
            )); 
        }
    }
    
    public function allGruposAction()
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
        $empId = $usuario->getEmpid()->getEmpid();
        
        $registros= $em->getRepository('ProgBundle:SysGrupousuarios')->allGruposPaginados($page,$rp,$sortname,$sortorder,$query, $qtype, $empId );
        $regis= $em->getRepository('ProgBundle:SysGrupousuarios')->findCantidadGrupos($query, $qtype, $empId );
        
        $contar2 = $regis['cant'];
        $i=0;
        foreach($registros as $row) 
        {
            $cell[0]=$row['gusuid'];
            $cell[1]=$row['gusunombre'];
            
            $tmp[$i]["id"]=$row['gusuid'];
            $tmp[$i]["cell"]=$cell;
            $i++;
        }
        $json["page"]=$page;
        $json["total"]=$contar2;
        $json["rows"]=$tmp;        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    public function dmlGruposAction()
    {
        $json = array();
        $peticion = $this->getRequest();
        $id = $peticion->request->get('id', 0);
        $nombre = $peticion->request->get('nm', '0');
        $borrar = $peticion->request->get('del', '0');
                        	                
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();
        $empId = $usuario->getEmpid()->getEmpid();
        
        if((int)$id>0 && (int)$borrar>0)
        {
            $usu = $em->getRepository('ProgBundle:SysUsuarios')->UsuariosPorGrupo($id);
            if(\count($usu)>0)
            {
                $json["error"]=-100;
                $json["mensaje"]="No se pudo eliminar el grupo porque existen usuarios asociados a él.";               
                $json["id"]=$id;
            }
            else
            {
                $reg = $em->getRepository('ProgBundle:SysGrupousuarios')->find($id);
                if(\count($reg)>0)
                {
                    $em->remove($reg);
                    $em->flush();
                    
                    $dql = " DELETE FROM ProgBundle:SysGrupopermisos g "
                    . " WHERE  g.gusuid =:id";            
                    $em->createQuery($dql)               
                    ->setParameter("id", $id)
                    ->execute();

                    $json["error"]=0;
                    $json["mensaje"]="Se borro con exito el grupo con id: ".$id;               
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
            $json= $this->insertGrupoAction($id, $nombre, $empId);
        }
        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    public function insertGrupoAction($id, $nombre, $empId)
    {
        $json = array();
        $em = $this->getDoctrine()->getManager();
        if($id>0)
        {
           $reg = $em->getRepository('ProgBundle:SysGrupousuarios')->ValidarGrupoExiste($id, $nombre, $empId);
           if(!$reg)
           {
                $grupo = $em->getRepository('ProgBundle:SysGrupousuarios')->find($id);                
                $grupo->setGusunombre($nombre);
                $grupo->setEmpid($em->getRepository('ProgBundle:Empresa')->find((int)$empId));
                $em->persist($grupo);
                $em->flush();

                $json["error"]=0;
                $json["mensaje"]="Se actualizo con exito el registro: ".$grupo->getGusuid();
                $json["id"]=$grupo->getGusuid();
           }
           else
           {
               $json["error"]=-120;
               $json["mensaje"]="Ya existe un registro con el mismo nombre de grupo ";               
               $json["id"]=$id;
           }
               
        }
        else
        {
           $reg = $em->getRepository('ProgBundle:SysGrupousuarios')->ValidarGrupoExiste($id, $nombre, $empId);
           if(!$reg)
           {
                $grupo = new SysGrupousuarios();
                $grupo->setGusunombre($nombre);
                $grupo->setEmpid($em->getRepository('ProgBundle:Empresa')->find((int)$empId));
                $em->persist($grupo);
                $em->flush();

                $json["error"]=0;
                $json["mensaje"]="Se inserto con exito el registro: ".$grupo->getGusuid();
                $json["id"]=$grupo->getGusuid();
           }
           else
           {
               $json["error"]=-120;
               $json["mensaje"]="Ya existe un registro con el mismo nombre de grupo ";               
               $json["id"]=$id;
           }
        }                        
        
        return $json;
    }
    
    
    public function dmlGrupoPermisosAction()
    {
        $params = array();
        $json = array();
        $content = $this->get("request")->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, true); 
        }
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();
        $empId = $usuario->getEmpid()->getEmpid();
        $id=0;
        foreach($params as $item)
        {            
            $per = $em->getRepository('ProgBundle:SysGrupopermisos')->ValidarExistenciaGrupoPermiso($item["grupo"], $item["modulo"], $empId);
            if(\count($per)>0)
            {
                $id=$per[0]['id'];
                $grupo = $em->getRepository('ProgBundle:SysGrupopermisos')->find($id);
                $grupo->setLectura($item["leer"]);
                $grupo->setEscritura($item["escribir"]);
                $grupo->setUpdate($item["actualizar"]);
                $grupo->setGusudelete($item["borrar"]);
                $em->persist($grupo);
            }
            else
            {
                if(($item["leer"]>0) || ($item["escribir"]>0) || ($item["actualizar"]>0) || ($item["borrar"]>0))
                {
                    $grupo = new SysGrupopermisos();
                    $grupo->setModid($em->getRepository('ProgBundle:SysModulo')->find((int)$item["modulo"]));
                    $grupo->setGusuid($em->getRepository('ProgBundle:SysGrupousuarios')->find((int)$item["grupo"]));
                    $grupo->setLectura($item["leer"]);
                    $grupo->setEscritura($item["escribir"]);
                    $grupo->setUpdate($item["actualizar"]);
                    $grupo->setGusudelete($item["borrar"]);
                    $em->persist($grupo);
                }
            }
            $id=$item["grupo"];
        }
                
        $em->flush();
        
        $json["error"]=0;
        $json["mensaje"]="Operación realizada con exito.";               
        $json["id"]=$id;
        
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
    
    public function consultaGrupoIdAction()
    {
        $peticion = $this->getRequest();
        $id = $peticion->request->get('id', 0);
        $em = $this->getDoctrine()->getManager();
        $json= $em->getRepository('ProgBundle:SysGrupousuarios')->findGrupoId($id);
        $cell = $em->getRepository('ProgBundle:SysGrupopermisos')->findAllPermisosUsuario($id);
        
        $tmp = array();                
        $tmp[0]["grupo"]=$json;
        $tmp[0]["det"]=$cell;
        
        
        return new Response(json_encode($tmp), 200, array('Content-Type'=>'application/json'));
    }
    
    public function exportarExcelGruposAction() {
        $peticion = $this->getRequest();
        $string = $peticion->query->get('StringConsulta', '');
        $campo = $peticion->query->get('CampoConsulta', '0');        

        $fila = 6;
        $maxColumnas = 2;

        $em = $this->getDoctrine()->getManager();

        $usuario = $this->get('security.context')->getToken()->getUser();
        $empresaId = $usuario->getEmpid()->getEmpid();
        $empresa["empnombre"] = $usuario->getEmpid()->getEmpnombre();
        $empresa["empnit"] = $usuario->getEmpid()->getEmpnit();
        //CONSEGUIR LOS DATOS DE LOS LOTES CON PLAGA E INGRESARLOS A LAS CELDAS
        $dataTable= $em->getRepository('ProgBundle:SysGrupousuarios')->allGruposPaginados(1,10000,null,null,$string, $campo, $empresaId );
               
        $informe = "LISTADO MAESTRO GRUPOS ";

        $excelService = $this->get('xls.service_xls2007');
        $borders = array('alignment' => array('horizontal' => "center"), 'font' => array('bold' => true),
            'borders' => array(
                'allborders' => array(
                    'style' => 'thin'
                )
            )
        );
        $borders2 = array('alignment' => array('horizontal' => "center"), 'borders' => array('allborders' => array('style' => 'thin')));
 
        $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue('A' . $fila, 'ID Grupo')
                    ->setCellValue('B' . $fila, 'Nombre Grupo');
        $excelService->excelObj->getActiveSheet()->getStyle('A' . $fila . ':B' . $fila)->applyFromArray($borders);
        //INGRESA LOS VALORES DE LAS CELDAS
        ++$fila;
        foreach ($dataTable as $key => $dat) 
        {
            $excelService->excelObj->getActiveSheet()->setCellValue('A' . $fila, $dat['gusuid'])
                    ->setCellValue('B' . $fila, $dat['gusunombre']);
            $excelService->excelObj->getActiveSheet()->getStyle('A' . $fila . ':B' . $fila)->applyFromArray($borders2);
            ++$fila;
        }
        $response = Util::generarExel($excelService, $informe, $maxColumnas, $empresa);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=listado_Maestro_Grupos.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        return $response;
    }
    /*
     * FIN DE LAS FUNCIONES PARA LOS GRUPOS
     */
    
    /*
     * INICIO FUNCIONES PARA AL CAMBIO DE CLAVE
     */
    public function cambiarClaveAction()
    { 
        $usuario = $this->get('security.context')->getToken()->getUser();
        $clave=$usuario->getPtw();
        return $this->render('ProgBundle:Seguridad:cambiarClave.html.twig', array('clave' =>$clave));
    }
    
    public function GuardarNuevaClaveAction()
    {
        $json = array();
        $peticion = $this->getRequest();
        $oldClave = $peticion->request->get('oldClave', '1234');
        $newClave= $peticion->request->get('newClave', '1234');
                        	                
        $em = $this->getDoctrine()->getManager();
        $usu = $this->get('security.context')->getToken()->getUser();
        
        $usuario = $em->getRepository('ProgBundle:SysUsuarios')->find($usu->getUsuid());
        $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);        
        $usuario->setPtw($newClave);
        $usuario->setSalt(md5(time()));
        $passwordCodificado = $encoder->encodePassword(
                    $newClave, $usuario->getSalt()
           );
        $usuario->setPassword($passwordCodificado);
        $em->persist($usuario);
        $em->flush();

        $json["error"]=0;
        $json["mensaje"]="Se cambió con éxito la clave.";               
        $json["id"]=$usuario->getUsuid();
        
      
        return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
    }
}
