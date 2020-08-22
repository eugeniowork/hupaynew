<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    function getEmpIdAllActiveEmp(){
        $CI =& get_instance();
        $CI->load->model('employee_model');

        $all_emp_id = "";
        $select_qry = $CI->employee_model->get_active_employee();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                if ($all_emp_id == "") {
					$all_emp_id = $value->emp_id;
				}

				else {
					$all_emp_id = $all_emp_id . "#" . $value->emp_id;
				}
            }
        }
        return $all_emp_id;
    }
?>