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
    public function attendance_info_all_object_type($id){
        $this->db->select('*');
        $this->db->from('tb_attendance');
        $this->db->where('bio_id',$id);
        $this->db->order_by('date', 'desc');
        $query = $this->db->get();
        return $query->row_array();
    }
    public function get_update_attendance($attendance_id, $bio_id){
        $query = $this->db->get_where('tb_attendance', array('bio_id'=>$bio_id, 'attendance_id'=>$attendance_id));
        return $query->row_array();
    }
    public function get_attendance_by_id($id){
        $query = $this->db->get_where('tb_attendance', array('attendance_id'=>$id));
        return $query->row_array();
    }
    public function get_attendance_notif($id){
        $query = $this->db->get_where('tb_attendance_notif', array('attendance_id'=>$id));
        return $query->row_array();
    }
    public function attendance_notif_update($attendanceNotifData, $attendanceId){
        $this->db->trans_start();
        $this->db->where('attendance_id',$attendanceId);
        $this->db->update('tb_attendance_notif',$attendanceNotifData);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function insert_notifications($data){
        $insert = $this->db->insert('tb_attendance_notifications',$data);
        return $insert;
    }
    public function insert_attendance_notif($data){
        $insert = $this->db->insert('tb_attendance_notif',$data);
        return $insert;
    }
    public function attendance_notif_last_id(){
        $this->db->select('*');
        $this->db->from('tb_attendance_notif');
        $this->db->order_by('attendance_notif_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_attendance_between_date($dateFrom, $dateTo, $bio_id){
        $this->db->select('*');
        $this->db->from('tb_attendance');
        
        //$this->db->where('date BETWEEN "'. $dateFrom. '" and "'. $dateTo.'"');
        $this->db->where('date >=',$dateFrom);
        $this->db->where('date <=', $dateTo);
        $this->db->where('bio_id',$bio_id);
        $this->db->order_by('date', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_attendance_overtime($emp_id, $date){
        $query = $this->db->get_where('tb_attendance_overtime', array('emp_id'=>$emp_id, 'date'=>$date));
        return $query->row_array();
    }
    public function update_attendance_overtime($emp_id, $date, $data){
        $this->db->trans_start();
        $this->db->where('emp_id',$emp_id);
        $this->db->where('date',$date);
        $this->db->update('tb_attendance_overtime',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function insert_attendance_overtime($data){
        $insert = $this->db->insert('tb_attendance_overtime',$data);
        return $insert;
    }
    public function attendance_ot_last_id(){
        $this->db->select('*');
        $this->db->from('tb_attendance_overtime');
        $this->db->order_by('attendance_ot_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_attendance_if_exist($emp_id,$date){
        $query = $this->db->get_where('tb_attendance_notif', array('emp_id'=>$emp_id, 'date'=>$date));
        return $query->row_array();
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