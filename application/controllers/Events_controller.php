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
        $this->load->helper('hupay_helper');
    }

    public function getEvents(){
        //$events = $this->events_model->get_join_events();
        $events = $this->events_model->get_events();
        
        $this->data['events'] = $events;
        echo json_encode($this->data);
    }

    public function index(){
        $this->data['pageTitle'] = 'Events';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('events/events');
        $this->load->view('global/footer');
    }

    public function getEventsList(){
        $user_id = $this->session->userdata('user');

        $select_qry = $this->events_model->get_events_list();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $events_date = $value->events_date;
                if ($events_date == "0000-00-00"){
                    $events_date = "No date";
                }
                else {
                    $events_date = dateFormat($value->events_date);
                }
                if ($user_id != 21){

                    $finalData .= "<tr id='".$value->events_id."'>";
                        $finalData .= "<td id='readmoreValue' width='45%'>".nl2br($value->events_value)."</td>"; 
                        $finalData .= "<td width='16%'>".$value->events_title."</td>";
                        $finalData .= "<td width='13%'>".$events_date."</td>";
                        $finalData .= "<td width='13%'>".dateFormat($value->dateTimeCreated)."</td>";
                        $finalData .= "<td width='15%'>";
                            $finalData .= "<button class='btn btn-outline-success btn-sm'>Approve</button>";
                            $finalData .= "<button class='btn btn-outline-danger btn-sm'>Disapprove</button>";
                        $finalData .= "</td>";
                    $finalData .= "</tr>";
                }
                else{
                    $finalData .= "<tr id='".$value->events_id."'>";
                        $finalData .= "<td id='readmoreValue' width='45%'>".nl2br($value->events_value)."</td>"; 
                        $finalData .= "<td width='16%'>".$value->events_title."</td>";
                        $finalData .= "<td width='13%'>".$events_date."</td>";
                        $finalData .= "<td width='13%'>".dateFormat($value->dateTimeCreated)."</td>";
                        $finalData .= "<td width='15%'>";
                            //echo "<a href='#' id='edit_events' class='action-a'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</a>";
                            //echo "<span> | </span>";
                            //echo "<a href='#' id='delete_events' class='action-a'><span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> Delete</a>";
                        $finalData .= "No action";
                        $finalData .= "</td>";
                    $finalData .= "</tr>";
                }
            }
        }
        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
}