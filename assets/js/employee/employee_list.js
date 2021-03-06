$(document).ready(function(){
	//for getting position list start
	get_employee_list();
	function get_employee_list(){
		$('#employeeList tbody').empty();
		$.ajax({
			url:base_url+'employee_controller/getEmployeeList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#employeeList tbody').append(response.finalData);
					$('#employeeList').dataTable({
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
	//for getting position list end
})