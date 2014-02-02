$(function(){
    $("#seleccionar_todos").on('change',function(){
        $('table tbody :checkbox').attr("checked",$("#seleccionar_todos").is(':checked'));
    })

    $(".eliminar_seleccionados").on('click',function(event){
        event.preventDefault()
        $(".form_lista").submit();
    })
});