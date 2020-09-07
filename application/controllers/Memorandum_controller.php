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
        $this->load->model("audit_trial_model", 'audit_trial_model');
        $this->load->helper('hupay_helper');
        $this->load->helper('memorandum_helper');
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
                    $finalData .= "<tr class='memo-tr-".$value->memo_id."'>";
                        $finalData .= "<td class='memo-subject-".$value->memo_id."'><small>".$value->Subject ."</small></td>";
                        $finalData .= "<td><small>".$recipient."</small></td>";
                        $finalData .= "<td><small>".$date."</small></td>";
                        $finalData .= "<td id=''><small>".nl2br($value->Content)."</small></td>";
                        $finalData .= "<td><small>";
                            $finalData .= "<button id=".$value->memo_id." class='open-edit-memo btn btn-sm btn-outline-success' data-toggle='modal' data-target='#editMemoModal'><i class='fas fa-pencil-alt' id=".$value->memo_id."></i></button>&nbsp;";
                            $finalData .= "<button id=".$value->memo_id." class='delete-memo btn btn-sm btn-outline-danger'><i id=".$value->memo_id." class='fas fa-trash-alt'></i></button>&nbsp;";
                            $finalData .= "<button class='btn btn-sm btn-outline-secondary'><i class='fas fa-print'></i></button>&nbsp;";
                        
                            if ($count != 0){
                                $finalData .= "<button id=".$value->memo_id." class='view-memo-image btn btn-sm btn-outline-primary' data-target='#memoImagesModal' data-toggle='modal'><i class='fas fa-images' id=".$value->memo_id."></i></button>&nbsp;";
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

    public function getUpdateMemoInfo(){
        $id = $this->input->post('id');

        $emp_id = $this->session->userdata('user');

        $memo = $this->memorandum_model->get_memorandum($id);
        $finalData = array();
        if(!empty($memo)){
            $disabled = false;
            $row = $this->memorandum_model->get_memorandum_data($id);

            $countMultipleMemo = 0;
            $multipleMemo = $this->memorandum_model->get_multiple_memo_recipient($id);
            if(!empty($multipleMemo)){
                $countMultipleMemo = count($multipleMemo);
            }
            if($countMultipleMemo == 1){
                $disabled = true;
            }
            $row_emp = $this->employee_model->employee_information($emp_id);
            $fullName = $row_emp['Lastname'] . ', ' . $row_emp['Firstname'] . ' ' . $row_emp['Middlename'];
            if ($row_emp['Middlename'] == ""){
                $fullName = $row_emp['Lastname'] . ', ' . $row_emp['Firstname'];
            }

            array_push($finalData, array(
                'disabled'=>$disabled,
                'from'=>$fullName,
                'subject'=>$row['Subject'],
                'content'=>htmlspecialchars($row['Content']),
                'recipient'=>getRecipientMultipleMemo($id),
                'memoRecipientCount'=>$countMultipleMemo,

            ));
            $this->data['status'] = "success";
            $this->data['finalData'] = $finalData;

        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }


    //for delete memo start
    public function deleteMemo(){
        $id = $this->input->post('id');
        $row = $this->memorandum_model->get_memorandum_data($id);
        if(!empty($row)){
            $subject = $row['Subject'];
            $this->memorandum_model->delete_memo_single($id);
            $this->memorandum_model->delete_memo_multiple($id);
            $this->memorandum_model->delete_memo_notif($id);

            $module = "Memorandum";
            $task_description = "Delete memorandum about <strong>".$subject."</strong>";
            $insertAuditTrialData = array(
                'audit_trail_id'=>'',
                'file_emp_id'=>0,
                'approve_emp_id'=>0,
                'involve_emp_id'=>$this->session->userdata('user'),
                'module'=>$module,
                'task_description'=>$task_description,
            );
            $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
            $this->data['status'] = "success";
            $this->data['msg'] = "Memorandum of <strong>".$subject." was successfully deleted.</strong> ";
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }
    //for delete memo end

    //get memo images start
    public function getMemoImages(){
        $id = $this->input->post('id');
        $images = "";
        $memo = $this->memorandum_model->get_memorandum_data($id);
        if(!empty($memo)){

            $memoImage = $this->memorandum_model->get_memo_image($id);
            if(!empty($memoImage)){
                foreach ($memoImage as $value) {
                    $images .= '<div class="image-memo-'.$value->memo_img_id.'">
                            <button type="button" id='.$value->memo_img_id.' class="remove-image-btn btn btn-danger pull-right btn-sm"><i id='.$value->memo_img_id.' class="fas fa-times"></i></button>
                            <img class="img-thumbnail"
                             src="'.base_url().'assets/images/'.$value->image_path.'"
                             alt="Another alt text">
                        </div>';
                }
            }

            $this->data['images'] = $images;
            $this->data['subject'] = $memo['Subject'];
            $this->data['status'] = "success";
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }

    //get memo images end

    //remove memo images start
    public function removeMemoImage(){
        $removeArray = $this->input->post('ids');
        // $removeArray = trim($removeArray,"[]");

        // $remove_array = explode(",", $removeArray);

        // $remove_array_count = count($remove_array);
        // $counter = 0;
        // do {
        //     $row_memo_img = $this->memorandum_model->get_memo_image_data(trim($remove_array[$counter],'"'));

        //     unlink(base_url().'assets/images/'.$row_memo_img['image_path']);

        //     $remove = $this->memorandum_model->delete_memo_image(trim($remove_array[$counter],'"'));


        //     $counter++;
        // }while($remove_array_count > $counter);
        foreach ($removeArray as $key => $value) {
            $row_memo_img = $this->memorandum_model->get_memo_image_data($value);
            unlink('assets/images/'.$row_memo_img['image_path']);
            $remove = $this->memorandum_model->delete_memo_image($value);
        }
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    //remove memo images end
}