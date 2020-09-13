<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Position_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("position_model", 'position_model');
        $this->load->model("department_model", 'department_model');
        $this->load->model("audit_trial_model", 'audit_trial_model');
        $this->load->model("employee_model", 'employee_model');
        $this->load->helper('hupay_helper');
        $this->load->helper('date_helper');
    }

    public function index(){
        $this->data['pageTitle'] = 'Position List';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('position/position_list');
        $this->load->view('global/footer');
    }

    public function getPositionList(){
        $select_qry = $this->position_model->get_all_position();
        $finalData = "";
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);
        $role = $employeeInfo['role_id'];
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $select_dept = $this->department_model->get_department($value->dept_id);

                $department_val = $select_dept['Department'];
                $num_rows = $this->employee_model->get_position_of_employee($value->position_id);
                if($value->position_id != 1){
                    if(empty($num_rows)){
                        $finalData .= "<tr class='position-tr-".$value->position_id."'>"; 
                            $finalData .= "<td class='position-name-".$value->position_id."' >" .$value->Position."</td>";
                            $finalData .= "<td>" . $department_val."</td>";
                            $finalData .= "<td>";
                                if ($role == 1) {
                                    $finalData .= "<button class='btn btn-sm btn-outline-success'>Edit</button>&nbsp;";
                                    $finalData .= "<button id=".$value->position_id." class='delete-position btn btn-sm btn-outline-danger'>Delete</button>";
                                }
                                else {
                                    $finalData .= "No Action";
                                }
                            $finalData .= "</td>";
                        $finalData .= "</tr>";
                    }
                    else{
                        $finalData .= "<tr id=".$value->position_id.">"; 
                            $finalData .= "<td>" .$value->Position."</td>";
                            $finalData .= "<td>" . $department_val."</td>";
                            $finalData .= "<td>";
                                if ($role == 1) {
                                    $finalData .= "<button class='btn btn-sm btn-outline-success'>Edit</button>&nbsp;";
                                }
                                else {
                                    $finalData .= "No Action";
                                }
                            $finalData .= "</td>";
                        $finalData .= "</tr>";
                    }
                }
            }
        }

        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }

    public function getPositionListForDropDown(){
        $id = $this->input->post('id');
        $select_qry = $this->position_model->get_all_position_in_department($id);
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $selected = "";
                if ($value->position_id != 1) {
                    $finalData .= "<option value='".$value->position_id."'>";
                        $finalData .= $value->Position;
                    $finalData .= "</option>";
                }
            }
        }
        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }
    public function addPosition(){
        $positionName = $this->input->post('positionName');
        $departmentId = $this->input->post('department');

        $emp_id = $this->session->userdata('user');

        $this->form_validation->set_rules('positionName','name','required', array(
            'required'=>"Please enter a position name."
        ));
        $this->form_validation->set_rules('department','name','required', array(
            'required'=>"Please select a department."
        ));

        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = validation_errors();
        }
        else{
            

            $department = $this->department_model->get_department($departmentId);
            if(!empty($department)){
                $position = $this->position_model->check_position($departmentId,$positionName);
                if(!empty($position)){
                    $this->data['status'] = "error";
                    $this->data['msg'] = "Position <strong>".ucwords($positionName)."</strong> in department <strong>".$department['Department']."</strong> already exist.";
                }
                else{
                    $positionName = ucwords($positionName);
                    $insertData = array(
                        'dept_id'=>$departmentId,
                        'Position'=>$positionName,
                        'DateCreated' => getDateDate()
                    );
                    $insert = $this->position_model->insert_position($insertData);

                    $dateTime = getDateTime();
                    $module = "Position";
                    $insertAuditTrialData = array(
                        'audit_trail_id'=>'',
                        'file_emp_id'=>0,
                        'approve_emp_id'=>0,
                        'involve_emp_id'=>$emp_id,
                        'module'=>$module,
                        'task_description'=>"Add position <strong>".$positionName."</strong>",
                    );
                    $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);

                    $this->data['status'] = "success";
                    $this->data['msg'] = "Position <strong>".$positionName."</strong> was successfully added.";
                }
            }
            else{
                $this->data['status'] = "error";
                $this->data['msg'] = "There was a problem on the selected department, please try again.";
            }

        }



        echo json_encode($this->data);
    }

    public function removePosition(){
        $id = $this->input->post('id');
        $position = $this->position_model->get_employee_position($id);
        $emp_id = $this->session->userdata('user');
        if(!empty($position)){
            $delete = $this->position_model->delete_position($id);

            $dateTime = getDateTime();
            $module = "Position";
            $insertAuditTrialData = array(
                'audit_trail_id'=>'',
                'file_emp_id'=>0,
                'approve_emp_id'=>0,
                'involve_emp_id'=>$emp_id,
                'module'=>$module,
                'task_description'=>"Delete position <strong>".$position['Position']."</strong>",
            );
            $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);

            $this->data['status'] = "success";
            $this->data['msg'] = "Position <strong>".$position['Position']."</strong> was successfully removed.";
        }
        else{
            $this->data['status'] = "error";
        }
        echo json_encode($this->data);
    }
}