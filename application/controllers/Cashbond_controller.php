<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashbond_controller extends CI_Controller{
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
        $this->load->model("cashbond_model", 'cashbond_model');
    }
    public function index(){
        $this->data['pageTitle'] = 'Cashbond';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('cashbond/cashbond');
        $this->load->view('global/footer');
    }
    public function getCashbondInfo(){
        $select_qry = $this->cashbond_model->get_all_cashbond();
        $finalCashbondData = array();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_emp_qry = $this->employee_model->employee_information($value->emp_id);
                if($select_emp_qry['ActiveStatus'] == "1"){
                    $fullName = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];

                    array_push($finalCashbondData, array(
                        'fullname'=>$fullName,
                        'cashbond_id'=>$value->cashbond_id,
                        'cashbond_value'=>number_format($value->cashbondValue,2),
                        'total_cashbond'=>number_format($value->totalCashbond,2),

                    ));
                }
            }
            $this->data['finalCashbondData'] = $finalCashbondData;
            $this->data['status'] = "success";
            
        }
        else{
            $this->data['status'] = "error";
        }
        
        echo json_encode($this->data);
    }
}