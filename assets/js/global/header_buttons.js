$(document).ready(function(){
    $('.messaging-btn').on('click',function(){
        $('.messagingDropdown').slideToggle('fast');
        show_hide_dropdowns('.caret-right-messaging', '.caret-down-messaging')
    })
    $('.attendance-btn').on('click',function(){
        $('.attendanceDropdown').slideToggle('fast');
        show_hide_dropdowns('.caret-right-attendance', '.caret-down-attendance')
    })
    $('.leaves-btn').on('click',function(){
        $('.leavesDropdown').slideToggle('fast');
        show_hide_dropdowns('.caret-right-leaves', '.caret-down-leaves')
    })
    $('.loans-btn').on('click',function(){
        $('.loansDropdown').slideToggle('fast');
        show_hide_dropdowns('.caret-right-loans', '.caret-down-loans')
    })
    $('.employee-btn').on('click',function(){
        $('.employeeDropdown').slideToggle('fast');
        show_hide_dropdowns('.caret-right-employee', '.caret-down-employee')
    })
    $('.gov-table-btn').on('click',function(){
        $('.govTableDropdown').slideToggle('fast');
        show_hide_dropdowns('.caret-right-gov-table', '.caret-down-gov-table')
    })
    $('.adjustment-report-btn').on('click',function(){
        $('.adjustmentReportDropdown').slideToggle('fast');
        show_hide_dropdowns('.caret-right-adjustment-report', '.caret-down-adjustment-report')
    })
    $('.payroll-reports-btn').on('click',function(){
        $('.payrollReportsDropdown').slideToggle('fast');
        show_hide_dropdowns('.caret-right-payroll-reports', '.caret-down-payroll-reports')
    })
    function show_hide_dropdowns(caretRight, caretDown){
        if($(caretRight).css('display') == "block"){
            $(caretRight).hide();
            $(caretDown).show();
        }
        else{
            $(caretRight).show();
            $(caretDown).hide();
        }
    }
    
})