$(document).ready(function(){
	//for getting memorandum list start
	get_payslip_list();
	function get_payslip_list(){
		$('#payslipList tbody').empty();
		$.ajax({
			url:base_url+'payroll_controller/getPayslipList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#payslipList tbody').append(response.finalData);
					$('#payslipList').dataTable({
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
	//for getting memorandum list end

	$(document).on('click','.print-payslip',function(e){
		var id = e.target.id;
		window.location.href = base_url+'payroll_controller/generatePayslip/'+id
		
		// $.ajax({
		// 	url:base_url+'payroll_controller/generatePayslip',
		// 	type:'post',
		// 	dataType:'json',
		// 	data:{
		// 		id:id,
		// 	},
		// 	success:function(response){
		// 		console.log(response)
		// 	},
		// 	error:function(response){
		// 		toast_options(4000);
  //               toastr.error("There was a problem, please try again!");
		// 	}
		// })
	})
})