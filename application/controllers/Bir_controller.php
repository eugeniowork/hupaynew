<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bir_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("bir_model", 'bir_model');
        $this->load->model("employee_model", 'employee_model');
        $this->load->helper('hupay_helper');
    }

    public function index(){
        $this->data['pageTitle'] = 'BIR Contribution Table';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('gov_table/bir_contribution');
        $this->load->view('global/footer');
    }

    public function getBirContribution(){
        $select_qry = $this->bir_model->get_all_bir_contribution();
        $finalData = "";
        $id = $this->session->userdata('user');
        $empInformation = $this->employee_model->employee_information($id);
        $role = $empInformation['role_id'];
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $finalData .= "<tr id='".$value->bir_contrib_id."'>";
                    $finalData .= "<td>" . $value->Status ."</td>";
                    $finalData .= "<td>" . moneyConvertion($value->amount) ."</td>";
                    $finalData .= "<td>" .moneyConvertion($value->Contribution)."</td>";
                    $finalData .= "<td>" .$value->percentage."%</td>";
                    $finalData .= "<td>";
                             if ($role == 1){
                                $finalData .= "<button class='btn btn-sm btn-outline-success'>Edit</button>&nbsp;";
                                $finalData .= "<button class='btn btn-sm btn-outline-danger'>Delete</button>";
                             }
                             else {
                                $finalData .= "No action";
                             }
                    $finalData .= "</td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }
}