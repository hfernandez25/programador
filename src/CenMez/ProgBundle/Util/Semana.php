<?php

namespace sigaind\siagBundle\Util;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Semana extends Controller {

    public $dias=array();
    public $diaInicial;
    public $diaFinal;
    public $diasLaborales;
    public $domFest;
    
    function __construct() {
        $this->diasLaborales = 0;
        $this->domFest = 0;
     }
     
    static public function semanasPorQuincena($fecha1, $fecha2, $paiId, $empId, $em) 
    {        
        $ns = 0;
        $nd = 0;
        $f1= date('Y-m-d', strtotime($fecha1));
        $f2= date('Y-m-d', strtotime($fecha2));
        $semanas=array();
        $semanas[$ns]= New Semana();
        //$em = $semanas[$ns]->emm;
        
        while ($f1 <= $f2) {
            $today =   date('N', strtotime($f1));
            switch ($today) {                
                case 1: //Lunes
                    $festivo=$em->getRepository('siagBundle:TmFestivos')->findFestivoPorEmpresaAndPais($f1,$paiId,$empId);
                    $nd = 0;
                    if($ns>=1){
                        if(!\array_key_exists($ns, $semanas))
                            $semanas[$ns]= New Semana();
                    }
                        
                    
                    $semanas[$ns]->diaInicial=$f1;
                    if(\count($festivo)>0){
                        $semanas[$ns]->domFest++;
                        $semanas[$ns]->dias[$nd]= new Dia($f1, true);
                    }
                    else{
                        $semanas[$ns]->diasLaborales++;
                        $semanas[$ns]->dias[$nd]= new Dia($f1, false);
                    }
                    break;
                case 2:    //Martes
                case 3:    //Miercoles
                case 4:    //Jueves
                case 5:    //Viernes
                case 6:    //Sabado
                    $festivo=$em->getRepository('siagBundle:TmFestivos')->findFestivoPorEmpresaAndPais($f1, $paiId, $empId);
                    if($ns>=1){
                        if(!\array_key_exists($ns, $semanas))
                            $semanas[$ns]= New Semana();
                    }
                        
                    $diaInic=strtotime('last Monday', strtotime($f1));
                    $diaIni = date( 'Y-m-d' , $diaInic );
                    $semanas[$ns]->diaInicial=$diaIni;
                    if(\count($festivo)>0){
                        $semanas[$ns]->domFest++;
                        $semanas[$ns]->dias[$nd]= new Dia($f1, true);
                    }
                    else{
                        $semanas[$ns]->diasLaborales++;
                        $semanas[$ns]->dias[$nd]= new Dia($f1, false);
                    }
                    break;
                case 7: //
                    if(!\array_key_exists($ns, $semanas))
                            $semanas[$ns]= New Semana();
                    
                    
                    if (empty( $semanas[$ns]->diaInicial)) {
                        $diaInic=strtotime('last Monday', strtotime($f1));
                        $diaIni = date( 'Y-m-d' , $diaInic );
                        $semanas[$ns]->diaInicial=$diaIni;
                    }
                    
                    $semanas[$ns]->domFest++;
                    $semanas[$ns]->diaFinal=$f1;
                    $semanas[$ns]->dias[$nd]= new Dia($f1, true);
                    $ns++;
                    break;
            }
            $nuevafecha = strtotime('+1 day', strtotime($f1) ) ;
            $f1 = date( 'Y-m-d' , $nuevafecha );
            $nd++;
        }
        
        return $semanas;
    }
}

		
class Dia
{
    public $dia;
    public $domFest;
    
    function __construct($fecha, $domical) {
        $this->dia=$fecha;
        $this->domFest=$domical;
    }
}