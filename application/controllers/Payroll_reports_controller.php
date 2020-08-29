<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_reports_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("payroll_model", 'payroll_model');
        $this->load->model("employee_model", 'employee_model');
        $this->load->model("minimum_wage_model", 'minimum_wage_model');
        $this->load->model("dependent_model", 'dependent_model');
        $this->load->model("allowance_model", 'allowance_model');
        $this->load->model("attendance_model", 'attendance_model');
        $this->load->model("audit_trial_model", 'audit_trial_model');
        $this->load->helper('hupay_helper', 'hupay_helper');
        $this->load->helper('employee_helper', 'employee_helper');
        $this->load->helper('date_helper', 'date_helper');
        $this->load->helper('cut_off_helper', 'cut_off_helper');
        $this->load->helper('pagibig_helper', 'pagibig_helper');
        $this->load->helper('sss_helper', 'sss_helper');
        $this->load->helper('salary_helper', 'salary_helper');
        $this->load->helper('simkimban_helper', 'simkimban_helper');
        $this->load->helper('cashbond_helper', 'cashbond_helper');
        $this->load->helper('leave_helper', 'leave_helper');
        $this->load->helper('allowance_helper', 'allowance_helper');
        $this->load->helper('deduction_helper', 'deduction_helper');
    }
    public function index(){
        
        $this->data['pageTitle'] = 'Adjustment Report';
        $select_qry = $this->payroll_model->get_all_payroll_approval();
        $finalAdjustmentReportData = array();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $num_rows = $this->payroll_model->get_payroll_approval_adjustment(0);
                if(!empty($num_rows)){
                    array_push($finalAdjustmentReportData, array(
                        'id'=>$value->approve_payroll_id,
                        'cut_off_period'=>$value->CutOffPeriod,
                    ));
                }
            }
        }
        $this->data['finalAdjustmentReportData'] = $finalAdjustmentReportData;
        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('payroll_reports/adjustment_reports', $this->data);
        $this->load->view('global/footer');
    }
    public function printPayrollAdjustmentReport($id){
        $this->load->library('fpdf_master');
        $cutOff = $this->payroll_model->get_payroll_approval_id($id);
        $cutOffPeriod = $cutOff['CutOffPeriod'];
		//$this->fpdf->SetFont('Arial','B',18);
        $splitCutOff = explode("-",$cutOffPeriod);

		$this->fpdf->SetMargins("20","10"); // left top

		$this->fpdf->AddPage();

		$this->fpdf->SetFont("Arial","B","9");
		$this->fpdf->Cell(65,5,"LLOYDS FINANCING CORPORATION",0,1,"C");

		$this->fpdf->SetFont("Arial","","9");
		$this->fpdf->Cell(65,5,"LIST OF ACTIVE EMPLOYEES",0,1,"C");

		$this->fpdf->SetFont("Arial","","9");
		$this->fpdf->Cell(65,5,$splitCutOff[0],0,1,"C"); // from

		$this->fpdf->SetFont("Arial","","9");
        $this->fpdf->Cell(65,5,$splitCutOff[1],0,1,"C"); // to
        
        $date_create = date_create($splitCutOff[1]);
		$day = date_format($date_create, 'd');

		if ($day == "10") {
			$dayImgPayroll =base_url()."assets/images/img/payroll images/15.png";
		}

		if ($day == "25") {
			$dayImgPayroll = base_url()."assets/images/img/payroll images/30.png";
		}

		//$this->fpdf->Image($dayImgPayroll,85,10,15,20);// margin-left,margin-top,width,height
        $this->fpdf->Cell(65,5,"",0,1,"C"); // for margin


		if ($day == "10") {
			
			$this->fpdf->SetFont("Arial","B","7");
			// for headers of adjustment
			$this->fpdf->Cell(110,5,"",0,0,"C");
			$this->fpdf->Cell(40,5,"ADJUSTMENT",1,1,"C");
			// for header
			$this->fpdf->setWidths(array(50,20,20,20,20,20,20,30,20,50));
			$this->fpdf->setAligns(array("C","C","C","C","C","C","C","C","C","C"));
			$this->fpdf->row(array("EMPLOYEE NAME","CIVIL STATUS","SALARY","ALLOWANCE","BEFORE","AFTER","HDMF LOAN","CASH ADVANCE","CASH BOND","REMARKS"));
		}
        if ($day == "25") {
			$this->fpdf->SetFont("Arial","B","7");
			// for headers of adjustment
			$this->fpdf->Cell(90,5,"",0,0,"C");
			$this->fpdf->Cell(40,5,"ADJUSTMENT",1,1,"C");
			// for header
			$this->fpdf->SetWidths(array(50,20,20,20,20,20,20,30,20,50));
			$this->fpdf->SetAligns(array("C","C","C","C","C","C","C","C","C","C"));
			$this->fpdf->Row(array("EMPLOYEE NAME","CIVIL STATUS","SALARY","ALLOWANCE","BEFORE","AFTER","SSS LOAN","CASH ADVANCE","CASH BOND","REMARKS")); 
        }
        $select_qry = $this->payroll_model->get_payroll_info_adjustment_cut_off_period($cutOffPeriod);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $this->fpdf->SetFont("Arial","","7");

                $select_emp_qry = $this->employee_model->employee_information($value->emp_id);
                $fullName = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];
                $date_create = date_create($select_emp_qry['DateHired']);
                $dateHired = "(" . date_format($date_create, 'm/d/Y'). ")";
                $salary = $select_emp_qry['Salary'];
                $select_min_wage_qry = $this->minimum_wage->get_minimum_wage();
                $minimumWage = ($select_min_wage_qry['basicWage'] + $select_min_wage_qry['COLA']) * 26;
                $civilStatus = "S";
                if ($select_emp_qry['CivilStatus'] == "Married") {
					$civilStatus = "ME";
                }
                if ($salary > $minimumWage) {
                    $num_rows = $this->dependent_model->get_dependent_rows($select_emp_qry['emp_id']);
                    if ($num_rows == 0){
						$num_rows = "";
					}

					$civilStatus = $civilStatus . $num_rows;
                }
                if ($select_emp_qry['DateHired'] == "0000-00-00"){
					$dateHired = "";
                }
                $allowance = 0;
                $select_allowance_qry = $this->allowance_model->get_info_allowance($select_emp_qry['emp_id']);
                if(!empty($select_allowance_qry)){
                    foreach($select_allowance_qry as $valueAllowance){
                        if ($allowance == ""){
                            $allowance = $valueAllowance->AllowanceValue;		
                        }
                        else {
                            $allowance = $allowance + $valueAllowance->AllowanceValue;
                        }
                    }
                }
                if ($day == "10") {
					$loan = $value->pagibigLoan;
                }
                if ($day == "25") {
					$loan = $value->sssLoan;
				}
                $this->fpdf->Row(array($fullName . " ".$dateHired,$civilStatus,moneyConvertion($salary),moneyConvertion($allowance),moneyConvertion($value->adjustmentBefore),moneyConvertion($value->adjustmentAfter),moneyConvertion($loan),moneyConvertion($value->cashAdvance),moneyConvertion($value->CashBond),htmlspecialchars($value->remarks))); 
            }
        }



        $this->fpdf->Cell(65,5,"",0,1,"C"); // for margin
        echo $this->fpdf->Output('hello_world.pdf','D');
    }

    public function viewPayrollReports(){
        $this->data['pageTitle'] = 'Payroll Report';
        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('payroll_reports/payroll_reports');
        $this->load->view('global/footer');
    }
    public function getPayrollReports(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $role = $employeeInfo['role_id'];
        $select_qry = $this->payroll_model->get_all_payroll_approval_by_date();
        $append = '';
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                //done na to
                if($role == 3 ){
                    
                    if ($value->approveStat == 0){
                        $status = "Already Sent";
                    }

                    else if ($value->approveStat == 1){
                        $status = "Approved";
                    }


                    else if ($value->approveStat == 3){
                        $status = "On Proccess";
                    }

                    else if ($value->approveStat == 4){
                        $status = "Pre Approved";
                    }
                    $append .="<tr id=".$value->approve_payroll_id.">";
                    $append .= "<td>".$value->CutOffPeriod."</td>";
                    $append .="<td id='status".$value->approve_payroll_id."'>".$status."</td>";
                    $append .="<td id='action".$value->approve_payroll_id."'>";
                        $append .="<button id='print_payroll_reports' class='btn btn-link action-a'>Print</button>";
                        if ($value->approveStat == 3){
                            $append .= " | ";
                            $append .= "<button id=".$value->approve_payroll_id." class='send_payroll_reports btn btn-link action-a'>Send</button>";
                        }
                        if ($value->approveStat == 0 && $role == 1){
                            $append .= " | ";
                            $append .="<button id=".$value->approve_payroll_id." class='btn btn-link approve_payroll action-a' title='Approve'>Approve</button>";
                        }

                        if ($value->approveStat == 1 && $role == 3){

                            $append .= " | ";
                            $append .= "<button id=".$value->approve_payroll_id." class='btn btn-link print_salary_info action-a' title='Approve'>Salary Info</button>";
                        }
                    $append .="</td>";
                    $append .="</tr>";
                }
                if(($role == 1 || $emp_id == 47 || $emp_id == 44) && $value->approveStat != 3 && $value->approveStat != 0){
                    $this->data['pasok'] = 'asd';
                    if ($value->approveStat == 1){
                        $status = "Approved";
                    }
                    else if ($value->approveStat == 3){
                        $status = "On Proccess";
                    }
                    else if ($value->approveStat == 4){
                        $status = "Pre Approved";
                    }
                    $append .="<tr id=".$value->approve_payroll_id.">";
                    $append .= "<td>".$value->CutOffPeriod."</td>";
                    $append .="<td id='status".$value->approve_payroll_id."'>".$status."</td>";
                    $append .="<td id='action".$value->approve_payroll_id."'>";
                        $append .="<button id='print_payroll_reports' class='btn btn-link action-a'>Print</button>";
                        if ($value->approveStat == 4){
                            $append .=" | ";
							$append .="<button id=".$value->approve_payroll_id." data-toggle='modal' data-target='#approvePayrollModal' class='btn btn-link approve_payroll action-a' title='Approve'>Approve</button>";
                        }
                        if ($value->approveStat == 1 && $role == 3){
                            $append .=" | ";
                            $append .= "<button id=".$value->approve_payroll_id." class='btn btn-link print_salary_info action-a' title='Approve'>Salary Info</button>";
                        }
                    $append .="</td>";
                    $append .="</tr>";
                }
                //done na here
                if($role == 2 && $value->approveStat != 3){
                    
                    if ($value->approveStat == 0){
                        $status = "Already Sent";
                    }

                    else if ($value->approveStat == 1){
                        $status = "Approved";
                    }


                    else if ($value->approveStat == 3){
                        $status = "On Proccess";
                    }

                    else if ($value->approveStat == 4){
                        $status = "Pre Approved";
                    }

                    $append .="<tr id=".$value->approve_payroll_id.">";
                    $append .= "<td>".$value->CutOffPeriod."</td>";
                    $append .="<td id='status".$value->approve_payroll_id."'>".$status."</td>";
                    $append .="<td id='action".$value->approve_payroll_id."'>";
                        $append .="<button id='print_payroll_reports' class='btn btn-link action-a'>Print</button>";
                        if ($value->approveStat == 0 && $role == 2){
                            $append .=" | ";
                            $append .="<button data-toggle='modal' data-target='#preApprovePayrollModal' id=".$value->approve_payroll_id." class='btn btn-link pre_approve_payroll action-a' title='Approve'>Approve</button>";
                        }

                        if ($value->approveStat == 1 && $role == 3){

                            $append .=" | ";
                            $append .= "<button id=".$value->approve_payroll_id." class='btn btn-link print_salary_info action-a' title='Approve'>Salary Info</button>";
                        }
                    $append .="</td>";
                    $append .="</tr>";
                }
            } 
        }
        
        $this->data['data'] = $append;
        $this->data['status'] ="success";
        echo json_encode($this->data);
    }

    public function sendPayroll(){
        $id = $this->input->post('id');
        $checkPayrollIfExist = $this->payroll_model->get_payroll_approval_id($id);
        $checkIfPayrollApprove = $this->payroll_model->get_approve_payroll_by_id($id);
        if(empty($checkPayrollIfExist)){
            $this->data['status'] = "error";
            $this->data['msg'] = "There was a problem sending the payroll reports. Please try again!";
        }
        else if(!empty($checkIfPayrollApprove)){
            $this->data['status'] = "error";
            $this->data['msg'] = "There was a problem sending the payroll reports. Please try again!";
        }
        else{
            $insertPayrollData = array(
                'approveStat'=>0,
            );
            $insertPayroll = $this->payroll_model->update_payroll_approval($id, $insertPayrollData);
            $payroll_admin_id = $this->session->userdata('user');
            $emp_id_admin = getEmpIdRoleAdmin();
            $emp_id = explode("#",$emp_id_admin);
            $count = $this->employee_model->get_active_admin();
            if(!empty($count)){
                $count = count($count);
            }
            $payroll_id = 0;
            $notif_type = "Already Sent";
            $row = $this->payroll_model->get_payroll_approval_id($id);
            $cutOffPeriod = $row['CutOffPeriod'];
            $readStatus = 0;

            $dateCreated = getDateTime();
            $counter = 0;
            do{
                $insertNotificationsData = array(
                    'payroll_notif_id'=>'',
                    'payroll_admin_id'=>$payroll_admin_id,
                    'emp_id'=>$emp_id[$counter],
                    'payroll_id'=>$payroll_id,
                    'approve_payroll_id'=>$id,
                    'file_salary_loan_id'=>'0',
                    'notifType'=>$notif_type,
                    'cutOffPeriod'=>$cutOffPeriod,
                    'readStatus'=>$readStatus,

                );
                $insertNotifications = $this->payroll_model->insert_payroll_notifications($insertNotificationsData);
                $counter++;
            }while($counter < $count);

            $module = "Send Payroll Reports";
			$task_description = "Send Payroll Reports, " . $cutOffPeriod;
			$dateTime = getDateTime();
            $insertAuditTrialData = array(
                'audit_trail_id'=>'',
                'file_emp_id'=>0,
                'approve_emp_id'=>0,
                'involve_emp_id'=>$this->session->userdata('user'),
                'module'=>$module,
                'task_description'=>$task_description,
            );
            $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
            $this->data['status'] = "success";
        }
        
        echo json_encode($this->data);
    }

    public function preApprovePayroll(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $originalPassword = $employeeInfo['Password'];
        $password = $this->input->post('password');
        $approve_payroll_id = $this->input->post('id');
        $this->form_validation->set_rules('password', 'password','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please enter your password.";
        }
        else{
            if(password_verify($password, $originalPassword)){
                $current_date = getDateDate();

                $approvePayrollData = array(
                    'approveStat'=>4,
                    'preApproveDate'=>$current_date,
                );
                $approvePayroll = $this->payroll_model->update_payroll_approval($approve_payroll_id, $approvePayrollData);
                $cutOffPeriod = getCutOffPeriodLatest();

                $module = "Pre approve Payroll Reports";
                $task_description = "Pre approve Payroll Reports, " . $cutOffPeriod;
                $dateTime = getDateTime();
                $insertAuditTrialData = array(
                    'audit_trail_id'=>'',
                    'file_emp_id'=>0,
                    'approve_emp_id'=>0,
                    'involve_emp_id'=>$this->session->userdata('user'),
                    'module'=>$module,
                    'task_description'=>$task_description,
                );
                $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
                $this->data['status'] = "success";
            }
            else{
                $this->data['status'] = "error";
                $this->data['msg'] = "Your password is incorrect.";
            }
        }
        

    
        
        echo json_encode($this->data);
    }
    public function approvePayroll(){
        $approve_payroll_id = $this->input->post('id');
        $current_date = getDateDate();
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $originalPassword = $employeeInfo['Password'];
        $password = $this->input->post('password');
        $this->form_validation->set_rules('password', 'password','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please enter your password.";
        }
        else{
            if(password_verify($password, $originalPassword)){
                $approvePayrollData = array(
                    'approveStat'=>1,
                    'dateApprove'=>$current_date,
                );
                $approvePayroll = $this->payroll_model->update_payroll_approval($approve_payroll_id, $approvePayrollData);

                $row = $this->payroll_model->get_payroll_approval_id($approve_payroll_id);
                $cutOffPeriod = $row['CutOffPeriod'];
                $approveInfoPayrollData = array(
                    'payrollStatus'=>0,
                );
                $this->payroll_model->update_payroll_info($cutOffPeriod, $approveInfoPayrollData);
                //deductPagibigLoan();
                //deductionSSSLoan();
                //deductSalaryLoan($cutOffPeriod);
                //deductSimkimban($cutOffPeriod);
                //addYTDcurrentYear($cutOffPeriod);
                //insertEmpCashbondHistory($current_date);
                //deductLeaveCount();
                insertPayslipAllowance($cutOffPeriod,$current_date);
                $cutOffPeriod = getCutOffPeriodLatest();
                $emp_count = 0;
                $getActiveEmp = $this->employee_model->get_active_employee();
                if(!empty($getActiveEmp)){
                    $emp_count = count($getActiveEmp);
                }
                $notfi_emp_id = explode("#",getEmpIdAllActiveEmp());
                $notifType = "Already Computed";
                $emp_counter = 0;
                do {
                    $payroll_admin_id = $this->payroll_model->get_notif_payroll_by_admin($approve_payroll_id);
                    $row_payroll_info = $this->payroll_model->get_cut_off_13_month_pay_old_data($notfi_emp_id[$emp_counter],$cutOffPeriod);
                    $readStatus = '0';
                    $insertPayrollData = array(
                        'payroll_notif_id'=>'',
                        'payroll_admin_id'=>$payroll_admin_id,
                        'emp_id'=>$notfi_emp_id[$emp_counter],
                        'payroll_id'=>$row_payroll_info['payroll_id'],
                        'approve_payroll_id'=>0,
                        'file_salary_loan_id'=>0,
                        'notifType'=>$notifType,
                        'cutOffPeriod'=>$cutOffPeriod,
                        'readStatus'=>$readStatus,
                    );
                    //$insertPayroll = $this->payroll_model->insert_payroll_notifications($insertPayrollData);

                    $emp_counter++;

                }while($emp_counter < $emp_count);

                $module = "Approve Payroll Reports";
                $task_description = "Approve Payroll Reports, " . $cutOffPeriod;
                $dateTime = getDateTime();
                $insertAuditTrialData = array(
                    'audit_trail_id'=>'',
                    'file_emp_id'=>0,
                    'approve_emp_id'=>0,
                    'involve_emp_id'=>$this->session->userdata('user'),
                    'module'=>$module,
                    'task_description'=>$task_description,
                );
                //$insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
                //if disapprove
                $payroll_class->disappovePayroll($approve_payroll_id,$current_date);

                // for updating table payrol info
                // $row = $payroll_class->getInfoPayrollAppoval($approve_payroll_id);

                // $cutOffPeriod = $row->CutOffPeriod;

                // $payroll_class->disapproveInfoPayroll($cutOffPeriod);

                // $_SESSION["approve_payroll_failed"] = "failed";
                $this->data['status'] = "success";
            }
            else{
                $this->data['status'] = "error";
                $this->data['msg'] = "Your password is incorrect.";
            }
            
        }
        echo json_encode($this->data);

    }
}