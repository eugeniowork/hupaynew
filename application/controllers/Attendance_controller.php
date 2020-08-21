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
        $this->load->model('holiday_model','holiday_model');
        $this->load->model('leave_model','leave_model');
        $this->load->helper('hupay_helper');
        $this->load->helper('attendance_helper');
        $this->load->helper('date_helper');
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
    public function getUpdateAttendance(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $bio_id = $employeeInfo['bio_id'];
        $attendanceId = $this->input->post('attendanceId');
        
        $attendance = $this->attendance_model->get_update_attendance($attendanceId, $bio_id);
        if(!empty($attendance)){
            $time_in = explode(":",$attendance['time_in']);
            $time_out = explode(":",$attendance['time_out']);
            $hour_time_in = $time_in[0];

            $period_time_in = "AM";
            // ibig sabihin 13 and above
            if ($hour_time_in > 12){
                $hour_time_in = $hour_time_in - 12;
                $period_time_in = "PM";
            }
            $min_time_in = $time_in[1];
            $hour_time_out = $time_out[0];

            $period_time_out = "AM";
            // ibig sabihin 13 and above
            if ($hour_time_out > 12){
                $hour_time_out = $hour_time_out - 12;
                $period_time_out = "PM";
            }
            if ($hour_time_out < 10 && $hour_time_out != 0){
                $hour_time_out = "0" . $hour_time_out;
            }
    
            $min_time_out = $time_out[1];
        }
        $date = dateFormat($attendance['date']);
        $this->data['hour_time_in'] = $hour_time_in;
        $this->data['min_time_in'] = $min_time_in;
        $this->data['hour_time_out'] = $hour_time_out;
        $this->data['min_time_out'] = $min_time_out;
        $this->data['period_time_in'] = $period_time_in;
        $this->data['period_time_out'] = $period_time_out;
        $this->data['date'] = $date;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    public function updateAttendance(){
        
        //$timeIn = $this->input->post('timeIn');
        //$timeOut = $this->input->post('timeOut');
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $head_emp_id = $employeeInfo['head_emp_id'];
        $attendanceId = $this->input->post('attendanceId');

        $hourTimeIn = $this->input->post('hourTimeIn');
        $minTimeIn = $this->input->post('minTimeIn');
        $periodTimeIn = $this->input->post('periodTimeIn');
        $hourTimeOut = $this->input->post('hourTimeOut');
        $minTimeOut = $this->input->post('minTimeOut');
        $periodTimeOut = $this->input->post('periodTimeOut');
        $remarks = $this->input->post('remarks');

        if ($periodTimeIn == "PM" && $hourTimeIn != 12){
            $hourTimeIn = $hourTimeIn + 12;
        }
        $time_in = $hourTimeIn . ":" . $minTimeIn . ":00";
        if ($periodTimeOut == "PM" && $hourTimeOut != 12){
            $hourTimeOut = $hourTimeOut + 12;
        }
        $time_out = $hourTimeOut. ":" . $minTimeOut . ":00";

        $dateCreated = getDateDate();

        $attendanceRow = $this->attendance_model->get_attendance_by_id($attendanceId);
        $date = $attendanceRow['date'];
        if (($periodTimeIn != "AM" && $periodTimeIn != "PM") || ($periodTimeOut != "AM" && $periodTimeOut != "PM")){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please select AM and PM only.";
        }
        else if($time_out <= $time_in){
            $this->data['status'] = "error1";
            $this->data['msg'] = "<strong>Time out</strong> cannot be greater than or equal to <strong>Time in</strong>.";
        }
        else{
            $this->form_validation->set_rules('remarks','remarks','required');
            $this->form_validation->set_rules('hourTimeIn','hourTimeIn','required');
            $this->form_validation->set_rules('minTimeIn','minTimeIn','required');
            $this->form_validation->set_rules('periodTimeIn','periodTimeIn','required');
            $this->form_validation->set_rules('hourTimeOut','hourTimeOut','required');
            $this->form_validation->set_rules('minTimeOut','minTimeOut','required');
            $this->form_validation->set_rules('periodTimeOut','periodTimeOut','required');
            if($this->form_validation->run() == FALSE){
                $this->data['status'] = "error1";
                $this->data['msg'] = "All fields are required.";
            }
            else{
                
            
                $attendanceNotif = $this->attendance_model->get_attendance_notif($attendanceId);
                if(!empty($attendanceNotif)){
                    $notif_status = 0;
                    // ibig sabihin staff xa
                    if ($head_emp_id != 0){
                        $notif_status = 4;
                    }
                    // ibig sabihin head xa
                    else if ($head_emp_id == 0){
                        $notif_status = 0;
                    }
                    $attendanceNotifData = array(
                        'time_in'=>$time_in,
                        'time_out'=>$time_out,
                        'remarks'=>$remarks,
                        'notif_status'=>$notif_status,
                        'DateCreated'=>$dateCreated,

                    );
                    $attendanceNotifUpdate = $this->attendance_model->attendance_notif_update($attendanceNotifData, $attendanceId);
                    // mabigyan ng notifications admin,hr lang so ung role id is 2 and 1
                    $emp_id_values = explode("#",getEmpIdByNotification($emp_id));
                    $count = getEmpIdByNotificationCount($emp_id) - 1;
                    $final_attendance_date = dateFormat($date);
                    $date_create = date_create($time_in);
                    //echo $time_in;
                    $final_time_in = date_format($date_create, 'g:i A');

                    $date_create = date_create($time_out);
                    $final_time_out = date_format($date_create, 'g:i A');
                    $counter = 0;
                    do{
                        $emp_id = $emp_id_values[$counter];
                        $approver_id = $this->session->userdata('user');
                        $notifType = "Update Attendance on $final_attendance_date with time in $final_time_in and time out $final_time_out";
                        $status = "Pending";
                        $dateTime = getDateTime();
                        $attendanceNotifId = $this->attendance_model->get_attendance_notif($attendanceId);
                        $insertNotificationsData = array(
                            'attendance_notification_id'=>'',
                            'emp_id'=>$emp_id,
                            'notif_emp_id'=>$approver_id,
                            'attendance_notif_id'=>$attendanceNotifId['attendance_notif_id'],
                            'attendance_ot_id'=>'0',
                            'leave_id'=>'0',
                            'NotifType'=>$notifType,
                            'type'=>'Update Attendance',
                            'Status'=>$status,
                            'DateTime'=>$dateTime,
                            'ReadStatus'=>0,
                        );
                        $insertNotifications = $this->attendance_model->insert_notifications($insertNotificationsData);
                        $counter++;
                    }
                    while($counter  <= $count);
                }
                else{
                    $notif_status = 0;
                    // ibig sabihin staff xa
                    if ($head_emp_id != 0){
                        $notif_status = 4;
                    }

                    // ibig sabihin head xa
                    else if ($head_emp_id == 0){
                        $notif_status = 0;
                    }
                    $insertAttendanceNotificationsData = array(
                        'attendance_notif_id'=>'',
                        'emp_id'=>$emp_id,
                        'head_emp_id'=>$head_emp_id,
                        'attendance_id'=>$attendanceId,
                        'date'=>$date,
                        'time_in'=>$time_in,
                        'time_out'=>$time_out,
                        'remarks'=>$remarks,
                        'notif_status'=>$notif_status,
                        'DateCreated'=>$dateCreated,
                    );
                    $insertAttendanceNotifications = $this->attendance_model->insert_attendance_notif($insertAttendanceNotificationsData);

                    $emp_id_values = explode("#",getEmpIdByNotification($emp_id));
                    $count = getEmpIdByNotificationCount($emp_id) - 1;
                    $final_attendance_date = dateFormat($date);
                    $date_create = date_create($time_in);
                    //echo $time_in;
                    $final_time_in = date_format($date_create, 'g:i A');

                    $date_create = date_create($time_out);
                    $final_time_out = date_format($date_create, 'g:i A');
                    $counter = 0;
                    do{
                        $emp_id = $emp_id_values[$counter];
                        $approver_id = $this->session->userdata('user');
                        $notifType = "Update Attendance on $final_attendance_date with time in $final_time_in and time out $final_time_out";
                        $status = "Pending";
                        $dateTime = getDateTime();
                        $attendanceNotifId = $this->attendance_model->get_attendance_notif($attendanceId);
                        $insertNotificationsData = array(
                            'attendance_notification_id'=>'',
                            'emp_id'=>$emp_id,
                            'notif_emp_id'=>$approver_id,
                            'attendance_notif_id'=>$attendanceNotifId['attendance_notif_id'],
                            'attendance_ot_id'=>'0',
                            'leave_id'=>'0',
                            'NotifType'=>$notifType,
                            'type'=>'Update Attendance',
                            'Status'=>$status,
                            'DateTime'=>$dateTime,
                            'ReadStatus'=>0,

                            
                        );
                        $insertNotifications = $this->attendance_model->insert_notifications($insertNotificationsData);
                        $counter++;
                    }
                    while($count <= $count);
                }
                $this->data['status'] = "success";
            }
        }
        
        echo json_encode($this->data);
    }


    public function getCutOffAttendance(){
        $searchOption = $this->input->post('searchOption');
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);

        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        $select_cutoff_qry = $this->attendance_model->get_cut_off();
        $attendanceFinal = array();
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
        $dates = array();
        $from = strtotime($final_date_from);
        $last = strtotime($final_date_to);
        $output_format = 'Y-m-d';
        $step = '+1 day';

        $count = 0;
        while( $from <= $last ) {

            $count++;
            $dates[] = date($output_format, $from);
            $from = strtotime($step, $from);
            
        }

        $count = $count- 1;
        $weekdays = array();
        $counter = 0;
        $weekdays_count = 0;

        do{
            $date_create = date_create($dates[$counter]);
            $day = date_format($date_create, 'l');
            if ($day != "Saturday" && $day != "Sunday"){
                $weekdays[] = $dates[$counter];
                $date =  $dates[$counter];    		
                $weekdays_count++;
                $existAttendance = $this->attendance_model->attendance_info($employeeInfo['bio_id'], $date);
                if ($existAttendance != 0){
                    $cutOff_dateFrom =  date_format(date_create($final_date_from), 'F d, Y');
                    $cutOff_dateTo =  date_format(date_create($final_date_to), 'F d, Y');

                    $cutOffPeriod = $cutOff_dateFrom . " - " . $cutOff_dateTo;
                    $num_rows = $this->payroll_model->get_payroll_info($cutOffPeriod);

                    $select_qry = $this->attendance_model->attendance_info_all_object_type($employeeInfo['bio_id'], $date);

                    if(!empty($select_qry)){
                        $date_create = date_create($select_qry['date']);
                        $date_format = date_format($date_create, 'F d, Y') . " - <u><i>" .date_format($date_create, 'D') . "</i></u>";
                        $timeFrom = date_format(date_create($select_qry['time_in']), 'g:i A');
                        $timeTo = date_format(date_create($select_qry['time_out']), 'g:i A');
                        if ($select_qry['time_out'] == "00:00:00"){
                            $timeTo = "-";
                        }
    
                        if ($select_qry['time_in'] == "00:00:00"){
                            $timeFrom = "-";
                        }
                        array_push($attendanceFinal, array(
                            'attendance_id'=>$select_qry['attendance_id'],
                            'date_format'=>$date_format,
                            'timeFrom'=>$timeFrom,
                            'timeTo'=>$timeTo,
                        ));
                        
                    }
                }
                else{
                    $date_format = date_format(date_create($date), 'F d, Y') . " - <u><i>" .date_format($date_create, 'D') . "</i></u>";
                    $holiday = date_format(date_create($date), 'F j');
                    $holiday_num_rows = $this->holiday_model->get_holiday_date_rows($holiday);
                    if($holiday_num_rows == 0){
                        //$row_emp = $this->employee_model->get_employee_by_bio_id_data($employeeInfo['bio_id']);

                        $leave_num_rows = $this->leave_model->get_leave_info($id,$date);
                        if(empty($leave_num_rows)){
                            array_push($attendanceFinal, array(
                                'attendance_id'=>'',
                                'date_format'=>$date_format,
                                'timeFrom'=>'',
                                'timeTo'=>'',
                            ));
                        }
                        else{
                            foreach($leave_num_rows as $value){
                                array_push($attendanceFinal, array(
                                    'date_form'=>$date_format,
                                    'FileLeaveType'=>$value->FileLeaveType,
                                    'LeaveType'=>$value->LeaveType,
                                ));
                            }
                        }
                    }
                    else{
                        $select_holiday_qry = $this->holiday_model->get_holiday_date_all($holiday);
                        if(!empty($select_holiday_qry)){
                            foreach($select_holiday_qry as $value){
                                array_push($attendanceFinal, array(
                                    'date_format'=>$date_format,
                                    'holiday_type'=>$value->holiday_type,
                                    'holiday_value'=>$value->holiday_value,
                                ));
                            }
                        }
                    }
                }
            }
            $counter++;
        }
        while($counter <= $count);

        $this->data['status'] = "success";
        $this->data['attendanceFinal'] = $attendanceFinal;
        echo json_encode($this->data);
    }

    public function getSpecificDateAttendance(){
        $dateFrom = $this->input->post('dateFrom');
        $dateTo = $this->input->post('dateTo');
        $dateFrom = dateDefaultDb($dateFrom);
        $dateTo = dateDefaultDb($dateTo);
        
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
}