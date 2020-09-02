$(document).ready(function(){
	get_employee_with_existing_pagibig();
	function get_employee_with_existing_pagibig(){
		$('#employeeWithExistingPagibig tbody').empty();
		$.ajax({
			url:base_url+'loans_controller/getEmployeeWithExistingPagibig',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#employeeWithExistingPagibig tbody').append(response.finalData);
					$('#employeeWithExistingPagibig').dataTable({
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
	var editPagibigLoanId = null;
	$(document).on('click', '.edit-pagibig-btn',function(e){
		editPagibigLoanId = e.target.id;
		$('.pagibig-info').hide();
        $('.update-pagibig-btn').hide();
        $('.loading-pagibig').show();
        $.ajax({
        	url:base_url+'loans_controller/getPagibigInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:editPagibigLoanId
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.pagibig-info').show();
			        $('.update-pagibig-btn').show();
			        $('.loading-pagibig').hide();
			        if(response.finalData.length >0){
			        	response.finalData.forEach(function(data,key){
			        		$('.employee-name').val(data.name);
			        		$('.date-from').val(data.date_from)
			        		$('.date-to').val(data.date_to)
			        		$('.amount-loan').val(data.amount_loan)
			        		$('.deduction').val(data.deduction)
			        		$('.remaining-balance').val(data.remaining_balance)

			        		$('.date-from').datepicker("option","defaultDate", data.date_from);
			        		$('.date-to').datepicker("option","defaultDate", data.date_to);
			        	})
			        }
        		}
        		else{
        			$('.loading-pagibig').show();
	           	 	$('.loading-pagibig').empty();
	            	$('.loading-pagibig').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
	            	editPagibigLoanId = null
        		}
        	},
        	error:function(response){
        		$('.loading-pagibig').show();
           	 	$('.loading-pagibig').empty();
            	$('.loading-pagibig').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
            	editPagibigLoanId = null
        	}
        })
	})

	var loadingUpdatePagibig = false;
	$('.update-pagibig-btn').on('click',function(){
		var btnName = this;
		if(!loadingUpdatePagibig){
			loadingUpdatePagibig = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.pagibig-warning').empty();
            $.ajax({
            	url:base_url+'loans_controller/updatePagibigInfo',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:editPagibigLoanId,
            		dateFrom:$('.date-from').val(),
					dateTo:$('.date-to').val(),
					amountLoan:$('.amount-loan').val(),
					deduction:$('.deduction').val(),
					remainingBalance:$('.remaining-balance').val(),
					name:$('.employee-name').val(),
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
            			render_response('.pagibig-warning',response.msg, "danger")
                        loadingUpdatePagibig = false;
                        change_button_to_default(btnName, 'Update');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdatePagibig = false;
                    change_button_to_default(btnName, 'Update');
            	}
            })
		}

	})
	//for update end


	//for adjust start
	var adjustPagibigLoanId = null;
	$(document).on('click','.adjust-pagibig-btn',function(e){
		adjustPagibigLoanId = e.target.id;
		$('.adjust-pagibig-info').hide();
        $('.adjust-pagibig-btn').hide();
        $('.adjust-loading-pagibig').show();
        $.ajax({
        	url:base_url+'loans_controller/getAdjustPagibigInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:adjustPagibigLoanId
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.adjust-pagibig-info').show();
			        $('.adjust-pagibig-btn').show();
			        $('.adjust-loading-pagibig').hide();
			        $('.adjust-employee-name').val(response.name);
					$('.adjust-outstanding-balance').val(response.remainingBalance)

					$('.adjust-date-payment').datepicker("option","defaultDate", new Date());
        		}
        		else{
        			$('.adjust-loading-pagibig').show();
	           	 	$('.adjust-loading-pagibig').empty();
	            	$('.adjust-loading-pagibig').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
	            	adjustPagibigLoanId = null
        		}
        	},
        	error:function(response){
        		$('.adjust-loading-pagibig').show();
           	 	$('.adjust-loading-pagibig').empty();
            	$('.adjust-loading-pagibig').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
            	adjustPagibigLoanId = null
        	}
        })
	})

	$('.adjust-cash-payment').on('change',function(e){
		var currentBalance = $('.adjust-outstanding-balance').val();
		var cashPayment = $(this).val();
		if(cashPayment != ""){
			var newBalance = parseFloat(currentBalance) - parseFloat(cashPayment);
            // for 2 decimal places
            newBalance = newBalance.toString().split('e');
            newBalance = Math.round(+(newBalance[0] + 'e' + (newBalance[1] ? (+newBalance[1] + 2) : 2)));

            newBalance = newBalance.toString().split('e');
            newBalance=  (+(newBalance[0] + 'e' + (newBalance[1] ? (+newBalance[1] - 2) : -2))).toFixed(2);
            $(".adjust-new-outstanding-balance").val(newBalance);
		}
		else{
			$(".adjust-new-outstanding-balance").val();
		}
	})
	var loadingAdjustPagibig = false;
	$('.adjust-pagibig-btn').on('click',function(){
		var btnName = this;
		if(!loadingAdjustPagibig){
			loadingAdjustPagibig = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.adjust-pagibig-warning').empty();
            $.ajax({
            	url:base_url+'loans_controller/adjustPagibigData',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:adjustPagibigLoanId,
            		adjustDatePayment:$('.adjust-date-payment').val(),
            		adjustCashPayment:$('.adjust-cash-payment').val(),
            		adjustOutstandingBalance:$('.adjust-outstanding-balance').val(),
            		adjustNewOutstandingBalance:$('.adjust-new-outstanding-balance').val(),
            		adjustRemarks:$('.adjust-remarks').val(),
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
            			render_response('.adjust-pagibig-warning',response.msg, "danger")
                        loadingAdjustPagibig = false;
                        change_button_to_default(btnName, 'Adjust');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAdjustPagibig = false;
                    change_button_to_default(btnName, 'Adjust');
            	}
            })
		}
	})
	//for adjust end

	//for delete start
	var deletePagibigId = null;
	$(document).on('click','.delete-pagibig',function(e){
		
		Swal.fire({
            html: 'Are you sure you want to delete the <strong>Pag-ibig Loan</strong> of <strong>'+$('.name-'+deletePagibigId).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
            	deletePagibigId = e.target.id;
            	$.ajax({
            		url:base_url+'loans_controller/deletePagibigLoan',
            		type:'post',
            		dataType:'json',
            		data:{
            			id:deletePagibigId
            		},
            		success:function(response){
            			if(response.status == "success"){
            				toast_options(4000);
	                        toastr.success(response.msg);
	                        setTimeout(function(){
	                            window.location.reload();
	                        },1000)
	                        $('.pagibig-tr-'+deletePagibigId).remove();
            			}
            			else{
            				toast_options(4000);
                    		toastr.error("There was a problem removing the pagibig loan, please try again!");
            			}
            		},
            		error:function(response){
            			toast_options(4000);
                    	toastr.error("There was a problem, please try again!");
            		}

            	})
            }
            else{
            	deletePagibigId = null;
            }
        });
	})


	//for delete end

	//for getting logged in pagibig history

	get_pagibig_loan_history()

	function get_pagibig_loan_history(){
		$('#pagibigHistoryList tbody').empty();
		$.ajax({
			url:base_url+'loans_controller/getPagibigHistoryList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#pagibigHistoryList tbody').append(response.finalData);
					$('#pagibigHistoryList').dataTable({
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

})