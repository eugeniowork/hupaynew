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
        
    })
    var id = null;
    $('.open-update-working-day').on('click',function(e){
        id = e.target.id;
        $('.update-working-days-info').hide();
        $('.update-working-days-btn').hide();
        $('.loading-update-working-days').show();
        $.ajax({
            url:base_url+'working_days_controller/viewUpdateWorkingDays',
            type:'post',
            dataType:'json',
            data:{
                id:id,
            },
            success:function(response){
                if(response.status == "success"){
                    $('.update-working-days-info').show();
                    $('.update-working-days-btn').show();
                    $('.loading-update-working-days').hide();
                    $('.day-from-update option[value='+response.day_from+']').attr('selected','selected');
                    $('.day-to-update option[value='+response.day_to+']').attr('selected','selected');
                }
                else{
                    show_loading_error_in_update_working_days(response.msg);
                }
            },
            error:function(response){
                show_loading_error_in_update_working_days(response.msg);

            }
        })
    })
    loadingUpdateWorkingDays = false;
    $('.update-working-days-btn').on('click',function(){
        var btnName = this;
        if(!loadingUpdateWorkingDays){
            loadingUpdateWorkingDays = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.update-working-days-warning').empty();
            $.ajax({
                url:base_url+'working_days_controller/updateWorkingDays',
                type:'post',
                dataType:'json',
                data:{
                    dayFrom:$('.day-from-update').val(),
                    dayTo:$('.day-to-update').val(),
                    id:id,
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
                        render_response('.update-working-days-warning',response.msg, "danger")
                        loadingUpdateWorkingDays = false;
                        change_button_to_default(btnName, 'Submit');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateWorkingDays = false;
                    change_button_to_default(btnName, 'Submit');
                }
            })
        }
        
    })
    loadingRemoveWorkingDays = false;
    $('.remove-working-days-btn').on('click',function(e){
        var id = e.target.id;
        var btnName = '.remove-working'+id;
        if(!loadingRemoveWorkingDays){
            var workingDaysName = $('.working-days-name'+id).text();
            loadingRemoveWorkingDays = true;
            Swal.fire({
                html: 'Are you sure you want to remove the <strong>Working Days</strong> of <strong>'+workingDaysName+'</strong>?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    //$('.row'+id).hide("slide");
                    $.ajax({
                        url:base_url+'working_days_controller/deleteWorkingDays',
                        type:'post',
                        dataType:'json',
                        data:{
                            id:id
                        },
                        success:function(response){
                            if(response.status == "success"){
                                toast_options(4000);
                                toastr.success(response.msg);
                                setTimeout(function(){
                                    window.location.reload();
                                },1000)
                                $('.row'+id).hide("slide");
                            }
                            else{
                                toast_options(4000);
                                toastr.error(response.msg);
                                loadingRemoveWorkingDays = false;
                            }
                        },
                        error:function(response){
                            toast_options(4000);
                            toastr.error("There was a problem, please try again!");
                            loadingRemoveWorkingDays = false;
                        }
                    })
                }
                
            })
        }
        else{
            toast_options(4000);
            toastr.warning("There is a data that is currently on process, please wait.");
        }
    })


    function show_loading_error_in_update_working_days(message){
        $('.loading-update-working-days').show();
        $('.loading-update-working-days').empty();
        $('.loading-update-working-days').append('<p class="text-danger" style="text-align:center">'+message+'</p>');
        id = null;
    }
    function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})