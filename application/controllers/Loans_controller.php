<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loans_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("pagibig_model", 'pagibig_model');
        $this->load->model("employee_model", "employee_model");
        // $this->load->model("payroll_model", "payroll_model");
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model('holiday_model','holiday_model');
        // $this->load->model('leave_model','leave_model');
        // $this->load->model('working_hours_model','working_hours_model');
        // $this->load->model('working_days_model','working_days_model');
        $this->load->helper('hupay_helper');
        // $this->load->helper('attendance_helper');
        $this->load->helper('date_helper');
        // $this->load->helper('leave_helper');
        //$this->load->library('../controllers/holiday_controller');

    }
    public function index(){
        $this->data['pageTitle'] = 'Pag-ibig Loan';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('loans/pagibig_loan');
        $this->load->view('global/footer');
    }

    public function getEmployeeWithExistingPagibig(){
        $select_qry = $this->pagibig_model->get_all_pagibig_loan();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $select_query_emp = $this->employee_model->employee_information($value->emp_id);

                $emp_name = $select_query_emp['Lastname'] . ", " . $select_query_emp['Firstname'] . " " . $select_query_emp['Middlename'];


                $date_create = date_create($value->dateFrom);
                $dateFrom = date_format($date_create, 'F d, Y');

                $date_create = date_create($value->dateTo);
                $dateTo = date_format($date_create, 'F d, Y');

                $date_range = $dateFrom . "- " .$dateTo;

                if ($value->remainingBalance != 0) {
                    $finalData .= "<tr id='".$value->pagibig_loan_id."'>";
                         $finalData .= "<td><small>".$emp_name."</small></td>";
                         $finalData .= "<td><small>".$date_range."</small></td>";
                         $finalData .= "<td><small>Php ".moneyConvertion($value->amountLoan)."</small></td>";
                         $finalData .= "<td><small>Php ".moneyConvertion($value->deduction)."</small></td>";
                         $finalData .= "<td><small>Php ".moneyConvertion($value->remainingBalance)."</small></td>";
                         $finalData .= "<td><small>";
                             $finalData .= "<button id=".$value->pagibig_loan_id." class='edit-pagibig-btn btn btn-sm btn-outline-success' data-toggle='modal' data-target='#editPagibigModal'>Edit</button>&nbsp;";
                             $finalData .= "<button class='btn btn-sm btn-outline-primary'>Adjustment</button>&nbsp;";
                             $finalData .= "<button class='btn btn-sm btn-outline-danger'>Delete</button>";
                         $finalData .= "</small></td>";
                     $finalData .= "</tr>";
                }
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    public function getPagibigInfo(){
        $id = $this->input->post('id');
        $finalData = array();
        $pagibigLoan = $this->pagibig_model->get_pagibig_loan($id);
        if(!empty($pagibigLoan)){
            $row_emp = $this->employee_model->employee_information($pagibigLoan['emp_id']);

            if ($row_emp['Middlename'] == ""){
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }
            else {
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            }

            $dateFrom =dateDefault($pagibigLoan['dateFrom']);
            $dateTo = dateDefault($pagibigLoan['dateTo']);
            $amountLoan = $pagibigLoan['amountLoan'];
            $deduction = $pagibigLoan['deduction'];
            $remainingBalance = $pagibigLoan['remainingBalance'];
            array_push($finalData, array(
                'name'=>$full_name,
                'date_from'=>$dateFrom,
                'date_to'=>$dateTo,
                'amount_loan'=>$amountLoan,
                'deduction'=>$deduction,
                'remaining_balance'=>$remainingBalance,
            ));

            $this->data['status'] = "success";
            $this->data['finalData'] = $finalData;
        }
        else{
         
            $this->data['status'] = "error";
            $this->data['msg'] = "There was a problem getting the pag-ibig information, please try again.";
        }
        echo json_encode($this->data);
    }

    public function updatePagibigInfo(){
        $id = $this->input->post('id');
        $dateFrom= dateDefaultDb($this->input->post('dateFrom'));
        $dateTo= dateDefaultDb($this->input->post('dateTo'));
        $amountLoan= $this->input->post('amountLoan');
        $deduction= $this->input->post('deduction');
        $remainingBalance= $this->input->post('remainingBalance');
        $name = $this->input->post('name');
        $this->form_validation->set_rules('dateFrom','dateFrom','required');
        $this->form_validation->set_rules('dateTo','dateTo','required');
        $this->form_validation->set_rules('amountLoan','amountLoan','required');
        $this->form_validation->set_rules('deduction','deduction','required');
        $this->form_validation->set_rules('remainingBalance','remainingBalance','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required.";
        }
        else{
            if($dateFrom >= $dateTo){
                $this->data['status'] = "error";
                $this->data['msg'] = "The <strong>Date From</strong> must be below the date of the declared <strong>Date To</strong>";
            }
            else{
                $updateData = array(
                    'dateFrom'=>$dateFrom,
                    'dateTo'=>$dateTo,
                    'amountLoan'=>$amountLoan,
                    'deduction'=>$deduction,
                    'remainingBalance'=>$remainingBalance,
                );

                $updateData = $this->pagibig_model->update_pagibig_loan_data($id,$updateData);

                $this->data['status'] = "success";
                $this->data['msg'] = "The Pag-ibig Loan info of <strong>".$name."</strong>";
            }
        }
        echo json_encode($this->data);
    }
}