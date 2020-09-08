$(document).ready(function(){
    $('#workingDaysList').dataTable();
    $('#workingHoursList').dataTable();
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
    $(document).on('click','.remove-working-hours',function(e){
        var id = e.target.id;
        Swal.fire({
            html: 'Are you sure you want to remove the <strong>Working Hours</strong> of <strong>'+$('.working-hours-'+id).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url:base_url+'working_hours_controller/deleteWorkingHours',
                    type:'post',
                    dataType:'json',
                    data:{
                        id:id,
                    },
                    success:function(response){
                        if(response.status == "success"){
                            toast_options(4000);
                            toastr.success(response.msg);
                            setTimeout(function(){
                                window.location.reload();
                            },1000)
                            $('.working-hours-tr-'+id).hide("slide");
                        }
                        else{
                            toast_options(4000);
                            toastr.error("There was a problem, please try again!");
                        }
                    },
                    error:function(response){
                        toast_options(4000);
                        toastr.error("There was a problem, please try again!");
                    }
                })
            }
                
        })
    })
    
    var loadingAddWorkingHours = false;
    $('.add-working-hours-btn').on('click',function(){
        var btnName = this;
        if(!loadingAddWorkingHours){

            loadingAddWorkingHours = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.add-working-hours-warning').empty();
            $.ajax({
                url:base_url+'working_hours_controller/addWorkingHours',
                type:'post',
                dataType:'json',
                data:{
                    timeInH:$('.time-in-h').val(),
                    timeInM:$('.time-in-m').val(),
                    timeInS:$('.time-in-s').val(),
                    timeOutH:$('.time-out-h').val(),
                    timeOutM:$('.time-out-m').val(),
                    timeOutS:$('.time-out-s').val(),
                    
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
                        render_response('.add-working-hours-warning',response.msg, "danger")
                        loadingAddWorkingHours = false;
                        change_button_to_default(btnName, 'Submit');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAddWorkingHours = false;
                    change_button_to_default(btnName, 'Submit');
                }
            })
        }
    })

    var updateWorkingHoursId = null;
    $(document).on('click','.open-update-time-hours',function(e){
        updateWorkingHoursId = e.target.id;
        $('.update-working-hours-info').hide();
        $('.update-working-hours-btn').hide();
        $('.loading-update-working-hours').show();
        $.ajax({
            url:base_url+'working_hours_controller/viewUpdateWorkingHours',
            type:'post',
            dataType:'json',
            data:{
                id:updateWorkingHoursId,
            },
            success:function(response){
                if(response.status == "success"){
                    $('.update-working-hours-info').show();
                    $('.update-working-hours-btn').show();
                    $('.loading-update-working-hours').hide();
                    if(response.finalData.length >0){
                        response.finalData.forEach(function(data,key){
                            $('.update-time-in-h').val(data.timeInH)
                            $('.update-time-in-m').val(data.timeInM)
                            $('.update-time-out-h').val(data.timeOutH)
                            $('.update-time-out-m').val(data.timeOutM)
                        })
                    }
                }
                else{
                    $('.loading-update-working-hours').show();
                    $('.loading-update-working-hours').empty();
                    $('.loading-update-working-hours').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                    updateWorkingHoursId = null
                }
            },
            error:function(response){
                $('.loading-update-working-hours').show();
                $('.loading-update-working-hours').empty();
                $('.loading-update-working-hours').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                updateWorkingHoursId = null

            }
        })
    })

    var loadingUpdateWorkingHours = false;
    $('.update-working-hours-btn').on('click',function(){
        var btnName = this;
        if(!loadingUpdateWorkingHours){

            loadingUpdateWorkingHours = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.add-working-hours-warning').empty();
            $.ajax({
                url:base_url+'working_hours_controller/updateWorkingHours',
                type:'post',
                dataType:'json',
                data:{
                    id:updateWorkingHoursId,
                    timeInH:$('.update-time-in-h').val(),
                    timeInM:$('.update-time-in-m').val(),
                    timeInS:$('.update-time-in-s').val(),
                    timeOutH:$('.update-time-out-h').val(),
                    timeOutM:$('.update-time-out-m').val(),
                    timeOutS:$('.update-time-out-s').val(),
                    
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
                        render_response('.update-working-hours-warning',response.msg, "danger")
                        loadingUpdateWorkingHours = false;
                        change_button_to_default(btnName, 'Update');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateWorkingHours = false;
                    change_button_to_default(btnName, 'Update');
                }
            })
        }
    })


    $('.show-working-hours').on('click',function(){
        $('.working-hours').show();
        $('.working-days').hide();
    })
    $('.show-working-days').on('click',function(){
        $('.working-hours').hide();
        $('.working-days').show();
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