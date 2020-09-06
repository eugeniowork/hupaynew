$(document).ready(function(){
	//add attendance updates start
	get_leave_request_list();
	function get_leave_request_list(){
		$('#attendanceUpdates tbody').empty();
		$('#attendanceUpdates').dataTable().fnDestroy();
		$.ajax({
			url:base_url+'attendance_controller/getAttendanceRequestList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#attendanceUpdates tbody').append(response.finalData);
					$('#attendanceUpdates').dataTable({
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
	//for add attendance updates end

	//for approve of attendance start
	
	var attendance_notif_count = $('.attendance-count').val();

	var check_count = 0;

	var counter = 0;
	do {
		counter++
		$(document).on("click","input[name='attendance_request"+counter+"']",function(){
			
			if ($(this).is(':checked')){
				check_count++;
				//alert(check_count)
			}
			else {
				check_count--;
			}
		});
	}while(attendance_notif_count >= counter);
	$('.open-approve-all-modal').on('click',function(){
		if(check_count == 0){
			toast_options(4000);
	        toastr.error("Please check atleast 1 attendance updates.");
		}
		else{
			$('#approveAttendanceModal').modal('toggle');
			
		}
	})

	$('.open-disapprove-all-modal').on('click',function(){
		if(check_count == 0){
			toast_options(4000);
	        toastr.error("Please check atleast 1 attendance updates.");
		}
		else{
			$('#disapproveAttendanceModal').modal('toggle');
			
		}
	})

	var loadingApproveAll = false;
	$('.approve-all-btn').on('click',function(){
		var form = $('.attendance-updates-form');
		var btnName = this;
		if(!loadingApproveAll){
			loadingApproveAll = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');

			$('.approve-attendance-warning').empty();
			$.ajax({
				url:base_url+'attendance_controller/validatePassForApproveDisapproveOfAttendance',
				type:'post',
				dataType:'json',
				data:{
					password:$('.approve-attendance-password').val(),
				},
				success:function(response){
					if(response.status == "success"){
						$.ajax({
							url:base_url+'attendance_controller/approveDisapproveAttendanceUpdatesMultiple',
							type:'post',
							dataType:'json',
							data:form.serialize(),
							success:function(response){
								if(response.status){
									toast_options(4000);
				                    toastr.success("Selected attendance was successfully approved.");
				                    get_leave_request_list()
				                    setTimeout(function(){
				                        window.location.reload();
				                    },1000)
				                    
								}
								else{
									toast_options(4000);
				                	toastr.error("There was a problem, please try again!");
				                	loadingApproveAll = false;
                    				change_button_to_default(btnName, 'Approve');
								}
							},
							error:function(response){
								toast_options(4000);
				            	toastr.error("There was a problem, please try again!");
				            	loadingApproveAll = false;
                    			change_button_to_default(btnName, 'Approve');
							}
						})
					}
					else{
						render_response('.approve-attendance-warning',response.msg, "danger")
						loadingApproveAll = false;
                    	change_button_to_default(btnName, 'Approve');
					}
				},
				error:function(response){
					toast_options(4000);
	                toastr.error("There was a problem, please try again!");
	                loadingApproveAll = false;
                    change_button_to_default(btnName, 'Approve');
				}
			})
		}
		
		
	})

	var loadingDisapproveAll = false;
	$('.disapprove-all-btn').on('click',function(){
		var form = $('.attendance-updates-form');
		var btnName = this;
		if(!loadingDisapproveAll){
			loadingDisapproveAll = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');

			$('.disapprove-attendance-warning').empty();
			$.ajax({
				url:base_url+'attendance_controller/validatePassForApproveDisapproveOfAttendance',
				type:'post',
				dataType:'json',
				data:{
					password:$('.disapprove-attendance-password').val(),
				},
				success:function(response){
					if(response.status == "success"){
						$.ajax({
							url:base_url+'attendance_controller/disapproveAttendanceUpdatesMultiple',
							type:'post',
							dataType:'json',
							data:form.serialize(),
							success:function(response){
								if(response.status){
									toast_options(4000);
				                    toastr.success("Selected attendance was successfully disapproved.");
				                    get_leave_request_list()
				                    setTimeout(function(){
				                        window.location.reload();
				                    },1000)
				                    
								}
								else{
									toast_options(4000);
				                	toastr.error("There was a problem, please try again!");
				                	loadingDisapproveAll = false;
                    				change_button_to_default(btnName, 'Approve');
								}
							},
							error:function(response){
								toast_options(4000);
				            	toastr.error("There was a problem, please try again!");
				            	loadingDisapproveAll = false;
                    			change_button_to_default(btnName, 'Approve');
							}
						})
					}
					else{
						render_response('.disapprove-attendance-warning',response.msg, "danger")
						loadingDisapproveAll = false;
                    	change_button_to_default(btnName, 'Disapprove');
					}
				},
				error:function(response){
					toast_options(4000);
	                toastr.error("There was a problem, please try again!");
	                loadingDisapproveAll = false;
                    change_button_to_default(btnName, 'Disapprove');
				}
			})
		}
		
		
	})

	var approveSingleAttendanceId = null;
	$(document).on('click','.approve-single-attendance',function(e){
		approveSingleAttendanceId = e.target.id;
	})

	var loaingApproveSingle = false;
	$('.single-approve-all-btn').on('click',function(){
		var btnName = this;
		if(!loaingApproveSingle){
			loaingApproveSingle = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');

			$('.single-approve-attendance-warning').empty();
			$.ajax({
				url:base_url+'attendance_controller/approveSingleAttendance',
				type:'post',
				dataType:'json',
				data:{
					password:$('.single-approve-attendance-password').val(),
					id:approveSingleAttendanceId,
				},
				success:function(response){
					if(response.status == "success"){
						toast_options(4000);
	                    toastr.success("Selected attendance was successfully approved.");
	                    get_leave_request_list()
	                    setTimeout(function(){
	                        window.location.reload();
	                    },1000)
					}
					else{
						render_response('.single-approve-attendance-warning',response.msg, "danger")
						loaingApproveSingle = false;
                    	change_button_to_default(btnName, 'Approve');
					}
				},
				error:function(response){
					toast_options(4000);
	                toastr.error("There was a problem, please try again!");
	                loaingApproveSingle = false;
                    change_button_to_default(btnName, 'Approve');
				}
			})
		}
	})


	var disapproveSingleAttendanceId = null;
	$(document).on('click','.disapprove-single-attendance',function(e){
		disapproveSingleAttendanceId = e.target.id;
	})

	var loaingDisapproveSingle = false;
	$('.single-disapprove-all-btn').on('click',function(){
		var btnName = this;
		if(!loaingDisapproveSingle){
			loaingDisapproveSingle = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');

			$('.single-disapprove-attendance-warning').empty();
			$.ajax({
				url:base_url+'attendance_controller/disapproveSingleAttendance',
				type:'post',
				dataType:'json',
				data:{
					password:$('.single-disapprove-attendance-password').val(),
					id:disapproveSingleAttendanceId,
				},
				success:function(response){
					if(response.status == "success"){
						toast_options(4000);
	                    toastr.success("Selected attendance was successfully disapproved.");
	                    get_leave_request_list()
	                    setTimeout(function(){
	                        window.location.reload();
	                    },1000)
					}
					else{
						render_response('.single-disapprove-attendance-warning',response.msg, "danger")
						loaingDisapproveSingle = false;
                    	change_button_to_default(btnName, 'Disapprove');
					}
				},
				error:function(response){
					toast_options(4000);
	                toastr.error("There was a problem, please try again!");
	                loaingDisapproveSingle = false;
                    change_button_to_default(btnName, 'Disapprove');
				}
			})
		}
	})
	//for approve of attendance end
})