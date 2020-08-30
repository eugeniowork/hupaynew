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
	function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})