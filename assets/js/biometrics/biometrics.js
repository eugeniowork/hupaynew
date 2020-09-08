$(document).ready(function(){
	//for getting position list start
	get_biometics_list();
	function get_biometics_list(){
		$('#biometrics tbody').empty();
		$.ajax({
			url:base_url+'biometrics_controller/getBiometrics',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#biometrics tbody').append(response.finalData);
					$('#biometrics').dataTable({
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
	//for getting position list end

	//for get bio info
	var updateBioId = null;
	var allowBioUpdate = false;
	$(document).on('click','.update-bio',function(e){
		updateBioId = e.target.id;
		$.ajax({
			url:base_url+'biometrics_controller/getUpdateBioInfo',
			type:'post',
			dataType:'json',
			data:{
				id:updateBioId,
			},
			success:function(response){
				if(response.status == "success"){
					allowBioUpdate = true;
					$('.update-bio-id').val(response.bio)
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
	})
	$(document).on('click','.create-bio',function(e){
		updateBioId = e.target.id;
		$.ajax({
			url:base_url+'biometrics_controller/getUpdateBioInfo',
			type:'post',
			dataType:'json',
			data:{
				id:updateBioId,
			},
			success:function(response){
				if(response.status == "success"){
					allowBioUpdate = true;
					$('.update-bio-id').val("")
					$('.update-bio-btn').text('Create')
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
	})
	//update bio
	var loadingUpdateBio = false;
	$('.update-bio-btn').on('click',function(){
		var btnName = this;
		if(allowBioUpdate){
			if(!loadingUpdateBio){
				loadingUpdateBio = true;
	            $(btnName).text('');
	            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
	            $(btnName).prop('disabled', true);
	            $(btnName).css('cursor','not-allowed');
	            $('.update-bio-warning').empty();
	            $.ajax({
					url:base_url+'biometrics_controller/updateBio',
					type:'post',
					dataType:'json',
					data:{
						id:updateBioId,
						bio:$('.update-bio-id').val(),
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
							render_response('.update-bio-warning',response.msg, "danger")
							loadingUpdateBio = false;
	                    	change_button_to_default(btnName, 'Update');
						}
					},
					error:function(response){
						toast_options(4000);
		                toastr.error("There was a problem on updating team, please try again!");
		                loadingUpdateBio = false;
	                    change_button_to_default(btnName, 'Update');
					}
				})
	        }
		}
		else{
			toast_options(4000);
	        toastr.error("There was a problem on updating the bio id, please try again!");
		}
	})
	$(".update-bio-id").on('input', function(){
       if ($(this).attr("maxlength") != 4){
            if ($(this).val().length > 4){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","4");
       }

   });
})