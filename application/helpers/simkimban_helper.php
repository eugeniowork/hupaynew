<?php
    function existPendingSimkimban($emp_id){
        $CI =& get_instance();
        $CI->load->model('simkimban_model');
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
        $num_rows = $CI->simkimban_model->get_pending_simkimban_loan($date_payroll, $emp_id);
        $count = 0;
        if(!empty($num_rows)){
            $count = count($num_rows);
        }
        return $count;
    }
    function getInfoBySimkimbanEmpId($emp_id){
        $CI =& get_instance();
        $CI->load->model('simkimban_model');
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
        $simkimban_amount = 0;
        $select_qry = $CI->simkimban_model->get_employee_simkimban_loan($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                if ($value->dateFrom <= $date_payroll && $value->dateTo >= $date_payroll){
                    if ($value->deductionType == "Monthly" && $value->deductionDay == getCutOffDay()){
                        if ($simkimban_amount == 0){
							if ($value->deduction >= $value->remainingBalance){
								$simkimban_amount = $value->remainingBalance;
							}
							else {
								$simkimban_amount = $value->deduction;
							}
						}
						else {
							if ($value->deduction >= $value->remainingBalance){
								$simkimban_amount = $value->remainingBalance + $simkimban_amount;
							}
							else {
								$simkimban_amount = $value->deduction + $simkimban_amount;
							}
						}
                    }
                    if ($value->deductionType == "Semi-monthly"){
						if ($simkimban_amount == 0){
							if ($value->deduction >= $value->remainingBalance){
								$simkimban_amount = $value->remainingBalance;
							}
							else {
								$simkimban_amount = $value->deduction;
							}
						}
						else {
				
							if ($value->deduction >= $value->remainingBalance){
								$simkimban_amount = $value->remainingBalance + $simkimban_amount;
							}
							else {
								$simkimban_amount = $value->deduction + $simkimban_amount;
							}
						}
					}
                }
            }
        }
        return $simkimban_amount;
    }
?>