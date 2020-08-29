<?php
    function getAllowanceInfoByEmpId($emp_id){
        $CI =& get_instance();
        $CI->load->model('allowance_model');
        $allowance = 0;
        $select_qry = $CI->allowance_model->get_info_allowance($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                if ($allowance == 0){
                    $allowance = $value->AllowanceValue;		
                }
                else {
                    $allowance = $allowance + $value->AllowanceValue;
                }
            }
        }
        return $allowance;
    }
    function getAllowanceInfoToPayslip($emp_id){
        $CI =& get_instance();
        $CI->load->model('allowance_model');
        $allowance = 0;
        $select_qry = $CI->allowance_model->get_info_allowance($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                if ($allowance == ""){
                    $allowance = $value->AllowanceValue;		
                }
                else {
                    $allowance = $allowance + $value->AllowanceValue;
                }
            }
        }
        return $allowance;
    }

    function getBasicPayAmount($emp_id){
        $CI =& get_instance();
        $CI->load->model('allowance_model');
        $CI->load->model('employee_model');
        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
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
        $dates = array();
        $from = strtotime($final_date_from);
        $last = strtotime($final_date_to);
        $output_format = 'Y-m-d';
        $step = '+1 day';

        $count = 0;
        while( $from <= $last ) {

            $count++;
            $dates[] = date($output_format, $from);
            $from = strtotime($step, $from);
            
        }
        $count = $count- 1;
        
        $weekdays = array();

        $counter = 0;


        $daysAffectedIncrease = 0;  
        $daysNotAffectedIncrease = 0;  
        $weekdays_count = 0;
        $minWageEffectiveDate = $CI->minimum_wage_model->get_minimum_wage();
        //$minWageEffectiveDate = $minWageEffectiveDate['effectiveDate'];
        $getLastEffectiveDate = $CI->minimum_wage_model->get_minimum_wage_last();
        do{
            $date_create = date_create($dates[$counter]);
            $day = date_format($date_create, 'l');
            if ($day != "Saturday" && $day != "Sunday"){
                $weekdays[] = $dates[$counter];
                $date =  $dates[$counter];

                $allowance = 0;
                $select_allowance_qry = $CI->allowance_model->get_info_allowance($emp_id);
                if(!empty($select_allowance_qry)){
                    foreach($select_allowance_qry as $value){
                        if ($allowance == ""){
                            $allowance = $value->AllowanceValue;		
                        }
                        else {
                            $allowance = $allowance + $value->AllowanceValue;
                        }
                    }
                }
                
                if ($date < $minWageEffectiveDate['effectiveDate']) {
                    $min_wage_increase = $minWageEffectiveDate['basicWage'] - $getLastEffectiveDate['basicWage'];
                    $monthly_increase = ($min_wage_increase * 26);

                    $daysAffectedIncrease++;
                }
                else{
                    $daysNotAffectedIncrease++;
                }
            }
            $counter++;
        }while($counter <= $count);
        $latest_min_wage = round((($minWageEffectiveDate['basicWage'] + $minWageEffectiveDate['COLA']) * 26)/22)*$daysAffectedIncrease;
        $oldest_min_wage = round((($getLastEffectiveDate['basicWage'] + $minWageEffectiveDate['COLA']) * 26)/22)*$daysNotAffectedIncrease;

        return round($latest_min_wage + $oldest_min_wage,2);
    }

    function insertPayslipAllowance($CutOffPeriod,$date_created){
        $CI =& get_instance();
        $CI->load->model('allowance_model');
        $CI->load->model('employee_model');

        $select_qry = $CI->allowance_model->get_all_payroll_info();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $select_pay_qry = $CI->allowance_model->get_info_allowance($value->emp_id);
                if(!empty($select_pay_qry)){
                    foreach($select_pay_qry as $valuePay){
                        $allowance = $valuePay->AllowanceValue / 2;
                        $insert_qryData = array(
                            'payroll_id'=>$value->payroll_id,
                            'CutOffPeriod'=>$CutOffPeriod,
                            'emp_id'=>$value->emp_id,
                            'allowanceType'=>$value->AllowanceType,
                            'allowanceValue'=>$allowance,
                            'date_created'=>$date_created,
                        );
                        $insert_qry = $CI->allowance_model->insert_allowance_date($insert_qryData);
                    }
                }
            }
        }
        return 'success';
    }
?>