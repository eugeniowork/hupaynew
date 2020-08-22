$(document).ready(function(){
    $('.generate-payroll-btn').on('click',function(){
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
                $.ajax({
                    url:base_url+'payroll_controller/generatePayroll',
                    type:'get',
                    dataType:'json',
                    success:function(response){
                        if(response.status == "success"){
                            $('.loading-generating').hide();
                        }
                    },
                    error:function(response){

                    }
                })
            }
        })
    })
})