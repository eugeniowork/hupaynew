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
                            $finalData .= "<button class='btn btn-sm btn-outline-success'>Update</button>";
                            $finalData .= "<button class='btn btn-sm btn-outline-primary'>Create</button>";
                        $finalData .= "</td>";
                    $finalData .= "</tr>";
                }
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
}