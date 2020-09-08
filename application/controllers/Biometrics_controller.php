<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biometrics_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("position_model", 'position_model');
        $this->load->model("employee_model", 'employee_model');
        $this->load->model("department_model", 'department_model');
        $this->load->model("attendance_model", 'attendance_model');
        $this->load->helper('hupay_helper');
    }

    public function index(){
        $this->data['pageTitle'] = 'Biometrics Registration';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('biometrics/biometrics');
        $this->load->view('global/footer');
    }

    public function getBiometrics(){
        $select_qry = $this->employee_model->get_all_employee_for_bio();
        $finalData = "";
        if (!empty($select_qry)) {
            foreach ($select_qry as $value) {
                $select_position_qry = $this->position_model->get_employee_position($value->position_id);
                $position_val = $select_position_qry['Position'];

                $select_dept_qry = $this->department_model->get_department($value->dept_id);
                $dept_val = $select_dept_qry['Department'];
                $bio_id = $value->bio_id;
                if ($bio_id == 0){
                    $bio_id = "No Bio Id Yet";
                }
                if ($value->role_id != 1){
                    $finalData .= "<tr id=".$value->emp_id.">";
                        $finalData .= "<td>".$bio_id. "</td>";
                        $finalData .= "<td>".$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname. "</td>";
                        $finalData .= "<td>".$dept_val."</td>";
                        $finalData .= "<td>".$position_val."</td>";
                        $finalData .= "<td>";
                            $finalData .= "<button id=".$value->emp_id." class='update-bio btn btn-sm btn-outline-success' data-toggle='modal' data-target='#updateBio'>Update</button>";
                            $finalData .= "<button id=".$value->emp_id." class='create-bio btn btn-sm btn-outline-primary' data-toggle='modal' data-target='#updateBio'>Create</button>";
                        $finalData .= "</td>";
                    $finalData .= "</tr>";
                }
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function getUpdateBioInfo(){
        $id = $this->input->post('id');
        $emp = $this->employee_model->employee_information($id);
        if(!empty($emp)){

            $this->data['bio'] = $emp['bio_id'];
            $this->data['status'] = "success";
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }

    public function updateBio(){
        $id = $this->input->post('id');
        $bio = $this->input->post('bio');
        $this->form_validation->set_rules('bio','biometrics','required|is_unique[tb_employee_info.bio_id]', array(
            'required'=>"Please enter a biometrics id.",
            'is_unique'=>"Biometrics ID was already assigned to other employee."
        ));
        if($bio == 0){
            $this->data['status'] = "error";
            $this->data['msg'] = "Biometrics ID '0' is not a valid Biometrics ID.";
        }
        else{


            if($this->form_validation->run() == FALSE){
                $this->data['status'] = "error";
                $this->data['msg'] = validation_errors();
            }
            else{
                
                $emp = $this->employee_model->employee_information($id);
                $old_bio_id = $emp['bio_id'];

                $update = array(
                    'no_bio'=>1,
                    'bio_id'=>$bio,
                );
                $update = $this->employee_model->update_employee_info($id,$update);

                $updateAttendanceBioData = array(
                    'bio_id'=>$bio
                );
                $updateAttendanceBio = $this->attendance_model->update_attendance_using_bio($old_bio_id, $updateAttendanceBioData);

                $this->data['status'] = "success";
                $this->data['msg'] = "Employee Biometrics ID was successfully updated/ registered.";
            }
        }

        echo json_encode($this->data);
    }
}