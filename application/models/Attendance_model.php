<?php
class Attendance_model extends CI_Model{
    public function get_cut_off(){
        $query = $this->db->get('tb_cut_off');
        return $query->result();
    }
    public function attendance_info($id, $date){
        $query = $this->db->get_where('tb_attendance', array('bio_id'=>$id, 'date'=>$date));
        return $query->num_rows();
    }
    public function attendance_info_all($id){
        $this->db->select('*');
        $this->db->from('tb_attendance');
        $this->db->where('bio_id',$id);
        $this->db->order_by('date', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    // public function get_info_working_hours($id){
    //     $query = $this->db->get_where('tb_working_hours',array('working_hours_id'=>$id));
    //     return $query->row_array();
    // }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
}