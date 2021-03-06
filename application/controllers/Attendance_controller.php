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
        $this->load->model("audit_trial_model", "audit_trial_model");
        $this->load->model('holiday_model','holiday_model');
        $this->load->model('leave_model','leave_model');
        $this->load->model('working_hours_model','working_hours_model');
        $this->load->model('working_days_model','working_days_model');
        $this->load->model('cut_off_model','cut_off_model');
        $this->load->helper('hupay_helper');
        $this->load->helper('attendance_helper');
        $this->load->helper('date_helper');
        $this->load->helper('leave_helper');
        $this->load->helper('cut_off_helper');
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
        $bio_id = $employeeInfo['bio_id'];
        
        $this->form_validation->set_rules('addAttendanceDate', 'attendanceDateOt', 'required');
        $this->form_validation->set_rules('hourTimeOutAttendance', 'hourTimeOutAttendance', 'required');
        $this->form_validation->set_rules('minTimeOutOtAttendance', 'minTimeOutOtAttendance', 'required');
        $this->form_validation->set_rules('periodTimeOutAttendance', 'periodTimeOutAttendance', 'required');
        $this->form_validation->set_rules('hourTimeInAttendance', 'hourTimeInAttendance', 'required');
        $this->form_validation->set_rules('minTimeInAttendance', 'minTimeInAttendance', 'required');
        $this->form_validation->set_rules('periodTimeInAttendance', 'periodTimeInAttendance', 'required');
        $this->form_validation->set_rules('remarksAttendance', 'remarksAttendance', 'required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required.";
        }
        else{
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
            $getRowTimeInOut = $this->attendance_model->attendance_info($bio_id, dateDefaultDb($addAttendanceDate));
        
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
            else if ($getRowTimeInOut != 0){
                $this->data['status'] = "error";
                $this->data['msg'] = "The date <strong>".$addAttendanceDate."</strong> is already in your attendance list.";
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
                    $attendanceNotifUpdate = $this->attendance_model->attendance_notif_update($attendanceNotifData, $attendance_notif_id);

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
                    }while($counter <= $count);
                    $this->data['status'] = "success";
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
                        'attendance_id'=>'0',
                        'date'=>$attendance_date,
                        'time_in'=>$time_in,
                        'time_out'=>$time_out,
                        'remarks'=>$remarks,
                        'notif_status'=>$notif_status,
                        'DateCreated'=>$current_date,
                    );
                    $insertAttendanceNotifications = $this->attendance_model->insert_attendance_notif($insertAttendanceNotificationsData);

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
                        $attendanceNotifId = $this->attendance_model->attendance_notif_last_id();
                        foreach($attendanceNotifId as $value){
                            $insertNotificationsData = array(
                                'attendance_notification_id'=>'',
                                'emp_id'=>$emp_id,
                                'notif_emp_id'=>$approver_id,
                                'attendance_notif_id'=>'0',
                                'attendance_ot_id'=>$value->attendance_notif_id,
                                'leave_id'=>'0',
                                'NotifType'=>$notifType,
                                'type'=>'Add Attendance',
                                'Status'=>$status,
                                'DateTime'=>$dateTime,
                                'ReadStatus'=>0,
                            );
                            
                        }
                        $insertAttendance = $this->attendance_model->insert_notifications($insertNotificationsData);
                        $counter++;
                    }
                    while($counter <= $count);
                    $this->data['status'] = "success";
                    
                }
                
            }
        }
        echo json_encode($this->data);
    }

    public function addLeave(){
        $leaveType = $this->input->post('leaveType');
        $dateFromLeave = $this->input->post('dateFromLeave');
        $dateToLeave = $this->input->post('dateToLeave');
        $remarksLeave = $this->input->post('remarksLeave');
        $fileLeaveType = $this->input->post('fileLeaveType');
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $head_emp_id = $employeeInfo['head_emp_id'];
        $bio_id = $employeeInfo['bio_id'];

        $this->form_validation->set_rules('leaveType', 'leaveType', 'required');
        $this->form_validation->set_rules('dateFromLeave', 'dateFromLeave', 'required');
        $this->form_validation->set_rules('dateToLeave', 'dateToLeave', 'required');
        $this->form_validation->set_rules('remarksLeave', 'remarksLeave', 'required');
        $this->form_validation->set_rules('fileLeaveType', 'fileLeaveType', 'required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "All fields are required.";
        }
        else{
            $dateFrom_month = substr($dateFromLeave,0,2);
            $dateFrom_day = substr(substr($dateFromLeave, -7), 0,2);
            $dateFrom_year = substr($dateFromLeave, -4);
            
            $dateTo_month = substr($dateToLeave,0,2);
            $dateTo_day = substr(substr($dateToLeave, -7), 0,2);
            $dateTo_year = substr($dateToLeave, -4);
            
            $date1=date_create(date_format(date_create($dateFromLeave),"Y-m-d"));
            $date2=date_create(date_format(date_create(date("Y-m-d")),"Y-m-d"));
            $diff =date_diff($date1,$date2);
            $diffConverted =  $diff->format("%R%a");
            $days = str_replace("+","",$diffConverted);

            $exist_leave_type = 0;
            $no_days_to_file = 0;
            $lv_id = 0;
            $name = "";

            $leave = $this->leave_model->get_type_of_leave_by_id($leaveType);
            if(!empty($leave)){

                $lv_id = $leave['lv_id'];
                $no_days_to_file = $leave['no_days_to_file'];
                $name = $leave['name'];
                //$db_leave_count = $row_leave_type->count;

                $exist_leave_type = 1;
            }
            $row_wd = $this->working_days_model->get_working_days_info($employeeInfo['working_days_id']);

            $day_from = $row_wd['day_from'];
            $day_to = $row_wd['day_to'];
            
            $leaveValidated = leaveValidation($lv_id, $dateFromLeave, $dateToLeave, $no_days_to_file,$emp_id);

            if ($exist_leave_type == 0){
                $this->data['status'] = "error";
                $this->data['msg'] = "Leave type does not exists.";
            }
            else if($leaveValidated !=""){
                $this->data['status'] = "error";
                $this->data['msg'] = $leaveValidated;
            }
            else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateFromLeave)) {
                $this->data['status'] = "error";
                $this->data['msg'] = "<strong>Date From</strong> not match to the current format mm/dd/yyyy.";
            }
            else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateToLeave)) {
                $this->data['status'] = "error";
                $this->data['msg'] = "<strong>Date To</strong> not match to the current format mm/dd/yyyy.";
            }
            else if ($dateFrom_year % 4 == 0 && $dateFrom_month == 2 && $dateFrom_day >= 30){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid leave date.";
            }
            else if ($dateFrom_year % 4 != 0 && $dateFrom_month == 2 && $dateFrom_day >= 29){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid leave date.";
            }
            else if (($dateFrom_month == 4 || $dateFrom_month == 6 || $dateFrom_month == 9 || $dateFrom_month == 11)
                && $dateFrom_day  >= 31){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid leave date.";
            }
            else if ($dateTo_year % 4 == 0 && $dateTo_month == 2 && $dateTo_day >= 30){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid leave date.";
            }
            else if ($dateTo_year % 4 != 0 && $dateTo_month == 2 && $dateTo_day >= 29){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid leave date.";
            }
            else if (($dateTo_month == 4 || $dateTo_month == 6 || $dateTo_month == 9 || $dateTo_month == 11)
                && $dateTo_day  >= 31){
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid leave date.";
            }
            else if ($dateFromLeave > $dateToLeave){
                $this->data['status'] = "error";
                $this->data['msg'] = "<strong>Date From</strong> cannot be greater than or equal to <strong>Date To</strong>.";
            }
            else{
                $lt_id = $leaveType;
                $remaining_leave_count = getEmpLeaveCountByEmpIdLtId($emp_id,$lv_id);

                $dateFrom = dateDefaultDb($dateFromLeave);
                $dateTo = dateDefaultDb($dateToLeave);

                $dates = array();
                $from = strtotime($dateFrom);
                $last = strtotime($dateTo);
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
                $days_count = 0;
                do{
                    $date_create = date_create($dates[$counter]);
                    $day = date_format($date_create, 'w');
                    if ($day >= $day_from && $day <= $day_to){
                        $weekdays[] = $dates[$counter];
                        $date =  $dates[$counter]; 
                        $weekdays_count++;
                        $holiday = date_format(date_create($date), 'F j');
                        $holiday_num_rows =$this->holiday_model->get_holiday_date_rows($holiday);
                        if($holiday_num_rows != 1){
                            $days_count ++;
                        }
                    }
                    $counter++;
                }
                while($counter <= $count);

                $dateCreated = getDateDate();
                $can_file = false;
                if ($name == "Formal Leave"){
                    $fileLeaveType = "Leave without pay";
                    $can_file = true;
                }
                $notifFileLeave = "File Leave";
                if ($days_count <= $remaining_leave_count || $can_file == true){
                    $approveStat = 0;
                    // ibig sabihin staff xa
                    if ($head_emp_id != 0){
                        $approveStat = 4;
                    }

                    // ibig sabihin head xa
                    else if ($head_emp_id == 0){
                        $approveStat = 0;
                    }

                    $lt_id = $leaveType;

                    $leaveDateFromDateTo = $this->leave_model->leave_date_from_date_to($emp_id,dateDefaultDb($dateFromLeave),
                        dateDefaultDb($dateToLeave), $fileLeaveType
                    );
                    if(!empty($leaveDateFromDateTo)){
                        $updateLeaveData = array(
                            'head_emp_id'=>$head_emp_id,
                            'LeaveType'=>$leaveType,
                            'lt_id'=>$lt_id,
                            'Remarks'=>$remarksLeave,
                            'FileLeaveType'=>$fileLeaveType,
                            'approveStat'=>$approveStat
                        );
                        $updateLeave = $this->leave_model->update_leave($emp_id, $dateFrom,$dateTo, $updateLeaveData);
                        
                    }
                    else{
                        
                        $approveStat = 0;
                        // ibig sabihin staff xa
                        if ($head_emp_id != 0){
                            $approveStat = 4;
                        }

                        // ibig sabihin head xa
                        else if ($head_emp_id == 0){
                            $approveStat = 0;
                        }
                        $insertLeaveData = array(
                            'emp_id'=>$emp_id,
                            'head_emp_id'=>$head_emp_id,
                            'dateFrom'=>$dateFrom,
                            'dateTo'=>$dateTo,
                            'LeaveType'=>$name,
                            'lt_id'=>$lt_id,
                            'Remarks'=>$remarksLeave,
                            'FileLeaveType'=>$fileLeaveType,
                            'approveStat'=>$approveStat,
                            'DateCreated'=>$dateCreated,
                        );
                        $insertLeave = $this->leave_model->insert_leave($insertLeaveData);
                        //asd
                    }
                    $emp_id_values = explode("#",getEmpIdByNotification($emp_id));
                    //echo $emp_id_values[0];

                    $count = getEmpIdByNotificationCount($emp_id) - 1;
                    //echo $count;
                    $final_date_from = dateFormat($dateFrom);
                    $final_date_to = dateFormat($dateTo);

                    $counter = 0;
                    do {

                        $emp_id = $emp_id_values[$counter];
                        //echo $emp_id . "<br/>";
                        
                        $approver_id = $emp_id;
                        $notifType = "File ".$name." from $final_date_from to $final_date_to";
                        $status = "Pending";
                        $dateTime = getDateTime();
                        $attendanceLeaveId = $this->leave_model->leave_last_id();
                        $insertNotificationsData = array(
                            'attendance_notification_id'=>'',
                            'emp_id'=>$emp_id,
                            'notif_emp_id'=>$approver_id,
                            'attendance_notif_id'=>0,
                            'attendance_ot_id'=>'0',
                            'leave_id'=>$attendanceLeaveId['leave_id'],
                            'NotifType'=>$notifType,
                            'type'=>$notifFileLeave,
                            'Status'=>$status,
                            'DateTime'=>$dateTime,
                            'ReadStatus'=>0,
                        );
                        $insertNotifications = $this->attendance_model->insert_notifications($insertNotificationsData);
                        $counter++;
                    }
                    while($counter <= $count);
                    $this->data['status'] = "success";
                    $this->data['msg'] = "<strong>$name</strong> from <strong>$final_date_from</strong> to <strong>$final_date_to</strong> was successfully filed.";   
                }
                else{
                    $this->data['error'] = "error";
                    $this->data['msg'] = "You cannot file leave with pay because your remaining leave count is less than the date range you've enter.";
                }

            }
            
        }
        echo json_encode($this->data);
    }


    //for add attendance start
    public function viewAddAttendance(){
        $this->data['pageTitle'] = 'Add Attendance';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('attendance/add_attendance');
        $this->load->view('global/footer');
    }

    //for add attendance end

    //for attendance updates start
    public function viewAttendanceUpdates(){
        $this->data['pageTitle'] = 'Attendance Updates Request';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('attendance/attendance_updates');
        $this->load->view('global/footer');
    }
    //for attendance updates end

    //for attendance request list start
    public function getAttendanceRequestList(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $role = $employeeInfo['role_id'];
        $finalData = "";
        $counter = 1;
        if($role == 3 || $role == 4){
            $select_qry = $this->attendance_model->get_attendance_notif_head($emp_id);
            $this->data['pasok'] = '1';
        }
        else{
            if($emp_id == 167 || $emp_id == 168 || $emp_id == 174){
                $select_qry = $this->attendance_model->get_attendance_notif_for_head($emp_id);
                
            }
            else{
                $select_qry = $this->attendance_model->get_attendance_notif_for_employee($emp_id);
                
            }
        }
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                $select_emp_qry = $this->employee_model->employee_information($value->emp_id);

                date_default_timezone_set("Asia/Manila");
                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');

                $year = date("Y");
                $select_cutoff_qry = $this->attendance_model->get_cut_off();
                if(!empty($select_cutoff_qry)){
                    foreach($select_cutoff_qry as $valueCutOff){

                        $date_from = date_format(date_create($valueCutOff->dateFrom . ", " .$year),'Y-m-d');
                        if (date_format(date_create($valueCutOff->dateFrom),'m-d') == "12-26"){
                            $prev_year = $year - 1;
                            $date_from = $prev_year . "-" .date_format(date_create($valueCutOff->dateFrom),'m-d');
                            
                        }
                        $date_from = date_format(date_create($date_from),"Y-m-d");
                        $date_to = date_format(date_create($valueCutOff->dateTo. ", " .$year),'Y-m-d');

                        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                        
                        if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                            $final_date_from = $date_from;
                            $final_date_to = $date_to;
                            $date_payroll = date_format(date_create($valueCutOff->datePayroll . ", " .$year),'Y-m-d');
                        }
                    }
                }
                $attendance_date = date_format(date_create($value->date),"Y-m-d");

                $timeFrom = date_format(date_create($value->time_in), 'g:i A');
                $timeTo = date_format(date_create($value->time_out), 'g:i A');

                $attendance_status = "Add Attendance";
                if ($value->attendance_id != 0){
                    $select_attendance_qry = $this->attendance_model->get_attendance_by_id($value->attendance_id);
                    $orig_timeFrom = date_format(date_create($select_attendance_qry['time_in']), 'g:i A');
                    $orig_timeTo = date_format(date_create($select_attendance_qry['time_out']), 'g:i A');
                    if ($value->time_out == "00:00:00") {
                        $orig_timeTo = "No Time Out";
                    }

                    $attendance_status = $orig_timeFrom . " - " . $orig_timeTo;
                }
                if ($value->notif_status != 1 && $value->notif_status != 2) {
                    $finalData .= "<tr id='".$value->attendance_notif_id."''>";
                        $finalData .= "<td><input type='checkbox' value='".$value->attendance_notif_id."' name='attendance_request".$counter."' /></td>";
                        $finalData .= "<td>". $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'] . " " . $select_emp_qry['Lastname'] . "</td>";
                        $finalData .= "<td>" . $date_format . "</td>";
                        $finalData .= "<td>" . $attendance_status . "</td>";
                        $finalData .= "<td>" . $timeFrom . " - ". $timeTo . "</td>";
                        $finalData .= "<td id='readmoreValue'>" . nl2br(htmlspecialchars($value->remarks)) . "</td>";
                        $finalData .= "<td>";
                            
                            if ($emp_id != 21 && ((($emp_id == 167 || $emp_id == 168 || $emp_id == 174) && $value->notif_status == 4) || $emp_id == 71 || ($value->head_emp_id == $emp_id && $value->notif_status == 4)) || $role == 1){
                                $finalData .= "<button id=".$value->attendance_notif_id." class='approve-single-attendance btn btn-sm btn-outline-success' type='button' data-toggle='modal' data-target='#singleApproveAttendanceModal'>Approve</button>";
                                $finalData .= "<button id=".$value->attendance_notif_id." class='disapprove-single-attendance btn btn-sm btn-outline-danger' type='button' data-toggle='modal' data-target='#singleDisapproveAttendanceModal'>Disapprove</button";
                            }
                            else {
                                if ($row->emp_id == 71){
                                   $finalData .= "<button id=".$value->attendance_notif_id." class='approve-single-attendance btn btn-sm btn-outline-success' type='button' data-toggle='modal' data-target='#singleApproveAttendanceModal'>Approve</button>";
                                    $finalData .= "<button id=".$value->attendance_notif_id." class='disapprove-single-attendance btn btn-sm btn-outline-danger' type='button' data-toggle='modal' data-target='#singleDisapproveAttendanceModal'>Disapprove</button";
                                }
                                else {
                                    $finalData .= "No action";
                                }
                            }
                        $finalData .= "</td>";
                    $finalData .= "</tr>";
                    $counter++;
                }
            }
        }
        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }
    //for attendance request list end

    //for ot list approved start
    public function viewOtListApproved(){
        $this->data['pageTitle'] = 'Overtime List Approved';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('attendance/ot_list_approved');
        $this->load->view('global/footer');
    }
    public function getOtRequestList(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $role = $employeeInfo['role_id'];

        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        $select_cutoff_qry = $this->attendance_model->get_cut_off();

        $finalData = "";
        if(!empty($select_cutoff_qry)){
            foreach($select_cutoff_qry as $valueCutOff){

                $date_from = date_format(date_create($valueCutOff->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($valueCutOff->dateFrom),'m-d') == "12-26"){
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($valueCutOff->dateFrom),'m-d');
                    
                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($valueCutOff->dateTo. ", " .$year),'Y-m-d');

                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                    $final_date_from = $date_from;
                    $final_date_to = $date_to;
                    $date_payroll = date_format(date_create($valueCutOff->datePayroll . ", " .$year),'Y-m-d');
                }
            }
        }
        if($role == 4 || $role ==  3){
            $select_qry = $this->attendance_model->get_attendance_overtime_for_head($emp_id, $final_date_from, $final_date_to);
            
        }
        else{
            $select_qry = $this->attendance_model->get_attendance_overtime_for_employee($final_date_from, $final_date_to);

        }
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $select_emp_qry = $this->employee_model->employee_information($value->emp_id);

                $fullName = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];


                $date_create = date_create($value->date);
                $date = date_format($date_create, 'F d, Y');

                $timeFrom = date_format(date_create($value->time_from), 'g:i A');
                $timeTo = date_format(date_create($value->time_out), 'g:i A');

                $finalData .= "<tr>";
                    $finalData .= "<td>" .$fullName. "</td>";
                    $finalData .= "<td>" .$date. "</td>";
                    $finalData .= "<td>" .$timeFrom. "</td>";
                    $finalData .= "<td>" .$timeTo. "</td>";
                    $finalData .= "<td>" .$value->type_ot. "</td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }

    public function getAllOtApproveList(){
        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        $select_cutoff_qry = $this->attendance_model->get_cut_off();

        $finalData = "";
        if(!empty($select_cutoff_qry)){
            foreach($select_cutoff_qry as $valueCutOff){

                $date_from = date_format(date_create($valueCutOff->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($valueCutOff->dateFrom),'m-d') == "12-26"){
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($valueCutOff->dateFrom),'m-d');
                    
                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($valueCutOff->dateTo. ", " .$year),'Y-m-d');

                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                    $final_date_from = $date_from;
                    $final_date_to = $date_to;
                    $date_payroll = date_format(date_create($valueCutOff->datePayroll . ", " .$year),'Y-m-d');
                }
            }
        }
        $select_qry = $this->attendance_model->get_all_attendance_overtime($final_date_from,$final_date_to);
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $select_emp_qry = $this->employee_model->employee_information($value->emp_id);

                $fullName = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];
                $date_create = date_create($value->date);
                $date = date_format($date_create, 'F d, Y');

                $timeFrom = date_format(date_create($value->time_from), 'g:i A');
                $timeTo = date_format(date_create($value->time_out), 'g:i A');

                $finalData .= "<tr>";
                    $finalData .= "<td>" .$fullName. "</td>";
                    $finalData .= "<td>" .$date. "</td>";
                    $finalData .= "<td>" .$timeFrom. "</td>";
                    $finalData .= "<td>" .$timeTo. "</td>";
                    $finalData .= "<td>" .$value->type_ot. "</td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }



    //for ot list approved end


    //for over time list start
    public function viewOtList(){
        $this->data['pageTitle'] = 'Overtime List';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('attendance/file_overtime');
        $this->load->view('global/footer');
    }

    public function getOvertimeList(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $role = $employeeInfo['role_id'];
        $finalData = "";
        if($role == 4 || $role == 3){
            $select_qry = $this->attendance_model->get_attendance_overtime_for_head_approve_condition($emp_id);
        }
        else{
            if ($emp_id == 167 || $emp_id == 168){
                $select_qry = $this->attendance_model->get_attendance_overtime_emp_or_head($emp_id);
            }
            else{
                $select_qry = $this->attendance_model->get_all_attendance_overtime_zero_stat($emp_id);
            }
        }
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $select_emp_qry = $this->employee_model->employee_information($value->emp_id);

                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');
                date_default_timezone_set("Asia/Manila");
                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');
                $year = date("Y");
                $select_cutoff_qry = $this->attendance_model->get_cut_off();

                
                if(!empty($select_cutoff_qry)){
                    foreach($select_cutoff_qry as $valueCutOff){

                        $date_from = date_format(date_create($valueCutOff->dateFrom . ", " .$year),'Y-m-d');
                        if (date_format(date_create($valueCutOff->dateFrom),'m-d') == "12-26"){
                            $prev_year = $year - 1;
                            $date_from = $prev_year . "-" .date_format(date_create($valueCutOff->dateFrom),'m-d');
                            
                        }
                        $date_from = date_format(date_create($date_from),"Y-m-d");
                        $date_to = date_format(date_create($valueCutOff->dateTo. ", " .$year),'Y-m-d');

                        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                        
                        if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                            $final_date_from = $date_from;
                            $final_date_to = $date_to;
                            $date_payroll = date_format(date_create($valueCutOff->datePayroll . ", " .$year),'Y-m-d');
                        }
                    }
                }
                $date_create = date_create($value->date);
                $date_format = date_format($date_create, 'F d, Y');

                $date_create_file = date_create($value->DateCreated);
                $date_format_file = date_format($date_create_file, 'F d, Y');

                $attendance_date = date_format(date_create($value->date),"Y-m-d");

                $timeFrom = date_format(date_create($value->time_from), 'g:i A');
                $timeTo = date_format(date_create($value->time_out), 'g:i A');

                if ($value->approve_stat != 2 && $value->approve_stat != 1) {
                    $finalData .= "<tr id=".$value->attendance_ot_id.">";
                    $finalData .= "<td>" .$select_emp_qry['Lastname'] . ", " .$select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename']."</td>";
                    $finalData .= "<td>".$date_format_file."</td>";
                    $finalData .= "<td>".$date_format."</td>";
                    $finalData .= "<td>".$timeFrom."</td>";
                    $finalData .= "<td>".$timeTo."</td>";
                    $finalData .= "<td>".nl2br(htmlspecialchars($value->remarks))."</td>";
                    $finalData .= "<td>";

                        if ($emp_id != 21 && ((($emp_id == 167 || $emp_id == 168) && $value->approve_stat == 4)) || $emp_id == 71 || ($value->head_emp_id == $emp_id && $value->approve_stat == 4) || $role == 1 || $role == 2){

                            $finalData .= "<button id=".$value->attendance_ot_id." class='open-approval-ot btn btn-sm btn-outline-success' data-toggle='modal' data-target='#approveOtModal'>Approve</button>";
                            $finalData .= "<button id=".$value->attendance_ot_id." class='open-disapproval-ot btn btn-sm btn-outline-danger' data-toggle='modal' data-target='#disapproveOtModal'>Disapprove</button>";
                        }
                        else {
                            $finalData .= "No action";
                        }
                    $finalData .= "</td>";
                    $finalData .= "</tr>";
                }
            }
        }
        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }
    //for over time list end

    //for attendance list start
    public function viewAttendanceList(){
        $this->data['pageTitle'] = 'Attendance List';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('attendance/attendance_list');
        $this->load->view('global/footer');
    }

    public function searchAllAttendance(){
        $dateFrom = dateDefaultDb($this->input->post('dateFrom'));
        $dateTo = dateDefaultDb($this->input->post('dateTo'));
        $finalData = "";
        $this->form_validation->set_rules('dateFrom','dateFrom','required');
        $this->form_validation->set_rules('dateTo','dateTo','required');
        if($this->form_validation->run() == FALSE){
            $this->status = "error";

        }
        else{
            $select_qry = $this->attendance_model->get_all_attendance($dateFrom, $dateTo);
            if(!empty($select_qry)){
                foreach ($select_qry as $value) {
                    $date_create = date_create($value->date);
                    $date = date_format($date_create, 'F d, Y');

                    $empInformation = $this->employee_model->get_employee_by_bio_id_data($value->bio_id);
                    if(!empty($empInformation)){
                        $fullName = $empInformation['Lastname'] . ", " . $empInformation['Firstname'] . " " . $empInformation['Middlename'];
                        $timeFrom = "-";
                        if ($value->time_in != "00:00:00"){
                            $timeFrom = date_format(date_create($value->time_in), 'g:i:s A');
                        }
                        $timeTo = "-";
                        if ($value->time_out != "00:00:00") {
                            $timeTo = date_format(date_create($value->time_out), 'g:i:s A');
                        }
                        $finalData .= "<tr>";
                            $finalData .= "<td>".$fullName."</td>";
                            $finalData .= "<td>".$date."</td>";
                            $finalData .= "<td>".$timeFrom."</td>";
                            $finalData .= "<td>".$timeTo."</td>";
                        $finalData .= "</tr>";
                    }
                }
            }

            $this->data['status'] = "success";
            $this->data['finalData'] = $finalData;
        }
        
        echo json_encode($this->data);
    }
    //fora attnendace list end

    //for generate attendance for cut off period start
    public function generateAttendance(){
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');

        $year = date("Y");

        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
        $finalData = "";
        $select_qry = $this->cut_off_model->get_cut_off();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $date_from = date_format(date_create($value->dateFrom),'Y-m-d');
                if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
                    //echo "wew";
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
                    //echo $date_from . "sad";
                    //$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

                
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                    $final_date_from = $date_from;
                    $final_date_to = $date_to;
                    //$date_payroll = date_format(date_create($value->datePayroll),'Y-m-d');
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
        $name_count = 0;

        do {
            $date_create = date_create($dates[$counter]);
            $attendance_date = date_format($date_create, 'M d, Y');

            $day = date_format($date_create, 'l');

            if ($day != "Saturday" && $day != "Sunday"){
                $name_count++;
            
                $finalData .= "<table style='background-color:#d6eaf8' border='1'>";
                    $finalData .= "<tbody>";
                        $finalData .= "<tr>";
                            $finalData .= "<td style='padding:5px;' width='15%'><input type='text' class='input-only form-control' name='attendance_date".$name_count."' value='".$attendance_date."'</td>";
                            $finalData .= "<td style='padding:5px;' width='8%'><input type='text' title='hour' name='time_in_hour_attendance".$name_count ."' class='form-control number-only' placeholder='Hour'/></td>";

                            $finalData .= "<td style='padding:5px;' width='8%'><input type='text' title='min' name='time_in_min_attendance".$name_count ."' class='form-control number-only' placeholder='Min'/></td>";

                            $finalData .= "<td style='padding:1px;' width='8%'>";
                                $finalData .= "<select class='' name='time_in_period".$name_count."'>
                                        <option value=''></option>
                                        <option value='AM'>AM</option>
                                        <option value='PM'>PM</option>
                                    </select>";
                            $finalData .= "</td>";

                            $finalData .= "<td style='padding:5px;' width='8%'><input type='text' title='hour' name='time_out_hour_attendance".$name_count ."' class='form-control number-only' placeholder='Hour'/></td>";

                            $finalData .= "<td style='padding:5px;' width='8%'><input type='text' title='min' name='time_out_min_attendance".$name_count ."' class='form-control number-only' placeholder='Min'/></td>";

                            $finalData .= "<td style='padding:1px;' width='8%'>";
                                $finalData .= "<select class='' name='time_out_period".$name_count."'>
                                        <option value=''></option>
                                        <option value='AM'>AM</option>
                                        <option value='PM'>PM</option>
                                    </select>";
                            $finalData .= "</td>";
                        $finalData .= "</tr>";
                    $finalData .= "</tbody>";
                $finalData .= "</table>";


            }
            $counter++;
        }while($counter <= $count);
        $finalData .='<input type="hidden" class="for-id" name="id">';

        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }
    //for generate attendance for cut off period end

    //for add attendance to cut off start
    public function addAttendanceForCutOff(){
        $id = $this->input->post('id');
        $employeeInfo = $this->employee_model->employee_information($id);
        if(!empty($employeeInfo)){
            $name = $employeeInfo['Lastname'] . ", " . $employeeInfo['Firstname'] . " " . $employeeInfo['Middlename'];
            //echo $name;

            $bio_id = $employeeInfo['bio_id'];
            $count = getCutOffAttendanceDateCount();
            $counter = 0;
            do {

                $counter++;
                if ($_POST["time_in_hour_attendance".$counter] != "" && $_POST["time_in_min_attendance".$counter] && $_POST["time_out_hour_attendance".$counter] != "" && $_POST["time_out_min_attendance".$counter] != ""){

                    $attendance_date = $_POST["attendance_date".$counter];

                    $time_in = date_format(date_create($_POST["time_in_hour_attendance".$counter] . ":" . $_POST["time_in_min_attendance".$counter] ),"H:i:s");
                    
                    $time_in_period = $_POST["time_in_period".$counter];
                    if ($time_in_period == "PM"){
                        $time_in  = date("H:i:s",strtotime($time_in ." +12 hours"));
                    }

                    $time_out = date_format(date_create($_POST["time_out_hour_attendance".$counter] . ":" . $_POST["time_out_min_attendance".$counter]),"H:i:s");
                    $time_out_period = $_POST["time_out_period".$counter];
                    if ($time_out_period == "PM"){
                        $time_out  =  date("H:i:s",strtotime($time_out ." +12 hours"));
                    }
                    if ($time_in_period != "AM" && $time_in_period != "PM"){
                        //echo $attendance_date . " " . $time_in . " " .$time_out ."Hindi masisave";
                    }

                    else if ($time_in >= $time_out){
                        //echo $attendance_date . " " . $time_in . " " .$time_out ."Hindi masisave";
                    }

                    else {
                        $dateCreated = getDateDate();

                        $attendance_date = dateDefaultDb($attendance_date);
                        $checkAttendance = $this->attendance_model->get_leave_date($bio_id, $attendance_date);
                        if(!empty($checkAttendance)){
                            $updateData = array(
                                'time_in'=>$time_in,
                                'time_out'=>$time_out,

                            );
                            $update = $this->attendance_model->update_attendance_using_bio_and_date($bio_id,$attendance_date, $updateData);
                        }
                        else{
                            $insertData = array(
                                'bio_id'=>$bio_id,
                                'date'=>$attendance_date,
                                'time_in'=>$time_in,
                                'time_out'=>$time_out,
                                'DateCreated'=>$dateCreated
                            );
                            $insert = $this->attendance_model->insert_attendance($insertData);
                        }

                        
                    }
                }
            }while($count > $counter);
            $this->data['status'] = "success";
            $this->data['msg'] = '<strong>'.$name.'</strong> attendance for the cut off <strong>'.getCutOffPeriodLatest().'</strong> was successfully added.';
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }
    //for add attendance to cut off end

    //for approve attendance updates start
    public function approveDisapproveAttendanceUpdatesMultiple(){
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);
        $role = $employeeInfo['role_id'];
        $count = attendanceNotifToTableCount();
        $counter = 1;
        do{
            if (isset($_POST["attendance_request".$counter])){
                $attendance_notif_id = $_POST["attendance_request".$counter];
                $row = $this->attendance_model->get_attendance_notif_request($attendance_notif_id);
                $notif_status = 0;
                if ($row['head_emp_id'] == 0 || $row['notif_status'] == 0){
                    $notif_status = 1;
                }
                date_default_timezone_set("Asia/Manila");
                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');
                $updateRequestData = array(
                    'notif_status'=>$notif_status,
                    'DateApprove'=>$current_date_time,
                );
                $updateRequest = $this->attendance_model->attendance_notif_update($updateRequestData,$attendance_notif_id);
                $emp_id = $row['emp_id'];
                
                $time_in = $row['time_in'];
                $time_out = $row['time_out'];
                $attendance_date = $row['date']; 


                $attendance_id = $row['attendance_id'];
                $row_emp = $this->employee_model->employee_information($emp_id);
                $bio_id = $row_emp['bio_id'];
                $checkAttendance = $this->attendance_model->get_attendance_by_id($attendance_id);
                if(!empty($checkAttendance)){
                    $updateData = array(
                        'time_in'=>$time_in,
                        'time_out'=>$time_out,

                    );
                    $update = $this->attendance_model->update_attendance($attendance_id,$updateData);
                }
                else{
                    $insertData = array(
                        'bio_id'=>$bio_id,
                        'date'=>$attendance_date,
                        'time_in'=>$time_in,
                        'time_out'=>$time_out,
                        'DateCreated'=>getDateDate()
                    );
                    $insert = $this->attendance_model->insert_attendance($insertData);
                }
                
                $final_attendance_date = dateFormat($attendance_date);

                $date_create = date_create($time_in);
                $final_time_in = date_format($date_create, 'g:i A');

                $date_create = date_create($time_out);
                $final_time_out = date_format($date_create, 'g:i A');


                $emp_id = $row['emp_id'];
                $approver_id = $this->session->userdata('user');
                $notifType = "Update Attendance on ".$final_attendance_date." with time in ".$final_time_in." and time out ".$final_time_out."";
                //$status = $approve;
                $dateTime = getDateTime();

                $insertNotificationsData = array(
                    'attendance_notification_id'=>'',
                    'emp_id'=>$emp_id,
                    'notif_emp_id'=>$approver_id,
                    'attendance_notif_id'=>$attendance_notif_id,
                    'attendance_ot_id'=>0,
                    'leave_id'=>0,
                    'NotifType'=>$notifType,
                    'type'=>'Approve Attendance',
                    'Status'=>"Approve",
                    'DateTime'=>$dateTime,
                    'ReadStatus'=>0,
                );
                $insertNotifications = $this->attendance_model->insert_notifications($insertNotificationsData);

                $module = "Attendance Updates List";
                $task_description = "Approve Attendance Updates";
                $insertAuditTrialData = array(
                    'audit_trail_id'=>'',
                    'file_emp_id'=>$emp_id,
                    'approve_emp_id'=>$approver_id,
                    'involve_emp_id'=>0,
                    'module'=>$module,
                    'task_description'=>$task_description,
                );
                $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);


                
            }

            $counter++;
        }while($count >= $counter);

        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function validatePassForApproveDisapproveOfAttendance(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $originalPassword = $employeeInfo['Password'];
        $password = $this->input->post('password');
        $approve_payroll_id = $this->input->post('id');
        $this->form_validation->set_rules('password', 'password','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please enter your password.";
        }
        else{
            if(password_verify($password, $originalPassword)){
                $this->data['status'] = "success";
            }
            else{
                $this->data['status'] = "error";
                $this->data['msg'] = "Your password is incorrect.";
            }
        }

        echo json_encode($this->data);
    }

    public function disapproveAttendanceUpdatesMultiple(){
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);
        $role = $employeeInfo['role_id'];
        $count = attendanceNotifToTableCount();
        $counter = 1;
        do{
            if (isset($_POST["attendance_request".$counter])){
                $attendance_notif_id = $_POST["attendance_request".$counter];
                $row = $this->attendance_model->get_attendance_notif_request($attendance_notif_id);
                date_default_timezone_set("Asia/Manila");
                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');
                $updateRequestData = array(
                    'notif_status'=>2,
                    'DateApprove'=>$current_date_time,
                );
                $updateRequest = $this->attendance_model->attendance_notif_update($updateRequestData,$attendance_notif_id);
                $emp_id = $row['emp_id'];
                
                $time_in = $row['time_in'];
                $time_out = $row['time_out'];
                $attendance_date = $row['date'];

                $final_attendance_date = dateFormat($attendance_date);

                $date_create = date_create($time_in);
                $final_time_in = date_format($date_create, 'g:i A');

                $date_create = date_create($time_out);
                $final_time_out = date_format($date_create, 'g:i A');


                $emp_id = $row['emp_id'];
                $approver_id = $this->session->userdata('user');
                $notifType = "Update Attendance on ".$final_attendance_date." with time in ".$final_time_in." and time out ".$final_time_out."";
                //$status = $approve;
                $dateTime = getDateTime();

                $insertNotificationsData = array(
                    'attendance_notification_id'=>'',
                    'emp_id'=>$emp_id,
                    'notif_emp_id'=>$approver_id,
                    'attendance_notif_id'=>$attendance_notif_id,
                    'attendance_ot_id'=>0,
                    'leave_id'=>0,
                    'NotifType'=>$notifType,
                    'type'=>'Disapprove Attendance',
                    'Status'=>"Disapprove",
                    'DateTime'=>$dateTime,
                    'ReadStatus'=>0,
                );
                $insertNotifications = $this->attendance_model->insert_notifications($insertNotificationsData);

                $module = "Attendance Updates List";
                $task_description = "Disapprove Attendance Updates";
                $insertAuditTrialData = array(
                    'audit_trail_id'=>'',
                    'file_emp_id'=>$emp_id,
                    'approve_emp_id'=>$approver_id,
                    'involve_emp_id'=>0,
                    'module'=>$module,
                    'task_description'=>$task_description,
                );
                $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);


                
            }

            $counter++;
        }while($count >= $counter);

        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    //for approve attendance updates end

    //for approve single attendance start 
    public function approveSingleAttendance(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $originalPassword = $employeeInfo['Password'];


        $password = $this->input->post('password');
        $id = $this->input->post('id');

        $this->form_validation->set_rules('password', 'password','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please enter your password.";
        }
        else{
            if(password_verify($password, $originalPassword)){
                $row = $this->attendance_model->get_attendance_notif_request($id);
                $notif_status = 0;
                if ($row['head_emp_id'] == 0 || $row['notif_status'] == 0){
                    $notif_status = 1;
                }
                date_default_timezone_set("Asia/Manila");
                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');
                $updateRequestData = array(
                    'notif_status'=>$notif_status,
                    'DateApprove'=>$current_date_time,
                );
                $updateRequest = $this->attendance_model->attendance_notif_update($updateRequestData,$id);
                if ($notif_status == 1) {
                    $emp_id = $row['emp_id'];
            
                    $time_in = $row['time_in'];
                    $time_out = $row['time_out'];
                    $attendance_date = $row['date']; 


                    $attendance_id = $row['attendance_id'];
                    $row_emp =  $this->employee_model->employee_information($emp_id);
                    $bio_id = $row_emp['bio_id'];
                    $row_wh = $this->working_hours_model->get_info_working_hours($row_emp['working_hours_id']);

                    $emp_time_in = $row_wh['timeFrom'];
                    $emp_time_out = $row_wh['timeTo'];

                    $checkAttendance = $this->attendance_model->get_attendance_by_id($attendance_id);
                    if(!empty($checkAttendance)){
                        $db_time_in = $checkAttendance['time_in'];
                        $db_time_out = $checkAttendance['time_out'];

                        if ($db_time_in >= $emp_time_out){
                            $time_out = $db_time_in;
                        }

                        if ($db_time_out >= $emp_time_out){
                            $time_out = $db_time_out;
                        }
                        $updateData = array(
                            'time_in'=>$time_in,
                            'time_out'=>$time_out,

                        );
                        $update = $this->attendance_model->update_attendance($attendance_id,$updateData);
                    }
                    else{
                        $checkAttendanceWithBio = $this->attendance_model->get_leave_date($bio_id, $attendance_date);
                        if(!empty($checkAttendanceWithBio)){
                            $db_time_in = $checkAttendanceWithBio['time_in'];
                            $db_time_out = $checkAttendanceWithBio['time_out'];

                            if ($db_time_in >= $emp_time_out){
                                $time_out = $db_time_in;
                            }

                            if ($db_time_out >= $emp_time_out){
                                $time_out = $db_time_out;
                            }
                            $updateData = array(
                                'time_in'=>$time_in,
                                'time_out'=>$time_out,

                            );
                            $update = $this->attendance_model->update_attendance_using_bio_and_date($bio_id,$attendance_date, $updateData);
                        }
                        else{
                            $insertData = array(
                                'bio_id'=>$bio_id,
                                'date'=>$attendance_date,
                                'time_in'=>$time_in,
                                'time_out'=>$time_out,
                                'DateCreated'=>getDateDate(),
                            );
                            $insert = $this->attendance_model->insert_attendance($insertData);
                        }
                    }

                }

                $final_attendance_date = dateFormat($attendance_date);

                $date_create = date_create($time_in);
                $final_time_in = date_format($date_create, 'g:i A');

                $date_create = date_create($time_out);
                $final_time_out = date_format($date_create, 'g:i A');


                $emp_id = $row['emp_id'];
                $approver_id = $this->session->userdata('user');
                $notifType = "Update Attendance on ".$final_attendance_date." with time in ".$final_time_in." and time out ".$final_time_out."";
                //$status = $approve;
                $dateTime = getDateTime();

                $insertNotificationsData = array(
                    'attendance_notification_id'=>'',
                    'emp_id'=>$emp_id,
                    'notif_emp_id'=>$approver_id,
                    'attendance_notif_id'=>$id,
                    'attendance_ot_id'=>0,
                    'leave_id'=>0,
                    'NotifType'=>$notifType,
                    'type'=>'Approve Attendance',
                    'Status'=>"Approve",
                    'DateTime'=>$dateTime,
                    'ReadStatus'=>0,
                );
                $insertNotifications = $this->attendance_model->insert_notifications($insertNotificationsData);

                $module = "Attendance Updates List";
                $task_description = "Approve Attendance Updates";
                $insertAuditTrialData = array(
                    'audit_trail_id'=>'',
                    'file_emp_id'=>$emp_id,
                    'approve_emp_id'=>$approver_id,
                    'involve_emp_id'=>0,
                    'module'=>$module,
                    'task_description'=>$task_description,
                );
                $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);

                $this->data['status'] = "success";
            }
            else{
                $this->data['status'] = "error";
                $this->data['msg'] = "Your password is incorrect.";
            }
        }

        echo json_encode($this->data);
    }
    //for approve single attendance end

    //for disapprove single attendance start
    public function disapproveSingleAttendance(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $originalPassword = $employeeInfo['Password'];


        $password = $this->input->post('password');
        $id = $this->input->post('id');

        $this->form_validation->set_rules('password', 'password','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please enter your password.";
        }
        else{
            if(password_verify($password, $originalPassword)){
                $row = $this->attendance_model->get_attendance_notif_request($id);
                $this->data['asd'] = $row;
                date_default_timezone_set("Asia/Manila");
                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');
                $updateRequestData = array(
                    'notif_status'=>2,
                    'DateApprove'=>$current_date_time,
                );
                $updateRequest = $this->attendance_model->attendance_notif_update($updateRequestData,$id);

                //$emp_id = $row['emp_id'];
                
                $time_in = $row['time_in'];
                $time_out = $row['time_out'];
                $attendance_date = $row['date'];

                $final_attendance_date = dateFormat($attendance_date);

                $date_create = date_create($time_in);
                $final_time_in = date_format($date_create, 'g:i A');

                $date_create = date_create($time_out);
                $final_time_out = date_format($date_create, 'g:i A');


                $emp_id = $row['emp_id'];
                $approver_id = $this->session->userdata('user');
                $notifType = "Update Attendance on ".$final_attendance_date." with time in ".$final_time_in." and time out ".$final_time_out."";
                //$status = $approve;
                $dateTime = getDateTime();

                $insertNotificationsData = array(
                    'attendance_notification_id'=>'',
                    'emp_id'=>$emp_id,
                    'notif_emp_id'=>$approver_id,
                    'attendance_notif_id'=>$id,
                    'attendance_ot_id'=>0,
                    'leave_id'=>0,
                    'NotifType'=>$notifType,
                    'type'=>'Disapprove Attendance',
                    'Status'=>"Disapprove",
                    'DateTime'=>$dateTime,
                    'ReadStatus'=>0,
                );
                $insertNotifications = $this->attendance_model->insert_notifications($insertNotificationsData);

                $module = "Attendance Updates List";
                $task_description = "Disapprove Attendance Updates";
                $insertAuditTrialData = array(
                    'audit_trail_id'=>'',
                    'file_emp_id'=>$emp_id,
                    'approve_emp_id'=>$approver_id,
                    'involve_emp_id'=>0,
                    'module'=>$module,
                    'task_description'=>$task_description,
                );
                $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);

                $this->data['status'] = "success";
            }
            else{
                $this->data['status'] = "error";
                $this->data['msg'] = "Your password is incorrect.";
            }
        }

        echo json_encode($this->data);
    }
    //for disapprove single attendance end

    //for approval of ot start 
    public function otApproval(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $originalPassword = $employeeInfo['Password'];

        $password = $this->input->post('password');
        $id = $this->input->post('id');
        $type = $this->input->post('type');

        $this->form_validation->set_rules('password', 'password','required');

        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please enter your password.";
        }
        else{
            if(password_verify($password, $originalPassword)){
                $row = $this->attendance_model->get_attendance_ot($id);

                date_default_timezone_set("Asia/Manila");
                //$date = date_create("1/1/1990");

                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');

                if($type == "Approve"){
                    $approve_stat = 0;
                    if ($row['head_emp_id'] == 0 || $row['approve_stat'] == 0){
                        $approve_stat = 1;
                    }
                    $updateOtData = array(
                        'approve_stat'=>$approve_stat,
                        'DateApprove'=>$current_date_time,
                    );
                    $this->attendance_model->update_attendance_overtime_by_id($id, $updateOtData);
                    $task_description = "Approve File OT";
                }
                else{
                    $updateOtData = array(
                        'approve_stat'=>2,
                        'DateApprove'=>$current_date_time,
                    );
                    $this->attendance_model->update_attendance_overtime_by_id($id, $updateOtData);
                    $task_description = "Disapprove File OT";
                }

                $emp_id = $row['emp_id'];
                $approver_id = $this->session->userdata('user');
                $time_from = $row['time_from'];
                $time_out = $row['time_out'];
                $ot_date = $row['date'];

                $final_attendance_date = dateFormat($ot_date);

                $date_create = date_create($time_from);
                $final_time_in = date_format($date_create, 'g:i A');

                $date_create = date_create($time_out);
                $final_time_out = date_format($date_create, 'g:i A');

                
                $notifType = "File Overtime on ".$final_attendance_date." from ".$final_time_in." to time out ".$final_time_out."";
                $status = $type;
                $dateTime = getDateTime();

                $insertNotificationsData = array(
                    'attendance_notification_id'=>'',
                    'emp_id'=>$emp_id,
                    'notif_emp_id'=>$approver_id,
                    'attendance_notif_id'=>0,
                    'attendance_ot_id'=>$id,
                    'leave_id'=>0,
                    'NotifType'=>$notifType,
                    'type'=>$type." OT",
                    'Status'=>$status,
                    'DateTime'=>$dateTime,
                    'ReadStatus'=>0,
                );
                $insertNotifications = $this->attendance_model->insert_notifications($insertNotificationsData);

                $module = "File Overtime List";
                $insertAuditTrialData = array(
                    'audit_trail_id'=>'',
                    'file_emp_id'=>$emp_id,
                    'approve_emp_id'=>$approver_id,
                    'involve_emp_id'=>0,
                    'module'=>$module,
                    'task_description'=>$task_description,
                );
                $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);

                $this->data['status'] = "success";
            }
            else{
                $this->data['status'] = "error";
                $this->data['msg'] = "Your password is incorrect.";
            }
        }

        echo json_encode($this->data);
    }
    //for approval of ot end

    //for upload attendance view start
    public function viewUploadAttendance(){
        $this->data['pageTitle'] = 'Upload Attendance';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('attendance/upload_attendance');
        $this->load->view('global/footer');
    }
    //for upload attendance view end

    //for upload attendance start
    public function uploadAttendance(){
        
    }
    //for upload attendance end

    

    public function printAbsentReports(){
        $dateTo = $this->input->post('dateTo');
        $dateFrom = $this->input->post('dateFrom');
        $this->load->library('excel');
        $filename = "late_attendance_list";

        $this->excel->setActiveSheetIndex(0) 
                    ->setCellValue('A1', 'Emplyee Name')
                    ->setCellValue('B1', 'Date');
        $count = 1;
        $select_qry = $this->employee_model->get_employee_for_print();
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
               $counter = 0;

                $from = $dateFrom;
                do {

                    if ($counter > 0){

                        $from = str_replace('-', '/', $from);
                        $from = date('Y-m-d',strtotime($from . "+1 days"));
                    }
                    $day = date_format(date_create($from), 'l');

                    $holiday = date_format(date_create($from), 'F j');

                    $holidayData = $this->holiday_model->get_holiday_date_all($holiday);

                    $leaveData = $this->leave_model->get_leave_for_absent_report($from,$value->bio_id);

                    $countHoliday = 0;
                    if(!empty($holidayData)){
                        $countHoliday = count($holidayData);
                    }
                    $countLeave = 0;
                    if(!empty($leaveData)){
                        $countLeave = count($leaveData);
                    }


                    if ($day != "Saturday" && $day != "Sunday" && $countHoliday == 0 && $countLeave == 0){
                        $absent = $this->attendance_model->get_leave_date($value->bio_id, $from);
                        if(empty($absent)){
                            $full_name = $value->Lastname . ", " . $value->Firstname . " " . $value->Middlename;
                            $count++;
                            $rowArray = array($full_name,$from);
                            $this->excel->getActiveSheet()
                                    ->fromArray(
                                        $rowArray,   // The data to set
                                        NULL,        // Array values with this value will not be set
                                        'A'.$count         // Top left coordinate of the worksheet range where
                                                     //    we want to set these values (default is A1)
                                    );
                        }
                    }
                    $counter++;
                }while($dateTo > $from);

            }
        }
        foreach(range('A','D') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        /*********************Autoresize column width depending upon contents END***********************/
        
        $this->excel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true); //Make heading font bold
        
        /*********************Add color to heading START**********************/
        $this->excel->getActiveSheet()
                    ->getStyle('A1:D1')
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('abb2b9');
        /*********************Add color to heading END***********************/
        
        $this->excel->getActiveSheet()->setTitle('late_attendance_list_reports'); //give title to sheet
        $this->excel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;Filename=$filename.xls");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
        exit;

        
    }
}