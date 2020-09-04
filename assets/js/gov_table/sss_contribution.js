$(document).ready(function(){
	//for getting sss contribution list start
	get_sss_contribution();
	function get_sss_contribution(){
		$('#sssContributionList tbody').empty();
		$.ajax({
			url:base_url+'sss_controller/getSssContribution',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#sssContributionList tbody').append(response.finalData);
					$('#sssContributionList').dataTable({
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
	//for getting sss contribution list end
})