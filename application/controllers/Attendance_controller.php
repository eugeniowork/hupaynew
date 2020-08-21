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
        $this->load->model('working_hours_model','working_hours_model');
        $this->load->model('working_days_model','working_days_model');
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
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $bio_id = $employeeInfo['bio_id'];
        $dateFrom = $this->input->post('dateFrom');
        $dateTo = $this->input->post('dateTo');
        $dateFrom = dateDefaultDb($dateFrom);
        $dateTo = dateDefaultDb($dateTo);
        
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
        $select_qry = $this->attendance_model->get_attendance_between_date($dateFrom, $dateTo, $bio_id);
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');

                $timeFrom = date_format(date_create($value->time_in), 'g:i A');
                $timeTo = date_format(date_create($value->time_out), 'g:i A');
                if ($value->time_out == "00:00:00"){
                    $timeTo = "-";
                }
                if ($value->time_in == "00:00:00"){
                    $timeFrom = "-";
                }
                $cutOff_dateFrom =  date_format(date_create($final_date_from), 'F d, Y');

                $cutOff_dateTo =  date_format(date_create($final_date_to), 'F d, Y');

                $cutOffPeriod = $cutOff_dateFrom . " - " . $cutOff_dateTo;
                $num_rows = $this->payroll_model->get_payroll_info($cutOffPeriod);

                array_push($attendanceFinal, array(
                    'attendance_id'=>$value->attendance_id,
                    'date_format'=>$date_format,
                    'timeFrom'=>$timeFrom,
                    'timeTo'=>$timeTo,
                ));
            }
        }
        $this->data['attendanceFinal'] = $attendanceFinal;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function addOt(){
        
        $attendanceDateOt = $this->input->post('attendanceDateOt');
        $hourTimeOutOt = $this->input->post('hourTimeOutOt');
        $minTimeOutOt = $this->input->post('minTimeOutOt');
        $periodTimeOutOt = $this->input->post('periodTimeOutOt');
        
        $hourTimeInOt = $this->input->post('hourTimeInOt');
        $minTimeInOt = $this->input->post('minTimeInOt');
        $periodTimeInOt = $this->input->post('periodTimeInOt');

        $remarksOt = $this->input->post('remarksOt');

        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $row_wd = $this->working_days_model->get_working_days_info($employeeInfo['working_days_id']);

        $day_from = $row_wd['day_from'];
        $day_to = $row_wd['day_to'];
        
        $head_emp_id = $employeeInfo['head_emp_id'];
        $this->form_validation->set_rules('attendanceDateOt', 'attendanceDateOt', 'required');
        $this->form_validation->set_rules('hourTimeOutOt', 'hourTimeOutOt', 'required');
        $this->form_validation->set_rules('minTimeOutOt', 'minTimeOutOt', 'required');
        $this->form_validation->set_rules('periodTimeOutOt', 'periodTimeOutOt', 'required');
        $this->form_validation->set_rules('remarksOt', 'remarksOt', 'required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required.";
        }
        else{
            $row_working_hours = $this->working_hours_model->get_info_working_hours($employeeInfo['working_hours_id']);
            $working_hours_time_out = $row_working_hours['timeTo'];

            $date_ot_attendance = date_create($attendanceDateOt);
            $day = date_format($date_ot_attendance, 'l');
            $day_of_the_week = date_format($date_ot_attendance, 'w'); // 

            $day_month = date_format($date_ot_attendance, 'j');
            $month = date_format($date_ot_attendance, 'F');
            $holiday_date = $month." ".$day_month;
            $holiday = $this->holiday_model->get_holiday_date($holiday_date);
            $otType = "";
            if(!empty($holiday)){
                $holidayType = $holiday['holiday_type'];
                if ($holidayType != "Regular Holiday") {
                    $holidayType = "Special Holiday";
                }
                if ($day_of_the_week >= $day_from && $day_of_the_week <= $day_to){
                    $otType = $holidayType;
                }
                else {
                    $otType = 'Restday / '.$holidayType;
                }
            }
            else{
                if ($day_of_the_week >= $day_from && $day_of_the_week <= $day_to){
                    $otType = "Regular";
                }
        
                else {
                    $otType = "Restday";
                }
            }
            $this->form_validation->set_rules('hourTimeInOt', 'hourTimeOutOt', 'required');
            $this->form_validation->set_rules('minTimeInOt', 'minTimeOutOt', 'required');
            $this->form_validation->set_rules('periodTimeInOt', 'periodTimeInOt', 'required');
            $proceed = true;
            if($this->form_validation->run() == FALSE){
                $proceed = false;
            }

            if ($otType != "Regular" && $proceed) {
                // time in
                $hour_time_in = $hourTimeInOt;
                if ($hour_time_in < 10 && strlen($hour_time_in) == 1){
                    $hour_time_in = "0" . $hour_time_in;
                }
                $min_time_in = $minTimeInOt;
                //echo $min_time_in . "<br/>";
                if ($min_time_in < 10 && strlen($min_time_in) == 1){
                    $min_time_in = "0" . $min_time_in;
                }
                //$sec_time_in = $_POST["sec_time_in"];
                $period_time_in = $periodTimeInOt;
                if ($period_time_in == "PM" && $hour_time_in != 12){
                    $hour_time_in = $hour_time_in + 12;
                }
        
                $time_from = $hour_time_in . ":" . $min_time_in . ":" . "00";
            }
            else{
                $period_time_in = "PM"; 
		        $time_from = $working_hours_time_out;
            }
            $hour_time_out = $hourTimeOutOt;
            if ($hour_time_out < 10 && strlen($hour_time_out) == 1){
                $hour_time_out = "0" . $hour_time_out;
            }
            $min_time_out = $minTimeOutOt;
            if ($min_time_out < 10 && strlen($min_time_out) == 1){
                $min_time_out = "0" . $min_time_out;
            }
            
            $period_time_out = $periodTimeOutOt;
            if ($period_time_out == "PM" && $hour_time_out != 12){
                $hour_time_out = $hour_time_out + 12;
            }
            $time_out = $hour_time_out . ":" . $min_time_out . ":" . "00";

            $type_ot = $otType;
            
            $remarks = $remarksOt;

            $current_date = getDateDate();
            $attendance_date_ot_month = substr($attendanceDateOt,0,2);
	        $attendance_date_ot_day = substr(substr($attendanceDateOt, -7), 0,2);
            $attendance_date_ot_year = substr($attendanceDateOt, -4);
            
            //echo $time_from." ".$time_out;

            if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$attendanceDateOt)) {
                $this->data['status'] = "error";
                $this->data['msg'] = "<strong>OT Date</strong> not match to the current format mm/dd/yyyy.";
                
            }
            else if($attendance_date_ot_year % 4 == 0 && $attendance_date_ot_month == 2 && $attendance_date_ot_day >= 30){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid overtime date.";
            }
            else if ($attendance_date_ot_year % 4 != 0 && $attendance_date_ot_month == 2 && $attendance_date_ot_day >= 29){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid overtime date.";
            }
            else if (($attendance_date_ot_month == 4 || $attendance_date_ot_month == 6 || $attendance_date_ot_month == 9 || $attendance_date_ot_month == 11)
                && $attendance_date_ot_day  >= 31){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid overtime date.";
            }
            else if (($period_time_in != "AM" && $period_time_in != "PM") || ($period_time_out != "AM" && $period_time_out != "PM")){
		        $this->data['status'] = "error";
                $this->data['msg'] = "Please select AM and PM only.";
            }
            else if ($time_out <= $time_from){
                $this->data['status'] = "error";
                $this->data['msg'] = "<strong>Time out</strong> cannot be greater than or equal to <strong>Time in</strong>.";
            }
            else{
                $attendance_date = dateDefaultDb($attendanceDateOt);
                $overTime = $this->attendance_model->get_attendance_overtime($emp_id, $attendance_date);
                if(!empty($overTime)){
                    $approve_stat = 0;
                    // ibig sabihin staff xa
                    if ($head_emp_id != 0){
                        $approve_stat = 4;
                    }

                    // ibig sabihin head xa
                    else if ($head_emp_id == 0){
                        $approve_stat = 0;
                    }
                    $updateAttendanceOvertimeData = array(
                        'time_from'=>$time_from,
                        'time_out'=>$time_out,
                        'type_ot'=>$type_ot,
                        'remarks'=>$remarks,
                        'approve_stat'=>$approve_stat,
                        'DateCreated'=>$current_date,
                        'head_emp_id'=>$head_emp_id,
                    );
                    $updateAttendanceOvertime = $this->attendance_model->update_attendance_overtime($emp_id, $attendance_date,$updateAttendanceOvertimeData);
                }
                else{
                    $approve_stat = 0;
                    // ibig sabihin staff xa
                    if ($head_emp_id != 0){
                        $approve_stat = 4;
                    }

                    // ibig sabihin head xa
                    else if ($head_emp_id == 0){
                        $approve_stat = 0;
                    }
                    $insertAttendanceOvertimeData = array(
                        'attendance_ot_id'=>'',
                        'time_from'=>$time_from,
                        'time_out'=>$time_out,
                        'type_ot'=>$type_ot,
                        'remarks'=>$remarks,
                        'approve_stat'=>$approve_stat,
                        'DateCreated'=>$current_date,
                        'head_emp_id'=>$head_emp_id,
                        'emp_id'=>$emp_id,
                        'date'=>$attendance_date,
                    );
                    $insertAttendanceOvertime = $this->attendance_model->insert_attendance_overtime($insertAttendanceOvertimeData);
                }

                $emp_id_values = explode("#",getEmpIdByNotification($emp_id));
                $count = getEmpIdByNotificationCount($emp_id) - 1;

                $final_attendance_date = dateFormat($attendance_date);
                $date_create = date_create($time_from);
                $final_time_from = date_format($date_create, 'g:i A');

                $date_create = date_create($time_out);
                $final_time_out = date_format($date_create, 'g:i A');

                $counter = 0;
                do{
                    $emp_id = $emp_id_values[$counter];
                    $createNotifEmpId = $emp_id;
                    $notifType = "File Overtime on $final_attendance_date from $final_time_from and time out $final_time_out";
                    $status = "Pending";
                    $dateTime = getDateTime();
                    $attendanceOtId = $this->attendance_model->attendance_ot_last_id();
                    foreach($attendanceOtId as $value){
                        $insertNotificationsData = array(
                            'attendance_notification_id'=>'',
                            'emp_id'=>$emp_id,
                            'notif_emp_id'=>$createNotifEmpId,
                            'attendance_notif_id'=>'0',
                            'attendance_ot_id'=>$value->attendance_ot_id,
                            'leave_id'=>'0',
                            'NotifType'=>$notifType,
                            'type'=>'Attendance OT',
                            'Status'=>$status,
                            'DateTime'=>$dateTime,
                            'ReadStatus'=>0,
                        );
                        
                    }
                    $insertOt = $this->attendance_model->insert_notifications($insertNotificationsData);
                    $counter++;
                }
                while($counter <= $count);
                $this->data['status'] = "success";
                
            }
        }
        
        echo json_encode($this->data);
    }
    public function addAttendance(){
        $addAttendanceDate = $this->input->post('addAttendanceDate');
        $hourTimeOutAttendance = $this->input->post('hourTimeOutAttendance');
        $minTimeOutOtAttendance = $this->input->post('minTimeOutOtAttendance');
        $periodTimeOutAttendance = $this->input->post('periodTimeOutAttendance');
        
        $hourTimeInAttendance = $this->input->post('hourTimeInAttendance');
        $minTimeInAttendance = $this->input->post('minTimeInAttendance');
        $periodTimeInAttendance = $this->input->post('periodTimeInAttendance');

        $remarksAttendance = $this->input->post('remarksAttendance');

        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $head_emp_id = $employeeInfo['head_emp_id'];
        $biod_id = $employeeInfo['bio_id'];
        
        $hour_time_in = $hourTimeInAttendance;
        if ($hour_time_in < 10){
            $hour_time_in = "0" . $hour_time_in;
        }
    
        $min_time_in = $minTimeInAttendance;
    
        if ($min_time_in < 10){
            $min_time_in = "0" . $min_time_in;
        }

        $period_time_in = $periodTimeInAttendance;
        if ($period_time_in == "PM" && $hour_time_in != 12){
            $hour_time_in = $hour_time_in + 12;
        }

        $time_in = $hour_time_in . ":" . $min_time_in . ":" . "00";

        // time out
        $hour_time_out = $hourTimeOutAttendance;

        if ($hour_time_out < 10){
            $hour_time_out = "0" . $hour_time_out;
        }

        $min_time_out = $minTimeOutOtAttendance;

        if ($min_time_out < 10){
            $min_time_out = "0" . $min_time_out;
        }
        //$sec_time_out = $_POST["sec_time_out"];
        $period_time_out = $periodTimeOutAttendance;
        if ($period_time_out == "PM" && $hour_time_out != 12){
            $hour_time_out = $hour_time_out + 12;
        }
        $time_out = $hour_time_out . ":" . $min_time_out . ":" . "00";

        $remarks = $remarksAttendance;

        $current_date = getDateDate();

        $attendance_date_month = substr($addAttendanceDate,0,2);
        $attendance_date_day = substr(substr($addAttendanceDate, -7), 0,2);
        $attendance_date_year = substr($addAttendanceDate, -4);

        if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$addAttendanceDate)) {
            $this->data['status'] = "error";
            $this->data['msg'] = "<strong>Attendance Date</strong> not match to the current format mm/dd/yyyy.";
        }
        else if ($attendance_date_year % 4 == 0 && $attendance_date_month == 2 && $attendance_date_day >= 30){
            $this->data['status'] = "error";
            $this->data['msg'] = "Invalid attendance date.";
        }
        else if ($attendance_date_year % 4 != 0 && $attendance_date_month == 2 && $attendance_date_day >= 29){
            $this->data['status'] = "error";
            $this->data['msg'] = "Invalid attendance date.";
        }
        else if (($attendance_date_month == 4 || $attendance_date_month == 6 || $attendance_date_month == 9 || $attendance_date_month == 11)
			&& $attendance_date_day  >= 31){
            $this->data['status'] = "error";
            $this->data['msg'] = "Invalid attendance date.";
        }
        else if ($attendance_class->getRowsTimeInOut(dateDefaultDb($addAttendanceDate),$biod_id) != 0){
            $this->data['status'] = "error";
            $this->data['msg'] = "The date <strong>".$addAttendanceDate."</strong> is already exist in your attendance list";
        }
        else if (($period_time_in != "AM" && $period_time_in != "PM") || ($period_time_out != "AM" && $period_time_out != "PM")){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please select AM and PM only.";
        }
        else if ($time_out <= $time_in && $time_out != "000:000:00"){
            $this->data['status'] = "error";
            $this->data['msg'] = "<strong>Time out</strong> cannot be greater than or equal to <strong>Time in</strong>.";
        }
        else{
            $attendance_date = dateDefaultDb($addAttendanceDate);
            $get_attendance_if_exist = $this->attendance_model->get_attendance_if_exist($emp_id,$attendance_date);
            if(!empty($get_attendance_if_exist)){
                $attendance_notif_id = $get_attendance_if_exist['attendance_notif_id'];
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
                    'DateCreated'=>$current_date,

                );
                $attendanceNotifUpdate = $this->attendance_model->attendance_notif_update($attendanceNotifData, $attendanceId);

                $emp_id_values = explode("#",getEmpIdByNotification($emp_id));

                $count = getEmpIdByNotificationCount($emp_id) - 1;

                $final_attendance_date = dateFormat($attendance_date);

                $date_create = date_create($time_in);
                $final_time_in = date_format($date_create, 'g:i A');

                $date_create = date_create($time_out);
                $final_time_out = date_format($date_create, 'g:i A');
                $counter = 0;
                do{
                    $emp_id = $emp_id_values[$counter];
                    $approver_id = $emp_id;
                    $notifType = "Add Attendance on $final_attendance_date with time in $final_time_in and time out $final_time_out";
                    $status = "Pending";
                    $dateTime = getDateTime();
                    $insertNotificationsData = array(
                        'attendance_notification_id'=>'',
                        'emp_id'=>$emp_id,
                        'notif_emp_id'=>$approver_id,
                        'attendance_notif_id'=>$attendance_notif_id,
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
            }
        }
    }
}