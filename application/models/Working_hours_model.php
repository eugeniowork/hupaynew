<?php
class Working_hours_model extends CI_Model{
    public function get_info_working_hours($id){
        $query = $this->db->get_where('tb_working_hours',array('working_hours_id'=>$id));
        return $query->row_array();
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }

    public function get_all_working_hours(){
    	$query = $this->db->get('tb_working_hours');
        return $query->result();
    }
}