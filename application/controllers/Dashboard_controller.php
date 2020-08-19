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
        
        
        //for running balance end


        $this->data['pageTitle'] = 'Dashboard';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('dashboard/dashboard', $this->data);
        $this->load->view('global/footer');
    }

    public function logout(){
        if($this->session->userdata('user')){
            session_destroy();
            $this->session->unset_userdata('user');
        }
        
        redirect('');
    }
}