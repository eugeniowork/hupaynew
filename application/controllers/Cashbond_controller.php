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
        $this->load->helper('date_helper');
        $this->load->helper('hupay_helper');
        $this->load->helper('allowance_helper');
        $this->load->helper('cashbond_helper');
        
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
    public function getCashbondHistory(){
        $cashbond_id = $this->input->post('id');
        $checkCashbond = $this->cashbond_model->get_cashbond_by_id($cashbond_id);
        $finalCashbondHistoryEmployeeData = array();
        $finalCashbondHistoryData = array();
        if(!empty($checkCashbond)){
            $emp_id = $checkCashbond['emp_id'];

            
            $current_date = dateFormat(getDateDate());
            $row_emp = $this->employee_model->employee_information($emp_id);
            $fullName = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            if ($row_emp['Middlename'] == ""){
                $fullName = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }

            $tmpGetEndingBalance = $this->cashbond_model->get_cashbond_current_ending_balance_order_by($emp_id);
            $percentage = "5%";
            //echo ;
            if (str_replace(",","",str_replace("Php","",moneyConvertion($tmpGetEndingBalance['cashbond_balance']))) >= 30000){
                $percentage = "7%";
            }

            $totalDebits = getTotalDebitsCashbondHistory($emp_id);
            $totalInterestEarned = getTotalInterestEarnedCashbondHistory($emp_id);
            array_push($finalCashbondHistoryEmployeeData, array(
                'date'=>$current_date,
                'name'=>$fullName,
                'percentage'=>$percentage,
                'total_credit'=>moneyConvertion($tmpGetEndingBalance['cashbond_balance']),
                'total_debits'=>moneyConvertion($totalDebits),
                'total_interest'=>moneyConvertion($totalInterestEarned),
            ));

            $tmpCashbondHistory = $this->cashbond_model->get_all_employee_cashbond_history($emp_id, 'posting_date', 'ASC');
            if(!empty($tmpCashbondHistory)){
                foreach($tmpCashbondHistory as $value){
                    $posting_date = date_format(date_create($value->posting_date), 'F d, Y');
                    array_push($finalCashbondHistoryData,array(
                        'emp_cashbond_history' => $value->emp_cashbond_history,
                        'posting_date'=>$posting_date,
                        'cash_deposit'=>moneyConvertion($value->cashbond_deposit),
                        'interset'=>moneyConvertion($value->interest),
                        'amount_withdraw'=>moneyConvertion($value->amount_withdraw),
                        'reference_no'=>$value->reference_no,
                        'cashbond_balance'=>$value->cashbond_balance,
                    ));
                    
                }
            }



            $this->data['status'] = "success";
            $this->data['finalCashbondHistoryEmployeeData'] = $finalCashbondHistoryEmployeeData;
            $this->data['finalCashbondHistoryData'] = $finalCashbondHistoryData;
        }
        else{
            $this->data['status'] = "error";
            $this->data['msg'] = "There was a problem getting the cashbond history, please try again.";
        }


        
        echo json_encode($this->data);
    }
}