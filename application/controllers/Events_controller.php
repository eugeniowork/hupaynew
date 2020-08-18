<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("events_model", 'events_model');
        $this->load->model("employee_model", 'employee_model');
    }

    public function getEvents(){
        //$events = $this->events_model->get_join_events();
        $events = $this->events_model->get_events();
        
        $this->data['events'] = $events;
        echo json_encode($this->data);
    }
}