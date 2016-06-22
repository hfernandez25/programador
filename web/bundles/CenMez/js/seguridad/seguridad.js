//FUNCION DEL INICIO
function inicio()
{            
    $( "#traid" ).autocomplete({
        minLength: 2,
        source: "../../autocomplete/trabajadoresdos",
        select: function( event, ui ) {
                 $( "#traid" ).get(0).name= ui.item.id ;
            }
    });
}

function listadoUsuarios()
{
  $("#tblUsuarios").flexigrid(	{
            url: 'allUsuarios',
            dataType: 'json',
            colModel : [
                    {display: 'ID', name : 'u.usuid', width : 50, sortable : true, align: 'left'},
                    {display: 'Trabajador', name : 't.tranombre', width : 200, sortable : true, align: 'left'},
                    {display: 'Usuario', name : 'u.updalogin', width : 120, sortable : true, align: 'left'},                    
                    {display: 'Grupo', name : 'g.gusunombre', width : 150, sortable : true, align: 'left'},                    
                    {display: 'Estado', name : 'et.estnombre', width : 80, sortable : true, align: 'left'}
                    ],
            buttons : [
                  {name: 'Nuevo', bclass: 'add', onpress : nuevo},
                  {separator: true},
                  {name: 'Delete', bclass: 'delete', onpress : Delect},
                  {separator: true},
                  {name: 'Exportar', bclass: 'export', onpress : Exportar},
                  {separator: true},
                ],
            searchitems : [
                    {display: 'ID', name : 'u.usuid'},
                    {display: 'Trabajador', name : 't.tranombre', isdefault: true},
                    {display: 'Usuario', name : 'u.updalogin'},                    
                    {display: 'Grupo', name : 'g.gusunombre'},                    
                    {display: 'Estado', name : 'et.estnombre'}
                    ],
            sortname: "u.usuid",
            sortorder: "asc",
            title: "LISTADO USUARIOS",
            usepager: true,
            useRp: true,
            rp: 15,
            showTableToggleBtn: true,
            width: 1094,
            height: 350
    });
			
}


function nuevo(com,grid)
{
    if($('#escritura').get(0).value==0)
    {
        alert("No tiene permisos para crear usuarios");
    }
    else
    {
        $('#divForm').hide();
        $('#formulario').slideDown(); 
        $("#titulo").html('NUEVO USUARIO');

        $('#usuario').get(0).value="";
        $('#clave').get(0).value="";
        $('#traid').get(0).value="";
        $('#traid').get(0).name="";
        $('#usuid').get(0).value="0";
        
        $('#usuario').focus();

        ComboDependiente("estado","../../combos/estadoRegistro", "","");
        ComboDependiente("grupo","../../combos/GrupoUsuarios", "","");
    }   
}

function guardarUsuario()
{   
    var usuario = $('#usuario').get(0).value;
    var clave = $('#clave').get(0).value;
    var traid = $('#traid').get(0).name;
    var grupo = $('#grupo').get(0).value;
    var estado = $('#estado').get(0).value;
    var id = $('#usuid').get(0).value;
    
    if(usuario=="" || clave=="")
        alert("Los campos usuario y clave son obligatorios");
    else
    {
        if(traid=="")
            alert("Debe seleccionar un trabajador");
        else
        {
            if(grupo==-1 || estado==-1)
                alert("Debe seleccionar un grupo y un estado");
            else
            {
                $('#loading').show();
                $.ajax({
                    url: 'dmlUsuarios',
                    type: 'POST',
                    async: false,
                    data: 'usu='+usuario+'&clave='+clave+'&traid='+traid+'&grupo='+grupo+'&estado='+estado+'&id='+id,
                    success: respuestaInsert,
                    error: muestraError
                });
            }
        }
    }
}

function respuestaInsert(datos)
{
    if(datos.error<0)
    {
         alert(datos.mensaje+"\nError: "+datos.error);
         $('#loading').hide();
    }
    else
    {
       alert(datos.mensaje+"\nRegistro ID: "+datos.id);
       if($('#usuid').get(0).value==0)
       {
            $('#usuario').get(0).value="";
            $('#clave').get(0).value="";
            $('#traid').get(0).name="";
            $('#traid').get(0).value="";
       }
       else
       {
           $('#divForm').slideDown();
           $('#formulario').hide();
       }
       $('#loading').hide();
       $("#tblUsuarios").flexReload();
    }
}

function muestraError(datos)
{
    alert("Error al intentar guardar el usuario \nError: "+datos);
    $('#loading').hide();
}

function Exportar()
{
    var StringConsulta = $("input[name='q']").get(0).value;
    var CampoConsulta = $("select[name='qtype']").get(0).value;

    
    var cad= 'StringConsulta='+StringConsulta+'&CampoConsulta='+CampoConsulta;
    var obj_window = window.open(
		'exportarExcelUsuarios?'+cad,
		'Calendar', 'width=40,height=30,scrollbars=yes,status=no,resizable=yes,top=10,left=10,dependent=no,alwaysRaised=yes');
    obj_window.opener = window;
    obj_window.focus();
}

function Delect(com,grid)
{
    if($('#delete').get(0).value==0)
    {
        alert("No tiene permisos para borrar usuarios");
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
                        url: 'dmlUsuarios',
                            type: 'POST',
                            async: false,
                            data: datos,
                            success: function(data){
                                    if (parseInt(data.error)<0)
                                    {
                                        msg=data.mensaje+" - Usuario ID:"+data.id;
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
                    alert("Se eliminaron los siguientes usuarios: "+itemlist);
                    $("#tbllotes").flexReload();
                    if(msg!="")
                        alert("Se presentaron problemas para eliminar algunos registros debido a: \n"+msg);
                }
                else
                {
                    alert("Se presentaron problemas para eliminar algunos registros debido a: \n"+msg)
                }

                $('#loading').hide();
                $("#tblUsuarios").flexReload();
            }
        }
    }
}

function Cancelar()
{
    $('#formulario').hide(); 
    $('#divForm').slideDown();   
}


function RedireccionFlexigrid(id)
{
    if($('#update').get(0).value==0)
    {
        alert("No tiene permisos para editar registros");
    }
    else
    {
        $('#divForm').hide();
        $('#loading').show();
        $.ajax({
              url: 'consultaUsuarioId',
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
        $('#usuid').get(0).value=row.usuid;
        
        $('#divForm').hide();
        $('#formulario').slideDown(); 
        $("#titulo").html('USUARIO ID: '+row.usuid);
        
        $('#usuario').get(0).value=row.updalogin;
        $('#clave').get(0).value=row.ptw;
        $('#traid').get(0).value=row.tranombre;
        $('#traid').get(0).name=row.traid;        
        $('#usuario').focus();
        var radios=document.getElementsByName("tipoInicio");
        for(var i=0;i<radios.length;i++)
        {
            if(radios[i].value==row.tipoInicio)
                radios[i].checked=true;
        }

        ComboDependiente("estado","../../combos/estadoRegistro", row.estnombre, row.estid);
        ComboDependiente("grupo","../../combos/GrupoUsuarios", row.gusunombre, row.gusuid);
        
        $('#loading').hide();
    }
    else
    {
        alert("no hay datos para mostrar");
        $('#loading').hide();
        $('#divForm').slideDown();    
    }    
}

function muestraErrorConsulta(error)
{
    alert("Error en la consulta codigo: "+error);
    $('#loading').hide();
    $('#divForm').slideDown();
}

