<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("employee_model", 'employee_model');
        $this->load->model('working_days_model','working_days_model');
        $this->load->model('company_model','company_model');
        $this->load->model('department_model','department_model');
        $this->load->model('minimum_wage_model','minimum_wage_model');
        $this->load->model('dependent_model','dependent_model');
        $this->load->model('bir_model','bir_model');
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
        $this->load->helper('cut_off_helper');
        $this->load->helper('holiday_helper');
        $this->load->helper('employee_helper');
        $this->load->helper('attendance_helper');
    }
    public function index(){
        $this->data['pageTitle'] = 'Generate Payroll';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('payroll/generate_payroll');
        $this->load->view('global/footer');
    }
    public function generatePayroll(){

        $sample = "";
        $count = $this->employee_model->get_active_employee();
        $count = count($count);
        $counter = 0;
        $cut_off_count = getCutOffAttendanceDateCount();
        $holiday_cut_off_count = holidayCutOffTotalCount();
        $all_emp_id = getEmpIdAllActiveEmp();
        $emp_values = explode("#",$all_emp_id);
        do{
            $emp_id = $emp_values[$counter];
            $row = $this->employee_model->employee_information($emp_id);
            $row_wd = $this->working_days_model->get_working_days_info($row['working_days_id']);
            $day_from = $row_wd['day_from'];
		    $day_to = $row_wd['day_to'];
            
            $working_days_count = getCutOffAttendanceDateCountToPayroll($day_from, $day_to);
            $days = $working_days_count;

            $is_increase = checkExistIncreaseCutOff($emp_id);
            $gross_income_inc = 0;

            $row_company = $this->company_model->get_company_info($row['company_id']);
            $logo_source = $row_company['logo_source'];

            $row_dept = $this->department_model->get_department($row['dept_id']);
            $min_wage = $this->minimum_wage_model->get_minimum_wage();
            $min_wage = ($min_wage['basicWage'] + $min_wage['COLA']) * 26;

            $taxCode = "";
            $tax = 0;

            if ($row['Salary'] > $min_wage){
                $dependentCount = $this->dependent_model->get_dependent_rows($emp_id);
                $taxStatus = $this->bir_model->get_bir_status_to_payroll($dependentCount)['Status'];
                $civilStatus = $row['CivilStatus'];

                if ($dependentCount == 0){
                    $dependentCount = "";
                }

                if ($civilStatus == "Single"){
                    $taxCode = "S" . $dependentCount;
                }

                else {
                    $taxCode = "ME" . $dependentCount;
                }
            }
            $regularOTmin = round(getOvertimeRegularOt($emp_id)/60,2);


            $sample .= " ".$regularOTmin;
            $counter++;
        }
        while($counter < $count);
        $this->data['sample'] = $sample;
        echo json_encode($this->data);
    }
}