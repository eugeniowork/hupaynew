$(document).ready(function(){
    
    var attendanceId;
    var searchOption = "";
    $('.datepicker').datepicker();
    $('.datepicker').datepicker("option","defaultDate", new Date());



    $('.optionSearch').on('click',function(event){
        var id = event.target.id;
        $('input:radio').prop('checked',false);
        $('#'+id).prop('checked',true);
        searchOption = id;
    })
    $('.searchBtn').on('click',function(){
        var url ="";
        var searchResult = "";
        var dateFrom = "";
        var dateTo = "";
        var proceed = true;
        if(searchOption == "optionSearchAll"){
            url = 'getAllAttendance';
            searchResult = "All";
        }
        else if(searchOption == "optionSearchCurrentCutOff"){
            searchResult = "Current Cut off";
            url = "getCutOffAttendance";
        }
        else if(searchOption == "optionSearchSpecificDate"){
            searchResult = "Specific date";
            dateFrom = $('.date-from').val();
            dateTo = $('.date-to').val();
            url = "getSpecificDateAttendance";
            proceed = false;
        }

        if(url == ""){
            toast_options(4000);
            toastr.warning("Please choose from <strong>Search Option</strong>.");
        }
        else{
            if(proceed){
                $('.attendance-loading').show();
                $('#attendanceTable').dataTable().fnDestroy();
                $('.attendance-body').hide();
                $('#attendanceTable tbody').empty()
                $.ajax({
                    url:base_url+'attendance_controller/'+url,
                    type:'post',
                    dataType:'json',
                    data:{
                        searchOption:searchOption,
                        dateFrom:dateFrom,
                        dateTo:dateTo,
                    },
                    success:function(response){
                        if(response.status == "success"){
                            $('.searchByValue').text(searchResult);
                            $('.attendance-body').show();
                            $('#attendanceTable tbody').empty()
                            response.attendanceFinal.forEach(function(data,key){
                                if(data.holiday_type){
                                    var tr = '<tr id='+data.attendance_id+'>'+
                                        '<td>'+data.date_format+'</td>'+
                                        '<td style="background-color:#2980b9;color:#fff">'+data.holiday_type+'</td>'+
                                        '<td style="background-color:#2980b9;color:#fff">'+data.holiday_value+'</td>'+
                                        '<td>No Action</td>'+
                                    '</tr>';
                                    $('#attendanceTable tbody').append(tr)
                                }
                                else{
                                    var timeIn = "-";
                                    var timeOut = "-";
                                    if(data.timeFrom){
                                        timeIn = data.timeFrom;
                                    }
                                    if(data.timeTo){
                                        timeOut = data.timeTo;
                                    }
                                    var button = "-";
                                    if(data.timeTo && data.timeFrom){
                                        button = '<button data-toggle="modal" data-target="#editAttendanceModal" class="editAttendanceBtn" id='+data.attendance_id+'><i id='+data.attendance_id+' class="fas text-success fa-pencil-alt"></i></button>';
                                    }
                                    var tr = '<tr id='+data.attendance_id+'>'+
                                        '<td>'+data.date_format+'</td>'+
                                        '<td>'+timeIn+'</td>'+
                                        '<td>'+timeOut+'</td>'+
                                        '<td>'+button+'</td>'+
                                    '</tr>';
                                    $('#attendanceTable tbody').append(tr)
                                }
                                
                            })
                            
                            $('.attendance-loading').hide();
                            $('#attendanceTable').dataTable({
                                "ordering": false,
                                "info":     false
                            });
                            
                        }
                    },
                    error:function(response){

                    }
                })
            }
            else{
                toast_options(4000);
                toastr.warning("Please provide a <strong>Date From</strong> and <strong>Date To</strong>.");
            }
        }
    })
    $(document).on('click', '.editAttendanceBtn',function(event){
        attendanceId = event.target.id;
        $.ajax({
            url:base_url+'attendance_controller/getUpdateAttendance',
            type:'post',
            dataType:'json',
            data:{
                attendanceId:attendanceId
            },
            success:function(response){
                if(response.status == "success"){
                    $('.dateValue').text(response.date)
                    $('.hour_time_in').val(response.hour_time_in);
                    $('.min_time_in').val(response.min_time_in);
                    $('.period_time_in option[value='+response.period_time_in+']').attr('selected','selected')
                    $('.hour_time_out').val(response.hour_time_out);
                    $('.min_time_out').val(response.min_time_out);
                    $('.period_time_out option[value='+response.period_time_out+']').attr('selected','selected')
                }
            },
            error:function(response){

            }
        })
    })
    $('.number-only').keyup(function(e){
        var val = $(this).val();
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false; 
        }
        else{
            if(val.length != 2){
                if ($(this).val().length > 2){
                    $(this).val($(this).val().slice(0,-1));
                }
                $(this).attr("maxlength","2");
            }
        }
        
    })
    var loadingUpdateAttendance = false;
    $('.save-change-btn').on('click',function(){
        var btnName = this;
        //var time_in = $("input[name='hour_time_in']").val()  + ":"+ $("input[name='min_time_in']").val() + ":"+ $("input[name='sec_time_in']").val();
		//var time_out = $("input[name='hour_time_out']").val()  + ":"+ $("input[name='min_time_out']").val() + ":"+ $("input[name='sec_time_out']").val();
        if(!loadingUpdateAttendance){
            loadingUpdateAttendance = true;
            
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Requesting . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.update-attendance-warning').empty();
            $.ajax({
                url:base_url+'attendance_controller/updateAttendance',
                type:'post',
                dataType:'json',
                data:{
                    attendanceId:attendanceId,
                    hourTimeIn:$('.hour_time_in').val(),
                    minTimeIn:$('.min_time_in').val(),
                    periodTimeIn:$('.period_time_in').val(),
                    hourTimeOut:$('.hour_time_out').val(),
                    minTimeOut:$('.min_time_out').val(),
                    periodTimeOut:$('.period_time_out').val(),
                    remarks:$('.remarks').val(),
                },
                success:function(response){
                    if(response.status == "success"){
                        toast_options(4000);
                        toastr.success("Your request has been sent. Page will be reloaded shortly.");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
                    }
                    else{
                        render_response('.update-attendance-warning',response.msg, "danger")
                        loadingUpdateAttendance = false;
                        change_button_to_default(btnName, 'Request Update');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateAttendance = false;
                    change_button_to_default(btnName, 'Request Update');
                    //render_response('.update-attendance-warning',"There was a problem, please try again.", "danger")
                }
            })
        }
        
    })
    $("#editAttendanceModal").on('hide.bs.modal', function(){
        $('.update-attendance-warning').empty();
        $('.remarks').val("");
    });


    //for file overtime
    $('.attendance-date-ot').on('change',function(e){
        var date = e.target.value;

        $.ajax({
            url:base_url+'working_days_controller/getOverTimeType',
            type:'post',
            dataType:'json',
            data:{
                date:date,
            },
            success:function(response){
                if(response.status == "success"){
                    if(response.finalHolidayType == "Regular"){
                        $('.hour-time-in-ot').attr('disabled', 'disabled');
                        $('.min-time-in-ot').attr('disabled', 'disabled');
                        $('.period-time-in-ot').attr('disabled', 'disabled');
                    }
                    else{
                        $('.hour-time-in-ot').removeAttr('disabled');
                        $('.min-time-in-ot').removeAttr('disabled');
                        $('.period-time-in-ot').removeAttr('disabled');
                    }
                }
            },
            error:function(response){

            }
        })

    })

    var loadingAddOt = false;
    $('.submit-ot-btn').on('click',function(){
        var btnName = this;
        if(!loadingAddOt){
            $('.add-ot-warning').empty();
            loadingAddOt = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $.ajax({
                url:base_url+'attendance_controller/addOt',
                type:'post',
                dataType:'json',
                data:{
                    attendanceDateOt:$('.attendance-date-ot').val(),
                    hourTimeOutOt:$('.hour-time-out-ot').val(),
                    minTimeOutOt:$('.min-time-out-ot').val(),
                    periodTimeOutOt:$('.period-time-out-ot').val(),
                    hourTimeInOt:$('.hour-time-in-ot').val(),
                    minTimeInOt:$('.min-time-in-ot').val(),
                    periodTimeInOt:$('.period-time-in-ot').val(),
                    remarksOt:$('.remarks-ot').val(),
                },
                success:function(response){
                    if(response.status == "success"){
                        toast_options(4000);
                        toastr.success("Overtime file has been submitted. Page will be reloaded shortly.");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
                    }
                    else{
                        render_response('.add-ot-warning',response.msg, "danger")
                        loadingAddOt = false;
                        change_button_to_default(btnName, 'Submit');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAddOt = false;
                    change_button_to_default(btnName, 'Submit');
                }
            })
        }
    })
    var loadingAddAttendance = false;
    $('.submit-attendance-btn').on('click',function(){
        var btnName = this;
        if(!loadingAddAttendance){
            loadingAddAttendance = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.add-attendance-warning').empty();
            $.ajax({
                url:base_url+'attendance_controller/addAttendance',
                type:'post',
                dataType:'json',
                data:{
                    addAttendanceDate:$('.add-attendance-date').val(),
                    hourTimeOutAttendance:$('.hour-time-out-attendance').val(),
                    minTimeOutOtAttendance:$('.min-time-out-attendance').val(),
                    periodTimeOutAttendance:$('.period-time-out-attendance').val(),
                    hourTimeInAttendance:$('.hour-time-in-attendance').val(),
                    minTimeInAttendance:$('.min-time-in-attendance').val(),
                    periodTimeInAttendance:$('.period-time-in-attendance').val(),
                    remarksAttendance:$('.remarks-attendance').val(),
                },
                success:function(response){
                    if(response.status == "success"){
                        toast_options(4000);
                        toastr.success("Attendance was successfully submitted. Page will be reloaded shortly.");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
                    }
                    else{
                        render_response('.add-attendance-warning',response.msg, "danger")
                        loadingAddAttendance = false;
                        change_button_to_default(btnName, 'Submit');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAddAttendance = false;
                    change_button_to_default(btnName, 'Submit');
                }
            })
        }
    })
    $('.open-file-leave-btn').on('click',function(response){
        $('.leave-type').empty();
        $.ajax({
            url:base_url+'leave_controller/getTypesOfLeave',
            type:'post',
            dataType:'json',
            data:{
                leaveId:0,
                status:"Add",
            },
            success:function(response){
                if(response.status == "success"){
                    var selectedDefault = '<option selected disabled>Please Select</option>';
                    $('.leave-type').append(selectedDefault);
                    response.leaveOptions.forEach(function(data,key){
                        var option = '<option value='+data.lt_id+'>'+data.name+'</option>';
                        $('.leave-type').append(option);
                    });
                }
            },
            error:function(response){
                toast_options(4000);
                toastr.error("There was a problem, please try again!");
            }
        })
    })
    loadingSubmitLeave = false;
    $('.submit-leave-btn').on('click',function(response){
        var btnName = this;
        if(!loadingSubmitLeave){
            loadingSubmitLeave = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.file-leave-warning').empty();
            $.ajax({
                url:base_url+'attendance_controller/addLeave',
                type:'post',
                dataType:'json',
                data:{
                    leaveType:$('.leave-type').val(),
                    dateFromLeave:$('.date-from-leave').val(),
                    dateToLeave:$('.date-to-leave').val(),
                    remarksLeave:$('.remarks-leave').val(),
                    fileLeaveType:'Leave with pay',
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
                        render_response('.file-leave-warning',response.msg, "danger")
                        loadingSubmitLeave = false;
                        change_button_to_default(btnName, 'Submit');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingSubmitLeave = false;
                    change_button_to_default(btnName, 'Submit');
                }
            })
        }
    })


    function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})