<?php

namespace sigaind\siagBundle\Util;

class Util {

    static public function iniciarExpor($excelService) 
    {
        // create the object see http://phpexcel.codeplex.com documentation
        $excelService->excelObj->getProperties()->setCreator("Sigaind S.A.S")
                ->setLastModifiedBy("Sigaind S.A.S")
                ->setTitle("Informe desde el aplicativo Sigaind S.A.S")
                ->setSubject("")
                ->setDescription("Informe")
                ->setKeywords("office")
                ->setCategory("Test result file");
    }
    
    //array celdas excel dinamicas
    static public function celdasDinamicas($lenght){
        $abecedario = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        
        $array = $abecedario; 
        $i = 0;
        foreach($abecedario as $letra){
            foreach($abecedario as $letra1){
                if($lenght > $i){
                    $array[] = $letra.$letra1;
                    $i++; 
                }else
                    break;
            }
        }
        return $array;
    }

    /*
     * AÑADE EL ENCABEZADO DEL EXCEL
     */

    static public function generarExel($excelService, $informe, $maxColumnas, $empresa) {
        $fila = 0;

        //ARRAY DE CELDAS DINAMICAS
        $abecedario = Util::celdasDinamicas($maxColumnas);
       

        $nit = isset($empresa["empnit"]) ? "Nit: " . $empresa["empnit"] : "";
        $fechaHoy = "Fecha del informe: " . date("d/M/Y");

        $excelService->excelObj->getProperties()->setCreator("Sigaind")
                ->setLastModifiedBy("Sigaind")
                ->setTitle($informe)
                ->setSubject("")
                ->setDescription("Informe")
                ->setKeywords("Informe generado desde el aplicatigo SIGAIND")
                ->setCategory("Informe");

        $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue('A' . ++$fila, isset($empresa["empnombre"]) ? $empresa["empnombre"] : "")
                ->setCellValue('A' . ++$fila, $nit)
                ->setCellValue('A' . ++$fila, $fechaHoy)
                ->setCellValue('A' . ++$fila, "")
                ->setCellValue('A' . ++$fila, $informe);

        //LAS CELDAS QUEDAN CON AUTOSIZE DEL ENCABEZADO
        for ($i = 0; $i < $maxColumnas; $i++) {
            $excelService->excelObj->getActiveSheet()->getColumnDimension($abecedario[$i])->setAutoSize(true);
        }

        //ARRAY CON LOS ESTILOS DE EXCEL
        $borders = array('alignment' => array('horizontal' => "center"), 'font' => array('bold' => true,));
        //COLOCAR LAS OPCIONES DEL ENCABEZADO
        for ($i = 1; $i <= 5; $i++) {
            //PROPIEDADES DE LETRAS Y MEZCLA CELDAS
            $excelService->excelObj->getActiveSheet()->mergeCells('A' . $i . ':' . $abecedario[$maxColumnas - 1] . $i);
            $excelService->excelObj->getActiveSheet()->getStyle('A' . $i)->applyFromArray($borders);
        }

        $excelService->excelObj->getActiveSheet()->getStyle('A6:' . $abecedario[$maxColumnas - 1] . '6')->applyFromArray($borders);

        $excelService->excelObj->getActiveSheet()->setTitle('Informe');
        $excelService->excelObj->setActiveSheetIndex(0);

        return $excelService->getResponse();
    }

    //El array A trae todos los elmentos de una tabla que tiene todos los elemente y en el caso de encontrar un valor igual a otro se le asignara
    static public function mezclarLotes($A, $B) {
        $index = 0;
        foreach ($A as $key => $item) {
            for ($i = $index; $i < count($B); $i++) {
                if ($item["id"] == $B[$i]["id"]) {
                    $index = $i;
                    $A[$key] = $B[$i];
                }
            }
        }
        return $A;
    }

    //Genear el Xml de la grafica de barras
    static public function gBarraXml($array, $nom, $nomx = "", $nomy = "", $subcaption = "", $porcentaje = 1) {
        $colors = Array("AFD8F8", "F6BD0F", "8BBA00", "FF8E46", "008E8E", "D64646", "8E468E", "588526", "B3AA00", "008ED6", "9D080D", "A186BE");

        $strXML = htmlspecialchars("<graph caption='" . $nom . "' showNames='1' subcaption='" . $subcaption . "' decimalPrecision='2' animation='1' showValues='1' " .
                "rotateValues='1' yaxismaxvalue='1' xAxisName='" . $nomx . "' yAxisName='" . $nomy . "' sNumberSuffix='%'>");

        $i = 0;
        foreach ($array as $value) {
            $strXML .= htmlspecialchars("<set name='" . $value["nombre"] . "' value='" . $value["value"] / $value["total"] * $porcentaje . "' color='" . $colors[$i] . "' />");
            $i++;
        }

        $strXML .= htmlspecialchars("</graph>");
        return $strXML;
    }

    //Genear el Xml de la grafica de Torta
    static public function gTortaXml($array, $nom, $nomx = "", $nomy = "", $subcaption = "", $porcentaje = 1) {
        $colors = Array("AFD8F8", "F6BD0F", "8BBA00", "FF8E46", "008E8E", "D64646", "8E468E", "588526", "B3AA00", "008ED6", "9D080D", "A186BE");

        $strXML = htmlspecialchars("<graph caption='" . $nom . "' showNames='1' decimalPrecision='2' subcaption='" . $subcaption . "' animation='1' showValues='0' showPercentValues='1' " .
                " rotateValues='1' yaxismaxvalue='100' xAxisName='" . $nomx . "' yAxisName='Porcentaje' >");

        $i = 0;
        foreach ($array as $value) {
            $strXML .= htmlspecialchars("<set name='" . $value["nombre"] . "' value='" . $value["value"] / $value["total"] * $porcentaje . "' color='" . $colors[$i] . "' />");
            $i++;
        }

        $strXML .= htmlspecialchars("</graph>");
        return $strXML;
    }

    //Genear el Xml de la grafica de Lineas
    static public function gLineasXml($historico, $series, $categorias, $nombre, $nomx = "", $nomy = "", $subcaption = "", $porcentaje = 1) {
        $colors = Array("AFD8F8", "F6BD0F", "8BBA00", "FF8E46", "008E8E", "D64646", "8E468E", "588526", "B3AA00", "008ED6", "9D080D", "A186BE");

        $strXML = htmlspecialchars("<graph caption='" . $nombre . "' subcaption='" . $subcaption . "'  hovercapbg='FFECAA' hovercapborder='F47E00' formatNumberScale='0'
        decimalPrecision='1' showvalues='0' numdivlines='3' numVdivlines='0' showValues='1' yaxismaxvalue='1' rotateNames='1' xAxisName='" . $nomx . "' yAxisName='" . $nomy . "'>");
        $strXML .= htmlspecialchars("<categories>");
        foreach ($categorias as $categoria) {
            $strXML .= htmlspecialchars("<category name='" . $categoria . "'/>");
        }
        $strXML .= htmlspecialchars(" </categories>");

        $i = 0;
        //print_r($historico);
        foreach ($series as $serie) {
            $strXML .= htmlspecialchars("<dataset seriesName='" . $serie . "' color='" . $colors[$i] . "' anchorBorderColor='" . $colors[$i] . "' anchorBgColor='" . $colors[$i] . "'>");
            foreach ($historico as $key => $value) {
                $strXML .= htmlspecialchars(isset($historico[$key][$serie]) ? "<set value='" . $historico[$key][$serie] * $porcentaje . "'/>" : "<set value='" . 0 . "'/>");
            }
            $strXML .= htmlspecialchars("</dataset>");
            $i++;
        }
        $strXML .= htmlspecialchars("</graph>");
        return $strXML;
    }
    
    
    //Genear el Xml de la grafica de Lineas para produccion
    static public function gHistoricoLineasXml($historico, $series, $categorias, $nombre, $nomx = "", $nomy = "", $subcaption = "", $porcentaje = 1) {
        $colors = Array("AFD8F8", "F6BD0F", "8BBA00", "FF8E46", "008E8E", "D64646", "8E468E", "588526", "B3AA00", "008ED6", "9D080D", "A186BE");

        $strXML = htmlspecialchars("<graph caption='" . $nombre . "' subcaption='" . $subcaption . "'  hovercapbg='FFECAA' hovercapborder='F47E00' formatNumberScale='0'
        decimalPrecision='0' showvalues='0' numdivlines='3' numVdivlines='0' showValues='1' yaxismaxvalue='10' rotateNames='1' xAxisName='" . $nomx . "' yAxisName='" . $nomy . "'>");
        $strXML .= htmlspecialchars("<categories>");
        foreach ($categorias as $categoria) {
            $strXML .= htmlspecialchars("<category name='" . $categoria . "'/>");
        }
        $strXML .= htmlspecialchars(" </categories>");

        $i = 0;
        foreach ($series as $serie) {
            $strXML .= htmlspecialchars("<dataset seriesName='" . $serie . "' color='" . $colors[$i] . "' anchorBorderColor='" . $colors[$i] . "' anchorBgColor='" . $colors[$i] . "'>");
            foreach ($categorias as $categoria) {  
                    $strXML .= htmlspecialchars(isset($historico[$serie][$categoria]) ? "<set value='" . $historico[$serie][$categoria] * $porcentaje . "'/>" : "<set value='" . 0 . "'/>");
            }
            $strXML .= htmlspecialchars("</dataset>");
            $i++;
        }
        $strXML .= htmlspecialchars("</graph>");
        return $strXML;
    }
    
    //Genear el Xml de la grafica de barras
    static public function gHistoricoBarraXml($array, $nom, $nomx = "", $nomy = "", $subcaption = "", $ton = 1, $flag = false) {
        $colors = Array("AFD8F8", "F6BD0F", "8BBA00", "FF8E46", "008E8E", "D64646", "8E468E", "588526", "B3AA00", "008ED6", 
                        "9D080D", "A186BE", "A186BE", "A186BE", "A186BE", "A186BE", "A186BE", "A186BE", "A186BE", "A186BE");

        $strXML = htmlspecialchars("<graph caption='" . $nom . "' showNames='1' subcaption='" . $subcaption . "' formatNumberScale='0' decimalPrecision='2' animation='1' showValues='1' " .
                "xAxisName='" . $nomx . "' yAxisName='" . $nomy . "' yAxisMinValue='0' yAxisMaxValue='10'>");

        $i = 0;
        foreach ($array as $value) {
            if((float)$value["lothectarea"]>0)
                $produccion = !$flag?$value["value"]:($value["value"]/$value["lothectarea"]);
            else
                $produccion=0;
            $strXML .= htmlspecialchars("<set name='" . $value["nombre"] . "' value='" .$produccion/$ton  . "' color='" . $colors[$i] . "' />");
            $i++;
        }

        $strXML .= htmlspecialchars("</graph>");
        return $strXML;
    }

     /*
     * SACAR LA EDAD DE LOS LOTES
     */
    
    static public function edadLote($edad) {
        $edadFinal = 0;
        $añoAcutal = date("Y");
        $mesAcutal = date("M");

        if (count(explode('-', $edad)) > 1) {
            list($año, $mes) = explode('-', $edad);
            $fechainicial = new \DateTime($año.'-'.$mes.'-01');
            $fechafinal = new \DateTime("now");
            $diferencia = $fechainicial->diff($fechafinal);
            $edadFinal = ( $diferencia->y * 12 ) + $diferencia->m;
        }
        else {
            $edadFinal = (int) $añoAcutal - (int) $edad;
        }
        return $edadFinal;
    }
    
      /*
     * SACAR LA EDAD DE LOS LOTES
     */
    
    static public function añoSiembra($edad) {

        if (count(explode('-', $edad)) > 1) {
            list($año, $mes) = explode('-', $edad);
            return $año;
            
        } else {
            return  $edad;
        }
    }

    /*
     * ORDENA UN VECTOR POR UNO O VARIOS CAMPOS
     */

    static public function ordenar_array() {
        $n_parametros = func_num_args(); // Obenemos el número de parámetros 
        if ($n_parametros < 3 || $n_parametros % 2 != 1) { // Si tenemos el número de parametro mal... 
            return false;
        } else { // Hasta aquí todo correcto...veamos si los parámetros tienen lo que debe ser... 
            $arg_list = func_get_args();

            if (!(is_array($arg_list[0]) && is_array(current($arg_list[0])))) {
                return false; // Si el primero no es un array...MALO! 
            }
            for ($i = 1; $i < $n_parametros; $i++) { // Miramos que el resto de parámetros tb estén bien... 
                if ($i % 2 != 0) {// Parámetro impar...tiene que ser un campo del array... 
                    if (!array_key_exists($arg_list[$i], current($arg_list[0]))) {
                        return false;
                    }
                } else { // Par, no falla...si no es SORT_ASC o SORT_DESC...a la calle! 
                    if ($arg_list[$i] != SORT_ASC && $arg_list[$i] != SORT_DESC) {
                        return false;
                    }
                }
            }
            $array_salida = $arg_list[0];

            // Una vez los parámetros se que están bien, procederé a ordenar... 
            $a_evaluar = "foreach (\$array_salida as \$fila){\n";
            for ($i = 1; $i < $n_parametros; $i+=2) { // Ahora por cada columna... 
                $a_evaluar .= "  \$campo{$i}[] = \$fila['$arg_list[$i]'];\n";
            }
            $a_evaluar .= "}\n";
            $a_evaluar .= "array_multisort(\n";
            for ($i = 1; $i < $n_parametros; $i+=2) { // Ahora por cada elemento... 
                $a_evaluar .= "  \$campo{$i}, SORT_REGULAR, \$arg_list[" . ($i + 1) . "],\n";
            }
            $a_evaluar .= "  \$array_salida);";

            eval($a_evaluar);
            return $array_salida;
        }
    }

    /*
     * CAMBIAR UN VALOR A LETRAS
     */

    static public function num2letras($num, $fem = false, $dec = true) {
//if (strlen($num) > 14) die("El n?mero introducido es demasiado grande"); 
        $matuni[2] = "dos";
        $matuni[3] = "tres";
        $matuni[4] = "cuatro";
        $matuni[5] = "cinco";
        $matuni[6] = "seis";
        $matuni[7] = "siete";
        $matuni[8] = "ocho";
        $matuni[9] = "nueve";
        $matuni[10] = "diez";
        $matuni[11] = "once";
        $matuni[12] = "doce";
        $matuni[13] = "trece";
        $matuni[14] = "catorce";
        $matuni[15] = "quince";
        $matuni[16] = "dieciseis";
        $matuni[17] = "diecisiete";
        $matuni[18] = "dieciocho";
        $matuni[19] = "diecinueve";
        $matuni[20] = "veinte";
        $matunisub[2] = "dos";
        $matunisub[3] = "tres";
        $matunisub[4] = "cuatro";
        $matunisub[5] = "quin";
        $matunisub[6] = "seis";
        $matunisub[7] = "sete";
        $matunisub[8] = "ocho";
        $matunisub[9] = "nove";

        $matdec[2] = "veint";
        $matdec[3] = "treinta";
        $matdec[4] = "cuarenta";
        $matdec[5] = "cincuenta";
        $matdec[6] = "sesenta";
        $matdec[7] = "setenta";
        $matdec[8] = "ochenta";
        $matdec[9] = "noventa";
        $matsub[3] = 'mill';
        $matsub[5] = 'bill';
        $matsub[7] = 'mill';
        $matsub[9] = 'trill';
        $matsub[11] = 'mill';
        $matsub[13] = 'bill';
        $matsub[15] = 'mill';
        $matmil[4] = 'millones';
        $matmil[6] = 'billones';
        $matmil[7] = 'de billones';
        $matmil[8] = 'millones de billones';
        $matmil[10] = 'trillones';
        $matmil[11] = 'de trillones';
        $matmil[12] = 'millones de trillones';
        $matmil[13] = 'de trillones';
        $matmil[14] = 'billones de trillones';
        $matmil[15] = 'de billones de trillones';
        $matmil[16] = 'millones de billones de trillones';

        $num = trim((string) @$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        }else
            $neg = '';
        while ($num[0] == '0')
            $num = substr($num, 1);
        if ($num[0] < '1' or $num[0] > 9)
            $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (!(strpos(".,'''", $n) === false)) {
                if ($punt)
                    break;
                else {
                    $punt = true;
                    continue;
                }
            } elseif (!(strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0')
                        $zeros = false;
                    $fra .= $n;
                }else
                    $ent .= $n;
            }else
                break;
        }
        $ent = '     ' . $ent;
        if ($dec and $fra and !$zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' cero';
                elseif ($s == '1')
                    $fin .= $fem ? ' una' : ' un';
                else
                    $fin .= ' ' . $matuni[$s];
            }
        }else
            $fin = '';
        if ((int) $ent === 0)
            return 'Cero ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while (($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'una';
                $subcent = 'as';
            } else {
                $matuni[1] = $neutro ? 'un' : 'uno';
                $subcent = 'os';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
                
            } elseif ($n2 < 21)
                $t = ' ' . $matuni[(int) $n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0)
                    $t = 'i' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }else {
                $n3 = $num[2];
                if ($n3 != 0)
                    $t = ' y ' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' ciento' . $t;
            } elseif ($n == 5) {
                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
            } elseif ($n != 0) {
                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
            }
            if ($sub == 1) {
                
            } elseif (!isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' mil';
                } elseif ($num > 1) {
                    $t .= ' mil';
                }
            } elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . 'ón'; // Modificacion por p4scu41
            } elseif ($num > 1) {
                $t .= ' ' . $matsub[$sub] . 'ones';
            }
            if ($num == '000')
                $mils++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub]))
                    $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        return ucfirst($tex);
    }
    
    /*
     * EL MES EN ESPAÑOL
     */
    static public function getMes($index){
        $meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        return $meses[(int)$index];
    }
    
    
         /*
     * QUINCENA DE UNA FECHA
     */
    static public function getQuincenaFecha($fecha){        
        $dia = $fecha->format("d");
        
        $i = 0;
        //Fecha Inicial verificar si es de dos quincenas o de una sola
        if($dia <= 15){
            $quincena = "1 Qna de " . Util::getMes($fecha->format("m")). " ".$fecha->format("Y");  
        }else{
            $quincena = "2 Qna de " . Util::getMes($fecha->format("m")). " ".$fecha->format("Y");
        }        
        return $quincena;
    }
    
     /*
     * QUINCENS ENTRE DOS FECHAS
     */
    static public function getQuincenas($fecha1, $fecha2){        
        $fecha1 = new \DateTime($fecha1);
        $fecha2 = new \DateTime($fecha2);
        $quincena = array();
        $diaIni = $fecha1->format("d");
        $diaFin = $fecha2->format("d");
       
        
        $fecha1->modify('last day of this month');
        $i = 0;
        //Fecha Inicial verificar si es de dos quincenas o de una sola
        if($diaIni <= 15){
            //Fecha Inicial
            $quincena[$i]["fechaIni"] =  $fecha1->format("Y"). "-" .$fecha1->format("m"). "-01 00:00:00";
            $quincena[$i]["fechaFin"] =  $fecha1->format("Y"). "-" .$fecha1->format("m"). "-15 23:59:59";
            $quincena[$i]["quincena"] = "1 Qna " . Util::getMes($fecha1->format("m")). " ".$fecha1->format("Y");  
            $i++;
            //Fecha Final
            $quincena[$i]["fechaIni"] =  $fecha1->format("Y"). "-" .$fecha1->format("m"). "-16 00:00:00";
            $quincena[$i]["fechaFin"] =  $fecha1->format("Y-m-d"). " 23:59:59";
            $quincena[$i]["quincena"] = "2 Qna " . Util::getMes($fecha1->format("m")). " ".$fecha1->format("Y");
        }else{
            $quincena[$i]["fechaIni"] =  $fecha1->format("Y"). "-" .$fecha1->format("m"). "-16 00:00:00";
            $quincena[$i]["fechaFin"] =  $fecha1->format("Y-m-d"). " 23:59:59";
            $quincena[$i]["quincena"] = "2 Qna " . Util::getMes($fecha1->format("m")). " ".$fecha1->format("Y");
        }
        
        $fecha1->modify('first day of this month');
        if(($fecha1->format("m") != $fecha2->format("m")) || ($fecha1->format("Y") != $fecha2->format("Y")))
        {
            //Verificar las fechas para sacar las quincenas
            $fecha1->modify('+1 month');
            $fecha1->modify('first day of this month');
            $fecha2->modify('first day of this month');
            $i++;
            
            while($fecha1 < $fecha2 ){

                //Fecha Inicial
                $quincena[$i]["fechaIni"] =  $fecha1->format("Y"). "-" .$fecha1->format("m"). "-01 00:00:00";
                $quincena[$i]["fechaFin"] =  $fecha1->format("Y"). "-" .$fecha1->format("m"). "-15 23:59:59";
                $quincena[$i]["quincena"] = "1 Qna " . Util::getMes($fecha1->format("m")). " ".$fecha1->format("Y");
                $i++;
                //Fecha Final
                $fecha1->modify('last day of this month');
                $quincena[$i]["fechaIni"] =  $fecha1->format("Y"). "-" .$fecha1->format("m"). "-16 00:00:00";          
                $quincena[$i]["fechaFin"] =  $fecha1->format("Y-m-d"). " 23:59:59";
                $quincena[$i]["quincena"] = "2 Qna " . Util::getMes($fecha1->format("m")). " ".$fecha1->format("Y");
                $fecha1->modify('first day of this month');
                $fecha1->modify('+1 month');
                $i++;
            }

            //Fecha final verificar si es de dos quincenas o de una sola
            $fecha2->modify('last day of this month');
            if($diaFin > 15){
                //Fecha Inicial
                $quincena[$i]["fechaIni"] =  $fecha2->format("Y"). "-" .$fecha2->format("m"). "-01 00:00:00";
                $quincena[$i]["fechaFin"] =  $fecha2->format("Y"). "-" .$fecha2->format("m"). "-15 23:59:59";
                $quincena[$i]["quincena"] = "1 Qna " . Util::getMes($fecha2->format("m")). " ".$fecha2->format("Y"); 
                $i++;
                //Fecha Final
                $quincena[$i]["fechaIni"] =  $fecha2->format("Y"). "-" .$fecha2->format("m"). "-16 00:00:00";
                $quincena[$i]["fechaFin"] =  $fecha2->format("Y-m-d"). " 23:59:59";
                $quincena[$i]["quincena"] = "2 Qna " . Util::getMes($fecha2->format("m")). " ".$fecha2->format("Y");
            }else{
                $quincena[$i]["fechaIni"] =  $fecha2->format("Y"). "-" .$fecha2->format("m"). "-01 00:00:00";
                $quincena[$i]["fechaFin"] =  $fecha1->format("Y-m-d"). "-15 23:59:59";
                $quincena[$i]["quincena"] = "1 Qna " . Util::getMes($fecha2->format("m")). " ".$fecha2->format("Y");
            }
        }
        return $quincena;
    }
    
    /*
     * funcion para generar la cadena para el filtro de las consultas
     */
    static public function GenerateStringFilters($filters, $campo,$empId){
        // GridFilters sends filters as an Array if not json encoded
        if (\is_array($filters)) {
            $encoded = false;
        } else {
            $encoded = true;
            $filters = json_decode($filters);
        }

        $where = $campo.' = '.$empId;
        $qs = '';

        // loop through filters sent by client
        if (\is_array($filters)) {
            for ($i=0;$i<count($filters);$i++){
                $filter = $filters[$i];

                // assign filter data (location depends if encoded or not)
                if ($encoded) {
                    $field = $filter->field;
                    $value = $filter->value;
                    $compare = isset($filter->comparison) ? $filter->comparison : null;
                    $filterType = $filter->type;
                } else {
                    $field = $filter['field'];
                    $value = $filter['data']['value'];
                    $compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
                    $filterType = $filter['data']['type'];
                }

                switch($filterType){
                    case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
                    case 'list' :
                        if (strstr($value,',')){
                            $fi = explode(',',$value);
                            for ($q=0;$q<count($fi);$q++){
                                $fi[$q] = "'".$fi[$q]."'";
                            }
                            $value = implode(',',$fi);
                            $qs .= " AND ".$field." IN (".$value.")";
                        }else{
                            $qs .= " AND ".$field." = '".$value."'";
                        }
                    Break;
                    case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
                    case 'numeric' :
                        switch ($compare) {
                            case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
                            case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
                            case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
                        }
                    Break;
                    case 'date' :
                        switch ($compare) {
                            case 'eq' : $qs .= " AND ".$field." BETWEEN'".date('Y-m-d',strtotime($value))." 00:00:00' AND '".date('Y-m-d',strtotime($value))." 23:59:59' "; Break;
                            case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))." 23:59:59'"; Break;
                            case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))." 00:00:00'"; Break;
                        }
                    Break;
                }
            }
            $where .= $qs;            
        }  
        return $where;
    }
    
    //generar el codigo catastral de la palma
    static public function GenerateCodCatastralPalma($lotId, $linea,$palma)
    {
        $CodCatastral=$lotId;
        
        if(strlen($linea)==3)
            $CodCatastral=$CodCatastral.$linea;
        else if(strlen($linea)==2)
            $CodCatastral=$CodCatastral."0".$linea;
        else if(strlen($linea)==1)
            $CodCatastral=$CodCatastral."00".$linea;
        
        if(strlen($palma)==2)
            $CodCatastral=$CodCatastral.$palma;
        else if(strlen($palma)==1)
            $CodCatastral=$CodCatastral."0".$palma;
        
        return $CodCatastral;
        
    }
    
    static public function r2d($valor) { 
        $float_redondeado=round($valor * 100) / 100; 
        return $float_redondeado; 
    }
    
    static public function rNd ($numero, $decimales) { 
        $factor = pow(10, $decimales); 
        return (round($numero*$factor)/$factor); 
   
    }
        
}