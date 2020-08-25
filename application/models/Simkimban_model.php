<?php
class Simkimban_model extends CI_Model{
    public function check_if_has_simkimban($id){
        $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'remainingBalance !='=>0, 'status' => 1));
        return $query->num_rows();
    }
    public function get_info_simkimban($id){
        $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
        return $query->result();
    }
    public function get_pending_simkimban_loan($date_payroll, $emp_id){
        $this->db->select('*');
        $this->db->from('tb_simkimban');
        $this->db->where('dateFrom <=', $date_payroll);
        $this->db->where('dateTo >=', $date_payroll);
        $this->db->where('emp_id',$emp_id);
        $this->db->where('remainingBalance !=',0);
        $this->db->where('status',1);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_employee_simkimban_loan($emp_id){
        $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$emp_id, 'status'=>1));
        return $query->result();
    }
}