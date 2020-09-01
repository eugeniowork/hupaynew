$(document).ready(function(){
	get_employee_with_existing_simkimban()
	function get_employee_with_existing_simkimban(){
		$('#employeeWithExistingSimkimban tbody').empty();
		$.ajax({
			url:base_url+'simkimban_controller/getEmployeeWithExistingSimkimban',
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
					$('#employeeWithExistingSimkimban tbody').append(response.finalData);
					$('#employeeWithExistingSimkimban').dataTable({
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




	var editSimkimbanId = null;
	$(document).on('click','.edit-simkimban',function(e){
		editSimkimbanId = e.target.id;
		$('.simkimban-info').hide();
        $('.update-simkimban-btn').hide();
        $('.loading-simkimban').show();
        $.ajax({
        	url:base_url+'simkimban_controller/getSimkimbanInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:editSimkimbanId,
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.simkimban-info').show();
			        $('.update-simkimban-btn').show();
			        $('.loading-simkimban').hide();
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
			        		
			        		$('.total-months option[value='+data.total_months+']').attr('selected','selected');
			        		$('.date-from').val(data.date_from)
			        		$('.date-to').val(data.date_to)
			        		$('.item').val(data.item)
			        		$('.amount-loan').val(data.amount_loan)
			        		$('.deduction').val(data.deduction)
			        		$('.remaining-balance').val(data.remaining_balance)
			        		$('.date-from').datepicker("option","defaultDate", data.date_from);
			        		$('.date-to').datepicker("option","defaultDate", data.date_to);
			        	})
			        }
        		}
        		else{
        			$('.loading-simkimban').show();
               	 	$('.loading-simkimban').empty();
                	$('.loading-simkimban').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                	editSimkimbanId = null
        		}
        	},
        	error:function(response){
        		$('.loading-simkimban').show();
                $('.loading-simkimban').empty();
                $('.loading-simkimban').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                editSimkimbanId = null
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

	var loadingUpdateSimkimban = false;
	$('.update-simkimban-btn').on('click',function(){
		var btnName = this;
		if(!loadingUpdateSimkimban){
			loadingUpdateSimkimban = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.simkimban-warning').empty();
            var deductionDay = "";
            if($('#fifteen').is(':checked')){
            	deductionDay = "15";

            }
            else if($('#thirty').is(':checked')){
            	deductionDay = "30";
            }
            $.ajax({
            	url:base_url+'simkimban_controller/updateSimkimbanData',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:editSimkimbanId,
            		deductionType:$('.deduction-type').val(),
            		deductionDay:deductionDay,
            		totalMonths:$('.total-months').val(),
            		dateFrom:$('.date-from').val(),
            		dateTo:$('.date-to').val(),
            		item:$('.item').val(),
            		amountLoan:$('.amount-loan').val(),
            		deduction:$('.deduction').val(),
            		remainingBalance:$('.remaining-balance').val(),
            	},
            	success:function(response){
            		if(response.status == "success"){
            			toast_options(4000);
                        toastr.success("The SIMKIMBAN info was successfully updated.");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
            		}
            		else{
            			render_response('.simkimban-warning',response.msg, "danger")
                        loadingUpdateSimkimban = false;
                        change_button_to_default(btnName, 'Update');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateSimkimban = false;
                    change_button_to_default(btnName, 'Update');
            	}
            })
		}
	})

	var adjustSimkimbanId = false;
	$(document).on('click','.adjust-simkimban',function(e){
		adjustSimkimbanId = e.target.id;

		$('.adjsut-simkimban-info').hide();
        $('.adjust-simkimban-btn').hide();
        $('.adjust-loading-simkimban').show();
        $.ajax({
        	url:base_url+'simkimban_controller/getAdjustSimkimbanInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:adjustSimkimbanId,
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.adjsut-simkimban-info').show();
					$('.adjust-simkimban-btn').show();
					$('.adjust-loading-simkimban').hide();
					$('.adjust-employee-name').val(response.name);
					$('.adjust-outstanding-balance').val(response.remainingBalance)

			        $('.adjust-date-payment').datepicker("option","defaultDate", new Date());
        		}
        		else{
        			$('.adjust-loading-simkimban').show();
               	 	$('.adjust-loading-simkimban').empty();
                	$('.adjust-loading-simkimban').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                	adjustSimkimbanId = null
        		}
        	},
        	error:function(response){
        		$('.adjust-loading-simkimban').show();
                $('.adjust-loading-simkimban').empty();
                $('.adjust-loading-simkimban').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                adjustSimkimbanId = null
        	}
        })
	})

	var loadingAdjustSimkimban = false;
	$('.adjust-simkimban-btn').on('click',function(){
		var btnName = this;
		if(!loadingAdjustSimkimban){
			loadingAdjustSimkimban = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.adjust-simkimban-warning').empty();
            $.ajax({
            	url:base_url+'simkimban_controller/adjustSimkimbanData',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:adjustSimkimbanId,
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
            			render_response('.adjust-simkimban-warning',response.msg, "danger")
                        loadingAdjustSimkimban = false;
                        change_button_to_default(btnName, 'Adjust');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAdjustSimkimban = false;
                    change_button_to_default(btnName, 'Adjust');
            	}
            })
		}
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

	var viewSimkimbanHistoryId = null;
	$(document).on('click','.view-simkimban-history-btn',function(e){
		viewSimkimbanHistoryId = e.target.id;
		$('.simkimban-history-info').hide();
        $('.loading-simkimban-history').show();
        $.ajax({
        	url:base_url+'simkimban_controller/getSimkimbanHistoryInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:viewSimkimbanHistoryId,
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.simkimban-history-info').show();
        			$('.loading-simkimban-history').hide();
        			$('.simkimbanLoanHistory tbody').append(response.finalData);
        			$('#simkimbanLoanHistory').dataTable({
						ordering:false
					});
        			
        		}
        		else{
        			$('.loading-simkimban-history').show();
               	 	$('.loading-simkimban-history').empty();
                	$('.loading-simkimban-history').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                	viewSimkimbanHistoryId = null
        		}
        	},
        	error:function(response){
        		$('.loading-simkimban-history').show();
                $('.loading-simkimban-history').empty();
                $('.loading-simkimban-history').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                viewSimkimbanHistoryId = null
        	}
        })
	})



	$(".input-only").keydown(function (e) {
		//  return false;
		if(e.keyCode != 116) {
		    return false;
		}
	});

		// onpaste
	$(".input-only").on("paste", function(){
		 return false;
	});
	$(".float-only").keydown(function (e) {

        // for decimal pint
        if (e.keyCode == "190") {
            if ($(this).val().replace(/[0-9]/g, "") == ".") {
            return false;  
            }
        }

        // Allow: backspace, delete, tab, escape, enter , F5
        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
             // let it happen, don't do anything
             return;
        }
            // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

        // for security purpose return false
    $(".float-only").on("paste", function(){
        return false;
    });


    $(".float-only").on('input', function(){
        if ($(this).attr("maxlength") != 9){
            if ($(this).val().length > 9){
                $(this).val($(this).val().slice(0,-1));
            }
            $(this).attr("maxlength","9");
        }

    });

    function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})