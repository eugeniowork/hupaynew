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

	//for getting validation list start
	get_validation_list();
	function get_validation_list(){
		$('.leave-validation').empty();
		$.ajax({
			url:base_url+'leave_controller/getValidationList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('.leave-validation').append('<option selected disabled>Select Validation</option>')
					$('.leave-validation').append(response.finalData);
					// $('#leaveMaintenance').dataTable({
					// 	ordering:false
					// });
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

	//for getting validation list end

	//for validation of add leave maintenance start
	var obj = $(".no-of-days-to-file");
	disabledNoOfDays(obj);
	$(".leave-validation").on("change",function(){


		var validation = $(this).val();
		var values = obj.val();
		disabledNoOfDays(obj);

		if (validation == 1 || validation == 2){

			enabledNoOfDays(obj,values);
		}

	});
	function disabledNoOfDays(obj){
    	obj.attr("disabled","disabled");
    	obj.val("");
    }

    function enabledNoOfDays(obj,values){
    	obj.val(values);
    	obj.removeAttr("disabled");
    	
    }
	//for validation of  add leave maintenance end

	//for save leave maintenance start
	var loadingAddLeave = false;
	$('.submit-new-leave-maintenance-btn').on('click',function(){
		var btnName = this;
		if(!loadingAddLeave){
            loadingAddLeave = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.add-leave-maintenance-warning').empty();
            var is_convetable_to_cash = 0;
			if ($(".is-convertable-to-cash").is(":checked")){
				is_convetable_to_cash = 1;
			}
            $.ajax({
            	url:base_url+'leave_controller/addNewLeaveMaintenance',
            	type:'post',
            	dataType:'json',
            	data:{
            		leaveName:$('.leave-name').val(),
            		leaveValidation:$('.leave-validation').val(),
            		noOfDays:$('.no-of-days-to-file').val(),
            		leaveCount:$('.leave-count').val(),
            		isConvertable:is_convetable_to_cash,
            	},
            	success:function(response){
            		if(response.status == "success"){
            			toast_options(4000);
                        toastr.success(response.msg);
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
            		}
            		else{
            			render_response('.add-leave-maintenance-warning',response.msg, "danger")
                        loadingAddLeave = false;
                        change_button_to_default(btnName, 'Submit');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAddLeave = false;
                    change_button_to_default(btnName, 'Submit');
            	}
            })
        }
	})
	//for save leave maintenance end
})