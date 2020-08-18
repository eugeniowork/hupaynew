<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("dashboard_model", 'dashboard_model');
    }

    public function index(){
        $this->data['pageTitle'] = 'Dashboard';
        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('dashboard/dashboard');
        $this->load->view('global/footer');
    }

    public function logout(){
        if($this->session->userdata('user')){
            session_destroy();
            $this->session->unset_userdata('user');
        }
        
        redirect('');
    }
}