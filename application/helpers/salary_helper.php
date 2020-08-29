<?php
    function existPendingSalaryLoan($emp_id){
        $CI =& get_instance();
        $CI->load->model('salary_model');
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
        $num_rows = $CI->salary_model->get_pending_salary_loan($date_payroll, $emp_id);
        $count = 0;
        if(!empty($num_rows)){
            $count = count($num_rows);
        }
        return $count;
    }
    function getSalaryLoanInfoToPayroll($emp_id){
        $CI =& get_instance();
        $CI->load->model('salary_model');
        $CI->load->model('attendance_model');
        $CI->load->helper('cut_off_helper');
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
        $cutOff_day = getCutOffDay();
        $salary_loan_amount = 0;
        $select_qry = $CI->salary_model->get_employee_salary_loan($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                if ($value->dateFrom <= $date_payroll && $value->dateTo >= $date_payroll) { 
                    if ($value->deductionType == "Monthly" && $value->deductionDay == $cutOff_day) {
						if ($value->deduction >= $value->remainingBalance){
							$salary_loan_amount += $value->remainingBalance;
						}
						else {
							$salary_loan_amount += $value->deduction;
						}
                    }
                    if ($value->deductionType == "Semi-monthly"){
						if ($value->deduction >= $value->remainingBalance){
							$salary_loan_amount += $value->remainingBalance;
						}

						else {
							$salary_loan_amount += $value->deduction;
						}
					}
                }
            }
        }
        return $salary_loan_amount;
    }
    function getAllSalaryLoan($emp_id){
        $CI =& get_instance();
        $CI->load->model('salary_model');
        $remainingBalance = 0;
        $select_qry = $CI->salary_model->get_info_salary($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $remainingBalance += $value->remainingBalance;
            }
        }
        return $remainingBalance;
    }
    function deductSalaryLoan($cutOffPeriod){
        $CI =& get_instance();
        $CI->load->model('salary_model');
        $CI->load->model('cut_off_model');
        $CI->load->model('employee_model');
        $CI->load->helper('cut_off_helper');
        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");

        $select_cutoff_qry = $CI->cut_off_model->get_cut_off();
        if(!empty($select_cutoff_qry)){
            foreach($select_cutoff_qry as $value){
                $date_from = date_format(date_create($value->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo. ", " .$year),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                    $final_date_from = $date_from;
                    $final_date_to = $date_to;
                    $date_payroll = date_format(date_create($value->datePayroll . ", " .$year),'Y-m-d');
                }
            }
        }
        $cutOff_day = getCutOffDay();

        $remainingBalance = 0;
        $select_qry = $CI->salary_model->get_salary_with_balance();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $active_status = $CI->employee_model->employee_information($value->emp_id);
                $active_status = $active_status['ActiveStatus'];

                if ($active_status == 1){
                    if ($value->dateFrom <= $date_payroll) { 
                        if ($value->deductionType == "Monthly" && $value->deductionDay == $cutOff_day) {
                            $remainingBalance = $value->remainingBalance - $value->deduction;
                            if ($remainingBalance <= 0){
								$remainingBalance = 0;
                            }
                            $update_qryData = array(
                                'remainingBalance'=>$remainingBalance,
                            );
                            $update_qry = $CI->salary_model->update_salary_loan_data($value->salary_loan_id, $update_qryData);
                            $salaryHistoryInsertData = array(
                                'salary_loan_id'=>$value->salary_loan_id,
                                'remainingBalance'=>$remainingBalance,
                                'deduction'=>$value->deduction,
                                'CutOffPeriod'=>$cutOffPeriod,
                                'date_payroll'=>$date_payroll,
                                'dateCreated'=>$current_date_time,
                            );
                            insert_salary_loan_history_data($salaryHistoryInsertData);
                        }
                        if ($value->deductionType == "Semi-monthly"){
                            $remainingBalance = $value->remainingBalance - $value->deduction;
                            if ($remainingBalance <= 0){
								$remainingBalance = 0;
                            }
                            $update_qryData = array(
                                'remainingBalance'=>$remainingBalance,
                            );
                            $update_qry = $CI->salary_model->update_salary_loan_data($value->salary_loan_id, $update_qryData);
                            $salaryHistoryInsertData = array(
                                'salary_loan_id'=>$value->salary_loan_id,
                                'remainingBalance'=>$remainingBalance,
                                'deduction'=>$value->deduction,
                                'CutOffPeriod'=>$cutOffPeriod,
                                'date_payroll'=>$date_payroll,
                                'dateCreated'=>$current_date_time,
                            );
                            $CI->salary_model->insert_salary_loan_history_data($value->salary_loan_id,$salaryHistoryInsertData);
                        }
                    }
                }
            }
        }
        return 'success';
    }
?>