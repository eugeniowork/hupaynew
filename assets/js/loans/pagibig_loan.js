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
					// if(response.finalAdjustmentReportsData.length > 0){
					// 	response.finalAdjustmentReportsData.forEach(function(data,key){
					// 		var append = '<tr '+data.adjustment_loan_id+'>'+
					// 			'<td>'+data.name+'</td>'+
					// 			'<td>'+data.date_payment+'</td>'+
					// 			'<td>'+data.loan_type+'</td>'+
					// 			'<td>Php. '+data.cash_payment+'</td>'+
					// 			'<td>Php. '+data.outstanding_balance+'</td>'+
					// 			'<td><a href="#">Print Reports</a></td>'+
					// 		'</tr>';
					// 		$('#adjustmentSalaryLoanReportsList tbody').append(append);
					// 	})
					// 	$('#adjustmentSalaryLoanReportsList').dataTable({
					// 		ordering:false
					// 	});
					// }
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
})