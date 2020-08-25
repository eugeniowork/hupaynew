<?php
class Deduction_model extends CI_Model{
    public function get_yearly_deduction($emp_id){
        $query = $this->db->get_where('tb_year_total_deduction', array('emp_id' => $emp_id));
        return $query->row_array();
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
}