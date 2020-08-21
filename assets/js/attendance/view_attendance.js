$(document).ready(function(){
    
    var attendanceId;
    var searchOption = "";
    $('.datepicker').datepicker();
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
        }

        if(url == ""){
            toast_options(4000);
            toastr.warning("Please choose from <strong>Search Option</strong>.");
        }
        else{
            if(dateFrom != "" && dateTo != ""){
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
        function change_button_to_default(btnName, btnText){
            $(btnName).prop('disabled', false);
            $(btnName).css('cursor','pointer');
            $(btnName).text(btnText);
        }
    })
    $("#editAttendanceModal").on('hide.bs.modal', function(){
        $('.update-attendance-warning').empty();
        $('.remarks').val("");
    });
})