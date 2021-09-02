function getData(action){
    const nombre=$('#nombre').val();
    const edad=$('#edad').val();
    const date=$('#date').val();
    return{
        ajax:1,
        action: action,
        nombre: nombre,
        edad: edad,
        date: date,
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
        action: "Update2",
        id:id,
    }
}
function callapi(action,success){
    $.ajax({
        method: "Post",
        url: window.od_module.end_point,
        data: getData(action),
        success: success,
    })
}
function callapi2(success){
    $.ajax({
        method:"Post",
        url: window.od_module.end_point,
        data: dataCheck.call(this),
        context: this,
        success: success,
    })
}
function callapi3(success){
    $.ajax({
        method:"Post",
        url: window.od_module.end_point,
        data: dataDelete.call(this),
        context: this,
        success: success,
    })
}
$(document).on('click','#validar',function(){
    callapi("Validate",function(data){
        resultados=JSON.parse(data);
        console.log(resultados);
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
    callapi("Save",function(data){
        resultados=JSON.parse(data);
        console.log(resultados);
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
    $("img[alt='enabled.gif']").addClass('enabled');
    $("img[alt='disabled.gif']").addClass('disabled');
})
$(document).on('click',"img[alt='disabled.gif']",function(){
    callapi2.call(this, function(data){
        resultados=JSON.parse(data);
        let pregunta=confirm("Deseas activar el campo?")
        if(pregunta==true){
            location.reload();
        }
    }) 
})
$(document).on('click',"img[alt='enabled.gif']",function(){
    callapi3.call(this, function(data){
        resultados=JSON.parse(data);
        let pregunta=confirm("Deseas borrar el campo?")
        if(pregunta==true){
            location.reload();
        }
    })
})
$(document).on('click',"button[name='submitResetod_first_formulario']",function(){
    $("input[class='filter']").val('')
})
