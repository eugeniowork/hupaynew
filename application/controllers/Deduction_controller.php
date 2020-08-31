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
        $this->load->helper('minimum_wage_helper');

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
                        $finalYearTotalDeductionData .= "<button  id=".$value->ytd_id." class='btn btn-outline-success btn-sm edit-emp-yearly-deduction-btn' data-toggle='modal' data-target='#updateYearTotalDeductionModal'><i id=".$value->ytd_id." class='fas fa-edit'></i>&nbsp;Edit</button>";
                    $finalYearTotalDeductionData .= "</center></td>";
                $finalYearTotalDeductionData .= "</tr>";
            }
        }
        $this->data['finalYearTotalDeductionData'] = $finalYearTotalDeductionData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function getYearTotalDeductionInfo(){
        $id = $this->input->post('id');

        $min_wage = getMinimumWage();
        $yearTotalDeduction = $this->deduction_model->get_yearly_deduction_by_id($id);
        $finalData = array();
        if(!empty($yearTotalDeduction)){
            $row_emp = $this->employee_model->employee_information($yearTotalDeduction['emp_id']);
            if ($row_emp['Middlename'] == ""){
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }
            else {
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            }

            $ytdGross = $yearTotalDeduction['ytd_Gross'];
            $ytdAllowance = $yearTotalDeduction['ytd_Allowance'];
            $ytdTax = $yearTotalDeduction['ytd_Tax'];
            $year = $yearTotalDeduction['Year'];
            $ytdTaxStatus = 'readonly';
            if($row_emp['Salary'] > $min_wage){
                $ytdTaxStatus = 'editable';
            }
            array_push($finalData, array(
                'name'=>$full_name,
                'ytd_gross'=>$ytdGross,
                'ytd_allowance'=>$ytdAllowance,
                'ytd_tax'=>$ytdTax,
                'ytd_tax_status'=>$ytdTaxStatus,
                'year'=>$year

            ));
            $this->data['finalData'] = $finalData;
            $this->data['status'] = "success";
        }
        else{
            $this->data['msg'] = "There was a problem getting the year total deduction information. Please try again";
            $this->data['status'] = "error";
        }
        
        echo json_encode($this->data);
    }

    public function updateYTD(){
        $id = $this->input->post('id');
        $ytdGross = $this->input->post('ytdGross');
        $ytdAllowance = $this->input->post('ytdAllowance');
        $ytdTax = $this->input->post('ytdTax');

        $this->form_validation->set_rules('ytdGross', 'ytdGross', 'required');
        $this->form_validation->set_rules('ytdAllowance', 'ytdAllowance', 'required');
        $this->form_validation->set_rules('ytdTax', 'ytdTax', 'required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required";
        }
        else{
            $checkYTDInfo = $this->deduction_model->if_no_changes_in_update_ytd($ytdGross, $ytdAllowance, $ytdTax, $id);
            if(!empty($checkYTDInfo)){
                $this->data['msg'] = "There's no changes in the data.";
                $this->data['status'] = "error";
            }
            else{
                $updateData = array(
                    'ytd_Gross'=>$ytdGross,
                    'ytd_Allowance'=>$ytdAllowance,
                    'ytd_Tax'=>$ytdTax,
                );


                $this->deduction_model->update_ytd($id,$updateData);
                $this->data['status'] = "success";
            }
            
        }

        
        echo json_encode($this->data);
    }
}