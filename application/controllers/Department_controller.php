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
                    $finalData .= "<tr id=".$value->dept_id.">";
                        $finalData .= "<td>" .$value->Department ."</td>";
                        $finalData .= "<td>";
                            // for admin only
                            if ($role == 1){
                                $finalData .= "<button class='btn btn-sm btn-outline-success'>Edit</button>&nbsp;";
                                $finalData .= "<button class='btn btn-sm btn-outline-danger'>Delete</button>";
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
                                $finalData .= "<button class='btn btn-sm btn-outline-success'>Edit</button>&nbsp;";
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
}