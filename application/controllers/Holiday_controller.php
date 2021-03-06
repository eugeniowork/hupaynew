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
        $this->load->helper('month_day_helper');
        $this->load->helper('hupay_helper');
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

    public function index(){
        $this->data['pageTitle'] = 'Holiday';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('holiday/holiday');
        $this->load->view('global/footer');
    }

    public function getHolidayList(){
        $select_qry = $this->holiday_model->get_holiday();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $finalData .= "<tr class='holiday-tr-".$value->holiday_id."'>";
                    $finalData .= "<td class='holiday-date-".$value->holiday_id."'>".$value->holiday_date."</td>";
                    $finalData .= "<td id='readmoreValue' class='holiday-name-".$value->holiday_id."'>".$value->holiday_value."</td>";
                    $finalData .= "<td>".$value->holiday_type."</td>";
                    $finalData .= "<td>";
                            $finalData .= "<button id=".$value->holiday_id." class='open-edit-holiday btn btn-sm btn-outline-success' data-toggle='modal' data-target='#updateHolidayModal'>Edit</button>&nbsp;";
                            $finalData .= "<button id=".$value->holiday_id." class='delete-holiday btn btn-sm btn-outline-danger'>Delete</button>";
                    $finalData .= "</td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function getRegularHoliday(){
        $holiday = $this->input->post('holiday');
        $year = date("Y");
        $select_qry = $this->holiday_model->get_holiday_types($holiday);
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $date_create = date_create($value->holiday_date .", ".$year);
                $date_format = date_format($date_create, 'l');
                $finalData .= "<strong>".$value->holiday_date . "</strong> - " . $value->holiday_value . " (<i>".$date_format."</i>)<br/>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    public function getSpecialHoliday(){
        $holiday = $this->input->post('holiday');
        $year = date("Y");
        $select_qry = $this->holiday_model->get_holiday_types($holiday);
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $date_create = date_create($value->holiday_date .", ".$year);
                $date_format = date_format($date_create, 'l');
                $finalData .= "<strong>".$value->holiday_date . "</strong> - " . $value->holiday_value . " (<i>".$date_format."</i>)<br/>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    //for update holiday start
    public function getUpdateHolidayInfo(){
        $id = $this->input->post('id');
        $holiday = $this->holiday_model->get_holiday_data($id);
        $finalData = array();
        if(!empty($holiday)){
            $holiday_date = explode(" ",$holiday['holiday_date']);
            array_push($finalData, array(
                'month'=>$holiday_date[0],
                'day'=>$holiday_date[1],
                'dayOptions'=>getDayOfMonthUpdate($holiday_date[0],$holiday_date[1]),
                'holiday_name'=>$holiday['holiday_value'],
                'holiday_type'=>$holiday['holiday_type'],
            ));
            $this->data['finalData'] = $finalData;
            $this->data['status'] = "success";
        }
        else{
            $this->data['status'] = "error";

            
        }

        echo json_encode($this->data);
    }

    public function updateHoliday(){
        $id = $this->input->post('id');
        $month= $this->input->post('month');
        $day= $this->input->post('day');
        $name= $this->input->post('name');
        $type= $this->input->post('type');
        $originalDateOfHoliday = $this->input->post('originalDateOfHoliday');

        $current_date = getDateDate();

        $year = date("Y");
        if ($year % 4 == 0) {
            $total_day = 29;
        }
        else {
            $total_day = 28;
        }

        $this->form_validation->set_rules('month','month','required',array('required'=>'Please select a month.'));
        $this->form_validation->set_rules('day','day','required',array('required'=>'Please select a day.'));
        $this->form_validation->set_rules('name','name','required',array('required'=>'Please enter a name.'));
        $this->form_validation->set_rules('type','type','required',array('required'=>'Please select a type.'));
        if($month." ".$day != $originalDateOfHoliday){
            $this->form_validation->set_rules('date','date','is_unique[tb_holiday.holiday_date]', array(
                'is_unique'=>'<strong>'.$month. ' '.$day.'</strong> already exist'
            ));
        }
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = validation_errors(); 
        }
        else{
            if ($month != "January" && $month != "February" && $month != "March" && $month != "April" && $month != "May" && $month != "June"
            && $month != "July" && $month != "August" && $month != "September" && $month != "October" && $month != "November" && $month != "December"){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid month or day."; 
            }
            else if (($month == "January" || $month == "March" || $month == "May" || $month == "July" || $month == "August" || $month == "October" || $month == "December") && ($day <=0 || $day >= 32)){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid month or day."; 
            }
            else if (($month == "February" && $total_day == 28) && ($day <=0 || $day >=29) || ($month == "February" && $total_day == 29) && ($day <=0 || $day >=30)){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid month or day."; 
            }
            else if (($month == "April" || $month == "June" || $month == "September" || $month == "November") && ($day <=0 || $day >= 31)){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid month or day."; 
            }

            // chec if the type of holiday is equal to the needed type
            else if ($type != "Regular Holiday" && $type != "Special non-working day"){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid holiday type."; 
            }
            else{
                $holiday_date = $month . " " . $day;
                $updateData = array(
                    'holiday_date'=>$holiday_date,
                    'holiday_value'=>$name,
                    'holiday_type'=>$type,
                );
                $update = $this->holiday_model->update_holiday($id, $updateData);
                $this->data['status'] = "success";
            }
        }
        echo json_encode($this->data);
    }
    //for update holiday end

    //for delete holiday start
    public function deleteHoliday(){
        $id = $this->input->post('id');

        $holiday = $this->holiday_model->get_holiday_data($id);
        if(!empty($holiday)){
            $delete = $this->holiday_model->delete_holiday($id);

            $this->data['status'] = "success";
            $this->data['msg'] = "The Holiday <strong>".$holiday['holiday_date']." - ".$holiday['holiday_value']. "</strong> was successfully deleted. ";
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }
    //for delete holiday end


    //for get add holiday start
    public function getDayInMonth(){
        $month = $this->input->post('month');
        if ($month != "" && $month != "January" && $month != "February" && $month != "March" 
            && $month != "April" && $month != "May" && $month != "June" && $month != "July"
             && $month != "August" && $month != "September" && $month != "October" 
             && $month != "November" && $month != "December") {
            $this->data['status'] = "error";
        }
        else{
            $this->data['status'] = "success";
            $this->data['finalData'] = getDayOfMonth($month);
        }

        echo json_encode($this->data);
    }
    public function addHoliday(){
        $month = $this->input->post('month');
        $day= $this->input->post('day');
        $name= $this->input->post('name');
        $type= $this->input->post('type');
        $current_date = getDateDate();
        $this->form_validation->set_rules('month','month','required',array('required'=>'Please select a month.'));
        $this->form_validation->set_rules('day','day','required',array('required'=>'Please select a day.'));
        $this->form_validation->set_rules('name','name','required',array('required'=>'Please enter a name.'));
        $this->form_validation->set_rules('type','type','required',array('required'=>'Please select a type.'));
        $this->form_validation->set_rules('date','date','is_unique[tb_holiday.holiday_date]', array(
            'is_unique'=>'<strong>'.$month. ' '.$day.'</strong> already exist'
        ));
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = validation_errors(); 
        }
        else{
            if ($month != "January" && $month != "February" && $month != "March" && $month != "April" && $month != "May" && $month != "June"
            && $month != "July" && $month != "August" && $month != "September" && $month != "October" && $month != "November" && $month != "December"){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid month or day."; 
            }
            else if (($month == "January" || $month == "March" || $month == "May" || $month == "July" || $month == "August" || $month == "October" || $month == "December") && ($day <=0 || $day >= 32)){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid month or day."; 
            }
            else if (($month == "February" && $total_day == 28) && ($day <=0 || $day >=29) || ($month == "February" && $total_day == 29) && ($day <=0 || $day >=30)){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid month or day."; 
            }
            else if (($month == "April" || $month == "June" || $month == "September" || $month == "November") && ($day <=0 || $day >= 31)){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid month or day."; 
            }

            // chec if the type of holiday is equal to the needed type
            else if ($type != "Regular Holiday" && $type != "Special non-working day"){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid holiday type."; 
            }
            else{
                $holiday_date = $month . " " . $day;
                $insertData = array(
                    'holiday_id'=>'',
                    'holiday_date'=>$holiday_date,
                    'holiday_value'=>$name,
                    'holiday_type'=>$type,
                    'DateCreated'=>$current_date,
                );
                $insert = $this->holiday_model->insert_holiday($insertData);
                $this->data['status'] = "success";
                $this->data['msg'] = "The <strong>".$name."</strong> was successfully added to <strong>".$type."</strong>";
            }
        }
        echo json_encode($this->data);
    }
    //for get add holiday end
}