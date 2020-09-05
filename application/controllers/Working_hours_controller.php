<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Working_hours_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("working_hours_model", 'working_hours_model');
        //$this->load->model("working_days_model",'working_days_model');
        //$this->load->model("employee_model",'employee_model');
        //$this->load->model("holiday_model",'holiday_model');
        //$this->load->model("audit_trial_model", "audit_trial_model");
        //$this->load->helper('hupay_helper');
	}
    public function getWorkingHoursDropDown(){
        $select_qry = $this->working_hours_model->get_all_working_hours();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $timeFrom = date_format(date_create($value->timeFrom), 'g:i A');
                $timeTo = date_format(date_create($value->timeTo), 'g:i A');

                $working_hours = $timeFrom . "-" . $timeTo;

                $finalData .= "<option value='".$value->working_hours_id."'>".$working_hours ."</option>";
            }
        }

        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }
}