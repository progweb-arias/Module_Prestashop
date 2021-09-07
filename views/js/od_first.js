function getData(action){
    const nombre=$('#nombre').val();
    const edad=$('#edad').val();
    const fecha=$('#date').val();
    return{
        ajax:1,
        action: action,
        nombre: nombre,
        edad: edad,
        fecha: fecha,
    }
}
function dataCheck(){
    const id=parseInt($(this).closest('tr').find('td').html());
    return{
        ajax:1,
        action: "Update",
        id:id,
    }
}
function dataDelete(){
    const id=parseInt($(this).closest('tr').find('td').html());
    return{
        ajax:1,
        action: "Delete",
        id:id,
    }
}
function callapi(data,success){
    $.ajax({
        method: "Post",
        url: window.od_module.end_point,
        data: data,
        context: this,
        success: success,
    })
}
$(document).on('click','#validar',function(){
    callapi(getData("Validate"),function(data){
        resultados=JSON.parse(data);
        resultados.error.forEach(function(error) {
            $('#'+error).removeClass('alert alert-danger').removeClass('alert alert-success');
            $('#'+error).addClass('alert alert-danger');
        });
        resultados.correcto.forEach(function(correcto) {
            $('#'+correcto).removeClass('alert alert-danger').removeClass('alert alert-success');
            $('#'+correcto).addClass('alert alert-success');
        });
    });
})
$(document).on('click','#guardar',function(){
    callapi(getData("Save"),function(data){
        resultados=JSON.parse(data);
        if(resultados !== true){
            resultados.error.forEach(function(error) {
                $('#'+error).removeClass('alert alert-danger').removeClass('alert alert-success');
                $('#'+error).addClass('alert alert-danger');
            });
            resultados.correcto.forEach(function(correcto) {
                $('#'+correcto).removeClass('alert alert-danger').removeClass('alert alert-success');
                $('#'+correcto).addClass('alert alert-success');
            });
        }
        else{
            $('#nombre').addClass('alert alert-success');
            $('#edad').addClass('alert alert-success');
            $('#date').addClass('alert alert-success');
        }
    });
})

$(document).ready(function(){
    $( '#date' ).attr("autocomplete","off");
    $( '#date' ).datepicker();
})
$(document).on('click',"i[id='disabled']",function(){
    let pregunta=confirm("Deseas activar el campo?")
    if(pregunta==true){
    callapi.call(this,dataCheck.call(this), function(data){
        resultados=JSON.parse(data);
            location.reload();   
    }) 
    }
})
$(document).on('click',"i[id='enabled']",function(){
    let pregunta=confirm("Deseas borrar el campo?")
    if(pregunta==true){
    callapi.call(this,dataDelete.call(this), function(data){
        resultados=JSON.parse(data);
        location.reload();
    })
    }
})
 
