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
    listasAutoc("#medId", "../../autocomplete/medicamentosFull");
}

function listado()
{
    if($('#e').get(0).value>0)
        botones2 = [{name: 'Nuevo', bclass: 'add', onpress : nuevo}];
    if($('#l').get(0).value>0)
    {
        columnas = [
                    {display: 'ID', name : 'R.id', width : 50, sortable : true, align: 'left'},
                    {display: 'MEDICAMENTO', name : 'M.nombre', width : 200, sortable : true, align: 'left'},
                    {display: 'MATERIA PRIMA', name : 'MP.nombre', width : 200, sortable : true, align: 'left'},
                    {display: 'CANTIDAD REQUERIDA', name : 'R.cantrequerida', width : 150, sortable : false, align: 'left'},
                    {display: 'UNIDAD', name : 'U.unisigla', width : 100, sortable : true, align: 'left'}
                    ];
    }
    
    $("#tblRegistros").flexigrid({
            url: 'allRequerimientosProduccion',
            dataType: 'json',
            colModel : columnas,
            buttons : botones2,
            searchitems : [
                    {display: 'ID', name : 'R.id'},
                    {display: 'MEDICAMENTO', name : 'M.nombre', isdefault: true},
                    {display: 'MATERIA PRIMA', name : 'MP.nombre'},
                    {display: 'CANTIDAD REQUERIDA', name : 'R.cantrequerida'},
                    {display: 'UNIDAD', name : 'U.unisigla'}
                    ],
            sortname: "R.id",
            sortorder: "asc",
            title: "LISTADO MATERIA PRIMA REQUERIDA MAGISTRALES",
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
        $('#formulario').slideDown();
        $('#btnInsertar').show();
        
        $("#medId").prop('disabled', false);
        $("#medId").get(0).value="";
        $("#medId").get(0).name="0";
        
        $('#divForm').hide();
        $('#formulario').slideDown(); 
        $('#demotable2').find('tbody').find('tr').remove();
        adicionar(0, 0);
        myObj.isNuevo = true;
        $('#tituloFormulario').text("NUEVO REQUERIMIENTO PRODUCCIÓN MAGISTRALES");
        $('#id').get(0).value=0;
        $("#medId").focus();
        
    }   
}

function adicionar(ban, index)
{
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
           + ' <input type="text" size=35" id="mp_'+myObj.indice+'" class="materiasprimas" name="0" idProg="'+id+'" />'
        + ' </td>'
        + '<td>' 
            + '<input type="text" size="12" id="cant_'+myObj.indice+'"  class="cantidad"/>'
        + '</td>'
        + '<td>' 
            + '<input type="text" size="12" id="uniId_'+myObj.indice+'" class="unidades" name="0" disabled/>'
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
    var mp = "#mp_"+myObj.indice;
    var cant = "#cant_"+myObj.indice;
    var uniId = "#uniId_"+myObj.indice;
    
//    listasAutoc(labId, "../../autocomplete/labores");
    $(mp).autocomplete({
        minLength: 2,        
        source: "../../autocomplete/materiaPrimaFull",
        select: function( event, ui ) {
                 $(mp).get(0).name= ui.item.id ;
                 $(uniId).get(0).value= ui.item.unisigla ;
            }
    });
    $(mp).focus();     
}


function validarCampos(indice)
{
    console.log(indice);
    var band=true;
    var mp = "#mp_"+indice;
    var cant = "#cant_"+indice;
    
    if($(mp).get(0).name=="0" || $(mp).get(0).name=="")
    {
        alert("Debe seleccionar una materia prima");
        $(mp).focus();
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
    var id = $("#mp_"+index).attr("idProg");
    var tr=$(this).parent().parent();
    if(id>0)
    {
        $('#loading').show();
        var datos ="id="+id;
        $.ajax({
            url: 'delectRequerimientoProduccion',
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
              url: 'findRequerimientosProduccion?id='+id,
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
        $('#tituloFormulario').text("REQUERIMIENTOS PRODUCCIÓN MAGISTRALES | MEDICAMENTO: "+ data[0].medicamento);
        $("#id").get(0).value="0";
        $("#medId").get(0).value=data[0].medicamento;
        $("#medId").get(0).name=data[0].medId;
        
        $("#medId").prop('disabled', true);
        
        var datos = data[1];
        for(var i=0; i<datos.length; i++)
        {
            var row = datos[i];
            adiccion(0, row.id, 0);
            
            $("#mp_"+i).get(0).name=row.mpId;
            $("#mp_"+i).get(0).value=row.materiaprima;
            
            $("#cant_"+i).get(0).value=row.cantrequerida;
            $("#uniId_"+i).get(0).value=row.unisigla;
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
    
    if($('#medId').get(0).value=="" || $('#medId').get(0).name=="")
    {
        alert("El medicamento es obligatorio");
    }
    else
    {
        var postData = [];
        var verificar = true;
        var medId=$('#medId').get(0).name;
        var mp = $('.materiasprimas');
        var cant = $('.cantidad');
        var i=0;
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
               postData[i] = {"id": $(mp[index]).attr("idProg"), "mpId": $(mp[index]).get(0).name, "cant": $(cant[index]).get(0).value, "medId": medId}; 
               i++;
           }
            
        });
        
        if(verificar){      
            $('#loading').show();
            if(true){
                $.ajax({
                    url: 'dmlRequerimientosProduccion',
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
function imprimir()
{
    var id=$("#id").get(0).value; 
    var obj_window = window.open(
		'ImprimirAlistamientoOrdenMagistral?id='+id,
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