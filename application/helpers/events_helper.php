<?php 

function getAllEventsNotif(){
	$CI =& get_instance();
    $CI->load->model('events_model');
    $CI->load->model('employee_model');
    $emp_id = $CI->session->userdata('user');
    $finalData = "";

    $select_qry = $CI->events_model->get_events_with_employee($emp_id);
    if(!empty($select_qry)){
    	foreach ($select_qry as $value) {
    		$select_emp_qry = $CI->employee_model->employee_information($value->notif_id);

    		$notif_name = $select_emp_qry['Firstname'] . " " . $select_emp_qry['Lastname'];

			$dateCreated = date_format(date_create($value->dateCreated), 'F d, Y');

			$time = date_format(date_create($value->dateCreated), 'g:i A');

			$finalData .= '<div class="notif-content '.$value->events_notif_id.' ">
                <div class="d-flex flex-row">
                    <img src="'.base_url().'assets/images/'.$select_emp_qry['ProfilePath'].'">

                    <div class="notif-content-sub">
                        <b>'.$notif_name.'</b> '.$value->notif_type.' <b>on '.$dateCreated.' at '.$time.'</b>
                    </div>
                </div>
            </div>';
    	}
    }
    return $finalData;
}



 ?>