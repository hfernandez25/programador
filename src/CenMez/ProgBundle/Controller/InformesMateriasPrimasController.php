<?php

namespace CenMez\ProgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CenMez\ProgBundle\Entity\TmMateriaprima;
//use sigaind\ProgBundle\Util\Util;


class InformesMateriasPrimasController extends Controller
{
    /*
     * 
     * INFORME MATERIAS PRIMAS UTILIZADAS EN RANGO DE FECHAS
     */
    public function ConsumoMateriaPrimaAction()
    {       
        $em = $this->getDoctrine()->getManager();
        
        $usuario = $this->get('security.context')->getToken()->getUser();
        $grupo= $usuario->getGusuid()->getGusuid();
        
        return $this->render('ProgBundle:Informes:MateriasPrimas/ConsumoMateriaPrima.html.twig');
        
    }
    
    public function ConsumoMateriaPrimaListaAction()
    {
        $peticion = $this->getRequest();
        $f1 = $peticion->query->get('f1', 0);
        $f2 = $peticion->query->get('f2', 0);
        $em = $this->getDoctrine()->getManager();
        $json= $em->getRepository('ProgBundle:TtMateriaprimaUtilizada')->findMateriaPrimaUtilizadaPorRangoFechas($f1, $f2);
        
        return $this->render('ProgBundle:Informes:MateriasPrimas/ConsumoMateriaPrimaLista.html.twig', array(
                    'datos' => $json
                ));
    }
    
    /*
     * funcion para exportar elementos del plan de fertilizacion
     */
    public function ConsumoMateriaPrimaExportarAction() {
        $peticion = $this->getRequest();
        $f1 = $peticion->query->get('f1', 0);
        $f2 = $peticion->query->get('f2', 0);
        $tipo = $peticion->query->get('tipo', 'Excel');

        $em = $this->getDoctrine()->getManager();

        $usuario = $this->get('security.context')->getToken()->getUser();
        $empresa["empnombre"] = "SANTA SOFIA";
        $empresa["empnit"] = "Hospital Departamental Universitario de Caldas";
     
        //CONSULTA        
        $dt= $em->getRepository('ProgBundle:TtMateriaprimaUtilizada')->findMateriaPrimaUtilizadaPorRangoFechas($f1, $f2);
               
        $titulo = "INFORME MATERIA PRIMA UTILIZADA";
        $NomArchivo = "Informe_Materia_Prima";
        if($tipo==="PDF")
            $f = $this->get('slik_dompdf');
        else
            $f=$this->get('xls.service_xls2007');
        
        $arrayTitulos=array("NOMBRE", "CANTIDAD TOTAL");
        $camp=array();
        $camp[0]= array("campo"=>"nombre", "tipo"=>"string");
        $camp[1]= array("campo"=>"canttotal", "tipo"=>"int");
       
        $response= Export2Excel::TipoExport($dt, $empresa, $titulo, $NomArchivo, $arrayTitulos, $camp, $f, $tipo);
        return $response;
    }
}
