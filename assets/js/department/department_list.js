$(document).ready(function(){
	//for getting position list start
	get_department_list();
	function get_department_list(){
		$('#departmentList tbody').empty();
		$.ajax({
			url:base_url+'department_controller/getDepartmentList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#departmentList tbody').append(response.finalData);
					$('#departmentList').dataTable({
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