$(document).ready(function(){
    

    get_atm_account_no_list();
    function get_atm_account_no_list(){
        $('#atmAccountNoList tbody').empty()
        $.ajax({
            url:base_url+'employee_controller/getAtmAccountNoList',
            type:'get',
            dataType:'json',
            success:function(response){
                if(response.status =="success"){
                    response.finalListOfEmployeeAtm.forEach(function(data,key){
                        if(data.action == "yes"){
                            var tr = '<tr id='+data.emp_id+'>'+
                                '<td>'+data.emp_name+'</td>'+
                                '<td >'+data.atmAccountNumber+'</td>'+
                                '<td style="text-align:center">'+
                                    '<button class="btn btn-outline-success btn-sm" id='+data.emp_id+'><i id='+data.emp_id+' class="fas fa-pencil-alt"></i>&nbsp;Edit</button>'
                                '</td>'+
                            '</tr>';
                            $('#atmAccountNoList tbody').append(tr)
                        }
                        else{
                            var tr = '<tr id='+data.emp_id+'>'+
                                '<td>'+data.emp_name+'</td>'+
                                '<td>'+data.atmAccountNumber+'</td>'+
                                '<td>No Action</td>'+
                            '</tr>';
                            $('#atmAccountNoList tbody').append(tr)
                        }
                        
                    })
                    $('#atmAccountNoList').dataTable();
                }
            },
            error:function(response){

            }
        })
    }
})