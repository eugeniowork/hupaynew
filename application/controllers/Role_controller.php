<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("role_model", 'role_model');
        $this->load->model("employee_model", 'employee_model');
        //$this->load->helper('hupay_helper');
    }
    public function getAllRole(){
    	$select_qry = $this->role_model->get_all_role();
    	$emp_id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($emp_id);
        $role = $employeeInfo['role_id'];
        $finalData = "";
    	if(!empty($select_qry)){
    		foreach ($select_qry as $value) {
    			if($value->role_value != "Admin"){
    				if($role){
    					// if Admin so lahat pde nya icreate na role maliban sa admin
    					if($role == 1){
    						$finalData .= "<option value='".$value->role_id."'>";
								$finalData .= $value->role_value;
							$finalData .= "</option>";
    					}
    					// for HR and Payroll admin pde ding magcreate ng employee pero dapat user lang pde nyang icr8
    					if($role == 2 || $role == 3){
    						if ($value->role_id == 4) {
								$finalData .= "<option value='".$value->role_id."' selected='selected'>"; // auto select para ok na mismo
									$finalData .= $value->role_value;
								$finalData .= "</option>";
							}
    					}

    				}
    			}
    		}
    	}

    	$this->data['status'] = "success";
    	$this->data['finalData'] = $finalData;
    	echo json_encode($this->data);
    }
}

?>