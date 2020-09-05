$(document).ready(function(){

	//for leave request list start
	get_leave_request_list();
	function get_leave_request_list(){
		$('#leaveRequestList tbody').empty();
		$.ajax({
			url:base_url+'leave_controller/getRequestList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#leaveRequestList tbody').append(response.finalData);
					$('#leaveRequestList').dataTable({
						ordering:false
					});
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
	//for leave request list end

	//for leave request list history start
	get_leave_list_history();
	function get_leave_list_history(){
		$('#leaveListHistory tbody').empty();
		$.ajax({
			url:base_url+'leave_controller/getListHistory',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#leaveListHistory tbody').append(response.finalData);
					$('#leaveListHistory').dataTable({
						ordering:false
					});
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
	//for leave request list history end

	//for employee leave list start
	get_employee_leave_list();
	function get_employee_leave_list(){
		$('#employeeLeaveList tbody').empty();
		$.ajax({
			url:base_url+'leave_controller/getEmployeeLeaveList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#employeeLeaveList tbody').append(response.finalData);
					$('#employeeLeaveList').dataTable({
						ordering:false
					});
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
	//for employee leave list end


	//for approval of leave start
	var approveLeaveId = null;
	$(document).on('click','.for-approve-leave-btn',function(e){
		approveLeaveId = e.target.id
	})
	var approveLeaveLoading = false;
	$('.approve-leave-btn').on('click',function(){
		var btnName = this;
		if(!approveLeaveLoading){
            approveLeaveLoading = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.approve-leave-warning').empty();
            $.ajax({
            	url:base_url+'leave_controller/approveLeave',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:approveLeaveId,
            		password:$('.approve-leave-password').val(),
            	},
            	success:function(response){
            		if(response.status == "success"){
                        toast_options(4000);
                        toastr.success("Leave was successfully approved.");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
                        $('.leave-approval-'+approveLeaveId).remove()
                    }
                    else{
                        render_response('.approve-leave-warning',response.msg, "danger")
                        approveLeaveLoading = false;
                        change_button_to_default(btnName, 'Approve');
                    }
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    approveLeaveLoading = false;
                    change_button_to_default(btnName, 'Approve');
            	}

            })
        }
	})
	//for approval of leave end

	//for disapproval of leave start
	var disapproveLeaveId
	$(document).on('click','.for-disapprove-leave-btn',function(e){
		disapproveLeaveId = e.target.id;
	})
	var disapproveLeaveLoading
	$('.disapprove-leave-btn').on('click',function(){
		var btnName = this;
		if(!disapproveLeaveLoading){
            disapproveLeaveLoading = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.disapprove-leave-warning').empty();
            $.ajax({
            	url:base_url+'leave_controller/disapproveLeave',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:disapproveLeaveId,
            		password:$('.disapprove-leave-password').val(),
            	},
            	success:function(response){
            		if(response.status == "success"){
                        toast_options(4000);
                        toastr.success("Leave was successfully disapproved.");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
                        $('.leave-approval-'+disapproveLeaveId).remove()
                    }
                    else{
                        render_response('.disapprove-leave-warning',response.msg, "danger")
                        disapproveLeaveLoading = false;
                        change_button_to_default(btnName, 'Disapprove');
                    }
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    disapproveLeaveLoading = false;
                    change_button_to_default(btnName, 'Disapprove');
            	}

            })
        }
	})
	//for disapproval of leave end
})