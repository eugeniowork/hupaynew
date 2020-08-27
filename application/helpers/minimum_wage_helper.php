<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');

    function checkMinWageEffectiveDateInCutOff($emp_id,$monthly_rate_with_allowance){
        $CI =& get_instance();
        $CI->load->model('minimum_wage_model');
        $CI->load->model('attendance_model');
        
        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		$current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        $select_cutoff_qry = $CI->attendance_model->get_cut_off();
        if(!empty($select_cutoff_qry)){
            foreach($select_cutoff_qry as $valueCutOff){
                $date_from = date_format(date_create($valueCutOff->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($valueCutOff->dateFrom),'m-d') == "12-26"){
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($valueCutOff->dateFrom),'m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($valueCutOff->dateTo. ", " .$year),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                    $final_date_from = $date_from;
                    $final_date_to = $date_to;
                    $date_payroll = date_format(date_create($valueCutOff->datePayroll . ", " .$year),'Y-m-d');
                }
                    
            }
        }
        $select_qry = $CI->minimum_wage_model->get_minimum_wage();
        $row = $select_qry;
        $inCutOff = 0;
		if ($row['effectiveDate'] >= $final_date_from AND $row['effectiveDate'] <= $final_date_to){


			$monthly_min_wage = $CI->minimum_wage->get_minimum_wage();
            $monthly_min_wage = ($monthly_min_wage['basicWage'] + $monthly_min_wage['COLA']) * 26;
			if ($monthly_rate_with_allowance <= $monthly_min_wage) {
				$inCutOff = 1;
			}
			//echo $monthly_rate_with_allowance;	
        }
        return $inCutOff;
    }
    function getMinimumWage(){
        $CI =& get_instance();
        $CI->load->model('minimum_wage_model');
        $monthly_min_wage = $CI->minimum_wage_model->get_minimum_wage();
        $minimumWage = ($monthly_min_wage['basicWage'] + $monthly_min_wage['COLA']) * 26;

        return $minimumWage;
    }

    function getMinWageEffectiveDate(){
        $CI =& get_instance();
        $CI->load->model('minimum_wage_model');

        $minWage = $CI->minimum_wage_model->get_minimum_wage();
        $date_create = date_create($minWage['effectiveDate']);
        $date_format = date_format($date_create, 'F d, Y');
        
        return $date_format;
    }
    function existMinWage(){
        $CI =& get_instance();
        $CI->load->model('minimum_wage_model');
        $minWage = $CI->minimum_wage_model->get_all_min_wage();
        $count = 0;
        if(!empty($minWage)){
            $count = count($minWage);
        }
        return $count;
    }
    function getLatestMinimumWage(){
        $CI =& get_instance();
        $CI->load->model('minimum_wage_model');
        $CI->load->model('employee_model');
        $CI->load->helper('hupay_helper');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        $employeeInformation = $CI->employee_model->employee_information($emp_id);
        $minWage = $CI->minimum_wage_model->get_minimum_wage();

        $minWageData = array();
        if(!empty($minWage)){
            $action = 'no';
            if($employeeInformation['role_id'] == 1){
                $action='yes';
            }
            array_push($minWageData, array(
                'min_wage_id'=>$minWage['min_wage_id'],
                'basic_wage'=>moneyConvertion($minWage['basicWage']),
                'cola'=>moneyConvertion($minWage['COLA']),
                'min_wage_rates'=>moneyConvertion($minWage['basicWage'] + $minWage['COLA']),
                'action'=>$action,
            ));
        }
        return $minWageData;
    }

    function sameMinWageInfo($effectiveDate,$basicWage,$cola, $id){
        $CI =& get_instance();
        $CI->load->model('minimum_wage_model');
        $minWage = $CI->minimum_wage_model->get_min_wage_id($id);
        $minWageBasicWage = $minWage['basicWage'];
        $minWageCola = $minWage['COLA'];
        $minWageEffectiveDate = $minWage['effectiveDate'];
        $count = 0;
        if($minWageEffectiveDate == $effectiveDate && $minWageBasicWage == $basicWage && $minWageCola == $cola){
            $count = 1;
        }   
        return $count;
    }
    function getMinimumWageHistory(){
        $CI =& get_instance();
        $CI->load->model('minimum_wage_model');
        $CI->load->helper('hupay_helper');
        $select_qry = $CI->minimum_wage_model->get_all_minimum_wage_sorted();
        $minWageData = array();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $date_create = date_create($value->effectiveDate);
                $date_format = date_format($date_create, 'F d, Y');
                
                array_push($minWageData, array(
                    'date_format'=>$date_format,
                    'basic_wage'=>moneyConvertion($value->basicWage),
                    'cola'=>moneyConvertion($value->COLA),
                    'min_wage_rates'=>moneyConvertion($value->basicWage + $value->COLA),
                    'monthly_rate'=>moneyConvertion(($value->basicWage + $value->COLA) * 26),
                ));
            }
        }
        return $minWageData;
    }
    
?>