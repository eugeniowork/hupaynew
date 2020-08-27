$(document).ready(function(){
    $('.effective-date').datepicker();
    
    $(document).on('keydown','.float-only',function (e) {


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
    $(document).on("paste",'.float-only',function(){
        return false;
    });
    loadingSubmitMinWage = false;
    $('.minimum-wage-btn').on('click', function(){
        var basicWage = $('.basic-wage').val();
        var effectiveDate = $('.effective-date').val();
        var cola = $('.cola').val();
        if(basicWage == "" || effectiveDate =="" || cola == ""){
            render_response('.add-min-wage-warning','All fiels are required.', "danger")
        }
        else{
            var btnName = this;
            Swal.fire({
                html: 'Are you sure you want add the latest <strong>Minimum Wage</strong> with <strong>basic wage</strong> of <strong>Php. '+basicWage+', COLA</strong> of <strong>Php. '+cola+'</strong> and <strong>effect date</strong> of <strong>'+effectiveDate+'</strong>?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                
                    if(!loadingSubmitMinWage){
                        loadingSubmitMinWage = true
                        $('.add-min-wage-warning').empty();
                        $(btnName).text('');
                        $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
                        $(btnName).prop('disabled', true);
                        $(btnName).css('cursor','not-allowed');
                        $.ajax({
                            url:base_url+'minimum_wage_controller/addMinimumWage',
                            type:'post',
                            dataType:'json',
                            data:{
                                effectiveDate:$('.effective-date').val(),
                                basicWage:$('.basic-wage').val(),
                                cola:$('.cola').val(),
                            },
                            success:function(response){
                                if(response.status == "success"){
                                    toast_options(4000);
                                    toastr.success("The new minimum wage was successfully submitted.");
                                    setTimeout(function(){
                                        window.location.reload();
                                    },1000)
                                }
                                else{
                                    render_response('.add-min-wage-warning',response.msg, "danger")
                                    change_button_to_default(btnName, 'Submit');
                                    loadingSubmitMinWage = false;
                                }
                            },
                            error:function(response){
                                toast_options(4000);
                                toastr.error("There was a problem, please try again!");
                                change_button_to_default(btnName, 'Submit');
                                loadingSubmitMinWage = false;
                            }
                        })
                    }
                }
            })
        }
    })
    var minWageId = '';
    $('.edit-wage-btn').on('click',function(e){
        var id = e.target.id;
        $.ajax({
            url:base_url+'minimum_wage_controller/getMinWage',
            type:'post',
            dataType:'json',
            data:{
                id:id,
            },
            success:function(response){
                if(response.status == "success"){
                    minWageId = id;
                    $('.update-basic-wage').val(response.minWageBasicWage)
                    $('.update-effective-date').val(response.minWageEffectiveDate)
                    $('.update-cola').val(response.minWageCola)
                    $('.update-effective-date').datepicker({ defaultDate: response.minWageEffectiveDates});
                }
            },
            error:function(response){

            }
        })
    })
    loadingUpdateMinWage = false;
    $('.update-min-wage-btn').on('click',function(){
        var btnName = this;
        if(!loadingUpdateMinWage){
            loadingUpdateMinWage = true
            $('.update-min-wage-warning').empty();
            $(btnName).text('');
            $(btnName).append('<span><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></span> Validating . . .');
            $(btnName).prop('disabled', true);
            $(btnName).css('cursor','not-allowed');
            $.ajax({
                url:base_url+'minimum_wage_controller/updateMinWage',
                type:'post',
                dataType:'json',
                data:{
                    effectiveDate:$('.update-effective-date').val(),
                    basicWage:$('.update-basic-wage').val(),
                    cola:$('.update-cola').val(),
                    id :minWageId
                },
                success:function(response){
                    if(response.status == "success"){
                        toast_options(4000);
                        toastr.success("The new minimum wage was successfully updated.");
                        setTimeout(function(){
                            window.location.reload();
                        },1000)
                    }
                    else{
                        render_response('.update-min-wage-warning',response.msg, "danger")
                        change_button_to_default(btnName, 'Submit');
                        loadingUpdateMinWage = false;
                    }
                },
                error:function(response){
                    toast_options(4000);
                    toastr.error("There was a problem, please try again!");
                    change_button_to_default(btnName, 'Submit');
                    loadingUpdateMinWage = false;
                }
            })
        }
    })
    $('.remove-min-wage').on('click',function(e){
        var id = e.target.id;
        Swal.fire({
            html: 'Are you sure you want to delete the latest minimum wage?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url:base_url+'minimum_wage_controller/removeMinWage',
                    type:'post',
                    dataType:'json',
                    data:{
                        id:id,
                    },
                    success:function(response){
                        if(response.status == "success"){
                            toast_options(4000);
                            toastr.success("The new minimum wage was successfully deleted.");
                            setTimeout(function(){
                                window.location.reload();
                            },1000)
                            $('.min-wage-tr').remove();
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
        });
    })




    function change_button_to_default(btnName, btnText){
        $(btnName).prop('disabled', false);
        $(btnName).css('cursor','pointer');
        $(btnName).text(btnText);
    }
})