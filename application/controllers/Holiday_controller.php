<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("holiday_model", 'holiday_model');
        $this->load->model("leave_model", 'leave_model');
    }
    public function getHolidayCutOff($holiday,$bio_id){
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);
        $leave = 0;
		$prev_exist_leave = 0;
        $next_exist_leave = 0;
        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        $cutoff = $this->holiday_model->get_cut_off();
        if(!empty($cutoff)){
            foreach($cutoff as $value){
                $date_from = date_format(date_create($value->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($row_cutoff->dateFrom),'m-d') == "12-26"){
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo. ", " .$year),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($value->datePayroll . ", " .$year),'Y-m-d');
				}
            }
        }
        $prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($holiday))));
        $next_day = date('F d',(strtotime ( '+1 day' , strtotime ($holiday))));
        
        $month_prev = date_format(date_create($prev_day. ", " . $year),"F");
        $day_prev = date_format(date_create($prev_day. ", " . $year),"j");
        
        $month_next = date_format(date_create($next_day. ", " . $year),"F");
        $day_next = date_format(date_create($next_day. ", " . $year),"j");
        
        $prev_isHoliday = $this->dateIsHoliday($month_prev,$day_prev,$year);
        if ($prev_isHoliday == 0){
            if ($final_date_from == ((date("Y")) - 1) . "-12-26" && $month_prev == "December") {
                $year =(date("Y")) - 1;
            }
            else {
				$year = date("Y");
            }
            $holiday_day_type_prev = date_format(date_create($prev_day. ", " . $year), 'l');

            if($holiday_day_type_prev == "Sunday"){
                $prev_day = date('F d',(strtotime ( '-2 day' , strtotime ($prev_day)))); // so friday na to
				$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
                $day_prev = date_format(date_create($prev_day. ", " . $year),"j");
                
                $prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev,$year);
                if ($prev_isHoliday_monday == 0){
                    $prev_day = date_format(date_create($prev_day. ", " . $year),"Y-m-d");
                    $leave = $this->leave_model->get_leave($id);
                    if(!empty($leave)){
                        $selectLeave = $this->leave_model->get_leave_info_by_condition($id);
                        if(!empty($selectLeave)){
                            foreach($selectLeave as $value){
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
                        if($prev_exist_leave != 1){
                            $prevLeave = $this->attendance_model->attendance_info($employeeInfo['bio_id'], $prev_day);
                        }
                        else{
                            $prevLeave = 1;
                        }
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
    
                        }
                        if ($holiday_day_type_prev == "Saturday") {
                            $prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day)))); // so friday na to
                            $month_prev = date_format(date_create($prev_day. ", " . $year),"F");
                            $day_prev = date_format(date_create($prev_day. ", " . $year),"j");
                        }
                        if($this->dateIsHoliday($month_prev,$day_prev,$year) == 0){
                            $holiday_day_type_prev = date_format(date_create($holiday. ", " . $year), 'l');
                            $prev_day = date_format(date_create($type_day_prev. ", " . $year),"Y-m-d");
                            $num_rows_leave = $this->leave_model->get_leave_rows($id);
                            if($num_rows_leave != 0){
                                $select_leave_qry = $this->leave_model->get_leave_info_by_condition($id);
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
                                $prevLeave = $this->attendance_model->attendance_info($employeeInfo['bio_id'], $prev_day);
                            }
                            else{
                                $prevLeave = 0;
                            }
                            $prev_isHoliday_monday = 0;
                        }
                    }
                }while($prev_isHoliday_monday == 1);
            }
            else{
                $prev_day = date_format(date_create($prev_day. ", " . $year),"Y-m-d");
                $num_rows_leave  = $this->leave_model->get_leave($id);
                if(!empty($num_rows_leave)){
                    $selectLeave = $this->leave_model->get_leave_info_by_condition($id);
                    if(!empty($selectLeave)){
                        foreach($selectLeave as $value){
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
                    if ($prev_exist_leave != 1){
                        $prevLeave = $this->attendance_model->attendance_info($employeeInfo['bio_id'], $prev_day);
                    }
                    else{
                        $prevLeave = 1;
                    }
                }
            }
            
        }
        do{
            if ($prev_isHoliday == 1){
                $prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day))));
                $month_prev = date_format(date_create($prev_day. ", " . $year),"F");
                if ($final_date_from == ((date("Y")) - 1) . "-12-26" && $month_prev == "December") {
					//echo "wew";
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
                $prev_isHoliday = $this->dateIsHoliday($month_prev,$day_prev,$year);
                if ($prev_isHoliday == 0){
                    $prev_day = date_format(date_create($prev_day. ", " . $year),"Y-m-d");

                    $num_rows_leave  = $this->leave_model->get_leave($id);
                    if(!empty($num_rows_leave)){
                        $selectLeave = $this->leave_model->get_leave_info_by_condition($id);
                        if(!empty($selectLeave)){
                            foreach($selectLeave as $value){
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
                        if ($prev_exist_leave != 1){
                            $prevLeave = $this->attendance_model->attendance_info($employeeInfo['bio_id'], $prev_day);
                        }
                        else{
                            $prevLeave = 1;
                        }
                        $prev_isHoliday = 0;
                    }
                }
            }
        }while($prev_isHoliday == 1);

        $next_isHoliday = $this->dateIsHoliday($month_next,$day_next,$year);
        $year = date("Y");
        if ($next_isHoliday == 0){
            $holiday_day_type_next = date_format(date_create($next_day. ", " . $year), 'l');
            if ($holiday_day_type_next == "Saturday"){
                $next_day = date('F d',(strtotime ( '+2 day' , strtotime ($next_day))));
                $month_next = date_format(date_create($next_day. ", " . $year),"F");
                $day_next = date_format(date_create($next_day. ", " . $year),"j");
                $next_isHoliday_saturday = $this->dateIsHoliday($month_next,$day_next,$year);
                if ($next_isHoliday_saturday == 0){
                    $next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");

                    $num_rows_leave  = $this->leave_model->get_leave($id);

                    if(!empty($num_rows_leave)){
                        $selectLeave = $this->leave_model->get_leave_info_by_condition($id);
                        if(!empty($selectLeave)){
                            foreach($selectLeave as $value){
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
                    if ($next_day <= $final_date_to){
                        if ($next_exist_leave != 1){
                            $num_rows_next = $this->attendance_model->attendance_info($employeeInfo['bio_id'], $next_day);
                        }
                        else{
                            $num_rows_next = 1;
                        }
                    }
                    else{
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

							//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
                        }
                        if ($holiday_day_type_next == "Sunday") {
							$next_day = date('F d',(strtotime ( '+1 day' , strtotime ($next_day)))); // so friday na to
							$month_next = date_format(date_create($next_day. ", " . $year),"F");
							$day_next = date_format(date_create($next_day. ", " . $year),"j");

							//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
                        }
                        if ($this->dateIsHoliday($month_next,$day_next,$year) == 0){
                            $next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");

                            $num_rows_leave  = $this->leave_model->get_leave($id);
                            if(!empty($num_rows_leave)){
                                $selectLeave = $this->leave_model->get_leave_info_by_condition($id);
                                if(!empty($selectLeave)){
                                    foreach($selectLeave as $value){
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
                                if ($next_day <= $final_date_to){

                                    if ($next_exist_leave != 1){
                                        $num_rows_next = $this->attendance_model->attendance_info($employeeInfo['bio_id'], $prev_day);
                                    }
                                    else {
                                        $num_rows_next = 1;
                                    }
                                }
                                else{
                                    $num_rows_next = 1;
                                }
                                $next_isHoliday_saturday = 0;
                            }
                        }
                    }
                }
                while($next_isHoliday_saturday == 1);
            }
            else{
                $next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");
                $num_rows_leave  = $this->leave_model->get_leave($id);
                if(!empty($num_rows_leave)){
                    $selectLeave = $this->leave_model->get_leave_info_by_condition($id);
                    if(!empty($selectLeave)){
                        foreach($selectLeave as $value){
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
                    if ($next_day <= $final_date_to){
                        if ($next_exist_leave != 1){
                            $num_rows_next = $num_rows_next = $this->attendance_model->attendance_info($employeeInfo['bio_id'], $next_day);
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
                if ($this->dateIsHoliday($month_next,$day_next,$year) == 0){
                    $next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");

                    $num_rows_leave  = $this->leave_model->get_leave($id);
                    if(!empty($num_rows_leave)){
                        $selectLeave = $this->leave_model->get_leave_info_by_condition($id);
                        if(!empty($selectLeave)){
                            foreach($selectLeave as $value){
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
                        if ($next_day <= $final_date_to){
                            if ($next_exist_leave != 1){
                                $num_rows_next = $this->attendance_model->attendance_info($employeeInfo['bio_id'], $next_day);
                            }
                            else {
                                $num_rows_next = 1;
                            }
                        }
                        else{
                            $num_rows_next = 1;
                        }
                        $next_isHoliday = 0;
                    }
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
        
    }
    public function dateIsHoliday($month,$day,$year){
        $holiday_date = $month . " " . $day;
        $date_create_leave = date_create($month . " " . $day . ", " . $year );
        $date_format_leave = date_format($date_create_leave,"l");
        $holiday = $this->holiday_model->get_holiday_date($holiday_date);
        if ($date_format_leave == "Sunday" || $date_format_leave == "Saturday"){
			$holiday = 0;
        }
        return $holiday;
    }

}