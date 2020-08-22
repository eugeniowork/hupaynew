$(document).ready(function(){
    

    get_atm_account_no_list();
    function get_atm_account_no_list(){
        $('#atmAccountNoList tbody').empty()
        $.ajax({
            url:base_url+'employee_controller/getAtmAccountNoList',
            type:'get',
            dataType:'json',
            success:function(response){
                if(response.status =="success"){
                    response.finalListOfEmployeeAtm.forEach(function(data,key){
                        if(data.action == "yes"){
                            var tr = '<tr id='+data.emp_id+'>'+
                                '<td>'+data.emp_name+'</td>'+
                                '<td >'+data.atmAccountNumber+'</td>'+
                                '<td style="text-align:center">'+
                                    '<button data-toggle="modal" data-target="#editAtmNoModal" class="btn btn-outline-success btn-sm edit-account-no-btn" id='+data.emp_id+'><i id='+data.emp_id+' class="fas fa-pencil-alt"></i>&nbsp;Edit</button>'
                                '</td>'+
                            '</tr>';
                            $('#atmAccountNoList tbody').append(tr)
                        }
                        else{
                            var tr = '<tr id='+data.emp_id+'>'+
                                '<td>'+data.emp_name+'</td>'+
                                '<td>'+data.atmAccountNumber+'</td>'+
                                '<td>No Action</td>'+
                            '</tr>';
                            $('#atmAccountNoList tbody').append(tr)
                        }
                        
                    })
                    $('#atmAccountNoList').dataTable();
                }
            },
            error:function(response){

            }
        })
    }
    var id = null;
    $(document).on('click','.edit-account-no-btn',function(e){
        id = e.target.id;
        $('.atm-account-info').hide();
        $('.update-atm-account-no-btn').hide();
        $('.loading-atm-account-no').show();
        $.ajax({
            url:base_url+'employee_controller/getInformationOfAtmAccount',
            type:'post',
            dataType:'json',
            data:{
                id:id,
            },
            success:function(response){
                if(response.status == "success"){
                    $('.atm-account-info').show();
                    $('.update-atm-account-no-btn').show();
                    $('.loading-atm-account-no').hide();
                    $('.account-no').val(response.atmAccountNumber);
                }
                else{
                    $('.loading-atm-account-no').show();
                    $('.loading-atm-account-no').empty();
                    $('.loading-atm-account-no').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                    id = null;
                }
            },
            error:function(response){
                $('.loading-atm-account-no').show();
                $('.loading-atm-account-no').empty();
                $('.loading-atm-account-no').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                id = null;
            }
        })
    })
    $('.account-no').keydown(function(e){
        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116]) !== -1 ||
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
    })
    $('.account-no').on('input', function(){
        if ($(this).attr("maxlength") != 12){
             if ($(this).val().length > 12){
                 $(this).val($(this).val().slice(0,-1));
             }
            $(this).attr("maxlength","12");
        }

    });
    var loadingUpdateAtm = false;
    $('.update-atm-account-no-btn').on('click',function(){
        var btnName = this;
        if(!loadingUpdateAtm){
            loadingUpdateAtm = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.atm-account-no-warning').empty();
            $.ajax({
                url:base_url+'employee_controller/updateAtmAccountNo',
                type:'post',
                dataType:'json',
                data:{
                    id:id,
                    atmAccountNo:$('.account-no').val(),
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
                        render_response('.atm-account-no-warning',response.msg, "danger")
                        loadingUpdateAtm = false;
                        change_button_to_default(btnName, 'Submit');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingUpdateAtm = false;
                    change_button_to_default(btnName, 'Submit');
                }
            })
        }
    })
    $("#editAtmNoModal").on('hide.bs.modal', function(){
        $('.atm-account-no-warning').empty();
        $('.account-no').val("");
    });
    function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})