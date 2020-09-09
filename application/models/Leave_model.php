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
    public function get_type_of_leave_status_one(){
        $query = $this->db->get_where('tb_leave_type', array('status' =>1));
        return $query->result();
    }
    public function get_leave_info_by_one_condition($id){
        $query = $this->db->get_where('tb_leave',array('emp_id'=>$id, 'approveStat' => 1, 'FileLeaveType'=>'Leave with pay'));
        return $query->result();
    }
    public function get_leave_info_by_condition_with_date($emp_id, $attendance_date){
        $this->db->select('*');
        $this->db->from('tb_leave');
        $this->db->where('emp_id',$emp_id);
        $where = '(FileLeaveType ="Morning Halfday Leave with pay" or
            FileLeaveType ="Afternoon Halfday Leave with pay" 
        )';
        $this->db->where($where);
        $this->db->where('dateFrom ', $attendance_date);
        $this->db->where('dateTo', $attendance_date);
        $this->db->where('approveStat',1);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function get_leave_info_by_condition_with_date_rows($emp_id, $attendance_date){
        $this->db->select('*');
        $this->db->from('tb_leave');
        $this->db->where('emp_id',$emp_id);
        $where = '(FileLeaveType ="Morning Halfday Leave with pay" or
            FileLeaveType ="Afternoon Halfday Leave with pay" 
        )';
        $this->db->where($where);
        $this->db->where('dateFrom ', $attendance_date);
        $this->db->where('dateTo', $attendance_date);
        $this->db->where('approveStat',1);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function get_leave_info($emp_id, $date){
        $query = $this->db->get_where('tb_leave',array('emp_id'=>$emp_id, 'approveStat' => 1,'dateFrom <=' => $date, 'dateTo >='=>$date));
        return $query->result();
    }
    public function get_type_of_leave_status_oene(){
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
    public function get_leave_info_by_condition_not_emergency_birthday($id){
        $this->db->select('*');
        $this->db->from('tb_leave');
        $this->db->where('emp_id',$id);
        $this->db->where('approveStat',1);
        $where = '(FileLeaveType ="Leave with pay" or FileLeaveType ="Morning Halfday Leave with pay" or
            FileLeaveType ="Afternoon Halfday Leave with pay")';
        $this->db->where($where);
        $this->db->where('LeaveType !=','Reserve Emergency Leave');
        $this->db->where('LeaveType !=','Birthday Leave');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_leave_info_by_condition_with_emergency($id){
        $this->db->select('*');
        $this->db->from('tb_leave');
        $this->db->where('emp_id',$id);
        $this->db->where('approveStat',1);
        $this->db->where('FileLeaveType =','Leave with pay');
        $this->db->where('LeaveType =','Reserve Emergency Leave');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_leave_info_by_condition_with_birthday($id){
        $this->db->select('*');
        $this->db->from('tb_leave');
        $this->db->where('emp_id',$id);
        $this->db->where('approveStat',1);
        $this->db->where('FileLeaveType =','Leave with pay');
        $this->db->where('LeaveType =','Birthday Leave');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_leave_info_by_head($id){
        $query = $this->db->get_where('tb_leave', array('head_emp_id' =>$id, 'approveStat !='=>3,'approveStat'=>4));
        return $query->result();
    }
    public function get_leave_info_by_employee($id){
        $query = $this->db->get_where('tb_leave', array('emp_id !=' =>$id,'approveStat'=>0));
        return $query->result();
    }
    public function get_leave_list_history(){
        $query = $this->db->get_where('tb_leave', array('approveStat ' =>1));
        return $query->result();
    }

    public function get_all_type_of_leave(){
        $query = $this->db->get('tb_leave_type');
        return $query->result();
    }

    public function get_leave_lidation_data($id){
        $query = $this->db->get_where('tb_leave_validation', array('lv_id ' =>$id));
        return $query->row_array();
    }
    public function get_all_leave(){
        $query = $this->db->get('tb_emp_leave');
        return $query->result();
    }

    public function insert_employee_leave($data){
        $insert = $this->db->insert('tb_emp_leave',$data);
        return $insert;
    }

    public function get_leave_by_id($id){
        $query = $this->db->get_where('tb_leave', array('leave_id ' =>$id));
        return $query->row_array();
    }

    public function update_leave_data($id,$data){
        $this->db->trans_start();
        $this->db->where('leave_id',$id);
        $this->db->update('tb_leave',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }

    public function get_all_leave_validation(){
        $query = $this->db->get('tb_leave_validation');
        return $query->result();
    }

    public function insert_leave_type($data){
        $insert = $this->db->insert('tb_leave_type',$data);
        return $insert;
    }

    public function update_leave_type_data($id,$data){
        $this->db->trans_start();
        $this->db->where('lt_id',$id);
        $this->db->update('tb_leave_type',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function delete_leave_type($id){
        $this->db->trans_start();
        $this->db->where('lt_id',$id);
        $this->db->delete('tb_leave_type');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }

    public function get_leave_of_employee($id){
        $query = $this->db->get_where('tb_leave', array('emp_id' =>$id));
        return $query->result();
    }
    public function get_leave_for_absent_report($from,$id){
        $this->db->select('*');
        $this->db->from('tb_leave');
        $this->db->where('emp_id',$id);
        $this->db->where('approveStat',1);
        $where = '(FileLeaveType ="Leave with pay" or FileLeaveType ="Leave without pay")';
        $this->db->where($where);
        $this->db->where('dateFrom <=',$from);
        $this->db->where('dateTo >=',$from);
        $query = $this->db->get();
        return $query->result();
    }
}
