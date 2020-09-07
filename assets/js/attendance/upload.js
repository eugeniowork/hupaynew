$(document).ready(function(){
	$('.upload-btn').on('click',function(){
		var fd = new FormData();

		var files = $('.dat_file')[0].files[0];
		fd.append('file','files');

		// $.ajax({
		// 	url:base_url+'attendance_controller/uploadAttendance'
		// })
	})
})