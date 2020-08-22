$(document).ready(function(){
    $('#workingDaysList').dataTable();

    $('.add-working-days-btn').on('click',function(){
        $.ajax({
            url:base_url+'working_days_controller/addWorkingDays',
            type:'post',
            dataType:'json',
            data:{
                dayTo:$('.day-to').val(),
                dayFrom:$('.day-from').val(),
            },
            success:function(response){

            },
            error:function(response){
                
            }
        })
    })
})