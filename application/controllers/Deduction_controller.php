<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deduction_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        //$this->load->model("adjustment_loan_model", 'adjustment_loan_model');
        //$this->load->model("employee_model", "employee_model");
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
        $this->load->model("deduction_model", 'deduction_model');
        $this->load->model("employee_model", "employee_model");

    }
    public function index(){
        $this->data['pageTitle'] = 'Year Total Deduction';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('year_total_deduction/year_total_deduction');
        $this->load->view('global/footer');
    }
    public function getYearTotalDeduction(){
        $year = date("Y");

        $select_qry = $this->deduction_model->get_yearly_deduction_by_year_date($year);
        $finalYearTotalDeductionData = '';
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $emp_id = $value->emp_id;
                $select_emp_qry = $this->employee_model->employee_information($emp_id);
                if ($select_emp_qry['Middlename'] == ""){
                    $full_name = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'];
                }
                else {
                    $full_name = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];
                }
                $finalYearTotalDeductionData .= "<tr id='".$value->ytd_id."'>";
                    $finalYearTotalDeductionData .= "<td>".$full_name."</td>";
                    $finalYearTotalDeductionData .= "<td>Php ".moneyConvertion($value->ytd_Gross)."</td>";
                    $finalYearTotalDeductionData .= "<td>Php ".moneyConvertion($value->ytd_Allowance)."</td>";
                    $finalYearTotalDeductionData .= "<td>Php ".moneyConvertion($value->ytd_Tax)."</td>";
                    $finalYearTotalDeductionData .= "<td><center>";
                        $finalYearTotalDeductionData .= "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_ytd' class='action-a'>Edit</a>";
                    $finalYearTotalDeductionData .= "</center></td>";
                $finalYearTotalDeductionData .= "</tr>";
            }
        }
        $this->data['finalYearTotalDeductionData'] = $finalYearTotalDeductionData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
}