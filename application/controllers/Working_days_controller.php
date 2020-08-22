<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Working_days_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('dashboard');
        }
        $this->load->model("login_model", 'login_model');
        $this->load->model("working_days_model",'working_days_model');
        $this->load->model("employee_model",'employee_model');
        $this->load->model("holiday_model",'holiday_model');
	}
    public function getOverTimeType(){
        $date = $this->input->post('date');
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $bio_id = $employeeInfo['bio_id'];
        $row_wd = $this->working_days_model->get_working_days_info($employeeInfo['working_days_id']);
        $finalHolidayType = "";

        $day_from = $row_wd['day_from'];
	    $day_to = $row_wd['day_to'];

        $date_create = date_create($_POST["date"]);
        $day = date_format($date_create, 'l');

        $day_of_the_week = date_format($date_create, 'w');

        // if ($day_of_the_week >= $day_from && $day_of_the_week <= $day_to){

        // }
        // //rest day
        // else {
    
        // }
        $day_month = date_format($date_create, 'j');
        $month = date_format($date_create, 'F');
        $holiday_date = $month." ".$day_month;
        $holiday = $this->holiday_model->get_holiday_date($holiday_date);

        if(!empty($holiday)){
            $holidayType = $holiday['holiday_type'];
            if ($holidayType != "Regular Holiday") {
                $finalHolidayType = "Special Holiday";
            }
            if ($day_of_the_week >= $day_from && $day_of_the_week <= $day_to){
               $finalHolidayType = $holidayType;

            }
            else {
                $finalHolidayType = 'Restday / '.$holidayType;
            }
        }
        else{
            if ($day_of_the_week >= $day_from && $day_of_the_week <= $day_to){
                $finalHolidayType = "Regular";
            }
    
            else {
                $finalHolidayType =  "Restday";
            }
        }
        $this->data['finalHolidayType'] = $finalHolidayType;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function viewWorkingSchedule(){
        $workingDays = $this->working_days_model->get_all_working_days();
        $finalWorkingDays = array();
        if(!empty($workingDays)){
            foreach($workingDays as $value){
                $alreadyUsed = $this->employee_model->get_working_days_of_employee($value->working_days_id);
                $day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
                $working_days = $day_of_the_week[$value->day_from] . "-" . $day_of_the_week[$value->day_to];
                $action = "no";
                if(empty($alreadyUsed)){
                    $emp_id = $this->session->userdata('user');
                    if($emp_id != 21){
                        $action = 'yes';
                    }
                }
                array_push($finalWorkingDays, array(
                    'working_days_id'=>$value->working_days_id,
                    'working_days'=>$working_days,
                    'action'=>$action,
                ));
                
            }
            $this->data['finalWorkingDays'] = $finalWorkingDays;
        }

        $this->data['pageTitle'] = 'Working Hours and Days';
        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('working_schedule/working_schedule');
        $this->load->view('global/footer');
    }
}

