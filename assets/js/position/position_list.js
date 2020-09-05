$(document).ready(function(){
	//for getting position list start
	get_position_list();
	function get_position_list(){
		$('#positionList tbody').empty();
		$.ajax({
			url:base_url+'position_controller/getPositionList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#positionList tbody').append(response.finalData);
					$('#positionList').dataTable({
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