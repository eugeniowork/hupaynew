$(document).ready(function(){

	get_audit_trail();
	function get_audit_trail(){
		$('#auditTrail tbody').empty();
		$('.loading-audit-trail').show();
		$('.audit-trail-content').hide();
		$.ajax({
			url:base_url+'audit_trail_controller/getAuditTrailLogs',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					if(response.finalAuditTrailData.length > 0){
						response.finalAuditTrailData.forEach(function(data,key){
							var append = '<tr>'+
								'<td>'+data.module+'</td>'+
								'<td>'+data.description+'</td>'+
								'<td>'+data.date+'</td>'+
							'</tr>';
							$('#auditTrail tbody').append(append);
						})
						
					}
					$('#auditTrail').dataTable({
						ordering:false
					});
					$('.loading-audit-trail').hide();
					$('.audit-trail-content').show();
				}
				else{
					//toast_options(4000);
                    //toastr.error("There was a problem, please try again!");
                    $('.loading-audit-trail-content').empty();
                    $('.loading-audit-trail-content').append('<p class="text-danger">There was a problem fetching the audit trail logs, please try again.</p>')
				}
			},
			error:function(response){
				$('.loading-audit-trail-content').empty();
                $('.loading-audit-trail-content').append('<p class="text-danger">There was a problem fetching the audit trail logs, please try again.</p>')
			}
		})
	}
})