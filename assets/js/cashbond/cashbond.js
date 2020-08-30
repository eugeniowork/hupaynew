$(document).ready(function(){
    

    get_cashbond_data();

    function get_cashbond_data(){
        //$('.cashbondList tbody').empty();
        $.ajax({
            url:base_url+'cashbond_controller/getCashbondInfo',
            type:'get',
            dataType:'json',
            success:function(response){
                if(response.status == "success"){
                    response.finalCashbondData.forEach(function(data,key){
                        var append = '<tr class='+data.cashbond_id+'>'+
                            '<td>'+data.fullname+'</td>'+
                            '<td>'+data.cashbond_value+'</td>'+
                            '<td>'+data.total_cashbond+'</td>'+
                            '<td>'+
                            '<button id='+data.cashbond_id+' class="edit-cashbond-btn btn btn-sm btn-outline-success" data-toggle="modal" data-target="#editCashbondValueNoModal"><i id='+data.cashbond_id+' class="fas fa-pencil-alt"></i></button>&nbsp;'+
                            '<button id="edit_cashbond" class="btn btn-sm btn-outline-success"><i class="fas fa-eye"></i></button>&nbsp;'+
                            '<button id="edit_cashbond" class="btn btn-sm btn-outline-success"><i class="fas fa-plus-circle"></i></button>&nbsp;'+
                            '<button id="edit_cashbond" class="btn btn-sm btn-outline-success"><i class="fas fa-adjust"></i></button>&nbsp;'+
                            '</td>'+
                        '</tr>';
                        $('#cashbondList tbody').append(append);
                    })
                    $('#cashbondList').dataTable();
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

    $("#float_only").keydown(function (e) {

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
    $("#float_only").on("paste", function(){
        return false;
    });


    $("#float_only").on('input', function(){
        if ($(this).attr("maxlength") != 9){
            if ($(this).val().length > 9){
                $(this).val($(this).val().slice(0,-1));
            }
            $(this).attr("maxlength","9");
        }

    });
    var cashbondValueId = null;
    $(document).on('click','.edit-cashbond-btn',function(e){
        cashbondValueId = e.target.id;
        $('.cashbond-info').hide();
        $('.update-cashbond-btn').hide();
        $('.loading-cashbond').show();
        loadingCashbondValue = false;
        change_button_to_default('.update-cashbond-btn', 'Update');
        $.ajax({
            url:base_url+'cashbond_controller/getEditCashbondData',
            type:'post',
            dataType:'json',
            data:{
                id:cashbondValueId,
            },
            success:function(response){
                if(response.status == "success"){
                    $('.cashbond-info').show();
                    $('.update-cashbond-btn').show();
                    $('.loading-cashbond').hide();
                    $('.cashbond-value').val(response.cashbondValue)
                }
                else{
                    $('.loading-cashbond').show();
                    $('.loading-cashbond').empty();
                    $('.loading-cashbond').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                    cashbondValueId = null;
                }
            },
            error:function(response){
                $('.loading-cashbond').show();
                $('.loading-cashbond').empty();
                $('.loading-cashbond').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                cashbondValueId = null
            }
        })
    })

    var loadingCashbondValue = false;
    $('.update-cashbond-btn').on('click',function(){
        var btnName = this;
        if(!loadingCashbondValue){
            loadingCashbondValue = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $('.update-cashbond-warning').empty();
            $.ajax({
                url:base_url+'cashbond_controller/updateCashbond',
                type:'post',
                dataType:'json',
                data:{
                    id:cashbondValueId,
                    cashbondValue:$('.cashbond-value').val(),
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
                        render_response('.update-cashbond-warning',response.msg, "danger")
                        loadingCashbondValue = false;
                        change_button_to_default(btnName, 'Update');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingCashbondValue = false;
                    change_button_to_default(btnName, 'Update');
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