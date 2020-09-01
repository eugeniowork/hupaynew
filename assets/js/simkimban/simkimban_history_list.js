$(document).ready(function(){

	get_employee_simkimban_history_list();
	function get_employee_simkimban_history_list(){
		$('.employeeSimkimbanListHistory tbody').empty();
		$.ajax({
			url:base_url+'simkimban_controller/getEmployeeSimkimbanHistoryList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#employeeSimkimbanListHistory tbody').append(response.finalData);
					$('#employeeSimkimbanListHistory').dataTable({
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



	get_simkimban_history_list();
	function get_simkimban_history_list(){
		$('.simkimbanListHistory tbody').empty();
		$.ajax({
			url:base_url+'simkimban_controller/getSimkimbanHistoryList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#simkimbanListHistory tbody').append(response.finalData);
					$('#simkimbanListHistory').dataTable({
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
})