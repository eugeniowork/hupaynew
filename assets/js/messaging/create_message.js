$(document).ready(function(){
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
                    $('.to-name').typeahead({
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
    var empName = "";
    $('.to-name').on('typeahead:selected', function(evt, item) {
        empName = item
    })
    //for send message start
    var loadingSendMsg = false;
    $('.send-message-btn').on('click',function(){
    	var btnName = this;
    	if(!loadingSendMsg){
			loadingSendMsg = true;
			$(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.send-message-warning').empty();
            $.ajax({
            	url:base_url+'messaging_controller/sendMessage',
            	type:'post',
            	dataType:'json',
            	data:{
            		to:empName,
            		subject:$('.subject').val(),
            		message:$('.message').val(),
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
            			render_response('.send-message-warning',response.msg, "danger")
                        loadingSendMsg = false;
                        change_button_to_default(btnName, 'Send');
            		}
            	},
            	error:function(response){
            		toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingSendMsg = false;
                    change_button_to_default(btnName, 'Send');
            	}
            })
        }

    })
    //for send message end
})