<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simkimban_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("simkimban_model", 'simkimban_model');
        $this->load->model("employee_model", "employee_model");
        $this->load->model("adjustment_loan_model", "adjustment_loan_model");
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
        $this->data['pageTitle'] = 'SIMKIMBAN';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('simkimban/simkimban');
        $this->load->view('global/footer');
    }

    public function getEmployeeWithExistingSimkimban(){
        $select_qry = $this->simkimban_model->get_employee_with_existing_simkimban();
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
                if($value->remainingBalance != 0){
                    $finalData .="<tr id='".$value->simkimban_id."'>";
                        $finalData .="<td><small>".$emp_name."</small></td>";
                        $finalData .="<td><small>".$date_range."</small></td>";
                        $finalData .="<td><small>".$value->Items."</small></td>";
                        $finalData .="<td><small>Php ".moneyConvertion($value->amountLoan)."</small></td>";
                        $finalData .="<td><small>Php ".moneyConvertion($value->deduction)."</small></td>";
                        $finalData .="<td><small>Php ".moneyConvertion($value->remainingBalance)."</small></td>";
                        $finalData .="<td><small>";
                            $finalData .="<button id=".$value->simkimban_id." class='edit-simkimban btn btn-sm btn-outline-success' data-toggle='modal' data-target='#editSimkimbanModal'>Edit</button>";
                            $finalData .= "<button id=".$value->simkimban_id." class='adjust-simkimban btn btn-sm btn-outline-primary' data-toggle='modal' data-target='#adjustSimkimbanModal'>Adjustment</button>";
                            //echo "<span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> <a href='#' id='delete_simkimban' class='action-a'>Delete</a>";
                            //echo "<span> | </span>";
                            $finalData .= "<button id=".$value->simkimban_id." class='btn btn-sm btn-outline-success view-simkimban-history-btn' data-toggle='modal' data-target='#simkimbanHistoryModal'>View</button>";
                            $finalData .= "<button class='btn btn-sm btn-outline-success'>Print</button>";
                        $finalData .= "</small></td>";
                    $finalData .= "</tr>";
                }
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    public function getSimkimbanInfo(){
        $id = $this->input->post('id');
        $simkimbanInfo = $this->simkimban_model->get_simkimban_data($id);
        $finalData = array();
        if(!empty($simkimbanInfo)){
            $row_emp = $this->employee_model->employee_information($simkimbanInfo['emp_id']);
            if ($row_emp['Middlename'] == ""){
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }
            else {
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            }
            $dateFrom = dateDefault($simkimbanInfo['dateFrom']);
            $dateTo = dateDefault($simkimbanInfo['dateTo']);
            $item = $simkimbanInfo['Items'];
            $amountLoan = $simkimbanInfo['amountLoan'];
            $deduction = $simkimbanInfo['deduction'];
            $remainingBalance = $simkimbanInfo['remainingBalance'];

            array_push($finalData, array(
                'name'=>$full_name,
                'deduction_type'=>$simkimbanInfo['deductionType'],
                'deduction_day'=>$simkimbanInfo['deductionDay'],
                'total_months'=>$simkimbanInfo['totalMonths'],
                'date_from'=>$dateFrom,
                'date_to'=>$dateTo,
                'item'=>$item,
                'amount_loan'=>$amountLoan,
                'deduction'=>$deduction,
                'remaining_balance'=>$remainingBalance,
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

    public function updateSimkimbanData(){
        $id = $this->input->post('id');
        $deductionType = $this->input->post('deductionType');
        
        $totalMonths = $this->input->post('totalMonths');
        $dateFrom = dateDefaultDb($this->input->post('dateFrom'));
        $dateTo = dateDefaultDb($this->input->post('dateTo'));
        $item = $this->input->post('item');
        $amountLoan = $this->input->post('amountLoan');
        $deduction = $this->input->post('deduction');
        $remainingBalance = $this->input->post('remainingBalance');
        $deductionDay = 0;

        $this->form_validation->set_rules('deductionType', 'deductionType', 'required');
        $this->form_validation->set_rules('totalMonths', 'totalMonths', 'required');
        $this->form_validation->set_rules('dateFrom', 'dateFrom', 'required');
        $this->form_validation->set_rules('dateTo', 'dateTo', 'required');
        $this->form_validation->set_rules('item', 'item', 'required');
        $this->form_validation->set_rules('amountLoan', 'amountLoan', 'required');
        $this->form_validation->set_rules('deduction', 'deductideductiononType', 'required');
        $this->form_validation->set_rules('remainingBalance', 'remainingBalance', 'required');
        $this->form_validation->set_rules('deductionDay', 'deductionDay', 'required');   

        if($deductionType == "Monthly"){
            $deductionDay = $this->input->post('deductionDay');
        }
        $sameSimkimban = $this->simkimban_model->if_simkimband_has_no_changes($id, $deductionType, $deductionDay, $totalMonths, $dateFrom,$dateTo,$item,$amountLoan,$deduction, $remainingBalance);
        if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
            $this->data['status'] = 'error';
            $this->data['msg'] = "Invalid deduction day.";
        }
        else if(!empty($sameSimkimban)){
            $this->data['status'] = 'error';
            $this->data['msg'] = "There's no changes in the data.";
        }
        else if($dateFrom >= $dateTo){
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
                'Items'=>$item,
                'amountLoan'=>$amountLoan,
                'deduction'=>$deduction,
                'remainingBalance'=>$remainingBalance,
            );
            $update = $this->simkimban_model->update_simkimban_loan_data($id, $updateData);
            $this->data['status'] = "success";
        }

        //$this->data['asd'] = $deductionDay;
        echo json_encode($this->data);
    }

    public function getAdjustSimkimbanInfo(){
        $id = $this->input->post('id');
        $simkimbanInfo = $this->simkimban_model->get_simkimban_data($id);
        if(!empty($simkimbanInfo)){
            $row_emp = $this->employee_model->employee_information($simkimbanInfo['emp_id']);
            if ($row_emp['Middlename'] == ""){
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'];
            }
            else {
                $full_name = $row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename'];
            }
            //$dateFrom = dateDefault($simkimbanInfo['dateFrom']);
            //$dateTo = dateDefault($simkimbanInfo['dateTo']);
            //$item = $simkimbanInfo['Items'];
            //$amountLoan = $simkimbanInfo['amountLoan'];
            //$deduction = $simkimbanInfo['deduction'];
            $remainingBalance = $simkimbanInfo['remainingBalance'];

            // array_push($finalData, array(
            //     'name'=>$full_name,
            //     'deduction_type'=>$simkimbanInfo['deductionType'],
            //     'deduction_day'=>$simkimbanInfo['deductionDay'],
            //     'total_months'=>$simkimbanInfo['totalMonths'],
            //     'date_from'=>$dateFrom,
            //     'date_to'=>$dateTo,
            //     'item'=>$item,
            //     'amount_loan'=>$amountLoan,
            //     'deduction'=>$deduction,
            //     'remaining_balance'=>$remainingBalance,
            // ));
            $this->data['remainingBalance'] = $remainingBalance;
            $this->data['name'] = $full_name;
            $this->data['status'] = "success";
        }   
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }

    public function adjustSimkimbanData(){
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
                $updateRemainingBalance = $this->simkimban_model->update_simkimban_loan_data($id, $updateRemainingBalanceData);
                $simkimbanInfo = $this->simkimban_model->get_simkimban_data($id);
                $emp_id = $simkimbanInfo['emp_id'];
                $pagibig_loan_id = 0;
                //$sss_loan_id = 0;
                $sssLoanId = 0;
                $loanType = "Simkimban";
                $salaryLoanId = 0;
                $current_date_time = getDateDate();

                $insertAdjustmentLoanData = array(
                    'adjustment_loan_id'=>'',
                    'emp_id'=>$emp_id,
                    'datePayment'=>$adjustDatePayment,
                    'pagibig_loan_id'=>$pagibig_loan_id,
                    'sss_loan_id'=>$sssLoanId,
                    'salary_loan_id'=>$salaryLoanId,
                    'simkimban_id'=>$id,
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

    public function getSimkimbanHistoryInfo(){
        $id = $this->input->post('id');
        $history = $this->simkimban_model->get_simkimban_data_order_date($id);
        $finalData = "";
        if(!empty($history)){
            foreach ($history as $value) {
                $finalData .="<tr>";
                    $finalData .= "<td>".$value->date_payroll."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->deduction)."</td>";
                    $finalData .= "<td>Php ".moneyConvertion($value->remainingBalance)."</td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = 'success';
        echo json_encode($this->data);

    }

    public function getEmployeeSimkimbanHistoryList(){
        $select_qry = $this->simkimban_model->get_simkimban_zero_balance();
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
                $finalData .="<tr>";
                    $finalData .= "<td><small>".$emp_name."</small></td>";
                    $finalData .= "<td><small>".$date_range."</small></td>";
                    $finalData .= "<td><small>".$value->Items."</small></td>";
                    $finalData .= "<td><small>Php ".moneyConvertion($value->amountLoan)."</small></td>";
                    $finalData .= "<td><small>Php ".moneyConvertion($value->deduction)."</small></td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function getSimkimbanHistoryList(){
        $id = $this->session->userdata('user');
        $select_qry = $this->simkimban_model->get_simkimban_history($id);
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
                $finalData .="<tr>";
                    $finalData .= "<td>".$date_range."</td>";
                    $finalData .= "<td>".$value->Items."</td>";
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

    public function addNewSimkimban(){
        $empId = $this->input->post('empId');
        $name = $this->input->post('name');
        $deductionType= $this->input->post('deductionType');
        
        $totalMonths= $this->input->post('totalMonths');
        $dateFrom= dateDefaultDb($this->input->post('dateFrom'));
        $dateTo= dateDefaultDb($this->input->post('dateTo'));
        $item= $this->input->post('item');
        $amountLoan= $this->input->post('amountLoan');
        $deduction= $this->input->post('deduction');
        $remainingBalance= $this->input->post('remainingBalance');
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
        $this->form_validation->set_rules('item', 'item','required');
        $this->form_validation->set_rules('amountLoan', 'amountLoan','required');
        $this->form_validation->set_rules('deduction', 'deduction','required');
        $this->form_validation->set_rules('remainingBalance', 'remainingBalance','required');
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
                else if($dateFrom > $dateTo){
                    $this->data['status'] = 'error';
                    $this->data['msg'] = "The <strong>Date From</strong> must be below the date of the declared <b>Date To</b>.";
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
                        'remainingBalance'=>$remainingBalance,
                        'status'=>1,
                        'DateCreated'=>$dateCreated
                    );

                    $insert = $this->simkimban_model->insert_simkimban_loan_data($insertData);

                    $this->data['status'] = "success";
                    $this->data['msg'] = "Simkimban loan was successfully filed.";
                }
            }
        }

        echo json_encode($this->data);
    }
}