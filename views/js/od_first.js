function getData(action){
    const nombre=$('#nombre').val();
    const edad=parseInt($('#edad').val());
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
function displayMessage($word,$html,$save){
    $("div[class='form-group alert alert-danger']").hide();
    $("div[class='form-group alert alert-success']").hide();
    $("div[class='form-wrapper']").append('<div id="#texto" class="form-group alert alert-' + $word + '">Datos ' + $html + $save + '</div>');
}
function readDataErrors($array){
    $array.forEach(function(error) {
        $('#'+error).removeClass('alert alert-danger').removeClass('alert alert-success');
        $('#'+error).addClass('alert alert-danger');
    });
}
function readDatacorrects($array){
    $array.forEach(function(correcto) {
        $('#'+correcto).removeClass('alert alert-danger').removeClass('alert alert-success');
        $('#'+correcto).addClass('alert alert-success');
    });
}
function date(){
    let dt = new Date();
    return ('0' + dt.getDate()).slice(-2) + "/" + 
        ('0' + (dt.getMonth()+1)).slice(-2) + "/" + 
        dt.getFullYear() + " " +
        dt.getHours() + ":" + 
        ('0' + dt.getMinutes()).slice(-2) + ":" + 
        ('0' + dt.getSeconds()).slice(-2);
}
$(document).on('click','#validar',function(){
    callapi(getData("Validate"),function(data){
        resultados=JSON.parse(data);
        readDataErrors(resultados.error);
        readDatacorrects(resultados.correcto);
        if(resultados.correcto.length == 3){
            displayMessage('success','correctos','');
        }else{
            displayMessage('danger','incorrectos','');
        }
    });
})
$(document).on('click','#guardar',function(){
    callapi(getData("Save"),function(data){
        resultados=JSON.parse(data);
        if(resultados !== true){
            readDataErrors(resultados.error);
            readDatacorrects(resultados.correcto);
            displayMessage('danger','incorrectos','. No se pudo guardar');
        }
        else{
            $('#nombre').addClass('alert alert-success');
            $('#edad').addClass('alert alert-success');
            $('#date').addClass('alert alert-success');
            displayMessage('success','correctos','. Se guardo');   
        }
    });
})
$(document).ready(function(){
    $( '#date' ).attr("autocomplete","off");
    $( '#date' ).attr("readonly","readonly");
    $( '#date' ).datepicker({
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
    });
})
$(document).on('click',"i[id='disabled']",function(){
    let pregunta=confirm("Deseas activar el campo?")
    if(pregunta==true){
    callapi.call(this,dataCheck.call(this), function(data){
        resultados=JSON.parse(data);
        $(this).attr('id','enabled').html('check')
        $(this).closest('tr').find('td').eq(7).empty()
    }) 
    }
})
$(document).on('click',"i[id='enabled']",function(){
    let pregunta=confirm("Deseas borrar el campo?")
    if(pregunta==true){
    callapi.call(this,dataDelete.call(this), function(data){
        resultados=JSON.parse(data);
        $(this).attr('id','disabled').html('close')
        $(this).closest('tr').find('td').eq(7).show().html(date());
    })
    }
})
 
