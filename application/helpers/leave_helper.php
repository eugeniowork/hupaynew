<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    function leaveValidation($lv_id,$date_from,$date_to,$no_days_to_file,$emp_id){
        $CI =& get_instance();
        $CI->load->model('leave_model');
        $date_today = date("Y-m-d");
        $checkExistPetInfoByEmpId = $CI->leave_model->get_pet_info($emp_id);
        $message = "";
		if ($lv_id == 1){

			$date_to_file = date('Y-m-d', strtotime('-'.$no_days_to_file.' day', strtotime($date_from)));

			if ($date_today <= $date_to_file){ // 2020-02-10 <= 2020-01-11
				//$can_file = true;
			}

			else {
				$message = "Must File Before <b>".$no_days_to_file."</b> days and above";
			}

		}
        else if ($lv_id == 2){

			$date_to_file = date('Y-m-d', strtotime('+'.$no_days_to_file.' day', strtotime($date_to))); // 

			if ($date_today <= $date_to_file){ // 2020-02-17 <= 2020-02-16
				//$can_file = true;
			}

			else {
				$message = "Must File After <b>".$no_days_to_file."</b> days and below";
			}

        }
        else if ($lv_id == 3){

			if(empty($checkExistPetInfoByEmpId)){
                $message = "There is no registed pet in the system";
            }
        }
        else if ($lv_id == 4){
			//$can_file = true;
        }
        return $message;
    }
    function getEmpLeaveCountByEmpIdLtId($emp_id, $lt_id){
        $CI =& get_instance();
        $CI->load->model('leave_model');
        $remaining_leave = 0;

        $select_qry = $CI->leave_model->get_employee_leave($emp_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $leave_array_explode =explode("," ,$value->leave_array);
                $leave_count_array_explode =explode("," ,$value->leave_count_array);

                $counter = 0;
                $count = count($leave_array_explode);
				do{
					if ($leave_array_explode[$counter] == $lt_id){
						$remaining_leave = $leave_count_array_explode[$counter];
					}
					$counter++;
                }
                while($count > $counter);
            }
        }
        return $remaining_leave;
        
        
    }

?>