//FUNCION DEL INICIO
function inicio()
{   
    CampoDate("#f1", "#f2"); 
}

//COLOCA EN EL TITULO LOS CAMPOS PARA FILTRAR
function tituloFiltro(){
    var mensaje = "Informe Consumo Materias Primas ";
    var f1 = $('#f1').get(0).value;
    var f2 = $('#f2').get(0).value;
    
    if(f1 != "" && f2 != ""){
        mensaje += " | Fechas: " + f1 + " - " + f2;
    }
    $('#titulo-consulta').html(mensaje);
    
 }
 
function LimpiarCamposConsulta()
{
    $('#f1').get(0).value="";
    $('#f2').get(0).value="";
}

//FUNCION PARA ACTIVAR LA CONSULTA
function Consulta(){
    //$('#botones').hide();
    $('#formulario').hide();
    $('#consulta').slideDown();    
}

//FUNCION PARA CANCELAR LA CONSULTA
function CancelarConsulta()
{
    $('#consulta').hide();
    $('#botones').slideDown();
}

//FUNCION PARA CANCELAR LA CONSULTA
function CancelarConsultaLista()
{
    $('#divTabla').hide();
    $('#loading').hide();
    $('#divDetalle').hide();
    $('#consulta').slideDown();
    
    
}

//FUNCION PARA CONSULTAR
function validarEnvio(){
    var mensaje = "";
    if($("#f1").val() == "" && $("#f2").val() == ""){
        alert("- Ingrese rango de fechas");
    }
    else
    {
        consultar();
    }
}

function consultar()
{
    var f1 = $('#f1').get(0).value;
    var f2 = $('#f2').get(0).value;
 
    $('#formulario').hide();    
    $('#consulta').hide();
    $('#loading').show();         
    var cadena = "ConsumoMateriaPrimaLista?f1="+f1+"&f2="+f2;
    $('#info').load(cadena, function(response, status, xhr) {
        if (status == "error") {
            alert("Se presento un inconveniente en la consulta: \nError: "+ xhr.status+"\n"+xhr.statusText);
            $('#divTabla').hide();
            $('#loading').hide();
            $('#consulta').slideDown();
        }
        else
        {
            $('#divTabla').slideDown();
            $('#loading').hide();
            //$('#botones').hide();
            $('#consulta').hide();
        }

    });                 
    
}

//FUNCION PARA EXPORTAR A EXCEL
function exportar(tipo)
{
    var f1 = $('#f1').get(0).value;
    var f2 = $('#f2').get(0).value;

    var obj_window = window.open(
        "ConsumoMateriaPrimaExportar?f1="+f1+"&f2="+f2+"&tipo="+tipo,
        'Calendar', 'width=40,height=30,scrollbars=yes,status=no,resizable=yes,top=10,left=10,dependent=no,alwaysRaised=yes');
    obj_window.opener = window;
    obj_window.focus();
}