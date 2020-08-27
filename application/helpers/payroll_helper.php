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
?>