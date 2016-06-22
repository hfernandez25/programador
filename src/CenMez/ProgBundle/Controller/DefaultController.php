<?php

namespace CenMez\ProgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{   
    public function loginAction()
    {
        $peticion = $this->getRequest();
        $sesion = $peticion->getSession();
        $error = $peticion->attributes->get(
            SecurityContext::AUTHENTICATION_ERROR,
            $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );
        return $this->render('ProgBundle:Default:login.html.twig', array(
            'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
            'error' => $error
        ));
    }
    
    public function logoutAction()
    {
        $this->get('security.context')->setToken(null); 
        $this->get('request')->getSession()->invalidate();
        
        return new RedirectResponse($this->get("router")->generate("usuario_login"));
        
    }
    
    public function UsuarioLogueadoAction()
    {
        $usuario = $this->get('security.context')->getToken()->getUser();
        $respuesta = $this->render('ProgBundle:Default:UsuarioLogueado.html.twig', array(
                                            'nombre' => $usuario->getTraid()->getNombre()
                                    ));
        $respuesta->setMaxAge(5);
        $respuesta->setPrivate();
        return $respuesta;
    }
    
    public function menu2Action()
    {
        $respuesta = $this->render('ProgBundle::Menu2.html.twig');
        $respuesta->setSharedMaxAge(60 * 60);
        return $respuesta;
    }
    
    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();
        $grupo= $usuario->getGusuid()->getGusuid();
        //$permisos = $em->getRepository('siagBundle:SysGrupopermisos')->PermisosPorGurpoModulo($grupo, 84, $empId);
        
        $hoy = date("Y-m-d");
        $datos = $this->DatosDashboard($hoy);
        $respuesta = $this->render('ProgBundle:Default:dashboard.html.twig', array(
            'datos' => $datos,
            'fecha' => $hoy
        ));

       $etag = md5($respuesta);
       $respuesta->setEtag($etag);
       $respuesta->isNotModified($this->getRequest());
       return $respuesta;
    }
    
    public function DatosDashboard($fecha)
    {
        $em = $this->getDoctrine()->getManager();
        $produccion = array();
        
//        $labProgramadas=$em->getRepository('siagBundle:TtProgramacionlabores')->findLaboresProgramadasPorfecha($empId, $fecha);
//        $HaCosechaProgramada=$em->getRepository('siagBundle:TtProgramacionlabores')->HaProgramadasCosechasFecha($empId,$fecha);
//        $plagas=$em->getRepository('siagBundle:ThEstadoPlagas')->findPromedioLarvasHojasPlagasSuperiorNivelCritico($empId);
//        $enferm=$em->getRepository('siagBundle:ThEstadoEnfermedades')->findIncidenciaEnfermedadesPorEmpresa($empId);

        $produccion[0]=array('name'=>'Labores Programadas', 'value'=>0);
        $produccion[1]=array('name'=>'Ha a cosechar', 'value'=>0);
        $produccion[2]=array('name'=>'Plaga Est Critico', 'value'=>0);
        $produccion[3]=array('name'=>'Enfermedad Est Critico', 'value'=>0);
        
        return $produccion;
    }
}
