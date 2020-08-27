<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Minimum_wage_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        // $this->load->model("employee_model", 'employee_model');
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model("payroll_model", "payroll_model");
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model('holiday_model','holiday_model');
        // $this->load->model('leave_model','leave_model');
        // $this->load->model('working_hours_model','working_hours_model');
        // $this->load->model('working_days_model','working_days_model');
        $this->load->helper('hupay_helper');
        // $this->load->helper('attendance_helper');
        $this->load->model('minimum_wage_model','minimum_wage_model');
        $this->load->helper('date_helper');
        $this->load->helper('minimum_wage_helper');
        // $this->load->helper('leave_helper');
        //$this->load->library('../controllers/holiday_controller');

    }
    public function index(){
        $this->data['pageTitle'] = 'Attendance';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('minimum_wage/minimum_wage');
        $this->load->view('global/footer');
    }
    public function addMinimumWage(){
        $basicWage = $this->input->post('basicWage');
        $cola = $this->input->post('cola');
        $currentDate = getDateDate();
        $effectiveDate = $this->input->post('effectiveDate');
        $effective_date_month = substr($effectiveDate,0,2);
	    $effective_date_day = substr(substr($effectiveDate, -7), 0,2);
        $effective_date_year = substr($effectiveDate, -4);
        $this->form_validation->set_rules('basicWage','basicWage','required');
        $this->form_validation->set_rules('cola','cola','required');
        $this->form_validation->set_rules('effectiveDate','effectiveDate','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required.";
        }
        else{
            $getSameMinimumWage = $this->minimum_wage_model->get_same_minimum_wage(dateDefaultDb($effectiveDate), $basicWage, $cola);
            if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$effectiveDate)) {
                $this->data['status'] = "error";
                $this->data['msg'] = "<strong>Effective Date</strong> not match to the current format mm/dd/yyyy.";
            }
            else if ($effective_date_year % 4 == 0 && $effective_date_month == 2 && $effective_date_day >= 30){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid effective date.";
            }
            else if ($effective_date_year % 4 != 0 && $effective_date_month == 2 && $effective_date_day >= 29){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid effective date.";
            }
            else if ($effective_date_month == 4 || $effective_date_month == 6 || $effective_date_month == 9 || $effective_date_month == 11){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid effective date.";
            }
            else if(!empty($getSameMinimumWage)){
                $this->data['status'] = "error";
                $this->data['msg'] = "The minimum wage already exist.";
            }
            else{
                $effectiveDate = dateDefaultDb($effectiveDate);
                $insertMinWageData = array(
                    'min_wage_id'=>'',
                    'basicWage'=>$basicWage,
                    'COLA'=>$cola,
                    'effectiveDate'=>$effectiveDate,
                    'dateCreated'=>$currentDate
                );
                $insertMinWage = $this->minimum_wage_model->insert_minimum_wage($insertMinWageData);
                $this->data['status'] = "success";
            }
        }
        echo json_encode($this->data);
    }
    public function getMinWage(){
        $id = $this->input->post('id');
        $minWage = $this->minimum_wage_model->get_min_wage_id($id);
        $minWageId = $minWage['min_wage_id'];
        $minWageBasicWage = $minWage['basicWage'];
        $minWageCola = $minWage['COLA'];
        $minWageEffectiveDate = $minWage['effectiveDate'];

        $this->data['minWageBasicWage'] = $minWageBasicWage;
        $this->data['minWageCola'] = $minWageCola;
        $this->data['minWageEffectiveDate'] = dateDefault($minWageEffectiveDate);

        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    public function updateMinWage(){
        $basicWage = $this->input->post('basicWage');
        $cola = $this->input->post('cola');
        $id = $this->input->post('id');
        $currentDate = getDateDate();
        $effectiveDate = $this->input->post('effectiveDate');
        $effective_date_month = substr($effectiveDate,0,2);
	    $effective_date_day = substr(substr($effectiveDate, -7), 0,2);
        $effective_date_year = substr($effectiveDate, -4);
        $this->form_validation->set_rules('basicWage','basicWage','required');
        $this->form_validation->set_rules('cola','cola','required');
        $this->form_validation->set_rules('effectiveDate','effectiveDate','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required.";
        }
        else{
            $getSameMinimumWage = $this->minimum_wage_model->get_same_minimum_wage(dateDefaultDb($effectiveDate), $basicWage, $cola);
            if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$effectiveDate)) {
                $this->data['status'] = "error";
                $this->data['msg'] = "<strong>Effective Date</strong> not match to the current format mm/dd/yyyy.";
            }
            else if ($effective_date_year % 4 == 0 && $effective_date_month == 2 && $effective_date_day >= 30){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid effective date.";
            }
            else if ($effective_date_year % 4 != 0 && $effective_date_month == 2 && $effective_date_day >= 29){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid effective date.";
            }
            else if ($effective_date_month == 4 || $effective_date_month == 6 || $effective_date_month == 9 || $effective_date_month == 11){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid effective date.";
            }
            else if(sameMinWageInfo($effectiveDate,$basicWage,$cola, $id) == 1){
                $this->data['status'] = "error";
                $this->data['msg'] = "There's no changes in the data.";
            }
            else if(!empty($getSameMinimumWage)){
                $this->data['status'] = "error";
                $this->data['msg'] = "The minimum wage already exist.";
            }
            else{
                $effectiveDate = dateDefaultDb($effectiveDate);
                $updateMinWageData = array(
                    'basicWage'=>$basicWage,
                    'COLA'=>$cola,
                    'effectiveDate'=>$effectiveDate,
                );
                $updateMinWage = $this->minimum_wage_model->update_minimum_wage($updateMinWageData, $id);
                $this->data['status'] = "success";
            }
        }
        echo json_encode($this->data);
    }
    public function removeMinWage(){
        $id = $this->input->post('id');
        $removeMinWage = $this->minimum_wage_model->delete_min_wage($id);
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    
}