<?php
    function getTardinessLatest($emp_id,$bio_id,$timeFrom,$timeTo,$day_from,$day_to,$hourly_rate,$daily_rate,$total_hours){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('attendance_model');
        $CI->load->model('holiday_model');
        $CI->load->model('leave_model');
        $select_emp_qry = $CI->employee_model->get_employee_by_bio_id_data($bio_id);
        $dept_id = $select_emp_qry['dept_id'];
        $tardiness = 0;
        $timeIn = $timeFrom;
        $timeOut = $timeTo;
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
        $timeFromPlus30mins = date("H:i:s", strtotime('+30 minutes', strtotime($timeIn)));
        $timeFromPlus60mins = date("H:i:s", strtotime('+60 minutes', strtotime($timeIn)));
        $lunch_break_from = "12:00:00";
        $lunch_break_to = "13:00:00";
        
        do {
            $date_create = date_create($dates[$counter]);
            $day = date_format($date_create, 'w');
            if ($day >= $day_from && $day <= $day_to){
                $weekdays[] = $dates[$counter];
                $date =  $dates[$counter]; 
                $attendance_date = date_format(date_create($date),"Y-m-d");
                $weekdays_count++;
                if ($date < date("Y-m-d")){
                    $num_rows = $CI->attendance_model->attendance_info($bio_id, $date);
                    $holiday = date_format(date_create($attendance_date), 'F j');
                    $holiday_num_rows = $CI->holiday_model->get_holiday_date_rows($holiday);
                    if ($num_rows == 1 && $holiday_num_rows == 0) {
                        $select_qry = $CI->attendance_model->attendance_info_data($bio_id, $date);
                        $db_time_in = substr($select_qry['time_in'],0,6) . "00";
                        $db_time_out = substr($select_qry['time_out'],0,6) . "00";
                        if ($select_qry['time_out'] == "00:00:00"){

                            if ($select_qry['time_in'] >= $timeOut){ // 18:40:00 
                                $db_time_in = $lunch_break_to;
                                $db_time_out = $select_qry['time_in'];
                            }

                            else {
                                $db_time_out = $lunch_break_to;
                            }
                        }
                        if ($select_qry['time_in'] >= $timeOut){ // 18:40:00 

                            $db_time_in = $lunch_break_to;
                        }
                        $num_rows_half_day = $CI->leave_model->get_leave_info_by_condition_with_date($emp_id, $attendance_date);
                        $period_type = "";
                        if(!empty($num_rows_half_day)){
                            $halfdayType = $num_rows_half_day['FileLeaveType'];
                            $period_type = substr($halfdayType,0,-23);
                        }
                        if ($db_time_in > $timeIn){
                            if ($period_type == "Morning"){
                            }
                            else{
                                if ($timeFromPlus30mins > $db_time_in){
                                    $date1 = strtotime($db_time_in);  
                                    $date2 = strtotime($timeIn);  
                                    $diff = abs($date1 - $date2) / 60;
                                    $diff = ($diff / 60);
                                    if ($dept_id == 1){
                                    }
                                    else {

                                        $tardiness += round($diff * $hourly_rate,2);
                                    }
                                }
                                else if($timeFromPlus30mins <= $db_time_in && $timeFromPlus60mins > $db_time_in){
                                    if ($dept_id == 1){
                                    }
                                    else {
                                        $tardiness += round($daily_rate * .25,2);
                                    }
                                }
                                else if ($timeFromPlus60mins <= $db_time_in){
                                    if ($dept_id == 1){
                                         if ($db_time_in > "11:00:00"){
                                            $tardiness += round($daily_rate * .50,2);
                                        }
                                    }
                                    else {
                                        $tardiness += round($daily_rate * .50,2);
                                    }
                                }
                            }
                        }
                        if ($db_time_out < $timeOut){
                            if ($period_type == "Afternoon"){
                            }
                            else{
                                if ($timeFromPlus30mins > $db_time_in){
                                    $date1 = strtotime($db_time_out);  // 12:00:00
                                    $date2 = strtotime($timeOut);  //18:30:00

                                    if ($db_time_out < $timeOut){
                                        $diff = abs($date1 - $date2) / 60;
                                        $diff = ($diff / 60);
                                        $tardiness += round($diff * $hourly_rate,2);
                                    }
                                }
                                else if ($timeFromPlus30mins <= $db_time_in && $timeFromPlus60mins > $db_time_in){
                                    $date1 = strtotime($db_time_out);  // 12:00:00
                                    $date2 = strtotime($timeOut);
                                    if ($dept_id == 1){
                                        $diff = abs($date1 - $date2) / 60;
                                        $diff = ($diff / 60);
                                        $tardiness += round($diff * $hourly_rate,2);
                                    }
                                    else {
                                        if ($db_time_out < $timeOut){
                                            $diff = abs($date1 - $date2) / 60;
											$diff = ($diff / 60);
											$tardiness += round($diff *(round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2)),2);
                                        }
                                    }
                                }
                                else if ($timeFromPlus60mins <= $db_time_in){
                                    $date1 = strtotime($db_time_out);  // 12:00:00
                                    $date2 = strtotime($timeOut);
                                    if ($dept_id == 1){
                                        if ($db_time_in > "11:00:00"){
                                            $diff = abs($date1 - $date2) / 60;
											$diff = ($diff / 60); 
											$tardiness += round($diff *(round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2)),2);
                                        }
                                        else {
                                            $diff = abs($date1 - $date2) / 60;
                                            $diff = ($diff / 60);
                                            $tardiness += round($diff * $hourly_rate,2);
                                        }
                                    }
                                    else {
                                        if ($db_time_out < $timeOut){
                                            $diff = abs($date1 - $date2) / 60;
                                            $diff = ($diff / 60);
                                            $tardiness += round($diff *(round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2)),2);
                                        }
                                    }
                                    
                                }
                                
                            }
                        }
                    }
                }
            }
            $counter++;
        }while($counter <= $count);
	
			
        return $tardiness;
    }
?>