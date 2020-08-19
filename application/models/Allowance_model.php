<?php
class Allowance_model extends CI_Model{
    public function get_info_allowance($id){
        $query = $this->db->get_where('tb_emp_allowance',array('emp_id'=>$id));
        return $query->result();
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
}