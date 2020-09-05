$(document).ready(function(){
	//for getting position list start
	get_biometics_list();
	function get_biometics_list(){
		$('#biometrics tbody').empty();
		$.ajax({
			url:base_url+'biometrics_controller/getBiometrics',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#biometrics tbody').append(response.finalData);
					$('#biometrics').dataTable({
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