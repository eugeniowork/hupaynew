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
					if(response.finalPendingWithdrawData.length > 0){
						response.finalPendingWithdrawData.forEach(function(data,key){
							var append = '<tr '+data.file_cashbond_withdrawal_id+'>'+
								'<td class="withdrawal-name-'+data.file_cashbond_withdrawal_id+'">'+data.emp_name+'</td>'+
								'<td>Php. '+data.amount+'</td>'+
								'<td>'+data.date_file+'</td>'+
								'<td>'+
									'<button id='+data.file_cashbond_withdrawal_id+' class="btn btn-sm btn-outline-primary approve-withdrawal-btn">Approve</button>'+
									'&nbsp;'+
									'<button id='+data.file_cashbond_withdrawal_id+' class="btn btn-sm btn-outline-danger">Disapprove</button>'+
								'</td>'+
							'</tr>';
							$('#pendingCashbondWithdrawal tbody').append(append);
						})
					}
					
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

	$(document).on('click','.approve-withdrawal-btn',function(e){
		var id = e.target.id;
		Swal.fire({
            html: 'Are you sure you want to approve this filed cashbond by <strong>'+$('.withdrawal-name-'+id).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
        	if (result.value) {
        		$.ajax({
        			url:base_url+'cashbond_controller/approveCashWithdrawal',
        			type:'post',
        			dataType:'json',
        			data:{
        				id:id,
        			},
        			success:function(response){
        				if(response.status == "success"){
        					toast_options(4000);
	                        toastr.success('Cashbond withdrawal by <strong>'+$('.withdrawal-name-'+id).text()+'</strong> was successfully approved.');
	                        setTimeout(function(){
	                            window.location.reload();
	                        },1000)
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


	function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})