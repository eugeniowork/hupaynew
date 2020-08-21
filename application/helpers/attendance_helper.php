<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    function getEmpIdByNotification($id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->library('session');
        //$emp_id = $CI->session->userdata('user');
        $row_emp = $CI->employee_model->employee_information($id);
        $head_emp_id = $row_emp['head_emp_id'];
        $emp_id_values = "";
        $count = "";
        if($head_emp_id == 0){
            $select_qry = $CI->employee_model->get_employee_by_role();
            if(!empty($select_qry)){
                foreach($select_qry as $value){
                    if ($emp_id_values == ""){
						$emp_id_values = $value->emp_id;
					}

					else {
						$emp_id_values = $emp_id_values . "#" . $value->emp_id;
					}

					$count++;
                }
            }
        }
        else{
            $select_qry = $CI->employee_model->get_employee_by_role_one();
            if(!empty($select_qry)){
                foreach($select_qry as $value){
                    if ($emp_id_values == ""){
						$emp_id_values = $value->emp_id;
					}

					else {
						$emp_id_values = $emp_id_values . "#" . $value->emp_id;
					}

					$count++;
                }
            }
            $emp_id_values = $emp_id_values . "#" . $head_emp_id;
        }
        return $emp_id_values;
    }
    function getEmpIdByNotificationCount($id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->library('session');
        //$emp_id = $CI->session->userdata('user');
        $row_emp = $CI->employee_model->employee_information($id);
        $head_emp_id = $row_emp['head_emp_id'];
        $count = 0;
        if ($head_emp_id == 0) {
            $select_qry = $CI->employee_model->get_employee_by_role();
            if(!empty($select_qry)){
                foreach($select_qry as $value){
					$count++;
                }
            }
        }
        else{
            $select_qry = $CI->employee_model->get_employee_by_role_one();
            if(!empty($select_qry)){
                foreach($select_qry as $value){
					$count++;
                }
            }
            $count++;
        }
        return $count;
    }

    function insertNotifications($emp_id,$notif_emp_id,$attendance_notif_id,$attendance_ot_id,$leave_id,$notifType,
        $type,$status,$dateTime
    ){

    }
?>