$(document).ready(function(){
    $('#cashbondList').dataTable();

    get_cashbond_data();

    function get_cashbond_data(){
        $.ajax({
            url:base_url+'cashbond_controller/getCashbondInfo',
            type:'get',
            dataType:'json',
            success:function(response){
                if(response.status == "success"){

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
})