$(document).ready(function(){

	get_year_total_deduction();
	function get_year_total_deduction(){
		$('#yearTotalDeduction tbody').empty();
		$.ajax({
			url:base_url+'deduction_controller/getYearTotalDeduction',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#yearTotalDeduction tbody').append(response.finalYearTotalDeductionData);
					$('#yearTotalDeduction').dataTable({
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
	var editEmpYearlyDeductionId = null;
	$(document).on('click', '.edit-emp-yearly-deduction-btn',function(e){
		editEmpYearlyDeductionId = e.target.id;
		$('.year-total-deduction-info').hide();
        $('.update-year-total-deduction').hide();
        $('.loading-total-deduction').show();
        $.ajax({
        	url:base_url+'deduction_controller/getYearTotalDeductionInfo',
        	type:'post',
        	dataType:'json',
        	data:{
        		id:editEmpYearlyDeductionId,
        	},
        	success:function(response){
        		if(response.status == "success"){
        			$('.year-total-deduction-info').show();
			        $('.update-year-total-deduction').show();
			        $('.loading-total-deduction').hide();
			        if(response.finalData.length > 0){
			        	response.finalData.forEach(function(data,key){
			        		// var append = '<div class="row">'+
			        		// 	'<div class="col-lg-6">'+
			        		// 		'<span>Employee Name</span>'+
			        		// 		'<input readonly type="text"  class="form-control">'
			        		// 	'</div>'+
			        		// 	'<div class="col-lg-6">'+
			        		// 		'<span>YTD Gross</span>'+
			        		// 		'<input type="text"  class="form-control">'
			        		// 	'</div>'+
			        		// '</div>';
			        		$('.ytd-employee-name').val(data.name);
			        		$('.ytd-year').val(data.year);
			        		$('.ytd-gross').val(data.ytd_gross);
			        		$('.ytd-allowance').val(data.ytd_allowance);
			        		if(data.ytd_tax_status == 'readonly'){
			        			var append = '<span>YTD W/Tax</span>'+
			        				'<input readonly type="text" class="form-control" value='+data.ytd_tax+'>';
			        		}
			        		else{
			        			var append = '<span>YTD W/Tax</span>'+
			        				'<input type="text" class="float-only form-control ytd-tax" value='+data.ytd_tax+' placeholder="Enter YTD W/Tax">';
			        		}
			        		$('.ytd-tax-info').empty();
			        		$('.ytd-tax-info').append(append);
			        	})
			        }
        		}
        		else{
        			$('.loading-total-deduction').show();
                    $('.loading-total-deduction').empty();
                    $('.loading-total-deduction').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                    editEmpYearlyDeductionId = null;
        		}
        	},
        	error:function(response){
        		$('.loading-total-deduction').show();
                $('.loading-total-deduction').empty();
                $('.loading-total-deduction').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                editEmpYearlyDeductionId = null;
        	}
        })
	})

	var loadingUpdateYTD = false;
	$('.update-year-total-deduction').on('click',function(){
		var btnName = this;
		if(!loadingUpdateYTD){
			loadingUpdateYTD = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.year-total-deduction-warning').empty();
            $.ajax({
            	url:base_url+'deduction_controller/updateYTD',
            	type:'post',
            	dataType:'json',
            	data:{
            		id:editEmpYearlyDeductionId,
            		ytdGross:$('.ytd-gross').val(),
            		ytdAllowance:$('.ytd-allowance').val(),
            		ytdTax:$('.ytd-tax').val(),
            	},
            	success:function(response){
            		if(response.status == "success"){
            			toast_options(4000);
                        toastr.success('The year total deduction information was successfully updated.');
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
            		}
            		else{
            			render_response('.year-total-deduction-warning',response.msg, "danger")
                        loadingUpdateYTD = false;
                        change_button_to_default(btnName, 'Update');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateYTD = false;
                    change_button_to_default(btnName, 'Update');
            	}
            })
		}
	})



	$(document).on('keydown',".float-only",function (e) {

        // for decimal pint
        if (e.keyCode == "190") {
            if ($(this).val().replace(/[0-9]/g, "") == ".") {
            return false;  
            }
        }

        // Allow: backspace, delete, tab, escape, enter , F5
        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
             // let it happen, don't do anything
             return;
        }
            // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

        // for security purpose return false
    $(document).on('paste',".float-only",function(){
        return false;
    });


    $(document).on('input',".float-only", function(){
        if ($(this).attr("maxlength") != 9){
            if ($(this).val().length > 9){
                $(this).val($(this).val().slice(0,-1));
            }
            $(this).attr("maxlength","9");
        }

    });

    function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})