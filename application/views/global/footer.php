</body>
</html>

<script>
    var base_url = '<?php echo base_url()?>'
    $.protip();
    function render_response(div,msg, status){
        $(div).empty();
        $(div).append(
            '<div class="alert alert-'+status+' alert-dismissible fade show">'+
            msg+
            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
            '</div>'
        );
    }
    function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
    $(".input-only").keydown(function (e) {
        //  return false;
        if(e.keyCode != 116) {
            return false;
        }
    });

        // onpaste
    $(".input-only").on("paste", function(){
         return false;
    });
    $(".float-only").keydown(function (e) {

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
    $(".float-only").on("paste", function(){
        return false;
    });


    $(".float-only").on('input', function(){
        if ($(this).attr("maxlength") != 9){
            if ($(this).val().length > 9){
                $(this).val($(this).val().slice(0,-1));
            }
            $(this).attr("maxlength","9");
        }

    });
    $(".text-only").on('input', function(){
        $(this).val($(this).val().charAt(0).toUpperCase() + $(this).val().slice(1));
       // document.getElementById(id).value = inputTxt.value.charAt(0).toUpperCase() + inputTxt.value.slice(1);
     }); 

    $(document).on('keypress', '.text-only', function (event) {
        var regex = new RegExp("^[0-9?!@#$%^&*()_+<>/]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

        if (regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });

    $(".text-only").on("paste", function(){ 
        return false;
    });

    $(".text-only").on('input', function(){

       if ($(this).attr("maxlength") != 50){
            if ($(this).val().length > 50){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","50");
       }

   });
    $(".number-only").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter , F5
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
    });


     // for security purpose return false
     $(".number-only").on("paste", function(){
          return false;
     });



      // for handling security in contactNo
    $(".number-only").on('input', function(){
        if ($(this).attr("maxlength") != 11){
            if ($(this).val().length > 11){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","11");
        }

    });
    $(".number-only-sss").on("paste", function(){
          return false;
     });



      // for handling security in contactNo
    $(".number-only-sss").on('input', function(){
        if ($(this).attr("maxlength") != 10){
            if ($(this).val().length > 10){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","10");
        }

    });

    $(".number-only-pagibig").on("paste", function(){
          return false;
     });



      // for handling security in contactNo
    $(".number-only-pagibig").on('input', function(){
        if ($(this).attr("maxlength") != 12){
            if ($(this).val().length > 12){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","12");
        }

    });

    $(".number-only-tin").on("paste", function(){
          return false;
     });



      // for handling security in contactNo
    $(".number-only-tin").on('input', function(){
        if ($(this).attr("maxlength") != 9){
            if ($(this).val().length > 9){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","9");
        }

    });

    $(".number-only-philhealth").on("paste", function(){
          return false;
    });
    


      // for handling security in contactNo
    $(".number-only-philhealth").on('input', function(){
        if ($(this).attr("maxlength") != 12){
            if ($(this).val().length > 12){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","12");
        }

    });

    $(".date-only").keydown(function (e) {
      //  return false;
        if(e.keyCode != 116) {
            return false;
        }
    });

        // onpaste
    $(".date-only").on("paste", function(){
        return false;
    });

    $(document).on('keydown',".year-only",function (e) {
        // Allow: backspace, delete, tab, escape, enter , F5
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
    });


    // float only
    $(document).on('input', ".year-only",function(){
        if ($(this).attr("maxlength") != 4){
            if ($(this).val().length > 4){
                $(this).val($(this).val().slice(0,-1));
            }
            $(this).attr("maxlength","4");
        }

    });
</script>
<script src="<?php echo base_url();?>assets/js/global/toast_options.js"></script>