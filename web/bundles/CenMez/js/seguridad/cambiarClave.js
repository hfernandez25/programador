//FUNCION DEL INICIO
function inicio()
{            
    $('#forma').validate({
            rules: {
                oldClave: {required: true, equalTo: "#ptw" },
                newClave: {required: true,  minlength: 4},
                repitClave: {required: true,  minlength: 4, equalTo: "#newClave" }
            },
            messages: {
                oldClave: {equalTo: "La clave digitada no coincide con la actual" }
            },
            debug: true,
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function(form){
                guardar();
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



function guardar()
{   
    var oldClave = $('#oldClave').get(0).value;
    var newClave = $('#newClave').get(0).value;


    $('#loading').show();
    $.ajax({
        url: 'GuardarNuevaClave',
        type: 'POST',
        async: false,
        data: 'oldClave='+oldClave+'&newClave='+newClave,
        success: respuestaInsert,
        error: muestraError
    });
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
       alert(datos.mensaje);
       $('#oldClave').get(0).value="";
       $('#newClave').get(0).value="";
       $('#repitClave').get(0).value="";
       $('#loading').hide();
    }
}

function muestraError(datos)
{
    alert("Error al intentar guardar el usuario \nError: "+datos);
    $('#loading').hide();
}



function Cancelar()
{
    $('#formulario').hide(); 
    $('#divForm').slideDown();   
}