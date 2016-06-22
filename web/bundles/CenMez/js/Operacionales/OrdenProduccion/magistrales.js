var botones2 = [];
var columnas = [];
//FUNCION DEL INICIO

var esNuevo = true;
var indice = 0;

// Define your global object  
var myObj = {};  

// Add property (variable) to it  
myObj.indice = 0; 
myObj.isNuevo = true; 


// Add method to it  
myObj.myFunctions = function() {  
        // Do cool stuff  
};

function inicio()
{
    $("#fecha").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        numberOfMonths: 1,
        showAnim: "drop",
        autoclose: true,
        onSelect: function(date) {
            calcularCodOrden(date);
        }
    });
    
    if($('#traId').get(0).value!="1")
        listasAutoc("supId", "../../autocomplete/trabajadoresdos");
}

function listado()
{
    if($('#e').get(0).value>0)
        botones2 = [{name: 'Nuevo', bclass: 'add', onpress : nuevo}];
    if($('#l').get(0).value>0)
    {
        columnas = [
                    {display: 'ID', name : 'O.id', width : 50, sortable : true, align: 'left'},
                    {display: 'Orden Producción', name : 'O.ordenproduccion', width : 100, sortable : true, align: 'left'},
                    {display: 'Fecha', name : 'O.fecha', width : 100, sortable : true, align: 'left'},                    
                    {display: 'Q.F.', name : 'Q.nombre', width : 230, sortable : true, align: 'left'},
                    {display: 'Digitado Por', name : 'T.nombre', width : 230, sortable : false, align: 'left'},
                    {display: 'Observaciones', name : 'O.observaciones', width : 230, sortable : false, align: 'left'}
                    ];
    }
    
    $("#tblRegistros").flexigrid({
            url: 'allOrdenesProduccionMagistrales',
            dataType: 'json',
            colModel : columnas,
            buttons : botones2,
            searchitems : [
                    {display: 'ID', name : 'O.id'},
                    {display: 'Orden Producción', name : 'O.ordenproduccion'},
                    {display: 'Fecha', name : 'O.fecha', isdefault: true},
                    {display: 'Q.F.', name : 'Q.nombre'},
                    {display: 'Observacion', name : 'O.observaciones'},
                    {display: 'Digitado Por', name : 'T.nombre'}
                    ],
            sortname: "O.fecha",
            sortorder: "desc",
            title: "LISTADO ORDENES DE PRODUCCIÓN MAGISTRALES",
            usepager: true,
            useRp: true,
            rp: 15,
            showTableToggleBtn: true,
            width: 970,
            height: 380
    });
			
}

function nuevo(com,grid)
{
    if($('#e').get(0).value==0)
    {
        alert("No tiene permisos para programar labores");
    }
    else
    {
        $("#btnAdiconar").show();
        $("#tdEliminar").show();
        $('#consulta').hide();
        $('#imprimir').hide();
        $('#alista').hide();
        $('#formulario').slideDown();
        $('#btnInsertar').show();
        
        
        $('#divForm').hide();
        $('#formulario').slideDown(); 
        $('#demotable2').find('tbody').find('tr').remove();
        adicionar(0, 0);
        myObj.isNuevo = true;
        $('#tituloFormulario').text("NUEVA ORDEN DE PRODUCCIÓN MAGISTRALES");
        $('#fecha').get(0).value=$('#f').get(0).value;
        calcularCodOrden($('#fecha').get(0).value);
        $('#obs').get(0).value="";
        $('#id').get(0).value=0;
        
    }   
}

function calcularCodOrden(fec)
{
    var codOrden = "MAG-"+DiaDelAnio(fec.split("-")[0], (fec.split("-")[1]-1), (fec.split("-")[2]))+fec.split("-")[0];
    $('#orden').get(0).value = codOrden
}

function adicionar(ban, index)
{
    $('#imprimir').hide();
    $('#alista').hide();
    if(myObj.indice>0)
    {
        var tabla = $("#tblLabores").get(0);
        var tr=tabla.rows[tabla.rows.length-1];
        var indice= tr.id.split("_")[1];
       if(validarCampos(indice))
       {           
           adiccion(ban, 0, index);
       } 
    }
    else
        adiccion(ban, 0, 0);
}

function adiccion(ban, id, index)
{
    var fila = '<tr id="tr_'+myObj.indice+'">'
        + '<td> '
           + ' <input type="text" size=25" id="medId_'+myObj.indice+'" class="medicamentos" name="0" idProg="'+id+'" />'
        + ' </td>'
        + '<td>' 
            + '<input type="text" size="4" id="cant_'+myObj.indice+'"  class="cantidad"/>'
        + '</td>'
        + '<td>' 
            + '<input type="text" size="12" id="lote_'+myObj.indice+'" class="lotes" name="0" disabled/>'
        + '</td>'
        + '<td>' 
            + '<input type="text" size="7" id="presentacion_'+myObj.indice+'" class="presentaciones" name="0" disabled/>'
        + '</td>'
        + '<td>' 
            + '<input type="text" size="7" id="concentracion_'+myObj.indice+'" class="concentraciones" name="0" disabled/>'
        + '</td>'
        + '<td>' 
            + '<input type="text" size="7" id="reconstituir_'+myObj.indice+'" class="reconstituircon" name="0" disabled/>'
        + '</td>'
        + '<td>' 
            + '<input type="text" size="7" id="VolRecons_'+myObj.indice+'" class="VolReconstituciones" name="0" disabled/>'
        + '</td>'
        + '<td>' 
            + '<input type="text" size="11" id="VehDilucion_'+myObj.indice+'" class="VehDiluciones" name="0" disabled/>'
        + '</td>'
        + '<td>' 
            + '<input type="text" size="6" id="VolDilucion_'+myObj.indice+'" class="VolDiluciones" name="0" disabled/>'
        + '</td>'
        + '<td>' 
            +'<a class="adicion" id="nuevo_'+myObj.indice+'" href="javascript:void(0)"><img src="'+ $("#imgeAgregar").val() +'" width="16" height="16" alt="" /></a>'
        + '</td>'
        + '<td>'
            +'<a class="delete" id="elim_'+myObj.indice+'" href="javascript:void(0)"><img src="'+ $("#imgeliminar").val() +'" width="16" height="16" alt="Eliminar" /></a>'
        +'</td>'

        + '</tr>';
        $('#demotable2').find('tbody').append(fila);
        iniciarCampos(ban, index);
        myObj.indice = myObj.indice + 1;
}

function iniciarCampos(ban, id)
{
    var medId = "#medId_"+myObj.indice;
    var cant = "#cant_"+myObj.indice;
    var lote = "#lote_"+myObj.indice;
    var presentacion = "#presentacion_"+myObj.indice;
    var concentracion = "#concentracion_"+myObj.indice;
    var reconstituir = "#reconstituir_"+myObj.indice;
    var VolRecons = "#VolRecons_"+myObj.indice;
    var VehDilucion = "#VehDilucion_"+myObj.indice;
    var VolDilucion = "#VolDilucion_"+myObj.indice;
    
//    listasAutoc(labId, "../../autocomplete/labores");
    $(medId).autocomplete({
        minLength: 2,        
        source: "../../autocomplete/medicamentosFull",
        select: function( event, ui ) {
                 $(medId).get(0).name= ui.item.id ;
                 $(presentacion).get(0).value= ui.item.presentacion ;
                 $(concentracion).get(0).value= ui.item.concentacion ;
                 $(reconstituir).get(0).value= ui.item.vehreconstitucion ;
                 $(VolRecons).get(0).value= ui.item.volreconstitucion ;
                 $(VehDilucion).get(0).value= ui.item.vehdilucion ;
                 $(VolDilucion).get(0).value= ui.item.volvehdilucion ;
                 calcularCodLote(lote, ui.item.value);
            }
    });
    
    $(medId).focus(); 
        
}


function validarCampos(indice)
{
    console.log(indice);
    var band=true;
    var medId = "#medId_"+indice;
    var cant = "#cant_"+indice;
    
    if($(medId).get(0).name=="0" || $(medId).get(0).name=="")
    {
        alert("Debe seleccionar un medicamento");
        $(medId).focus();
        band=false;
    }
    else if($(cant).get(0).value=="0" || $(cant).get(0).value=="")
    {
        alert("Debe ingresar una cantidad");
        $(cant).focus();
        band=false;
    }
    else
        band=true;
        
    return band;
}

$(".delete").live('click', function() {
    
    var elim = $(this)[0].id;
    var index = elim.split("_")[1];
    var id = $("#medId_"+index).attr("idProg");
    var tr=$(this).parent().parent();
    if(id>0)
    {
        $('#loading').show();
        var datos ="id="+id;
        $.ajax({
            url: 'delectDetalleOrdenMagistrales',
            type: 'POST',
            async: true,
            data: datos,
            success: function(data){
                    if (parseInt(data.error)<0)
                    {
                        alert(data.mensaje);
                        $('#loading').hide();
                    }
                    else
                    {
                       alert(data.mensaje);
                       tr.remove();
                       $("#tblRegistros").flexReload();
                       $('#loading').hide();
                    }
               },
            error: muestraError
         });
         $("#tblRegistros").flexReload();
    }
    else
        tr.remove();
});

$(".adicion").live('click', function() {
    var camp = $(this)[0].id;
    var index = camp.split("_")[1];
    adicionar(0, index);
});
//==============================================================================
//------------------------------------------------------------------------------
//==============================================================================



function RedireccionFlexigrid(id)
{
    if($('#u').get(0).value==0)
    {
        alert("No tiene permisos para editar");
    }
    else
    {
        $('#divForm').hide();
        $('#loading').show();
        $.ajax({
              url: 'consultaOrdenProduccionMagistrales?id='+id,
              type: 'POST',
              async: true,
              data: 'id='+id,
              success: ResponseConsulta,
              error: muestraErrorConsulta	
         });
    }
  
}

function ResponseConsulta(data)
{
    if(data.length>0)
    {
        $('#tituloFormulario').text("ORDEN DE PRODUCCIÓN MAGISTRALES | ORDEN: "+ data[0].ordenproduccion);
        $("#fecha").get(0).value=data[0].fecha;
        $("#traId").get(0).name=data[0].idQf;
        $("#traId").get(0).value=data[0].qf;
        $("#orden").get(0).value=data[0].ordenproduccion;
        $("#obs").get(0).value=data[0].observaciones;
        $("#id").get(0).value=data[0].id;
        
        var datos = data[1];
        for(var i=0; i<datos.length; i++)
        {
            var row = datos[i];
            adiccion(0, row.id, 0);
            
            $("#medId_"+i).get(0).name=row.medId;
            $("#medId_"+i).get(0).value=row.nombre;
            
            $("#cant_"+i).get(0).value=row.cantidad;
            $("#lote_"+i).get(0).value=row.lote;
            $("#presentacion_"+i).get(0).value=row.presentacion;
            $("#concentracion_"+i).get(0).value=row.concentacion;
            $("#reconstituir_"+i).get(0).value=row.vehreconstitucion;
            $("#VolRecons_"+i).get(0).value=row.volreconstitucion;
            $("#VehDilucion_"+i).get(0).value=row.vehdilucion;
            $("#VolDilucion_"+i).get(0).value=row.volvehdilucion;
        }
        $('#divForm').hide();
        $('#formulario').slideDown();
        $('#imprimir').slideDown();
        $('#alista').slideDown();
        $('#loading').hide();

    }
    else
    {
        alert("no hay datos para mostrar");
        $('#loading').hide();
        $('#divForm').slideDown();    
    }    
}


function guardarRegistro()
{
    
    if($('#fecha').get(0).value=="" || $('#traId').get(0).name=="" || $('#traId').get(0).name=="0")
    {
        alert("El Q.F. y la fecha son obligatorios");
    }
    else
    {
        var postData = [];
        var verificar = true;
        var fe=$('#fecha').get(0).value;
        var traId=$('#traId').get(0).name;
        var orden=$('#orden').get(0).value;
        var obs=$('#obs').get(0).value;
        var med = $('.medicamentos');
        var cant = $('.cantidad');
        var lote = $('.lotes');
        var i=0;
        postData[i] = {"id": $('#id').get(0).value, "fe": fe, "traId": traId, "orden": orden, "obs": obs};
        i++;
        $("#tblLabores tr").each(function (index) 
        {
            var idd= $(this).attr("id");
            var ind = idd.split("_")[1];
            
            if(!validarCampos(ind))
            {
                verificar = false;
                return false;                
            }
           else
           {    
               postData[i] = {"id": $(med[index]).attr("idProg"), "medId": $(med[index]).get(0).name, "cant": $(cant[index]).get(0).value, "lote": $(lote[index]).get(0).value}; 
               i++;
           }
            
        });
        
        if(verificar){      
            $('#loading').show();
            if(true){
                $.ajax({
                    url: 'insertOrdenProduccionMagistral',
                    type: 'POST',
                    async: false,
                    contentType: 'application/json',
                    data: JSON.stringify(postData),
                    success: ResponseDml,
                    error: muestraError
                });
            }
        }
    }
}

function ResponseDml(datos)
{
    if(datos[0].error<0)
    {
         alert(datos[0].mensaje+"\nError: "+datos.error);
         $('#loading').hide();
    }
    else
    {
       alert(datos[0].mensaje);
       $('#divForm').slideDown();
       $('#formulario').hide();
       $('#loading').hide();
       $("#tblRegistros").flexReload();
       $('#demotable2').find('tbody').find('tr').remove();
        if(myObj.isNuevo){
            $('#formulario').hide();
            $('#divForm').slideDown();
            myObj.indice = 0;
        }
    }
}


//FUNCIONES PARA EXPORTAR
function Cancelar()
{
    $('#demotable2').find('tbody').find('tr').remove();
    if(myObj.isNuevo){
        $('#formulario').hide();
        $('#divForm').slideDown();
        myObj.indice = 0;
    }else{
        
        $('#formulario').hide();
        $('#divForm').slideDown();        
    }
}

function muestraError(error)
{
    alert("Error en la consulta codigo: "+error);
    $('#loading').hide();
}

function muestraErrorConsulta(error)
{
    alert("Error en la consulta codigo: "+error);
    $('#loading').hide();
    $('#divForm').slideDown();
}

//FUNCIONES PARA EXPORTAR
function exportar()
{
    var tipo = "Excel";
    var f=$("#fecha").get(0).value;
    var supId=$("#supId").get(0).name;
    var supNom=$("#supId").get(0).value;
    
    var cad= 'f='+f+'&supId='+supId+"&tipo="+tipo+"&t=1&supNom="+supNom;
    
    var obj_window = window.open(
		'exportar?'+cad,
		'Calendar', 'width=40,height=30,scrollbars=yes,status=no,resizable=yes,top=10,left=10,dependent=no,alwaysRaised=yes');
    obj_window.opener = window;
    obj_window.focus();
}

//FUNCIONES PARA IMPRIMIR LA ORDEN DE PRODUCION
function imprimir()
{
    var id=$("#id").get(0).value; 
    var obj_window = window.open(
		'ImprimirOrdenProduccionMagistral?id='+id,
		'Calendar', 'width=40,height=30,scrollbars=yes,status=no,resizable=yes,top=10,left=10,dependent=no,alwaysRaised=yes');
    obj_window.opener = window;
    obj_window.focus();
}


//FUNCIONES PARA IMPRIMIR EL ALISTAMIENTO
function alista()
{
    var id=$("#id").get(0).value; 
    var obj_window = window.open(
		'ImprimirAlistamientoOrdenMagistral?id='+id,
		'Calendar', 'width=40,height=30,scrollbars=yes,status=no,resizable=yes,top=10,left=10,dependent=no,alwaysRaised=yes');
    obj_window.opener = window;
    obj_window.focus();
}

//FUNCIONES PARA IMPRIMIR EL CONTROL DE CALIDAD
function ControlCalidad()
{
    var id=$("#id").get(0).value; 
    var obj_window = window.open(
		'ImprimirControlCalisdadOrdenMagistral?id='+id,
		'Calendar', 'width=40,height=30,scrollbars=yes,status=no,resizable=yes,top=10,left=10,dependent=no,alwaysRaised=yes');
    obj_window.opener = window;
    obj_window.focus();
}

function calcularCodLote(lote, med)
{
    var siglas = med.substring(0,4);
    var fec = $('#fecha').get(0).value;
    var cant = CantMedProg(siglas);
    var codLote = siglas+cant+fec.split("-")[0]+fec.split("-")[1]+fec.split("-")[2];
    $(lote).get(0).value = codLote;
}

function CantMedProg(cadena)
{
    var cant=0;
    var med = $('.medicamentos');

    $("#tblLabores tr").each(function (index) 
    {
        var string = $(med[index]).val();
        var letras = string.substring(0,4);
        
        if(cadena.toUpperCase() == letras.toUpperCase())
            cant++;
    });
    
    return cant;
}