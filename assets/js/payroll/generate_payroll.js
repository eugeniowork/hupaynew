$(document).ready(function(){
    var ids = [];
    $('.generate-payroll-btn').on('click',function(){
        var btnName = this;
        Swal.fire({
            html: 'Are you sure you want to generate a payroll?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $('.loading-generating').show();
                $('.generated-payroll-body').empty();
                
                $.ajax({
                    url:base_url+'payroll_controller/generatePayroll',
                    type:'get',
                    dataType:'json',
                    success:function(response){
                        if(response.status == "success"){
                            $('.loading-generating').hide();
                            var submitBtn = '<button class="btn btn-sm btn-success submit-payroll-btn">Submit Payroll</button>';
                            $('.generated-payroll-body').append(submitBtn)
                            response.finalPayrollData.forEach(function(data,key){
                                
                                var logo = '<div class="generated-payroll-content"><div class="d-flex flex-row align-items-center justify-content-center">'+
                                        '<img src="'+base_url+'assets/images/'+data.logo_source+'" class="payroll-logo"/>'+
                                        '&nbsp;<span class="title-lloyds">LLOYDS FINANCING CORPORATION</span>'
                                    +'</div></div>'
                                var basicPay = ""
                                // for basic pay condition
                                if(data.min_wage >= data.row_salary){
                                    basicPay = round((data.ro_salary/2),2)
                                }
                                else{
                                    basicPay = data.basicCutOffPay;
                                }
                                var reg_ot_amount = 0;var rd_ot_amount = 0;var regHoliday_ot_amount = 0;
                                var specialHoliday_ot_amount = 0;var rdRegularHoliday_ot_amount=0;
                                var rdSpecialHoliday_ot_amount = 0;var tardinessAmount = 0;
                                var absencesAmount = 0;var present = 0;var grossIncome = data.totalGrossIncome;
                                var tax = data.tax;var netPay = data.net_pay
                                if(data.bio_id == 0){
                                    reg_ot_amount = data.reg_ot_amount;
                                    rd_ot_amount = data.rd_ot_amount;
                                    regHoliday_ot_amount = data.regHoliday_ot_amount;
                                    specialHoliday_ot_amount = data.specialHoliday_ot_amount;
                                    rdRegularHoliday_ot_amount = data.rdRegularHoliday_ot_amount;
                                    rdSpecialHoliday_ot_amount = data.rdSpecialHoliday_ot_amount;
                                    tardinessAmount = data.tardinessAmount;
                                    absencesAmount = data.absencesAmount;
                                    present = data.present;
                                    grossIncome = data.basicCutOffPay;
                                    tax = 0;
                                    netPay = 0;
                                }
                                var append = '<hr><div class="generated-payroll-content">'+
                                    '<div class="d-flex flex-row align-items-center justify-content-center">'+
                                        '<img src="'+base_url+'assets/images/'+data.logo_source+'" alt="Logo" class="payroll-logo">'+
                                        '&nbsp;<span class="title-lloyds">LLOYDS FINANCING CORPORATION</span>'+
                                    '</div>'+
                                    
                                    '<div class="row">'+
                                        '<div class="col-lg-12">'+
                                            '<span class="pull-right titles">Payroll Period: '+data.payroll_period+'</span>'+
                                            '<span class="pull-left titles">Employee No: '+data.emp_id+'</span>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row">'+
                                        '<div class="col-lg-12">'+
                                            '<span class="pull-right titles">Basic Pay: '+basicPay+'</span>'+
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
                                    '<span>Earnings</span>'+
                                    '<div class="row earnings ">'+
                                       ' <div class="col-lg-3">'+
                                            '<span>Regular OT:</span>'+
                                            '<input id='+data.emp_id+' class="earnings earnings_'+data.emp_id+' regOT_'+data.emp_id+' float_only form-control " value='+reg_ot_amount+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Restday OT:</span>'+
                                            '<input id='+data.emp_id+' class="earnings earnings_'+data.emp_id+' float_only form-control rdOT_'+data.emp_id+'" value='+rd_ot_amount+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Regular Holiday OT:</span>'+
                                            '<input id='+data.emp_id+' class="earnings earnings_'+data.emp_id+' float_only form-control regHolidayOT_'+data.emp_id+'" value='+regHoliday_ot_amount+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Special Holiday OT:</span>'+
                                            '<input id='+data.emp_id+' class="earnings earnings_'+data.emp_id+' float_only form-control specialHolidayOT_'+data.emp_id+'" value='+specialHoliday_ot_amount+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>RD/Regular Holiday OT:</span>'+
                                            '<input id='+data.emp_id+' class="earnings earnings_'+data.emp_id+' float_only form-control rdREgHolidayOT_'+data.emp_id+'" value='+rdRegularHoliday_ot_amount+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>RD/Special Holiday OT:</span>'+
                                            '<input id='+data.emp_id+' class="earnings earnings_'+data.emp_id+' float_only form-control rdSpecialHolidayOT_'+data.emp_id+'" value='+rdSpecialHoliday_ot_amount+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Tardiness:</span>'+
                                            '<input id='+data.emp_id+' class="earnings earnings_'+data.emp_id+' float_only form-control tardiness_'+data.emp_id+'" value='+absencesAmount+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3" styles="display:none">'+
                                            '<span>Absences:</span>'+
                                            '<input id='+data.emp_id+' class="earnings earnings_'+data.emp_id+' float_only form-control absences_'+data.emp_id+'" value='+absencesAmount+' type="text">'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Present:</span>'+
                                            '<input id='+data.emp_id+' class="earnings earnings_'+data.emp_id+' float_only form-control present_'+data.emp_id+'" value='+present+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Gross Income:</span>'+
                                            '<input id='+data.emp_id+' class="input_payroll form-control grossIncome_'+data.emp_id+'" value='+grossIncome+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Adjustment:</span>'+
                                            '<input id='+data.emp_id+' class="earnings earnings_'+data.emp_id+' float_only form-control adjustment_'+data.emp_id+'" value="0" type="text" >'+
                                        '</div>'+
                                    '</div>'+
                                    '<span>Deductions</span>'+
                                    '<div class="row earnings ">'+
                                        '<div class="col-lg-3">'+
                                            '<span>SSS Contribution:</span>'+
                                            '<input id='+data.emp_id+' class="deductions deductions_'+data.emp_id+' float_only form-control sssContrib_'+data.emp_id+'" value='+data.sssContribution+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>SSS Loan:</span>'+
                                            '<input id='+data.emp_id+' class="deductions deductions_'+data.emp_id+' float_only form-control sssLoan_'+data.emp_id+'" value='+data.sss_loan_amount+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Philhealth Contribution:</span>'+
                                            '<input id='+data.emp_id+' class="deductions deductions_'+data.emp_id+' float_only form-control philhealthContrib_'+data.emp_id+'" value='+data.philhealthContribution+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Pag-ibig Contribution:</span>'+
                                            '<input id='+data.emp_id+' class="deductions deductions_'+data.emp_id+' float_only form-control pagibigContrib_'+data.emp_id+'" value='+data.pagibigContribution+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Pag-ibig Loan:</span>'+
                                            '<input id='+data.emp_id+' class="deductions deductions_'+data.emp_id+' float_only form-control pagibigLoan_'+data.emp_id+'" value='+data.pagibig_loan_amount+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Cashbond:</span>'+
                                            '<input id='+data.emp_id+' class="deductions deductions_'+data.emp_id+' float_only form-control cashBond_'+data.emp_id+'" value='+data.cashBond+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Cash Advance:</span>'+
                                            '<input id='+data.emp_id+' class="input_payroll deductions deductions_'+data.emp_id+' form-control cashAdvance_'+data.emp_id+'" value='+data.totalCashAdvance+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Total Deductions:</span>'+
                                            '<input class="input_payroll form-control totalDeductions_'+data.emp_id+'" value='+data.totalDeduction+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Adjustment:</span>'+
                                            '<input id='+data.emp_id+' class="deductions deductions_'+data.emp_id+' float_only form-control adjustmentDeduction_'+data.emp_id+'" value="0" type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Tax:</span>'+
                                            '<span>With holding tax:</span>'+
                                            '<input id='+data.emp_id+' class="input_payroll form-control witholdingTax_'+data.emp_id+'" value='+tax+' type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Allowance:</span>'+
                                            '<span>Nontax allowance:</span>'+
                                            '<input id='+data.emp_id+' class="float_only form-control deductions_'+data.emp_id+' nontaxAllowance_'+data.emp_id+'" value='+data.present_allowance+' type="text">'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Adjustment after:</span>'+
                                            '<span>adjustment:</span>'+
                                            '<input id='+data.emp_id+' class="float_only form-control adjustmentAfter'+data.emp_id+'" value="0" type="text" >'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>additional:</span>'+
                                            '<span>incentives: <span>'+data.incentives+'</span></span>'+
                                            '<span>current cutoff 13th:</span>'+
                                            '<span>'+data.getDatePayroll+'(BASIC): '+data.current_cut_off_13th_basic_value+'</span>'+
                                            '<span>'+data.getDatePayroll+'(ALLOWANCE): '+data.current_cut_off_13th_allowance_value+'</span>'+
                                            '<span>TOTAL BASIC PAY:</span>'+
                                            '<span>'+data.total_13_basic_pay+':</span>'+
                                            '<span>TOTAL ALLOWANCE PAY:</span>'+
                                            '<span>'+data.total_13_allowance_pay+':</span>'+
                                        '</div>'+
                                        '<div class="col-lg-3">'+
                                            '<span>Net Pay:</span>'+
                                            '<input id='+data.emp_id+' class="input_payroll form-control netPay_'+data.emp_id+'" value='+netPay+' type="text" class="form-control">'+
                                        '</div>'+
                                    '</div>'+
                                    '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#payrollRemarksModal'+data.emp_id+'">Adjustment</button>'+
                                    '<div class="modal fade payrollRemarksModal" id="payrollRemarksModal'+data.emp_id+'" tabindex="-1" role="dialog" aria-labelledby="payrollRemarksModalTitle" aria-hidden="true">'+
                                        '<div class="modal-dialog modal-dialog-centered" role="document">'+
                                            '<div class="modal-content">'+
                                                '<div class="modal-header">'+
                                                    '<h5 class="modal-title" id="payrollRemarksModalLongTitle">Remarks</h5>'+
                                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                                                    '<span aria-hidden="true">&times;</span>'+
                                                    '</button>'+
                                                '</div>'+
                                                '<div class="modal-body">'+
                                                    '<textarea class="form-control remarksModal'+data.emp_id+'" id='+data.emp_id+' placeholder="Input Remarks" rows="3"></textarea>'+
                                                '</div>'+
                                                '<div class="modal-footer">'+
                                                    '<button class="btn btn-sm btn-primary "   data-dismiss="modal">Submit</button>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<input type="hidden" class="form-control taxCode_'+data.emp_id+'" value='+data.tax_code+' type="text" class="form-control">'+
                                    '<input type="hidden" class="form-control adjustmentRemarks_'+data.emp_id+'" type="text" class="form-control">'+
                                    '<input type="hidden" class="form-control id='+data.emp_id+' getCuttOffDay_'+data.emp_id+'" type="text" class="form-control">'+
                                    '<input type="hidden" id='+data.emp_id+' class="form-control lastTotalGrossIncome_'+data.emp_id+'" value='+data.last_total_gross_income+' type="text" class="form-control">'+
                                    '<input type="hidden" id='+data.emp_id+' class="form-control total13BasicPay_'+data.emp_id+'" value='+data.total_13_basic_pay+' type="text" class="form-control">'+
                                    '<input type="hidden" id='+data.emp_id+' class="form-control total13AllowancePay_'+data.emp_id+'" value='+data.total_13_allowance_pay+' type="text" class="form-control">'+
                                    '<input type="hidden" id='+data.emp_id+' class="form-control incentives_'+data.emp_id+'" value='+data.incentives+' type="text" class="form-control">'+
                                '</div>'
                                $('.generated-payroll-body').append(append)
                            })
                            $(btnName).hide();
                        }
                        else{
                            toast_options(4000);
                            toastr.error("There was a problem, please try again!");
                            $('.loading-generating').hide();
                        }
                    },
                    error:function(response){
                        toast_options(4000);
                        toastr.error("There was a problem, please try again!");
                        $('.loading-generating').hide();
                    }
                })
            }
        })
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
    $(document).on('keydown','.input_payroll',function (e) {
        //  return false;
        if(e.keyCode != 116) {
            return false;
        }
    });
    $(document).on('change keyup paste','.earnings',function(e){
        var id = e.target.id;
        if ($('.earnings_'+id).val() == ""){
            $('.earnings_'+id).val(0);
        }
        var incentives = $('.incentives_'+id).val();
        var regOT = $(".regOT_"+id).val();
        var rdOT = $(".rdOT_"+id).val();
        var regHolidayOT =  $(".regHolidayOT_"+id).val();
        var specialHolidayOT = $(".specialHolidayOT_"+id).val();
        var rd_regHolidayOT = $(".rdREgHolidayOT_"+id).val();
        var rd_specialHolidayOT = $(".rdSpecialHolidayOT_"+id).val();
        var tardiness = $(".tardiness_"+id).val();
        var absences = $(".absences_"+id).val();
        var present = $(".present_"+id).val();
        var adjustmentEarnings = $(".adjustment_"+id).val();
        var sss_contribution = $('.sssContrib_'+id).val();
        var pagibig_contribution = $('.pagibigContrib_'+id).val();
  		var philhealth_contribution = $('.philhealthContrib_'+id).val();
  	    var cutOff_day = $('.getCuttOffDay_'+id).val();
          
        var total_13_basic_pay = $('.total13BasicPay_'+id).val();
  		var total_13_allowance_pay = $('.total13AllowancePay_'+id).val();

        var last_total_gross_income = $('.lastTotalGrossIncome_'+id).val();
        var totalGrossIncome = parseFloat(convertToZero(present)) + parseFloat(convertToZero(regOT)) + parseFloat(convertToZero(rdOT)) + parseFloat(convertToZero(regHolidayOT)) + parseFloat(convertToZero(specialHolidayOT)) + parseFloat(convertToZero(rd_regHolidayOT)) + parseFloat(convertToZero(rd_specialHolidayOT)) - parseFloat(convertToZero(tardiness)) - parseFloat(convertToZero(absences)) + parseFloat(convertToZero(adjustmentEarnings));
        $('.grossIncome_'+id).val(totalGrossIncome)
        totalGrossIncome = totalGrossIncome.toString().split('e');
        totalGrossIncome = Math.round(+(totalGrossIncome[0] + 'e' + (totalGrossIncome[1] ? (+totalGrossIncome[1] + 2) : 2)));

        totalGrossIncome = totalGrossIncome.toString().split('e');
        totalGrossIncome =  (+(totalGrossIncome[0] + 'e' + (totalGrossIncome[1] ? (+totalGrossIncome[1] - 2) : -2))).toFixed(2);
        $.ajax({
            url:base_url+'payroll_controller/appendTaxValue',
            type:'post',
            dataType:'json',
            data:{
                totalGrossIncome:totalGrossIncome,
                empId:id,
                lastTotalGrossIncome:last_total_gross_income,
                cutOffDay:cutOff_day,
                sssContribution:sss_contribution,
                pagibigContribution:pagibig_contribution,
                philhealthContribution:philhealth_contribution
            },
            success:function(response){
                if(response.status == "success"){
                    var totalDeductions = $('.totalDeductions_'+id).val();
                    var nontaxAllowance = $('.nontaxAllowance_'+id).val();
                    var adjustmentAfter = $('.adjustmentAfter'+id).val();
                    var netPay = parseFloat(totalGrossIncome) - parseFloat(convertToZero(totalDeductions)) -  parseFloat(response.tax) + parseFloat(convertToZero(nontaxAllowance)) + parseFloat(convertToZero(adjustmentAfter));

                    netPay += parseFloat(convertToZero(incentives));
                    netPay += parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay));
                    
                    // for 2 decimal places
                    netPay = netPay.toString().split('e');
                    netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

                    netPay = netPay.toString().split('e');
                    final_netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);
                    $('.netPay_'+id).val(final_netPay);
                }
            },
            error:function(response){

            }
        })
        
    })
    $(document).on('change keyup paste', '.deductions', function(e){
        var id = e.target.id;
        if ($('.deductions_'+id).val() == ""){
            $('.deductions_'+id).val(0);
        }
        var sssContrib = $('.sssContrib_'+id).val();
        var sssLoan = $(".sssLoan_"+id).val();
        var philhealthContrib = $('.philhealthContrib_'+id).val();
        var pagibigContrib = $(".pagibigContrib_"+id).val();
        var pagibigLoan = $(".pagibigLoan_"+id).val();
        var cashBond = $(".cashBond_"+id).val();
        var cashAdvance = $(".cashAdvance_"+id).val();
        var adjustmentDeduction = $(".adjustmentDeduction_"+id).val();

        var total_13_basic_pay = $('.total13BasicPay_'+id).val();
        var total_13_allowance_pay = $('.total13AllowancePay_'+id).val();

        var totalDeductions = parseFloat(convertToZero(sssContrib)) + parseFloat(convertToZero(sssLoan)) + parseFloat(convertToZero(philhealthContrib)) + parseFloat(convertToZero(pagibigContrib)) + parseFloat(convertToZero(pagibigLoan)) + parseFloat(convertToZero(cashBond)) + parseFloat(convertToZero(cashAdvance)) + parseFloat(convertToZero(adjustmentDeduction));

        totalDeductions = totalDeductions.toString().split('e');
        totalDeductions = Math.round(+(totalDeductions[0] + 'e' + (totalDeductions[1] ? (+totalDeductions[1] + 2) : 2)));

        totalDeductions = totalDeductions.toString().split('e');
        totalDeductions =  (+(totalDeductions[0] + 'e' + (totalDeductions[1] ? (+totalDeductions[1] - 2) : -2))).toFixed(2);

        $(".totalDeductions_"+id).val(totalDeductions);
        var totalGrossIncome = $('.grossIncome_'+id).val()
        var tax = $(".witholdingTax_"+id).val();
        var nontaxAllowance = $(".witholdingTax_"+id).val();
        var adjustmentAfter = $(".adjustmentAfter"+id).val();
        var incentives = $('.incentives_'+id).val();
        var netPay = parseFloat(convertToZero(totalGrossIncome)) - parseFloat(convertToZero(totalDeductions)) - parseFloat(convertToZero(tax)) + parseFloat(convertToZero(nontaxAllowance)) + parseFloat(convertToZero(adjustmentAfter));


        netPay += parseFloat(convertToZero(incentives));
        netPay += (parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay)));

        netPay = netPay.toString().split('e');
        netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

        netPay = netPay.toString().split('e');
        netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

        $(".netPay_"+id).val(netPay);

    })
    var loadingSubmitPayroll = false;
    $(document).on('click','.submit-payroll-btn', function(e){
        //var url = form.attr('action');
        var btnName = this;
        if(!loadingSubmitPayroll){
            loadingSubmitPayroll = true
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Submitting . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
        
            var finalData = [];
            ids.forEach(function(data, key){
                var regOT = 'regOT_'+data.id;
                var regHolidayOT = 'regHolidayOT_'+data.id;
                var specialHolidayOT = 'specialHolidayOT_'+data.id;
                var rdREgHolidayOT = 'rdREgHolidayOT_'+data.id;
                var rdSpecialHolidayOT = 'rdSpecialHolidayOT_'+data.id;
                var rdOT = 'rdOT_'+data.id;
                var tardiness = 'tardiness_'+data.id;
                var present = 'present_'+data.id;
                var adjustment = 'adjustment_'+data.id;
                var adjustmentDeduction = 'adjustmentDeduction_'+data.id
                var adjustmentAfter = 'adjustmentAfter'+data.id
                var grossIncome = 'grossIncome_'+data.id;
                var nontaxAllowance = 'nontaxAllowance_'+data.id;
                var witholdingTax = 'witholdingTax_'+data.id
                var sssContrib = 'sssContrib_'+data.id;
                var philhealthContrib = 'philhealthContrib_'+data.id;
                var pagibigContrib = 'pagibigContrib_'+data.id;

                var sssLoan = 'sssLoan_'+data.id;
                var pagibigLoan = 'pagibigLoan_'+data.id;
                var cashAdvance = 'cashAdvance_'+data.id;
                var cashBond = 'cashBond_'+data.id;
                var totalDeductions = 'totalDeductions_'+data.id;
                var netPay = 'netPay_'+data.id;
                var adjustmentRemarks = 'remarksModal'+data.id;
                finalData.push({
                    'emp_id':data.id,
                    'regOT':$('.'+regOT).val(),
                    'regHolidayOT':$('.'+regHolidayOT).val(),
                    'specialHolidayOT':$('.'+specialHolidayOT).val(),
                    'rdREgHolidayOT':$('.'+rdREgHolidayOT).val(),
                    'rdSpecialHolidayOT':$('.'+rdSpecialHolidayOT).val(),
                    'rdOT':$('.'+rdOT).val(),
                    'tardiness':$('.'+tardiness).val(),

                    'present':$('.'+present).val(),
                    'adjustment':$('.'+adjustment).val(),
                    'adjustmentDeduction':$('.'+adjustmentDeduction).val(),
                    'adjustmentAfter':$('.'+adjustmentAfter).val(),

                    'grossIncome':$('.'+grossIncome).val(),
                    'nontaxAllowance':$('.'+nontaxAllowance).val(),
                    'witholdingTax':$('.'+witholdingTax).val(),
                    'sssContrib':$('.'+sssContrib).val(),
                    'philhealthContrib':$('.'+philhealthContrib).val(),
                    'pagibigContrib':$('.'+pagibigContrib).val(),
                    'sssLoan':$('.'+sssLoan).val(),
                    'pagibigLoan':$('.'+pagibigLoan).val(),
                    'cashAdvance':$('.'+cashAdvance).val(),

                    'cashBond':$('.'+cashBond).val(),
                    'totalDeductions':$('.'+totalDeductions).val(),

                    'netPay':$('.'+netPay).val(),
                    'adjustmentRemarks':$('.'+adjustmentRemarks).val(),
                })
            })
            $.ajax({
                url:base_url+'payroll_controller/savePayroll',
                type:'post',
                dataType:'json',
                data:{
                    finalData:finalData,
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
                        toast_options(4000);
                        toastr.error("There was a problem, please try again!");
                        loadingSubmitPayroll = false;
                        change_button_to_default(btnName, 'Submit Payroll');
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    loadingSubmitPayroll = false;
                    change_button_to_default(btnName, 'Submit Payroll');
                }
            })
        }
    })
    getActive();
    function getActive(){
        $.ajax({
            url:base_url+'payroll_controller/getActive',
            type:'GET',
            dataType:'json',
            success:function(response){
                ids = response.ids;
            },
            error:function(response){
                toast_options(4000);
                toastr.error("There was a problem, please try again!");
                loadingSubmitPayroll = false;
                change_button_to_default(btnName, 'Submit');
            }
        })
    }
    function convertToZero(value){

        if (value == ""){
            value = 0;
        }

        return value;
    }
    function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})