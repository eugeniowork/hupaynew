<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_controller extends CI_Controller{
    function __construct(){
		parent::__construct();

		$this->load->model("company_model", 'company_model');
	}
    public function getAllCompanyForDropdown(){
        $company = $this->company_model->get_all_company_for_dropdown();
        $this->data['company'] = $company;
        echo json_encode($this->data);
    }
}