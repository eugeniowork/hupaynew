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
        $this->load->model("employee_model", 'employee_model');
        $this->load->helper('hupay_helper');
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
                        $finalData .= "<tr id=".$value->position_id.">"; 
                            $finalData .= "<td>" .$value->Position."</td>";
                            $finalData .= "<td>" . $department_val."</td>";
                            $finalData .= "<td>";
                                if ($role == 1) {
                                    $finalData .= "<button class='btn btn-sm btn-outline-success'>Edit</button>&nbsp;";
                                    $finalData .= "<button class='btn btn-sm btn-outline-danger'>Delete</button>";
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
}