$(document).ready(function(){
	//for getting pagibig contribution list start
	get_philheatlth_contribution();
	function get_philheatlth_contribution(){
		$('#philhealthContributionList tbody').empty();
		$.ajax({
			url:base_url+'philhealth_controller/getPhilhealthContribution',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#philhealthContributionList tbody').append(response.finalData);
					$('#philhealthContributionList').dataTable({
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
	//for getting pagibig contribution list end
})