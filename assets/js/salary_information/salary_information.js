$(document).ready(function(){


	get_employee_salary_information();
	function get_employee_salary_information(){
		$('#activeEmployeeSalaryInformation tbody').empty();
		$.ajax({
			url:base_url+'salary_controller/getEmployeeSalaryInformation',
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
					$('#activeEmployeeSalaryInformation tbody').append(response.finalSalaryInformationData);
					$('#activeEmployeeSalaryInformation').dataTable({
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