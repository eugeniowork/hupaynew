$(document).ready(function(){
	//for getting bir contribution list start
	get_bir_contribution();
	function get_bir_contribution(){
		$('#birContributionList tbody').empty();
		$.ajax({
			url:base_url+'bir_controller/getBirContribution',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#birContributionList tbody').append(response.finalData);
					$('#birContributionList').dataTable({
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
	//for getting bir contribution list end
})