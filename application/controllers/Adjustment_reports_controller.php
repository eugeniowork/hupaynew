<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjustment_reports_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("adjustment_loan_model", 'adjustment_loan_model');
        $this->load->model("employee_model", "employee_model");
        // $this->load->model("payroll_model", "payroll_model");
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model('holiday_model','holiday_model');
        // $this->load->model('leave_model','leave_model');
        // $this->load->model('working_hours_model','working_hours_model');
        // $this->load->model('working_days_model','working_days_model');
        $this->load->helper('hupay_helper');
        // $this->load->helper('attendance_helper');
        // $this->load->helper('date_helper');
        // $this->load->helper('leave_helper');
        //$this->load->library('../controllers/holiday_controller');

    }
    public function index(){
        $this->data['pageTitle'] = 'SIMKIMBAN Adjustment Reports';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('adjustment_reports/simkimban_adjustment');
        $this->load->view('global/footer');
    }
    public function getSimkimbanAdjustmentReport(){
        $select_qry = $this->adjustment_loan_model->get_all_adjustment_loan_with_type('Simkimban');
        $finalAdjustmentReportsData = array();
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {

                $select_emp_qry = $this->employee_model->employee_information($value->emp_id);

                $fullName = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];

                $date_create = date_create($value->datePayment);
                $datePayment = date_format($date_create, 'F d, Y');

                array_push($finalAdjustmentReportsData, array(
                    'adjustment_loan_id'=>$value->adjustment_loan_id,
                    'name'=>$fullName,
                    'date_payment'=>$datePayment,
                    'loan_type'=>$value->loanType,
                    'cash_payment'=>moneyConvertion($value->cashPayment),
                    'outstanding_balance'=>moneyConvertion($value->remainingBalance),
                ));
            }
        }
        $this->data['status'] = "success";
        $this->data['finalAdjustmentReportsData'] = $finalAdjustmentReportsData;

        echo json_encode($this->data);
    }
    public function viewLoanAdjustment(){
        $this->data['pageTitle'] = 'Loan Adjustment Reports';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('adjustment_reports/loan_adjustment');
        $this->load->view('global/footer');
    }
    public function getLoanAdjustmentReport(){
        
        $select_qry = $this->adjustment_loan_model->get_all_adjustment_loan_with_type_but_not_simkimban();
        $finalAdjustmentReportsData = array();
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {

                $select_emp_qry = $this->employee_model->employee_information($value->emp_id);

                $fullName = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];

                $date_create = date_create($value->datePayment);
                $datePayment = date_format($date_create, 'F d, Y');

                array_push($finalAdjustmentReportsData, array(
                    'adjustment_loan_id'=>$value->adjustment_loan_id,
                    'name'=>$fullName,
                    'date_payment'=>$datePayment,
                    'loan_type'=>$value->loanType,
                    'cash_payment'=>moneyConvertion($value->cashPayment),
                    'outstanding_balance'=>moneyConvertion($value->remainingBalance),
                ));
            }
        }
        $this->data['status'] = "success";
        $this->data['finalAdjustmentReportsData'] = $finalAdjustmentReportsData;

        echo json_encode($this->data);
    }
}