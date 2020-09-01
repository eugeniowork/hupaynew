$(document).ready(function(){

	get_employee_simkimban_history_list();
	function get_employee_simkimban_history_list(){
		$('.employeeSimkimbanListHistory tbody').empty();
		$.ajax({
			url:base_url+'simkimban_controller/getEmployeeSimkimbanHistoryList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#employeeSimkimbanListHistory tbody').append(response.finalData);
					$('#employeeSimkimbanListHistory').dataTable({
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



	get_simkimban_history_list();
	function get_simkimban_history_list(){
		$('.simkimbanListHistory tbody').empty();
		$.ajax({
			url:base_url+'simkimban_controller/getSimkimbanHistoryList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#simkimbanListHistory tbody').append(response.finalData);
					$('#simkimbanListHistory').dataTable({
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

	var loaingAddSimkimban = false;
	$('.add-simkimban-btn').on('click',function(){
		var btnName = this;
		if(!loaingAddSimkimban){
			loaingAddSimkimban = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.add-simkimban-warning').empty();
            var deductionDay = "";
            if($('#addFifteen').is(':checked')){
            	deductionDay = "15";

            }
            else if($('#addThirty').is(':checked')){
            	deductionDay = "30";
            }
            if($('.add-deduction-type').val() == "Semi-monthly"){
            	deductionDay = "0";
            }
            $.ajax({
            	url:base_url+'simkimban_controller/addNewSimkimban',
            	type:'post',
            	dataType:'json',
            	data:{
            		empId:selectedEmpId,
            		name:$('.add-employee-name').val(),
            		deductionType:$('.add-deduction-type').val(),
            		deductionDay:deductionDay,
            		totalMonths:$('.add-total-months').val(),
            		dateFrom:$('.add-date-from').val(),
            		dateTo:$('.add-date-to').val(),
            		item:$('.add-item').val(),
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
            			render_response('.add-simkimban-warning',response.msg, "danger")
                        loaingAddSimkimban = false;
                        change_button_to_default(btnName, 'Submit');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loaingAddSimkimban = false;
                    change_button_to_default(btnName, 'Submit');
            	}

            })
		}
	})
	$('.add-deduction-type').on('change',function(e){
		if($(this).val() == "Semi-monthly"){
			$('#addFifteen').prop("disabled",true);
			$('#addThirty').prop("disabled",true);
		}
		else{
			$('#addFifteen').prop("disabled",false);
			$('#addThirty').prop("disabled",false);
		}
	})
	function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})