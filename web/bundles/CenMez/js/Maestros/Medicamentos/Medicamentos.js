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
        columnas = [{display: 'ID', name : 'M.id', width : 50, sortable : true, align: 'left'},
                    {display: 'Nombre', name : 'M.nombre', width : 200, sortable : true, align: 'left'},
                    {display: 'PRESENTACION', name : 'M.presentacion', width : 70, sortable : true, align: 'left'},
                    {display: 'UNIDAD', name : 'U.unisigla', width : 70, sortable : true, align: 'left'},
                    {display: 'VOLUMEN RECONSTITUCIÓN', name : 'M.volreconstitucion', width : 70, sortable : true, align: 'left'},
                    {display: 'VEHICULO RECONSTITUCIÓN', name : 'M.vehreconstitucion', width : 90, sortable : true, align: 'left'},
                    {display: 'CONCENTRACION MEDICAMENTO', name : 'M.concentacion', width : 70, sortable : true, align: 'left'},
                    {display: 'VEHICULO DE DILUCIÓN', name : 'M.vehdilucion', width : 90, sortable : true, align: 'left'},
                    {display: 'VOLUMEN VEHICULO DILUCION', name : 'M.volvehdilucion', width : 70, sortable : true, align: 'left'},
                    {display: 'ESTABILIDAD PRODUCTO RECONSTITUÍDO', name : 'M.estabprodreconstituido', width : 70, sortable : true, align: 'left'},
                    {display: 'ESTABILIDAD PREPARACIÓN', name : 'M.estabilidadpreparacion', width : 70, sortable : true, align: 'left'},
                    {display: 'UNIDADES CONCENTRACION', name : 'UC.unisigla', width : 70, sortable : true, align: 'left'},
                    {display: 'VALOR LIGADO PREPARACIÓN ', name : 'M.valorligadopreparacion', width : 70, sortable : true, align: 'left'},
                    {display: 'PRESENTACIÓN FARMACÉUTICA', name : 'M.presentacionfarmaceutica', width : 70, sortable : true, align: 'left'},
                    {display: 'OBSERVACIONES', name : 'M.observacion', width : 70, sortable : true, align: 'left'},
                    {display: 'CONDICIONES ALMACENAMIENTO', name : 'M.condalmacenamiento', width : 200, sortable : true, align: 'left'},
                    {display: 'ESTADO', name : 'ES.estnombre', width : 70, sortable : true, align: 'left'}];
    }
    
  $("#tblRegistros").flexigrid({
            url: 'allMedicamentos',
            dataType: 'json',
            colModel : columnas,
            buttons : botones,
            searchitems : [
                    {display: 'ID', name : 'M.id'},
                    {display: 'NOMBRE', name : 'M.nombre', isdefault: true},
                    {display: 'PRESENTACION', name : 'M.presentacion'},
                    {display: 'UNIDAD', name : 'U.unisigla'},
                    {display: 'VOLUMEN RECONSTITUCIÓN', name : 'M.volreconstitucion'},
                    {display: 'VEHICULO RECONSTITUCIÓN', name : 'M.vehreconstitucion'},
                    {display: 'CONCENTRACION MEDICAMENTO', name : 'M.concentacion'},
                    {display: 'VEHICULO DE DILUCIÓN', name : 'M.vehdilucion'},
                    {display: 'VOLUMEN VEHICULO DILUCION', name : 'M.volvehdilucion'},
                    {display: 'ESTABILIDAD PRODUCTO RECONSTITUÍDO', name : 'M.estabprodreconstituido'},
                    {display: 'CONDICIONES ALMACENAMIENTO', name : 'M.condalmacenamiento'},
                    {display: 'ESTABILIDAD PREPARACIÓN', name : 'M.estabilidadpreparacion'},
                    {display: 'UNIDADES CONCENTRACION', name : 'UC.unidconcentacion'},
                    {display: 'VALOR LIGADO PREPARACIÓN ', name : 'M.valorligadopreparacion'},
                    {display: 'PRESENTACIÓN FARMACÉUTICA', name : 'M.presentacionfarmaceutica'},
                    {display: 'OBSERVACIONES', name : 'M.observacion'},
                    {display: 'ESTADO', name : 'ES.estnombre'}
                    ],
            sortname: "M.id",
            sortorder: "asc",
            title: "LISTADO MAESTRO MEDICAMENTOS",
            usepager: true,
            useRp: true,
            rp: 15,
            showTableToggleBtn: true,
            width: 1090,
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
        $("#titulo").html('NUEVO MEDICAMENTO');
        $('#id').get(0).value="0";

        $('#nom').get(0).value="";
        $('#present').get(0).value="";
        $('#volreconstitucion').get(0).value="";
        $('#vehreconstitucion').get(0).value="";
        $('#concentacion').get(0).value="";
        $('#vehdilucion').get(0).value="";
        $('#volvehdilucion').get(0).value="";
        $('#estabprodreconstituido').get(0).value="";
        $('#condalmacenamiento').get(0).value="";
        $('#estabilidadpreparacion').get(0).value="";
        $('#valorligadopreparacion').get(0).value="";
        $('#presentacionfarmaceutica').get(0).value="";
        $('#obs').get(0).value="";
        
        ComboDependiente("uniId","../../combos/UnidadesDeMedida", "","");
        ComboDependiente("unidconcentacion","../../combos/UnidadesDeMedida", "","");
        ComboDependiente("estId","../../combos/estadoRegistro", "","");
        $('#nom').focus();
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
              url: 'consultaMedicamentoId',
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
        $("#titulo").html('MEDICAMENTO ID: '+row.id);
        
        
        $('#nom').get(0).value=row.nombre;
        $('#present').get(0).value=row.presentacion;
        $('#volreconstitucion').get(0).value=row.volreconstitucion;
        $('#vehreconstitucion').get(0).value=row.vehreconstitucion;
        $('#concentacion').get(0).value=row.concentacion;
        $('#vehdilucion').get(0).value=row.vehdilucion;
        $('#volvehdilucion').get(0).value=row.volvehdilucion;
        $('#estabprodreconstituido').get(0).value=row.estabprodreconstituido;
        $('#condalmacenamiento').get(0).value=row.condalmacenamiento;
        $('#estabilidadpreparacion').get(0).value=row.estabilidadpreparacion;
        $('#valorligadopreparacion').get(0).value=row.valorligadopreparacion;
        $('#presentacionfarmaceutica').get(0).value=row.presentacionfarmaceutica;
        $('#obs').get(0).value=row.observacion;
        
        ComboDependiente("uniId","../../combos/UnidadesDeMedida", row.uniid,row.uniid);
        ComboDependiente("unidconcentacion","../../combos/UnidadesDeMedida", row.unidconcentacionId,row.unidconcentacionId);
        ComboDependiente("estId","../../combos/estadoRegistro", row.estid,row.estid);
        $('#nom').focus();
        
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
    var present=$('#present').get(0).value;
    var volreconstitucion=$('#volreconstitucion').get(0).value;
    var vehreconstitucion=$('#vehreconstitucion').get(0).value;
    var concentacion=$('#concentacion').get(0).value;
    var vehdilucion=$('#vehdilucion').get(0).value;
    var volvehdilucion=$('#volvehdilucion').get(0).value;
    var estabprodreconstituido=$('#estabprodreconstituido').get(0).value;
    var condalmacenamiento=$('#condalmacenamiento').get(0).value;
    var estabilidadpreparacion=$('#estabilidadpreparacion').get(0).value;
    var valorligadopreparacion=$('#valorligadopreparacion').get(0).value;
    var presentacionfarmaceutica=$('#presentacionfarmaceutica').get(0).value;
    var obs=$('#obs').get(0).value;
    var uniId=$('#uniId').get(0).value;
    var unidconcentacion=$('#unidconcentacion').get(0).value;
    var est=$('#estId').get(0).value;

    var cad="id="+id+"&nom="+nom+"&present="+present+"&volreconstitucion="+volreconstitucion+"&vehreconstitucion="+vehreconstitucion+"&concentacion="+concentacion;
    cad+="&vehdilucion="+vehdilucion+"&volvehdilucion="+volvehdilucion+"&estabprodreconstituido="+estabprodreconstituido+"&condalmacenamiento="+condalmacenamiento;
    cad+="&estabilidadpreparacion="+estabilidadpreparacion+"&valorligadopreparacion="+valorligadopreparacion+"&presentacionfarmaceutica="+presentacionfarmaceutica;
    cad+="&obs="+obs+"&uniId="+uniId+"&unidconcentacion="+unidconcentacion+"&est="+est;

    $('#divForm').hide();
    $('#loading').slideDown();
    $.ajax({
          url: 'dmlMedicamentos',
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
            $('#present').get(0).value="";
            $('#volreconstitucion').get(0).value="";
            $('#vehreconstitucion').get(0).value="";
            $('#concentacion').get(0).value="";
            $('#vehdilucion').get(0).value="";
            $('#volvehdilucion').get(0).value="";
            $('#estabprodreconstituido').get(0).value="";
            $('#condalmacenamiento').get(0).value="";
            $('#estabilidadpreparacion').get(0).value="";
            $('#valorligadopreparacion').get(0).value="";
            $('#presentacionfarmaceutica').get(0).value="";
            $('#obs').get(0).value="";
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