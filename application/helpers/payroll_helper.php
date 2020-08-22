<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');

    function ifPayrollExist($datePayroll){
        $CI =& get_instance();
        $CI->load->model('payroll_model');

        $payroll = $CI->payroll_model->get_payroll_date($datePayroll);

        return count($payroll);
    }
?>