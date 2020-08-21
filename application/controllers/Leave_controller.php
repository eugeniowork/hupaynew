<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("leave_model", 'leave_model');
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
}