$(document).ready(function(){

    get_emp_name_list();
    get_cut_off_list();
    var emp_name_list;
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
                    $('.typeahead').typeahead({
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

    function get_cut_off_list(){
        $.ajax({
            url:base_url+'cut_off_controller/getAllCutOffPeriod',
            type:'get',
            dataType:'json',
            success:function(response){
                if(response.status == "success"){
                    response.cutOffList.forEach(function(data, key){
                        $('.cut-off-period').append('<option value="'+data.dateFrom+' - '+data.dateTo+'">'+data.dateFrom+' - '+data.dateTo+'</option>');
                    })
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
    $('.year').keyup(function(e){
        if (/\D/g.test(this.value)){
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
        }
        if($(this).val().length > 4){
            return false;
        }
    });
    var empName = "";
    $('.search-payroll-info-btn').on('click',function(){
        $('.loading-generating').show();
        $('.generated-payroll-info-body').empty()
        $.ajax({
            url:base_url+'payroll_controller/generatePayrollPerEmployee',
            type:'post',
            dataType:'json',
            data:{
                empName: empName,
                cutOffPeriod: $('.cut-off-period').val(),
                year:$('.year').val(),
            },
            success:function(response){
                if(response.status == "success"){
                    $('.loading-generating').hide();
                    response.employeePayrollData.forEach(function(data,key){
                        var incentives = '';
                        
                        var present_amount = '';
                        var absences = '';
                        if(data.date_payroll <= "2020-01-30"){
                            incentives = '<p>Incentives: '+data.incentives+'</p>';
                            absences = '<div class="col-lg-3" >'+
                                '<span>Absences:</span>'+
                                '<input readonly class="form-control " value='+data.absences+' type="text" >'+
                            '</div>';
                        }
                        if(data.date_payroll >= "2020-02-15"){
                            present_amount = '<div class="col-lg-3">'+
                                '<span>Present:</span>'+
                                '<input readonly class="form-control " value='+data.present_amount+' type="text" >'+
                            '</div>';
                        }
                        var regularOT = '<input readonly class="form-control " value='+data.regularOT+' type="text" >'
                        var restdayOT = '<input readonly class="form-control " value='+data.restdayOT+' type="text" >'
                        var reg_holidayOT = '<input readonly class="form-control " value='+data.reg_holidayOT+' type="text" >'
                        var special_holidayOT = '<input readonly class="form-control " value='+data.special_holidayOT+' type="text" >'
                        var rd_reg_holidayOT = '<input readonly class="form-control " value='+data.rd_reg_holidayOT+' type="text" >'
                        var rd_special_holidayOT = '<input readonly class="form-control " value='+data.rd_special_holidayOT+' type="text" >'
                        var tardiness = '<input readonly class="form-control " value='+data.tardiness+' type="text" >'
                        var totalGrossIncome = '<input readonly class="form-control " value='+data.totalGrossIncome+' type="text" >'
                        var adjustmentEarnings = '<input readonly class="form-control " value='+data.adjustmentEarnings+' type="text" >'

                        var sssDeduction = '<input readonly class="form-control " value='+data.sssDeduction+' type="text" >'
                        var sssLoan = '<input readonly class="form-control " value='+data.sssLoan+' type="text" >'
                        var philhealthDeduction = '<input readonly class="form-control " value='+data.philhealthDeduction+' type="text" >'
                        var pagibigDeduction = '<input readonly class="form-control " value='+data.pagibigDeduction+' type="text" >'
                        var pagibigLoan = '<input readonly class="form-control " value='+data.pagibigLoan+' type="text" >'
                        var cashBond = '<input readonly class="form-control " value='+data.cashBond+' type="text" >'
                        var cashAdvance = '<input readonly class="form-control " value='+data.cashAdvance+' type="text" >'
                        var totalDeductions = '<input readonly class="form-control " value='+data.totalDeductions+' type="text" >'
                        var adjustmentDeductions = '<input readonly class="form-control " value='+data.adjustmentDeductions+' type="text" >'

                        var tax = '<input readonly class="form-control " value='+data.tax+' type="text" >'
                        var nontaxAllowance = '<input readonly class="form-control " value='+data.nontaxAllowance+' type="text" >'
                        var adjustmentAfter = '<input readonly class="form-control " value='+data.adjustmentAfter+' type="text" >'
                        var net_pay = '<input readonly class="form-control " value='+data.net_pay+' type="text" >'
                        var button = '<button type="button" class="btn btn-success" id="print-empployee-payslip-btn">Print Payslip</button>';
                        var remarks = '<textarea readonly class="form-control value='+data.remarks+' placeholder="Input Remarks" rows="3"></textarea>'
                        if(data.is_cut_off == 1){
                            regularOT = '<input class="earnings regOT float_only form-control " value='+data.regularOT+' type="text" >'
                            restdayOT = '<input class="earnings restdayOT float_only form-control " value='+data.restdayOT+' type="text" >'
                            reg_holidayOT = '<input class="earnings reg_holidayOT float_only form-control " value='+data.reg_holidayOT+' type="text" >'
                            special_holidayOT = '<input class="earnings special_holidayOT float_only form-control " value='+data.special_holidayOT+' type="text" >'
                            rd_reg_holidayOT = '<input class="earnings rd_reg_holidayOT float_only form-control " value='+data.rd_reg_holidayOT+' type="text" >'
                            rd_special_holidayOT = '<input class="earnings rd_special_holidayOT float_only form-control " value='+data.rd_special_holidayOT+' type="text" >'
                            tardiness = '<input class="earnings tardiness float_only form-control " value='+data.tardiness+' type="text" >'
                            absences = '<div class="col-lg-3" >'+
                                '<span>Absences:</span>'+
                                '<input class="absences earnings float_only form-control " value='+data.absences+' type="text" >'
                            '</div>';
                            present_amount = '<div class="col-lg-3">'+
                                '<span>Present:</span>'+
                                '<input class="present_amount earnings float_only form-control " value='+data.present_amount+' type="text" >'+
                            '</div>';
                            totalGrossIncome = '<input class="totalGrossIncome float_only form-control " value='+data.totalGrossIncome+' type="text" >'
                            
                            sssDeduction = '<input class="sssDeduction float_only form-control " value='+data.sssDeduction+' type="text" >'
                            sssLoan = '<input class="sssLoan float_only form-control " value='+data.sssLoan+' type="text" >'
                            philhealthDeduction = '<input class="philhealthDeduction float_only form-control " value='+data.philhealthDeduction+' type="text" >'
                            pagibigDeduction = '<input class="pagibigDeduction float_only form-control " value='+data.pagibigDeduction+' type="text" >'
                            pagibigLoan = '<input class="pagibigLoan float_only form-control " value='+data.pagibigLoan+' type="text" >'
                            cashBond = '<input class="cashBond float_only form-control " value='+data.cashBond+' type="text" >'
                            cashAdvance = '<input class="cashAdvance float_only form-control " value='+data.cashAdvance+' type="text" >'
                            totalDeductions = '<input class="adjustmentEarnings float_only form-control " value='+data.totalDeductions+' type="text" >'
                            adjustmentDeductions = '<input class="adjustmentDeductions float_only form-control " value='+data.adjustmentDeductions+' type="text" >'
                            tax = '<input class="tax float_only form-control " value='+data.tax+' type="text" >'
                            nontaxAllowance = '<input class="nontaxAllowance float_only form-control " value='+data.nontaxAllowance+' type="text" >'
                            adjustmentAfter = '<input class="adjustmentAfter float_only form-control " value='+data.adjustmentAfter+' type="text" >'
                            net_pay = '<input class="net_pay float_only form-control " value='+data.net_pay+' type="text" >'
                            button = '<button type="button" class="btn btn-success" id="update-payroll-info-btn">Update Payroll Info</button>'
                            remarks = '<textarea class="form-control remarks value='+data.remarks+' placeholder="Input Remarks" rows="3"></textarea>'
                        }
                        var append = '<hr><div class="generated-payroll-info-content">'+
                            '<div class="d-flex flex-row align-items-center justify-content-center">'+
                                '<img src="'+base_url+'assets/images/img/logo/lloyds logo.png" alt="Logo" class="payroll-logo">'+
                                '&nbsp;<span class="title-lloyds">LLOYDS FINANCING CORPORATION</span>'+
                            '</div>'+
                            '<div class="row">'+
                                '<div class="col-lg-12">'+
                                    '<span class="pull-right titles">Payroll Period: '+data.cutOffPeriod+'</span>'+
                                    '<span class="pull-left titles">Employee No: '+data.emp_id+'</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="row">'+
                                '<div class="col-lg-12">'+
                                    '<span class="pull-right titles">Basic Pay: '+data.basic_pay+'</span>'+
                                    
                                    '<span class="pull-left titles">Department: '+data.department+'</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="row">'+
                                '<div class="col-lg-12">'+
                                    '<span class="pull-right titles">Tax Code: '+data.tax_code+'</span>'+
                                    '<span class="pull-left titles">Name: '+data.name+'</span>'+
                                '</div>'+
                            '</div>'+
                            '<hr>'+
                            incentives+
                            '<span>Earnings</span>'+
                            '<div class="row earnings ">'+
                                '<div class="col-lg-3">'+
                                    '<span>Regular OT:</span>'+
                                    regularOT+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Restday OT:</span>'+
                                    restdayOT+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Regular Holiday OT:</span>'+
                                    reg_holidayOT+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Special Holiday OT:</span>'+
                                    special_holidayOT+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>RD/Regular Holiday OT:</span>'+
                                    rd_reg_holidayOT+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>RD/Special Holiday OT:</span>'+
                                    rd_special_holidayOT+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Tardiness:</span>'+
                                    tardiness+
                                '</div>'+
                                present_amount+
                                '<div class="col-lg-3">'+
                                    '<span>Gross Income:</span>'+
                                    totalGrossIncome+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Adjustment:</span>'+
                                    adjustmentEarnings+
                                '</div>'+
                            '</div>'+   
                            '<span>Deductions</span>'+
                            '<div class="row deductions ">'+
                                '<div class="col-lg-3">'+
                                    '<span>SSS Contribution:</span>'+
                                    sssDeduction+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>SSS Loan:</span>'+
                                    sssLoan+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Philhealth Contribution:</span>'+
                                    philhealthDeduction+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Pag-ibig Contribution:</span>'+
                                    pagibigDeduction+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Pag-ibig Loan:</span>'+
                                    pagibigLoan+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Cashbond:</span>'+
                                    cashBond+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Cash Advance:</span>'+
                                    cashAdvance+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Total Deductions:</span>'+
                                    totalDeductions+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Adjustment:</span>'+
                                    adjustmentDeductions+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Tax:</span>'+
                                    '<span>With holding tax:</span>'+
                                    tax+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Allowance:</span>'+
                                    '<span>Nontax allowance:</span>'+
                                    nontaxAllowance+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Adjustment after:</span>'+
                                    '<span>adjustment:</span>'+
                                    adjustmentAfter+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>additional:</span>'+
                                    '<span>incentives: <span>'+data.incentives+'</span></span>'+
                                    '<span>current cutoff 13th:</span>'+
                                    '<span>'+data.cutOffPeriod+'(BASIC): '+data.current_cut_off_13th_basic_value+'</span>'+
                                    '<span>'+data.cutOffPeriod+'(ALLOWANCE): '+data.current_cut_off_13th_allowance_value+'</span>'+
                                    '<span>TOTAL BASIC PAY:</span>'+
                                    '<span>'+data.total_13_basic_pay+':</span>'+
                                    '<span>TOTAL ALLOWANCE PAY:</span>'+
                                    '<span>'+data.total_13_allowance_pay+':</span>'+
                                '</div>'+
                                '<div class="col-lg-3">'+
                                    '<span>Net Pay:</span>'+
                                    net_pay+
                                '</div>'+
                                
                            '</div>'+
                            '<div class="row">'+
                                '<div class="col-lg-12">'+
                                    remarks+
                                '</div>'+
                            '</div>'+
                            button+
                            '<input type="hidden" class="form-control emp_id" value='+data.emp_id+' class="form-control">'+
                            '<input type="hidden" class="form-control lastTotalGrossIncome" value='+data.last_total_gross_income+' type="text" class="form-control">'+
                            '<input type="hidden" class="form-control total13BasicPay" value='+data.total_13_basic_pay+' type="text" class="form-control">'+
                            '<input type="hidden" class="form-control total13AllowancePay" value='+data.total_13_allowance_pay+' type="text" class="form-control">'+
                            '<input type="hidden" class="form-control incentives" value='+data.incentives+' type="text" class="form-control">'+
                            '<input type="hidden" class="form-control basic-pay" value='+data.basic_pay+' type="text" class="form-control">'+
                            '<input type="hidden" class="form-control name" value='+data.name+' type="text" class="form-control">'+
                            '<input type="hidden" class="form-control date-payroll" value='+data.date_payroll+' type="text" class="form-control">'+
                        '</div>';
                        $('.generated-payroll-info-body').append(append)
                    })
                }
            },
            error:function(response){

            }
        })
    })
    $('.typeahead').on('typeahead:selected', function(evt, item) {
        empName = item
    })
    $(document).on('keydown','.float_only',function (e) {


        //	alert(e.keyCode);
        if ($(this).val() == 0 && e.keyCode == "9") {
            $(this).val("0");
        }

        //var new_value =0;
        else if ($(this).val() == 0) {
            $(this).val($(this).val().slice(1,-1));
        }


        if (e.keyCode == "190" && $(this).val() == 0) {
            $(this).val("0.");
        }

        // for decimal pint
        if (e.keyCode == "190") {
            if ($(this).val().replace(/[0-9]/g, "") == ".") {
                return false;  
            }
        }
        if (e.keyCode == "189" || e.keyCode == "173") {
            if ($(this).val().replace(/[0-9]/g, "") == "-") {
                return false;  
            }
        }
        // Allow: backspace, delete, tab, escape, enter , F5
        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190,189,173]) !== -1 ||
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
    $(document).on("paste",'.float_only',function(){
        return false;
    });
    // $(document).on('input','.float_only', function(){
    //     if ($(this).attr("maxlength") != 10){
    //         if ($(this).val().length > 10){
    //              $(this).val($(this).val().slice(0,-1));
    //         }
    //         $(this).attr("maxlength","10");
    //     }

    // });
    $(document).on('change keyup paste', '.earnings',function(){
        if ($(this).val() == ""){
            $(this).val(0);
        }
        var emp_id = $('.emp_id').val();
        var basicPay = $('.basic-pay').val();
        var hasTax = 1;
        var emp_name = $('.name').val();
        var last_total_gross_income  = $('.lastTotalGrossIncome').val();
        var cutOff_day = $('.date-payroll').val();

        var regOt = $(".regOT").val();
        var rdOT = $(".restdayOT").val();
        var regHolidayOT = $(".reg_holidayOT").val();
        var specialHolidayOT = $(".special_holidayOT").val();
        var rd_regHolidayOT = $(".rd_reg_holidayOT").val();
        var rd_specialHolidayOT = $(".rd_special_holidayOT").val();
        var tardiness = $(".tardiness").val();
        var absences = 0;
        var present = $(".present_amount").val();
        var adjustmentEarnings = $(".adjustmentEarnings").val();

        var sssContribution = $(".sssDeduction").val();
        // var sssLoan = $("input[name='sssLoan']").val();
        var philhealthContribution = $(".philhealthDeduction").val();
        var pagibigContribution = $(".pagibigDeduction").val();

        var incentives = $('.incentives').val();
        var total_13_basic_pay = $('.total13BasicPay').val();
        var total_13_allowance_pay = $('.total13AllowancePay').val();
        console.log(parseFloat(convertToZero(regOt)))
        var totalGrossIncome = parseFloat(convertToZero(present)) + parseFloat(convertToZero(regOt)) + parseFloat(convertToZero(rdOT)) + parseFloat(convertToZero(regHolidayOT)) + parseFloat(convertToZero(specialHolidayOT)) + parseFloat(convertToZero(rd_regHolidayOT)) + parseFloat(convertToZero(rd_specialHolidayOT)) - parseFloat(convertToZero(tardiness)) - parseFloat(convertToZero(absences)) + parseFloat(convertToZero(adjustmentEarnings));
        $('.totalGrossIncome').val(totalGrossIncome);
        totalGrossIncome = totalGrossIncome.toString().split('e');
		totalGrossIncome = Math.round(+(totalGrossIncome[0] + 'e' + (totalGrossIncome[1] ? (+totalGrossIncome[1] + 2) : 2)));

		totalGrossIncome = totalGrossIncome.toString().split('e');
        totalGrossIncome =  (+(totalGrossIncome[0] + 'e' + (totalGrossIncome[1] ? (+totalGrossIncome[1] - 2) : -2))).toFixed(2);
        
        if (hasTax == 1) {
            $.ajax({
                url:base_url+'payroll_controller/appendTaxValue',
                type:'post',
                dataType:'json',
                data:{
                    totalGrossIncome:totalGrossIncome,
                    empId:emp_id,
                    lastTotalGrossIncome:last_total_gross_income,
                    cutOffDay:cutOff_day,
                    sssContribution:sssContribution,
                    pagibigContribution:pagibigContribution,
                    philhealthContribution:philhealthContribution
                },
                success:function(response){
                    if(response.status == "success"){
                        var totalDeductions = $('.adjustmentEarnings').val();
                        var nontaxAllowance = $('.nontaxAllowance').val();
                        var adjustmentAfter = $('.adjustmentAfter').val();
                        var netPay = parseFloat(totalGrossIncome) - parseFloat(convertToZero(totalDeductions)) -  parseFloat(response.tax) + parseFloat(convertToZero(nontaxAllowance)) + parseFloat(convertToZero(adjustmentAfter));

                        netPay += parseFloat(convertToZero(incentives));
                        netPay += parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay));
                        
                        // for 2 decimal places
                        netPay = netPay.toString().split('e');
                        netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

                        netPay = netPay.toString().split('e');
                        final_netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);
                        $('.net_pay').val(final_netPay);
                    }
                },
                error:function(response){

                }
            })
        }
    })
    function convertToZero(value){

        if (value == ""){
            value = 0;
        }

        return value;
    }
})