<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagibig_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("pagibig_model", 'pagibig_model');
        $this->load->model("employee_model", 'employee_model');
        $this->load->helper('hupay_helper');
    }

    public function index(){
        $this->data['pageTitle'] = 'Pagibig Contribution Table';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('gov_table/pagibig_contribution');
        $this->load->view('global/footer');
    }

    public function getPagibigContribution(){
        $select_qry = $this->pagibig_model->get_all_pagibig_contribution();
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);
        $role = $employeeInfo['role_id'];
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                if (moneyConvertion($value->compensationFrom) == "over") {
                    $compensationFrom = "Php 0.00";
                }
                else {
                    $compensationFrom = "Php. ".moneyConvertion($value->compensationFrom);
                }
                $finalData .= "<tr id='".$value->pagibig_contrib_id."'>";
                    $finalData .= "<td>" .$compensationFrom. " - Php. ". moneyConvertion($value->compensationTo) ."</td>";
                    $finalData .= "<td>Php. " .moneyConvertion($value->Contribution)."</td>";
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