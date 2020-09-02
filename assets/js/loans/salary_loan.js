$(document).ready(function(){
	get_employee_with_existing_salary_loan();
	function get_employee_with_existing_salary_loan(){
		$('#employeeWithExistingSalaryLoan tbody').empty();
		$.ajax({
			url:base_url+'loans_controller/getEmployeeWithExistingSalaryLoan',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#employeeWithExistingSalaryLoan tbody').append(response.finalData);
					$('#employeeWithExistingSalaryLoan').dataTable({
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

	//for update salary loan start
	var editSalaryLoanId = null;
	$(document).on('click','.edit-salary-loan-btn',function(e){
		editSalaryLoanId = e.target.id;
		$('.salary-loan-info').hide();
        $('.update-salary-loan-btn').hide();
        $('.loading-salary-loan').show();
        $.ajax({
        	url:base_url+'loans_controller/getSalaryLoanInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:editSalaryLoanId,
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.salary-loan-info').show();
        			$('.update-salary-loan-btn').show();
        			$('.loading-salary-loan').hide();
			        if(response.finalData.length > 0){
			        	response.finalData.forEach(function(data,key){
			        		$('.employee-name').val(data.name)
			        		$('.deduction-type option[value='+data.deduction_type+']').attr('selected','selected');
			        		if(data.deduction_day == 15){
			        			$('#fifteen').prop("checked",true);
			        			$('#thirty').prop("checked",false);
			        		}
			        		else{
			        			$('#fifteen').prop("checked",false);
			        			$('#thirty').prop("checked",true);
			        		}
			        		if(data.deduction_type == "Semi-monthly"){
								$('#fifteen').prop("disabled",true);
								$('#thirty').prop("disabled",true);
							}
							else{
								$('#fifteen').prop("disabled",false);
								$('#thirty').prop("disabled",false);
							}
			        		$('.total-months option[value='+data.total_months+']').attr('selected','selected');
			        		$('.date-from').val(data.date_from)
			        		$('.date-to').val(data.date_to)
			        		$('.amount-loan').val(data.amount_loan)
			        		$('.deduction').val(data.deduction)
			        		$('.remaining-balance').val(data.remaining_balance)
			        		$('.date-from').datepicker("option","defaultDate", data.date_from);
			        		$('.date-to').datepicker("option","defaultDate", data.date_to);
			        		$('.remarks').val(data.remarks)
			        	})
			        }
        		}
        		else{
        			$('.loading-simkimban').show();
               	 	$('.loading-simkimban').empty();
                	$('.loading-simkimban').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                	editSalaryLoanId = null
        		}
        	},
        	error:function(response){
        		$('.loading-simkimban').show();
                $('.loading-simkimban').empty();
                $('.loading-simkimban').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                editSalaryLoanId = null
        	}
        })
	})
	var deductionDay = null;
	$('.deduction-type').on('change',function(e){
		if($(this).val() == "Semi-monthly"){
			$('#fifteen').prop("disabled",true);
			$('#thirty').prop("disabled",true);
		}
		else{
			$('#fifteen').prop("disabled",false);
			$('#thirty').prop("disabled",false);
		}
	})
	$("input:checkbox").on('click',function(){
		$("input:checkbox").prop('checked',false)
		$(this).prop('checked',true)

	})
	var loadingUpdateSalaryLoan = false;
	$('.update-salary-loan-btn').on('click',function(){
		var btnName = this;
		if(!loadingUpdateSalaryLoan){
			loadingUpdateSalaryLoan = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.salary-loan-warning').empty();
            var deductionDay = "";
            if($('#fifteen').is(':checked')){
            	deductionDay = "15";

            }
            else if($('#thirty').is(':checked')){
            	deductionDay = "30";
            }
            if($('.add-deduction-type').val() == "Semi-monthly"){
            	deductionDay = "0";
            }
            $.ajax({
            	url:base_url+'loans_controller/updateSalaryLoanData',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:editSalaryLoanId,
            		deductionType:$('.deduction-type').val(),
            		deductionDay:deductionDay,
            		totalMonths:$('.total-months').val(),
            		dateFrom:$('.date-from').val(),
            		dateTo:$('.date-to').val(),
            		amountLoan:$('.amount-loan').val(),
            		deduction:$('.deduction').val(),
            		remainingBalance:$('.remaining-balance').val(),
            		remarks:$('.remarks').val(),
            		name:$('.employee-name').val(),
            	},
            	success:function(response){
            		if(response.status == "success"){
            			toast_options(4000);
                        toastr.success("The Salary Loan info was successfully updated.");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
            		}
            		else{
            			render_response('.salary-loan-warning',response.msg, "danger")
                        loadingUpdateSalaryLoan = false;
                        change_button_to_default(btnName, 'Update');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateSalaryLoan = false;
                    change_button_to_default(btnName, 'Update');
            	}
            })
		}
	})
	//for update salary loan end

	//for adjust salary loan start

	var adjustSalaryLoanId = false;
	$(document).on('click','.adjust-salary-loan-btn',function(e){
		adjustSalaryLoanId = e.target.id;

		$('.adjust-salary-loan-info').hide();
        $('.adjust-salary-loan-btn').hide();
        $('.adjust-loading-salary-loan').show();
        $.ajax({
        	url:base_url+'loans_controller/getAdjustSalaryLoanInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:adjustSalaryLoanId,
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.adjust-salary-loan-info').show();
					$('.adjust-salary-loan-btn').show();
					$('.adjust-loading-salary-loan').hide();
					$('.adjust-employee-name').val(response.name);
					$('.adjust-outstanding-balance').val(response.remainingBalance)

			        $('.adjust-date-payment').datepicker("option","defaultDate", new Date());
        		}
        		else{
        			$('.adjust-loading-salary-loan').show();
               	 	$('.adjust-loading-salary-loan').empty();
                	$('.adjust-loading-salary-loan').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                	adjustSalaryLoanId = null
        		}
        	},
        	error:function(response){
        		$('.adjust-loading-salary-loan').show();
                $('.adjust-loading-salary-loan').empty();
                $('.adjust-loading-salary-loan').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                adjustSalaryLoanId = null
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

	var loadingAdjustSalaryLoan = false;
	$('.adjust-salary-loan-btn').on('click',function(){
		var btnName = this;
		if(!loadingAdjustSalaryLoan){
			loadingAdjustSalaryLoan = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.adjust-salary-loan-warning').empty();
            $.ajax({
            	url:base_url+'loans_controller/adjustSalaryLoanData',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:adjustSalaryLoanId,
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
            			render_response('.adjust-salary-loan-warning',response.msg, "danger")
                        loadingAdjustSalaryLoan = false;
                        change_button_to_default(btnName, 'Adjust');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAdjustSalaryLoan = false;
                    change_button_to_default(btnName, 'Adjust');
            	}
            })
		}
	})


	//for adjust salary loan end
})