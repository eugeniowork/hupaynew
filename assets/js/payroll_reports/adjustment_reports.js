$(document).ready(function(){
    $('.table').dataTable();

    $('.print-adjustment-report-btn').on('click',function(e){
        var id = e.target.id;
        // $.ajax({
        //     url:base_url+'payroll_reports_controller/printPayrollAdjustmentReport',
        //     type:'post',
        //     dataType:'json',
        //     data:{
        //         cutOffPeriod :cutOffPeriod
        //     },
        //     success:function(response){

        //     },
        //     error:function(response){

        //     }
        // })
        window.open(base_url+'download/'+id);
    })
})