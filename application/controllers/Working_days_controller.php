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
        $this->load->model("audit_trial_model", "audit_trial_model");
        $this->load->helper('hupay_helper');
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
    public function addWorkingDays(){
        $emp_id = $this->session->userdata('user');
        $dayFrom = $this->input->post('dayFrom');
        $dayTo = $this->input->post('dayTo');
        $this->form_validation->set_rules('dayFrom', 'dayFrom', 'required');
        $this->form_validation->set_rules('dayTo','dayTo','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required.";
            
        }
        else{
            $days = array(0,1,2,3,4,5,6);
            $proceed = true;
            if(!in_array($dayFrom, $days)){
                $proceed = false;
                $this->data['pasok'] = 'asd';
            }
            if(!in_array($dayTo, $days)){
                $proceed = false;
            }
            if($proceed){
                if($dayFrom > $dayTo){
                    $this->data['status'] = "error";
                    $this->data['msg'] = '<strong>Day From</strong> must be not greater than <strong>Day To</strong>.';
                }
                else if($dayFrom == $dayTo){
                    $this->data['status'] = "error";
                    $this->data['msg'] = '<strong>Day From</strong> must be not equal to <strong>Day To</strong>';
                }
                else{
                    $day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
                    $working_days = $day_of_the_week[$dayFrom] . "-" . $day_of_the_week[$dayTo];
                    
                    $checkWorkingDays = $this->working_days_model->check_working_days($dayFrom, $dayTo);
                    
                    if(!empty($checkWorkingDays)){
                        $this->data['status'] = "error";
                        $this->data['msg'] = "<strong>$working_days</strong> already exist.";
                    }
                    else{
                        $insertWorkingDaysData = array('day_from'=>$dayFrom, 'day_to'=>$dayTo);
                        $insertWorkingDays = $this->working_days_model->insert_working_days($insertWorkingDaysData);

                        $dateTime = getDateTime();
                        $module = "Working Days";
                        $insertAuditTrialData = array(
                            'audit_trail_id'=>'',
                            'file_emp_id'=>0,
                            'approve_emp_id'=>0,
                            'involve_emp_id'=>$emp_id,
                            'module'=>$module,
                            'task_description'=>"Add working days of <b>$working_days</b>",
                        );
                        $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
                        $this->data['status'] = "success";
                        $this->data['msg'] = "Working days of <strong>$working_days</strong> was successfully saved.";
                    }
                }
            }
            else{
                $this->data['msg'] = "There was a problem, please try again.";
                $this->data['status'] = "error";
            }
            
        }
        echo json_encode($this->data);
    }

    public function viewUpdateWorkingDays(){
        $id = $this->input->post('id');

        $checkWorkingDays = $this->working_days_model->get_working_days_info($id);
        if(!empty($checkWorkingDays)){
            $day_from = $checkWorkingDays['day_from'];
            $day_to = $checkWorkingDays['day_to'];
            $this->data['status'] = "success";
            $this->data['day_from'] = $day_from;
            $this->data['day_to'] = $day_to;
        }
        else{
            $this->data['status'] = "error";
            $this->data['msg'] = "There was a problem, please try again.";
        }
        echo json_encode($this->data);
    }
    public function updateWorkingDays(){
        $emp_id = $this->session->userdata('user');
        $id = $this->input->post('id');
        $dayFrom = $this->input->post('dayFrom');
        $dayTo = $this->input->post('dayTo');
        $this->form_validation->set_rules('dayFrom', 'dayFrom', 'required');
        $this->form_validation->set_rules('dayTo','dayTo','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required.";
            
        }
        else{
            $days = array(0,1,2,3,4,5,6);
            $proceed = true;
            if(!in_array($dayFrom, $days)){
                $proceed = false;
                $this->data['pasok'] = 'asd';
            }
            if(!in_array($dayTo, $days)){
                $proceed = false;
            }
            if($proceed){
                if($dayFrom > $dayTo){
                    $this->data['status'] = "error";
                    $this->data['msg'] = '<strong>Day From</strong> must be not greater than <strong>Day To</strong>.';
                }
                else if($dayFrom == $dayTo){
                    $this->data['status'] = "error";
                    $this->data['msg'] = '<strong>Day From</strong> must be not equal to <strong>Day To</strong>';
                }
                else{
                    $day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
                    $working_days = $day_of_the_week[$dayFrom] . "-" . $day_of_the_week[$dayTo];
                    
                    $checkWorkingDays = $this->working_days_model->check_working_days($dayFrom, $dayTo);
                    $checkIfNoChanges = $this->working_days_model->check_working_days_has_no_changes($dayFrom, $dayTo, $id);
                    if(!empty($checkIfNoChanges)){
                        $this->data['status'] = "error";
                        $this->data['msg'] = "No updates were taken, no changes was made.";
                    }
                    else if(!empty($checkWorkingDays)){
                        $this->data['status'] = "error";
                        $this->data['msg'] = "<strong>$working_days</strong> already exist.";
                    }
                    else{
                        $updateWorkingDaysData = array('day_from'=>$dayFrom, 'day_to'=>$dayTo);
                        $updateWorkingDays = $this->working_days_model->update_working_days($id,$updateWorkingDaysData);
                        if($updateWorkingDays == "success"){
                            $dateTime = getDateTime();
                            $module = "Working Days";
                            $insertAuditTrialData = array(
                                'audit_trail_id'=>'',
                                'file_emp_id'=>0,
                                'approve_emp_id'=>0,
                                'involve_emp_id'=>$emp_id,
                                'module'=>$module,
                                'task_description'=>"Edit working days of <b>$working_days</b>",
                            );
                            $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
                            $this->data['status'] = "success";
                            $this->data['msg'] = "Working days of <strong>$working_days</strong> was successfully updated to working hours list.";
                        }
                        else{
                            $this->data['status'] = "error";
                            $this->data['msg'] = "There was a problem, please try again.";
                        }
                    }
                }
            }
            else{
                $this->data['msg'] = "There was a problem, please try again.";
                $this->data['status'] = "error";
            }
        }

        echo json_encode($this->data);
    }
    public function deleteWorkingDays(){
        $id = $this->input->post('id');
        $emp_id = $this->session->userdata('user');
        $checkIfNotUsed = $this->employee_model->get_working_days_of_employee($id);
        if(empty($checkIfNotUsed)){
            $workingDays = $this->working_days_model->get_working_days_info($id);
            $day_from = $workingDays['day_from'];
            $day_to = $workingDays['day_to'];

            $day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
            $day_of_the_week_value = [0,1,2,3,4,5,6];

            $count = count($day_of_the_week);

            $counter = 0;

            $day_from_value = "";
            $day_to_value = "";

            for($countDays = 0; $countDays < $count; $countDays++){
                if ($day_of_the_week_value[$countDays] == $day_from){
                    $day_from_value = $day_of_the_week[$countDays];
                }
    
                if ($day_of_the_week_value[$countDays] == $day_to){
                    $day_to_value = $day_of_the_week[$countDays];
                }
            }
            $deleteWorkingDays = $this->working_days_model->delete_working_days($id);
            if($deleteWorkingDays == "success"){
                
                $dateTime = getDateTime();
                $module = "Working Days";
                $insertAuditTrialData = array(
                    'audit_trail_id'=>'',
                    'file_emp_id'=>0,
                    'approve_emp_id'=>0,
                    'involve_emp_id'=>$emp_id,
                    'module'=>$module,
                    'task_description'=>"Delete working days of <b>'.$day_from_value.' - '.$day_to_value.'</b>",
                );
                $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
                $this->data['status'] = "success";
                $this->data['msg'] = '<strong>Working Days</strong> of <strong>'.$day_from_value.' - '.$day_to_value.'</strong> was successfully deleted.';
            }
            else{
                $this->data['msg'] = "There was a problem removing the working days, please try again.";
            }
            $this->data['status'] = $deleteWorkingDays;
        }
        else{
            $this->data['status'] = "error";
            $this->data['msg'] = "You can't delete a <strong>Working Days</strong> that is being used by other information.";
        }
        echo json_encode($this->data);
    }
}

