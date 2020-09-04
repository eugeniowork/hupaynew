$(document).ready(function(){
	//for leave maintenance list start
	$.protip();
	get_leave_request_list();
	function get_leave_request_list(){
		$('#leaveMaintenance tbody').empty();
		$.ajax({
			url:base_url+'leave_controller/getLeaveMaintenance',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#leaveMaintenance tbody').append(response.finalData);
					$('#leaveMaintenance').dataTable({
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

	$('[data-toggle="popover"]').popover();  
	$(document).on('hover','#hover_info',function() {
    	//alert("Hello World!");
        $(this).trigger("click");
    	
    });
	//for leave maintenance list end
})