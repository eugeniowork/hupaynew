<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("position_model", 'position_model');
        $this->load->model("department_model", 'department_model');
        $this->load->model("employee_model", 'employee_model');

        $this->load->model("audit_trial_model", 'audit_trial_model');
        $this->load->helper('hupay_helper');
    }

    public function index(){
        $this->data['pageTitle'] = 'Department List';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('department/department_list');
        $this->load->view('global/footer');
    }
    public function getDepartmentList(){
        $select_qry = $this->department_model->get_all_department();
        $finalData = "";
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);
        $role = $employeeInfo['role_id'];
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $num_rows = $this->position_model->get_department_in_position($value->dept_id);
                if(empty($num_rows)){
                    $finalData .= "<tr class=".'department-tr-'.$value->dept_id.">";
                        $finalData .= "<td class='department-name-".$value->dept_id."'>" .$value->Department ."</td>";
                        $finalData .= "<td>";
                            // for admin only
                            if ($role == 1){
                                $finalData .= "<button id=".$value->dept_id." class='update-department btn btn-sm btn-outline-success' data-target='#updateDepartmentModal' data-toggle='modal'>Edit</button>&nbsp;";
                                $finalData .= "<button id=".$value->dept_id." class='delete-department btn btn-sm btn-outline-danger'>Delete</button>";
                            }
                            else {
                                $finalData .= "No actions";
                            }
                        $finalData .= "</td>";
                    $finalData .= "</tr>";
                }
                else{
                    $finalData .= "<tr id=".$value->dept_id.">";
                        $finalData .= "<td>" .$value->Department ."</td>";
                        $finalData .= "<td>";
                            // for adming only can edit but cannot delete
                            if ($role == 1){
                                $finalData .= "<button id=".$value->dept_id." class='update-department btn btn-sm btn-outline-success' data-target='#updateDepartmentModal' data-toggle='modal'>Edit</button>&nbsp;";
                            }
                            else {
                                $finalData .= "No actions";
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

    public function getDepartListForDropdown(){
        $select_qry = $this->department_model->get_all_department();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $finalData .= "<option value='".$value->dept_id."'>";
                    $finalData .= $value->Department;
                $finalData .= "</option>";
            }
        }
        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function addDepartment(){
        $emp_id = $this->session->userdata('user');
        $name = $this->input->post('name');
        $this->form_validation->set_rules('name','name','required|is_unique[tb_department.Department]',array(
            'required'=>'Please enter a department.',
            'is_unique'=>'Department already exist.',
        ));
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = validation_errors();
        }
        else{
            $insertData = array(
                'department'=>ucwords($name),
            );
            $insert = $this->department_model->insert_department($insertData);
            $dateTime = getDateTime();
            $module = "Department";
            $insertAuditTrialData = array(
                'audit_trail_id'=>'',
                'file_emp_id'=>0,
                'approve_emp_id'=>0,
                'involve_emp_id'=>$emp_id,
                'module'=>$module,
                'task_description'=>"Add department <strong>".$name."</strong>",
            );
            $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);

            $this->data['status'] = "success";
            $this->data['msg'] = "The department <strong>".$name."</strong> was successfully added.";
            //asd
        }

        echo json_encode($this->data);
    }

    public function getUpdateInfo(){
        $id = $this->input->post('id');
        $department = $this->department_model->get_department($id);
        if(!empty($department)){
            $this->data['department'] = $department['Department'];
            $this->data['status'] = "success";
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }
    public function updateDepartment(){
        $emp_id = $this->session->userdata('user');
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $checkDepartment = $this->department_model->get_department($id);
        if(!empty($checkDepartment)){
            if($name != $checkDepartment['Department']){
                $this->form_validation->set_rules('name','name','is_unique[tb_department.Department]',array(
                    'is_unique'=>'Department already exist.',
                ));
            }
            $this->form_validation->set_rules('name','name','required',array(
                'required'=>'Please enter a department.'
            ));
            if($this->form_validation->run() == FALSE){
                $this->data['status'] = "error";
                $this->data['msg'] = validation_errors();
            }
            else{
                $updateData = array(
                    'Department'=>$name,
                );
                $update = $this->department_model->update_department($id,$updateData);
                $dateTime = getDateTime();
                $module = "Department";
                $insertAuditTrialData = array(
                    'audit_trail_id'=>'',
                    'file_emp_id'=>0,
                    'approve_emp_id'=>0,
                    'involve_emp_id'=>$emp_id,
                    'module'=>$module,
                    'task_description'=>"Update department from <strong>".$checkDepartment['Department']."</strong> to <strong>".$name."</strong>",
                );
                $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);

                $this->data['status'] = "success";
                $this->data['msg'] = "Department <strong>".$checkDepartment['Department']."</strong> was successfully updated to <strong>".$name."</strong>.";

            }
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }

    public function removeDepartment(){
        $id = $this->input->post('id');
        $emp_id = $this->session->userdata('user');
        $department = $this->department_model->get_department($id);
        if(!empty($department)){
            $delete = $this->department_model->delete_department($id);
            $dateTime = getDateTime();
            $module = "Department";
            $insertAuditTrialData = array(
                'audit_trail_id'=>'',
                'file_emp_id'=>0,
                'approve_emp_id'=>0,
                'involve_emp_id'=>$emp_id,
                'module'=>$module,
                'task_description'=>"Delete department <strong>".$department['Department']."</strong>",
            );
            $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
            $this->data['status'] = "success";
            $this->data['msg'] = "Department <strong>".$department['Department']."</strong> was successfully deleted.";
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }
}