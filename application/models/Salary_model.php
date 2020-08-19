<?php
class Salary_model extends CI_Model{
    public function check_if_has_salary($id){
        $query = $this->db->get_where('tb_salary_loan',array('emp_id'=>$id, 'remainingBalance !='=>0));
        return $query->num_rows();
    }
    public function get_info_salary($id){
        $query = $this->db->get_where('tb_salary_loan',array('emp_id'=>$id));
        return $query->result();
    }
}