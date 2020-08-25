<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    function holidayCutOffTotalCount(){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');
        $CI->load->model('holiday_model');
        $dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');

		$year = date("Y");

        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
        $cutOff = $CI->cut_off_model->get_cut_off();
        if(!empty($cutOff)){
            foreach($cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom),'Y-m-d');
				if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
					//echo "wew";
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
					//echo $date_from . "sad";
					//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($value->datePayroll),'Y-m-d');
				}
            }
        }
        $count = 0;
        $holiday = $CI->holiday_model->get_holiday();
        if(!empty($holiday)){
            foreach($holiday as $value){
                $holiday_date = date_format(date_create($value->holiday_date. ", " . $year),"Y-m-d");

                $day = date_format(date_create($holiday_date), 'l');
				if ($holiday_date >= $final_date_from && $holiday_date <= $final_date_to && $day != "Saturday" && $day != "Sunday"){
					$count++;
					
				}
            }
        }
        return $count;
    }
    function getHolidayDateCutOff($holiday,$bio_id){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->helper('date_is_holiday_helper');
        $CI->load->model('leave_model');
        $CI->load->model('attendance_model');
        $row_emp = $CI->employee_model->get_employee_by_bio_id($bio_id);
        $emp_id = $row_emp['emp_id'];
		$leave = 0;
		$prev_exist_leave = 0;
        $next_exist_leave = 0;
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

        $prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($holiday))));
		$next_day = date('F d',(strtotime ( '+1 day' , strtotime ($holiday))));
		$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
		$day_prev = date_format(date_create($prev_day. ", " . $year),"j");
		$month_next = date_format(date_create($next_day. ", " . $year),"F");
        $day_next = date_format(date_create($next_day. ", " . $year),"j");
        
        $prev_isHoliday = dateIsHoliday($month_prev,$day_prev,$year);
        if ($prev_isHoliday == 0){
            if ($final_date_from == ((date("Y")) - 1) . "-12-26" && $month_prev == "December") {
                $year =(date("Y")) - 1;
            }
            else {
                $year = date("Y");
            }
            $holiday_day_type_prev = date_format(date_create($prev_day. ", " . $year), 'l');
            if ($holiday_day_type_prev == "Sunday") {
                $prev_day = date('F d',(strtotime ( '-2 day' , strtotime ($prev_day)))); // so friday na to
				$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
				$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

                $prev_isHoliday_monday = dateIsHoliday($month_prev,$day_prev,$year);
                if ($prev_isHoliday_monday == 0){
                    $prev_day = date_format(date_create($prev_day. ", " . $year),"Y-m-d");
                    $num_rows_leave = $CI->leave_model->get_leave($emp_id);
                    if(!empty($num_rows_leave)){
                        $select_leave_qry = $CI->leave_model->get_leave_info_by_condition($emp_id);
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
                                    if ($prev_day == $leave_date){
                                        $prev_exist_leave = 1;
                                    }
						   
							    	$leave_counter++;
							    } while($leave_counter < $leave_count);
                            }
                        }
                    }
                    if ($prev_exist_leave != 1){
                        $num_rows_prev = $CI->attendance_model->attendance_info($bio_id, $prev_day);
                    }
                    else{
                        $num_rows_prev = 1;
                    }
                }
                do{
                    if ($prev_isHoliday_monday == 1){
                        $prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day))));
						$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
                        $day_prev = date_format(date_create($prev_day. ", " . $year),"j");
                        if ($holiday_day_type_prev == "Sunday") {
							$prev_day = date('F d',(strtotime ( '-2 day' , strtotime ($prev_day)))); // so friday na to
							$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
							$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

							//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
                        }
                        if ($holiday_day_type_prev == "Saturday") {
							$prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day)))); // so friday na to
							$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
							$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

							//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
                        }
                        if (dateIsHoliday($month_prev,$day_prev,$year) == 0){
                            $holiday_day_type_prev = date_format(date_create($holiday. ", " . $year), 'l');
                            $prev_day = date_format(date_create($type_day_prev. ", " . $year),"Y-m-d");
                            $num_rows_leave = $CI->leave_model->get_leave_rows($emp_id);
                            if ($num_rows_leave != 0) {
                                $select_leave_qry = $CI->leave_model->get_leave_info_by_condition($emp_id);
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
                                            if ($prev_day == $leave_date){
                                                $prev_exist_leave = 1;
                                            }
								   
									    	$leave_counter++;
									    } while($leave_counter < $leave_count);
                                    }
                                }
                            }
                            if ($prev_exist_leave != 1){
                                $num_rows_prev = $CI->attendance_model->attendance_info($bio_id, $prev_day);
                            }
                            else {
								$num_rows_prev = 1;
							}
							$prev_isHoliday_monday = 0;

                        }
                    }
                }while($prev_isHoliday_monday == 1);
            }
            else{
                $prev_day = date_format(date_create($prev_day. ", " . $year),"Y-m-d");
                $num_rows_leave = $CI->leave_model->get_leave_rows($emp_id);
                if ($num_rows_leave != 0) {
                    $select_leave_qry = $CI->leave_model->get_leave_info_by_condition($emp_id);
                    if(!empty($select_leave_qry)){
                        foreach($select_leave_qry as $value){
                            $leaveRange = array();
						    $leaveFrom = strtotime($row_leave->dateFrom);
						    $leaveTo = strtotime($row_leave->dateTo);
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
                                if ($prev_day == $leave_date){
                                    $prev_exist_leave = 1;
                                }
					   
						    	$leave_counter++;
						    } while($leave_counter < $leave_count);
                        }
                    }
                }
                if ($prev_exist_leave != 1){
                    $num_rows_prev = $CI->attendance_model->attendance_info($bio_id, $prev_day);
                }
                else {
					$num_rows_prev = 1;
				}
            }
        }
        do {
            if ($prev_isHoliday == 1){
                $prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day))));
                $month_prev = date_format(date_create($prev_day. ", " . $year),"F");
                if ($final_date_from == ((date("Y")) - 1) . "-12-26" && $month_prev == "December") {
					$year =(date("Y")) - 1;
				}
				else {
					$year = date("Y");
                }
                $day_prev = date_format(date_create($prev_day. ", " . $year),"j");
                $holiday_day_type_prev = date_format(date_create($prev_day. ", " . $year), 'l');
                if ($holiday_day_type_prev == "Sunday") {
					$prev_day = date('F d',(strtotime ( '-2 day' , strtotime ($prev_day)))); // so friday na to
					$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
					$day_prev = date_format(date_create($prev_day. ", " . $year),"j");
                }
                if ($holiday_day_type_prev == "Saturday") {
					$prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day)))); // so friday na to
					$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
                    $day_prev = date_format(date_create($prev_day. ", " . $year),"j");
                    
                }
                $prev_isHoliday = dateIsHoliday($month_prev,$day_prev,$year);
                if ($prev_isHoliday == 0){
                    $prev_day = date_format(date_create($prev_day. ", " . $year),"Y-m-d");
                    $num_rows_leave = $CI->leave_model->get_leave_rows($emp_id);

                    if ($num_rows_leave != 0) {
                        $select_leave_qry = $CI->leave_model->get_leave_info_by_condition($emp_id);
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
                                    if ($prev_day == $leave_date){
                                        $prev_exist_leave = 1;
                                    }
                        
                                    $leave_counter++;
                                } while($leave_counter < $leave_count);
                            }
                        }
                    }
                    if ($prev_exist_leave != 1){
                        $num_rows_prev = $CI->attendance_model->attendance_info($bio_id, $prev_day);
                    }
                    else {
                        $num_rows_prev = 1;
                    }
                    $prev_isHoliday = 0;
                }
            }
        }while($prev_isHoliday == 1);
        $next_isHoliday = dateIsHoliday($month_next,$day_next,$year);
        $year = date("Y");
        if ($next_isHoliday == 0){
            $holiday_day_type_next = date_format(date_create($next_day. ", " . $year), 'l');
            if ($holiday_day_type_next == "Saturday"){
                $next_day = date('F d',(strtotime ( '+2 day' , strtotime ($next_day))));
				$month_next = date_format(date_create($next_day. ", " . $year),"F");
                $day_next = date_format(date_create($next_day. ", " . $year),"j");
                $next_isHoliday_saturday = dateIsHoliday($month_next,$day_next,$year);
                if ($next_isHoliday_saturday == 0){
                    $next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");
                    $num_rows_leave = $CI->leave_model->get_leave_rows($emp_id);
                    if ($num_rows_leave != 0) {
                        $select_leave_qry = $CI->leave_model->get_leave_info_by_condition($emp_id);
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
                                    if ($next_day == $leave_date){
                                        $next_exist_leave = 1;
                                    }
						   
							    	$leave_counter++;
							    } while($leave_counter < $leave_count);
                            }
                        }
                    }
                    if ($next_day <= $final_date_to){
                        if ($next_exist_leave != 1){
                            $num_rows_next = $CI->attendance_model->attendance_info($bio_id, $next_day);
                        }
                        else {
							$num_rows_next = 1;
						}
                    }
                    else {
						$num_rows_next = 1;
					}
                }
                do{
                    if ($next_isHoliday_saturday == 1){
                        $next_day = date('F d',(strtotime ( '+1 day' , strtotime ($next_day))));

						$month_next = date_format(date_create($next_day. ", " . $year),"F");
						$day_next = date_format(date_create($next_day. ", " . $year),"j");

                        $holiday_day_type_next = date_format(date_create($next_day. ", " . $year), 'l');
                        if ($holiday_day_type_next == "Saturday") {
							$next_day = date('F d',(strtotime ( '+2 day' , strtotime ($next_day)))); // so friday na to
							$month_next = date_format(date_create($next_day. ", " . $year),"F");
							$day_next = date_format(date_create($next_day. ", " . $year),"j");
                        }
                        if ($holiday_day_type_next == "Sunday") {
							$next_day = date('F d',(strtotime ( '+1 day' , strtotime ($next_day)))); // so friday na to
							$month_next = date_format(date_create($next_day. ", " . $year),"F");
							$day_next = date_format(date_create($next_day. ", " . $year),"j");
                        }
                        if (dateIsHoliday($month_next,$day_next,$year) == 0){
                            $next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");
                            $num_rows_leave = $CI->leave_model->get_leave_rows($emp_id);
                            if ($num_rows_leave != 0) {
                                $select_leave_qry = $CI->leave_model->get_leave_info_by_condition($emp_id);
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
                                            if ($next_day == $leave_date){
                                                $next_exist_leave = 1;
                                            }
								   
									    	$leave_counter++;
									    } while($leave_counter < $leave_count);
                                    }
                                }
                            }
                            if ($next_day <= $final_date_to){
                                if ($next_exist_leave != 1){
                                    $num_rows_next = $CI->attendance_model->attendance_info($bio_id);
                                }
                                else {
									$num_rows_next = 1;
								}
                            }
                            else {
								$num_rows_next = 1;
							}
							$next_isHoliday_saturday = 0;
                        }
                    }
                }while($next_isHoliday_saturday == 1);
            }
            else{
                $next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");
                $num_rows_leave = $CI->leave_model->get_leave_rows($emp_id);
                if ($num_rows_leave != 0) {
                    $select_leave_qry = $CI->leave_model->get_leave_info_by_condition($emp_id);
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
                                if ($next_day == $leave_date){
                                    $next_exist_leave = 1;
                                }
					   
						    	$leave_counter++;
						    } while($leave_counter < $leave_count);
                        }
                    }
                }
                if ($next_day <= $final_date_to){
				    if ($next_exist_leave != 1){
						$num_rows_next = $CI->attendance_model->attendance_info($bio_id, $next_day);
					}
					else {
						$num_rows_next = 1;
					}
                }
                else{
                    $num_rows_next = 1;
                }
            }
        }
        do{
            if ($next_isHoliday == 1){
                $next_day = date('F d',(strtotime ( '+1 day' , strtotime ($next_day))));

				$month_next = date_format(date_create($next_day. ", " . $year),"F");
                $day_next = date_format(date_create($next_day. ", " . $year),"j");
                $holiday_day_type_next = date_format(date_create($next_day. ", " . $year), 'l');
                if ($holiday_day_type_next == "Saturday") {
					$next_day = date('F d',(strtotime ( '+2 day' , strtotime ($next_day)))); // so friday na to
					$month_next = date_format(date_create($next_day. ", " . $year),"F");
					$day_next = date_format(date_create($next_day. ", " . $year),"j");

                }
                if ($holiday_day_type_next == "Sunday") {
					$next_day = date('F d',(strtotime ( '+1 day' , strtotime ($next_day)))); // so friday na to
					$month_next = date_format(date_create($next_day. ", " . $year),"F");
					$day_next = date_format(date_create($next_day. ", " . $year),"j");

                }
                if (dateIsHoliday($month_next,$day_next,$year) == 0){
                    $next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");
                    $num_rows_leave = $CI->leave_model->get_leave_rows($emp_id);
                    if ($num_rows_leave != 0) {
                        $select_leave_qry = $CI->leave_model->get_leave_info_by_condition($emp_id);
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
                                do {
							    	$leave_date = $leaveRange[$leave_counter];
                                    if ($next_day == $leave_date){
                                        $next_exist_leave = 1;
                                    }
							    	$leave_counter++;
							    } while($leave_counter < $leave_count);
                            }
                        }
                    }
                    if ($next_day <= $final_date_to){
					    if ($next_exist_leave != 1){
							$num_rows_next = $CI->attendance_model->attendance_info($bio_id, $next_day);
						}
						else {
							$num_rows_next = 1;
						}
					}
					else {
						$num_rows_next = 1;
					}
					$next_isHoliday = 0;

                }
            }
        }while($next_isHoliday == 1);
        if ($num_rows_prev == 1 && $num_rows_next == 1){
			$granted =  "Granted";
		}

		else {
			$granted  = "Not Granted";
		}
		

		return $granted;
        //return $prev_isHoliday;
    }
    // function dateIsHoliday(){
    //     return "asd";
    // }
?>