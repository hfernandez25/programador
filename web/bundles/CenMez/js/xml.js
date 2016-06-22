//the array of colors contains 4 unique Hex coded colours (without #) for the 4 products
var colors=new Array("AFD8F8", "F6BD0F", "8BBA00", "FF8E46", "008E8E", "D64646", "8E468E", "588526", "B3AA00", "008ED6", "9D080D", "A186BE");

/*
 * *XML PARA LOS GRAFICOS DE BARRA Y TOTA
 * 
 */
console.log(data);
function generateXML(){	
    var strXML="";

        strXML = "<graph caption='Enfermedad: "+nomenfermedad+"' showNames='1' decimalPrecision='2' animation='1' showValues='1' "+
        "rotateValues='1' yaxismaxvalue='100' xAxisName='Enfermedad' yAxisName='Porcentaje' sNumberSuffix='%'>";	
    i=0;
    for (var indice in arrayEnfermedad)
    {
        strXML = strXML + "<set name='"+indice+"' value='" + data[i][1] + "' color='"+ colors[i]  +"' />";
        i++;
    }			

    strXML = strXML + "</graph>";

    return strXML;
}

function generarXmlLinea(){

strXML = '<graph caption="Enfermedad: '+nomenfermedad+'" subcaption="(desde '+arrayHist[1]["fechaini"]+' hasta '+arrayHist[5]["fechafin"]+')" hovercapbg="FFECAA" hovercapborder="F47E00" formatNumberScale="0"'
+' decimalPrecision="0" showvalues="0" numdivlines="3" numVdivlines="0" showValues="1" sNumberSuffix="%" yaxismaxvalue="1" rotateNames="1" xAxisName="Evolucion de los estados de la Enfermedad" yAxisName="Promedio">';
strXML +=  '<categories>';
    
    for (var indice in arrayHist){
         strXML += '<category name="'+arrayHist[indice]["consecutivo"]+'"/>';
    }
    strXML += ' </categories>';
    
    i=0;
    for (var indexPla in arrayEnfermedad){
        strXML += '<dataset seriesName="'+indexPla+'" color="'+colors[i]+'" anchorBorderColor="'+colors[i]+'" anchorBgColor="'+colors[i]+'">'
        for (var indexHist in arrayHist){ 
                if (arrayHist[indexHist]["total"] > 0)
                    strXML +=  '<set value="'+arrayHist[indexHist][indexPla]/arrayHist[indexHist]["lotnumeropalmas"] * 100 +'"/>';
                else
                    strXML +=  '<set value="'+ 0 +'"/>';
            } 
         strXML += '</dataset>';
         i++;
    }
strXML += '</graph>';

return strXML;

}

function generarGrafico(tipo){
        
    if(tipo=="n"){
        tipoGraficoNext(tipoGrafico);
    }
    else{
        tipoGraficoPrev(tipoGrafico);
    }
    FRutaGrafico(tipoGrafico);
}

function FRutaGrafico(tipo){
    switch (tipo) { 
        case 1: 
            generarXml(graBarra); 
            break 
        case 2: 
            generarXml(graTorta); 
            break 
        case 3: 
            generarXml(graLinea); 
            break 
        default: 
            rutaGrafico = "";
    }
}

function tipoGraficoNext(tipo){
    switch (tipo) { 
        case 1: 
            tipoGrafico = 2; 
            break 
        case 2: 
            tipoGrafico = 3;
            break 
        case 3: 
            tipoGrafico = 1; 
            break 
        default: 

    }
}

function tipoGraficoPrev(tipo){
    switch (tipo) { 
        case 1: 
            tipoGrafico = 3; 
            break 
        case 2: 
            tipoGrafico = 1;
            break 
        case 3: 
            tipoGrafico = 2; 
            break 
        default: 

    }
}

function generarXml(swf){
    var chart1 = new FusionCharts(swf, "chart1Id", "800", "400");		   
    var strXML = "";
    
    if(tipoGrafico == 3){  
        strXML = generarXmlLinea();
    }else{
        strXML = generateXML();
    }
    chart1.setDataXML(strXML);
    chart1.render("chart1div");
}

generarXml(graBarra);

