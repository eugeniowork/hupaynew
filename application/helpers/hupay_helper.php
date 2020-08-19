<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');

    function employeeInformation(){
        $CI =& get_instance();
        $CI->load->model('employee_model');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        //$name = $sessionData['user_data']['firstname'].' '.$sessionData['user_data']['lastname'];
        // $data = $sessionData['user_data'];
        $employeeInformation = $CI->employee_model->employee_information($emp_id);

        return $employeeInformation;
    }
    function unreadMemoNotif(){
        $CI =& get_instance();
        $CI->load->model('header_model');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        //$name = $sessionData['user_data']['firstname'].' '.$sessionData['user_data']['lastname'];
        // $data = $sessionData['user_data'];
        //$emp_id = $sessionData['user']['emp_id'];
        $unreadMemo = $CI->header_model->unread_memo_notif($emp_id);

        return $unreadMemo;
    }
    function unreadPayrollNotif(){
        $CI =& get_instance();
        $CI->load->model('header_model');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        //$emp_id = $sessionData['user']['emp_id'];
        $unreadPayroll = $CI->header_model->unread_payroll_notif($emp_id);

        return $unreadPayroll;
    }
    function unreadEventsNotif(){
        $CI =& get_instance();
        $CI->load->model('header_model');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        //$emp_id = $sessionData['user']['emp_id'];
        $unreadEvents = $CI->header_model->unread_events_notif($emp_id);

        return $unreadEvents;
    }
    function unreadAttendanceNotif(){
        $CI =& get_instance();
        $CI->load->model('header_model');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        //$emp_id = $sessionData['user']['emp_id'];
        $unreadAttendance = $CI->header_model->unread_attendance_notif($emp_id);

        return $unreadAttendance;
    }
    function getEmployeePosition(){
        $CI =& get_instance();
        $CI->load->model('position_model');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        //$emp_id = $sessionData['user']['position_id'];
        $employeeInformation = $CI->employee_model->employee_information($emp_id);
        $position = $CI->position_model->get_employee_position($employeeInformation['position_id']);

        return $position;
    }

    function moneyConvertion($value){
        $ci =& get_instance();
        $ci->load->helper('money_convertion_helper');
        if ($value < 0){
            $final_value = $value;
        }
        

        else if ($value > 0 && $value < 1){
            $final_value = $value;
        }
        else if ($value == 0) { // if 0       
            
            $final_value = "0.00";                   
        }


        else if ($value >= 1 && $value < 10) { // for 1 digit
          
            $decimal = "";

            $one = substr($value,0,1);

            if (is_decimal($value) == 1) {
                $decimal = substr($value,1);
                $final_value = $one . $decimal;
            }

            else {
                $final_value = $one . ".00";
            }

            
        }

        else if ($value >= 10 && $value < 100) { // for 2 digits 
          
            $decimal = "";
            $ten = substr($value,0,1);
            $one = substr(substr($value,1),0,1);

            if (is_decima($value) == 1) {
                $decimal = substr($value,2);
                $final_value = $ten . $one . $decimal;
            }
            else {
                $final_value = $ten . $one . ".00";
            }

            
        }


        else if ($value >= 100 && $value < 1000) { // for 3 digits 
          
            $decimal = "";
            $hundred = substr($value,0,1);
            $ten = substr(substr($value,1),0,1);
            $one = substr(substr($value,2),0,1);

            if (is_decimal($value) == 1) {
                $decimal = substr($value,3);
                $final_value = $hundred . $ten . $one . $decimal;
            }

            else {
                 $final_value =  $hundred . $ten . $one . ".00";
            }

           
        }


        else if ($value >= 1000 && $value < 10000) { // for 4 digits 
          
            $decimal = "";
            $thousand = substr($value,0,1);
            $hundred = substr(substr($value,1),0,1);
            $ten = substr(substr($value,2),0,1);
            $one = substr(substr($value,3),0,1);

            if (is_decimal($value) == 1) {
                $decimal = substr($value,4);
                $final_value = $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
                $final_value = $thousand . "," . $hundred . $ten . $one . ".00";
            }

           
        }

        else if ($value >= 10000 && $value < 100000) { // for 5 digits
            $ten_thousand = substr($value,0,1);
            $thousand = substr(substr($value,1),0,1);
            $hundred = substr(substr($value,2),0,1);
            $ten = substr(substr($value,3),0,1);
            $one = substr(substr($value,4),0,1);

            $decimal = "";
             if (is_decimal($value) == 1) {
                $decimal = substr($value,5);
                $final_value = $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
                $final_value = $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . ".00";
            }

           
           
        }

        else if ($value>= 100000 && $value < 1000000) { // 6 digits
            $hundred_thousand = substr($value,0,1);
            $ten_thousand = substr(substr($value,1),0,1);
            $thousand = substr(substr($value,2),0,1);
            $hundred = substr(substr($value,3),0,1);
            $ten = substr(substr($value,4),0,1);
            $one = substr(substr($value,5),0,1);

            $decimal = "";
             if (is_decimal($value) == 1) {
                $decimal = substr($value,6);
                $final_value = $hundred_thousand. $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
                $final_value =  $hundred_thousand. $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . ".00";
            }
        }

        return $final_value;
    }
    
?>