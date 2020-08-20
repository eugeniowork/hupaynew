<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("employee_model", 'employee_model');
        $this->load->model("attendance_model", "attendance_model");
        $this->load->model("payroll_model", "payroll_model");
        $this->load->model("attendance_model", "attendance_model");
        //$this->load->library('../controllers/holiday_controller');

    }
    public function index(){
        $this->data['pageTitle'] = 'Attendance';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('attendance/view_attendance');
        $this->load->view('global/footer');
    }

    public function getAllAttendance(){
        $searchOption = $this->input->post('searchOption');
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);


        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        $select_cutoff_qry = $this->attendance_model->get_cut_off();
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
        $cutOff_dateFrom =  date_format(date_create($final_date_from), 'F d, Y');
		$cutOff_dateTo =  date_format(date_create($final_date_to), 'F d, Y');
        $cutOffPeriod = $cutOff_dateFrom . " - " . $cutOff_dateTo;
        $num_rows = $this->payroll_model->get_payroll_info($cutOffPeriod);
        $select_qry = $this->attendance_model->attendance_info_all($employeeInfo['bio_id']);
        $attendanceFinal = array();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');

                $attendance_date = date_format(date_create($value->date),"Y-m-d");
                $timeFrom = date_format(date_create($value->time_in), 'g:i A');
                $timeTo = date_format(date_create($value->time_out), 'g:i A');
                if ($value->time_out == "00:00:00"){
                    $timeTo = "-";
                }

                if ($value->time_in == "00:00:00"){
                    $timeFrom = "-";
                }
                array_push($attendanceFinal, array(
                    'attendance_id'=>$value->attendance_id,
                    'date_format'=>$date_format,
                    'timeFrom'=>$timeFrom,
                    'timeTo'=>$timeTo,
                ));
            }
        }
        $this->data['status'] = "success";
        $this->data['attendanceFinal'] = $attendanceFinal;
        echo json_encode($this->data);
    }
}