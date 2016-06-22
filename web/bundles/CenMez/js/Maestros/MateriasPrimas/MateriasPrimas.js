var columnas = [];
var botones = [];
function inicio()
{
     $('#forma').validate({
            debug: true,
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function(form){
                guardarRegistro();
                validacion = true;
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
    });
}

function listFlexigrid()
{
    activarCampos();
    if($('#l').get(0).value>0)
    {
        columnas = [{display: 'ID', name : 'M.id', width : 80, sortable : true, align: 'left'},
                    {display: 'Nombre', name : 'M.nombre', width : 200, sortable : true, align: 'left'},
                    {display: 'Presentación', name : 'M.presentacion', width : 100, sortable : true, align: 'left'},
                    {display: 'Unidad Medida', name : 'M.unisigla', width : 100, sortable : true, align: 'left'},
                    {display: 'Costo', name : 'M.costo', width : 100, sortable : true, align: 'left'},
                    {display: 'Estado', name : 'ES.estnombre', width : 100, sortable : true, align: 'left'}];
    }
    
  $("#tblRegistros").flexigrid({
            url: 'allMateriasPrimas',
            dataType: 'json',
            colModel : columnas,
            buttons : botones,
            searchitems : [
                    {display: 'ID', name : 'M.id'},
                    {display: 'Nombre', name : 'M.nombre', isdefault: true},
                    {display: 'Presentación', name : 'M.presentacion'},
                    {display: 'Unidad Medida', name : 'M.unisigla'},
                    {display: 'Costo', name : 'M.costo'},
                    {display: 'Estado', name : 'ES.estnombre'}
                    ],
            sortname: "M.id",
            sortorder: "asc",
            title: "LISTADO MAESTRO MATERIAS PRIMAS",
            usepager: true,
            useRp: true,
            rp: 15,
            showTableToggleBtn: true,
            width: 880,
            height: 380
    });
			
}

function nuevo(com,grid)
{
    if($('#e').get(0).value==0)
    {
        alert("No tiene permisos para crear nuevos registros");
    }
    else
    {
        $('#divForm').hide();
        $('#formulario').slideDown(); 
        $("#titulo").html('NUEVA MATERIA PRIMA');
        $('#id').get(0).value="0";

        $('#nom').get(0).value="";
        $('#costo').get(0).value="";
        $('#present').get(0).value="";
        $('#nom').focus();
        ComboDependiente("uniId","../../combos/UnidadesDeMedida", "","");
        ComboDependiente("estId","../../combos/estadoRegistro", "","");
    }
}


function Cancelar()
{
    $('#formulario').hide(); 
    $('#divForm').slideDown();   
}

function RedireccionFlexigrid(id)
{
    if($('#u').get(0).value==0)
    {
        alert("No tiene permisos para editar lotes");
    }
    else
    {
        $('#divForm').hide();
        $('#loading').show();
        $.ajax({
              url: 'consultaMateriaPrimaId',
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
        var row = data[0];
        $('#id').get(0).value=row.id;
        
        $('#divForm').hide();
        $('#formulario').slideDown(); 
        $("#titulo").html('MATERIA PRIMA ID: '+row.id);
        
        
        $('#nom').get(0).value=row.nombre;
        $('#costo').get(0).value=row.costo;
        $('#present').get(0).value=row.presentacion;
        $('#nom').focus();
        
        ComboDependiente("uniId","../../combos/UnidadesDeMedida", row.unisigla, row.uniid);
        ComboDependiente("estId","../../combos/estadoRegistro", row.estnombre, row.estid);
        $('#loading').hide();

    }
    else
    {
        alert("no hay datos para mostrar");
        $('#loading').hide();
        $('#divForm').slideDown();    
    }    
}



function muestraError(error)
{
    alert("Error en la consulta \nCodigo Error: "+error);
    $('#loading').hide();
}

function muestraErrorConsulta(error)
{
    alert("Error en la consulta codigo: "+error);
    $('#loading').hide();
    $('#divForm').slideDown();
}


function guardarRegistro()
{
    var id=$('#id').get(0).value;
    var nom = $('#nom').get(0).value;
    var costo = $('#costo').get(0).value;
    var present = $('#present').get(0).value;
    var uniId = $('#uniId').get(0).value;
    var est=$('#estId').get(0).value;

    var cad="id="+id+"&nom="+nom+"&costo="+costo+"&present="+present+"&uniId="+uniId+"&est="+est;

    $('#divForm').hide();
    $('#loading').slideDown();
    $.ajax({
          url: 'dmlMateriaPrima',
          type: 'POST',
          async: true,
          data: cad,
          success: ResponseDmlProductos,
          error: muestraError	
     });
}

function ResponseDmlProductos(datos)
{
    if(datos.error<0)
    {
         alert(datos.mensaje+"\nError: "+datos.error);
         $('#loading').hide();
    }
    else
    {
       alert(datos.mensaje+"\nRegistro ID: "+datos.id)
       if($('#id').get(0).value==0)
       {
            $('#nom').get(0).value="";
            $('#costo').get(0).value="";
            $('#present').get(0).value="";
            $('#nom').focus();
       }
       else
       {
           $('#divForm').slideDown();
           $('#formulario').hide();
       }
       $('#loading').hide();
       $("#tblRegistros").flexReload();
    }
}

function Delect(com,grid)
{
    if($('#d').get(0).value==0)
    {
        alert("No tiene permisos para borrar productos");
    }
    else
    {
        if($('.trSelected',grid).length>0)
        {
            if(confirm('Esta segura que quiere eliminar ' + $('.trSelected',grid).length + ' registros?'))
            {
                $('#loading').show();
                var datos ="";
                var items = $('.trSelected',grid);
                var itemlist ='';
                var msg="";
                var id;
                for(i=0;i<items.length;i++)
                {
                    id=items[i].id.substr(3);
                    datos ="id="+id+"&del=1";
                    $.ajax({
                        url: 'dmlMateriaPrima',
                            type: 'POST',
                            async: false,
                            data: datos,
                            success: function(data){
                                    if (parseInt(data.error)<0)
                                    {
                                        msg+=data.mensaje+" - Proveedor ID:"+data.id+"\n";
                                    }
                                    else
                                    {
                                       itemlist+= id+", "; 
                                    }
                               },
                            error: muestraError
                     });

                }
                if(itemlist!='')
                {
                    alert("Se eliminaron los siguientes Proveedores: "+itemlist);
                    $("#tblRegistros").flexReload();
                    if(msg!="")
                        alert("Se presentaron problemas para eliminar algunos registros debido a: \n"+msg);
                }
                else
                {
                    alert("Se presentaron problemas para eliminar algunos registros debido a: \n"+msg);
                }

                $('#loading').hide();
            }
        }
    }
}

//FUNCIONES PARA EXPORTAR
function Exportar(tipo)
{
    var StringConsulta = $("input[name='q']").get(0).value;
    var CampoConsulta = $("select[name='qtype']").get(0).value;
    var cad= 'StringConsulta='+StringConsulta+'&CampoConsulta='+CampoConsulta+"&tipo="+tipo;
    var obj_window = window.open(
		'exportarProveedores?'+cad,
		'Calendar', 'width=40,height=30,scrollbars=yes,status=no,resizable=yes,top=10,left=10,dependent=no,alwaysRaised=yes');
    obj_window.opener = window;
    obj_window.focus();
}