$(document).ready(function(){
	get_inbox();
	function get_inbox(){
		$('#inboxList tbody').empty();
		$.ajax({
			url:base_url+'messaging_controller/getInboxData',
			type:'get',
			dataType:'json',
			success:function(response){
				if(response.status == "success"){
					$('#inboxList tbody').append(response.finalData);
					$('#inboxList').dataTable({
						ordering:false
					});
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

	//for history message start
	var messageHistoryId = null;
	$(document).on('click','.open-message-history',function(e){
		messageHistoryId = e.target.id;
		$('.message-history-info').hide();
        $('.submit-reply-btn').hide();
        $('.loading-message-history').show();
        get_message();
	})
	//for history message end
	function get_message(){
		$.ajax({
            url:base_url+'messaging_controller/getMessageHistory',
            type:'post',
            dataType:'json',
            data:{
                id:messageHistoryId,
            },
            success:function(response){
                if(response.status == "success"){
                    $('.message-history-info').show();
        			$('.submit-reply-btn').show();
        			$('.loading-message-history').hide();      

        			//$('.account-no').val(response.atmAccountNumber);
        			if(response.finalData.length > 0){
        				$('.messages-body').empty();
        				response.finalData.forEach(function(data,key){
        					$('.subject-name').text(data.subject)
        					$('.messages-body').append(data.reply)
        				})
        				console.log(response.finalData)
        			}
                }
                else{
                    $('.loading-message-history').show();
                    $('.loading-message-history').empty();
                    $('.loading-message-history').append('<p class="text-danger" style="text-align:center">'+response.msg+'</p>');
                    messageHistoryId = null;
                }
            },
            error:function(response){
                $('.loading-message-history').show();
                $('.loading-message-history').empty();
                $('.loading-message-history').append('<p class="text-danger" style="text-align:center">There was a problem, please try again.</p>');
                messageHistoryId = null;
            }
        })
	}
	//for add reply start
	$('.submit-reply-btn').on('click',function(){
		if($('.message-reply').val() != ""){
			$.ajax({
				url:base_url+'messaging_controller/addReply',
				type:'post',
				dataType:'json',
				data:{
					id:messageHistoryId,
					message:$('.message-reply').val()
				},
				success:function(response){
					if(response.status == "success"){
						get_message();
						$('.message-reply').val('')
					}
					else{
						toast_options(4000);
	                	toastr.error("There was a problem sending a reply, please try again!");
					}
				},
				error:function(response){
					toast_options(4000);
	                toastr.error("There was a problem sending a reply, please try again!");
				}
			})
		}
		
	})
	//for add reply end
})