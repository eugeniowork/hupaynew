<?php
    function getContribution($salary){
        $CI =& get_instance();
        $CI->load->model('sss_model');

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
                    $date_payroll = date_format(date_create($valueCutOff->datePayroll . ", " .$year),'d');
                }
                    
            }
        }
        $contribution = 0;
        if ($date_payroll == "30" || $date_payroll == "29" || $date_payroll == "28") {
            $select_qry = $CI->sss_model->get_sss_contribution();
            if(!empty($select_qry)){
                foreach($select_qry as $value){
                    if ($value->compensationTo != 0) {

                        if ($salary >= $value->compensationFrom && $salary <= $value->compensationTo) {
                            $contribution = $value->Contribution;
                        }
                    }

                    else {
                        if ($salary >= $value->compensationFrom) {
                            $contribution = $value->Contribution;
                        }

                    }
                }
            }
        }
        return $contribution;
    }
    function existPendingSSSLoan($emp_id){
        $CI =& get_instance();
        $CI->load->model('sss_model');
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
        $num_rows = $CI->sss_model->get_pending_sss_loan($date_payroll, $emp_id);
        $count = 0;
        if(!empty($num_rows)){
            $count = count($num_rows);
        }
        return $count;
    }

    function getSSSLoanToPayroll($emp_id){
        $CI =& get_instance();
        $CI->load->model('sss_model');
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
        $sss_loan_amount = 0;
        $select_qry = $CI->sss_model->get_employee_sss_loan($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                if ($value->dateFrom <= $date_payroll) {
					
					if ($value->deduction >= $value->remainingBalance){
						$sss_loan_amount += $value->remainingBalance;
					}

					else {
						$sss_loan_amount += $value->deduction;
					}
				}
            }
        }
        return $sss_loan_amount;
    }
    function deductionSSSLoan(){
        $CI =& get_instance();
        $CI->load->model('sss_model');
        $CI->load->model('cut_off_model');

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

        $select_qry = $CI->sss_model->get_sss_with_balance();
        $sss_loan_amount = 0;
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                if ($value->deduction >= $value->remainingBalance){
					$sss_loan_amount += $value->remainingBalance;
				}

				else {
					$sss_loan_amount += $value->deduction;
                }
                $remainingBalance = $value->remainingBalance - $sss_loan_amount;
                $update_qryData = array(
                    'remainingBalance'=>$remainingBalance,
                );
                $update_qry = $CI->sss_model->update_sss_loan_data($value->sss_loan_id, $update_qryData);

            }
        }
        return 'success';
    }
?>