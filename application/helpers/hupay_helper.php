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

?>