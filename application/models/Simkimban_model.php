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
    public function get_employee_with_existing_simkimban(){
        $query = $this->db->get_where('tb_simkimban',array('remainingBalance >'=>0, 'status'=>1));
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
    public function get_simkimban_data($id){
        $query = $this->db->get_where('tb_simkimban',array('simkimban_id'=>$id));
        return $query->row_array();
    }
    public function if_simkimband_has_no_changes($simkimban_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$item,$amountLoan,$deduction,$remainingBalance){
        $query = $this->db->get_where('tb_simkimban',array(
            'deductionType'=>$deductionType,
            'deductionDay'=>$deductionDay,
            'totalMonths'=>$totalMonths,
            'dateFrom'=>$dateFrom,
            'dateTo'=>$dateTo,
            'Items'=>$item,
            'amountLoan'=>$amountLoan,
            'deduction'=>$deduction,
            'remainingBalance'=>$remainingBalance,
            'simkimban_id'=>$simkimban_id,
            

        ));
        return $query->row_array();
    }
    public function get_simkimban_data_order_date($id){
        $this->db->select('*');
        $this->db->from('tb_simkimban_loan_history');
        $this->db->where('simkimban_id',$id);
        $this->db->order_by('dateCreated', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_simkimban_zero_balance(){
        $query = $this->db->get_where('tb_simkimban',array('remainingBalance'=>0, 'status'=>1));
        return $query->result();
    }

    public function get_simkimban_history($id){
        $this->db->select('*');
        $this->db->from('tb_simkimban');
        $this->db->where('emp_id',$id);
        $this->db->where('status',1);
        $this->db->order_by('dateCreated', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    public function insert_simkimban_loan_data($data){
        $insert = $this->db->insert('tb_simkimban',$data);
        return $insert;
    }

}