<?php
class Holiday_model extends CI_Model{
    public function get_cut_off(){
        $query = $this->db->get('tb_cut_off');
        return $query->result();
    }
    public function get_holiday(){
        $query = $this->db->get('tb_holiday');
        return $query->result();
    }
    public function get_holiday_date($holiday){
        $query = $this->db->get_where('tb_holiday', array('holiday_date'=>$holiday));
        return $query->row_array();
    }
    public function get_holiday_date_rows($holiday){
        $query = $this->db->get_where('tb_holiday', array('holiday_date'=>$holiday));
        return $query->num_rows();
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
}