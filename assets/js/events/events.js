$(document).ready(function(){
	//for getting events list start
	get_events_list();
	function get_events_list(){
		$('#eventsList tbody').empty();
		$.ajax({
			url:base_url+'events_controller/getEventsList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#eventsList tbody').append(response.finalData);
					$('#eventsList').dataTable({
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
	//for getting events list end
})