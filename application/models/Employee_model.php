<?php
class Employee_model extends CI_Model{
    public function employee_information($id){
        $query = $this->db->get_where('tb_employee_info',array('emp_id'=>$id));
        return $query->row_array();
    }
    public function get_employee_by_bio_id($id){
        $query = $this->db->get_where('tb_employee_info',array('bio_id'=>$id));
        return $query->num_rows();
    }
    public function get_employee_by_bio_id_data($id){
        $query = $this->db->get_where('tb_employee_info',array('bio_id'=>$id));
        return $query->row_array();
    }
    public function get_employee_by_role(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $where = '(role_id ="1" or role_id ="2")';
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_employee_by_role_one(){
        $query = $this->db->get_where('tb_employee_info',array('role_id'=>1));
        return $query->result();
    }

    public function check_if_head($id){
        $query = $this->db->get_where('tb_employee_info',array('head_emp_id'=>$id));
        return $query->row_array();
    }
    public function get_employee_atm(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $this->db->where('withAtm', 1);
        $this->db->order_by('Lastname', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function check_atm_no($emp_id, $atmAccountNumber){
        $query = $this->db->get_where('tb_employee_info',array('emp_id'=>$emp_id, 'atmAccountNumber'=>$atmAccountNumber));
        return $query->row_array();

    }
    public function update_atm_no($emp_id,$data){
        $this->db->trans_start();
        $this->db->where('emp_id',$emp_id);
        $this->db->update('tb_employee_info',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function get_working_days_of_employee($working_days_id){
        $query = $this->db->get_where('tb_employee_info',array('working_days_id'=>$working_days_id));
        return $query->result();
    }
    public function get_active_employee(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $this->db->where('ActiveStatus', 1);
        $where = '(role_id !="1" or dept_id !="1")';
        $this->db->where($where);
        $this->db->order_by('Lastname', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_active_employee_row_array(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $this->db->where('ActiveStatus', 1);
        $where = '(role_id !="1" or dept_id !="1")';
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_employee_with_cut_off($emp_id, $cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_info',array('emp_id'=>$emp_id, 'CutOffPeriod'=>$cutOffPeriod));
        return $query->result();
    }
    public function get_employee_with_cut_off_row_array($emp_id, $cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_info',array('emp_id'=>$emp_id, 'CutOffPeriod'=>$cutOffPeriod));
        return $query->row_array();
    }
    
}