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
}