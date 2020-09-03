$(document).ready(function(){


	//for getting logged in user's loan history list
	get_employee_file_loan_list();
	function get_employee_file_loan_list(){
		$('#loanListHistory tbody').empty();
		$.ajax({
			url:base_url+'loans_controller/getFileLoanList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#loanListHistory tbody').append(response.finalData);
					$('#loanListHistory').dataTable({
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

	//for update start
	var updateFileLoanId = null;
	$(document).on('click','.update-file-loan-btn',function(e){
		updateFileLoanId = e.target.id;
		$('.update-file-loan-info').hide();
        $('.update-file-loan-btn').hide();
        $('.loading-file-loan').show();
        $.ajax({
        	url:base_url+'loans_controller/getFileLoanInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:updateFileLoanId
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.update-file-loan-info').show();
			        $('.update-file-loan-btn').show();
			        $('.loading-file-loan').hide();
			        if(response.finalData.length > 0){
			        	response.finalData.forEach(function(data,key){
			        		$('.file-loan-amount').val(data.amount);
			        		$('.file-loan-purpose').val(data.purpose)
			        		$('.file-loan-type option[value='+data.type+']').attr('selected','selected');
			        		if(data.type != 3){
			        			$('.program-section').hide();
			        		}
			        		else{
			        			$('.program-section').show();
			        			if(data.program != ""){
			        				$('.file-loan-program option[value='+data.program+']').attr('selected','selected');
			        			}
			        			
			        		}
			        		
			        	});
			        }
        		}
        		else{
        			$('.loading-file-loan').show();
	           	 	$('.loading-file-loan').empty();
	            	$('.loading-file-loan').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
	            	updateFileLoanId = null
        		}
        		
        	},
        	error:function(response){
        		$('.loading-file-loan').show();
           	 	$('.loading-file-loan').empty();
            	$('.loading-file-loan').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
            	updateFileLoanId = null
        	}
        })
	})
	$('.file-loan-type').on('change',function(){
		if($(this).val() == 3){
			$('.program-section').show();
		}
		else{
			$('.program-section').hide()
		}
	})
	var loadingUpdateFileLoan = false;
	$('.update-file-loan-data-btn').on('click',function(){
		var btnName = this;
		if(!loadingUpdateFileLoan){

			loadingUpdateFileLoan = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.file-loan-warning').empty();

            $.ajax({
            	url:base_url+'loans_controller/updateFileLoanInfo',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:updateFileLoanId,
            		amount:$('.file-loan-amount').val(),
            		type:$('.file-loan-type').val(),
            		program:$('.file-loan-program').val(),
            		purpose:$('.file-loan-purpose').val(),
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
            			render_response('.file-loan-warning',response.msg, "danger")
                        loadingUpdateFileLoan = false;
                        change_button_to_default(btnName, 'Update');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateFileLoan = false;
                    change_button_to_default(btnName, 'Update');
            	}
            })
		}
	})
	//for update end

	// for cancel start

	$(document).on('click','.cancel-file-loan-btn',function(e){
		var id = e.target.id;
		Swal.fire({
            html: 'Are you sure you want to cancel your file salary loan with Reference No. <strong>'+$('.ref-no-'+id).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
        	if (result.value) {
        		$.ajax({
        			url:base_url+'loans_controller/cancelFileLoan',
        			type:'post',
        			dataType:'json',
        			data:{
        				id:id,
        			},
        			success:function(response){
        				if(response.status == "success"){
        					toast_options(4000);
	                        toastr.success(response.msg);
	                        setTimeout(function(){
	                            window.location.reload();
	                        },1000)
	                        $('.file-loan-'+id).remove();
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
		});
	})
	//for cancel end

	//for add file loan data start
	$('.add-file-loan-type').on('change',function(){
		if($(this).val() == 3){
			$('.add-program-section').show();
		}
		else{
			$('.add-program-section').hide();
		}
	})

	var loadingAddFileLoan = false;
	$('.add-file-loan-data-btn').on('click',function(){
		var btnName = this;
		if(!loadingAddFileLoan){
			loadingAddFileLoan = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.add-file-loan-warning').empty();
            $.ajax({
            	url:base_url+'loans_controller/addNewFileLoan',
            	type:'post',
            	dataType:'json',
            	data:{
            		amount:$('.add-file-loan-amount').val(),
            		type:$('.add-file-loan-type').val(),
            		program:$('.add-file-loan-program').val(),
            		purpose:$('.add-file-loan-purpose').val(),
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
            			render_response('.add-file-loan-warning',response.msg, "danger")
                        loadingAddFileLoan = false;
                        change_button_to_default(btnName, 'Update');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAddFileLoan = false;
                    change_button_to_default(btnName, 'Submit');
            	}
            })
        }
	})

	//for add file loan data end
})