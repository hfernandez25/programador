function autocompleta(campo, ruta)
{
    $( "#"+campo ).autocomplete({
        minLength: 2,        
        source: ruta,
        select: function( event, ui ) {
            $( "#"+campo ).get(0).name= ui.item.id ;
        }
    });
}