<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        // if(!$this->session->userdata('user')){
        //     redirect('login');
        // }
		$this->load->model("company_model", 'company_model');
	}
    public function getAllCompanyForDropdown(){
        $company = $this->company_model->get_all_company_for_dropdown();
        $this->data['company'] = $company;
        echo json_encode($this->data);
    }

    public function getAllCompanyForSelectDropDown(){
        $company = $this->company_model->get_all_company_for_dropdown();
        $finalData = "";
        if(!empty($company)){
            foreach ($company as $value) {
                $finalData .= "<option value='".$value->company_id."'>".$value->company."</option>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
}