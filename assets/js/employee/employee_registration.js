$(document).ready(function(){
	$('.datepicker').datepicker("option","defaultDate", new Date());

	//for registration start

	var loadingRegister = false;
	$('.register-btn').on('click',function(e){
		e.preventDefault();
		var btnName = this;
		if(!loadingRegister){
			loadingRegister = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');

			$('.add-employee-warning').empty();
			var form = $('.new-employee-form');
			$.ajax({
				url:base_url+'employee_controller/addEmployee',
				type:'post',
				dataType:'json',
				data:form.serialize(),
				success:function(response){
					if(response.status == "success"){
						toast_options(4000);
	                    toastr.success(response.msg);
	                    setTimeout(function(){
	                        window.location.reload();
	                    },1000)
					}
					else{
						render_response('.add-employee-warning',response.msg, "danger")
						loadingRegister = false;
                    	change_button_to_default(btnName, 'Register');
					}
				},
				error:function(response){
					toast_options(4000);
	                toastr.error("There was a problem, please try again!");
	                loadingRegister = false;
                    change_button_to_default(btnName, 'Register');
				}
			})
		}
	})

	//for add remove pet start
	// $('.add-pet-btn').on('click',function(){
	// 	$('.pet-type-2').show();
	// 	$(this).hide();
	// 	$('.pet-div').empty()
	// })
	// $('.remove-pet-btn').on('click',function(){
	// 	$('.pet-type-2').hide();
	// 	$('.add-pet-btn').show();
	// })
	 var has_add_pet = false;
    var maximum_pet_count = 2;
    var maximum_pet_counter = 1;

    $(".add-pet-btn").on("click",function(){
    	//alert("HELLO WORLD!");


    	if (has_add_pet == false){

	    	var html = "";
	    	html += '<div class="row">';	
				html += '<div class="col-lg-4">';						
					html += '<span>Pet Type </span>';
					html += '<input type="text" name="petType[]" class="text-only form-control" placeholder="Enter Pet Type (Dog/Cat/etc.)">';
				html += '</div>';
				html += '<div class="col-lg-4">';						
					html += '<span>Pet Name </span>';
					html += '<input type="text" name="petName[]" class="text-only form-control" placeholder="Enter Pet Name">';
				html += '</div>';

				html += '<div class="col-lg-1">';
					html +='<span>&nbsp;</span>';				
					html += '<button type="button" class="remove-pet-btn btn btn-danger " >Remove</button>';
				html += '</div>';
			html += '</div>';

			$(".pet-type-2").html(html);

			maximum_pet_counter++;

			if (maximum_pet_count == maximum_pet_counter){

				$(this).attr("disabled","disabled");
				has_add_pet = true;
			}
		}
		$('.add-pet-btn').hide();
    });


    $(".pet-type-2").on("click",".remove-pet-btn",function(){
    	$('.add-pet-btn').show();
    	$(this).closest("div").parent("div").remove();
    	maximum_pet_counter--;
    	$(".add-pet-btn").removeAttr("disabled");
    	has_add_pet = false;
    });
	//for add remove pet end



	//for get department list start
	get_department_list();
	function get_department_list(){
		//$('.department').empty();
		$.ajax({
			url:base_url+'department_controller/getDepartListForDropdown',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('.department').append('<option selected disabled>Select Department</option>')
					$('.department').append(response.finalData);
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
	//for get department list end

	//for getting position list start
	$('.department').on('change',function(){
		var id = $(this).val();
		$('.position-dropdown').empty();
		$.ajax({
			url:base_url+'position_controller/getPositionListForDropDown',
			type:'post',
			dataType:'json',
			data:{
				id:id
			},
			success:function(response){
				if(response.status == "success"){
					$('.position-dropdown').append('<option selected disabled>Select Position</option>')
					$('.position-dropdown').append(response.finalData);
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
	//for getting position list end

	//for get working hours start
	get_working_hours();
	function get_working_hours(){
		$('.working-hours').empty();
		$.ajax({
			url:base_url+'working_hours_controller/getWorkingHoursDropDown',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('.working-hours').append('<option selected disabled>Select Working Hours</option>')
					$('.working-hours').append(response.finalData);
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
	//for get working hours end

	//for getting employee head start
	get_emp_name_list();
	function get_emp_name_list(){
        $.ajax({
            url:base_url+'employee_controller/getEmployeeNames',
            type:'get',
            dataType:'json',
            success:function(response){
                if(response.status == "success"){
                    emp_name_list = response.employeeNames
                    var emp_name_list = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.whitespace,
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        local: emp_name_list
                    });
                    $('.heads-name').typeahead({
                        hint: true,
                        highlight: true, /* Enable substring highlighting */
                        minLength: 1 /* Specify minimum characters required for showing result */
                    },
                    {
                        name: 'emp_name',
                        source: emp_name_list
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
	//for getting employee head end

	//for getting company list start
	get_company();
    function get_company(){
        $('.company').empty();
		$.ajax({
			url:base_url+'company_controller/getAllCompanyForSelectDropDown',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('.company').append('<option selected disabled>Select Company</option>')
					$('.company').append(response.finalData);
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
	//for getting company list end

	//for working days start
	get_working_days();
    function get_working_days(){
        $('.working-days').empty();
		$.ajax({
			url:base_url+'working_days_controller/getWorkingDaysForDropDown',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('.working-days').append('<option selected disabled>Select Working Days</option>')
					$('.working-days').append(response.finalData);
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
	//for working days end

	//for educational attainment start
	$('.education-attainment').on('change',function(){
		var val = $(this).val();
		$('.education-div').empty();
		var html = "";
		if(val == ""){
			$('.education-div').empty();
		}
		else if(val == "Secondary"){
			html = "";
			html +='<div class="row">';
				html +='<div class="col-lg-12">';
					html += '<label style="color: #27ae60 "><i>Secondary Information</i></label>';
					html +='</div>';
				html +='<div class="col-lg-4">';
				  	html +='</span> School Name:&nbsp;<span class="text-danger">*</span></label>';
				  	html +='<input type="text" name="school_name[]" class="form-control" placeholder="Enter School Name"/>';
				html +='</div>';
				html +='<div class="col-lg-2">';
				  	html +='<span> Year From:&nbsp;<span class="text-danger">*</span></span>';
				  	html +='<input type="text" name="year_from[]" class="year-only form-control" placeholder="Year from"/>';
				html +='</div>';
				html +='<div class="col-lg-2">';
				  	html +='<span> Year To:&nbsp;<span class="text-danger">*</span></span>';
				  	html +='<input type="text" name="year_to[]" class="year-only form-control"  placeholder="Year to"/>';
				html +='</div>';
			html +='</div>';
			$('.education-div').append(html);
		}
		else{
			html = "";
			html +='<div class="row">';
				html +='<div class="col-lg-12">';
					html += '<label style="color: #27ae60 "><i>Secondary Information</i></label>';
					html +='</div>';
				html +='<div class="col-lg-4">';
				  	html +='</span> School Name:&nbsp;<span class="text-danger">*</span></label>';
				  	html +='<input type="text" name="school_name[]" class="form-control" placeholder="Enter School Name"/>';
				html +='</div>';
				html +='<div class="col-lg-4">';
				  	html +='<span> Year From:&nbsp;<span class="text-danger">*</span></span>';
				  	html +='<input type="text" name="year_from[]" class="year-only form-control" placeholder="Year from"/>';
				html +='</div>';
				html +='<div class="col-lg-4">';
				  	html +='<span> Year To:&nbsp;<span class="text-danger">*</span></span>';
				  	html +='<input type="text" name="year_to[]" class="year-only form-control"  placeholder="Year to"/>';
				html +='</div>';
			html +='</div>';

			html +='<div class="row">';
				html +='<div class="col-lg-12">';
					html += '<label style="color: #27ae60 "><i>Tertiary Information</i></label>';
					html +='</div>';
				html +='<div class="col-lg-4">';
				  	html +='</span> School Name:&nbsp;<span class="text-danger">*</span></label>';
				  	html +='<input type="text" name="school_name[]" class="form-control" placeholder="Enter School Name"/>';
				html +='</div>';
				html +='<div class="col-lg-3">';
				  	html +='</span> Course:&nbsp;<span class="text-danger">*</span></label>';
				  	html +='<textarea class="form-control" name="course[]" placeholder="Enter Course"></textarea>';
				html +='</div>';
				html +='<div class="col-lg-2">';
				  	html +='<span> Year From:&nbsp;<span class="text-danger">*</span></span>';
				  	html +='<input type="text" name="year_from[]" class="year-only form-control" placeholder="Year from"/>';
				html +='</div>';
				html +='<div class="col-lg-2">';
				  	html +='<span> Year To:&nbsp;<span class="text-danger">*</span></span>';
				  	html +='<input type="text" name="year_to[]" class="year-only form-control"  placeholder="Year to"/>';
				html +='</div>';
				html += '<div class="col-lg-1">';
					html +='<span>&nbsp;</span>';				
					html += '<button type="button" class="add-educational-attainment-btn btn btn-success " >Add</button>';
				html += '</div>';
			html +='</div>';
			
			$('.education-div').append(html);
		}
	})
	$(document).on('click','.add-educational-attainment-btn',function(){
		var html =  "";
		html +='<div class="row">';
			html +='<div class="col-lg-4">';
			  	html +='</span> School Name:&nbsp;<span class="text-danger">*</span></label>';
			  	html +='<input type="text" name="school_name[]" class="form-control" placeholder="Enter School Name"/>';
			html +='</div>';
			html +='<div class="col-lg-3">';
			  	html +='</span> Course:&nbsp;<span class="text-danger">*</span></label>';
			  	html +='<textarea class="form-control" name="course[]" placeholder="Enter Course"></textarea>';
			html +='</div>';
			html +='<div class="col-lg-2">';
			  	html +='<span> Year From:&nbsp;<span class="text-danger">*</span></span>';
			  	html +='<input type="text" name="year_from[]" class="year-only form-control" placeholder="Year from"/>';
			html +='</div>';
			html +='<div class="col-lg-2">';
			  	html +='<span> Year To:&nbsp;<span class="text-danger">*</span></span>';
			  	html +='<input type="text" name="year_to[]" class="year-only form-control"  placeholder="Year to"/>';
			html +='</div>';
			html += '<div class="col-lg-1">';
				html +='<span>&nbsp;</span>';				
				html += '<button type="button" class="remove-educational-attainment-btn btn btn-danger " >Remove</button>';
			html += '</div>';
		html +='</div>';
		
		$('.education-div').append(html);
	})
	$('.education-div').on('click','.remove-educational-attainment-btn',function(){
		$(this).closest("div").parent("div").remove();
	})
	//for educational attainment end

	//for add work start
	$('.add-work-btn').on('click',function(){
		var html = "";
	    html += '<div class="row">';
	      html += '<div class="col-lg-12">';
	        html += '<button class="btn btn-danger btn-sm pull-right remove-work-xp" type="button" >Remove';
	        html += '</button>';
	      html += '</div>';
	    html += '</div>';
	    html +='<div class="row">'; 
	      html +='<div class="col-lg-4">';            
	        html +='<span>Position <span class="text-danger">*</span></span>';
	        html +='<input type="text" name="work_position[]" class="text-only form-control" placeholder="Enter Position" >';
	      html +='</div>';

	      html +='<div class="col-lg-4">';            
	        html +='<span>Company Name <span class="text-danger">*</span></span>';
	        html +='<input type="text" name="company_name[]" class="text-only form-control" placeholder="Enter Company Name" >';
	      html +='</div>';
	      html +='<div class="col-lg-4">';            
	        html +='<span>Job Description</span>';
	        html +='<textarea class="form-control" name="job_description[]" placeholder="Enter job Description" ></textarea>';
	      html +='</div>';
	      html +='<div class="col-lg-2">';
	            html +='<span> Year From:&nbsp;<span class="text-danger">*</span></span>';
	            html +='<input type="text" name="work_year_from[]" class="year-only form-control" placeholder="Year from"/>';
	          html +='</div>';
	          html +='<div class="col-lg-2">';
	            html +='<span> Year To:&nbsp;<span class="text-danger">*</span></span>';
	            html +='<input type="text" name="work_year_to[]" class="year-only form-control" placeholder="Year to"/>';
	          html +='</div>';
	    html +='</div>';

    	$(".add-work-div").append(html);
	})
	$(".add-work-div").on("click",".remove-work-xp",function(){
	    $(this).closest("div").parent("div").next("div").remove();
	    $(this).closest("div").parent("div").remove();
	 });
	//for add work end



	//for get all role start
	get_all_role();
    function get_all_role(){
        $('.role').empty();
		$.ajax({
			url:base_url+'role_controller/getAllRole',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('.role').append('<option selected disabled>Select Role</option>')
					$('.role').append(response.finalData);
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
	//for get all role end
})