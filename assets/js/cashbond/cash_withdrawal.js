$(document).ready(function(){

	var loadingWithdraw = false;
	$('.file-withdraw-btn').on('click',function(){
		var btnName = this;
		if(!loadingWithdraw){
			loadingWithdraw = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.file-withdraw-warning').empty();
            $.ajax({
            	url:base_url+'cashbond_controller/insertCashWithdraw',
            	type:'post',
            	dataType:'json',
            	data:{
            		amount:$('.amount-withdraw').val(),
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
            			render_response('.file-withdraw-warning',response.msg, "danger")
                        loadingWithdraw = false;
                        change_button_to_default(btnName, 'File Withdrawal');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingWithdraw = false;
                    change_button_to_default(btnName, 'File Withdrawal');
            	}
            })
		}
	})

	get_approve_cashbond_withdraw();
	function get_approve_cashbond_withdraw(){
		$('#cashbondWithdrawHistoryApprove tbody').empty();
		$.ajax({
			url:base_url+'cashbond_controller/getApproveCashbondWithdraw',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					response.finalCashbondWithdrawData.forEach(function(data,key){
						var append = '<tr>'+
							'<td>'+data.date_file+'</td>'+
							'<td>'+data.approve_date+'</td>'+
							'<td>Php. '+data.amount+'</td>'+
						'</tr>';
						$('#cashbondWithdrawHistoryApprove tbody').append(append);
					});
					$('#cashbondWithdrawHistoryApprove').dataTable();
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

	get_pending_cashbond_withdraw();
	function get_pending_cashbond_withdraw(){
		$('#pendingCashbondWithdrawal tbody').empty();
		$.ajax({
			url:base_url+'cashbond_controller/getPendingCashbondWithdraw',
			type:'get',
			dataType:'json',
			success:function(response) {
				if(response.status == "success"){
					response.finalPendingWithdrawData.forEach(function(data,key){
						var append = '<tr '+data.file_cashbond_withdrawal_id+'>'+
							'<td>'+data.emp_name+'</td>'+
							'<td>Php. '+data.amount+'</td>'+
							'<td>'+data.date_file+'</td>'+
							'<td>'+data.date_file+'</td>'+
						'</tr>';
						$('#pendingCashbondWithdrawal tbody').append(append);
					})
					$('#pendingCashbondWithdrawal').dataTable();
				}
				else{
					toast_options(4000);
                    toastr.error("There was a problem, please try again!");
				}
			},
			error:function(response) {
				toast_options(4000);
                toastr.error("There was a problem, please try again!");
			}
		})
	}




	function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})