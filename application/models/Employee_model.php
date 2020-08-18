<?php
class Employee_model extends CI_Model{
    public function employee_information($id){
        $query = $this->db->get_where('tb_employee_info',array('emp_id'=>$id));
        return $query->row_array();
    }
}