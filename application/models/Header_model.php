<?php
class Header_model extends CI_Model{
    public function unread_memo_notif($id){
        $query = $this->db->get_where('tb_memo_notif',array('to_emp_id'=>$id,'readStatus'=>0));
        return $query->result();
    }
    public function unread_payroll_notif($id){
        $query = $this->db->get_where('tb_payroll_notif',array('emp_id'=>$id,'readStatus'=>0));
        return $query->result();
    }
    public function unread_events_notif($id){
        $query = $this->db->get_where('tb_events_notif',array('emp_id'=>$id,'readStatus'=>0));
        return $query->result();
    }
    public function unread_attendance_notif($id){
        $query = $this->db->get_where('tb_attendance_notifications',array('emp_id'=>$id,'ReadStatus'=>0));
        return $query->result();
    }
}