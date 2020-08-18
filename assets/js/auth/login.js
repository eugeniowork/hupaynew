$(document).ready(function(){
    get_company();
    function get_company(){
        $.ajax({
            url:base_url+'company_controller/getAllCompanyForDropdown',
            post:'get',
            dataType:'json',
            success:function(response){
                $('.companySelect').empty();
                $('.companySelect').append(
                    '<option value="na" selected disabled>Select Company</option>'
                );
                for(var company = 0; company < response.company.length; company++){
                    var id = response.company[company]['company_id'];
                    var companyName = response.company[company]['company'];
                    $('.companySelect').append(
                        '<option value='+id+'>'+companyName+'</option>'
                    );
                }
            },
            error:function(response){

            }
        })
    }
    var emp_id;
    var forgotPasswordLoading = false;
    var changePasswordLoading = false;
    var loginLoading = false;
    $('.submitForgotBtn').on('click',function(){
        if(!forgotPasswordLoading){
            // $('.modal-body p').text("");
            var btnName = this;
            forgotPasswordLoading = true;
            change_button_to_disabled(btnName)
            $.ajax({
                url:base_url+'login_controller/validateForgotPasswordCodeandUsername',
                type:'post',
                dataType:'json',
                data:{
                    forgotUsername : $('.forgot-username').val(),
                    forgotCode:$('.forgot-code').val(),
                },
                success:function(response){
                    
                    if(response.status == "success"){
                        emp_id = response.emp_id;
                        $('#forgotPasswordModal .submitForgotBtn').remove();
                        $('#forgotPasswordModal .modal-footer').append(
                            '<button type="button" class="btn btn-primary btn-sm form-control submitNewBtn">Submit Changes</button>'
                        )
                        $('#forgotPasswordModal .forgot-warning').remove();
                        $('#forgotPasswordModal .modal-body').append(
                            '<input type="password" class="form-control new-password" placeholder="New Password">'+
                            '<input type="password" class="form-control confirm-password" placeholder="Confirm Password">'
                        )
                        $('#forgotPasswordModal .modal-body').append('<div class="forgot-warning"></div>');
                        $('.forgot-username').prop('disabled',true);
                        $('.forgot-code').prop('disabled',true);
                        $('.forgot-username').css('cursor','not-allowed');
                        $('.forgot-code').css('cursor','not-allowed');
                    }
                    else{
                        forgotPasswordLoading = false;
                        render_response('.forgot-warning',response.msg, "danger")
                        change_button_to_default(btnName, "Submit");
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    forgotPasswordLoading = false;
                    change_button_to_default(btnName, "Submit");
                }
            })
        }
    })
    $(document).on('click','.submitNewBtn',function(){
        if(!changePasswordLoading){
            changePasswordLoading = true;
            var btnName = this;
            change_button_to_disabled(btnName)
            $.ajax({
                url:base_url+'login_controller/validateChangePassword',
                type:'post',
                dataType:'json',
                data:{
                    'empId':emp_id,
                    'newPassword':$('.new-password').val(),
                    'confirmPassword':$('.confirm-password').val(),
                },
                success:function(response){
                    if(response.status == "success"){
                        render_response('.forgot-warning',response.msg, "success")
                        setTimeout(function(){
                            window.location.reload()
                        },1000)
                    }
                    else{
                        changePasswordLoading = false;
                        render_response('.forgot-warning',response.msg, "danger")
                        change_button_to_default(btnName, "Submit Changes");
                    }
                },
                error:function(response){
                    changePasswordLoading = false;
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    change_button_to_default(btnName, "Submit Changes");
                }
            })
        }
        
    })
    $(".modal").on("hidden.bs.modal", function () { 
        $('.modal-body p').text("");
        $('.modal-body input').val("");
    });
    function render_response(div,msg, status){
        $(div).empty();
        $(div).append(
            '<div class="alert alert-'+status+' alert-dismissible fade show">'+
            msg+
            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
            '</div>'
        );
    }
    function change_button_to_disabled(btnName){
        $(btnName).text("");
        $(btnName).attr('disabled', true);
        $(btnName).css('cursor','not-allowed');
        $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
    }
    function change_button_to_default(btnName,btnText){
        $(btnName).text(btnText);
        $(btnName).attr('disabled', false);
        $(btnName).css('cursor','pointer');
        
    }

    //LOGIN
    $('.loginBtn').on('click',function(){
        if(!loginLoading){
            var btnName = this;
            loginLoading = true;
            change_button_to_disabled(btnName)
            $.ajax({
                url:base_url+'login_controller/validateLogin',
                type:'post',
                dataType:'json',
                data:{
                    username:$('.username').val(),
                    password:$('.password').val(),
                    companyId:$('.companySelect').val(),
                },
                success:function(response){
                    if(response.status == "success"){
                        setTimeout(function(){
                            window.location.href = base_url+'dashboard';
                        },1000)
                    }
                    else{
                        loginLoading = false;
                        render_response('.login-warning',response.msg, "danger")
                        change_button_to_default(btnName, "LOG IN");
                    }
                },
                error:function(response){
                    render_response('.login-warning',"There was a problem, please try again!", "danger")
                    forgotPasswordLoading = false;
                    change_button_to_default(btnName, "LOG IN");
                }
            })
        }
        
    })
})
