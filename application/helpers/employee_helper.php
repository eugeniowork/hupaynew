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

    function checkExistIncreaseCutOff($emp_id){
        $CI =& get_instance();
        $CI->load->model('salary_model');

        $num_rows = $CI->salary_model->get_increate_cut_off_on_employee($emp_id);
        $exist = 0;
        if(!empty($num_rows)){
            $row = $num_rows;
            $date_increase = date_format(date_create($row['date_increase']), 'Y-m-d');

			date_default_timezone_set("Asia/Manila");
			//$date = date_create("1/1/1990");

			$dates = date("Y-m-d H:i:s");
			$date = date_create($dates);
			//date_sub($date, date_interval_create_from_date_string('15 hours'));

			// $current_date_time = date_format($date, 'Y-m-d H:i:s');
			$current_date_time = date_format($date, 'Y-m-d');

			//echo $current_date_time;
            $year = date("Y");
            $cutOff = $CI->salary_model->get_cut_off();
            if(!empty($cutOff)){
                foreach($cutOff as $value){
                    $date_from = date_format(date_create($value->dateFrom.", ".$year),'Y-m-d');
                    if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
                        $prev_year = $year - 1;
                        $date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
    
                    }
                    $date_from = date_format(date_create($date_from),"Y-m-d");
                    $date_to = date_format(date_create($value->dateTo.", ".$year),'Y-m-d');
                    $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
    
                    
                    if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                        $final_date_from = $date_from;
                        $final_date_to = $date_to;
                        $date_payroll = date_format(date_create($value->datePayroll.", ".$year),'Y-m-d');
                    }
                }
            }
            if ($final_date_from <= $date_increase && $final_date_to >= $date_increase){
				$exist = 1;
			}
        }
        return $exist;
    }

    function getEmpIdRoleAdmin(){
        $CI =& get_instance();
        $CI->load->model('employee_model');

        $emp_id_values = "";
        $count = "";
        $select_qry = $CI->employee_model->get_active_admin();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                if ($emp_id_values == ""){
					$emp_id_values = $value->emp_id;
				}

				else {
					$emp_id_values = $emp_id_values . "#" . $value->emp_id;
				}

				$count++;
            }
        }
        return $emp_id_values;
    }
    function getAllEmployeesNameToTable(){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $finalData = "";
        $select_qry = $CI->employee_model->get_all_employee();
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                if (($value->role_id != 1 || $value->dept_id != 1) && $value->ActiveStatus == 1) {
                    $finalData .= "<tr id='".$value->emp_id."' style='text-align:center;'>";
                        $finalData .= "<td><a href='#' id='chooseEmployee' title='Choose ".$value->Lastname .", " . $value->Firstname . " " . $value->Middlename."'>" . $value->Lastname .", " . $value->Firstname . " " . $value->Middlename . "</a></td>";
                    $finalData .= "</tr>";
                }
            }
        }

        return $finalData;
    }
    function checkExistEmployeeName($emp_name){
        $CI =& get_instance();
        $CI->load->model('employee_model');

        $has_error = 1;
        $select_qry = $CI->employee_model->get_active_employee_row_array();
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $name = $value->Lastname . ", " . $value->Firstname . " " . $value->Middlename;
                if ($value->Middlename == ""){
                    $name = $value->Lastname . ", " . $value->Firstname;
                }

                if ($emp_name == $name){
                    $has_error = 0;
                }
            }
        }
        return $has_error;
    }
    function checkMessageSelf($emp_id,$emp_name){
        
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $existData = 0;
        $select_qry = $CI->employee_model->get_all_employee();
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $emp_id_db = $value->emp_id;
                $emp_name_db = $value->Lastname . ", " . $value->Firstname . " " . $value->Middlename;

                if ($value->Middlename == ""){
                    $emp_name_db = $value->Lastname . ", " . $value->Firstname;
                }
                
                if ($emp_id == $emp_id_db && $emp_name == $emp_name_db){
                    $existData = 1;
                }
            }
        }

        return $existData;
    }
    function getEmpIdByEmployeeName($emp_name){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $emp_id = 0;
        $select_qry = $CI->employee_model->get_active_employee_row_array();
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $name = $value->Lastname . ", " . $value->Firstname . " " . $value->Middlename;
                if ($value->Middlename == ""){
                    $name = $value->Lastname . ", " . $value->Firstname;
                }

                if ($emp_name == $name){
                    $emp_id = $value->emp_id;
                }
            }
        }

        return $emp_id;
    }
?>