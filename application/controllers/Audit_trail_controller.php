<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_trail_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("audit_trial_model", 'audit_trial_model');
        $this->load->model("employee_model", "employee_model");
        // $this->load->model("payroll_model", "payroll_model");
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model('holiday_model','holiday_model');
        // $this->load->model('leave_model','leave_model');
        // $this->load->model('working_hours_model','working_hours_model');
        // $this->load->model('working_days_model','working_days_model');
        //$this->load->helper('hupay_helper');
        // $this->load->helper('attendance_helper');
        // $this->load->helper('date_helper');
        // $this->load->helper('leave_helper');
        //$this->load->library('../controllers/holiday_controller');

    }
    public function index(){
        $this->data['pageTitle'] = 'Audit Trail';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('audit_trail/audit_trail');
        $this->load->view('global/footer');
    }

    public function getAuditTrailLogs(){
        $select_qry = $this->audit_trial_model->get_audit_trail_order_by_date();
        $finalAuditTrailData = array();
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $date = date_format(date_create($value->dateCreated), 'F d, Y');
                $time = date_format(date_create($value->dateCreated), 'g:i A');
                $from = "";
                $to = "";
                if ($value->file_emp_id != '0' && $value->approve_emp_id != '0'){
                    $select_from_qry = $this->employee_model->employee_information($value->file_emp_id);

                    $from = " <b>from " .  $select_from_qry['Firstname'] . " " . $select_from_qry['Lastname'] ."</b>";

                    $select_to_qry = $this->employee_model->employee_information($value->approve_emp_id);

                    $to = "<b>".$select_to_qry['Firstname'] . " " . $select_to_qry['Lastname'] . "</b> ";
                }

                $involve = "";
                $who = "";
                if ($value->involve_emp_id != '0') {
                    $select_involve_qry = $this->employee_model->employee_information($value->involve_emp_id);

                    $involve = " <b>" .  $select_involve_qry['Firstname'] . " " . $select_involve_qry['Lastname'] ." </b>";

                    if ($value->file_emp_id != '0'){
                        $select_who_qry = $this->employee_model->employee_information($value->file_emp_id);

                        $who = " of <b>" .  $select_who_qry['Firstname'] . " " . $select_who_qry['Lastname'] ." </b>";
                    }
                }

                array_push($finalAuditTrailData, array(
                    'module'=>$value->module,
                    'description'=>$to. $involve.  $value->task_description . $from. $who,
                    'date'=>$date . " " . $time
                ));
            }
        }
        $this->data['status'] = "success";
        $this->data['finalAuditTrailData'] = $finalAuditTrailData;
        echo json_encode($this->data);
    }
}