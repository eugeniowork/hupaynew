$(document).ready(function(){
	//for getting position list start
	get_position_list();
	function get_position_list(){
		$('#positionList tbody').empty();
		$.ajax({
			url:base_url+'position_controller/getPositionList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#positionList tbody').append(response.finalData);
					$('#positionList').dataTable({
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

	var loadingAddPosition = false;
	$('.add-poisition-btn').on('click',function(){
		var btnName = this;
		if(!loadingAddPosition){
			loadingAddPosition = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.add-position-warning').empty();
            $.ajax({
				url:base_url+'position_controller/addPosition',
				type:'post',
				dataType:'json',
				data:{
					positionName:$('.add-position-name').val(),
					department:$('.add-position-department').val(),
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
						render_response('.add-position-warning',response.msg, "danger")
						loadingAddPosition = false;
                    	change_button_to_default(btnName, 'Submit');
					}
				},
				error:function(response){
					toast_options(4000);
	                toastr.error("There was a problem on updating team, please try again!");
	                loadingAddPosition = false;
                    change_button_to_default(btnName, 'Submit');
				}
			})
        }
	})


	$(document).on('click','.delete-position',function(e){
		var id = e.target.id;
		Swal.fire({
            html: 'Are you sure you want to delete the <strong>'+$('.position-name-'+id).text()+'</strong> position?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
        	if (result.value) {
        		$.ajax({
        			url:base_url+'position_controller/removePosition',
        			type:'post',
        			dataType:'json',
        			data:{
        				id:id
        			},
        			success:function(response){
        				if(response.status == "success"){
        					toast_options(4000);
		                    toastr.success(response.msg);
		                    $('.position-tr-'+id).remove();
		                    setTimeout(function(){
		                        window.location.reload();
		                    },1000)
        				}
        				else{
        					toast_options(4000);
	                		toastr.error("There was a problem on deleting the position, please try again!");
        				}
        			},
        			error:function(response){
        				toast_options(4000);
	                	toastr.error("There was a problem on deleting the position, please try again!");
        			}
        		})
			}
		});
	})
})