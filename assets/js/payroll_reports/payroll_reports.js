$(document).ready(function(){
    
    get_payroll_reports();
    function get_payroll_reports(){
        $.ajax({
            url:base_url+'payroll_reports_controller/getPayrollReports',
            type:'get',
            dataType:'json',
            success:function(response){
                $('.table-payroll-reports tbody').append(response.data);
                $('.table-payroll-reports').dataTable({
                    "ordering": false,

                });
            },
            error:function(response){
                toast_options(4000);
                toastr.error("There was a problem, please try again!");
            }
        })
    }
    loadingSendPayrollReports = false;
    $(document).on('click','.send_payroll_reports',function(e){
        var id = e.target.id;
        var btnName = this;
        
        if(!loadingSendPayrollReports){
            loadingSendPayrollReports = true;
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>&nbsp;Sending...</span>');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $.ajax({
                url:base_url+'payroll_reports_controller/sendPayroll',
                type:'post',
                dataType:'json',
                data:{
                    id:id
                },
                success:function(response){
                    if(response.status == "success"){
                        toast_options(4000);
                        toastr.success("Payroll Report has been sent!");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
                    }
                    else{
                        toast_options(4000);
                        toastr.error(response.msg);
                        loadingSendPayrollReports = false;
                        change_button_to_default(btnName, 'Send');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingSendPayrollReports = false;
                    change_button_to_default(btnName, 'Send');
                }
            })
        }
        
    })

    var payrollReportId = null;
    $(document).on('click','.pre_approve_payroll',function(e){
        var id = e.target.id;
        payrollReportId = id;
        // $.ajax({
        //     url:base_url+'payroll_reports_controller/preApprovePayroll',
        //     type:'post',
        //     dataType:'json',
        //     data:{
        //         id:id,
        //     },
        //     success:function(response){

        //     },
        //     error:function(response){

        //     }
        // })
    })

    var loadingPreApprove = false;
    
    $('.pre-approve-payroll-btn').on('click',function(){
        var btnName = this;
        if(!loadingPreApprove){
            loadingPreApprove = true;
            $('.pre-approve-payroll-warning').empty();
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>&nbsp;Validating...</span>');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $.ajax({
                url:base_url+'payroll_reports_controller/preApprovePayroll',
                type:'post',
                dataType:'json',
                data:{
                    password:$('.pre-approve-password').val(),
                    id:payrollReportId,
                },
                success:function(response){
                    if(response.status == "success"){
                        toast_options(4000);
                        toastr.success("Payroll status has been successfully changed into pre approved!");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
                    }
                    else{
                        render_response('.pre-approve-payroll-warning',response.msg, "danger")
                        change_button_to_default(btnName, 'Approve');
                        loadingPreApprove = false;
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    change_button_to_default(btnName, 'Approve');
                    loadingPreApprove = false;
                }
            })
        }
    })


    var approvePayrollId = null;
    $(document).on('click','.approve_payroll',function(e){
        var id = e.target.id;
        approvePayrollId = id;
        
    })
    
    loadingApprove = false;
    $('.approve-payroll-btn').on('click',function(){
        var btnName = this;

        if(!loadingApprove){
            loadingApprove = true;
        
            $('.approve-payroll-warning').empty();
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>&nbsp;Validating...</span>');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $.ajax({
                url:base_url+'payroll_reports_controller/approvePayroll',
                type:'post',
                dataType:'json',
                data:{
                    id:approvePayrollId,
                    password:$('.approve-password').val(),
                },
                success:function(response){
                    if(response.status == "success"){
                        toast_options(4000);
                        toastr.success("Payroll status has been successfully changed into approved!");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
                    }
                    else{
                        render_response('.approve-payroll-warning',response.msg, "danger")
                        change_button_to_default(btnName, 'Approve');
                        loadingApprove = false;
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    change_button_to_default(btnName, 'Approve');
                    loadingApprove = false;
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