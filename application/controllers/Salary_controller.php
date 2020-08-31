<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        //$this->load->model("adjustment_loan_model", 'adjustment_loan_model');
        $this->load->model("employee_model", "employee_model");
         $this->load->model("allowance_model", "allowance_model");
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model('holiday_model','holiday_model');
        // $this->load->model('leave_model','leave_model');
        // $this->load->model('working_hours_model','working_hours_model');
        // $this->load->model('working_days_model','working_days_model');
        //$this->load->helper('hupay_helper');
        // $this->load->helper('attendance_helper');
        // $this->load->helper('date_helper');
        // $this->load->helper('leave_helper');
        //$this->load->library('../controllers/holiday_controller');

    }
    public function index(){
        $this->data['pageTitle'] = 'Salary Information';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('salary_information/salary_information');
        $this->load->view('global/footer');
    }
    public function getEmployeeSalaryInformation(){
        $select_qry = $this->employee_model->get_all_employee_order_by_lastname();
        $finalSalaryInformationData ="";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {

                $finalSalaryInformationData .='<tr>';
                $finalSalaryInformationData .='<td>'.$value->Lastname . ", " . $value->Firstname . " " . $value->Middlename.'</td>';
                $finalSalaryInformationData .='<td>'.number_format($value->Salary,2).'</td>';


                $select_qry_allowance = $this->allowance_model->get_info_allowance($value->emp_id);

                $total_allowance = 0;
                //$allowance = 0;
                if(empty($select_qry_allowance)){
                    $finalSalaryInformationData .='<td>No Allowance</td>';
                }
                else{
                    $finalSalaryInformationData .="<td>";
                    $finalSalaryInformationData .="<table class='table table-border table-dark'>";
                        $finalSalaryInformationData .="<thead>";
                            $finalSalaryInformationData .="<tr>";
                                $finalSalaryInformationData .="<th>Allowance Type</th>";
                                $finalSalaryInformationData .="<th>Value</th>";
                            $finalSalaryInformationData .="</tr>";
                        $finalSalaryInformationData .="</thead>";

                        $finalSalaryInformationData .="<tbody>";
                    foreach ($select_qry_allowance as $valueAllowance) {
                        
                        $total_allowance +=$valueAllowance->AllowanceValue;
                        $finalSalaryInformationData .="<tr>";
                            $finalSalaryInformationData .="<td>".$valueAllowance->AllowanceType."</td>";

                            $finalSalaryInformationData .="<td>".number_format($valueAllowance->AllowanceValue,2)."</td>";
                        $finalSalaryInformationData .="</tr>";
                                
                    }
                        $finalSalaryInformationData .="</tbody>";

                    $finalSalaryInformationData .="</table>";
                    $finalSalaryInformationData .="</td>";
                }

                $finalSalaryInformationData .="<td>".number_format($value->Salary + $total_allowance, 2)."</td>";
                $finalSalaryInformationData .='</tr>';
            }
        }


        $this->data['finalSalaryInformationData'] = $finalSalaryInformationData;
        $this->data['status'] = "success";
        echo json_encode($this->data);

    }
}