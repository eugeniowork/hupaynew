$(document).ready(function(){
	//for getting holiday  list start
	get_holiday_list();
	function get_holiday_list(){
		$('#holidayList tbody').empty();
		$.ajax({
			url:base_url+'holiday_controller/getHolidayList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#holidayList tbody').append(response.finalData);
					$('#holidayList').dataTable({
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
	//for getting holiday  list end
})