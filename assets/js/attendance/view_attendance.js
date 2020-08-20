$(document).ready(function(){
    

    var searchOption = "";
    $('.optionSearch').on('click',function(event){
        var id = event.target.id;
        $('input:radio').prop('checked',false);
        $('#'+id).prop('checked',true);
        searchOption = id;
    })
    $('.searchBtn').on('click',function(){
        var url ="";
        if(searchOption == "optionSearchAll"){
            url = 'getAllAttendance';
        }
        $('.attendance-loading').show();
        $.ajax({
            url:base_url+'attendance_controller/'+url,
            type:'post',
            dataType:'json',
            data:{
                searchOption:searchOption
            },
            success:function(response){
                if(response.status == "success"){
                    
                    $('.attendance-body').show();
                    $('#attendanceTable tbody').empty()
                    response.attendanceFinal.forEach(function(data,key){
                        var tr = '<tr id='+data.attendance_id+'>'+
                            '<td>'+data.date_format+'</td>'+
                            '<td>'+data.timeFrom+'</td>'+
                            '<td>'+data.timeTo+'</td>'+
                            '<td>'+
                                '<button data-toggle="modal" data-target="#editAttendanceModal" class="editAttendanceBtn" id='+data.attendance_id+'><i id='+data.attendance_id+' class="fas text-success fa-pencil-alt"></i></button>'+
                            '</td>'+
                        '</tr>';
                        $('#attendanceTable tbody').append(tr)
                    })
                    
                    $('.attendance-loading').hide();
                    $('#attendanceTable').dataTable({
                        "ordering": false,
                        "info":     false
                    });
                }
            },
            error:function(response){

            }
        })
    })
    $(document).on('click', '.editAttendanceBtn',function(event){
        var id = event.target.id;
        
    })
})