$(document).ready(function(){
	$('.datepicker').datepicker();

	$('.search-attendance-btn').on('click',function(){

		$('.loading-search-attendance').show();
		$('.search-attendance').hide();
		
		$('#searchAttendance').dataTable().fnDestroy();
		var dateFrom = $(".date-from").val();
        var dateTo = $(".date-to").val();
        var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/; 

        if (dateFrom == "" ||dateTo == "") {
        	render_response('.search-attendance-warning',"Please provide Date To and Date From.", "danger")
        	$('.loading-search-attendance').hide();
        }
		else if (!dateFrom.match(dateformat) || !dateTo.match(dateformat)) {
			render_response('.search-attendance-warning',"Invalid Date From or Date To.", "danger")
			$('.loading-search-attendance').hide();
		}
		else{
			$.ajax({
				url:base_url+'attendance_controller/searchAllAttendance',
				type:'post',
				dataType:'json',
				data:{
					dateFrom:dateFrom,
					dateTo:dateTo,
				},
				success:function(response){
					if(response.status == "success"){
						$('#searchAttendance tbody').empty();
						$('#searchAttendance tbody').append(response.finalData)
						
					}
					else{
						render_response('.search-attendance-warning',response.msg, "danger")
					}
					$('.loading-search-attendance').hide();
					$('.search-attendance').show();
					$('#searchAttendance').dataTable({
						ordering:false
					});
				},
				error:function(response){
					toast_options(4000);
                    toastr.error("There was a problem, please try again!");
				}
			})
		}
	})
})