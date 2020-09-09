$(document).ready(function(){
	//for getting position list start
	get_department_list();
	function get_department_list(){
		$('#departmentList tbody').empty();
		$.ajax({
			url:base_url+'department_controller/getDepartmentList',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#departmentList tbody').append(response.finalData);
					$('#departmentList').dataTable({
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

	var loadingAddDepartment = false;
	$('.add-department-btn').on('click',function(){
        var btnName = this;
		if(!loadingAddDepartment){
            loadingAddDepartment = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.add-department-warning').empty();

            $.ajax({
            	url:base_url+'department_controller/addDepartment',
            	type:'post',
            	dataType:'json',
            	data:{
            		name:$('.add-department-name').val(),
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
                        render_response('.add-department-warning',response.msg, "danger")
                        loadingAddDepartment = false;
                        change_button_to_default(btnName, 'Submit');
                    }
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingAddDepartment = false;
                    change_button_to_default(btnName, 'Submit');
            	}

            })
        }
	})

    var allowUpdateDepartment = false
    var updateDepartmentId = null;
    $(document).on('click','.update-department ',function(e){
        updateDepartmentId = e.target.id;
        $.ajax({
            url:base_url+'department_controller/getUpdateInfo',
            type:'post',
            dataType:'json',
            data:{
                id:updateDepartmentId,
            },
            success:function(response){
                if(response.status == "success"){
                    allowUpdateDepartment = true;
                    $('.update-department-name').val(response.department)
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

    var loadingUpdateDepartment = false;
    $('.update-department-btn').on('click',function(){
        var btnName = this;
        if(allowUpdateDepartment){
            if(!loadingUpdateDepartment){
                loadingUpdateDepartment = true;
                $(btnName).text('');
                $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
                $(btnName).prop('disabled', true);
                $(btnName).css('cursor','not-allowed');
                $('.update-department-warning').empty();

                $.ajax({
                    url:base_url+'department_controller/updateDepartment',
                    type:'post',
                    dataType:'json',
                    data:{
                        id:updateDepartmentId,
                        name:$('.update-department-name').val(),
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
                            render_response('.update-department-warning',response.msg, "danger")
                            loadingUpdateDepartment = false;
                            change_button_to_default(btnName, 'Update');
                        }
                    },
                    error:function(response){
                        toast_options(4000);
                        toastr.error("There was a problem, please try again!");
                        loadingUpdateDepartment = false;
                        change_button_to_default(btnName, 'Update');
                    }

                })
            }
        }   
    })

    $(document).on('click','.delete-department',function(e){
        var id = e.target.id;
        Swal.fire({
            html: 'Are you sure you want to remove department <strong>'+$('.department-name-'+id).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url:base_url+'department_controller/removeDepartment',
                    type:'post',
                    dataType:'json',
                    data:{
                        id:id
                    },
                    success:function(response){
                        if(response.status == "success"){
                            toast_options(4000);
                            toastr.success(response.msg);
                            $('.department-tr-'+id).remove();
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
})