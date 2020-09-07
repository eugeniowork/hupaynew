$(document).ready(function(){
	//for getting memorandum list start
	get_sss_contribution();
	function get_sss_contribution(){
		$('#listOfMemorandum tbody').empty();
		$.ajax({
			url:base_url+'memorandum_controller/getListOfMemorandum',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#listOfMemorandum tbody').append(response.finalData);
					$('#listOfMemorandum').dataTable({
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

	//for update memo start
	var updateMemoId = null;
	var memoRecipientCount = 0;
	var dept_id_count = 0;
	var emp_id_count = 0;

	$(document).on('click','.open-edit-memo',function(e){
		updateMemoId = e.target.id;

		$('.update-memo-info').hide();
        $('.update-memo-btn').hide();
        $('.loading-update-memo').show();

        $('.recipient').empty();

        $.ajax({
        	url:base_url+'memorandum_controller/getUpdateMemoInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:updateMemoId
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.update-memo-info').show();
			        $('.update-memo-btn').show();
			        $('.loading-update-memo').hide();
			        if(response.finalData.length > 0 ){
			        	response.finalData.forEach(function(data,key){
			        		$('.update-add-recipient').prop('disabled',data.disabled)
			        		$('.recipient').append(data.recipient)
			        		memoRecipientCount = data.memoRecipientCount
			        		get_all_recipient(data.memoRecipientCount);
			        		$('.update-from').val(data.from)
			        		$('.update-subject').val(data.subject)
			        		$('.update-content').val(data.content)
			        		$(".update-content").jqte();
	
							// settings of status
							var jqteStatus = true;
							$(".update-content").click(function()
							{
								jqteStatus = jqteStatus ? false : true;
								$(".update-content").jqte({"status" : jqteStatus})
							});

			        	})
			        }
        		}
        		else{
        			$('.loading-update-memo').show();
	                $('.loading-update-memo').empty();
	                $('.loading-update-memo').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
	                updateMemoId = null;
        		}
        	},
        	error:function(response){
        		$('.loading-update-memo').show();
                $('.loading-update-memo').empty();
                $('.loading-update-memo').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                updateMemoId = null;
        	}

        })
	})

	


	//get employee for memorandum start
	// get_list_of_employee()
	// function get_list_of_employee(){
	// 	$('.memo-employee-list tbody').empty();
	// 	$.ajax({
	// 		url:base_url+'employee_controller/getAllEmployee',
	// 		type:'get',
	// 		dataType:'json',
	// 		success:function(response){
	// 			if(response.status == "success"){
	// 				$('.memo-employee-list tbody').append(response.finalData)
	// 				$('.memo-employee-list').dataTable({
	// 					ordering:false
	// 				});
	// 			}
	// 			else{
	// 				toast_options(4000);
 //                	toastr.error("There was a problem, please try again!");
	// 			}
	// 		},
	// 		error:function(response){
	// 			toast_options(4000);
 //                toastr.error("There was a problem, please try again!");
	// 		}
	// 	})
	// }
	//get employee for memorandum end
	$("#attendance_list").on("click","a[id='chooseEmployee']", function(){
    	//alert("Hello Worldss!");	
    	$("a[id='chooseEmployee']").on("click",function () {
			 // empName
        var datastring = "emp_id="+$(this).closest("tr").attr("id");

        //alert("Hello World!");
        //alert("Hello World!");

        var emp_id = $(this).closest("tr").attr("id");

        // datastring
          $.ajax({
                type: "POST",
                url: "ajax/append_emp_name.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

                  $("#emp_list_modal").modal("hide");
                 // $("#updateFormModal").modal("show");
                    
                  // if has an error
                  if (data == "Error" || data == 1){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {
                  	//alert("Hello World!");
                    $("input[name='update_to"+emp_id_count+"']").val(data);
                    //$("div[id='to_div']").html("<input type='hidden' name='updateEmpId' value='"+emp_id+"' />");
                    
                   // $("#atm_record_modal_body").html(data);
                   // $("#updateATM_modal").modal("show");
                   
                  }
                 
                }
           }); 
	    });
	})

	function get_all_recipient(count){
		counter = 0;
		do{
			console.log('asd')
			counter++;

			// for choose department in memo choose_department_memo
			$("div[id='choose"+counter+"']").on("click","a[id='choose_department_memo']",function () {
	        	$("#dept_list_modal").modal("show");
	        	dept_id_count = $(this).closest("div").attr("id").slice(6,7);
	      	});
	      	// for choosing memo specific employee choose_employee_memo
		    $("div[id='choose"+counter+"']").on("click","a[id='choose_employee_memo']",function () {
		        $("#emp_list_modal").modal("show");
		        emp_id_count = $(this).closest("div").attr("id").slice(6,7);
		    });
		    $("button[id='remove_recipient"+counter+"']").on("click",function(){
		    	//alert("Hello World!");
		    //alert($(this).attr("id").slice(16,17));
	          	$("div[id='update_recipient_mother_div"+$(this).attr("id").slice(16,17)+"']").remove();
	          //alert(memoRecipientCount);
	          //  $("recipient_mother_div"+memoRecipientCount)

	       	});
		    $("input[name='update_optRecipient"+counter+"']").on("click",function () {
		    	var recipientType = $(this).val();
		    	if (recipientType == "All"|| recipientType == "Specific Employee" || recipientType == "Department"){
					if (recipientType == "Specific Employee") {
						$("input[name='update_to"+counter+"']").removeAttr("disabled");
						$("div[id='choose"+counter+"']").html("<a href='#' id='choose_employee_memo'>Choose</a>");
						$("input[name='update_to"+counter+"']").attr("required","required");
						$("input[name='update_to"+counter+"']").val("");
						$("input[name='update_to"+counter+"']").attr("placeholder","Employee ..");

						$("button[id='add_recipient']").removeAttr("disabled");
					}

					if (recipientType == "All"){
						$("input[name='update_to"+counter+"']").attr("disabled","disabled");
						$("input[name='update_to"+counter+"'']").removeAttr("required");
						$("div[id='choose"+counter+"']").html("");
						$("input[name='update_to"+counter+"']").val("");
						$("input[name='update_to"+counter+"']").attr("placeholder","");

						$("button[id='add_recipient']").attr("disabled","disabled");

						$("#update_div_recipient").html("");
					}
					if (recipientType == "Department"){
						$("input[name='update_to"+counter+"']").removeAttr("disabled");
						$("input[name='update_to"+counter+"']").attr("required","required");
						$("div[id='choose"+counter+"']").html("<a href='#' id='choose_department_memo'>Choose</a>");
						$("input[name='update_to"+counter+"']").attr("placeholder","Department ..");
						$("input[name='update_to"+counter+"']").val("");

						$("button[id='add_recipient']").removeAttr("disabled");
						// for resetting global variable value

					}
				}
			});
		}
		while(count >= counter)

	}

	
	$(document).on('click','.delete-memo',function(e){
		var id = e.target.id;
		Swal.fire({
            html: 'Are you sure you want to delete the <strong>Memorandum</strong> of <strong>'+$('.memo-subject-'+id).text()+'</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
			if (result.value) {
				$.ajax({
					url:base_url+'memorandum_controller/deleteMemo',
					type:'post',
					dataType:'json',
					data:{
						id:id
					},
					success:function(response){
						if(response.status == "success"){
							toast_options(4000);
	                        toastr.success(response.msg)
	                        $('.memo-tr-'+id).remove()
	                        setTimeout(function(){
	                            window.location.reload();
	                        },1000)
						}
						else{
							toast_options(4000);
                    		toastr.error("There was a problem on deleting the memorandum, please try again!");
						}
					},
					error:function(response){
						toast_options(4000);
                    	toastr.error("There was a problem, please try again!");
					}
				})
			}
		})
	})

	//for update memo end

	//for view memo images start
	var updateMemoImagesId = null;
	$(document).on('click','.view-memo-image',function(e){
		updateMemoImagesId = e.target.id;
		$('.memo-images-info').hide();
        $('.update-memo-image-btn').hide();
        $('.loading-images-memo').show();

        $('.image-section').empty();
        $.ajax({
        	url:base_url+'memorandum_controller/getMemoImages',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:updateMemoImagesId,
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.memo-subject').text(response.subject);
        			$('.memo-images-info').show();
			        $('.update-memo-image-btn').show();
			        $('.loading-images-memo').hide();

			        $('.image-section').append(response.images);
        		}
        		else{
        			$('.loading-images-memo').show();
	                $('.loading-images-memo').empty();
	                $('.loading-images-memo').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
	                updateMemoImagesId = null;
        		}
        	},
        	error:function(response){
        		$('.loading-images-memo').show();
                $('.loading-images-memo').empty();
                $('.loading-images-memo').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                updateMemoImagesId = null;
        	}

        })
	})
	//for view memo images end

	//for remove images start
	var remove_array = [];
	$(document).on('click','.remove-image-btn',function(e){
		remove_array.push(e.target.id);
		$('.image-memo-'+e.target.id).remove();
	})

	var loadingRemoveMemoImage = false;
	$('.update-memo-image-btn').on('click',function(){
		if(remove_array.length == 0){
			toast_options(4000);
            toastr.error("Please select a image first!");
		}
		else{
			var btnName = this;
			if(!loadingRemoveMemoImage){
			loadingRemoveMemoImage = true;
				$(btnName).text('');
	            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
	            $(btnName).prop('disabled', true);
	            $(btnName).css('cursor','not-allowed');
	            $('.memo-images-warning').empty();
	            $.ajax({
	            	url:base_url+'memorandum_controller/removeMemoImage',
	            	type:'post',
	            	dataType:'json',
	            	data:{
	            		ids:remove_array
	            	},
	            	success:function(response){
	            		if(response.status == "success"){
	            			toast_options(4000);
	                        toastr.success("Memo images was successfully updated.");
	                        setTimeout(function(){
	                            window.location.reload();
	                        },1000)
	            		}
	            		else{
	            			render_response('.memo-images-warning',response.msg, "danger")
                        	loadingRemoveMemoImage = false;
                        	change_button_to_default(btnName, 'Update');
	            		}
	            	},
	            	error:function(response){
	            		toast_options(4000);
	                    toastr.error("There was a problem, please try again!");
	                    loadingRemoveMemoImage = false;
	                    change_button_to_default(btnName, 'Update');
	            	}
	            })
	        }

		}
	})
	$("#memoImagesModal").on('hide.bs.modal', function(){
	    remove_array = [];
	});
	//for remove images end
})