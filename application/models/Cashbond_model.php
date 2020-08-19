<?php
class Cashbond_model extends CI_Model{
    public function get_info_simkimban($id){
        $query = $this->db->get_where('tb_cashbond',array('emp_id'=>$id));
        return $query->row_array();
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
}