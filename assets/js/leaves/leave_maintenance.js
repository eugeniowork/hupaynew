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
		$('.update-leave-validation').empty();
		$.ajax({
			url:base_url+'leave_controller/getValidationList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('.leave-validation').append('<option selected disabled>Select Validation</option>')
					$('.leave-validation').append(response.finalData);
					$('.update-leave-validation').append('<option selected disabled>Select Validation</option>')
					$('.update-leave-validation').append(response.finalData);
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

	//for activate/inactive of leave maintenance
	$(document).on('click','.change-status-btn',function(e){
		var id = e.target.id;
		$.ajax({
			url:base_url+'leave_controller/getLeaveMaintenanceInfo',
			type:'post',
			dataType:'json',
			data:{
				id:id,
			},
			success:function(response){
				if(response.status == "success"){
					Swal.fire({
		                html: 'Are you sure you want to make the Leave Type <strong>'+response.name+'</strong> in '+response.status_leave+' status?',
		                icon: 'warning',
		                showCancelButton: true,
		                confirmButtonColor: '#3085d6',
		                cancelButtonColor: '#d33',
		                confirmButtonText: 'Yes'
		            }).then((result) => {
						if (result.value) {
							$.ajax({
								url:base_url+'leave_controller/changeLeaveStatus',
								type:'post',
								dataType:'json',
								data:{
									id:id,
								},
								success:function(responseData){
									if(responseData.status == "success"){
										toast_options(4000);
				                        toastr.success(responseData.msg);
				                        setTimeout(function(){
				                            window.location.reload();
				                        },1000)
									}
									else{
										toast_options(4000);
                    				toastr.error("There was a problem updating leave type, please try again!");
									}
								},
								error:function(responseData){
									toast_options(4000);
                					toastr.error("There was a problem, please try again!");
								}
							})
						}
					})
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
	})
	//for activate/inactive of leave maintenance

	//for delete of leave type start
	$(document).on('click','.delete-leave-type-btn',function(e){
		var id = e.target.id;
		$.ajax({
			url:base_url+'leave_controller/getLeaveMaintenanceInfo',
			type:'post',
			dataType:'json',
			data:{
				id:id
			},
			success:function(response){
				if(response.status == "success"){
					if(response.isDelete){
						Swal.fire({
			                html: 'Are you sure you want to delete the <strong>'+response.name+'</strong> Leave Type?',
			                icon: 'warning',
			                showCancelButton: true,
			                confirmButtonColor: '#3085d6',
			                cancelButtonColor: '#d33',
			                confirmButtonText: 'Yes'
			            }).then((result) => {
							if (result.value) {
								$.ajax({
									url:base_url+'leave_controller/deleteLeaveType',
									type:'post',
									dataType:'json',
									data:{
										id:id,
									},
									success:function(responseData){
										if(responseData.status == "success"){
											toast_options(4000);
					                        toastr.success(responseData.msg);
					                        setTimeout(function(){
					                            window.location.reload();
					                        },1000)
					                        $('.leave-type-'+id).remove();
										}
										else{
											toast_options(4000);
	                    					toastr.error("There was a problem deleting leave type, please try again!");
										}
									},
									error:function(responseData){
										toast_options(4000);
	                					toastr.error("There was a problem, please try again!");
									}
								})
							}
						})
					}
					else{
						toast_options(4000);
                    	toastr.error("Leave Type <strong>"+response.name+"</strong> cannot be deleted!");
					}
					console.log(response.isDelete)
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
	})
	//for delete of leave type end

	//for edit leave start
	var obj = $(".update-no-of-days-to-file");
	disabledNoOfDays(obj);
	$(".update-leave-validation").on("change",function(){


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

	var editLeaveId = null;
	var originalLeaveName = "";
	$(document).on('click', '.edit-leave-btn',function(e){
		editLeaveId = e.target.id
		$('.update-leave-info').hide();
        $('.submit-update-leave-btn').hide();
        $('.loading-update-leave').show();
		$.ajax({
			url:base_url+'leave_controller/getLeaveTypeInfo',
			type:'post',
			dataType:'json',
			data:{
				id:editLeaveId
			},
			success:function(response){
				if(response.status == "success"){
					$('.update-leave-info').show();
			        $('.submit-update-leave-btn').show();
			        $('.loading-update-leave').hide();
			        $('.update-leave-name').val(response.name);
			        $('.update-leave-validation option[value='+response.validation+']').attr('selected','selected');
			        $('.update-no-of-days-to-file').val(response.noOfDays)
			        $('.update-leave-count').val(response.leaveCount)
			        originalLeaveName = response.name
			        if(response.isConvertable == 1){
			        	$('.update-is-convertable-to-cash').prop('checked',true)
			        }
			        else{
			        	$('.update-is-convertable-to-cash').prop('checked',false)
			        }
			        var values = obj.val();
			        if (response.validation == 1 || response.validation == 2){
			        	
						enabledNoOfDays(obj,values);
					}
					else{
						disabledNoOfDays(obj);
					}
				}
				else{
					$('.loading-update-leave').show();
                    $('.loading-update-leave').empty();
                    $('.loading-update-leave').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                    editLeaveId = null;
				}
			},
			error:function(response){
				$('.loading-update-leave').show();
                $('.loading-update-leave').empty();
                $('.loading-update-leave').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                editLeaveId = null;
			}

		})
	})
	var loadingUpdateLeave = false
	$('.submit-update-leave-btn').on('click',function(){
		var btnName = this;
		if(!loadingUpdateLeave){
            loadingUpdateLeave = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.update-leave-warning').empty();
            var is_convetable_to_cash = 0;
			if ($(".update-is-convertable-to-cash").is(":checked")){
				is_convetable_to_cash = 1;
			}
            $.ajax({
            	url:base_url+'leave_controller/updateLeaveMaintenance',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:editLeaveId,
            		leaveName:$('.update-leave-name').val(),
            		leaveValidation:$('.update-leave-validation').val(),
            		noOfDays:$('.update-no-of-days-to-file').val(),
            		leaveCount:$('.update-leave-count').val(),
            		isConvertable:is_convetable_to_cash,
            		originalLeaveName:originalLeaveName,
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
            			render_response('.update-leave-warning',response.msg, "danger")
                        loadingUpdateLeave = false;
                        change_button_to_default(btnName, 'Submit');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateLeave = false;
                    change_button_to_default(btnName, 'Submit');
            	}
            })
        }
	})
	//for edit leave end
})