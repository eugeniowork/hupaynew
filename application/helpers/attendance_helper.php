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

    function getOvertimeRegularOt($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');

        $ot = "0";
        $select_emp_qry = $CI->employee_model->employee_information($emp_id);
        $working_id = $select_emp_qry['working_hours_id'];

        $select_workingHours_qry = $CI->working_hours_model->get_info_working_hours($working_id);

        $timeIn = $select_workingHours_qry['timeFrom'];
        $timeOut = $select_workingHours_qry['timeTo'];
        
        $select_qry = $CI->attendance_model->get_attendance_overtime_payroll($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                //$select_emp_qry = $CI->employee_model->employee_information($value->emp_id);

                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                date_default_timezone_set("Asia/Manila");
                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');
                $year = date("Y");

                $select_cutoff_qry = $CI->attendance_model->get_cut_off();
                if(!empty($select_cutoff_qry)){
                    foreach($select_cutoff_qry as $valueCutOff){
                        $date_from = date_format(date_create($valueCutOff->dateFrom . ", " .$year),'Y-m-d');
                        if (date_format(date_create($valueCutOff->dateFrom),'m-d') == "12-26"){
                            $prev_year = $year - 1;
                            $date_from = $prev_year . "-" .date_format(date_create($valueCutOff->dateFrom),'m-d');
                            
                        }
                        $date_from = date_format(date_create($date_from),"Y-m-d");
                        $date_to = date_format(date_create($valueCutOff->dateTo. ", " .$year),'Y-m-d');

                        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                        
                        if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                            $final_date_from = $date_from;
                            $final_date_to = $date_to;
                            $date_payroll = date_format(date_create($valueCutOff->datePayroll . ", " .$year),'Y-m-d');
                        }
                    }
                }
                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');

                $attendance_date = date_format(date_create($value->date),"Y-m-d");
                if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){
                    $total_hours = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                    if ($ot == "0"){	
                        $ot = ($total_hours);
                    }
                    else {
                        $ot = $ot + $total_hours;
                    }
                }	
            }
        }
        return $ot;
    }
    
    function getOvertimeRegularHolidayOt($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');

        $ot = "0";

		
		$afternoon_ot = 0;
        $morning_ot = 0;
        $select_qry = $CI->attendance_model->get_regular_holiday_overtime($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);

                $date_create = date_create($select_emp_qry['date']);
                $date_format = date_format($date_create, 'F d, Y');
                
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');

                //echo $current_date_time;
                $year = date("Y");
            }
        }
    }
?>