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
    public function get_type_of_leave_status_one(){
        $query = $this->db->get_where('tb_leave_type', array('status' =>1));
        return $query->result();
    }
    public function get_type_of_leave_by_id($lt_id){
        $query = $this->db->get_where('tb_leave_type', array('lt_id' =>$lt_id));
        return $query->row_array();
    }
    public function get_pet_info($emp_id){
        $query = $this->db->get_where('tb_pet_info', array('emp_id' =>$emp_id));
        return $query->row_array();
    }

    public function get_employee_leave($emp_id){
        $query = $this->db->get_where('tb_emp_leave', array('emp_id' =>$emp_id));
        return $query->result();
    }
    public function leave_date_from_date_to($emp_id, $dateFrom, $dateTo, $fileLeaveType){
        $query = $this->db->get_where('tb_leave', 
            array('FileLeaveType' =>$fileLeaveType, 'dateFrom'=>$dateFrom, 'dateTo'=>$dateTo, 'emp_id' => $emp_id)
        );
        return $query->row_array();
    }
    public function update_leave($emp_id, $dateFrom, $dateTo,$data){
        $this->db->trans_start();
        $this->db->where('emp_id',$emp_id);
        $this->db->where('dateFrom',$dateFrom);
        $this->db->where('dateTo',$dateTo);
        $this->db->update('tb_leave',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function insert_leave($data){
        $insert = $this->db->insert('tb_leave',$data);
        return $insert;
    }
    public function leave_last_id(){
        $this->db->select('*');
        $this->db->from('tb_leave');
        $this->db->order_by('leave_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
}