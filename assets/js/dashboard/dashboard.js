$(document).ready(function(){
    get_events();
    function get_events(){
        $.ajax({
            url:base_url+'events_controller/getEvents',
            type:'get',
            dataType:'json',
            success:function(response){
                $('.events').empty();
                response.events.forEach(function(data,index){
                    var time = new Date(data.dateTimeCreated);

                    

                    var appendProfile = '<div class="d-flex flex-row events-content-profile">'+
                        '<img class="events-profile" src="'+base_url+'assets/images/'+data.ProfilePath+'" />'+
                        '<div class="d-flex flex-column events-content-names">'+
                            '<span class="name">'+data.Firstname+' '+data.Middlename+' '+data.Lastname+'</span>'+
                            '<span class="role">'+data.Position+'</span>'
                        '</div>'
                    '</div>';
                    var appendPostImage = '<span></span>';
                    var appendPostDate = '<span></span>';
                    
                    if(data.imagePath){
                        appendPostImage = '<img src="'+base_url+'assets/images/'+data.imagePath+'" />';
                    }
                    if(data.events_date != "0000-00-00"){
                        appendPostDate = '<strong>Event Date: </strong><span>'+data.events_date+'</span>';
                    }
                    var appendPost = '<div class="events-content-post">'+
                        '<div class="events-post-image p-1 d-flex justify-content-center">'+
                            appendPostImage+
                            
                        '</div>'+
                        appendPostDate+
                        '<p class="event-value">'+nl2br(data.events_value)+'</p>'+
                    '</div>';

                    var append = '<div class="div-main-body events">'+ 
                        '<div class="div-main-body-head">'+
                            data.events_title+
                            '<p class="pull-right">Date Posted: '+time.toDateString()+'</p>'+
                        '</div>'+
                        '<div class="div-main-body-content events-content">'+
                            appendProfile+'<br/>'+
                            appendPost
                        '</div>'+
                    '</div>';



                    //console.log(base_url+'assets/images/'+data.ProfilePath)
                    $('.eventsList').append(append)
                    //date = d.toDateString();
                    // var s = new Date(asd).toLocaleDateString("en-US")
                    //console.log(date)
                    console.log(data)
                })
                
            },
            error:function(response){

            }
        })
    }
    function nl2br (str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }
    function timeConverter(timestamp){
        
        var a = new Date(timestamp * 1000);
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var date = a.getDate();
        var hour = a.getHours();
        var min = a.getMinutes();
        var sec = a.getSeconds();
        // var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
        var time = date + ' ' + month + ' ' + year + ' ';
        return time;
    }

    $('.show-sss-info-balance-btn').on('click',function(){
        $('.div-sss-info-balance').slideToggle('fast');
        
    })
})