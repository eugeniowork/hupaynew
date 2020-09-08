<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Working_hours_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("working_hours_model", 'working_hours_model');
        //$this->load->model("working_days_model",'working_days_model');
        //$this->load->model("employee_model",'employee_model');
        //$this->load->model("holiday_model",'holiday_model');
        $this->load->model("audit_trial_model", "audit_trial_model");
        $this->load->helper('hupay_helper');
        $this->load->helper('date_helper');
	}
    public function getWorkingHoursDropDown(){
        $select_qry = $this->working_hours_model->get_all_working_hours();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $timeFrom = date_format(date_create($value->timeFrom), 'g:i A');
                $timeTo = date_format(date_create($value->timeTo), 'g:i A');

                $working_hours = $timeFrom . "-" . $timeTo;

                $finalData .= "<option value='".$value->working_hours_id."'>".$working_hours ."</option>";
            }
        }

        $this->data['status'] = "success";
        $this->data['finalData'] = $finalData;
        echo json_encode($this->data);
    }
    public function deleteWorkingHours(){
        $id = $this->input->post('id');
        $emp_id = $this->session->userdata('user');
        $row = $this->working_hours_model->get_info_working_hours($id);
        if(!empty($row)){
            $working_hours = $row['timeFrom'] . "-" . $row['timeTo'];
            $delete = $this->working_hours_model->delete_working_hours($id);

            $dateTime = getDateTime();
            $module = "Working Hours";
            $insertAuditTrialData = array(
                'audit_trail_id'=>'',
                'file_emp_id'=>0,
                'approve_emp_id'=>0,
                'involve_emp_id'=>$emp_id,
                'module'=>$module,
                'task_description'=>"Delete working hours of <strong>".$working_hours."</strong>",
            );
            $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
            $this->data['status'] = "success";
            $this->data['msg'] = '<strong>Working Hours</strong> of <strong>'.$working_hours.'</strong> was successfully deleted.';
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }

    public function addWorkingHours(){
        $timeInH = $this->input->post('timeInH');
        $timeInM = $this->input->post('timeInM');
        $timeInS = $this->input->post('timeInS');
        $timeOutH = $this->input->post('timeOutH');
        $timeOutM = $this->input->post('timeOutM');
        $timeOutS = $this->input->post('timeOutS');
        $emp_id = $this->session->userdata('user');
        $this->form_validation->set_rules('timeInH', 'timeInH','required');
        $this->form_validation->set_rules('timeInM', 'timeInM','required');
        $this->form_validation->set_rules('timeInS', 'timeInS','required');
        $this->form_validation->set_rules('timeOutH', 'timeOutH','required');
        $this->form_validation->set_rules('timeOutM', 'timeOutM','required');
        $this->form_validation->set_rules('timeOutS', 'timeOutS','required');
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please fill-up all the fields.";
        }
        else{
            $timeFrom = date_create($timeInH . ":" . $timeInM . ":" . $timeInS);
            $final_timeFrom = date_format($timeFrom, 'H:i:s');

            $timeTo = date_create($timeOutH . ":" . $timeOutM . ":" . $timeOutS);
            $final_timeTo = date_format($timeTo, 'H:i:s');

            $checkHours = $this->working_hours_model->check_working_hours($final_timeFrom, $final_timeTo);
            $difference = strtotime($final_timeTo) - strtotime($final_timeFrom);
            if ($timeFrom >= $timeTo){
                $this->data['status'] = "error";
                $this->data['msg'] = "<strong>Time From</strong> must not be greater than or equal to <strong>Time To</strong>.";
            }

            // ibig sabihin , di siya 8 hours and above
            else if ($difference < 28800){
                $this->data['status'] = "error";
                $this->data['msg'] = "Time range must 8 hours and above.";
            }
            else if(!empty($checkHours)){
                $this->data['status'] = "error";
                $this->data['msg'] = "The workings hours of <strong>".$final_timeFrom." - ".$final_timeTo."</strong> already exist.";
            }
            else{
                $dateCreated = getDateDate();
                $insertData = array(
                    'working_hours_id'=>'',
                    'timeFrom'=>$final_timeFrom,
                    'timeTo'=>$final_timeTo,
                    'dateCreated'=>$dateCreated
                );
                $insert = $this->working_hours_model->insert_working_hours($insertData);

                $dateTime = getDateTime();
                $module = "Working Hours";
                $insertAuditTrialData = array(
                    'audit_trail_id'=>'',
                    'file_emp_id'=>0,
                    'approve_emp_id'=>0,
                    'involve_emp_id'=>$emp_id,
                    'module'=>$module,
                    'task_description'=>"Add working days of <strong>".$final_timeFrom." - ".$final_timeTo."</strong>",
                );
                $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
                $this->data['status'] = "success";
                $this->data['msg'] = "Working hours of <strong>".$final_timeFrom." - ". $final_timeTo."</strong> was successfully added.";
            }
        }

        echo json_encode($this->data);
    }

    public function viewUpdateWorkingHours(){
        $id = $this->input->post('id');
        $row = $this->working_hours_model->get_info_working_hours($id);
        $finalData = array();
        if(!empty($row)){

            $timeFrom = explode(":",$row['timeFrom']);
            $timeTo = explode(":",$row['timeTo']);
            array_push($finalData, array(
                'timeInH'=>$timeFrom[0],
                'timeInM'=>$timeFrom[1],
                'timeOutH'=>$timeTo[0],
                'timeOutM'=>$timeTo[1],
            ));
            $this->data['finalData'] = $finalData;
            $this->data['status'] = "success";
        }
        else{
            $this->data['status'] = "error";
        }

        echo json_encode($this->data);
    }
    public function updateWorkingHours(){
        $id = $this->input->post('id');
        $emp_id = $this->session->userdata('user');
        $timeInH = $this->input->post('timeInH');
        $timeInM = $this->input->post('timeInM');
        $timeInS = $this->input->post('timeInS');
        $timeOutH = $this->input->post('timeOutH');
        $timeOutM = $this->input->post('timeOutM');
        $timeOutS = $this->input->post('timeOutS');
        $emp_id = $this->session->userdata('user');
        $this->form_validation->set_rules('timeInH', 'timeInH','required');
        $this->form_validation->set_rules('timeInM', 'timeInM','required');
        $this->form_validation->set_rules('timeInS', 'timeInS','required');
        $this->form_validation->set_rules('timeOutH', 'timeOutH','required');
        $this->form_validation->set_rules('timeOutM', 'timeOutM','required');
        $this->form_validation->set_rules('timeOutS', 'timeOutS','required');


        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = "Please fill-up all the fields.";
        }
        else{
            $timeFrom = date_create($timeInH . ":" . $timeInM . ":" . $timeInS);
            $final_timeFrom = date_format($timeFrom, 'H:i:s');

            $timeTo = date_create($timeOutH . ":" . $timeOutM . ":" . $timeOutS);
            $final_timeTo = date_format($timeTo, 'H:i:s');


            $difference = strtotime($final_timeTo) - strtotime($final_timeFrom);
            $row = $this->working_hours_model->get_info_working_hours($id);
            if(!empty($row)){
                $old_time_from = $row['timeFrom'];
                $old_time_to = $row['timeTo'];
                $checkHours = $this->working_hours_model->check_working_hours_own($final_timeFrom, $final_timeTo,$id);
                if ($timeFrom >= $timeTo){
                    $this->data['status'] = "error";
                    $this->data['msg'] = "<strong>Time From</strong> must not be greater than or equal to <strong>Time To</strong>.";
                }

                // ibig sabihin , di siya 8 hours and above
                else if ($difference < 28800){
                    $this->data['status'] = "error";
                    $this->data['msg'] = "Time range must 8 hours and above.";
                }
                else if(!empty($checkHours)){
                    $this->data['status'] = "error";
                    $this->data['msg'] = "The workings hours of <strong>".$final_timeFrom." - ".$final_timeTo."</strong> already exist.";
                }
                else{
                    $updateData = array(
                        'timeFrom'=>$final_timeFrom,
                        'timeTo'=>$final_timeTo,
                    );
                    $update = $this->working_hours_model->update_working_days($id,$updateData);

                    $dateTime = getDateTime();
                    $module = "Working Hours";
                    $insertAuditTrialData = array(
                        'audit_trail_id'=>'',
                        'file_emp_id'=>0,
                        'approve_emp_id'=>0,
                        'involve_emp_id'=>$emp_id,
                        'module'=>$module,
                        'task_description'=>"Update working hours from <strong>".$old_time_from." - ".$old_time_to."</strong> to <strong>".$final_timeFrom." - ".$final_timeTo. "</strong> ",
                    );
                    $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
                    $this->data['status'] = "success";
                    $this->data['msg'] = "Working hours was successfully updated.";
                }
            }
            else{
                $this->data['status'] = "erorr";
                $this->data['msg'] = "There was a problem updating the working hours, please try again.";

            }
        }

        echo json_encode($this->data);
    }
}