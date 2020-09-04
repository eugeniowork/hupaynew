$(document).ready(function(){
	//for over time request list start
	get_overtime_request_list();
	function get_overtime_request_list(){
		$('#overtimeRequestList tbody').empty();
		$.ajax({
			url:base_url+'attendance_controller/getOtRequestList',
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

	//for all overtime approve list start
	get_all_overtime_request_list();
	function get_all_overtime_request_list(){
		$('#allOvertimeApproveList tbody').empty();
		$.ajax({
			url:base_url+'attendance_controller/getAllOtApproveList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#allOvertimeApproveList tbody').append(response.finalData);
					$('#allOvertimeApproveList').dataTable({
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
	//for all overtime approve list end
})