$(document).ready(function(){
	get_employee_with_existing_sss();
	function get_employee_with_existing_sss(){
		$('#employeeWithExistingSss tbody').empty();
		$.ajax({
			url:base_url+'loans_controller/getEmployeeWithSssPagibig',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#employeeWithExistingSss tbody').append(response.finalData);
					$('#employeeWithExistingSss').dataTable({
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
	var editSssLoanId = null;
	$(document).on('click', '.edit-sss-btn',function(e){
		editSssLoanId = e.target.id;
		$('.sss-info').hide();
        $('.update-sss-btn').hide();
        $('.loading-sss').show();
        $.ajax({
        	url:base_url+'loans_controller/getSssInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:editSssLoanId
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.sss-info').show();
			        $('.update-sss-btn').show();
			        $('.loading-sss').hide();
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
        			$('.loading-sss').show();
	           	 	$('.loading-sss').empty();
	            	$('.loading-sss').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
	            	editSssLoanId = null
        		}
        	},
        	error:function(response){
        		$('.loading-pagibig').show();
           	 	$('.loading-pagibig').empty();
            	$('.loading-pagibig').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
            	editSssLoanId = null
        	}
        })
	})

	var loadingUpdateSss = false;
	$('.update-sss-btn').on('click',function(){
		var btnName = this;
		if(!loadingUpdateSss){
			loadingUpdateSss = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.sss-warning').empty();
            $.ajax({
            	url:base_url+'loans_controller/updateSssInfo',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:editSssLoanId,
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
            			render_response('.sss-warning',response.msg, "danger")
                        loadingUpdateSss = false;
                        change_button_to_default(btnName, 'Update');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateSss = false;
                    change_button_to_default(btnName, 'Update');
            	}
            })
		}

	})

	//for update end


	//for adjust start
	var adjustSssLoanId = null;
	$(document).on('click','.adjust-sss-btn',function(e){
		adjustSssLoanId = e.target.id;
		$('.adjust-sss-info').hide();
        $('.adjust-sss-btn').hide();
        $('.adjust-loading-sss').show();
        $.ajax({
        	url:base_url+'loans_controller/getAdjustSssInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:adjustSssLoanId
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.adjust-sss-info').show();
			        $('.adjust-sss-btn').show();
			        $('.adjust-loading-sss').hide();
			        $('.adjust-employee-name').val(response.name);
					$('.adjust-outstanding-balance').val(response.remainingBalance)

					$('.adjust-date-payment').datepicker("option","defaultDate", new Date());
        		}
        		else{
        			$('.adjust-loading-sss').show();
	           	 	$('.adjust-loading-sss').empty();
	            	$('.adjust-loading-sss').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
	            	adjustSssLoanId = null
        		}
        	},
        	error:function(response){
        		$('.adjust-loading-sss').show();
           	 	$('.adjust-loading-sss').empty();
            	$('.adjust-loading-sss').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
            	adjustSssLoanId = null
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

	var loadingAdjustSss = false;
	$('.adjust-sss-btn').on('click',function(){
		var btnName = this;
		if(!loadingAdjustSss){
			loadingAdjustSss = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.adjust-sss-warning').empty();
            $.ajax({
            	url:base_url+'loans_controller/adjustSssData',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:adjustSssLoanId,
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
            			render_response('.adjust-sss-warning',response.msg, "danger")
                        loadingAdjustSss = false;
                        change_button_to_default(btnName, 'Adjust');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAdjustSss = false;
                    change_button_to_default(btnName, 'Adjust');
            	}
            })
		}
	})

	//for adjust end

	//for delete start
	var deleteSssId = null;
	$(document).on('click','.delete-sss',function(e){
		deleteSssId = e.target.id;
		console.log(deleteSssId)
		Swal.fire({
            html: 'Are you sure you want to delete the <strong>SSS Loan</strong> of <strong>'+$('.name-'+deleteSssId).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
            	
            	$.ajax({
            		url:base_url+'loans_controller/deleteSssLoan',
            		type:'post',
            		dataType:'json',
            		data:{
            			id:deleteSssId
            		},
            		success:function(response){
            			if(response.status == "success"){
            				toast_options(4000);
	                        toastr.success(response.msg);
	                        setTimeout(function(){
	                            window.location.reload();
	                        },1000)
	                        $('.sss-tr-'+deleteSssId).remove();
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
            	deleteSssId = null;
            }
        });
	})


	//for delete end


	//for getting logged in pagibig history

	get_pagibig_loan_history()

	function get_pagibig_loan_history(){
		$('#sssHistoryList tbody').empty();
		$.ajax({
			url:base_url+'loans_controller/getSssHistoryList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#sssHistoryList tbody').append(response.finalData);
					$('#sssHistoryList').dataTable({
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

	// for adding sss loan start
	$('.add-date-from').datepicker("option","defaultDate", new Date());
	$('.add-date-to').datepicker("option","defaultDate", new Date());
	get_list_of_employee()
	function get_list_of_employee(){
		$('.employeeList tbody').empty();
		$.ajax({
			url:base_url+'employee_controller/getAllEmployee',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#employeeList tbody').append(response.finalData)
					$('#employeeList').dataTable({
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
	var selectedEmpId = null;
	$(document).on('click','.employee-btn',function(e){
		selectedEmpId = e.target.id;
		$('.add-employee-name').val($(this).text())
	})

	var loadingAddSss = null;
	$('.add-sss-btn').on('click',function(){
		var btnName = this;
		if(!loadingAddSss){
			loadingAddSss = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.add-sss-warning').empty();
            $.ajax({
            	url:base_url+'loans_controller/addNewSss',
            	type:'post',
            	dataType:'json',
            	data:{
            		empId:selectedEmpId,
            		name:$('.add-employee-name').val(),
            		loanType:$('.loan-type').val(),
            		dateFrom:$('.add-date-from').val(),
            		dateTo:$('.add-date-to').val(),
            		amountLoan:$('.add-amount-loan').val(),
            		deduction:$('.add-deduction').val(),
            		remainingBalance:$('.add-remaining-balance').val(),
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
            			render_response('.add-sss-warning',response.msg, "danger")
                        loadingAddSss = false;
                        change_button_to_default(btnName, 'Submit');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAddSss = false;
                    change_button_to_default(btnName, 'Submit');
            	}
            })
		}
	})
	//for adding sss loan end
})