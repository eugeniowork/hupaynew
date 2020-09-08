<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');

    function ifPayrollExist($datePayroll){
        $CI =& get_instance();
        $CI->load->model('payroll_model');

        $payroll = $CI->payroll_model->get_payroll_date($datePayroll);
        $count = 0;
        if(!empty($payroll)){
            $count = count($payroll);
        }
        return $count;
    }
    function existGeneratePayrollcutOff($cutOffPeriod){
        $CI =& get_instance();
        $CI->load->model('payroll_model');
        $payroll = $CI->payroll_model->generate_payroll_cutoff($cutOffPeriod);
        $count = 0;
        if(!empty($payroll)){
            $count = count($payroll);
        }
        return $count;
    }
    function checkExistPayrollInformation($employeeName,$cutOffPeriod){
        $CI =& get_instance();
        $CI->load->model('payroll_model');
        $CI->load->model('employee_model');

        $emp_id = "";
        $select_qry = $CI->employee_model->get_active_employee_row_array();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $name = $value->Lastname . ", " . $value->Firstname . " " . $value->Middlename;
				if ($value->Middlename == ""){
					$name = $value->Lastname . ", " . $value->Firstname;
				}

				if ($employeeName == $name){
					$emp_id = $value->emp_id;
                }
            }
        }
        $count = 0;
        $payroll = $CI->employee_model->get_employee_with_cut_off($emp_id, $cutOffPeriod);
        if(!empty($payroll)){
            $count = count($payroll);
        }
        return $count;
    }

    function getPayrollInfoByCutOffPeriodEmpName($employeeName,$cutOffPeriod){
        $CI =& get_instance();
        $CI->load->model('payroll_model');
        $CI->load->model('employee_model');
        $emp_id = "";
        $select_qry = $CI->employee_model->get_active_employee();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $name = $value->Lastname . ", " . $value->Firstname . " " . $value->Middlename;
				if ($value->Middlename == ""){
					$name = $value->Lastname . ", " . $value->Firstname;
				}

				if ($employeeName == $name){
					$emp_id = $value->emp_id;
                }
            }
        }
        $payroll = $CI->employee_model->get_employee_with_cut_off_row_array($emp_id, $cutOffPeriod);
        return $payroll;
    }

    function getAllPayrollNotif(){
        $CI =& get_instance();
        $CI->load->model('payroll_model');
        $CI->load->model('employee_model');
        $CI->load->model('position_model');
        $CI->load->model('salary_model');
        $CI->load->library('session');
        $CI->load->helper('hupay_helper');
        $emp_id = $CI->session->userdata('user');
        $finalData = "";
        $select_qry = $CI->payroll_model->get_payroll_notif($emp_id);
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                if ($value->notifType == 'Already Sent' || $value->notifType == 'Already Computed'){
                    $select_emp_qry = $CI->employee_model->employee_information($value->payroll_admin_id);

                    $payrollAdminName = $select_emp_qry['Firstname'] . " " . $select_emp_qry['Lastname'];

                    // for getting payroll admin position
                    $select_position_qry = $CI->position_model->get_employee_position($select_emp_qry['position_id']);

                    $dateCreated = date_format(date_create($value->dateCreated), 'F d, Y');

                    $time = date_format(date_create($value->dateCreated), 'g:i A');
                    $finalData .= '<div class="notif-content '.$value->notifType.' ">
                        <div class="d-flex flex-row">
                            <img src="'.base_url().'assets/images/'.$select_emp_qry['ProfilePath'].'">

                            <div class="notif-content-sub">
                                <b>Payroll Information</b> for the cut off <b>'.$value->cutOffPeriod.'</b> is '.$value->notifType.' by <b> '.$payrollAdminName.' - '.$select_position_qry['Position'].' </b> on
                                        <b>'.$dateCreated.'</b> at <b>'.$time.'</b>
                            </div>
                        </div>
                    </div>';
                }
                else if($value->notifType == "File Salary Loan"){
                    $select_file_salary_loan = $CI->salary_model->get_filed_salary_loan($value->file_salary_loan_id);

                    $file_emp_id = $select_file_salary_loan['emp_id'];

                    $select_emp_qry = $CI->employee_model->employee_information($file_emp_id);

                    $filer_name = $select_emp_qry['Firstname'] . " " . $select_emp_qry['Lastname'];

                    $dateCreated = date_format(date_create($value->dateCreated), 'F d, Y');

                    $time = date_format(date_create($value->dateCreated), 'g:i A');

                    $finalData .= '<div class="notif-content '.$value->file_salary_loan_id.' ">
                        <div class="d-flex flex-row">
                            <img src="'.base_url().'assets/images/'.$select_emp_qry['ProfilePath'].'">

                            <div class="notif-content-sub">
                                <b>'.$filer_name.'</b> '.$value->notifType.' with the amount of <b>Php '.moneyConvertion($select_file_salary_loan['amountLoan']).'</b> for <b>'.$select_file_salary_loan['totalMonths'].' months, '.$select_file_salary_loan['deductionType'].'</b> on
                                <b>'.$dateCreated.'</b> at <b>'.$time.'</b>
                            </div>
                        </div>
                    </div>';
                }
                else if($value->notifType == "Approve Your File Salary Loan" || $value->notifType == "Disapprove Your File Salary Loan"){
                    $select_file_salary_loan = $CI->salary_model->get_filed_salary_loan($value->file_salary_loan_id);

                    $approver_id = $select_file_salary_loan['approver_id'];

                    $select_emp_qry = $CI->employee_model->employee_information($approver_id);

                    $approver_name = $select_emp_qry['Firstname'] . " " . $select_emp_qry['Lastname'];

                    $dateCreated = date_format(date_create($value->dateCreated), 'F d, Y');

                    $time = date_format(date_create($value->dateCreated), 'g:i A');
                    $finalData .= '<div class="notif-content '.$value->file_salary_loan_id.' ">
                        <div class="d-flex flex-row">
                            <img src="'.base_url().'assets/images/'.$select_emp_qry['ProfilePath'].'">

                            <div class="notif-content-sub">
                                <b>'.$approver_name.'</b> '.$value->notifType.' with the amount of <b>Php '.moneyConvertion($select_file_salary_loan['amountLoan']).'</b> for <b>'.$select_file_salary_loan['totalMonths'].' months, '.$select_file_salary_loan['deductionType'].'</b> on
                                    <b>'.$dateCreated.'</b> at <b>'.$time.'</b>
                            </div>
                        </div>
                    </div>';
                }
            }
        }

        return $finalData;
    }
?>