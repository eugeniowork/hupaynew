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
?>