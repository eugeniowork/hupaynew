<?php
    function getIncentives($bio_id,$daily_rate){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->model('attendance_model');
        $CI->load->model('working_hours_model');
        $CI->load->model('holiday_model');
        $CI->load->model('leave_model');
        $late = 0;
        $undertime = 0;
        
        $incentives = 0;
        if ($bio_id != 0){
            $select_emp_qry = $CI->employee_model->get_employee_by_bio_id_data($bio_id);
            $dept_id = $select_emp_qry['dept_id'];

            $working_id = $select_emp_qry['working_hours_id'];
            $select_workingHours_qry = $CI->working_hours_model->get_info_working_hours($working_id);
            $timeIn = $select_workingHours_qry['timeFrom'];
            $timeOut = $select_workingHours_qry['timeTo'];
            $select_qry = $CI->attendance_model->get_all_attendance_info_by_bio_id($bio_id);
            if(!empty($select_qry)){
                foreach($select_qry as $value){
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
                        $time_in = substr($value->time_in,0,6) . "00";
                        $time_out = substr($value->time_out,0,6) . "00";
                        $date_create = date_create($value->date);
                        $day = date_format($date_create,"l");
                        $num_rows_half_day = $CI->leave_model->get_leave_info_by_condition_with_date_rows($select_emp_qry['emp_id'], $attendance_date);
                        $period_type = "";

                        if($num_rows_half_day != 0){
                            $select_half_day = $CI->leave_model->get_leave_info_by_condition_with_date($select_emp_qry['emp_id'], $attendance_date);
                            $halfdayType = $select_half_day['FileLeaveType'];
                            $period_type = substr($halfdayType,0,-23);
                        }
                        $holiday = date_format(date_create($attendance_date), 'F j');
                        $holiday_num_rows = $CI->holiday_model->get_holiday_date_rows($holiday);
                        $late_in_monday = 0;
                        $undertime_in_monday = 0;
                        $late_in_friday = 0;
                        $undertime_in_frinday = 0;
                        if ($day != "Saturday" && $day != "Sunday" && $holiday_num_rows == 0 && $attendance_date < "2020-06-22") {
                            $grace_period = date("H:i:s",strtotime("+15 minutes",strtotime($timeIn)));
                            $morning_late = 0;

                            if ($dept_id == 1000 && $value->time_in <= "11:00:00"){ // for no exception dapat
                                $late += 0;
                            }
                            else{
                                if ($value->time_in > $grace_period || $value->time_in == "00:00:00"){
                                    if ($late == 0){
                                        if ($value->time_in == "00:00:00"){
                                            $morning_late = 16200;
                                            if ($num_rows_half_day == 1 && $period_type == "Morning"){
                                                $morning_late = $morning_late - (270 * 60);
                                                if ($morning_late <0){
                                                    $morning_late = 0;
                                                }
                                            }
                                            $late = $morning_late;
                                        }
                                        else{
                                            if ($value->time_in > $timeIn) {
                                                if ($value->time_in <= "12:00:00"){
                                                    $morning_late = (strtotime($time_in) - strtotime($timeIn));
                                                    if ($num_rows_half_day == 1 && $period_type == "Morning"){
                                                        $morning_late = $morning_late - (270*60);
                                                        if ($morning_late <0){
                                                            $morning_late = 0;
                                                        }
                                                    }
                                                    $late = $morning_late;
                                                }
                                                else if ($value->time_in >= "13:00:00"){
                                                    $morning_late = (strtotime($time_in) - strtotime("13:00:00")) + strtotime("12:00:00") - strtotime($timeIn);
                                                    if ($num_rows_half_day == 1 && $period_type == "Morning"){
                                                        $morning_late = $morning_late - (270*60);
                                                        if ($morning_late <0){
                                                            $morning_late = 0;
                                                        }

                                                    }
                                                    $late = $morning_late;
                                                }
                                                else if ($value->time_in >= "12:00:00" && $value->time_in <= "13:00:00" ) {
                                                    $morning_late = strtotime("12:00:00") - strtotime($timeIn);
                                                    if ($num_rows_half_day == 1 && $period_type == "Morning"){
                                                        $morning_late = $morning_late - (270*60);
                                                        if ($morning_late <0){
                                                            $morning_late = 0;
                                                        }
                                                    }

                                                    $late = $morning_late;
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        if ($value->time_in == "00:00:00"){
                                            $morning_late = 16200;
                                            if ($num_rows_half_day == 1 && $period_type == "Morning"){
                                                
                                                $morning_late = $morning_late - (270*60);
                                                if ($morning_late <0){
                                                    $morning_late = 0;
                                                }
                                            }
                                            $late = $late + $morning_late;
                                        }
                                        else{
                                            if ($value->time_in > $timeIn) {
                                                if ($value->time_in <= "12:00:00"){
                                                    $morning_late = (strtotime($time_in) - strtotime($timeIn));
                                                    if ($num_rows_half_day == 1 && $period_type == "Morning"){
                                                        $morning_late = $morning_late - (270*60);
                                                        if ($morning_late < 0){
                                                            $morning_late = 0;
                                                        }
                                                    }
                                                    $late = $late + $morning_late;
                                                }
                                                else if ($value->time_in >= "13:00:00"){
                                                    $morning_late = (strtotime($time_in) - strtotime("13:00:00")) + strtotime("12:00:00") - strtotime($timeIn);
                                                    if ($num_rows_half_day == 1 && $period_type == "Morning"){
                                                        $morning_late = $morning_late - (270*60);
                                                        if ($morning_late <0){
                                                            $morning_late = 0;
                                                        }
                                                    }
                                                    $late = $late + $morning_late;
                                                }
                                                else if ($value->time_in >= "12:00:00" && $value->time_in <= "13:00:00" ) {
                                                    $morning_late = strtotime("12:00:00") - strtotime($timeIn);
                                                    if ($num_rows_half_day == 1 && $period_type == "Morning"){
                                                        $morning_late = $morning_late - (270*60);
                                                        if ($morning_late <0){
                                                            $morning_late = 0;
                                                        }
                                                    }

                                                    $late = $morning_late + $late;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if ($morning_late != 0){
                                $late_in_monday = 1;
                            }
                            $afternoon_late = 0;
                            if ($undertime == 0) {
                                if ($value->time_out == "00:00:00"){
                                    $afternoon_late = 16200;
                                    if ($num_rows_half_day == 1 && $period_type == "Afternoon"){

                                        $afternoon_late = $afternoon_late - (270*60);
                                        if ($afternoon_late <0){
                                            $afternoon_late = 0;
                                        }
                                    }
                                    $undertime = $afternoon_late;
                                }
                                else{
                                    if ($value->time_out < $timeOut){
                                        $afternoon_late = strtotime($timeOut) - strtotime($time_out);
                                        if ($num_rows_half_day == 1 && $period_type == "Afternoon"){

                                            $afternoon_late = $afternoon_late - (270*60);
                                            if ($afternoon_late <0){
                                                $afternoon_late = 0;
                                            }
                                        }
                                        if ($select_emp_qry['emp_id'] == 5){
                                            if ($value->time_out >= "18:30:00"){
                                                $undertime += 0;
                                            }
                                            else {
                                                $undertime = $undertime + $afternoon_late;
                                            }
                                        }
                                        else if ($select_emp_qry['emp_id'] == 148){
                                            if ($value->time_out >= "21:00:00"){
                                                $undertime += 0;
                                            }
                                            else {
                                                $undertime = $undertime + $afternoon_late;
                                            }
                                        }
                                        else {

                                            $undertime = $undertime + $afternoon_late;
                                        }
                                    }
                                }
                            }
                            else{
                                if ($value->time_out == "00:00:00"){
                                    $afternoon_late = 16200;
                                    if ($num_rows_half_day == 1 && $period_type == "Afternoon"){
                                        $afternoon_late = $afternoon_late - (270*60);
                                        if ($afternoon_late <0){
                                            $afternoon_late = 0;
                                        }
                                    }
                                    $undertime = $undertime + $afternoon_late;
                                }
                                else{
                                    if ($value->time_out < $timeOut){
                                        $afternoon_late = strtotime($timeOut) - strtotime($time_out);
                                        if ($num_rows_half_day == 1 && $period_type == "Afternoon"){
                                            $afternoon_late = $afternoon_late - (270*60);
                                            if ($afternoon_late <0){
                                                $afternoon_late = 0;
                                            }
                                        }
                                        if ($select_emp_qry['emp_id'] == 5){
                                            if ($value->time_out >= "18:30:00"){
                                                $undertime += 0;
                                            }
                                            else {
                                                $undertime = $undertime + $afternoon_late;
                                            }
                                        }
                                        else if ($select_emp_qry['emp_id'] == 148){
                                            if ($value->time_out >= "21:00:00"){
                                                $undertime += 0;
                                            }
                                            else {
                                                $undertime = $undertime + $afternoon_late;
                                            }
                                        }
                                        else {

                                            $undertime = $undertime + $afternoon_late;
                                        }
                                    }
                                }
                            }
                            if ($afternoon_late != 0){
                                $undertime_in_monday = 1;
                            }
                            if ($day == "Monday"){
                                if ($morning_late == 0 && $afternoon_late == 0){
                                    $adjustment = $daily_rate * .10;
                                    $incentives += $adjustment;
                                }
                            }
                            if ($day == "Friday"){
                                if ($morning_late == 0 && $afternoon_late == 0){
                                    $adjustment = $daily_rate * .05;
                                    $incentives += $adjustment;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $incentives;
    }
?>