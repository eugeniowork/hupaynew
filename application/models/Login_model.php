<?php
class Login_model extends CI_Model{
    public function validate_forgot_password_code_and_username($forgotUsername, $forgotCode){
        $this->db->where('Username', $forgotUsername);
        $query = $this->db->get('tb_employee_info');
        $row = $query->row_array();
        if($row){
            if($row['generated_code'] == ""){
                return 'nocode';
            }
            else{
                if($row['generated_code'] != $forgotCode){
                    return 'errorcode';
                }
                else{
                    return $row['emp_id'];
                }
            }
        }
        else{
            return 'nouser';
        }
    }
    public function validate_forgot_password($id,$data){
        $this->db->trans_start();
        $this->db->where('emp_id',$id);
        $this->db->update('tb_employee_info',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function validate_login($username,$password){
        $this->db->where('Username',$username);
        $query = $this->db->get('tb_employee_info');
        $row = $query->row_array();
        if($row){
            if(password_verify($password, $row['Password'])){
                return $row;
            }
            else{
                return false;
            }
        }
        else{
            return null;
        }
    }
}