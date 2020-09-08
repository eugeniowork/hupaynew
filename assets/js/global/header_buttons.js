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
    $('.payroll-btn').on('click',function(){
        $('.payrollDropdown').slideToggle('fast');
        show_hide_dropdowns('.caret-right-payroll', '.caret-down-payroll')
    })

    $('.memo-notif-btn').on('click',function(){
        $('.memo-notif').toggle();
    })
    $(document).mouseup(function (e) { 
        if ($(e.target).closest(".memo-notif").length 
                    === 0) { 
            $(".memo-notif").hide(); 
        } 
    }); 

    $('.payroll-notif-btn').on('click',function(){
        $('.payroll-notif').toggle();
    })
    $(document).mouseup(function (e) { 
        if ($(e.target).closest(".payroll-notif").length 
                    === 0) { 
            $(".payroll-notif").hide(); 
        } 
    }); 

    $('.events-notif-btn').on('click',function(){
        $('.events-notif').toggle();
    })
    $(document).mouseup(function (e) { 
        if ($(e.target).closest(".events-notif").length 
                    === 0) { 
            $(".events-notif").hide(); 
        } 
    }); 

    $('.attendance-notif-btn').on('click',function(){
        $('.attendance-notif').toggle();
    })
    $(document).mouseup(function (e) { 
        if ($(e.target).closest(".attendance-notif").length 
                    === 0) { 
            $(".attendance-notif").hide(); 
        } 
    }); 

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