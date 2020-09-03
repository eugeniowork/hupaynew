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
        $this->load->model("adjustment_loan_model", "adjustment_loan_model");
        $this->load->model("sss_model", "sss_model");
        $this->load->model('salary_model','salary_model');
        $this->load->model('audit_trial_model', 'audit_trial_model');
        $this->load->model('payroll_model','payroll_model');
        $this->load->model('simkimban_model','simkimban_model');
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
                    $finalData .= "<tr class='pagibig-tr-".$value->pagibig_loan_id."'>";
                         $finalData .= "<td class='name-".$value->pagibig_loan_id."'><small>".$emp_name."</small></td>";
                         $finalData .= "<td><small>".$date_range."</small></td>";
                         $finalData .= "<td><small>Php ".moneyConvertion($value->amountLoan)."</small></td>";
                         $finalData .= "<td><small>Php ".moneyConvertion($value->deduction)."</small></td>";
                         $finalData .= "<td><small>Php ".moneyConvertion($value->remainingBalance)."</small></td>";
                         $finalData .= "<td><small>";
                             $finalData .= "<button id=".$value->pagibig_loan_id." class='edit-pagibig-btn btn btn-sm btn-outline-success' data-toggle='modal' data-target='#editPagibigModal'>Edit</button>&nbsp;";
                             $finalData .= "<button id=".$value->pagibig_loan_id." class='adjust-pagibig-btn btn btn-sm btn-outline-primary' data-toggle='modal' data-target='#adjustPagibigModal'>Adjustment</button>&nbsp;";
                             $finalData .= "<button id=".$value->pagibig_loan_id." class='delete-pagibig btn btn-sm btn-outline-danger'>Delete</button>";
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
    public function getAdjustPagibigInfo(){
        $id = $this->input->post('id');
        $pagibigInfo = $this->pagibig_model->get_pagibig_loan($id);
        if(!empty($pagibigInfo)){
            $row_emp = $this->employee_model->employee_information($pagibigInfo['emp_id']);
            if ($row_emp['Middlename'] == ""){
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }
            else {
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            }
            $remainingBalance = $pagibigInfo['remainingBalance'];

            $this->data['remainingBalance'] = $remainingBalance;
            $this->data['name'] = $full_name;
            $this->data['status'] = "success";
        }   
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }

    public function adjustPagibigData(){
        $id = $this->input->post('id');
        $adjustDatePayment= dateDefaultDb($this->input->post('adjustDatePayment'));
        $adjustCashPayment= $this->input->post('adjustCashPayment');
        $adjustOutstandingBalance= $this->input->post('adjustOutstandingBalance');
        $adjustNewOutstandingBalance= $this->input->post('adjustNewOutstandingBalance');
        $adjustRemarks= $this->input->post('adjustRemarks');


        $this->form_validation->set_rules('adjustDatePayment', 'adjustDatePayment','required');
        $this->form_validation->set_rules('adjustCashPayment', 'adjustCashPayment','required');
        $this->form_validation->set_rules('adjustOutstandingBalance', 'adjustOutstandingBalance','required');
        $this->form_validation->set_rules('adjustNewOutstandingBalance', 'adjustNewOutstandingBalance','required');
        $this->form_validation->set_rules('adjustRemarks', 'adjustRemarks','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All field are required";
        }
        else{
            if($adjustCashPayment > $adjustOutstandingBalance){
                $this->data['status'] = "error";
                $this->data['msg'] = "The <strong>Cash Payment Php ".moneyConvertion($adjustCashPayment)."</strong> must be not greater than the <strong>Outstanding Balance Php ".moneyConvertion($adjustOutstandingBalance)."</strong>.";
            }
            else if($adjustCashPayment == 0){
                $this->data['status'] = "error";
                $this->data['msg'] = "Cash payment must not be a zero number.";
            }
            else{
                $updateRemainingBalanceData = array(
                    'remainingBalance'=>$adjustNewOutstandingBalance
                );
                $updateRemainingBalance = $this->pagibig_model->update_pagibig_loan_data($id, $updateRemainingBalanceData);
                $pagibigInfo = $this->pagibig_model->get_pagibig_loan($id);
                $emp_id = $pagibigInfo['emp_id'];
                //$pagibig_loan_id = 0;
                $sss_loan_id = 0;
                //$sssLoanId = 0;
                $simkimban_id =0;
                $loanType = "Pagibig Loan";
                $salary_loan_id = 0;
                $current_date_time = getDateDate();

                $insertAdjustmentLoanData = array(
                    'adjustment_loan_id'=>'',
                    'emp_id'=>$emp_id,
                    'datePayment'=>$adjustDatePayment,
                    'pagibig_loan_id'=>$id,
                    'sss_loan_id'=>$sss_loan_id,
                    'salary_loan_id'=>$salary_loan_id,
                    'simkimban_id'=>$simkimban_id,
                    'loanType'=>$loanType,
                    'cashPayment'=>$adjustCashPayment,
                    'remainingBalance'=>$adjustOutstandingBalance,
                    'remarks'=>$adjustRemarks,
                    'DateCreated'=>$current_date_time,
                );
                $insertAdjustmentLoan = $this->adjustment_loan_model->insert_adjustment_loan_data($insertAdjustmentLoanData);

                $row = $this->employee_model->employee_information($emp_id);
                $fullName = $row['Lastname'] . ", " . $row['Firstname'] . " " . $row['Middlename'];

                $this->data['status'] = "success";
                $this->data['msg'] = "<strong>Pag-ibig Loan</strong> of employee <strong>".$fullName."</strong> was successfully adjusted";
            }
        }

        
        echo json_encode($this->data);
    }

    public function deletePagibigLoan(){
        $id = $this->input->post('id');

        $pagibigInfo = $this->pagibig_model->get_pagibig_loan($id);
        if(!empty($pagibigInfo)){
            $emp_id = $pagibigInfo['emp_id'];
            $row_emp = $this->employee_model->employee_information($emp_id);

            $fullName = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];

            $deletePagibig = $this->pagibig_model->delete_pagibig_loan($id);

            $this->data['status'] = "success";
            $this->data['msg'] = "<strong>Pag-ibig Loan</strong> of <strong>".$fullName."</strong> was successfully deleted.";
        }
        else{
            $this->data['status'] = "error";

        }
        echo json_encode($this->data);
    }

    public function getPagibigHistoryList(){
        $emp_id = $this->session->userdata('user');

        $select_qry = $this->pagibig_model->get_pagibig_history($emp_id);
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $row_emp = $this->employee_model->employee_information($emp_id);

                $emp_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];


                $date_create = date_create($value->dateFrom);
                $dateFrom = date_format($date_create, 'F d, Y');

                $date_create = date_create($value->dateTo);
                $dateTo = date_format($date_create, 'F d, Y');

                $date_range = $dateFrom . "- " .$dateTo;

                $status = "Current";
                if ($value->remainingBalance == 0){
                    $status = "Finish";
                }
                $finalData .= "<tr id='".$value->pagibig_loan_id."'>";
                    $finalData .= "<td>".$date_range."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->amountLoan)."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->deduction)."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->remainingBalance)."</td>";    
                    $finalData .= "<td>".$status."</td>";                        
                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function addNewPagibig(){
        $empId = $this->input->post('empId');
        $name= $this->input->post('name');
        $dateFrom= $this->input->post('dateFrom');
        $dateTo= $this->input->post('dateTo');
        $amountLoan= $this->input->post('amountLoan');
        $deduction= $this->input->post('deduction');
        $remainingBalance= $this->input->post('remainingBalance');
        $dateCreated = getDateDate();
        $this->form_validation->set_rules('name', 'name','required');
        $this->form_validation->set_rules('dateFrom', 'dateFrom','required');
        $this->form_validation->set_rules('dateTo', 'dateTo','required');
        $this->form_validation->set_rules('amountLoan', 'amountLoan','required');
        $this->form_validation->set_rules('deduction', 'deduction','required');
        $this->form_validation->set_rules('remainingBalance', 'remainingBalance','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required";
        }
        else{
            $check = $this->employee_model->employee_information($empId);
            $checkPagibig = $this->pagibig_model->get_employee_pagibig_loan($empId);
            if(empty($check)){
                $this->data['status'] = "error";
                $this->data['msg'] = "There was a problem a problem on the employee name, please try again.";
            }
            else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateFrom ) || !preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateTo)) {
                $this->data['status'] = "error";
                $this->data['msg'] = "Date From or Date To not match to the current format mm/dd/yyyy.";
            }
            else if($dateFrom >= $dateTo){
                $this->data['status'] = 'error';
                $this->data['msg'] = "The <strong>Date From</strong> must be below the date of the declared <b>Date To</b>.";
            }
            else if(!empty($checkPagibig)){
                $this->data['status'] = 'error';
                $this->data['msg'] = "Employee <strong>".$name."</strong> has an existing pag-ibig loan.";
            }
            else{
                $dateFrom = dateDefaultDb($dateFrom);
                $dateTo = dateDefaultDb($dateTo);
                $insertData = array(
                    'pagibig_loan_id'=>'',
                    'emp_id'=>$empId,
                    'dateFrom'=>$dateFrom,
                    'dateTo'=>$dateTo,
                    'amountLoan'=>$amountLoan,
                    'deduction'=>$deduction,
                    'remainingBalance'=>$remainingBalance,
                    'DateCreated'=>$dateCreated,
                );
                $insert = $this->pagibig_model->insert_pagibig_loan($empId, $insertData);
                $this->data['status'] = "success";
                $this->data['msg'] = "<strong>Pag-ibig Loan</strong> was successfully added to employee ".$name."";
            }
        }

        echo json_encode($this->data);
    }

    // for sss loans
    public function viewSssLoan(){
        $this->data['pageTitle'] = 'SSS Loan';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('loans/sss_loan');
        $this->load->view('global/footer');
    }

    public function getEmployeeWithSssPagibig(){
        $select_qry = $this->sss_model->get_all_sss_loan();
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
                    $finalData .= "<tr class='sss-tr-".$value->sss_loan_id."'>";
                        $finalData .= "<td class='name-".$value->sss_loan_id."'><small>".$emp_name."</small></td>";
                        $finalData .="<td><small>".$value->loan_type."</small></td>";
                        $finalData .= "<td><small>".$date_range."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->amountLoan)."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->deduction)."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->remainingBalance)."</small></td>";
                        $finalData .= "<td><small>";
                            $finalData .= "<button id=".$value->sss_loan_id." class='edit-sss-btn btn btn-sm btn-outline-success' data-toggle='modal' data-target='#editSssModal'>Edit</button>&nbsp;";
                            $finalData .= "<button id=".$value->sss_loan_id." class='adjust-sss-btn btn btn-sm btn-outline-primary' data-toggle='modal' data-target='#adjustSssModal'>Adjustment</button>&nbsp;";
                            $finalData .= "<button id=".$value->sss_loan_id." class='delete-sss btn btn-sm btn-outline-danger'>Delete</button>";
                        $finalData .= "</small></td>";
                     $finalData .= "</tr>";
                }
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    public function getSssInfo(){
        $id = $this->input->post('id');
        $finalData = array();
        $sssLoan = $this->sss_model->get_sss_loan($id);
        if(!empty($sssLoan)){
            $row_emp = $this->employee_model->employee_information($sssLoan['emp_id']);

            if ($row_emp['Middlename'] == ""){
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }
            else {
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            }

            $dateFrom =dateDefault($sssLoan['dateFrom']);
            $dateTo = dateDefault($sssLoan['dateTo']);
            $amountLoan = $sssLoan['amountLoan'];
            $deduction = $sssLoan['deduction'];
            $remainingBalance = $sssLoan['remainingBalance'];
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
            $this->data['msg'] = "There was a problem getting the sss information, please try again.";
        }
        echo json_encode($this->data);
    }
    public function updateSssInfo(){
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

                $updateData = $this->sss_model->update_sss_loan_data($id,$updateData);

                $this->data['status'] = "success";
                $this->data['msg'] = "The SSS Loan info of <strong>".$name."</strong>";
            }
        }
        echo json_encode($this->data);
    }
    public function getAdjustSssInfo(){
        $id = $this->input->post('id');
        $sssInfo = $this->sss_model->get_sss_loan($id);
        if(!empty($sssInfo)){
            $row_emp = $this->employee_model->employee_information($sssInfo['emp_id']);
            if ($row_emp['Middlename'] == ""){
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }
            else {
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            }
            $remainingBalance = $sssInfo['remainingBalance'];

            $this->data['remainingBalance'] = $remainingBalance;
            $this->data['name'] = $full_name;
            $this->data['status'] = "success";
        }   
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }

    public function adjustSssData(){
        $id = $this->input->post('id');
        $adjustDatePayment= dateDefaultDb($this->input->post('adjustDatePayment'));
        $adjustCashPayment= $this->input->post('adjustCashPayment');
        $adjustOutstandingBalance= $this->input->post('adjustOutstandingBalance');
        $adjustNewOutstandingBalance= $this->input->post('adjustNewOutstandingBalance');
        $adjustRemarks= $this->input->post('adjustRemarks');


        $this->form_validation->set_rules('adjustDatePayment', 'adjustDatePayment','required');
        $this->form_validation->set_rules('adjustCashPayment', 'adjustCashPayment','required');
        $this->form_validation->set_rules('adjustOutstandingBalance', 'adjustOutstandingBalance','required');
        $this->form_validation->set_rules('adjustNewOutstandingBalance', 'adjustNewOutstandingBalance','required');
        $this->form_validation->set_rules('adjustRemarks', 'adjustRemarks','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All field are required";
        }
        else{
            if($adjustCashPayment > $adjustOutstandingBalance){
                $this->data['status'] = "error";
                $this->data['msg'] = "The <strong>Cash Payment Php ".moneyConvertion($adjustCashPayment)."</strong> must be not greater than the <strong>Outstanding Balance Php ".moneyConvertion($adjustOutstandingBalance)."</strong>.";
            }
            else if($adjustCashPayment == 0){
                $this->data['status'] = "error";
                $this->data['msg'] = "Cash payment must not be a zero number.";
            }
            else{
                $updateRemainingBalanceData = array(
                    'remainingBalance'=>$adjustNewOutstandingBalance
                );
                $updateRemainingBalance = $this->sss_model->update_sss_loan_data($id, $updateRemainingBalanceData);
                $sssInfo = $this->sss_model->get_sss_loan($id);
                $emp_id = $sssInfo['emp_id'];
                $pagibig_loan_id = 0;
                //$sss_loan_id = 0;
                //$sssLoanId = 0;
                $simkimban_id =0;
                $loanType = "SSS Loan";
                $salary_loan_id = 0;
                $current_date_time = getDateDate();

                $insertAdjustmentLoanData = array(
                    'adjustment_loan_id'=>'',
                    'emp_id'=>$emp_id,
                    'datePayment'=>$adjustDatePayment,
                    'pagibig_loan_id'=>$pagibig_loan_id,
                    'sss_loan_id'=>$id,
                    'salary_loan_id'=>$salary_loan_id,
                    'simkimban_id'=>$simkimban_id,
                    'loanType'=>$loanType,
                    'cashPayment'=>$adjustCashPayment,
                    'remainingBalance'=>$adjustOutstandingBalance,
                    'remarks'=>$adjustRemarks,
                    'DateCreated'=>$current_date_time,
                );
                $insertAdjustmentLoan = $this->adjustment_loan_model->insert_adjustment_loan_data($insertAdjustmentLoanData);

                $row = $this->employee_model->employee_information($emp_id);
                $fullName = $row['Lastname'] . ", " . $row['Firstname'] . " " . $row['Middlename'];

                $this->data['status'] = "success";
                $this->data['msg'] = "<strong>SSS Loan</strong> of employee <strong>".$fullName."</strong> was successfully adjusted.";
            }
        }

        
        echo json_encode($this->data);
    }

    public function deleteSssLoan(){
        $id = $this->input->post('id');

        $sssInfo = $this->sss_model->get_sss_loan($id);
        if(!empty($sssInfo)){
            $emp_id = $sssInfo['emp_id'];
            $row_emp = $this->employee_model->employee_information($emp_id);
            $fullName = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];

            $deleteSss = $this->sss_model->delete_sss_loan($id);

            $this->data['status'] = "success";
            $this->data['msg'] = "<strong>SSS Loan</strong> of <strong>".$fullName."</strong> was successfully deleted.";
        }
        else{
            $this->data['status'] = "error";

        }
        echo json_encode($this->data);
    }
    public function getSssHistoryList(){
        $emp_id = $this->session->userdata('user');

        $select_qry = $this->sss_model->get_sss_history($emp_id);
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $row_emp = $this->employee_model->employee_information($emp_id);

                $emp_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];


                $date_create = date_create($value->dateFrom);
                $dateFrom = date_format($date_create, 'F d, Y');

                $date_create = date_create($value->dateTo);
                $dateTo = date_format($date_create, 'F d, Y');

                $date_range = $dateFrom . "- " .$dateTo;

                $status = "Current";
                if ($value->remainingBalance == 0){
                    $status = "Finish";
                }
                $finalData .= "<tr id='".$value->sss_loan_id."'>";
                    $finalData .= "<td>".$value->loan_type."</td>";
                    $finalData .= "<td>".$date_range."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->amountLoan)."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->deduction)."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->remainingBalance)."</td>";    
                    $finalData .= "<td>".$status."</td>";                        
                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function addNewSss(){
        $empId = $this->input->post('empId');
        $name= $this->input->post('name');
        $loanType = $this->input->post('loanType');
        $dateFrom= $this->input->post('dateFrom');
        $dateTo= $this->input->post('dateTo');
        $amountLoan= $this->input->post('amountLoan');
        $deduction= $this->input->post('deduction');
        $remainingBalance= $this->input->post('remainingBalance');
        $dateCreated = getDateDate();
        $this->form_validation->set_rules('name', 'name','required');
        $this->form_validation->set_rules('dateFrom', 'dateFrom','required');
        $this->form_validation->set_rules('loanType', 'loanType','required');
        $this->form_validation->set_rules('dateTo', 'dateTo','required');
        $this->form_validation->set_rules('amountLoan', 'amountLoan','required');
        $this->form_validation->set_rules('deduction', 'deduction','required');
        $this->form_validation->set_rules('remainingBalance', 'remainingBalance','required');

        $this->data['asd'] = $loanType;
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required";
        }
        else{
            $check = $this->employee_model->employee_information($empId);
            $checkSss = $this->sss_model->get_employee_sss_loan_type($empId,$loanType);
            if($loanType != "Salary Loan" && $loanType != "Calamity Loan"){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid loan type.";
            }
            else if(empty($check)){
                $this->data['status'] = "error";
                $this->data['msg'] = "There was a problem a problem on the employee name, please try again.";
            }
            else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateFrom ) || !preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateTo)) {
                $this->data['status'] = "error";
                $this->data['msg'] = "Date From or Date To not match to the current format mm/dd/yyyy.";
            }
            else if($dateFrom >= $dateTo){
                $this->data['status'] = 'error';
                $this->data['msg'] = "The <strong>Date From</strong> must be below the date of the declared <b>Date To</b>.";
            }
            else if(!empty($checkSss)){
                $this->data['status'] = 'error';
                $this->data['msg'] = "Employee <strong>".$name."</strong> has an existing <strong>".$loanType."</strong>.";
            }
            else{
                $dateFrom = dateDefaultDb($dateFrom);
                $dateTo = dateDefaultDb($dateTo);
                $insertData = array(
                    'loan_type'=>$loanType,
                    'emp_id'=>$empId,
                    'dateFrom'=>$dateFrom,
                    'dateTo'=>$dateTo,
                    'amountLoan'=>$amountLoan,
                    'deduction'=>$deduction,
                    'remainingBalance'=>$remainingBalance,
                    'DateCreated'=>$dateCreated,
                );
                $insert = $this->sss_model->insert_sss_loan($empId, $insertData);
                $this->data['status'] = "success";
                $this->data['msg'] = "<strong>Sss Loan</strong> was successfully added to employee <strong>".$name."</strong>";
            }
        }

        echo json_encode($this->data);
    }

    public function viewSalaryLoan(){
        $this->data['pageTitle'] = 'Salary Loan';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('loans/salary_loan');
        $this->load->view('global/footer');
    }

    public function getEmployeeWithExistingSalaryLoan(){
        $select_qry = $this->salary_model->get_all_salary_loan();
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
                $ref_no = $value->ref_no;
                $loan_type = "Salary Loan";
                if ($ref_no != ""){
                    
                    $row_fl = $this->employee_model->get_file_loan_data($ref_no);
                    
                    if ($row_fl['type'] == 3){
                        $loan_type = "Employee Benifit Program";
                    }
                }
                $info = "";
                $info .=  "<b>".$ref_no ."</b>" . "<br/>";
                $info .= $loan_type;

                if ($value->remainingBalance != 0) {

                    $day = "";
                    if ($value->deductionType == "Monthly"){
                        $day = "("  .$value->deductionDay.")";
                    }

                    $finalData .="<tr class='salary-loan-".$value->salary_loan_id."'>";
                        $finalData .= "<td class='name-".$value->salary_loan_id."'><small>".$emp_name."</small></td>";
                        $finalData .= "<td><small>".$date_range."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->amountLoan)."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->deduction)."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->remainingBalance)."</small></td>";
                        $finalData .= "<td><small>".$value->deductionType."" .$day."</small></td>";
                        $finalData .= "<td><small>".$info."</small></td>";
                        $finalData .= "<td><small>";
                            $finalData .= "<button id=".$value->salary_loan_id." class='edit-salary-loan-btn btn btn-sm btn-outline-success' data-toggle='modal' data-target='#editSalaryLoanModal'><i class='fas fa-pencil-alt' id=".$value->salary_loan_id."></i></button>&nbsp;";
                            $finalData .= "<button id=".$value->salary_loan_id." class='adjust-salary-loan-btn btn btn-sm btn-outline-success' data-toggle='modal' data-target='#adjustSssModal'><i id=".$value->salary_loan_id." class='fas fa-adjust'></i></button>&nbsp;";
                            $finalData .= "<button id=".$value->salary_loan_id." class='delete-salary-loan-btn btn btn-sm btn-outline-danger'><i id=".$value->salary_loan_id." class='fas fa-trash'></i></button>&nbsp;";
                            $finalData .= "<button id=".$value->salary_loan_id." class='view-salary-loan-history-btn btn btn-sm btn-outline-primary' data-toggle='modal' data-target='#existingSalaryLoanHistoryModal'><i class='fas fa-eye' id=".$value->salary_loan_id."></i></button>&nbsp;";
                            $finalData .= "<button class='btn btn-sm btn-outline-secondary'><i class='fas fa-print'></i></button>";
                        $finalData .= "</small></td>";
                    $finalData .= "</tr>";
                }
            }
        }
        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    public function getSalaryLoanInfo(){
        $id = $this->input->post('id');
        $salaryLoanInfo = $this->salary_model->get_salary_loan_data($id);
        $finalData = array();
        if(!empty($salaryLoanInfo)){
            $row_emp = $this->employee_model->employee_information($salaryLoanInfo['emp_id']);
            if ($row_emp['Middlename'] == ""){
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }
            else {
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            }
            $dateFrom = dateDefault($salaryLoanInfo['dateFrom']);
            $dateTo = dateDefault($salaryLoanInfo['dateTo']);
            $amountLoan = $salaryLoanInfo['amountLoan'];
            $deduction = $salaryLoanInfo['deduction'];
            $remainingBalance = $salaryLoanInfo['remainingBalance'];

            array_push($finalData, array(
                'name'=>$full_name,
                'deduction_type'=>$salaryLoanInfo['deductionType'],
                'deduction_day'=>$salaryLoanInfo['deductionDay'],
                'total_months'=>$salaryLoanInfo['totalMonths'],
                'date_from'=>$dateFrom,
                'date_to'=>$dateTo,
                'amount_loan'=>$amountLoan,
                'deduction'=>$deduction,
                'remaining_balance'=>$remainingBalance,
                'remarks'=>$salaryLoanInfo['remarks']
            ));
            $this->data['finalData'] = $finalData;
            $this->data['status'] = "success";
        }
        else{
            $this->data['status'] = "error";
            $this->data['msg'] = "There was a problem getting the simkimban information, please try again.";
        }

        echo json_encode($this->data);
    }

    public function updateSalaryLoanData(){
        $id = $this->input->post('id');
        $deductionType = $this->input->post('deductionType');
        
        $totalMonths = $this->input->post('totalMonths');
        $dateFrom = $this->input->post('dateFrom');
        $dateTo = $this->input->post('dateTo');
        $remarks = $this->input->post('remarks');
        $amountLoan = $this->input->post('amountLoan');
        $deduction = $this->input->post('deduction');
        $remainingBalance = $this->input->post('remainingBalance');
        $deductionDay = 0;

        $this->form_validation->set_rules('deductionType', 'deductionType', 'required');
        $this->form_validation->set_rules('totalMonths', 'totalMonths', 'required');
        $this->form_validation->set_rules('dateFrom', 'dateFrom', 'required');
        $this->form_validation->set_rules('dateTo', 'dateTo', 'required');
        $this->form_validation->set_rules('remarks', 'remarks', 'required');
        $this->form_validation->set_rules('amountLoan', 'amountLoan', 'required');
        $this->form_validation->set_rules('deduction', 'deductideductiononType', 'required');
        $this->form_validation->set_rules('remainingBalance', 'remainingBalance', 'required');
        $this->form_validation->set_rules('deductionDay', 'deductionDay', 'required');   

        if($this->form_validation->run() == FALSE){
            $this->data['status'] = 'error';
            $this->data['msg'] = "All field are required.";
        }
        else{
            if($deductionType == "Monthly"){
                $deductionDay = $this->input->post('deductionDay');
            }
            $sameSalaryLoan = $this->salary_model->if_salary_loan_has_no_changes($id, $deductionType, $deductionDay, $totalMonths, $dateFrom,$dateTo,$remarks,$amountLoan,$deduction, $remainingBalance);
            if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
                $this->data['status'] = 'error';
                $this->data['msg'] = "Invalid deduction day.";
            }
            else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateFrom ) || !preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateTo)) {
                $this->data['status'] = "error";
                $this->data['msg'] = "Date From or Date To not match to the current format mm/dd/yyyy.";
            }
            else if(!empty($sameSalaryLoan)){
                $this->data['status'] = 'error';
                $this->data['msg'] = "There's no changes in the data.";
            }
            
            else{
                $dateFrom = dateDefaultDb($dateFrom);
                $dateTo = dateDefaultDb($dateTo);
                if($dateFrom >= $dateTo){
                    $this->data['status'] = 'error';
                    $this->data['msg'] = "The <strong>Date From</strong> must be below the date of the declared <b>Date To</b>.";
                }
                else{
                    $updateData = array(
                        'deductionType'=>$deductionType,
                        'deductionDay'=>$deductionDay,
                        'totalMonths'=>$totalMonths,
                        'dateFrom'=>$dateFrom,
                        'dateTo'=>$dateTo,
                        'remarks'=>$remarks,
                        'amountLoan'=>$amountLoan,
                        'deduction'=>$deduction,
                        'remainingBalance'=>$remainingBalance,
                    );
                    $update = $this->salary_model->update_salary_loan_data($id, $updateData);
                    $this->data['status'] = "success";
                }
                
                
            }
        }
        

        //$this->data['asd'] = $deductionDay;
        echo json_encode($this->data);
    }

    public function getAdjustSalaryLoanInfo(){
        $id = $this->input->post('id');
        $salaryLoanInfo = $this->salary_model->get_salary_loan_data($id);
        if(!empty($salaryLoanInfo)){
            $row_emp = $this->employee_model->employee_information($salaryLoanInfo['emp_id']);
            if ($row_emp['Middlename'] == ""){
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }
            else {
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            }

            $remainingBalance = $salaryLoanInfo['remainingBalance'];
            $this->data['remainingBalance'] = $remainingBalance;
            $this->data['name'] = $full_name;
            $this->data['status'] = "success";
        }   
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }

    public function adjustSalaryLoanData(){
        $id = $this->input->post('id');
        $adjustDatePayment= dateDefaultDb($this->input->post('adjustDatePayment'));
        $adjustCashPayment= $this->input->post('adjustCashPayment');
        $adjustOutstandingBalance= $this->input->post('adjustOutstandingBalance');
        $adjustNewOutstandingBalance= $this->input->post('adjustNewOutstandingBalance');
        $adjustRemarks= $this->input->post('adjustRemarks');


        $this->form_validation->set_rules('adjustDatePayment', 'adjustDatePayment','required');
        $this->form_validation->set_rules('adjustCashPayment', 'adjustCashPayment','required');
        $this->form_validation->set_rules('adjustOutstandingBalance', 'adjustOutstandingBalance','required');
        $this->form_validation->set_rules('adjustNewOutstandingBalance', 'adjustNewOutstandingBalance','required');
        $this->form_validation->set_rules('adjustRemarks', 'adjustRemarks','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All field are required";
        }
        else{
            if($adjustCashPayment > $adjustOutstandingBalance){
                $this->data['status'] = "error";
                $this->data['msg'] = "The <strong>Cash Payment Php ".moneyConvertion($adjustCashPayment)."</strong> must be not greater than the <strong>Outstanding Balance Php ".moneyConvertion($adjustOutstandingBalance)."</strong>.";
            }
            else if($adjustCashPayment == 0){
                $this->data['status'] = "error";
                $this->data['msg'] = "Cash payment must not be a zero number.";
            }
            else{
                $updateRemainingBalanceData = array(
                    'remainingBalance'=>$adjustNewOutstandingBalance
                );
                $updateRemainingBalance = $this->salary_model->update_salary_loan_data($id, $updateRemainingBalanceData);
                $pagibigInfo = $this->salary_model->get_salary_loan_data($id);
                $emp_id = $pagibigInfo['emp_id'];
                $pagibig_loan_id = 0;
                $sss_loan_id = 0;
                //$sssLoanId = 0;
                $simkimban_id =0;
                $loanType = "Salary Loan";
                //$salary_loan_id = 0;
                $current_date_time = getDateDate();

                $insertAdjustmentLoanData = array(
                    'adjustment_loan_id'=>'',
                    'emp_id'=>$emp_id,
                    'datePayment'=>$adjustDatePayment,
                    'pagibig_loan_id'=>$pagibig_loan_id,
                    'sss_loan_id'=>$sss_loan_id,
                    'salary_loan_id'=>$id,
                    'simkimban_id'=>$simkimban_id,
                    'loanType'=>$loanType,
                    'cashPayment'=>$adjustCashPayment,
                    'remainingBalance'=>$adjustOutstandingBalance,
                    'remarks'=>$adjustRemarks,
                    'DateCreated'=>$current_date_time,
                );
                $insertAdjustmentLoan = $this->adjustment_loan_model->insert_adjustment_loan_data($insertAdjustmentLoanData);

                $row = $this->employee_model->employee_information($emp_id);
                $fullName = $row['Lastname'] . ", " . $row['Firstname'] . " " . $row['Middlename'];

                $this->data['status'] = "success";
                $this->data['msg'] = "<strong>Salary Loan</strong> of employee <strong>".$fullName."</strong> was successfully adjusted.";
            }
        }

        
        echo json_encode($this->data);
    }

    public function deleteSalaryLoan(){
        $id = $this->input->post('id');

        $pagibigInfo = $this->salary_model->get_salary_loan_data($id);
        if(!empty($pagibigInfo)){
            $emp_id = $pagibigInfo['emp_id'];
            $row_emp = $this->employee_model->employee_information($emp_id);

            $fullName = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];

            $deletePagibig = $this->salary_model->delete_salary_loan($id);

            $this->data['status'] = "success";
            $this->data['msg'] = "<strong>Salary Loan</strong> of <strong>".$fullName."</strong> was successfully deleted.";
        }
        else{
            $this->data['status'] = "error";

        }
        echo json_encode($this->data);
    }

    public function getSalaryLoanHistory(){
        $id = $this->input->post('id');

        $select_qry = $this->salary_model->get_salary_loan_history_data($id);
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $finalData .="<tr>";
                    $finalData .= "<td>".$value->date_payroll."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->deduction)."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->remainingBalance)."</td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function getEmployeeSalaryLoanHistory(){
        $select_qry = $this->salary_model->get_all_employee_salary_loan_history_with_zero_balance();
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

                $day = "";
                if ($value->deductionType == "Monthly"){
                    $day = "("  .$value->deductionDay.")";
                }
                $finalData .= "<tr>";
                    $finalData .= "<td><small>".$emp_name."</small></td>";
                    $finalData .= "<td><small>".$date_range."</small></td>";
                    $finalData .= "<td><small>Php ".moneyConvertion($value->amountLoan)."</small></td>";
                    $finalData .= "<td><small>Php ".moneyConvertion($value->deduction)."</small></td>";
                    $finalData .= "<td><small>".$value->deductionType."" .$day."</small></td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function getSalaryLoanHistoryCurrent(){

        $id = $this->session->userdata('user');

        $select_qry = $this->salary_model->get_employee_salary_loan_history_data_order_by_date($id);
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

                $status = "Finish";
                if ($value->remainingBalance != 0) {
                    $status = "Current";
                }

                $finalData .= "<tr>";
                    $finalData .= "<td>".$date_range."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->amountLoan)."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->deduction)."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->remainingBalance)."</td>";
                    $finalData .= "<td>".$value->remarks."</td>";
                    $finalData .= "<td>".$status."</td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }



    public function addNewSalaryLoan(){
        $empId = $this->input->post('empId');
        $name = $this->input->post('name');
        $deductionType= $this->input->post('deductionType');
        
        $totalMonths= $this->input->post('totalMonths');
        $dateFrom= $this->input->post('dateFrom');
        $dateTo= $this->input->post('dateTo');
        $remarks= $this->input->post('remarks');
        $amountLoan= $this->input->post('amountLoan');
        $deduction= $this->input->post('deduction');
        $remainingBalance= $this->input->post('remainingBalance');
        $totalPayment = $this->input->post('totalPayment');
        $dateCreated = getDateDate();
        $deductionDay = 0;
        if ($deductionType == "Monthly"){
            $deductionDay= $this->input->post('deductionDay');
        }
        $this->form_validation->set_rules('name', 'name','required');
        $this->form_validation->set_rules('deductionType', 'deductionType','required');
        $this->form_validation->set_rules('deductionDay', 'deductionDay','required');
        $this->form_validation->set_rules('totalMonths', 'totalMonths','required');
        $this->form_validation->set_rules('dateFrom', 'dateFrom','required');
        $this->form_validation->set_rules('dateTo', 'dateTo','required');
        $this->form_validation->set_rules('remarks', 'remarks','required');
        $this->form_validation->set_rules('amountLoan', 'amountLoan','required');
        $this->form_validation->set_rules('deduction', 'deduction','required');
        $this->form_validation->set_rules('remainingBalance', 'remainingBalance','required');
        $this->form_validation->set_rules('totalPayment', 'totalPayment','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required";
        }
        else{
            $check = $this->employee_model->employee_information($empId);
            if(empty($check)){
                $this->data['status'] = "error";
                $this->data['msg'] = "There was a problem a problem on the employee name, please try again.";
            }
            else{
                if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
                    $this->data['status'] = 'error';
                    $this->data['msg'] = "Invalid deduction day.";
                }
                else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateFrom ) || !preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateTo)) {
                    $this->data['status'] = "error";
                    $this->data['msg'] = "Date From or Date To not match to the current format mm/dd/yyyy.";
                }
                else if($dateFrom > $dateTo){
                    $this->data['status'] = 'error';
                    $this->data['msg'] = "The <strong>Date From</strong> must be below the date of the declared <b>Date To</b>.";
                }
                else{

                    $insertData = array(
                        'emp_id'=>$empId,
                        'deductionType'=>$deductionType,
                        'deductionDay'=>$deductionDay,
                        'totalMonths'=>$totalMonths,
                        'dateFrom'=>dateDefaultDb($dateFrom),
                        'dateTo'=>dateDefaultDb($dateTo),
                        'amountLoan'=>$amountLoan,
                        'deduction'=>$deduction,
                        'totalPayment'=>$totalPayment,
                        'remainingBalance'=>$remainingBalance,
                        'remarks'=>$remarks,
                        'DateCreated'=>$dateCreated
                    );

                    $insert = $this->salary_model->insert_salary_loan_data($insertData);

                    $this->data['status'] = "success";
                    $this->data['msg'] = "Salary loan was successfully filed.";
                }
            }
        }

        echo json_encode($this->data);
    }

    public function viewFileLoan(){
        $this->data['pageTitle'] = 'File Loan';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('loans/file_loan');
        $this->load->view('global/footer');
    }


    // for file loans
    public function getFileLoanList(){
        $emp_id = $this->session->userdata('user');

        $select_qry = $this->employee_model->get_employee_file_loan_data_order_by_date($emp_id);
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $loan_type = "Salary Loan";
                if ($value->type == 2){
                    $loan_type = "SIMKIMBAN";
                }

                else if ($value->type == 3){
                    $loan_type = "Employee Benifit Program Loan";

                    $program = "Service Rewards";
                    if ($value->program == 2){
                        $program = "Tulong Pangkabuhayan Program";
                    }

                    else if ($value->program == 3){
                        $program = "Education Assistance Program";
                    }

                    else if ($value->program == 4){
                        $program = "Housing Renovation Program";
                    }

                    else if ($value->program == 5){
                        $program = "Emergency and Medical Assistance Program";
                    }

                    $loan_type .= "<br/>" ."<span style='color:#b4b5b6'>" .$program . "</span>";

                }

                $status = "Pending";
                if ($value->status == 1){
                    $status = "Approve";
                }

                else if ($value->status == 2){
                    $status = "Disapprove";
                }

                else if ($value->status == 3){
                    $status = "Cancel";
                }

                else if ($value->status == 4){
                    $status = "On Process";
                }
                $finalData .= "<tr class='file-loan-".$value->file_loan_id."'>";
                    $finalData .= "<td class='ref-no-".$value->file_loan_id."'>".$value->ref_no."</td>";
                    $finalData .= "<td>".number_format($value->amount,2)."</td>";
                    $finalData .= "<td>".$value->purpose."</td>";
                    $finalData .= "<td>".$loan_type."</td>";
                    $finalData .= "<td>".$status."</td>";
                    $finalData .= "<td>";    
                        if ($value->status == 0){
                            $finalData .= "<button id=".$value->file_loan_id." class='update-file-loan-btn btn btn-sm btn-outline-success' data-target='#updateFileLoanModal' data-toggle='modal'>Update</button>";
                            $finalData .= "&nbsp;";
                            $finalData .= "<button id=".$value->file_loan_id." class='cancel-file-loan-btn btn btn-sm btn-outline-danger'>Cancel</button>";
                        }

                        else {
                            $finalData .= "No Action";
                        }
                    $finalData .= "</td>";
                $finalData .= "</tr>";
            }
        }


        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;

        echo json_encode($this->data);
    }

    public function getFileLoanInfo(){
        $id = $this->input->post('id');

        $fileLoan = $this->employee_model->get_employee_file_loan_data($id);
        $finalData = array();
        if(!empty($fileLoan)){
            array_push($finalData, array(
                'amount'=>$fileLoan['amount'],
                'type'=>$fileLoan['type'],
                'program'=>$fileLoan['program'],
                'purpose'=>$fileLoan['purpose'],

            ));
            $this->data['finalData'] = $finalData;
            $this->data['status'] = "success";
        }
        else{
            $this->data['status'] = "error";
        }
        echo json_encode($this->data);
    }

    public function updateFileLoanInfo(){
        $id = $this->input->post('id');
        $amount= $this->input->post('amount');
        $type= $this->input->post('type');
        $program= $this->input->post('program');
        $purpose= $this->input->post('purpose');

        $fileLoan = $this->employee_model->get_employee_file_loan_data($id);
        if(!empty($fileLoan)){
            $ref_no = $fileLoan['ref_no'];
            $this->form_validation->set_rules('amount','amount','required');
            $this->form_validation->set_rules('type','type','required');
            
            $this->form_validation->set_rules('purpose','purpose','required');

            if($type == 3){
                $this->form_validation->set_rules('program','program','required');
                
            }

            if($this->form_validation->run() == FALSE){
                $this->data['status'] = "error";
                $this->data['msg']= "All fields are required.";
            }
            else{
                $updateData = array(
                    'amount'=>$amount,
                    'type'=>$type,
                    'program'=>$program,
                    'purpose'=>$purpose,
                );

                $update = $this->employee_model->update_employee_file_loan_data($id,$updateData);
                $this->data['status'] = "success";
                $this->data['msg'] = "You successfully updated your filed loan for reference number <strong>".$ref_no."</strong>";
            }
        }
        else{
            $this->data['status'] = "error";
            $this->data['msg']= "There was a problem updating the information, please try again.";
        }
        echo json_encode($this->data);
    }
    public function cancelFileLoan(){
        $id = $this->input->post('id');
        $fileLoan = $this->employee_model->get_employee_file_loan_data($id);
        if(!empty($fileLoan)){
            $updateData = array(
                'status'=>3
            );
            $update = $this->employee_model->update_employee_file_loan_data($id,$updateData);
            $this->data['status'] = "success";
            $this->data['msg'] = "You successfully cancelled  your file loan for reference number <strong>".$fileLoan['ref_no']."</strong>";
        }
        else{
            $this->data['status'] = "error";
            $this->data['msg']= "There was a problem on the cancellation of your file loan, please try again.";
        }

        echo json_encode($this->data);
    }

    public function addNewFileLoan(){
        $amount= $this->input->post('amount');
        $type= $this->input->post('type');
        $program= $this->input->post('program');
        $purpose= $this->input->post('purpose');
        $emp_id = $this->session->userdata('user');
        $this->form_validation->set_rules('amount','amount','required');
        $this->form_validation->set_rules('type','type','required');
        
        $this->form_validation->set_rules('purpose','purpose','required');

        if($type == 3){
            $this->form_validation->set_rules('program','program','required');
            
        }

        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg']= "All fields are required.";
        }
        else{
            $fileLoan = $this->employee_model->get_all_employee_file_loan_data();
            $ref_no = "";
            if(empty($fileLoan)){
                
                $ref_no = "RF0" . date("Ymd") . "-1";
            }
            else{
                $tmpFileLoan = $this->employee_model->get_all_employee_file_loan_data_order_by();
                $ref_no = "RF0" . date("Ymd") . "-" . ($tmpFileLoan['file_loan_id'] + 1);
            }  
            $tmpProgram = "";

            if($type == 3){
                $tmpProgram = $program;
            }
            $insertData = array(
                'amount'=>$amount,
                'type'=>$type,
                'program'=>$program,
                'purpose'=>$purpose,
                'emp_id'=>$emp_id,
                'ref_no'=>$ref_no,
            );

            $loanTypeText ="";
            if($type == 1){
                $loanTypeText = "Salary Loan";
            }
            else if($type == 2){
                $loanTypeText = "SIMKIMBAN";
            }
            else{
                $loanTypeText = "Employee Benefit Program Loan";
            }
            $insert = $this->employee_model->insert_file_loan($insertData);
            $this->data['status'] = "success";
            $this->data['msg'] = "You successfully file a loan for <strong>".$loanTypeText."</strong>.";
        }
        echo json_encode($this->data);
    }

    public function getFileLoanListHistory(){
        //$emp_id = $this->session->userdata('user');

        $select_qry = $this->employee_model->get_employee_file_loan_data_status_zero();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $loan_type = "Salary Loan";
                if ($value->type == 2){
                    $loan_type = "SIMKIMBAN";
                }

                else if ($value->type == 3){
                    $loan_type = "Employee Benifit Program Loan";

                    $program = "Service Rewards";
                    if ($value->program == 2){
                        $program = "Tulong Pangkabuhayan Program";
                    }

                    else if ($value->program == 3){
                        $program = "Education Assistance Program";
                    }

                    else if ($value->program == 4){
                        $program = "Housing Renovation Program";
                    }

                    else if ($value->program == 5){
                        $program = "Emergency and Medical Assistance Program";
                    }

                    $loan_type .= "<br/>" ."<span style='color:#b4b5b6'>" .$program . "</span>";

                }

                $status = "Pending";
                if ($value->status == 1){
                    $status = "Approve";
                }

                else if ($value->status == 2){
                    $status = "Disapprove";
                }

                else if ($value->status == 3){
                    $status = "Cancel";
                }

                else if ($value->status == 4){
                    $status = "On Process";
                }
                    $row_emp = $this->employee_model->employee_information($value->emp_id);
                    $finalData .= "<tr class='file-loan-list-history-".$value->file_loan_id."'>";
                    
                    $finalData .= "<td class='name-file-loan-".$value->file_loan_id."'>".$row_emp['Firstname']. " " . $row_emp['Lastname'] ."</td>";
                    $finalData .= "<td class='ref-no-file-salary-".$value->file_loan_id."'>".$value->ref_no."</td>";
                    $finalData .= "<td>".number_format($value->amount,2)."</td>";
                    $finalData .= "<td>".$value->purpose."</td>";
                    $finalData .= "<td>".$loan_type."</td>";
                    $finalData .= "<td>";    
                        if ($value->status == 0){
                            $finalData .= "<button id=".$value->file_loan_id." class='create-schedule-file-loan-btn btn btn-sm btn-outline-success' data-target='#scheduleFileLoanModal' data-toggle='modal'>Create Schedule</button>";
                            $finalData .= "&nbsp;";
                            $finalData .= "<button id=".$value->file_loan_id." class='disapprove-file-loan-btn btn btn-sm btn-outline-danger'>Disapprove</button>";
                        }

                        else {
                            $finalData .= "No Action";
                        }
                    $finalData .= "</td>";
                $finalData .= "</tr>";
            }
        }


        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;

        echo json_encode($this->data);
    }
    public function getScheduleFileLoanInfo(){
        $id = $this->input->post('id');
        $fileLoan = $this->employee_model->get_employee_file_loan_data($id);
        $finalData = array();
        if(!empty($fileLoan)){
            $emp_id = $fileLoan['emp_id'];

            $program = $fileLoan['program'];
            $row_emp = $this->employee_model->employee_information($fileLoan['emp_id']);

            $date_hired = $row_emp['DateHired'];

            $now = date("Y-m-d");

            $date1 = $date_hired;
            $date1= date_create($date1);

            $date2= date_create($now);

            $diff =date_diff($date1,$date2);
            $wew =  $diff->format("%R%a");
            $days = str_replace("+","",$wew);


            $years = $days / 365;
            $years = floor($years);
            $loan_type = $fileLoan['type'];

            $loanTypeText = "SALARY LOAN";
            if($loan_type == 1 || $loan_type == 3){
                $loanTypeText = "EMPLOYEE BENIFIT PROGRAM";
            }
            $min_amount = 0;
            $max_amount = 0;
            if ($years == 1){
                $min_amount = 1000;
                $max_amount = 5000;
            }

            else if ($years == 2){
                $min_amount = 5000;
                $max_amount = 10000;
            }

            else if ($years == 3){
                $min_amount = 10000;
                $max_amount = 15000;
            }

            else if ($years == 4){
                $min_amount = 15000;
                $max_amount = 20000;
            }

            else if ($years == 5){
                $min_amount = 20000;
                $max_amount = 30000;
            }

            else if ($years >= 6){
                $min_amount = 20000;
                $max_amount = 35000;
            }
            $year = date('Y');
            $nextYear = $year + 1;
            $fullName = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            array_push($finalData, array(
                'loan_type_text'=>$loanTypeText,
                'year'=>$year,
                'next_year'=>$nextYear,
                'loan_type'=>$loan_type,
                'min_amount'=>$min_amount,
                'max_amount'=>$max_amount,
                'purpose' => $fileLoan['purpose'],
                'name'=>$fullName,
            ));
            $this->data['status'] = "success";
            $this->data['finalData'] = $finalData;
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }

    public function scheduleFileLoan(){
        $id = $this->input->post('id');
        $deductionType= $this->input->post('deductionType');
        $totalMonths= $this->input->post('totalMonths');
        $dateFromMonth= $this->input->post('dateFromMonth');
        $datefromDay= $this->input->post('datefromDay');
        $dateFromYear= $this->input->post('dateFromYear');
        $dateTo= $this->input->post('dateTo');
        $amountLoan= $this->input->post('amountLoan');
        $deduction= $this->input->post('deduction');
        $totalPayment= $this->input->post('totalPayment');
        $remarks= $this->input->post('remarks');
        $deductionDay = 0;
        $pre_approver_id = $this->session->userdata('user');
        $pre_approve_date = date("Y-m-d");
        $dateFrom = $dateFromMonth. "/" .$datefromDay. "/".$dateFromYear;
        //$dateFrom = $dateFromYear."/".$datefromDay."/".$dateFromMonth;
        $fileLoan = $this->employee_model->get_employee_file_loan_data($id);
        $emp_id = $fileLoan['emp_id'];

        $ref_no = $fileLoan['ref_no'];
        
        $row_emp = $this->employee_model->employee_information($emp_id);
        
        $this->form_validation->set_rules('deductionType', 'deductionType', 'required');
        $this->form_validation->set_rules('totalMonths', 'totalMonths', 'required');
        $this->form_validation->set_rules('dateFromMonth', 'dateFromMonth', 'required');
        $this->form_validation->set_rules('datefromDay', 'datefromDay', 'required');
        $this->form_validation->set_rules('dateFromYear', 'dateFromYear', 'required');
        $this->form_validation->set_rules('dateTo', 'dateTo', 'required');
        $this->form_validation->set_rules('amountLoan', 'amountLoan', 'required');
        $this->form_validation->set_rules('deduction', 'deduction', 'required');
        $this->form_validation->set_rules('totalPayment', 'totalPayment', 'required');
        $this->form_validation->set_rules('remarks', 'remarks', 'required');
        if($deductionType == "Monthly"){
            $deductionDay = $this->input->post('deductionDay');
            $this->form_validation->set_rules('deductionDay', 'deductionDay', 'required');
        }
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All field are required.";
        }
        else{

        
            
            if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
                $this->data['status'] = 'error';
                $this->data['msg'] = "Invalid deduction day.";
            }

            else if(dateDefaultDb($dateFrom) < getDateDate()){
                $new_dateFrom = dateFormat($dateFrom);
                $dateCreated = getDateDate();
                $dateCreated = dateFormat($dateCreated);
                $this->data['status'] = "error";
                $this->data['msg'] = "<strong>Date From ".$new_dateFrom."</strong> must be not below the current date <strong>".$dateCreated."</strong>";

            }
            else{

                $dateCreated = getDateDate();

                $final_dateFrom = dateDefaultDb($dateFrom);
                $final_dateTo = dateDefaultDb($dateTo);


                $new_dateFrom = dateDefaultDb($dateFrom);
                $new_dateTo = dateFormat($dateTo);

                $new_amountLoan = moneyConvertion($amountLoan);

                $insertData = array(
                    'emp_id'=>$emp_id,
                    'pre_approver_id'=>$pre_approver_id,
                    'pre_approval_date'=>$pre_approve_date,
                    'deductionType'=>$deductionType,
                    'deductionDay'=>$deductionDay,
                    'totalMonths'=>$totalMonths,
                    'dateFrom'=>$final_dateFrom,
                    'dateTo'=>$final_dateTo,
                    'amountLoan'=>$amountLoan,
                    'totalPayment'=>$totalPayment,
                    'deduction'=>$deduction,
                    'remarks'=>$remarks,
                    'apporveStat'=>0,
                    'dateCreated'=>$dateCreated,
                );
                $insert = $this->salary_model->insert_file_salary_loan_data($insertData);

                $idFileLoan = $this->salary_model->get_file_salary_loan_data_order_by_date();


                $updateFileLoanData = array(
                    'ref_no'=>$ref_no,
                );
                $this->salary_model->update_file_salary_loan($idFileLoan['file_salary_loan_id'], $updateFileLoanData);
                $last_file_salary_loan_id = $idFileLoan['file_salary_loan_id'];

                $updateEmpFileLoanData = array(
                    'status'=>4
                );
                $this->employee_model->update_employee_file_loan_data($id,$updateEmpFileLoanData);

                $notif_type = "File Salary Loan";
                $readStatus = '0';

                $this->data['status'] = "success";
                $this->data['msg'] = "You successfully filed a salary loan for <strong>".$row_emp['Firstname']." ".$row_emp['Lastname']. "</strong> of <strong>".$totalMonths." months</strong> starting from <strong>".$new_dateFrom." - ".$new_dateTo."</strong> amounting of <strong>".$new_amountLoan."</strong>";
            }
        }

        echo json_encode($this->data);
    }

    public function disapproveFileLoan(){
        $id = $this->input->post('id');
        $fileLoan = $this->employee_model->get_employee_file_loan_data($id);
        if(!empty($fileLoan)){
            $row_emp = $this->employee_model->employee_information($fileLoan['emp_id']);
            $ref_no = $fileLoan['ref_no'];
            $updateFileLoanData = array(
                'status'=>2,
            );
            $this->employee_model->update_employee_file_loan_data($id, $updateFileLoanData);

            $this->data['status'] = "success";
            $this->data['msg'] = "You successfully disapprove the file loan of <strong>".$row_emp['Firstname'] . " " . $row_emp['Lastname']."</strong> for reference number <strong>".$ref_no."</strong>.";
        }
        else{
            $this->data['error'];
        }

        echo json_encode($this->data);
    }

    public function fileLoanSalaryAndEmployment(){
        $select_qry = $this->salary_model->get_filed_salary_loan_to_approve();
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


                $interest_amount = $value->totalPayment - $value->amountLoan;


                $ref_no = $value->ref_no;
                $row_fl = $this->employee_model->get_file_loan_data($ref_no);
                $loan_type = "Salary Loan";
                if ($row_fl['type'] == 3){
                    $loan_type = "Employee Benifit Program";
                }

                $info = "";
                $info .=  "<b>".$ref_no ."</b>" . "<br/>";
                $info .= $loan_type;
                $finalData .="<tr id='salary-and-employment-".$value->file_salary_loan_id."'>";
                    $finalData .= "<td class='approval-name-loan-".$value->file_salary_loan_id."'><small>".$emp_name."</small></td>";
                    $finalData .= "<td><small>".$date_range."</small></td>";
                    $finalData .= "<td><small>Php ".moneyConvertion($value->amountLoan)."</small></td>";
                    $finalData .= "<td><small>Php ".moneyConvertion($value->deduction)."</small></td>";
                    $finalData .= "<td>".$info."</td>";
                    $finalData .= "<td><small>".$value->remarks."</small></td>";
                    $finalData .= "<td><small>";
                        $finalData .= "<button id=".$value->file_salary_loan_id." class='approve-file-loan-salary-employment-btn btn btn-sm btn-outline-success'>Approve</button>";
                        $finalData .= "<button id=".$value->file_salary_loan_id." class='disapprove-file-loan-salary-employment-btn btn btn-sm btn-outline-danger'>Disapprove</button>";
                        $finalData .= "</small>";


                    $finalData .= "</td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }
    public function disapproveFileSalaryAndEmploymentLoan(){
        $id = $this->input->post('id');
        $fileSalaryLoan = $this->salary_model->get_filed_salary_loan($id);
        if(!empty($fileSalaryLoan)){
            $emp_id = $fileSalaryLoan['emp_id'];
            $ref_no = $fileSalaryLoan['ref_no'];
            $row_emp = $this->employee_model->employee_information($emp_id);

            $fullName = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            if ($row_emp['Middlename'] == ""){
                $fullName = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }
            $approveStat = 2;

            $approver_id = $this->session->userdata('user');

            $updateEmpFileLoanData = array(
                'status'=>2
            );
            $this->employee_model->update_employee_file_loan_data_using_ref_no($ref_no, $updateEmpFileLoanData);

            $updateFileSalaryLoanData = array(
                'approver_id'=>$approver_id,
                'apporveStat'=>$approveStat,
                'dateApprove'=>getDateDate(),
            );
            $this->salary_model->update_file_salary_loan($id,$updateFileSalaryLoanData);

            $module = "Disapprove File Salary Loan";
            $task_description = "Disapprove File Salary Loan, " . $fileSalaryLoan['deductionType'];
            $dateTime = getDateTime();
            $insertAuditTrialData = array(
                'audit_trail_id'=>'',
                'file_emp_id'=>$emp_id,
                'approve_emp_id'=>$approver_id,
                'involve_emp_id'=>0,
                'module'=>$module,
                'task_description'=>$task_description,
            );
            $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);

            $notif_type = "Disapprove Your File Salary Loan";
            $readStatus = '0';
            $insertNotificationsData = array(
                'payroll_notif_id'=>'',
                'payroll_admin_id'=>0,
                'emp_id'=>$emp_id,
                'payroll_id'=>$approver_id,
                'approve_payroll_id'=>0,
                'file_salary_loan_id'=>$id,
                'notifType'=>$notif_type,
                'cutOffPeriod'=>'',
                'readStatus'=>$readStatus,

            );
            $insertNotifications = $this->payroll_model->insert_payroll_notifications($insertNotificationsData);

            $this->data['status'] = "success";
            $this->data['msg'] = "You successfully disapprove the file salary loan of <strong>".$fullName."</strong>.";
        }
        else{
            $this->data['status'] = "error";

        }

        echo json_encode($this->data);
    }

    public function fileLoanSimkimban(){
        $select_qry = $this->simkimban_model->get_employee_simkimban_loan_status_zero();
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


                //$interest_amount = $value->totalPayment - $value->amountLoan;


                $ref_no = $value->ref_no;
                if ($value->remainingBalance != 0) {
                    $finalData .= "<tr class='file-loan-simkimban-".$value->simkimban_id."'>";
                        $finalData .= "<td class='approval-name-loan-simkimban-".$value->simkimban_id."'><small>".$emp_name."</small></td>";
                        $finalData .= "<td><small>".$date_range."</small></td>";
                        $finalData .= "<td><small>".$value->Items."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->amountLoan)."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->deduction)."</small></td>";
                        $finalData .= "<td>".$ref_no."</td>";
                        //echo "<td><small>".$row->remarks."</td>";
                        $finalData .= "<td><small>";
                            $finalData .= "<button id=".$value->simkimban_id." class='approve-file-loan-simkimban-btn btn btn-sm btn-outline-success'>Approve</button>";
                            $finalData .= "<button id=".$value->simkimban_id." class='disapprove-file-loan-simkimban-btn btn btn-sm btn-outline-danger'>Disapprove</button>";
                            $finalData .= "</small>";
                        $finalData .= "</small></td>";
                    $finalData .= "</tr>";
                }
                
            }
        }
        $this->data['finalData'] =$finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function disapproveFileSimkimban(){
        $id = $this->input->post('id');

        $row = $this->simkimban_model->get_simkimban_data($id);
        $emp_id = $row['emp_id'];
        $ref_no = $row['ref_no'];
        $deductionType  = $row['deductionType'];
        $totalMonths = $row['totalMonths'];
        $new_amountLoan = moneyConvertion($row['amountLoan']);
        $new_dateFrom = dateFormat($row['dateFrom']);
        $new_dateTo = dateFormat($row['dateTo']);
        $row_emp = $this->employee_model->employee_information($emp_id);
        $empName = $row_emp['Firstname'] . " " . $row_emp['Lastname'];

        $updateSimkimbanData = array(
            'status'=>2
        );
        $this->simkimban_model->update_simkimban_loan_data($id, $updateSimkimbanData);

        $updateEmpFileLoanData = array(
            'status'=>2
        );
        $this->employee_model->update_employee_file_loan_data_using_ref_no($ref_no, $updateEmpFileLoanData);

        $module = "Disapprove File SIMKIMBAN Loan";
        $task_description = "Disapprove File SIMKIMBAN Loan, " . $deductionType;
        $approver_id =$this->session->userdata('user');
        $dateTime = getDateTime();
        $insertAuditTrialData = array(
            'audit_trail_id'=>'',
            'file_emp_id'=>$emp_id,
            'approve_emp_id'=>$approver_id,
            'involve_emp_id'=>0,
            'module'=>$module,
            'task_description'=>$task_description,
        );
        $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
        $this->data['status'] = "success";
        $this->data['msg'] = "You successfully disapprove the file simkimban loan of <strong>".$empName."</strong>.";

        echo json_encode($this->data);
    }

    public function scheduleFileLoanSimkimban(){
        $id = $this->input->post('id');
        $deductionType= $this->input->post('deductionType');
        $totalMonths= $this->input->post('totalMonths');
        $dateFromMonth= $this->input->post('dateFromMonth');
        $datefromDay= $this->input->post('datefromDay');
        $dateFromYear= $this->input->post('dateFromYear');
        $dateTo= $this->input->post('dateTo');
        $amountLoan= $this->input->post('amountLoan');
        $deduction= $this->input->post('deduction');
        $totalPayment= $this->input->post('totalPayment');
        $item= $this->input->post('item');
        $name = $this->input->post('name');

        $row_fl = $this->employee_model->get_employee_file_loan_data($id);
        $dateFrom = $dateFromMonth. "/" .$datefromDay. "/".$dateFromYear;
        $empId = $row_fl['emp_id'];
        $deductionDay = 0;

        $dateCreated = getDateDate();

        if($deductionType == "Monthly"){
            $deductionDay = $this->input->post('deductionDay');
            $this->form_validation->set_rules('deductionDay', 'deductionDay', 'required');
        }
        $this->form_validation->set_rules('deductionType', 'deductionType', 'required');
        $this->form_validation->set_rules('totalMonths', 'totalMonths', 'required');
        $this->form_validation->set_rules('dateFromMonth', 'dateFromMonth', 'required');
        $this->form_validation->set_rules('datefromDay', 'datefromDay', 'required');
        $this->form_validation->set_rules('dateFromYear', 'dateFromYear', 'required');
        $this->form_validation->set_rules('dateTo', 'dateTo', 'required');
        $this->form_validation->set_rules('amountLoan', 'amountLoan', 'required');
        $this->form_validation->set_rules('deduction', 'deduction', 'required');
        $this->form_validation->set_rules('totalPayment', 'totalPayment', 'required');
        $this->form_validation->set_rules('item', 'item', 'required');
        if($deductionType == "Monthly"){
            $deductionDay = $this->input->post('deductionDay');
            $this->form_validation->set_rules('deductionDay', 'deductionDay', 'required');
        }
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All field are required.";
        }
        else{

            $dateTo = dateDefaultDb($dateTo);
            if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
                $this->data['status'] = 'error';
                $this->data['msg'] = "Invalid deduction day.";
            }
            else if($dateFrom > $dateTo){
                $this->data['status'] = "error";
                $this->data['msg'] = "The <strong>Date From</strong> must be below the date of the declared <strong>Date To</strong>";
            }
            else{
                $insertData = array(
                    'simkimban_id'=>'',
                    'emp_id'=>$empId,
                    'deductionType'=>$deductionType,
                    'deductionDay'=>$deductionDay,
                    'totalMonths'=>$totalMonths,
                    'dateFrom'=>$dateFrom,
                    'dateTo'=>$dateTo,
                    'Items'=>$item,
                    'amountLoan'=>$amountLoan,
                    'deduction'=>$deduction,
                    'remainingBalance'=>$totalPayment,
                    'DateCreated'=>$dateCreated,
                );
                $this->simkimban_model->insert_simkimban_loan_data($insertData);
                $ref_no = $row_fl['ref_no'];
                $updateEmpFileLoanData = array(
                    'status'=>4
                );
                $this->employee_model->update_employee_file_loan_data($id,$updateEmpFileLoanData);

                $simkimbanId = $this->simkimban_model->simkimban_last_loan_id();
                $simkimbanId = $simkimbanId['simkimban_id'];
                $updateSimkimbanLoan = array(
                    'ref_no'=>$ref_no,
                );
                $this->simkimban_model->update_simkimban_loan_data($simkimbanId,$updateSimkimbanLoan);
                $this->data['status'] = "success";
                $this->data['msg'] = "Employee <strong>".$name."</strong> was successfully filed a <strong>Simkimban Loan</strong>.";
            }
        }

        echo json_encode($this->data);
    }
}