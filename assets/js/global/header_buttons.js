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