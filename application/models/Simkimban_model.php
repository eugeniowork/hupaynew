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

    public function get_active_simkimban_with_balance(){
        $query = $this->db->get_where('tb_simkimban',array('remainingBalance !='=>0, 'status'=>1));
        return $query->result();
    }
    public function update_simkimban_loan_data($id,$data){
        $this->db->trans_start();
        $this->db->where('simkimban_id',$id);
        $this->db->update('tb_simkimban',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function insert_simkimban_loan_history_data($id,$data){
        $insert = $this->db->insert('tb_salary_loan_history',$data);
        return $insert;
    }
}