<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("employee_model", 'employee_model');
        $this->load->model("dashboard_model", 'dashboard_model');
        $this->load->model("pagibig_model", 'pagibig_model');
        $this->load->model("sss_model", 'sss_model');
        $this->load->model("simkimban_model", 'simkimban_model');
        $this->load->model("salary_model", 'salary_model');
        $this->load->model("cashbond_model", 'cashbond_model');
        $this->load->model("working_days_model", "working_days_model");
        $this->load->model("cut_off_model", "cut_off_model");
        $this->load->model("holiday_model", "holiday_model");
        $this->load->model("allowance_model", "allowance_model");
        $this->load->model("working_hours_model", "working_hours_model");
        $this->load->model("attendance_model", "attendance_model");
        $this->load->model("leave_model", "leave_model");
        $this->load->helper('hupay_helper');
        //$this->load->library('../controllers/holiday_controller');

    }
    
    public function index(){
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);
       

        //for pagibig loan start
        $pagibig = $this->pagibig_model->check_if_has_pagibig($id);
        $this->data['pagibig'] = $pagibig;
        //for pagibig loan end
        //for sss loan start
        $sss = $this->sss_model->check_if_has_sss($id);
        $sssInfo = $this->sss_model->get_info_sss($id);
        $this->data['sss'] = $sss;
        $this->data['sssInfo'] = $sssInfo;
        // for sss loan end

        //for simkimban start
        $simkimban = $this->simkimban_model->check_if_has_simkimban($id);
        $simkimbanInfo = $this->simkimban_model->get_info_simkimban($id);
        $this->data['simkimban'] = $simkimban;
        $this->data['simkimbanInfo'] = $simkimbanInfo;
        //for simbimban end

        //for salary start
        $salary = $this->salary_model->check_if_has_salary($id);
        $salaryInfo = $this->salary_model->get_info_salary($id);
        $this->data['salaryInfo'] = $salaryInfo;
        $this->data['salary'] = $salary;
        //for salary end

        //for cashbond start
        $cashbond = $this->cashbond_model->get_info_simkimban($id);
        $this->data['cashbond'] = $cashbond;
        //for cashbond end

        //for working days start
        $workingDays = $this->working_days_model->get_working_days_info($employeeInfo['working_days_id']);
        //$this->data['workingDays'] = $workingDays;
        //for working days end

        //for cut off start
        $cutOff = $this->cut_off_model->get_cut_off();
        $day_from = $workingDays['day_from'];
        $day_to = $workingDays['day_to'];
        $final_date_from = null;
        $final_date_to = null;
        $dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        if(!empty($cutOff)){
            foreach($cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom),'Y-m-d');
                if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d");
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
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
        $cut_off_attendance_count = 0;
        do {
            $date_create = date_create($dates[$counter]);
            $attendance_date = date_format($date_create, 'F d, Y');

            $day = date_format($date_create, 'w');

           if ($day >= $day_from && $day <= $day_to){
               $cut_off_attendance_count++;    			    	
           }

           /*echo '<div class="col-sm-3">';
               echo '<b>' . $attendance_date . " :</b>";
           echo "</div>";
           */

            //$attendance_date . "<br/>";

           //echo $dates[$counter];
           
           $counter++;
           

        }while($counter <= $count);
        //$this->data['cut_off_attendance_count'] = $name_count;
        //for cut off end
        
        //for holiday cut off start
        $holiday_cutOff = $this->holiday_model->get_cut_off();
        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        if(!empty($holiday_cutOff)){
            foreach($holiday_cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
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
        $holiday_cut_off_count = 0;
        $holiday = $this->holiday_model->get_holiday();
        if(!empty($holiday)){
            foreach($holiday as $value){
                $holiday_date = date_format(date_create($value->holiday_date. ", " . $year),"Y-m-d");
                $day = date_format(date_create($holiday_date), 'l');
                if ($holiday_date >= $final_date_from && $holiday_date <= $final_date_to && $day != "Saturday" && $day != "Sunday"){
					$holiday_cut_off_count++;
				}
            }
        }
        $this->data['holiday_cut_off_count'] = $holiday_cut_off_count;
        //for holiday cut off end

        //for allowance start
        $allowance = $this->allowance_model->get_info_allowance($id);
        $allowanceValue = 0;
        if(!empty($allowance)){
            foreach($allowance as $value){
                $allowanceValue += $value->AllowanceValue;
            }
        }
        //$this->data['allowanceValue'] = $allowanceValue;
        //for allowance end

        //for attendance start
        $attendance_cutOff = $this->attendance_model->get_cut_off();
        $present = 0;
        $leave = 0;
        $holiday_not_granted = 0;
        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        if(!empty($attendance_cutOff)){
            foreach($attendance_cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){

                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
				$date_to = date_format(date_create($value->dateTo. ", " .$year),'Y-m-d');
                $minus_five_day = date('Y-m-d',(strtotime ( '-1 day' , strtotime (date("Y-m-d")) ) ));
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                    $final_date_from = $date_from;
                    $final_date_to = $date_to;
                    $date_payroll = date_format(date_create($value->datePayroll . ", " .$year),'Y-m-d');
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
        do{
            $date_create = date_create($dates[$counter]);
            $day = date_format($date_create, 'w'); //
            if($day >= $day_from && $day <= $day_to){
                $weekdays[] = $dates[$counter];
		    	$date =  $dates[$counter]; 
                $weekdays_count++; 
                if ($date < date("Y-m-d")){
                    $attendance = $this->attendance_model->attendance_info($employeeInfo['bio_id'],$date);
                    if(empty($attendance)){
                        $holiday = date_format(date_create($date), 'F j'); 
                        $holiday_num_rows = $this->holiday_model->get_holiday_date_rows($holiday);
                        $granted = "Granted";
                        if($holiday_num_rows ==1){
                            $granted = $this->getHolidayCutOff($holiday, $employeeInfo['bio_id']);
                            if($granted == "Granted"){
                                $present +=1;
                            }
                        }
                        else{
                            $present +=1;
                        }
                    }
                }
            }
            $counter++;
        }while($counter <= $count);

        //$select_emp_qry = $this->employee_model->get_employee_by_bio_id($employeeInfo['bio_id']);
        $num_rows_leave = $this->leave_model->get_leave_rows($id);
        if($num_rows_leave  != 0){
            $select_leave_qry = $this->leave_model->get_leave_info_by_one_condition($id);
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
                    do{
                        $leave_date = $leaveRange[$leave_counter];
                        if ($leave_date < date("Y-m-d")){
                            if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
                                $existAttendaceByDate = $this->attendance_model->attendance_info($employeeInfo['bio_id'], $leave_date);
                                if(empty($existAttendaceByDate) == 0){
                                    $date_create_leave = date_create($leave_date);
                                    $date_format_leave = date_format($date_create_leave,"w");
                                    if($date_format_leave >= $day_from && $date_format_leave <= $day_to){
                                        $present += 1;
                                    }
                                }
                            }
                        }
                        $leave_counter++;
                    }
                    while($leave_counter < $leave_count);
                }
            }
        }
        //$d = $this->getHolidayCutOff($holiday, $employeeInfo['bio_id']);

        $this->data['present'] = $present;
        //$present
        //for attendance end

        //for running balance start
        $dayFrom = $workingDays['day_from'];
        $dayTo = $workingDays['day_to'];
        $workingDaysCount = $cut_off_attendance_count;
        $holidayCutOffCount = $holiday_cut_off_count;
        $allowance = $allowanceValue;
        $salary = $employeeInfo['Salary'];

        $basicCutOffPay = round($salary / 2,2);
        $allowanceCutOffPay = round($allowanceValue / 2,2);
        
        $basicCutOffPay = round($basicCutOffPay / 12,2);
        $allowanceCutOffPay = round($allowanceCutOffPay / 12,2);
        
        $daily_rate = round((($allowanceValue + $salary) / 2) / ($workingDaysCount - $holidayCutOffCount),2);
        $workingHours = $this->working_hours_model->get_info_working_hours($employeeInfo['working_hours_id']);
        $timeFrom = $workingHours['timeFrom'];
        $timeTo = $workingHours['timeTo'];
        
        $timeFrom = strtotime($timeFrom);
        $timeTo = strtotime($timeTo);
        
        $total_hours = (($timeTo - $timeFrom) / 3600) - 1;

        $hourly_rate = round($daily_rate / $total_hours,2);
        $present = $present;
        
        //for running balance end


        $this->data['pageTitle'] = 'Dashboard';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('dashboard/dashboard', $this->data);
        $this->load->view('global/footer');
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
                if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
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
        $prev_day = date('F d',(strtotime ( '-1 day' , strtotime (13))));
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
    public function logout(){
        if($this->session->userdata('user')){
            session_destroy();
            $this->session->unset_userdata('user');
        }
        
        redirect('');
    }
}