<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if($this->session->userdata('user')){
            redirect('dashboard');
        }
        $this->load->model("login_model", 'login_model');
        $this->load->model("company_model",'company_model');
	}
    public function index(){
        $version = "2020 | LFC HR & Payroll System | V2.5.4";
        $this->data['pageTitle'] = 'Login';
        $this->data['version'] = $version;
        $this->load->view('global/header', $this->data);
        $this->load->view('auth/login',$this->data);
        $this->load->view('global/footer');
    }
    public function validateForgotPasswordCodeandUsername(){
        $forgotUsername = $this->input->post('forgotUsername');
        $forgotCode = $this->input->post('forgotCode');
        $errorMsg = "Please enter ";
        $this->form_validation->set_rules('forgotUsername','forgotUsername','required', array('required'=>$errorMsg.'your username.'));
        $this->form_validation->set_rules('forgotCode','forgotCode','required', array("required"=>$errorMsg."the code."));
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            // $this->data['msg'] = "Please fill up all fields first!";
            $this->data['msg'] = validation_errors();
        }
        else{
            $check = $this->login_model->validate_forgot_password_code_and_username($forgotUsername, $forgotCode);
            if($check == "nouser"){
                $this->data['status'] = "error";
                $this->data['msg'] = "Username does not exist!";
            }
            else if($check == "nocode"){
                $this->data['status'] = "error";
                $this->data['msg'] = "No set generated code for forgot password, contact administrator to set generated code.";
            }
            else if($check == "errorcode"){
                $this->data['status'] = "error";
                $this->data['msg'] = "Generated code does not match!";
            }
            else{
                $this->data['emp_id'] = $check;
                $this->data['status'] = "success";
            }
        }
        echo json_encode($this->data);
    }
    public function validateChangePassword(){
        $empId = $this->input->post('empId');
        $newPassword = $this->input->post('new-password');
        $confirmPassword = $this->input->post('confirm-password');
        $errorMsg = "Please enter ";
        $this->form_validation->set_rules('newPassword','newPassword','min_length[8]|required', 
            array(
                'required'=>$errorMsg.'your new password.',
                'min_length'=>'Password must be atleast 8 characters in length.'
            ));
        $this->form_validation->set_rules('confirmPassword','confirmPassword','required|matches[newPassword]',
            array('required'=>'Please confirm your password.', 'matches'=>"Password does not match.")
        );
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = validation_errors();
        }
        else{
            $newData = array(
                'Password'=>password_hash($newPassword, PASSWORD_DEFAULT),
                'generated_code'=>"",
            );
            $update = $this->login_model->validate_forgot_password($empId, $newData);
            if($update == "success"){
                $this->data['msg'] = "Your password has been changed.";
            }
            else{
                $this->data['msg'] = "There was a problem updating your password, please try again!";
            }
            $this->data['status'] = $update;
            
        }
        echo json_encode($this->data);
    }
    public function validateLogin(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $companyId = $this->input->post('companyId');
        $errorMsg = "Please enter ";
        $this->form_validation->set_rules('username','username','required', array('required'=>$errorMsg.'your username.'));
        $this->form_validation->set_rules('password','password','required', array('required'=>$errorMsg.'your password.'));
        $this->form_validation->set_rules('companyId','companyId','required', array('required'=>'Please select a company.'));
        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $this->data['msg'] = validation_errors();
        }
        else{
            $check = $this->login_model->validate_login($username,$password);
            $this->data['asd'] = $check;
            if($check){
                if($check['ActiveStatus'] == 0){
                    $this->data['status'] = "error";
                    $this->data['msg'] = "Your account is inactive, you are not permitted to access this account!";
                }
                else{
                    $company = $this->company_model->get_company_info($companyId);
                    if($company){
                        if($check['company_id'] != $companyId){
                            $this->data['status'] = "error";
                            $this->data['msg'] = 'You are not '.$company['company'].' employee.';
                        }
                        else{
                            $this->session->set_userdata('user',$check['emp_id']);
                            $this->data['status'] = "success";
                        }
                    }
                    else{
                        $this->data['status'] = "error";
                        $this->data['msg'] = "There was a problem, please try again.";
                        
                    }
                }
            }
            else{
                $this->data['status'] = "error";
                $this->data['msg'] = "Invalid username or password.";
            }
            
        }
        echo json_encode($this->data);
    }
}

