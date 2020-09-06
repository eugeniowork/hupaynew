$(document).ready(function(){
	//add attendance start
	get_leave_request_list();
	function get_leave_request_list(){
		$('#addAttendance tbody').empty();
		$.ajax({
			url:base_url+'employee_controller/getEmployeeWithoutBioId',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#addAttendance tbody').append(response.finalData);
					$('#addAttendance').dataTable({
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
	//for add attendance end


	//for get attendance for cutoff period start
	get_generate_attendance_for_cut_off();
	function get_generate_attendance_for_cut_off(){
		$('.generated-attendance-div').empty();

		$('.generate-attendance-info').hide();
        $('.submit-generate-attendance-btn').hide();
        $('.loading-generate-attendance').show();
		$.ajax({
			url:base_url+'attendance_controller/generateAttendance',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('.generated-attendance-div').append(response.finalData);
					// $('#addAttendance').dataTable({
					// 	ordering:false
					// });
					$('.generate-attendance-info').show();
        			$('.submit-generate-attendance-btn').show();
        			$('.loading-generate-attendance').hide();
				}
				else{
					toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    $('.loading-generate-attendance').show();
                    $('.loading-generate-attendance').empty();
                    $('.loading-generate-attendance').append('<p class="text-danger" style="text-align:center">There was a problem, please try again</p>');
                    
				}
			},
			error:function(response){
				$('.loading-generate-attendance').show();
                $('.loading-generate-attendance').empty();
                $('.loading-generate-attendance').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                
			}
		})
	}

	var addAttendanceEmpId = null;
	$(document).on('click','.add-attendance-btn',function(e){
		addAttendanceEmpId = e.target.id;
		var name = $('.employee-name-'+addAttendanceEmpId).text();
		$('.add-attendance-employee-name-value').text(name)
		$('.for-id').val(addAttendanceEmpId)
	})
	var loadingAddAttendance = false;
	$('.submit-generate-attendance-btn').on('click',function(e){
		e.preventDefault();
		var btnName = this;
		var form = $('.add-attendance-form');
		if(!loadingAddAttendance){
			loadingAddAttendance = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');

            $.ajax({
				url:base_url+'attendance_controller/addAttendanceForCutOff',
				type:'post',
				dataType:'json',
				data:form.serialize(),
				success:function(response){
					
                    if(response.status == "success"){
                    	toast_options(4000);
	                    toastr.success(response.msg);
	                    setTimeout(function(){
	                        window.location.reload();
	                    },1000)
                    }
                    else{
                    	//render_response('.add-employee-warning',response.msg, "danger")
						loadingAddAttendance = false;
                    	change_button_to_default(btnName, 'Submit');
                    }
				},
				error:function(response){
					toast_options(4000);
	                toastr.error("There was a problem, please try again!");
	                loadingAddAttendance = false;
                    change_button_to_default(btnName, 'Submit');
				}

			})
			//$('.add-employee-warning').empty();
		}
		//form.append('id',addAttendanceEmpId);

	})
	//for get attendance for cutoff period end
})