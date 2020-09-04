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

})