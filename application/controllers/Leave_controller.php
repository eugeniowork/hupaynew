<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("leave_model", 'leave_model');
        $this->load->model("employee_model", 'employee_model');
        $this->load->model("attendance_model", 'attendance_model');
        $this->load->model("audit_trial_model", 'audit_trial_model');
        $this->load->helper('leave_helper');
        $this->load->helper('hupay_helper');
    }
    public function getTypesOfLeave(){
        $leaveId = $this->input->post('leaveId');
        $status = $this->input->post('status');

        $options = array();
        if($status == "Add"){
            $leave = $this->leave_model->get_type_of_leave_status_one();
            if(!empty($leave)){
                foreach($leave as $value){
                    array_push($options, array(
                        'lt_id'=>$value->lt_id,
                        'name'=>$value->name,
                    ));
                }
                $this->data['status'] = "success";
                $this->data['leaveOptions'] = $options;
            }
            else{
                $this->data['status'] = "error";
                $this->data['msg'] = "There was a problem, please try again.";
            }
        }
        echo json_encode($this->data);
    }
    //for leave  start
    public function index(){
        $this->data['pageTitle'] = 'Leave List';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('leaves/leave');
        $this->load->view('global/footer');
    }

    public function getRequestList(){
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);
        $role = $employeeInfo['role_id'];
        $finalData = "";
        if($role == 4 || $role == 3){
            $select_qry  = $this->leave_model->get_leave_info_by_head($id);
        }
        else{
            $select_qry  = $this->leave_model->get_leave_info_by_employee($id);
        }
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $select_emp_qry =$this->employee_model->employee_information($value->emp_id);

                $fullName = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];

                $date_create = date_create($value->dateFrom);
                $dateFrom = date_format($date_create, 'F d, Y');

                $date_create = date_create($value->DateCreated);
                $dateFile = date_format($date_create, 'F d, Y');

                $date_create = date_create($value->dateTo);
                $dateTo = date_format($date_create, 'F d, Y');

                $dateRange = $dateFrom . " - " . $dateTo;
                if ($value->approveStat != 2 && $value->approveStat != 1) {

                    $finalData .= "<tr class='leave-approval-".$value->leave_id."'>";
                        $finalData .= "<td>" .$fullName ."</td>";
                        $finalData .= "<td>" .$dateFile ."</td>";
                        $finalData .= "<td>" .$dateRange ."</td>";
                        $finalData .= "<td>" .$value->LeaveType ."</td>";
                        $finalData .= "<td>" .$value->FileLeaveType ."</td>";
                        $finalData .= "<td id='readmoreValue'>" . nl2br(htmlspecialchars($value->Remarks)) ."</td>";
                        $finalData .= "<td>";


                        if ($id != 21){

                                $finalData .= "<button id=".$value->leave_id." class='for-approve-leave-btn btn btn-sm btn-outline-success' data-toggle='modal' data-target='#approveLeaveModal'>Approve</button>";
                                $finalData .= "<button id=".$value->leave_id." class='for-disapprove-leave-btn btn btn-sm btn-outline-danger' data-toggle='modal' data-target='#disapproveLeaveModal'>Disapprove</button>";
                            $finalData .= "</td>";
                        }
                        else {
                            $finalData .= "No action";
                        }
                    $finalData .= "</tr>";
                }
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function getListHistory(){
        $select_qry = $this->leave_model->get_leave_list_history();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $select_emp_qry =$this->employee_model->employee_information($value->emp_id);

                $fullName = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];

                $date_create = date_create($select_emp_qry['DateHired']);
                $dateHired = date_format($date_create, 'F d, Y');

                $date_create = date_create($value->dateFrom);
                $dateFrom = date_format($date_create, 'F d, Y');

                $date_create = date_create($value->dateTo);
                $dateTo = date_format($date_create, 'F d, Y');

                $dateRange = $dateFrom . " - " . $dateTo;


                $date_create = date_create($select_emp_qry['DateHired']);
                $anniv_day = date_format($date_create, 'd');
                $anniv_month = date_format($date_create, 'm');


                date_default_timezone_set("Asia/Manila");
                $dates = date("Y-m-d H:i:s");
                $date = date_create($dates);
                $current_date_time = date_format($date, 'Y-m-d');

                $date_create = date_create($select_emp_qry['DateHired']);
                $year = date("Y");
                $anniversary = date_format($date_create, 'm-d');
                $anniversary_1 = date_format(date_create($year."-".$anniversary), 'Y-m-d');

                $year_prev = $year - 1;
                $year_next = $year + 1;

                $anniv_from = date_format(date_create($year_prev."-".$anniversary), 'Y-m-d');
                $anniv_to = date_format(date_create($year."-".$anniv_month . "-".$anniv_day), 'Y-m-d');

                if ($current_date_time > $anniversary_1){
                    $anniv_from = date_format(date_create($year."-".$anniversary), 'Y-m-d');
                    $anniv_to = date_format(date_create($year_next."-".$anniversary), 'Y-m-d');
                }


                if ($anniv_day == 1){
                    $anniv_to = date("Y-m-d",strtotime($anniv_to) - (86400));
                }
                if ($value->dateFrom >= $anniv_from && $value->dateTo <= $anniv_to){

                    $status = "Approve";
                    if ($value->approveStat == "2"){
                        $status = "Disapprove";
                    }


                    $finalData .= "<tr id='".$value->leave_id."'>";
                        $finalData .= "<td>" .$fullName ."</td>";
                        $finalData .= "<td>" .$dateHired ."</td>";
                        $finalData .= "<td>" .$dateRange ."</td>";
                        $finalData .= "<td>" .$value->LeaveType ."</td>";
                        $finalData .= "<td>" .$value->FileLeaveType ."</td>";
                        $finalData .= "<td id='readmoreValue'>" . nl2br(htmlspecialchars($value->Remarks)) ."</td>";
                        $finalData .= "<td>".$status."</td>";
                    $finalData .= "</tr>";
                }
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = 'success';
        echo json_encode($this->data);
    }

    public function getEmployeeLeaveList(){
        $select_qry = $this->employee_model->get_active_employee();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $name = $value->Lastname . ", " . $value->Firstname . " " . $value->Middlename;
                if ($value->Middlename == ""){
                    $name = $value->Lastname . ", " . $value->Firstname;
                }

                $dateHired =date_format(date_create($value->DateHired), 'F d, Y');

                $finalData .= "<tr id='".$value->emp_id."'>";
                    $finalData .= "<td>" .$name. "</td>";
                    $finalData .= "<td>".$dateHired."</td>";
                    $finalData .= "<td>";
                        $finalData .= "<table class='table table-bordered table-dark'>";
                        $finalData .= "<thead>";
                          $finalData .= "<tr>";
                            $finalData .= "<th class='color-white bg-color-gray'><small>Leave Type</small></th>";
                            $finalData .= "<th class='color-white bg-color-gray'><small>Leave Count</small></th>";
                          $finalData .= "</tr>";
                        $finalData .= "</thead>";
                        $finalData .= "<tbody>";
                            $finalData.= getEmpLeaveCount($value->emp_id);
                         
                        $finalData .= "</tbody>";
                      $finalData .= "</table>";
                    $finalData .="</td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    //for leave  end


    //for leave maintenance start
    public function viewLeaveMaintenance(){
        $this->data['pageTitle'] = 'Leave Maintenance';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('leaves/leave_maintenance');
        $this->load->view('global/footer');
    }
    public function getLeaveMaintenance(){
        $select_qry = $this->leave_model->get_all_type_of_leave();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $info = "";
                $row_lv = $this->leave_model->get_leave_lidation_data($value->lv_id);
                $info .= "<b class='color-blue' >Validation:</b> " . $row_lv['name'] . "&nbsp;<a class='protip'  style='color:#337ab7;cursor:pointer;' data-pt-title='".$row_lv['information']."' data-pt-scheme='blue' data-pt-position='top'><i class='fas fa-question-circle'></i></a>";
                // $info .= "<b class='color-blue'>Validation:</b> " . $row_lv['name'] . "&nbsp;<a data-toggle='popover' id='hover_info' title='Validation Information' data-content='".$row_lv['information']."' style='color:#337ab7;cursor:pointer;'><i class='fas fa-question-circle'></i></a>";
                $info .= "<br/>";

                if ($value->no_days_to_file != 0){
                    $info .= "<b class='color-blue'>Set Days: </b>" . $value->no_days_to_file;
                    $info .= "<br/>";


                }

                $info .= "<b class='color-blue'>Leave Count:</b> " . $value->count;


                $status = "<span class='badge badge-success'>Active</span>";

                $status_txt = "Inactive";

                if ($value->status == 0){
                    $status = "<span class='badge badge-warning'>Inactive</span>";
                    $status_txt = "Activate";
                }

                $is_convertable_to_cash = "Yes";
                if ($value->is_convertable_to_cash == 0){
                    $is_convertable_to_cash = "No";
                }

                $info .= "<br/>";
                $info .= "<b class='color-blue'>Convertable To Cash: </b>" . $is_convertable_to_cash;

                $finalData .= "<tr id='".$value->lt_id."'>";
                    $finalData .= "<td><b>".$value->name."</b></td>";
                    $finalData .= "<td>".$info."</td>";
                    $finalData .= "<td>".$status."</td>";
                    $finalData .= "<td>";

                        if ($value->name != "Formal Leave"){
                            $finalData .= "<button class='btn btn-outline-success btn-sm' >Edit</button>";
                            $finalData .= "&nbsp;";
                            $finalData .= "<button class='btn btn-outline-primary btn-sm' >".$status_txt."</button>";
                            $finalData .= "&nbsp;";

                            // check tb_emp_leave

                            if (checkExistEmpLeaveInfo($value->lt_id) == 0){

                                $finalData .= "<button class='btn btn-outline-danger btn-sm' >Delete</button>";
                            }

                        }
                        else {
                            $finalData .= "No Action";
                        }
                    $finalData .= "</td>";
                $finalData .= "</tr>";
            }
        }


        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }


    //for leave maintenance end

    //for approve leave start
    public function approveLeave(){

        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $originalPassword = $employeeInfo['Password'];
        
        $id = $this->input->post('id');
        $row = $this->leave_model->get_leave_by_id($id);
        $password = $this->input->post('password');
        $this->form_validation->set_rules('password', 'password','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please enter your password.";
        }
        else{
            if(password_verify($password, $originalPassword)){
                
                $approveStat = 0;
                $approveDate = getDateDate();
                if ($row['head_emp_id'] == 0 || $row['approveStat'] == 0){
                    $approveStat = 1;
                }
                $updateData = array(
                    'approveStat'=>$approveStat,
                    'dateApprove'=>$approveDate,
                );
                $update = $this->leave_model->update_leave_data($id,$updateData);


                $emp_id = $row['emp_id'];
                $approver_id = $emp_id;
                $dateFrom = $row['dateFrom'];
                $dateTo = $row['dateTo'];
                $leaveType = $row['LeaveType'];

                $date_create = date_create($dateFrom);
                $final_date_from = date_format($date_create, 'F d, Y');

                $date_create = date_create($dateTo);
                $final_date_to = date_format($date_create, 'F d, Y');

                
                $notifType = "File ".$leaveType." from ".$final_date_from." to ".$final_date_to;
                $status = "Approve";
                $dateTime = getDateTime();

                $insertNotificationsData = array(
                    'attendance_notification_id'=>'',
                    'emp_id'=>$emp_id,
                    'notif_emp_id'=>$approver_id,
                    'attendance_notif_id'=>'0',
                    'attendance_ot_id'=>0,
                    'leave_id'=>$id,
                    'NotifType'=>$notifType,
                    'type'=>'Approve Leave',
                    'Status'=>$status,
                    'DateTime'=>$dateTime,
                    'ReadStatus'=>0,
                );
                $insertNotifications = $this->attendance_model->insert_notifications($insertNotificationsData);

                    
                $module = "Leave Request List";
                $task_description = "Approve " . $leaveType . ", " . $row['FileLeaveType'];
                $dateTime = getDateTime();
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
    //for approve leave end

    //for disapprove leave start
    public function disapproveLeave(){
        $emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $originalPassword = $employeeInfo['Password'];
        
        $id = $this->input->post('id');
        $row = $this->leave_model->get_leave_by_id($id);
        $password = $this->input->post('password');
        $this->form_validation->set_rules('password', 'password','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please enter your password.";
        }
        else{
            if(password_verify($password, $originalPassword)){
                $disapproveDate = getDateDate();
                $updateData = array(
                    'approveStat'=>2,
                    'dateApprove'=>$disapproveDate,
                );
                $update = $this->leave_model->update_leave_data($id,$updateData);


                $emp_id = $row['emp_id'];
                $approver_id = $emp_id;
                $dateFrom = $row['dateFrom'];
                $dateTo = $row['dateTo'];
                $leaveType = $row['LeaveType'];

                $date_create = date_create($dateFrom);
                $final_date_from = date_format($date_create, 'F d, Y');

                $date_create = date_create($dateTo);
                $final_date_to = date_format($date_create, 'F d, Y');

                
                $notifType = "File ".$leaveType." from ".$final_date_from." to ".$final_date_to;
                $status = "Disapprove";
                $dateTime = getDateTime();

                $insertNotificationsData = array(
                    'attendance_notification_id'=>'',
                    'emp_id'=>$emp_id,
                    'notif_emp_id'=>$approver_id,
                    'attendance_notif_id'=>'0',
                    'attendance_ot_id'=>0,
                    'leave_id'=>$id,
                    'NotifType'=>$notifType,
                    'type'=>'Disapprove Leave',
                    'Status'=>$status,
                    'DateTime'=>$dateTime,
                    'ReadStatus'=>0,
                );
                $insertNotifications = $this->attendance_model->insert_notifications($insertNotificationsData);

                    
                $module = "Leave Request List";
                $task_description = "Disapprove " . $leaveType . ", " . $row['FileLeaveType'];
                $dateTime = getDateTime();
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
    //for disapprove leave end
}