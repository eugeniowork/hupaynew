$(document).ready(function(){
	//for getting pagibig contribution list start
	get_pagibig_contribution();
	function get_pagibig_contribution(){
		$('#pagibigContributionList tbody').empty();
		$.ajax({
			url:base_url+'pagibig_controller/getPagibigContribution',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#pagibigContributionList tbody').append(response.finalData);
					$('#pagibigContributionList').dataTable({
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