$(document).ready(function(){
	//for over time request list start
	get_overtime_request_list();
	function get_overtime_request_list(){
		$('#overtimeRequestList tbody').empty();
		$.ajax({
			url:base_url+'attendance_controller/getOvertimeList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#overtimeRequestList tbody').append(response.finalData);
					$('#overtimeRequestList').dataTable({
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
	//for over time request list end

	//for approve of overime start
	var approvalOtId = null;
	$(document).on('click','.open-approval-ot',function(e){
		approvalOtId = e.target.id;
	})

	var loadingApprovalOt = false;
	$('.approve-ot-btn').on('click',function(){
		var btnName = this;
		if(!loadingApprovalOt){
			loadingApprovalOt = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');

			$('.approve-ot-warning').empty();
			$.ajax({
				url:base_url+'attendance_controller/otApproval',
				type:'post',
				dataType:'json',
				data:{
					id:approvalOtId,
					password:$('.approve-ot-password').val(),
					type:'Approve',
				},
				success:function(response){
					if(response.status == "success"){
						toast_options(4000);
	                    toastr.success("Selected overtime was successfully approved.");
	                    setTimeout(function(){
	                        window.location.reload();
	                    },1000)
					}
					else{
						render_response('.approve-ot-warning',response.msg, "danger")
						loadingApprovalOt = false;
                    	change_button_to_default(btnName, 'Approve');
					}
				},
				error:function(response){
					toast_options(4000);
	                toastr.error("There was a problem, please try again!");
	                loadingApprovalOt = false;
                    change_button_to_default(btnName, 'Approve');
				}
			})
		}
	})
	//for approve of overime END

	//for disapprove of overtime start
	var disapprovalOtId = null;
	$(document).on('click','.open-disapproval-ot',function(e){
		disapprovalOtId = e.target.id;
	})

	var loadingDisapprovalOt = false;
	$('.disapprove-ot-btn').on('click',function(){
		var btnName = this;
		if(!loadingDisapprovalOt){
			loadingDisapprovalOt = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');

			$('.disapprove-ot-warning').empty();
			$.ajax({
				url:base_url+'attendance_controller/otApproval',
				type:'post',
				dataType:'json',
				data:{
					id:disapprovalOtId,
					password:$('.disapprove-ot-password').val(),
					type:'Disapprove',
				},
				success:function(response){
					if(response.status == "success"){
						toast_options(4000);
	                    toastr.success("Selected overtime was successfully disapproved.");
	                    setTimeout(function(){
	                        window.location.reload();
	                    },1000)
					}
					else{
						render_response('.disapprove-ot-warning',response.msg, "danger")
						loadingDisapprovalOt = false;
                    	change_button_to_default(btnName, 'Disapprove');
					}
				},
				error:function(response){
					toast_options(4000);
	                toastr.error("There was a problem, please try again!");
	                loadingDisapprovalOt = false;
                    change_button_to_default(btnName, 'Disapprove');
				}
			})
		}
	})
	//for disapprove of overtime end
})