<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("employee_model", 'employee_model');
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model("payroll_model", "payroll_model");
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model('holiday_model','holiday_model');
        // $this->load->model('leave_model','leave_model');
        // $this->load->model('working_hours_model','working_hours_model');
        // $this->load->model('working_days_model','working_days_model');
        // $this->load->helper('hupay_helper');
        // $this->load->helper('attendance_helper');
        // $this->load->helper('date_helper');
        // $this->load->helper('leave_helper');
        //$this->load->library('../controllers/holiday_controller');

    }
    public function viewATMAccounts(){
        $this->data['pageTitle'] = 'ATM Account No';
        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('atm_account/atm_account_no',$this->data);
        $this->load->view('global/footer');
    }
    public function getAtmAccountNoList(){
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);

        $employeeAtm = $this->employee_model->get_employee_atm();
        $finalListOfEmployeeAtm = array();
        if(!empty($employeeAtm)){
            foreach($employeeAtm as $value){
                $empName = ucwords($value->Lastname.", ".$value->Firstname." ".$value->Middlename);
                if($value->Middlename == ""){
                    $empName = ucwords($value->Lastname." ".$value->Firstname);
                }
                if($id != 21){
                    array_push($finalListOfEmployeeAtm, array(
                        'emp_id'=>$value->emp_id,
                        'emp_name'=>$empName,
                        'atmAccountNumber'=>$value->atmAccountNumber,
                        'action'=>'yes',
                    ));
                }
                else{
                    array_push($finalListOfEmployeeAtm, array(
                        'emp_id'=>$value->emp_id,
                        'emp_name'=>$empName,
                        'atmAccountNumber'=>$value->atmAccountNumber,
                        'action'=>'no',
                    ));
                }
            }
            $this->data['status'] = "success";
            $this->data['finalListOfEmployeeAtm'] = $finalListOfEmployeeAtm;
        }
        else{
            $this->data['status'] = "error";
        }
        echo json_encode($this->data);
    }
    public function getInformationOfAtmAccount(){
        $id = $this->input->post('id');
        $employeeInfo = $this->employee_model->employee_information($id);
        if(!empty($employeeInfo)){
            $this->data['status'] = "success";
            $this->data['atmAccountNumber'] = $employeeInfo['atmAccountNumber'];
        }
        else{
            $this->data['status'] ="error";
            $this->data['msg'] = "There was a problem, please try again.";
        }

        echo json_encode($this->data);
    }
    public function updateAtmAccountNo(){
        $id = $this->input->post('id');
        $atmAccountNo = $this->input->post('atmAccountNo');

        if($id != "" || $id != null){
            $minmaxMsg = "Please enter a 12 digit account number.";
            $this->form_validation->set_rules('atmAccountNo', 'atmAccountNo', 'required|min_length[12]|max_length[12]',
            array('required'=>'Please enter a atm account number.', 'min_length'=>$minmaxMsg, 'max_length'=>$minmaxMsg));
            if($this->form_validation->run() == FALSE){
                $this->data['status'] = "error";
                $this->data['msg'] = validation_errors();
            }
            else{
                $checkAtmNo = $this->employee_model->check_atm_no($id, $atmAccountNo);
                if(!empty($checkAtmNo)){
                    $this->data['status'] = "error";
                    $this->data['msg'] = "No changes, no updates were taken.";
                }
                else{
                    $updateAtmNoData = array('atmAccountNumber'=>$atmAccountNo);
                    $updateAtmNo = $this->employee_model->update_atm_no($id, $updateAtmNoData);
                    if($updateAtmNo == "success"){
                        $this->data['msg'] = "ATM Account No was successfully updated.";
                    }
                    else{
                        $this->data['status'] = "error";
                        $this->data['msg'] = "There was a problem updating the ATM No, please try again.";
                    }
                    $this->data['status'] = $updateAtmNo;
                }
            }
        }
        else{
            $this->data['status'] = "error";
            $this->data['msg'] = "There was a problem updating the ATM No, please try again.";
        }
        echo json_encode($this->data);
        
    }
    public function printAtmAccountreports(){
        $this->load->library('excel');
        $filename = "atm_account_number_reports";
		/*********************Add column headings START**********************/
		$this->excel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Employee Name')
                    ->setCellValue('B1', 'ATM Account Number');
        $count = 1;
        $atmAccounts = $this->employee_model->get_employee_atm();
        if(!empty($atmAccounts)){
            foreach($atmAccounts as $value){
                $empName = ucwords($value->Lastname.", ".$value->Firstname." ".$value->Middlename);
                $count++;
                $this->excel->setActiveSheetIndex(0) 
                    ->setCellValue('A'.$count, $empName)
                    ->setCellValue('B'.$count, $value->atmAccountNumber);
            }
        }
        foreach(range('A','B') as $columnID){
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $this->excel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()
            ->getStyle('A1:B1')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('abb2b9');
        $this->excel->getActiveSheet()->setTitle('atm_account_number_reports'); //give title to sheet
        $this->excel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}