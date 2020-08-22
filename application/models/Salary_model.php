<?php
class Salary_model extends CI_Model{
    public function get_cut_off(){
        $query = $this->db->get('tb_cut_off');
        return $query->result();
    }
    public function check_if_has_salary($id){
        $query = $this->db->get_where('tb_salary_loan',array('emp_id'=>$id, 'remainingBalance !='=>0));
        return $query->num_rows();
    }
    public function get_info_salary($id){
        $query = $this->db->get_where('tb_salary_loan',array('emp_id'=>$id));
        return $query->result();
    }
    public function get_increate_cut_off_on_employee($emp_id){
        $query = $this->db->get_where('tb_increase_salary',array('emp_id'=>$emp_id));
        return $query->row_array();
    }
}