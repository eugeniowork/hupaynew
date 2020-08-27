<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cut_off_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("cut_off_model", 'cut_off_model');
        //$this->load->library('../controllers/holiday_controller');

    }
    public function getAllCutOffPeriod(){
        $select_qry = $this->cut_off_model->get_cut_off();
        $cuttOffList = array();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $date_from = date_format(date_create($value->dateFrom),'F d');
				$date_to = date_format(date_create($value->dateTo),'F d');
				array_push($cuttOffList, array('dateFrom'=> $date_from, 'dateTo' => $date_to));
            }
        }
        $this->data['status'] = "success";
        $this->data['cutOffList'] = $cuttOffList;

        echo json_encode($this->data);
    }
}