$(document).ready(function(){
    $('#workingDaysList').dataTable();

    var addWorkingDaysLoading = false;
    $('.add-working-days-btn').on('click',function(){
        var btnName = this;
        if(!addWorkingDaysLoading){
            addWorkingDaysLoading = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.add-working-days-warning').empty();
            $.ajax({
                url:base_url+'working_days_controller/addWorkingDays',
                type:'post',
                dataType:'json',
                data:{
                    dayTo:$('.day-to').val(),
                    dayFrom:$('.day-from').val(),
                },
                success:function(response){
                    if(response.status == "success"){
                        toast_options(4000);
                        toastr.success(response.msg);
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
                    }
                    else{
                        render_response('.add-working-days-warning',response.msg, "danger")
                        addWorkingDaysLoading = false;
                        change_button_to_default(btnName, 'Submit');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    addWorkingDaysLoading = false;
                    change_button_to_default(btnName, 'Submit');
                }
            })
        }
        function change_button_to_default(btnName, btnText){
            $(btnName).prop('disabled', false);
            $(btnName).css('cursor','pointer');
            $(btnName).text(btnText);
        }
    })
})