$(document).ready(function(){
	//add attendance start
	get_leave_request_list();
	function get_leave_request_list(){
		$('#addAttendance tbody').empty();
		$.ajax({
			url:base_url+'employee_controller/getEmployeeWithoutBioId',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#addAttendance tbody').append(response.finalData);
					$('#addAttendance').dataTable({
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
	//for add attendance end
})