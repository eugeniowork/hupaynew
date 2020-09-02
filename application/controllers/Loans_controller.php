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

                    $finalData .="<tr id='".$value->salary_loan_id."'>";
                        $finalData .= "<td><small>".$emp_name."</small></td>";
                        $finalData .= "<td><small>".$date_range."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->amountLoan)."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->deduction)."</small></td>";
                        $finalData .= "<td><small>Php ".moneyConvertion($value->remainingBalance)."</small></td>";
                        $finalData .= "<td><small>".$value->deductionType."" .$day."</small></td>";
                        $finalData .= "<td><small>".$info."</small></td>";
                        $finalData .= "<td><small>";
                            $finalData .= "<button id=".$value->salary_loan_id." class='edit-salary-loan-btn btn btn-sm btn-outline-success' data-toggle='modal' data-target='#editSalaryLoanModal'><i class='fas fa-pencil-alt' id=".$value->salary_loan_id."></i></button>&nbsp;";
                            $finalData .= "<button class='btn btn-sm btn-outline-success'><i class='fas fa-adjust'></i></button>&nbsp;";
                            $finalData .= "<button class='btn btn-sm btn-outline-danger'><i class='fas fa-trash'></i></button>&nbsp;";
                            $finalData .= "<button class='btn btn-sm btn-outline-primary'><i class='fas fa-eye'></i></button>&nbsp;";
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
}