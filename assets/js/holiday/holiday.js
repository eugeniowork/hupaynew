$(document).ready(function(){
	//for getting holiday  list start
	get_holiday_list();
	function get_holiday_list(){
		$('#holidayList tbody').empty();
		$.ajax({
			url:base_url+'holiday_controller/getHolidayList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#holidayList tbody').append(response.finalData);
					$('#holidayList').dataTable({
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
	//for getting holiday  list end

	//for updating holiday start
	var updateHolidayId = null;
	var originalDateOfHoliday = "";
	$(document).on('click','.open-edit-holiday',function(e){
		updateHolidayId = e.target.id;
		$('.holiday-info').hide();
        $('.update-holiday-btn').hide();
        $('.loading-update-holiday').show();
        $.ajax({
            url:base_url+'holiday_controller/getUpdateHolidayInfo',
            type:'post',
            dataType:'json',
            data:{
                id:updateHolidayId,
            },
            success:function(response){
                if(response.status == "success"){
                    $('.holiday-info').show();
                    $('.update-holiday-btn').show();
                    $('.loading-update-holiday').hide();

                    if(response.finalData.length > 0){
                    	$('.update-holiday-day').empty();
                    	response.finalData.forEach(function(data,key){
                    		$('.update-holiday-day').append(data.dayOptions);
                    		$('.update-holiday-month option[value='+data.month+']').attr('selected','selected');
                    		$('.update-holiday-name').val(data.holiday_name)
                    		$('.update-holiday-type option[value="'+data.holiday_type+'"]').attr('selected','selected');

                    		originalDateOfHoliday = data.month + " "+data.day;
                    	})
                    }
                }
                else{
                    $('.loading-update-holiday').show();
                    $('.loading-update-holiday').empty();
                    $('.loading-update-holiday').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                    updateHolidayIdupdateHolidayId = null;
                }
            },
            error:function(response){
                $('.loading-update-holiday').show();
                $('.loading-update-holiday').empty();
                $('.loading-update-holiday').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                updateHolidayId = null;
            }
        })
	})
	var loadingUpdateHoliday = false;
	$('.update-holiday-btn').on('click',function(e){
		var btnName = this;
		if(!loadingUpdateHoliday){
			loadingUpdateHoliday = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.update-holiday-warning').empty();
            $.ajax({
            	url:base_url+'holiday_controller/updateHoliday',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:updateHolidayId,
            		month:$('.update-holiday-month').val(),
            		day:$('.update-holiday-day').val(),
            		name:$('.update-holiday-name').val(),
            		type:$('.update-holiday-type').val(),
            		originalDateOfHoliday:originalDateOfHoliday,
            		date:$('.update-holiday-month').val()+" "+$('.update-holiday-day').val()
            	},
            	success:function(response){
            		if(response.status == "success"){
            			toast_options(4000);
                        toastr.success("Holiday Information was successfully updated.");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
            		}
            		else{
            			render_response('.update-holiday-warning',response.msg, "danger")
                        loadingUpdateHoliday = false;
                        change_button_to_default(btnName, 'Update');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateHoliday = false;
                    change_button_to_default(btnName, 'Update');
            	}
            })
        }
	})
	//for updating holiday end

	//for delete holiday start
	$(document).on('click','.delete-holiday',function(e){
		var id = e.target.id;
		Swal.fire({
            html: 'Are you sure you want to delete the <strong>'+$('.holiday-date-'+id).text()+' - '+ $('.holiday-name-'+id).text()+ '</strong> ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
			if (result.value) {
				$.ajax({
					url:base_url+'holiday_controller/deleteHoliday',
					type:'post',
					dataType:'json',
					data:{
						id:id,
					},
					success:function(response){
						if(response.status == "success"){
							toast_options(4000);
	                        toastr.success(response.msg)
	                        $('.holiday-tr-'+id).remove()
	                        setTimeout(function(){
	                            window.location.reload();
	                        },1000)
						}
						else{
							toast_options(4000);
                    		toastr.error("There was a problem on deleting the holiday, please try again!");
						}
					},
					error:function(response){
						toast_options(4000);
                    	toastr.error("There was a problem, please try again!");
					}
				})
			}
			                
		})
	})
	//for delete holiday end

	//for add holiday start 
	$('.add-holiday-month').on('change',function(){
		var month = $(this).val()
		$('.add-holiday-day').empty();
		$.ajax({
			url:base_url+'holiday_controller/getDayInMonth',
			type:'post',
			dataType:'json',
			data:{
				month:month
			},
			success:function(response){
				if(response.status == "success"){
					$('.add-holiday-day').append(response.finalData);
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

	var loadingAddHoliday = false;
	$('.add-holiday-btn').on('click',function(){
		var btnName = this;
        if(!loadingAddHoliday){
            loadingAddHoliday = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.update-holiday-warning').empty();
            $.ajax({
            	url:base_url+'holiday_controller/addHoliday',
            	type:'post',
            	dataType:'json',
            	data:{
            		month:$('.add-holiday-month').val(),
            		day:$('.add-holiday-day').val(),
            		name:$('.add-holiday-name').val(),
            		type:$('.add-holiday-type').val(),
            		date:$('.add-holiday-month').val()+' '+$('.add-holiday-day').val()
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
            			render_response('.update-holiday-warning',response.msg, "danger")
                        loadingAddHoliday = false;
                        change_button_to_default(btnName, 'Submit');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAddHoliday = false;
                    change_button_to_default(btnName, 'Submit');
            	}


            })
        }
	})
	//for add holiday end

})