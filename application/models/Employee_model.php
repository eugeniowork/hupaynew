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
    // public function get_employee_by_bio_id_data($id){
    //     $query = $this->db->get_where('tb_employee_info',array('bio_id'=>$id));
    //     return $query->row_array();
    // }
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
}