<?php 

function getReplyMessages($message_id){
	$CI =& get_instance();
    $CI->load->model('messaging_model');
    $CI->load->model('employee_model');
	$select_qry = $CI->messaging_model->get_reply($message_id);
	$finalData = "";
	if(!empty($select_qry)){
		foreach ($select_qry as $key => $value) {
			$select_message_qry = $CI->messaging_model->get_all_message($message_id);

			$select_emp_qry = $CI->employee_model->employee_information($value->from_emp_id);

			$from_name = $select_emp_qry['Firstname'] . " " . $select_emp_qry['Lastname'];

			///$date_create = ;
			$date = date_format(date_create($value->dateCreated), 'F d, Y');
			$time = date_format(date_create($value->dateCreated), 'g:i A');

			$finalData .= '<div class="row" style="border:1px solid #BDBDBD;padding:5px;background-color:#fff">

				<div class="col-lg-1">
					<img src="'.base_url().'assets/images/'.$select_emp_qry['ProfilePath'].'" class="events-profile-pic"/>
				</div>
				<div class="col-lg-8">
					<div class="col-lg-12">
						<b>'.$from_name.'</b>
					</div>
					<div class="col-lg-12">
						<span style="word-wrap: break-word" id="readmoreReply">'.nl2br(htmlspecialchars($value->reply)).'</span>
					</div>
				</div>
				<div class="col-lg-3" style="">
					<small style="color:#707b7c"><i>'.$date.' , '.$time.'</i></small>
				</div>
			</div>';

		}
	}
	return $finalData;
}


 ?>