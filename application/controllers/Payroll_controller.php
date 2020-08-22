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
    }
    public function index(){
        $this->data['pageTitle'] = 'Generate Payroll';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('payroll/generate_payroll');
        $this->load->view('global/footer');
    }
    public function generatePayroll(){
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

            
            $this->data['sample'] = $working_days_count;
            $counter++;
        }
        while($counter < $count);
        
        echo json_encode($this->data);
    }
}