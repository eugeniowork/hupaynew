<?php 
    function addYTDcurrentYear($cutOffPeriod){
        $CI =& get_instance();
        $CI->load->model('payroll_model');
        $CI->load->model('deduction_model');
        $dateCreated = date("Y-m-d");
        $select_qry = $CI->payroll_model->get_all_payroll_info($cutOffPeriod);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $year = date_format(date_create($value->DateCreated), 'Y');
                $exist_year = $CI->deduction_model->get_total_year_deduction_by_year($year);
                if(!empty($exist_year)){
                    $ytd_Gross = $exist_year['ytd_Gross'];
                    $ytd_Allowance = $exist_year['ytd_Allowance'];
                    $ytd_Tax = $exist_year['ytd_Tax'];
                    $update_qryData = array(
                        'ytd_Gross'=>$ytd_Gross,
                        'ytd_Allowance'=>$ytd_Allowance,
                        'ytd_Tax'=>$ytd_Tax
                    );
                    $update_qry = $CI->deduction_model->update_total_year_deduction_data($value->emp_id, $year, $update_qryData);
                }
                else{
                    $insert_qryData = array(
                        'simkimban_id'=>$value->simkimban_id,
                        'remainingBalance'=>$remainingBalance,
                        'deduction'=>$value->deduction,
                        'CutOffPeriod'=>$cutOffPeriod,
                        'date_payroll'=>$date_payroll,
                        'dateCreated'=>$current_date_time,
                    );
                    $insert_qry = $CI->deduction_model->insert_total_year_deduction_data($insert_qryData);

                }
            }
        }
        return 'success';
        

    }
?>