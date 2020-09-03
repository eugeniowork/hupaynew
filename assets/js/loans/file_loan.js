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


	//for getting file loan list history start


	get_file_loan_list_history();
	function get_file_loan_list_history(){
		$('#fileLoanListHistory tbody').empty();
		$.ajax({
			url:base_url+'loans_controller/getFileLoanListHistory',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#fileLoanListHistory tbody').append(response.finalData);
					$('#fileLoanListHistory').dataTable({
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
	//for getting file loan list history end

	//for schedule of file loan data start
	var scheduleFileLoanId = null;
	$(document).on('click','.create-schedule-file-loan-btn',function(e){
		scheduleFileLoanId = e.target.id;
		$('.schedule-file-loan-info').hide();
        $('.schedule-file-loan-data-btn').hide();
        $('.loading-schedule-file-loan').show();
        $.ajax({
        	url:base_url+'loans_controller/getScheduleFileLoanInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:scheduleFileLoanId,
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.schedule-file-loan-info').show();
			        $('.schedule-file-loan-data-btn').show();
			        $('.loading-schedule-file-loan').hide();
			        $('.schedule-date-from-year').empty();
			        if(response.finalData.length > 0){
			        	$('.schedule-date-from-year').append('<option disabled>Select Option</option>')
			        	response.finalData.forEach(function(data,key){
			        		$('.loan-type-text').text(data.loan_type_text)
			        		
			        		var appendOption = '<option>'+data.year+'</option>'+
			        		'<option>'+data.next_year+'</option>';
			        		$('.schedule-date-from-year').append(appendOption);
			        		$('.schedule-remarks').val(data.purpose)
			        		if(data.loan_type == 2){
			        			$('.loan-text').hide();
			        			$('.for-simkimban').show();
			        			$('.employee-name-simkimban').val(data.name)
			        			$('.for-salary-employment').hide();
			        			$('.schedule-file-loan-simkimban-data-btn').show();
			        			$('.schedule-file-loan-data-btn').hide();
			        		}
			        		else{
			        			$('.loan-text').show();
			        			$('.for-simkimban').hide();
			        			$('.for-salary-employment').show();
			        			$('.schedule-file-loan-simkimban-data-btn').hide();
			        			$('.schedule-file-loan-data-btn').show();
			        		}
			        	});
			        }
        		}
        		else{
        			$('.loading-file-loan').show();
	           	 	$('.loading-file-loan').empty();
	            	$('.loading-file-loan').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
	            	scheduleFileLoanId = null
        		}
        		
        	},
        	error:function(response){
        		$('.loading-file-loan').show();
           	 	$('.loading-file-loan').empty();
            	$('.loading-file-loan').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
            	scheduleFileLoanId = null
        	}
        })
	})
	$('.schedule-deduction-type').on('change',function(e){
		if($(this).val() == "Semi-monthly"){
			$('#scheduleFifteen').prop("disabled",true);
			$('#scheduleThirty').prop("disabled",true);
		}
		else{
			$('#scheduleFifteen').prop("disabled",false);
			$('#scheduleThirty').prop("disabled",false);
		}
	})
	$("input:checkbox").on('click',function(){
		$("input:checkbox").prop('checked',false)
		$(this).prop('checked',true)
	});
	$('.schedule-deduction-type').on('change',function(){
		$('.schedule-total-months').empty();
		var start = 0;
		if($(this).val() == "" || $(this).val() == "Monthly"){
			start = 1;
		}
		for(var value = start; value < 25; value++){
			$('.schedule-total-months').append('<option value='+value+'>'+value+'</option>');
		}
	})
	$('.schedule-total-months').on('change',function(){
		var totalMonths = parseFloat($(this).val());
		if(totalMonths != 0){
			var dateFrom = $(".schedule-date-from-month").val() + "/"+$(".schedule-date-from-day").val() +"/"+$(".schedule-date-from-year").val();
			var nextMonth = addMonths(new Date(dateFrom), totalMonths);
			var currentMonth = nextMonth.getMonth() + 1;
			if (currentMonth == 0){
				currentMonth= 12;
			}
			var currentDate = new Date(dateFrom);
			var currentDay = currentDate.getDate();

			var currentYear = nextMonth.getFullYear();
			if ($(".schedule-deduction-type").val() == "Semi-monthly") {
		        if (currentDay == 30){
		            currentDay = 15;
		        }

		        else if (currentDay == 15){
		            currentDay = 30;
		            currentMonth = currentMonth - 1;
		        }      
		    }
			else{
				currentMonth = currentMonth - 1;
			}
			if (currentMonth == 2 && currentDay == 30){
			    currentDay = 28;
			}

			if (currentDay == 28){
	            currentDay = 30;
	        }

		         
	        if (currentMonth == 0){
	          	currentMonth = 12;
	          	currentYear = currentYear - 1;
	        }

		    var newDate = currentMonth + "/" + currentDay + "/" + currentYear;

		    $(".schedule-date-to").val(newDate);
		    //console.log(dateFrom)
		}

	})
	$('.schedule-amount-loan').on('change',function(){
		var totalMonths = $(".schedule-total-months").val();
	          
		var amountLoan = $(this).val();
	    var interest  = 0;
	    var deductionType = $(".schedule-deduction-type").val();
	    if(totalMonths == 0){
	    	$('.scheudle-deduction').val(amountLoan)
	    }
	    else{
	    	
            if(deductionType != "" && deductionType != "Semi-monthly" && deductionType !="Monthly"){
            	render_response('.schedule-file-loan-warning',"Please select deduction type.","danger");
            }
            else{
            	if (deductionType == "Semi-monthly" ) {
	            	totalMonths = parseInt(totalMonths) * 2;
	            }
            	if (deductionType == ""){
	            	render_response('.schedule-file-loan-warning',"Please select <strong>Deduction Type</strong> first.","danger");
				}
				else{
					$interest_rate = .036;
	              	if ($(".schedule-total-months").val() == 1 || $(".schedule-total-months").val() == 0){
	              		$interest_rate = 0;
	              	}

	                interest = (parseFloat(amountLoan) * $interest_rate) * (parseFloat($(".schedule-total-months").val()));
	                var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
					var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

					// for 2 decimal places
					totalPayment = totalPayment.toString().split('e');
					totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

					totalPayment = totalPayment.toString().split('e');
					final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
					$(".schedule-total-payment").val(final_totalPayment);

					// for 2 decimal places
					deduction = deduction.toString().split('e');
					deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

					deduction = deduction.toString().split('e');
					final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);
					$(".schedule-deduction").val(final_deduction); 
				}
            }
            
	    }
	})

	var loaindgScheduleFileLoan = false;
	$('.schedule-file-loan-data-btn').on('click',function(){
		var btnName = this;
		console.log($('.schedule-date-from-month').val()+"/"+$('.schedule-date-from-day').val()+"/"+$('.schedule-date-from-year').val())
		if(!loaindgScheduleFileLoan){
			loaindgScheduleFileLoan = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.schedule-file-loan-warning').empty();
            var deductionDay = "";
            if($('#scheduleFifteen').is(':checked')){
            	deductionDay = "15";

            }
            else if($('#scheduleThirty').is(':checked')){
            	deductionDay = "30";
            }
            if($('.schedule-deduction-type').val() == "Semi-monthly"){
            	deductionDay = "0";
            }
            $.ajax({
            	url:base_url+'loans_controller/scheduleFileLoan',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:scheduleFileLoanId,
            		deductionType:$('.schedule-deduction-type').val(),
            		deductionDay:deductionDay,
            		totalMonths:$('.schedule-total-months').val(),
            		dateFromMonth:$('.schedule-date-from-month').val(),
            		datefromDay:$('.schedule-date-from-day').val(),
            		dateFromYear:$('.schedule-date-from-year').val(),
            		dateTo:$('.schedule-date-to').val(),
            		amountLoan:$('.schedule-amount-loan').val(),
            		deduction:$('.schedule-deduction').val(),
            		totalPayment:$('.schedule-total-payment').val(),
            		remarks:$('.schedule-remarks').val(),
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
            			render_response('.schedule-file-loan-warning',response.msg, "danger")
                        loaindgScheduleFileLoan = false;
                        change_button_to_default(btnName, 'Submit');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loaindgScheduleFileLoan = false;
                    change_button_to_default(btnName, 'Submit');
            	}
            })
        }
	})
	//for simkimban
	var loaindgScheduleFileLoanSimkimban = false;
	$('.schedule-file-loan-simkimban-data-btn').on('click',function(){
		var btnName = this;
		//console.log($('.schedule-date-from-month').val()+"/"+$('.schedule-date-from-day').val()+"/"+$('.schedule-date-from-year').val())
		if(!loaindgScheduleFileLoanSimkimban){
			loaindgScheduleFileLoanSimkimban = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.schedule-file-loan-warning').empty();
            var deductionDay = "";
            if($('#scheduleFifteen').is(':checked')){
            	deductionDay = "15";

            }
            else if($('#scheduleThirty').is(':checked')){
            	deductionDay = "30";
            }
            if($('.schedule-deduction-type').val() == "Semi-monthly"){
            	deductionDay = "0";
            }
            $.ajax({
            	url:base_url+'loans_controller/scheduleFileLoanSimkimban',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:scheduleFileLoanId,
            		deductionType:$('.schedule-deduction-type').val(),
            		deductionDay:deductionDay,
            		totalMonths:$('.schedule-total-months').val(),
            		dateFromMonth:$('.schedule-date-from-month').val(),
            		datefromDay:$('.schedule-date-from-day').val(),
            		dateFromYear:$('.schedule-date-from-year').val(),
            		dateTo:$('.schedule-date-to').val(),
            		amountLoan:$('.schedule-amount-loan').val(),
            		deduction:$('.schedule-deduction').val(),
            		totalPayment:$('.schedule-total-payment').val(),
            		item:$('.item-simkimban').val(),
            		name:$('.employee-name-simkimban').val(),
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
            			render_response('.schedule-file-loan-warning',response.msg, "danger")
                        loaindgScheduleFileLoanSimkimban = false;
                        change_button_to_default(btnName, 'Submit');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loaindgScheduleFileLoanSimkimban = false;
                    change_button_to_default(btnName, 'Submit');
            	}
            })
        }
	})

	//for schedule of file loan data end

	//for disapprove of file loan start
	$(document).on('click','.disapprove-file-loan-btn',function(e){
		var id = e.target.id;
		Swal.fire({
            html: 'Are you sure you want to cancel your file salary loan of <Strong>'+$('.name-file-loan-'+id).text()+'</strong> with Reference No. <strong>'+$('.ref-no-file-salary-'+id).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
			if (result.value) {
				$.ajax({
        			url:base_url+'loans_controller/disapproveFileLoan',
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
	                        $('.file-loan-list-history-'+id).remove();
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
	//for disapprove of file loan end

	//for filed salary loan & employment program start
	get_file_loan_salary_and_employment_program();
	function get_file_loan_salary_and_employment_program(){
		$('#fileLoanSalaryAndEmployment tbody').empty();
		$.ajax({
			url:base_url+'loans_controller/fileLoanSalaryAndEmployment',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#fileLoanSalaryAndEmployment tbody').append(response.finalData);
					$('#fileLoanSalaryAndEmployment').dataTable({
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

	//for disapprove
	$(document).on('click','.disapprove-file-loan-salary-employment-btn',function(e){
		var id = e.target.id;
		Swal.fire({
            html: 'Are you sure you want to disapprove the file salary loan of <Strong>'+$('.approval-name-loan-'+id).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
			if (result.value) {
				$.ajax({
        			url:base_url+'loans_controller/disapproveFileSalaryAndEmploymentLoan',
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
	                        $('.salary-and-employment-'+id).remove();
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
	//for filed salary loan & employment program end


	//for file simkimban loan start
	get_file_loan_simkimban();
	function get_file_loan_simkimban(){
		$('#fileLoanSimkimban tbody').empty();
		$.ajax({
			url:base_url+'loans_controller/fileLoanSimkimban',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#fileLoanSimkimban tbody').append(response.finalData);
					$('#fileLoanSimkimban').dataTable({
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


	$(document).on('click','.disapprove-file-loan-simkimban-btn',function(e){
		var id = e.target.id;
		Swal.fire({
            html: 'Are you sure you want to disapprove the simkimban loan of <Strong>'+$('.approval-name-loan-simkimban-'+id).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
			if (result.value) {
				$.ajax({
        			url:base_url+'loans_controller/disapproveFileSimkimban',
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
	                        $('.file-loan-simkimban-'+id).remove();
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


	//for file simkimban loan end

	//for approval of simkimban loan start
	$(document).on('click','.approve-file-loan-simkimban-btn',function(e){
		var id = e.target.id;
		Swal.fire({
            html: 'Are you sure you want to approve the simkimban loan of <Strong>'+$('.approval-name-loan-simkimban-'+id).text()+'</strong> with Reference No. <strong>'+$('.file-loan-ref-no-'+id).text()+'</strong> ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
			if (result.value) {
				$.ajax({
        			url:base_url+'loans_controller/approveFileSimkimban',
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
	                        $('.file-loan-simkimban-'+id).remove();
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
	//for approval of simkimban loan end


	//for approval of salary employment start
	$(document).on('click','.approve-file-loan-salary-employment-btn',function(e){
		var id = e.target.id;
		Swal.fire({
            html: 'Are you sure you want to approve the file salary loan of <Strong>'+$('.approval-name-loan-'+id).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
			if (result.value) {
				$.ajax({
        			url:base_url+'loans_controller/approveFileLoan',
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
	                        $('.salary-and-employment-'+id).remove();
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
	//for approval of salary employment end


















	function isLeapYear(year) { 
        return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0)); 
    }
	function getDaysInMonth(year, month) {
        return [31, (isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
    }
	function addMonths(date, value) {
        var d = new Date(date),
            n = date.getDate();
        d.setDate(1);
        d.setMonth(d.getMonth() + value);
        d.setDate(Math.min(n, getDaysInMonth(d.getFullYear(), d.getMonth())));
        return d;
    }
})