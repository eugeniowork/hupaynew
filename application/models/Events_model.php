<?php
class Events_model extends CI_Model{
    // public function unread_memo_notif($id){
    //     $query = $this->db->get_where('tb_memo_notif',array('to_emp_id'=>$id,'readStatus'=>0));
    //     return $query->result();
    // }
    // public function unread_payroll_notif($id){
    //     $query = $this->db->get_where('tb_payroll_notif',array('emp_id'=>$id,'readStatus'=>0));
    //     return $query->result();
    // }
    // public function unread_events_notif($id){
    //     $query = $this->db->get_where('tb_events_notif',array('emp_id'=>$id,'readStatus'=>0));
    //     return $query->result();
    // }
    // public function unread_attendance_notif($id){
    //     $query = $this->db->get_where('tb_attendance_notifications',array('emp_id'=>$id,'ReadStatus'=>0));
    //     return $query->result();
    // }
    // public function get_events(){
    //     $this->db->from('tb_events');
    //     $this->db->order_by('dateTimeCreated', 'desc');
    //     $query = $this->db->get();
    //     return $query->result();
    //     // $this->db->select('*');
    //     // $this->db->from('tb_events');
    //     // $this->db->join('tb_employee_info', 'tb_employee_info.emp_id = tb_events.emp_id','left');
    //     // $this->db->join('tb_position','tb_position.position_id = tb_employee_info.emp_id','left');
    //     // $query = $this->db->get();
    //     // return $query->result();
    // }
    public function get_events(){
        $this->db->select('*');
        $this->db->from('tb_events');
        $this->db->order_by('dateTimeCreated', 'desc');
        $this->db->join('tb_employee_info', 'tb_employee_info.emp_id = tb_events.emp_id','left');
        $this->db->join('tb_position','tb_position.position_id = tb_employee_info.position_id','left');
        $this->db->join('tb_events_images', 'tb_events_images.events_id = tb_events.events_id','left');
        $query = $this->db->get();
        return $query->result();
    }
}