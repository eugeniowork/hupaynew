$(document).ready(function(){
	//add attendance updates start
	get_leave_request_list();
	function get_leave_request_list(){
		$('#attendanceUpdates tbody').empty();
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
})