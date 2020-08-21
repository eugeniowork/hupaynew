<?php
class Leave_model extends CI_Model{
    public function get_leave($id){
        $query = $this->db->get_where('tb_leave',array('emp_id'=>$id, 'approveStat' => 1));
        return $query->row_array();
    }
    public function get_leave_rows($id){
        $query = $this->db->get_where('tb_leave',array('emp_id'=>$id, 'approveStat' => 1));
        return $query->num_rows();
    }
    public function get_leave_info_by_condition($id){
        $this->db->select('*');
        $this->db->from('tb_leave');
        $this->db->where('emp_id',$id);
        $this->db->where('approveStat',1);
        $where = '(FileLeaveType ="Leave with pay" or FileLeaveType ="Morning Halfday Leave with pay" or
            FileLeaveType ="Afternoon Halfday Leave with pay" or FileLeaveType ="Leave without pay" 
            )';
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_leave_info_by_one_condition($id){
        $query = $this->db->get_where('tb_leave',array('emp_id'=>$id, 'approveStat' => 1, 'FileLeaveType'=>'Leave with pay'));
        return $query->result();
    }
    public function get_leave_info($emp_id, $date){
        $query = $this->db->get_where('tb_leave',array('emp_id'=>$emp_id, 'approveStat' => 1,'dateFrom <=' => $date, 'dateTo >='=>$date));
        return $query->result();
    }
}