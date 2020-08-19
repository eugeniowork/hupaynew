<?php
class Cut_off_model extends CI_Model{
    public function get_cut_off(){
        $query = $this->db->get('tb_cut_off');
        return $query->result();
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
}