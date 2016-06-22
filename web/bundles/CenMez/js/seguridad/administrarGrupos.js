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

function listadoGrupos()
{
  $("#tblGrupos").flexigrid(	{
            url: 'allGrupos',
            dataType: 'json',
            colModel : [
                    {display: 'ID', name : 'g.gusuid', width : 50, sortable : true, align: 'left'},
                    {display: 'Nombre Grupo', name : 'g.gusunombre', width : 250, sortable : true, align: 'left'}
                    ],
            buttons : [
                  {name: 'Nuevo', bclass: 'add', onpress : nuevo},
                  {separator: true},
                  {name: 'Delete', bclass: 'delete', onpress : Delect},
                  {separator: true},
                  {name: 'Exportar', bclass: 'export', onpress : Exportar},
                  {separator: true}
                ],
            searchitems : [
                    {display: 'ID', name : 'g.gusuid'},
                    {display: 'Nombre Grupo', name : 'g.gusunombre', isdefault: true}
                    ],
            sortname: "g.gusuid",
            sortorder: "asc",
            title: "LISTADO GRUPOS",
            usepager: true,
            useRp: true,
            rp: 15,
            showTableToggleBtn: true,
            width: 894,
            height: 350
    });
			
}

function guardarGrupo()
{  
    var grupo = $('#nmGrupo').get(0).value;
    var id = $('#usuid').get(0).value;
    if(grupo=="")
    {
        alert("Debe ingresar un nombre del grupo");
        $('#nmGrupo').focus();
    }
    else
    {
        $('#loading').show();
        $.ajax({
            url: 'dmlGrupos',
            type: 'POST',
            async: false,
            data: 'nm='+grupo+'&id='+id,
            success: respuestaInsert,
            error: muestraError
        });
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
        var postData = [];
        conseguirDatos(datos.id, "tblMaestros", postData);
        conseguirDatos(datos.id, "tblOperacional", postData);
        conseguirDatos(datos.id, "tblInformes", postData);
        conseguirDatos(datos.id, "tblSig", postData);
        conseguirDatos(datos.id, "tblMovil", postData);
        conseguirDatos2(datos.id, "tblGerencia", postData);
        $.ajax({
            url: 'dmlGrupoPermisos',
            type: 'POST',
            async: false,
            contentType: 'application/json',
            data: JSON.stringify(postData),
            success: respuestaInsertDetalle,
            error: muestraError
        });
    }
}

function respuestaInsertDetalle(datos)
{
    $("#myTab li:eq(1) a").tab('show');
    if(datos.error<0)
    {
         alert(datos.mensaje+"\nError: "+datos.error);
         $('#loading').hide();
    }
    else
    {
       alert(datos.mensaje+"\nGrupo ID: "+datos.id);
       if($('#usuid').get(0).value==0)
       {
            $('#nmGrupo').get(0).value="";
            $('#nmGrupo').focus();
       }
       else
       {
           $('#divForm').slideDown();
           $('#formulario').hide();
       }
       $('#loading').hide();
       $("#tblGrupos").flexReload();
    }
}


function muestraError(datos)
{
    alert("Error al intentar guardar el grupo \nError: "+datos);
    $('#loading').hide();
}

function Exportar()
{
    var StringConsulta = $("input[name='q']").get(0).value;
    var CampoConsulta = $("select[name='qtype']").get(0).value;

    
    var cad= 'StringConsulta='+StringConsulta+'&CampoConsulta='+CampoConsulta;
    var obj_window = window.open(
		'exportarExcelGrupos?'+cad,
		'Calendar', 'width=40,height=30,scrollbars=yes,status=no,resizable=yes,top=10,left=10,dependent=no,alwaysRaised=yes');
    obj_window.opener = window;
    obj_window.focus();
}

function Delect(com,grid)
{
    if($('#delete').get(0).value==0)
    {
        alert("No tiene permisos para borrar Grupos");
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
                        url: 'dmlGrupos',
                            type: 'POST',
                            async: false,
                            data: datos,
                            success: function(data){
                                    if (parseInt(data.error)<0)
                                    {
                                        msg=data.mensaje+" - Grupo ID:"+data.id;
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
                    alert("Se eliminaron los siguientes Grupos: "+itemlist);
                    $("#tbllotes").flexReload();
                    if(msg!="")
                        alert("Se presentaron problemas para eliminar algunos registros debido a: \n"+msg);
                }
                else
                {
                    alert("Se presentaron problemas para eliminar algunos registros debido a: \n"+msg)
                }

                $('#loading').hide();
                $("#tblGrupos").flexReload();
            }
        }
    }
}

function nuevo(com,grid)
{
    $("#myTab li:eq(1) a").tab('show');
    if($('#escritura').get(0).value==0)
    {
        alert("No tiene permisos para crear grupos");
    }
    else
    {
        $('#divForm').hide();
        $('#formulario').slideDown();
        unSelectCampos();
        $("#titulo").html('CREAR NUEVO GRUPO');
        $('#usuid').get(0).value=0;
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
              url: 'consultaGrupoId',
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
        unSelectCampos();
        var enc=data[0]["grupo"][0];
        var detalle=data[0]["det"];
        
        
        $('#usuid').get(0).value=enc.gusuid;
        
        $('#divForm').hide();
        $('#formulario').slideDown(); 
        $("#titulo").html('GRUPO ID: '+enc.gusuid);
                
        $('#nmGrupo').get(0).value=enc["gusunombre"];
        $('#nmGrupo').focus();
        
        for(i=0; i<detalle.length; i++)
        {
            var row=detalle[i];
            if(row.lectura==1)
                $("#L_"+row.modid.toString()).get(0).checked=true;
            
            if(row.escritura==1)
                $("#E_"+row.modid.toString()).get(0).checked=true;
            
            if(row.update==1)
                $("#A_"+row.modid.toString()).get(0).checked=true;
            
            if(row.delete==1)
                $("#B_"+row.modid.toString()).get(0).checked=true;
                
        }
        
        $('#loading').hide();
    }
    else
    {
        alert("no hay datos para mostrar");
        $('#loading').hide();
        $('#divForm').slideDown();    
    }    
}

function unSelectCampos()
{
    var campos = $('input:checkbox');
    for(i=0; i<campos.length; i++)
    {
        $(campos[i]).get(0).checked=false;
    }
}

function muestraErrorConsulta(error)
{
    alert("Error en la consulta codigo: "+error);
    $('#loading').hide();
    $('#divForm').slideDown();
}

function conseguirDatos(id, table, postData)
{
   var tabla = $('#'+table).get(0);
   var index=0;
   for(i=0; i<tabla.rows.length; i++)
   {
       if(postData.length!=0 || postData.length!=null)
           index=postData.length;
       
       var L=0, E=0, A=0, B=0;
       var row= tabla.rows[i];
       if($("#L_"+row.id).get(0).checked==true)
            L=1;
       if($("#E_"+row.id).get(0).checked==true)
            E=1;
       if($("#A_"+row.id).get(0).checked==true)
            A=1;
       if($("#B_"+row.id).get(0).checked==true)
            B=1;

       postData[index] = { "grupo": id, "modulo": row.id, "leer": L, "escribir": E, "actualizar": A, "borrar": B};
   } 
}

function conseguirDatos2(id, table, postData)
{
   var tabla = $('#'+table).get(0);
   var index=0;
   for(i=0; i<tabla.rows.length; i++)
   {
       if(postData.length!=0 || postData.length!=null)
           index=postData.length;
       
       var L=0, E=0, A=0, B=0;
       var row= tabla.rows[i];
       if($("#L_"+row.id).get(0).checked==true)
            L=1;

       postData[index] = { "grupo": id, "modulo": row.id, "leer": L, "escribir": 0, "actualizar": 0, "borrar": 0};
   } 
}