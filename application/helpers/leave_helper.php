<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    function leaveValidation($lv_id,$date_from,$date_to,$no_days_to_file,$emp_id){
        $CI =& get_instance();
        $CI->load->model('leave_model');
        $date_today = date("Y-m-d");
        $checkExistPetInfoByEmpId = $CI->leave_model->get_pet_info($emp_id);
        $message = "";
		if ($lv_id == 1){

			$date_to_file = date('Y-m-d', strtotime('-'.$no_days_to_file.' day', strtotime($date_from)));

			if ($date_today <= $date_to_file){ // 2020-02-10 <= 2020-01-11
				//$can_file = true;
			}

			else {
				$message = "Must File Before <b>".$no_days_to_file."</b> days and above";
			}

		}
        else if ($lv_id == 2){

			$date_to_file = date('Y-m-d', strtotime('+'.$no_days_to_file.' day', strtotime($date_to))); // 

			if ($date_today <= $date_to_file){ // 2020-02-17 <= 2020-02-16
				//$can_file = true;
			}

			else {
				$message = "Must File After <b>".$no_days_to_file."</b> days and below";
			}

        }
        else if ($lv_id == 3){

			if(empty($checkExistPetInfoByEmpId)){
                $message = "There is no registed pet in the system";
            }
        }
        else if ($lv_id == 4){
			//$can_file = true;
        }
        return $message;
    }
    function getEmpLeaveCountByEmpIdLtId($emp_id, $lt_id){
        $CI =& get_instance();
        $CI->load->model('leave_model');
        $remaining_leave = 0;

        $select_qry = $CI->leave_model->get_employee_leave($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $leave_array_explode =explode("," ,$value->leave_array);
                $leave_count_array_explode =explode("," ,$value->leave_count_array);

                $counter = 0;
                $count = count($leave_array_explode);
				do{
					if ($leave_array_explode[$counter] == $lt_id){
						$remaining_leave = $leave_count_array_explode[$counter];
					}
					$counter++;
                }
                while($count > $counter);
            }
        }
        return $remaining_leave;
        
        
    }

    function deductLeaveCount(){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');
        $CI->load->model('employee_model');
        $CI->load->model('leave_model');

        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        
        $current_date_time = date_format($date, 'Y-m-d');

        $year = date("Y");
        //$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
        $cutOff = $CI->cut_off_model->get_cut_off();
        $date_payroll = "N/A";
        if(!empty($cutOff)){
            foreach($cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom . ", " .$year),'Y-m-d');
				if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
					//echo "wew";
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
					//echo $date_from . "sad";
					//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo . ", " .$year),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($value->datePayroll),'Y-m-d');
				}
            }
        }
        $select_qry = $CI->employee_model->get_active_employee_row_array();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $leave = 0;
				$db_leave_count = $value->leave_count;
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
                $emp_id = $value->emp_id;
                $num_rows_leave = $CI->leave_model->get_leave_rows($emp_id);
                if($num_rows_leave != 0){
                    $select_leave_qry = $CI->leave_model->get_leave_info_by_condition_not_emergency_birthday($emp_id);
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
                                if ($leave_date >= $final_date_from && $leave_date <= $final_date_to){
                                    $date_create_leave = date_create($leave_date);
                                    $date_format_leave = date_format($date_create_leave,"l");
                                    if($date_format_leave != "Saturday" && $date_format_leave != "Sunday"){
                                        if ($leave == 0){

                                            if ($value->FileLeaveType == "Morning Halfday Leave with pay" || $value->FileLeaveType == "Afternoon Halfday Leave with pay"){
                                                $leave = 0.5;
                                            }
                                            else {
                                                $leave = 1;
                                            }
                                        }
                                        else {
                                            if ($value->FileLeaveType == "Morning Halfday Leave with pay" || $value->FileLeaveType == "Afternoon Halfday Leave with pay"){
                                                $leave = $leave + 0.5;
                                            }
                                            else {
                                                $leave = $leave + 1;
                                            }
                                        }
                                    }
                                }
                                $leave_counter++;
                            }while($leave_counter < $leave_count);
                        }
                    }
                }
                $new_leave_count = $db_leave_count - $leave;
                $update_qryData = array(
                    'leave_count'=>$new_leave_count,
                );
                $update_qry = $CI->employee_model->update_employee_info($emp_id, $update_qryData);

                $reserve_leave = 0;
                if($num_rows_leave != 0){
                    $select_leave_qry = $CI->leave_model->get_leave_info_by_condition_with_emergency($emp_id);
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
                                if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
                                    $date_create_leave = date_create($leave_date);
                                    $date_format_leave = date_format($date_create_leave,"l");
                                    if($date_format_leave != "Saturday" && $date_format_leave != "Sunday"){
                                        if ($leave == 0){

                                            if ($value->FileLeaveType == "Morning Halfday Leave with pay" || $value->FileLeaveType == "Afternoon Halfday Leave with pay"){
                                                $reserve_leave = 0.5;
                                            }
                                            else {
                                                $reserve_leave = 1;
                                            }
                                        }
                                        else {
                                            if ($value->FileLeaveType == "Morning Halfday Leave with pay" || $value->FileLeaveType == "Afternoon Halfday Leave with pay"){
                                                $reserve_leave = $reserve_leave + 0.5;
                                            }
                                            else {
                                                $reserve_leave = $reserve_leave + 1;
                                            }
                                            
                                        }
                                    }
                                }
                                $leave_counter++;
                            }while($leave_counter < $leave_count);
                        }
                    }
                }
                if ($reserve_leave != 0){
                    $update_qryData = array(
                        'reserve_emergency_leave'=>0,
                    );
                    $update_qry = $CI->employee_model->update_employee_info($emp_id, $update_qryData);
                }
                $birthday_leave = 0;
                if ($num_rows_leave != 0) {
                    $select_leave_qry = $CI->leave_model->get_leave_info_by_condition_with_birthday($emp_id);
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

						    	//echo $leave_date;

						    	//echo $leave_date;
						    	// check na sakop siya ng date of payroll
						    	if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
                                    $date_create_leave = date_create($leave_date);
                                    $date_format_leave = date_format($date_create_leave,"l");
                                    if($date_format_leave != "Saturday" && $date_format_leave != "Sunday"){
                                        if ($leave == 0){

                                            if ($value->FileLeaveType == "Morning Halfday Leave with pay" || $value->FileLeaveType == "Afternoon Halfday Leave with pay"){
                                                $birthday_leave = 0.5;
                                            }
                                            else {
                                                $birthday_leave = 1;
                                            }
                                        }
                                        else {
                                            if ($value->FileLeaveType == "Morning Halfday Leave with pay" || $value->FileLeaveType == "Afternoon Halfday Leave with pay"){
                                                $birthday_leave = $reserve_leave + 0.5;
                                            }
                                            else {
                                                $birthday_leave = $reserve_leave + 1;
                                            }
                                            
                                        }
                                    }
                                }
                                $leave_counter++;
                            }while($leave_counter < $leave_count);
                        }
                    }
                }
                if ($birthday_leave != 0){
                    $update_qryData = array(
                        'birthday_leave'=>0,
                    );
                    $update_qry = $CI->employee_model->update_employee_info($emp_id, $update_qryData);
                }

            }
            return 'success';
        }
    }
?>