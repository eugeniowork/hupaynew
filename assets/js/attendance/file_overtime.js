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
})