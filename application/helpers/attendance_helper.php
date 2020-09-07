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

                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');

                //echo $current_date_time;
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
                    if ($ot == "0"){	
                        if ($value->time_from < "12:00:00") {
								
                            if ($value->time_out < "12:00:00"){
                                $morning_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }

                            else {
                                $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                            }

                        }
                        if ($value->time_out > "13:00:00") {

                            if ($value->time_from > "13:00:00"){
                                $afternoon_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }
                            else {
                                $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                            }
                        }
                        $ot = $morning_ot + $afternoon_ot;
                    }
                    else{
                        if ($value->time_from < "12:00:00") {
                            if ($value->time_out < "12:00:00"){
                                $morning_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }

                            else {
                                $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                            }
                        }
                        if ($value->time_out > "13:00:00") {
                            if ($value->time_from > "13:00:00"){
                                $afternoon_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }
                            else {
                                $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                            }
                        }
                        $ot = $ot + $morning_ot + $afternoon_ot;
                    }
                }
                
            }
        }
        return $ot;
    }
    function getOvertimeSpecialHolidayOt($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');
        $ot = "0";
		$afternoon_ot = 0;
        $morning_ot = 0;
        
        $select_qry = $CI->attendance_model->get_special_holiday_overtime($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);

                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');

                //echo $current_date_time;
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
                    if ($ot == "0"){	
                        if ($value->time_from < "12:00:00") {
								
                            if ($value->time_out < "12:00:00"){
                                $morning_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }

                            else {
                                $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                            }

                        }
                        if ($value->time_out > "13:00:00") {

                            if ($value->time_from > "13:00:00"){
                                $afternoon_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }
                            else {
                                $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                            }
                        }
                        $ot = $morning_ot + $afternoon_ot;
                    }
                    else{
                        if ($value->time_from < "12:00:00") {
                            if ($value->time_out < "12:00:00"){
                                $morning_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }

                            else {
                                $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                            }
                        }
                        if ($value->time_out > "13:00:00") {
                            if ($value->time_from > "13:00:00"){
                                $afternoon_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }
                            else {
                                $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                            }
                        }
                        $ot = $ot + $morning_ot + $afternoon_ot;
                    }
                }
            }
        }
        return $ot;
    }
    function getOvertimeRDRegularHolidayOt($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');

        $ot = "0";

		$afternoon_ot = 0;
        $morning_ot = 0;
        $select_qry = $CI->attendance_model->get_regular_holiday_rd_overtime($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);

                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');

                //echo $current_date_time;
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
                    if ($ot == "0"){	
                        if ($value->time_from < "12:00:00") {
								
                            if ($value->time_out < "12:00:00"){
                                $morning_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }

                            else {
                                $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                            }

                        }
                        if ($value->time_out > "13:00:00") {

                            if ($value->time_from > "13:00:00"){
                                $afternoon_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }
                            else {
                                $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                            }
                        }
                        $ot = $morning_ot + $afternoon_ot;
                    }
                    else{
                        if ($value->time_from < "12:00:00") {
                            if ($value->time_out < "12:00:00"){
                                $morning_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }

                            else {
                                $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                            }
                        }
                        if ($value->time_out > "13:00:00") {
                            if ($value->time_from > "13:00:00"){
                                $afternoon_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }
                            else {
                                $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                            }
                        }
                        $ot = $ot + $morning_ot + $afternoon_ot;
                    }
                }
            }
        }
        return $ot;
        
    }
    function getOvertimeRDSpecialHolidayOt($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');

        $ot = "0";

		
		$afternoon_ot = 0;
        $morning_ot = 0;
        $select_qry = $CI->attendance_model->get_special_holiday_rd_overtime($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);

                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');

                //echo $current_date_time;
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
                    if ($ot == "0"){	
                        if ($value->time_from < "12:00:00") {
								
                            if ($value->time_out < "12:00:00"){
                                $morning_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }

                            else {
                                $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                            }

                        }
                        if ($value->time_out > "13:00:00") {

                            if ($value->time_from > "13:00:00"){
                                $afternoon_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }
                            else {
                                $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                            }
                        }
                        $ot = $morning_ot + $afternoon_ot;
                    }
                    else{
                        if ($value->time_from < "12:00:00") {
                            if ($value->time_out < "12:00:00"){
                                $morning_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }

                            else {
                                $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                            }
                        }
                        if ($value->time_out > "13:00:00") {
                            if ($value->time_from > "13:00:00"){
                                $afternoon_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }
                            else {
                                $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                            }
                        }
                        $ot = $ot + $morning_ot + $afternoon_ot;
                    }
                }
            }
        }
        return $ot;
    }
    function getOvertimeRestdayOt($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');

        $ot = "0";

		
		$afternoon_ot = 0;
        $morning_ot = 0;
        $select_qry = $CI->attendance_model->get_restday_overtime($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);

                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');

                //echo $current_date_time;
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
                    if ($ot == "0"){	
                        if ($value->time_from < "12:00:00") {
								
                            if ($value->time_out < "12:00:00"){
                                $morning_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }

                            else {
                                $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                            }

                        }
                        if ($value->time_out > "13:00:00") {

                            if ($value->time_from > "13:00:00"){
                                $afternoon_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }
                            else {
                                $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                            }
                        }
                        $ot = $morning_ot + $afternoon_ot;
                    }
                    else{
                        if ($value->time_from < "12:00:00") {
                            if ($value->time_out < "12:00:00"){
                                $morning_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }

                            else {
                                $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                            }
                        }
                        if ($value->time_out > "13:00:00") {
                            if ($value->time_from > "13:00:00"){
                                $afternoon_ot = (strtotime($value->time_out) - strtotime($value->time_from)) / 60;
                            }
                            else {
                                $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                            }
                        }
                        $ot = $ot + $morning_ot + $afternoon_ot;
                    }
                }
            }
        }
        return $ot;
    }
    function getRegOtAmount($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');
        $CI->load->model('allowance_model');
        $CI->load->model('minimum_wage_model');
        $ot = "0";
        $ot_amount = 0;
        $select_emp_qry = $CI->employee_model->employee_information($emp_id);
        $working_id = $select_emp_qry['working_hours_id'];

        $select_workingHours_qry = $CI->working_hours_model->get_info_working_hours($working_id);
        $timeIn = $select_workingHours_qry['timeFrom'];
        $timeOut = $select_workingHours_qry['timeTo'];
        $select_qry = $CI->attendance_model->get_attendance_overtime_payroll($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);

                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

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
                    $allowance = 0;

                    $select_allowance_qry = $CI->allowance_model->get_info_allowance($emp_id);
                    if(!empty($select_allowance_qry)){
                        foreach($select_allowance_qry as $value){
                            if ($allowance == ""){
                                $allowance = $value->AllowanceValue;		
                            }
                            else {
                                $allowance = $allowance + $value->AllowanceValue;
                            }
                        }
                    }
                    $minWageEffectiveDate = $CI->minimum_wage_model->get_minimum_wage();
                    //$minWageEffectiveDate = $minWageEffectiveDate['effectiveDate'];
                    $getLastEffectiveDate = $CI->minimum_wage_model->get_minimum_wage_last();
                    //$getLastEffectiveDate = $getLastEffectiveDate['effectiveData'];

                    $daily_rate = ($select_emp_qry['Salary'] + $allowance )/ 22;
                    if ($attendance_date < $minWageEffectiveDate['effectiveDate']) {
                        $min_wage_increase = $minWageEffectiveDate['basicWage'] - $getLastEffectiveDate['basicWage'];
                        $monthly_increase = ($min_wage_increase * 26);

				    	$daily_rate = ($select_emp_qry['Salary'] + $allowance - $monthly_increase)/ 22;
                    }
                    $hourly_rate = $daily_rate / 9;
                    $ot_rate = round($hourly_rate + ($hourly_rate * .25),2);
                    $total_hours = (strtotime($value->time_out) - strtotime($value->time_from)) / 3600;
                    if ($ot_amount == "0"){	
                        $ot_amount = $total_hours * $ot_rate;
                    }
                    else {
                        $ot_amount = $ot_amount + ($total_hours * $ot_rate);
                    }
                }
            }
        }
        return $ot_amount;
    }
    function getRegHolidayOtAmount($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');
        $CI->load->model('allowance_model');
        $CI->load->model('minimum_wage_model');
        $ot = "0";
        $ot_amount = "0";
        $select_qry = $CI->attendance_model->get_regular_holiday_overtime($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);

                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

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
                    $allowance = 0;

                    $select_allowance_qry = $CI->allowance_model->get_info_allowance($emp_id);
                    if(!empty($select_allowance_qry)){
                        foreach($select_allowance_qry as $value){
                            if ($allowance == ""){
                                $allowance = $value->AllowanceValue;		
                            }
                            else {
                                $allowance = $allowance + $value->AllowanceValue;
                            }
                        }
                    }
                    $minWageEffectiveDate = $CI->minimum_wage_model->get_minimum_wage();
                    //$minWageEffectiveDate = $minWageEffectiveDate['effectiveDate'];
                    $getLastEffectiveDate = $CI->minimum_wage_model->get_minimum_wage_last();
                    //$getLastEffectiveDate = $getLastEffectiveDate['effectiveData'];
                    $daily_rate = ($select_emp_qry['Salary'] + $allowance )/ 22;
                    if ($attendance_date < $minWageEffectiveDate['effectiveDate']) {
                        $min_wage_increase = $minWageEffectiveDate['basicWage'] - $getLastEffectiveDate['basicWage'];
                        $monthly_increase = ($min_wage_increase * 26);

                        $daily_rate = ($select_emp_qry['Salary'] + $allowance - $monthly_increase)/ 22;
                    }
                    $hourly_rate = $daily_rate / 9;
                    $ot_rate = round($hourly_rate + ($hourly_rate),2);
                    $total_hours = (strtotime($value->time_out) - strtotime($value->time_from)) / 3600;
                    if ($ot_amount == "0"){	
                        if ($value->time_from < "12:00:00") {
                            $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                        }

                        if ($value->time_out > "13:00:00") {
                            $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                        }

                        $ot_amount = (($morning_ot + $afternoon_ot)/60) * $ot_rate;
                    }
                    else {
                        if ($value->time_from < "12:00:00") {
                            $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                        }

                        if ($value->time_out > "13:00:00") {
                            $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                        }

                        $ot_amount = ((($morning_ot + $afternoon_ot)/60) * $ot_rate) + $ot_amount;
                    }
                }
            }
        }
        return $ot_amount;
    }
    function getSpecialHolidayOtAmount($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');
        $CI->load->model('allowance_model');
        $CI->load->model('minimum_wage_model');
        $ot = "0";
        $ot_amount = "0";
        $select_qry = $CI->attendance_model->get_special_holiday_overtime($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);
                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

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
                    $allowance = 0;
    
                    $select_allowance_qry = $CI->allowance_model->get_info_allowance($emp_id);
                    if(!empty($select_allowance_qry)){
                        foreach($select_allowance_qry as $value){
                            if ($allowance == ""){
                                $allowance = $value->AllowanceValue;		
                            }
                            else {
                                $allowance = $allowance + $value->AllowanceValue;
                            }
                        }
                    }
                    $minWageEffectiveDate = $CI->minimum_wage_model->get_minimum_wage();
                    //$minWageEffectiveDate = $minWageEffectiveDate['effectiveDate'];
                    $getLastEffectiveDate = $CI->minimum_wage_model->get_minimum_wage_last();
                    //$getLastEffectiveDate = $getLastEffectiveDate['effectiveData'];
                    $daily_rate = ($select_emp_qry['Salary'] + $allowance )/ 22;
                    if ($attendance_date < $minWageEffectiveDate['effectiveDate']) {
                        $min_wage_increase = $minWageEffectiveDate['basicWage'] - $getLastEffectiveDate['basicWage'];
                        $monthly_increase = ($min_wage_increase * 26);
    
                        $daily_rate = ($select_emp_qry['Salary'] + $allowance - $monthly_increase)/ 22;
                    }
                    $hourly_rate = $daily_rate / 9;
                    $ot_rate = round($hourly_rate + ($hourly_rate),2);
                    $total_hours = (strtotime($value->time_out) - strtotime($value->time_from)) / 3600;
                    if ($ot_amount == "0"){	
                        if ($value->time_from < "12:00:00") {
                            $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                        }

                        if ($value->time_out > "13:00:00") {
                            $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                        }

                        $ot_amount = (($morning_ot + $afternoon_ot)/60) * $ot_rate;
                    }
                    else {
                        if ($value->time_from < "12:00:00") {
                            $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                        }
    
                        if ($value->time_out > "13:00:00") {
                            $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                        }
    
                        $ot_amount = ((($morning_ot + $afternoon_ot)/60) * $ot_rate) + $ot_amount;
                    }
                }
            }
        }
        return $ot_amount;
    }
    function getRdRegularHolidayOtAmount($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');
        $CI->load->model('allowance_model');
        $CI->load->model('minimum_wage_model');
        $ot = "0";
        $ot_amount = "0";
        $select_qry = $CI->attendance_model->get_regular_holiday_rd_overtime($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);
                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

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
                    $allowance = 0;
    
                    $select_allowance_qry = $CI->allowance_model->get_info_allowance($emp_id);
                    if(!empty($select_allowance_qry)){
                        foreach($select_allowance_qry as $value){
                            if ($allowance == ""){
                                $allowance = $value->AllowanceValue;		
                            }
                            else {
                                $allowance = $allowance + $value->AllowanceValue;
                            }
                        }
                    }
                    $minWageEffectiveDate = $CI->minimum_wage_model->get_minimum_wage();
                    //$minWageEffectiveDate = $minWageEffectiveDate['effectiveDate'];
                    $getLastEffectiveDate = $CI->minimum_wage_model->get_minimum_wage_last();
                    //$getLastEffectiveDate = $getLastEffectiveDate['effectiveData'];
                    $daily_rate = ($select_emp_qry['Salary'] + $allowance )/ 22;
                    if ($attendance_date < $minWageEffectiveDate['effectiveDate']) {
                        $min_wage_increase = $minWageEffectiveDate['basicWage'] - $getLastEffectiveDate['basicWage'];
                        $monthly_increase = ($min_wage_increase * 26);

                        $daily_rate = ($select_emp_qry['Salary'] + $allowance - $monthly_increase)/ 22;
                    }
                    $hourly_rate = $daily_rate / 9;
                    $ot_rate = round($hourly_rate * 2.6,2);
                    if ($ot_amount == "0"){	
                        if ($value->time_from < "12:00:00") {
                            $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                        }

                        if ($value->time_out > "13:00:00") {
                            $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                        }

                        $ot_amount = ($morning_ot + $afternoon_ot)*$ot_rate;
                    }
                    else {
                        if ($value->time_from < "12:00:00") {
                            $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                        }
    
                        if ($value->time_out > "13:00:00") {
                            $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                        }
    
                        $ot_amount = $ot_amount + ($morning_ot + $afternoon_ot * ($ot_rate));
                        
                    }
                }
            }
        }
        return $ot_amount;
    }
    function getRdOtAmount($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');
        $CI->load->model('allowance_model');
        $CI->load->model('minimum_wage_model');
        $ot = "0";
		$morning_ot = 0;
		$afternoon_ot = 0;

        $ot_amount = "0";
        $select_qry = $CI->attendance_model->get_special_holiday_rd_overtime($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);
    
                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");
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
                    $allowance = 0;
    
                    $select_allowance_qry = $CI->allowance_model->get_info_allowance($emp_id);
                    if(!empty($select_allowance_qry)){
                        foreach($select_allowance_qry as $value){
                            if ($allowance == ""){
                                $allowance = $value->AllowanceValue;		
                            }
                            else {
                                $allowance = $allowance + $value->AllowanceValue;
                            }
                        }
                    }
                    $minWageEffectiveDate = $CI->minimum_wage_model->get_minimum_wage();
                    //$minWageEffectiveDate = $minWageEffectiveDate['effectiveDate'];
                    $getLastEffectiveDate = $CI->minimum_wage_model->get_minimum_wage_last();
                    //$getLastEffectiveDate = $getLastEffectiveDate['effectiveData'];
                    $daily_rate = ($select_emp_qry['Salary'] + $allowance )/ 22;
                    if ($attendance_date < $minWageEffectiveDate['effectiveDate']) {
                        $min_wage_increase = $minWageEffectiveDate['basicWage'] - $getLastEffectiveDate['basicWage'];
                        $monthly_increase = ($min_wage_increase * 26);

                        $daily_rate = ($select_emp_qry['Salary'] + $allowance - $monthly_increase)/ 22;
                    }
                    $hourly_rate = $daily_rate / 9;
                    $ot_rate = round($hourly_rate + ($hourly_rate * .3),2);
                    if ($ot_amount == "0"){	
                        if ($value->time_from < "12:00:00") {
                            $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                        }

                        if ($value->time_out > "13:00:00") {
                            $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                        }

                        $ot_amount = (($morning_ot + $afternoon_ot)/60) * $ot_rate;
                    }
                    else {
                        if ($value->time_from < "12:00:00") {
                            $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                        }
    
                        if ($value->time_out > "13:00:00") {
                            $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                        }
    
                        $ot_amount = ((($morning_ot + $afternoon_ot)/60) * $ot_rate) + $ot_amount;
                    }
                }
            }
        }
        return $ot_amount;
    }
    function getPresentToPayroll($bio_id,$day_from,$day_to){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');
        $CI->load->model('allowance_model');
        $CI->load->model('minimum_wage_model');
        $CI->load->helper('holiday_helper');
        $present = 0;
        $leave = 0;
        $holiday_not_granted = 0;
        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        $select_cutoff_qry = $CI->attendance_model->get_cut_off();
        //uncomment later
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
        $dates = array();
        $from = strtotime($final_date_from);
        $last = strtotime($final_date_to);
        $output_format = 'Y-m-d';
        $step = '+1 day';

        $count = 0;
        while( $from <= $last ) {

            $count++;
            $dates[] = date($output_format, $from);
            $from = strtotime($step, $from);
            
        }
        $count = $count- 1;
        $weekdays = array();
        $counter = 0;
        $weekdays_count = 0;
        do {
            $date_create = date_create($dates[$counter]);
            $day = date_format($date_create, 'w');
            if ($day >= $day_from && $day <= $day_to){
                $weekdays[] = $dates[$counter];
                $date =  $dates[$counter];		
                $weekdays_count++;
                if ($date < date("Y-m-d")){
                    $num_rows = $CI->attendance_model->attendance_info($bio_id,$date);
                    if ($num_rows == 0) {
                        $holiday = date_format(date_create($date), 'F j');
                        $holiday_num_rows = $CI->holiday_model->get_holiday_date_rows($holiday);
                        $granted = "Granted";
                        if ($holiday_num_rows == 1){
                            $granted = getHolidayDateCutOff($holiday,$bio_id);

                            //echo $granted . "<br/>";
                            //echo $granted;
                            // ibig sabihin hindi granted
                            //$granted = "Granted"; // to be comment out after ECQ
                            if ($granted == "Granted") {
                                $present += 1;
                            }
                        }
                    }
                    else {
                        $present += 1;
                    }
                }
            }
            $counter++;
        }
        while($counter <= $count);
        $select_emp_qry = $CI->employee_model->get_employee_by_bio_id_data($bio_id);
        $emp_id = $select_emp_qry['emp_id'];

        $num_rows_leave = $CI->leave_model->get_leave_rows($emp_id);
        if ($num_rows_leave != 0) {
            $select_leave_qry = $CI->leave_model->get_leave_info_by_one_condition($emp_id);
            if(!empty($select_leave_qry)){
                foreach($select_leave_qry as $value){
                    $leaveRange = array();
                    $leaveFrom = strtotime($value->dateFrom);
                    $leaveTo = strtotime($value->dateTo);
                    $leave_output_format = 'Y-m-d';
                    $leave_step = '+1 day';

                    $leave_count = 0;
                    while($leaveFrom <= $leaveTo) {

                        $leave_count++;
                        $leaveRange[] = date($leave_output_format, $leaveFrom);
                        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
                    }
                    $leave_counter = 0;
                    do {
                        $leave_date = $leaveRange[$leave_counter];
                        if ($leave_date < date("Y-m-d")){
                            if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
                                $existAttendanceByDate = $CI->leave_model->get_leave_date($bio_id, $leave_date);
                                if(!empty($existAttendanceByDate)){
                                    $existAttendanceByDate = count($existAttendanceByDate);
                                }
                                if($existAttendanceByDate == 0){
                                    $date_create_leave = date_create($leave_date);
                                    $date_format_leave = date_format($date_create_leave,"w");
                                    if($date_format_leave >= $day_from && $date_format_leave <= $day_to){
                                        $present += 1;
                                    }
                                }
                            }
                        }
                        $leave_counter++;
                    } while($leave_counter < $leave_count);
                }
            }
        }
        return $present;
    }
    function getRdSpecialHolidayOTamount($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');
        $CI->load->model('allowance_model');
        $CI->load->model('minimum_wage_model');
        $ot = "0";
        $ot_amount = "0";
        $select_qry = $CI->attendance_model->get_special_holiday_rd_overtime($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);
                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");
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
                    $allowance = 0;
    
                    $select_allowance_qry = $CI->allowance_model->get_info_allowance($emp_id);
                    if(!empty($select_allowance_qry)){
                        foreach($select_allowance_qry as $value){
                            if ($allowance == ""){
                                $allowance = $value->AllowanceValue;		
                            }
                            else {
                                $allowance = $allowance + $value->AllowanceValue;
                            }
                        }
                    }
                    $minWageEffectiveDate = $CI->minimum_wage_model->get_minimum_wage();
                    //$minWageEffectiveDate = $minWageEffectiveDate['effectiveDate'];
                    $getLastEffectiveDate = $CI->minimum_wage_model->get_minimum_wage_last();
                    //$getLastEffectiveDate = $getLastEffectiveDate['effectiveData'];
                    $daily_rate = ($select_emp_qry['Salary'] + $allowance )/ 22;
                    if ($attendance_date < $minWageEffectiveDate['effectiveDate']) {
                        $min_wage_increase = $minWageEffectiveDate['basicWage'] - $getLastEffectiveDate['basicWage'];
                        $monthly_increase = ($min_wage_increase * 26);

                        $daily_rate = ($select_emp_qry['Salary'] + $allowance - $monthly_increase)/ 22;
                    }
                    $hourly_rate = $daily_rate / 9;
                    $ot_rate = round($hourly_rate * 2.6,2);
                    if ($ot_amount == "0"){	
                        if ($value->time_from < "12:00:00") {
                            $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                        }

                        if ($value->time_out > "13:00:00") {
                            $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                        }

                        $ot_amount = ($morning_ot + $afternoon_ot)*$ot_rate;
                    }
                    else {
                        if ($value->time_from < "12:00:00") {
                            $morning_ot = (strtotime("12:00:00") - strtotime($value->time_from)) / 60;
                        }
    
                        if ($value->time_out > "13:00:00") {
                            $afternoon_ot = (strtotime($value->time_out) - strtotime("13:00:00")) / 60;
                        }
    
                        $ot_amount = $ot_amount + ($morning_ot + $afternoon_ot * ($ot_rate));
                        
                    }
                }
            }
        }
        return $ot_amount;
    }

    function attendanceNotifToTableCount(){
        $CI =& get_instance();
        $CI->load->model('attendance_model');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        $row_emp = $CI->employee_model->employee_information($emp_id);
        $role = $row_emp['role_id'];

        $counter = 1;
        $count = 0;
        if ($role == 3 || $role == 4){
            $attendance = $CI->attendance_model->get_attendance_notif_head_zero($emp_id);
            if(!empty($attendance)){
                $count = count($attendance);
            }
        }
        else{
            $attendance = $CI->attendance_model->get_attendance_notif_head_zero_condition($emp_id);
            if(!empty($attendance)){
                $count = count($attendance);
            }
        }
        return $count;
    }


    function getOTStatusCurrentCutOff($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');
        $CI->load->model('allowance_model');
        $CI->load->model('minimum_wage_model');

        date_default_timezone_set("Asia/Manila");
        //$date = date_create("1/1/1990");
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

        $select_qry = $CI->attendance_model->get_overtime_of_employee($emp_id);

        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                if ($final_date_from <= $value->date && $final_date_to >= $value->date){
                    
                    $date_create = date_create($value->date);
                    $date = date_format($date_create, 'F d, Y');

                    $timeFrom = date_format(date_create($value->time_from), 'g:i A');
                    $timeTo = date_format(date_create($value->time_out), 'g:i A');
                                
                    if ($value->approve_stat == 1){
                        $approve_stat = "Approved";
                    }
                    else if ($value->approve_stat == 2){
                        $approve_stat = "Disapproved";
                    }
                    else if ($value->approve_stat == 0){
                        $approve_stat = "Pending";
                    }

                    else if ($value->approve_stat == 4){
                        $approve_stat = "Pending";
                    }

                    else if ($value->approve_stat == 3){
                        $approve_stat = "Cancelled";
                    }


                    $finalData .= "<tr id='".$value->attendance_ot_id."'>";
                        $finalData .= "<td>" . $date . "</td>";
                        $finalData .= "<td>" . $timeFrom . " - " . $timeTo . "</td>";
                        $finalData .= "<td>" . $value->type_ot . "</td>";
                        $finalData .= "<td>" . $approve_stat . "</td>";
                        $finalData .= "<td>";
                            if ($value->approve_stat == 0) {
                                $finalData .= "<button class='btn btn-sm btn-outline-success'>Edit</button>&nbsp;";
                            }

                            if ($value->approve_stat == 4) {
                                $finalData .= "<button class='btn btn-sm btn-outline-success'>Edit</button>&nbsp;";
                            }

                            if ($value->approve_stat != 3 && $value->approve_stat != 2 && $value->approve_stat != 1) {
                                $finalData .= "<button class='btn btn-sm btn-outline-danger'>Cancel</button>";
                            }

                            if ($value->approve_stat == 3 || $value->approve_stat == 2 || $value->approve_stat == 1) {
                                $finalData .= "No actions";
                            }


                            
                        $finalData .= "</td>";
                    $finalData .= "</tr>";
                }
            }
        }
        return $finalData;
    }

    function getAttendanceStatusCurrentCutOff($emp_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('attendance_model');
        $CI->load->model('allowance_model');
        $CI->load->model('minimum_wage_model');

        date_default_timezone_set("Asia/Manila");
        //$date = date_create("1/1/1990");
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
        $select_qry = $CI->attendance_model->get_attendance_of_employee($emp_id);
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                if ($final_date_from <= $value->date && $final_date_to >= $value->date){
                    
                    $date_create = date_create($value->date);
                    $date = date_format($date_create, 'F d, Y');

                    $timeIn = date_format(date_create($value->time_in), 'g:i A');
                    $timeTo = date_format(date_create($value->time_out), 'g:i A');
                                
                    if ($value->notif_status == 1){
                        $approve_stat = "Approved";
                    }
                    else if ($value->notif_status == 2){
                        $approve_stat = "Disapproved";
                    }
                    else if ($value->notif_status == 0){
                        $approve_stat = "Pending";
                    }
                    else if ($value->notif_status == 4){
                        $approve_stat = "Pending";
                    }

                    else if ($value->notif_status == 3){
                        $approve_stat = "Cancelled";
                    }

            


                    $finalData .= "<tr id='".$value->attendance_notif_id."'>";
                        $finalData .= "<td>" . $date . "</td>";
                        $finalData .= "<td>" . $timeIn . " - " . $timeTo . "</td>";
                        $finalData .= "<td>" . $approve_stat . "</td>";
                        $finalData .= "<td>";



                            if ($value->notif_status == 0) {
                                $finalData .= "<span style='color:#317eac;cursor:pointer;' id='edit_file_attendance_updates'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span>&nbsp;Edit</span>";
                                $finalData .= "<span>&nbsp;|&nbsp;</span>";
                            }

                            if ($value->notif_status == 4) {
                                $finalData .= "<span style='color:#317eac;cursor:pointer;' id='edit_file_attendance_updates'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span>&nbsp;Edit</span>";
                                $finalData .= "<span>&nbsp;|&nbsp;</span>";
                            }

                            if ($value->notif_status != 3 && $value->notif_status != 2 && $value->notif_status != 1) {
                                $finalData .= "<span style='color:#317eac;cursor:pointer;' id='cancel_file_attendance_updates'><span class='glyphicon glyphicon-remove' style='color: #c0392b '></span>&nbsp;Cancel</span>";
                            }

                            if ($value->notif_status == 3 || $value->notif_status == 1 || $value->notif_status == 2) {
                                $finalData .= "No actions";
                            }
                        $finalData .= "</td>";
                    $finalData .= "</tr>";
                }
            }
        }

        return $finalData;
    }
?>