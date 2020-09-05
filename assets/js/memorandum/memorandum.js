$(document).ready(function(){
	//for getting memorandum list start
	get_sss_contribution();
	function get_sss_contribution(){
		$('#listOfMemorandum tbody').empty();
		$.ajax({
			url:base_url+'memorandum_controller/getListOfMemorandum',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#listOfMemorandum tbody').append(response.finalData);
					$('#listOfMemorandum').dataTable({
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
	//for getting memorandum list end
})