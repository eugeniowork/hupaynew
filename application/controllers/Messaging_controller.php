<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messaging_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("messaging_model", 'messaging_model');
        $this->load->model("employee_model", 'employee_model');
        $this->load->helper('hupay_helper');
        $this->load->helper('message_helper');
        $this->load->helper('date_helper');
    }

    public function viewInbox(){
        $this->data['pageTitle'] = 'Message Inbox';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('messaging/inbox');
        $this->load->view('global/footer');
    }

    public function getInboxData(){
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);

        $select_qry = $this->messaging_model->get_messages($id);
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $date = date_format(date_create($value->dateCreated), 'm/d/Y');

                $select_emp_qr = $this->employee_model->employee_information($value->from_emp_id);
                
                $select_emp_to_qry = $this->employee_model->employee_information($value->to_emp_id);

                $toName = "You";
                if ($id != $value->to_emp_id){
                    $toName = $select_emp_to_qry['Firstname'] . " " . $select_emp_to_qry['Lastname'];
                    
                    ///if($row->to_readStatus == '0'){
                //      $read_style= "color:#2980b9";
                //  }
                }
                $fromName = "You";

                $read_style = "";
                if ($id != $value->from_emp_id){
                    $fromName = $select_emp_qr['Firstname'] . " " . $select_emp_qr['Lastname'];
                    
                    if($value->to_readStatus == '0'){
                        $read_style= "color:#2980b9";
                    }
                }

                $finalData .= "<tr id='".$value->message_id."'>";
                    $finalData .= "<td>".$date."</td>";
                    $finalData .= "<td>".$fromName."</td>";
                    $finalData .= "<td>".$toName."</td>";
                    $finalData .= "<td>".$value->subject."</td>";
                    $finalData .= "<td id='readmoreValueMemo' style='".$read_style."'>" .nl2br($value->message). "</td>";
                    $finalData .= "<td>";
                        $finalData .= "<button id=".$value->message_id." class='open-message-history btn btn-sm btn-link' data-target='#inboxModal' data-toggle='modal'><i id=".$value->message_id." class='fas fa-eye' ></i>&nbsp;Read</button>";
                    $finalData .= "</td>";

                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    public function getMessageHistory(){
        $id = $this->input->post('id');
        $row = $this->messaging_model->get_message_per_user($id);
        $finalData = array();
        if(!empty($row)){
            $my_id = $this->session->userdata('user');
            $own_message = 0;
            if ($row['from_emp_id'] == $my_id){
                $own_message = 1;
            }
            if($own_message == 0){
                $updateMesageData = array(
                    'to_readStatus'=>1
                );
            }
            else{
                $updateMesageData = array(
                    'from_readStatus'=>1
                );
            }
            $updateMessage = $this->messaging_model->read_message($id, $updateMesageData);

            $row_emp = $this->employee_model->employee_information($row['from_emp_id']);

            $profilePath = $row_emp['ProfilePath'];
            $from_name = $row_emp['Firstname'] . " " . $row_emp['Lastname'];

            $date = date_format(date_create($row['dateCreated']), 'F d, Y');
            $time = date_format(date_create($row['dateCreated']), 'g:i A');

            array_push($finalData, array(
                'subject'=>$row['subject'],
                'profilePath'=>$profilePath,
                'from_name'=>$from_name,
                'message'=>$row['message'],
                'date'=>$date,
                'time'=>$time,
                'reply'=>getReplyMessages($id),
            ));
            $this->data['status'] = "success";
            $this->data['finalData'] = $finalData;
        }
        else{
            $this->data['error'];
        }
        
        echo json_encode($this->data);
    }

    //for add reply start
    public function addReply(){
        $id = $this->input->post('id');
        $message = $this->input->post('message');

        $row = $this->messaging_model->get_message_per_user($id);
        $from = $this->session->userdata('user');
        if(!empty($row)){
            $own_message = 0;
            if ($row['from_emp_id'] == $from){
                $own_message = 1;
            }
            if($own_message == 0){
                $updateMesageData = array(
                    'to_readStatus'=>0
                );
            }
            else{
                $updateMesageData = array(
                    'from_readStatus'=>0
                );
            }
            $updateMessage = $this->messaging_model->read_message($id, $updateMesageData);

            $to = $row['from_emp_id']; // ung nagpadala ng message
            $dateCreated = getDateTime();

            $insertData = array(
                'message_reply_id'=>'',
                'message_id'=>$id,
                'from_emp_id'=>$from,
                'to_emp_id'=>$to,
                'reply'=>$message,
                'dateCreated'=>$dateCreated
            );
            $insert = $this->messaging_model->insert_reply($insertData);
            $this->data['status'] = "success";
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }
    //for add reply end
}