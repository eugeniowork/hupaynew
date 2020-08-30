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
        $this->load->helper('allowance_helper');
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

    public function getEditCashbondData(){
        $id = $this->input->post('id');
        $checkCashbond = $this->cashbond_model->get_cashbond_by_id($id);
        if(!empty($checkCashbond)){
            $this->data['cashbondValue'] = $checkCashbond['cashbondValue'];
            $this->data['status'] = "success";
        }
        else{
            $this->data['status'] = "error";
        }

        
        echo json_encode($this->data);
    }
    public function updateCashbond(){
        $id = $this->input->post('id');
        $cashbondValue = $this->input->post('cashbondValue');
        $this->form_validation->set_rules('cashbondValue','cashbondValue','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please enter a cashbond value.";
        }
        else{
            $checkCashbond = $this->cashbond_model->get_cashbond_by_id($id);
            if(!empty($checkCashbond)){
                $row_emp = $this->employee_model->employee_information($checkCashbond['emp_id']);
                $salary = $row_emp['Salary'];
                $allowance = getAllowanceInfoToPayslip($checkCashbond['emp_id']);
                $tmpSalary = $salary + $allowance;
                $tmpCashBond = ($tmpSalary * .02)/2;
                $cashbond_limit = round($tmpCashBond,2);
                $getCashbondIdValue = $this->cashbond_model->get_cashbond_id_value($id,$cashbondValue);
                if(!empty($getCashbondIdValue)){
                    $this->data['status'] = "error";
                    $this->data['msg'] = "There's no changes into the cashbond value.";
                }
                else if($cashbondValue < $cashbond_limit){
                    $this->data['status'] = "error";
                    $this->data['msg'] = "Cashbond Updates must be greater than the cashbond from formula.";
                }
                else{
                    $fullName = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
                    $updateCashbondData = array(
                        'cashbondValue'=>$cashbondValue
                    );
                    $updateCashbond = $this->cashbond_model->update_cashbond_data($checkCashbond['emp_id'], $updateCashbondData);
                    $this->data['status'] = "success";
                    $this->data['msg'] = "The <strong>cashbond information</strong> of <strong>$fullName</strong> was successfully updated.";
                }
                
            }
            else{
                $this->data['status'] = "error";
                $this->data['msg'] = "There was a problem updating the cashbond value, please try again.";
            }
            
        }

        echo json_encode($this->data);
    }
}