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
                            '<button id='+data.cashbond_id+' class="view-cashbond-history btn btn-sm btn-outline-success" data-toggle="modal" data-target="#viewCashbondHistoryModal"><i id='+data.cashbond_id+' class="fas fa-eye"></i></button>&nbsp;'+
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

    var cashbondHistoryId = null;
    $(document).on('click','.view-cashbond-history',function(e){
        cashbondHistoryId = e.target.id;
        $('.cashbond-history-info').hide();
        $('.print-cashbond-history-btn').hide();
        $('.loading-cashbond-history').show();
        $('.cashbond-history-info-employee').empty()
        $('#cashbondHistoryList tbody').empty()
        $('#cashbondHistoryList').DataTable().clear().destroy();
        $.ajax({
            url:base_url+'cashbond_controller/getCashbondHistory',
            type:'post',
            dataType:'json',
            data:{
                id:cashbondHistoryId,
            },
            success:function(response){
                if(response.status == "success"){
                    $('.cashbond-history-info').show();
                    $('.print-cashbond-history-btn').show();
                    $('.loading-cashbond-history').hide();
                    response.finalCashbondHistoryEmployeeData.forEach(function(data,key){
                        var date = '<div class="col-lg-4">'+
                            '<span>Date:</span>'+
                            '<input readonly type="text" class="form-control" value='+data.date+'>'+
                        '</div>';
                        $('.cashbond-history-info-employee').append(date)
                        var name = '<div class="col-lg-4">'+
                            '<span>Employee Name:</span>'+
                            '<input readonly type="text" class="form-control" value="'+data.name+'">'+
                        '</div>';
                        $('.cashbond-history-info-employee').append(name)

                        var interest = '<div class="col-lg-4">'+
                            '<span>Interest Rate:</span>'+
                            '<input readonly type="text" class="form-control" value="'+data.percentage+'">'+
                        '</div>';
                        $('.cashbond-history-info-employee').append(interest)
                        var credits = '<div class="col-lg-4">'+
                            '<span>Total Credits:</span>'+
                            '<input readonly type="text" class="form-control" value="Php '+data.total_credit+'">'+
                        '</div>';
                        $('.cashbond-history-info-employee').append(credits)
                        var debits = '<div class="col-lg-4">'+
                            '<span>Total Debits:</span>'+
                            '<input readonly type="text" class="form-control" value="Php '+data.total_debits+'">'+
                        '</div>';
                        $('.cashbond-history-info-employee').append(debits)
                        var interestTotal = '<div class="col-lg-4">'+
                            '<span>Total Interest Earned:</span>'+
                            '<input readonly type="text" class="form-control" value="Php '+data.total_interest+'">'+
                        '</div>';
                        $('.cashbond-history-info-employee').append(interestTotal)
                    })

                    response.finalCashbondHistoryData.forEach(function(data,key){
                        var reference = '';
                        if(data.amount_withdraw !=0){
                            if(data.reference_no != ""){
                                reference += '<br/>'+
                                '<small class="ref-no-text-'+data.emp_cashbond_history+'" style="background-color: #158cba;color:#fff">'+
                                    data.reference_no+
                                '</small>';
                                reference +='<input type="text" value='+data.reference_no+' class="ref-no ref-no-'+data.emp_cashbond_history+' form-control" placeholder="Enter ref no">'
                            }
                            else{
                                reference += '<br/>'+
                                '<small class="ref-no-text-'+data.emp_cashbond_history+'" style="background-color: #158cba;color:#fff">No Ref No.</small>';
                                reference +='<input type="text" class="ref-no ref-no-'+data.emp_cashbond_history+' form-control" placeholder="Enter ref no">'
                            }
                            reference +='&nbsp;<button id='+data.emp_cashbond_history+' class="btn btn-sm btn-link edit-ref-no-btn edit-ref-no-btn-'+data.emp_cashbond_history+'"><i id='+data.emp_cashbond_history+' class="fas fa-edit"></i></button>'
                        }
                        var append = '<tr class='+data.emp_cashbond_history+'>'+
                            '<td>'+data.posting_date+'</td>'+
                            '<td>Php. '+data.cash_deposit+'</td>'+
                            '<td>Php. '+data.interest+'</td>'+
                            '<td>Php. '+
                                data.amount_withdraw+
                                reference
                            +'</td>'+
                            '<td>Php. '+data.cashbond_balance+'</td>'+
                        '</tr>';
                        $('#cashbondHistoryList tbody').append(append);
                    })
                    $('#cashbondHistoryList').dataTable({
                        "ordering": false,
                    });
                }
                else{
                    $('.loading-cashbond-history').show();
                    $('.loading-cashbond-history').empty();
                    $('.loading-cashbond-history').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                    cashbondHistoryId = null;
                }
            },
            error:function(response){
                $('.loading-cashbond-history').show();
                $('.loading-cashbond-history').empty();
                $('.loading-cashbond-history').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                cashbondHistoryId = null
            }
        })

    })
    var cashbondHistoryUpdateId = null;
    $(document).on('click','.edit-ref-no-btn',function(e){
        var id = e.target.id;
        var refNo = $('.ref-no-text-'+id).text();
        cashbondHistoryUpdateId = id;
        $('.ref-no-'+id).show();
        $('.ref-no-'+id).focus();
        if($('.ref-no-text-'+id).text() == "No Ref No."){
            refNo = "";
        }

        $('.ref-no-'+id).val(refNo);
        $('.ref-no-text-'+id).hide();
        $('.edit-ref-no-btn-'+id).hide();
    })
    $(document).on('focusout','.ref-no',function(e){
        $('.ref-no-'+cashbondHistoryUpdateId).hide();
        $('.ref-no-'+cashbondHistoryUpdateId).val('');
        $('.ref-no-text-'+cashbondHistoryUpdateId).show();
        $('.edit-ref-no-btn-'+cashbondHistoryUpdateId).show();
        cashbondHistoryUpdateId = null;
    })
    $(document).on('keydown','.ref-no',function(e){
        
        if (e.keyCode == 13){
            
            var newRefNo = $('.ref-no-'+cashbondHistoryUpdateId).val();

            $.ajax({
                url:base_url+'cashbond_controller/updateCashbondHistoryRefNo',
                type:'post',
                dataType:'json',
                data:{
                    id:cashbondHistoryUpdateId,
                    newRefNo:newRefNo,
                },
                success:function(response){
                    if(response.status == "success"){
                        $('.ref-no-text-'+cashbondHistoryUpdateId).text(newRefNo);
                        $('.ref-no-'+cashbondHistoryUpdateId).focusout();
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
            
            //console.log(newRefNo)
        }
        
    })
    function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})