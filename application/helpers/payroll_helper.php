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
?>