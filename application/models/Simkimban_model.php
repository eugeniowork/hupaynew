<?php
class Simkimban_model extends CI_Model{
    public function check_if_has_simkimban($id){
        $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'remainingBalance !='=>0, 'status' => 1));
        return $query->num_rows();
    }
    public function get_info_simkimban($id){
        $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
        return $query->result();
    }
}