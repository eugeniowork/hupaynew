<?php

    function insertEmpCashbondHistory($dateCreated){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');
        $CI->load->model('employee_model');
        $CI->load->model('payroll_model');
        $CI->load->model('cashbond_model');

        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        
        $current_date_time = date_format($date, 'Y-m-d');

        $year = date("Y");
        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
        $cutOff = $CI->cut_off_model->get_cut_off();
        $date_payroll = "N/A";
        if(!empty($cutOff)){
            foreach($cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom),'Y-m-d');
				if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
					//echo "wew";
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
					//echo $date_from . "sad";
					//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($value->datePayroll),'Y-m-d');
				}
            }
        }
        $cut_off_period =  date_format(date_create($final_date_from),'F d, Y') . " - " . date_format(date_create($final_date_to),'F d, Y');
        $select_qry = $CI->employee_model->get_all_employee();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $employee = $CI->employee_model->get_employee_with_cut_off_row_array($value->emp_id, $cut_off_period);
                if(!empty($employee)){
                    $payroll_info_cashbond = $employee['CashBond'];
					$emp_id = $value->emp_id;
					$cashbond_deposit = $employee['CashBond'];
                    $current_date_payroll = $employee['datePayroll'];
                    
                    $num_rows_payroll_info = $CI->employee_model->employee_information($value->emp_id);
                    if(empty($num_rows_payroll_info)){
                        $newTotalCashbond = $payroll_info_cashbond;
						$cashbond_deposit = $payroll_info_cashbond;
						$interest = 0;
                    }
                    else{
                        $counter = 1;
                        $old_date_payroll = "";
                        $select_old_date_payroll = $CI->employee_model->get_employee_order_by_and_limit($value->emp_id,'datePayroll','DESC',2);
                        if(!empty($select_old_date_payroll)){
                            foreach($select_old_date_payroll as $valueDatePayroll){
                                if ($counter == 1){
									$counter++;
								}
								else {
									$old_date_payroll = $valueDatePayroll->DateCreated;
								}
                            }
                        }
                        $current_date_payroll = strtotime(date("Y-m-d"));
                        $old_date_payroll = strtotime($old_date_payroll);
                        $secs = $current_date_payroll - $old_date_payroll;// == <seconds between the two times>
                        $days = $secs / 86400;
                        $percentage = .05;
                        $cashbondHistoryEndingBalance = $CI->cashbond_model->get_cashbond_current_ending_balance_order_by($emp_id);
                        if($cashbondHistoryEndingBalance['cashbond_balance'] >= 30000){
                            $percentage = .07;
                        }
                        $interest = round(($days) * $cashbondHistoryEndingBalance['cashbond_balance'] * ($percentage/360),2);

                        $newTotalCashbond = round($interest + $cashbondHistoryEndingBalance['cashbond_balance']+ $payroll_info_cashbond,2);
                    }
                    $current_date_payroll2 = date("Y-m-d");
                    $insert_qryData = array(
                        'emp_cashbond_history'=>'',
                        'emp_id'=>$emp_id,
                        'cashbond_deposit'=>$cashbond_deposit,
                        'interest'=>$interest,
                        'posting_date'=>$current_date_payroll2,
                        'amount_withdraw'=>0,
                        'cashbond_balance'=>$newTotalCashbond,
                        'interest_rate'=>3,
                        'dateCreated'=>$dateCreated,
                    );
                    $insert_qry = $CI->cashbond_model->insert_cashbond_data($insert_qryData);
                    $update_qryData = array(
                        'totalCashbond'=>$newTotalCashbond,
                    );
                    $update_qry = $CI->cashbond_model->update_cashbond_data($emp_id, $update_qryData);
                }
            }
        }
        return 'success';
    }

    // function addCashbondTotalValue(){
    //     $CI =& get_instance();
    //     $CI->load->model('cut_off_model');
    //     $CI->load->model('employee_model');
    //     $CI->load->model('payroll_model');
    //     $CI->load->model('cashbond_model');

    //     $dates = date("Y-m-d H:i:s");
    //     $date = date_create($dates);
        
    //     $current_date_time = date_format($date, 'Y-m-d');

    //     $year = date("Y");
    //     $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
    //     $cutOff = $CI->cut_off_model->get_cut_off();
    //     $date_payroll = "N/A";
    //     if(!empty($cutOff)){
    //         foreach($cutOff as $value){
    //             $date_from = date_format(date_create($value->dateFrom),'Y-m-d');
	// 			if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
	// 				//echo "wew";
	// 				$prev_year = $year - 1;
	// 				$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
	// 				//echo $date_from . "sad";
	// 				//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

    //             }
    //             $date_from = date_format(date_create($date_from),"Y-m-d");
    //             $date_to = date_format(date_create($value->dateTo),'Y-m-d');
    //             $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
	// 			if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
	// 				$final_date_from = $date_from;
	// 				$final_date_to = $date_to;
	// 				$date_payroll = date_format(date_create($value->datePayroll),'Y-m-d');
	// 			}
    //         }
    //     }
    //     $cut_off_period =  date_format(date_create($final_date_from),'F d, Y') . " - " . date_format(date_create($final_date_to),'F d, Y');


    // }

    function getTotalDebitsCashbondHistory($emp_id){
        $CI =& get_instance();
        $CI->load->model('cashbond_model');

        $totalDebits = 0;
        $select_qry = $CI->cashbond_model->get_all_employee_cashbond($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                if ($totalDebits == 0){
					$totalDebits = $value->amount_withdraw;
				}

				else {
					$totalDebits = $totalDebits + $value->amount_withdraw;
				}
            }
        }
        return $totalDebits;
    }
    function getTotalInterestEarnedCashbondHistory($emp_id){
        $CI =& get_instance();
        $CI->load->model('cashbond_model');
        $totalInterestEarned = 0;
        $select_qry = $CI->cashbond_model->get_all_employee_cashbond($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                if ($totalInterestEarned == 0){
					$totalInterestEarned = $value->interest;
				}

				else {
					$totalInterestEarned = $totalInterestEarned + $value->interest;
				}
            }
        }
        return $totalInterestEarned;
    }
    // function checkExistCashBondByEmpId(){
    //     $CI =& get_instance();
    //     $CI->load->model('cashbond_model');
    //     $CI->load->library('session');
    //     $emp_id = $CI->session->userdata('user');
    //     $totalCashbond = 0;
    //     $cashbond = $CI->cashbond_model->get_cashbond($emp_id);
    //     if(!empty($cashbond)){
    //         $totalCashbond = $cashbond['totalCashbond'];
    //     }
    //     return $totalCashbond;
    // }
    function checkExistCashBondByEmpId(){
        $CI =& get_instance();
        $CI->load->model('cashbond_model');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        $cashbond = $CI->cashbond_model->get_cashbond_num_rows($emp_id);
        return $cashbond;
    }
    function getInfoByEmpId(){
        $CI =& get_instance();
        $CI->load->model('cashbond_model');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        $cashbond = $CI->cashbond_model->get_cashbond($emp_id);
        return $cashbond;
    }
    function checkExistFileCashbondWithdrawal($emp_id){
        $CI =& get_instance();
        $CI->load->model('cashbond_model');
        $cashbond = $CI->cashbond_model->get_pending_cashbond_withdraw($emp_id);
        $count = 0;
        if(!empty($cashbond)){
            $count = count($cashbond);
        }
        return $count;
    }
    function getLastestFileCashbondWithdrawal($emp_id){
        $CI =& get_instance();
        $CI->load->model('cashbond_model');
        $latestWithdrawal = $CI->cashbond_model->get_latest_file_cashbond_withdrawal($emp_id, '0', 'dateCreated', 'DESC', 1);

        return $latestWithdrawal;
        
    }
?>
