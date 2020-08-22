<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("employee_model", 'employee_model');
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model("payroll_model", "payroll_model");
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model('holiday_model','holiday_model');
        // $this->load->model('leave_model','leave_model');
        // $this->load->model('working_hours_model','working_hours_model');
        // $this->load->model('working_days_model','working_days_model');
        // $this->load->helper('hupay_helper');
        // $this->load->helper('attendance_helper');
        // $this->load->helper('date_helper');
        // $this->load->helper('leave_helper');
        //$this->load->library('../controllers/holiday_controller');

    }
    public function viewATMAccounts(){
        $this->data['pageTitle'] = 'ATM Account No';
        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('atm_account/atm_account_no',$this->data);
        $this->load->view('global/footer');
    }
    public function getAtmAccountNoList(){
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);

        $employeeAtm = $this->employee_model->get_employee_atm();
        $finalListOfEmployeeAtm = array();
        if(!empty($employeeAtm)){
            foreach($employeeAtm as $value){
                $empName = ucwords($value->Lastname.", ".$value->Firstname." ".$value->Middlename);
                if($value->Middlename == ""){
                    $empName = ucwords($value->Lastname." ".$value->Firstname);
                }
                if($id != 21){
                    array_push($finalListOfEmployeeAtm, array(
                        'emp_id'=>$value->emp_id,
                        'emp_name'=>$empName,
                        'atmAccountNumber'=>$value->atmAccountNumber,
                        'action'=>'yes',
                    ));
                }
                else{
                    array_push($finalListOfEmployeeAtm, array(
                        'emp_id'=>$value->emp_id,
                        'emp_name'=>$empName,
                        'atmAccountNumber'=>$value->atmAccountNumber,
                        'action'=>'no',
                    ));
                }
            }
            $this->data['status'] = "success";
            $this->data['finalListOfEmployeeAtm'] = $finalListOfEmployeeAtm;
        }
        else{
            $this->data['status'] = "error";
        }
        echo json_encode($this->data);
    }
}