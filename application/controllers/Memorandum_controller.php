<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memorandum_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("memorandum_model", 'memorandum_model');
        $this->load->model("employee_model", 'employee_model');
        $this->load->model("department_model", 'department_model');
        $this->load->helper('hupay_helper');
    }

    public function index(){
        $this->data['pageTitle'] = 'Memorandum';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('memorandum/memorandum');
        $this->load->view('global/footer');
    }

    public function getListOfMemorandum(){
        $select_qry = $this->memorandum_model->get_all_memorandum();
        $finalData = "";
        $emp_id = $this->session->userdata('user');
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $recipient = "";

                $select_multiple_memo_qry = $this->memorandum_model->get_multiple_memo($value->memo_id);
                if(!empty($select_multiple_memo_qry)){
                    foreach ($select_multiple_memo_qry as $valueMultipleMemo) {
                        if($recipient == ""){
                            if($valueMultipleMemo->recipient == "All"){
                                $recipient = "All";
                            }
                            if($valueMultipleMemo->recipient == "Specific Employee"){
                                $select_emp_qry = $this->employee_model->employee_information($valueMultipleMemo->emp_id);
                                $recipient = $select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];
                            }
                            if($valueMultipleMemo->recipient == "Department"){
                                $select_dept_qry = $this->department_model->get_department($valueMultipleMemo->dept_id);
                                $recipient = $select_dept_qry['Department'];
                            }
                        }
                        else{
                            if($valueMultipleMemo->recipient == "Specific Employee"){
                                $select_emp_qry = $this->employee_model->employee_information($valueMultipleMemo->emp_id);
                                $recipient =$recipient ." , " .$select_emp_qry['Lastname'] . ", " . $select_emp_qry['Firstname'] . " " . $select_emp_qry['Middlename'];
                            }
                            if($valueMultipleMemo->recipient == "Department"){
                                $select_dept_qry = $this->department_model->get_department($valueMultipleMemo->dept_id);
                                $recipient =  $recipient . " , " . $select_dept_qry['Department'];
                            }
                        }
                    }
                }
                $date_create = date_create($value->DateCreated);
                $date = date_format($date_create, 'F d, Y');


                if ($emp_id !== 21){
                    $memoImg = $this->memorandum_model->get_memo_image($value->memo_id);
                    $count = 0;
                    if(!empty($memoImg)){
                        $count = count($memoImg);
                    }
                    $finalData .= "<tr id='".$value->memo_id."'>";
                        $finalData .= "<td><small>".$value->Subject ."</small></td>";
                        $finalData .= "<td><small>".$recipient."</small></td>";
                        $finalData .= "<td><small>".$date."</small></td>";
                        $finalData .= "<td id=''><small>".nl2br($value->Content)."</small></td>";
                        $finalData .= "<td><small>";
                            $finalData .= "<button class='btn btn-sm btn-outline-success'><i class='fas fa-pencil-alt'></i></button>&nbsp;";
                            $finalData .= "<button class='btn btn-sm btn-outline-danger'><i class='fas fa-trash-alt'></i></button>&nbsp;";
                            $finalData .= "<button class='btn btn-sm btn-outline-secondary'><i class='fas fa-print'></i></button>&nbsp;";
                        
                            if ($count != 0){
                                $finalData .= "<button class='btn btn-sm btn-outline-primary'><i class='fas fa-images'></i></button>&nbsp;";
                            }
                            $finalData .= "<button class='btn btn-sm btn-outline-success'><i class='fas fa-paperclip'></i></button>&nbsp;";
                        $finalData .= "</td></small>";

                    $finalData .= "</tr>";
                }
                else{
                    $finalData .= "<tr id='".$value->memo_id."'>";
                        $finalData .= "<td>".$value->Subject ."</td>";
                        $finalData .= "<td>".$recipient."</td>";
                        $finalData .= "<td>".$date."</td>";
                        $finalData .= "<td id=''>".nl2br($value->Content)."</td>";
                        $finalData .= "<td>";
                            //echo "<div style='cursor:pointer;float:left;' id='edit_memorandum' class='action-a'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span></div>";
                            //echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
                            //echo "<a href='#' id='delete_memorandum' class='action-a'><span class='glyphicon glyphicon-trash' style='color:#515a5a'></span></a>";
                            //echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
                            $finalData .= "<button class='btn btn-sm btn-outline-secondary'><i class='fas fa-print'></i></button>&nbsp;";
                        
                            /*if ($this->checkExistMemoImg($row->memo_id) != 0){
                                echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
                                echo "<a href='#' id='view_memo_img' class='action-a'><span class='glyphicon glyphicon-picture' style='color: #2980b9 '></span></a>";
                            }*/
                            //echo "<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
                            //echo "<a href='#' id='add_memo_img' class='action-a'><span class='glyphicon glyphicon-paperclip' style='color:#2c3e50'></span></a>";
                        $finalData .= "</td>";

                    $finalData .= "</tr>";
                }
            }
        }


        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }
}