function CampoDate(cam1, cam2)
{   
    $(cam1).datepicker({         
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        numberOfMonths: 1,
        showAnim: "drop",
        onClose: function( selectedDate ) {
                $(cam2).datepicker( "option", "minDate", selectedDate );
            }
    });
    
    $(cam2).datepicker({         
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        numberOfMonths: 1,
        showAnim: "drop",
        onClose: function( selectedDate ) {
                $(cam1).datepicker( "option", "maxDate", selectedDate );
            }
    });
}

function listasAutoc(campo, ruta)
{
    $( campo ).autocomplete({
        minLength: 2,        
        source: ruta,
        select: function( event, ui ) {
                 $( campo ).get(0).name= ui.item.id ;
            }
    });
}

function Combo(idLista, rutaCombo)
{
	var lista = document.getElementById(idLista);
	   $.get(rutaCombo,
            function(datos) {
		    var opciones = eval(datos);
	            lista.options[0] = new Option("- Selecciona -", '');
                    for(i=1; i<= opciones.length; i++) {
                        lista.options[i] = new Option(opciones[i-1].name, opciones[i-1].id);
                    } 
         });
}

function ComboDependiente(idLista, rutaCombo, datoInicial, idInicial)
{
	var pos=1;
	var lista = document.getElementById(idLista);
	   $.get(rutaCombo,
            function(datos) 
            {
                    var opciones = datos;
                    if((idInicial==="") || (idInicial==null))
                    {
                       lista.options[0] = new Option("-Selecciona-", '');
                       idInicial=null;
                    }
                    else
                    {
                        for(var i=0; i< opciones.length; i++) 
                        {
                            if(opciones[i].id === idInicial)
                                lista.options[0] = new Option(opciones[i].name, opciones[i].id);
                         }
                    }

					
                 for(var i=0; i< opciones.length; i++) 
                 {
                     if(opciones[i].id !== idInicial)
                     {
                         lista.options[pos] = new Option(opciones[i].name, opciones[i].id);
			 pos++;
                     }
                  } 
         });
}

/**
 * Funcion para eliminar las columnas de una tabla.
 */
function ClearTable(table)
{
    var trs=$("#"+table+" tr").length;  
    for(var i=0; i<trs; i++)
    {
        $("#"+table+" tr:last").remove();
    }
}

/**
 * FUNCION PARA ACTIVAR LOS CAMPOS DEL FLEXIGRID SEGUN PERMISOS
 */
function activarCampos()
{
    if ($('#e').get(0).value > 0)
    {
        botones.push({name: 'Nuevo', bclass: 'add', onpress : nuevo});
        botones.push({separator: true});
    }
    if($('#d').get(0).value>0){
        botones.push({name: 'Delete', bclass: 'delete', onpress : Delect});
        botones.push({separator: true});
    }
    if($('#l').get(0).value>0)
    {
//        botones.push({name: 'Excel', bclass: 'export', onpress : Exportar});
//        botones.push({separator: true});
//        botones.push({name: 'PDF', bclass: 'exportPdf', onpress : Exportar});
//        botones.push({separator: true});
    }
}


function r2d(numero)
{
    var flotante = parseFloat(numero);
    var resultado = Math.round(flotante*100)/100;
    return resultado;
}

function DiaDelAnio(anio, mes, dia)
{
    var ahora = new Date(anio, mes, dia);
    var comienzo = new Date(ahora.getFullYear(), 0, 1);
    console.log(comienzo)
    var dif = ahora - comienzo;
    var unDia = 1000 * 60 * 60 * 24;
    return (Math.ceil(dif / unDia)+1);
}