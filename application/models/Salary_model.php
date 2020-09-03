<?php
class Salary_model extends CI_Model{
    public function get_cut_off(){
        $query = $this->db->get('tb_cut_off');
        return $query->result();
    }
    public function check_if_has_salary($id){
        $query = $this->db->get_where('tb_salary_loan',array('emp_id'=>$id, 'remainingBalance !='=>0));
        return $query->num_rows();
    }
    public function get_info_salary($id){
        $query = $this->db->get_where('tb_salary_loan',array('emp_id'=>$id));
        return $query->result();
    }
    public function get_increate_cut_off_on_employee($emp_id){
        $query = $this->db->get_where('tb_increase_salary',array('emp_id'=>$emp_id));
        return $query->row_array();
    }
    public function get_pending_salary_loan($date_payroll, $emp_id){
        $this->db->select('*');
        $this->db->from('tb_salary_loan');
        $this->db->where('dateFrom <=', $date_payroll);
        $this->db->where('dateTo >=', $date_payroll);
        $this->db->where('emp_id',$emp_id);
        $this->db->where('remainingBalance !=',0);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_employee_salary_loan($emp_id){
        $query = $this->db->get_where('tb_salary_loan',array('emp_id'=>$emp_id));
        return $query->result();
    }
    public function get_salary_with_balance(){
        $query = $this->db->get_where('tb_salary_loan',array('remainingBalance !='=>0));
        return $query->result();
    }
    public function update_salary_loan_data($id,$data){
        $this->db->trans_start();
        $this->db->where('salary_loan_id',$id);
        $this->db->update('tb_salary_loan',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function insert_salary_loan_history_data($id,$data){
        $insert = $this->db->insert('tb_salary_loan_history',$data);
        return $insert;
    }
    
    public function get_all_salary_loan(){
        $query = $this->db->get_where('tb_salary_loan');
        return $query->result();
    }
    public function get_salary_loan_data($id){
        $query = $this->db->get_where('tb_salary_loan',array('salary_loan_id '=>$id));
        return $query->row_array();
    }
    public function if_salary_loan_has_no_changes($salary_loan_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$remarks,$amountLoan,$deduction,$remainingBalance){
        $query = $this->db->get_where('tb_salary_loan',array(
            'deductionType'=>$deductionType,
            'deductionDay'=>$deductionDay,
            'totalMonths'=>$totalMonths,
            'dateFrom'=>$dateFrom,
            'dateTo'=>$dateTo,
            'remarks'=>$remarks,
            'amountLoan'=>$amountLoan,
            'deduction'=>$deduction,
            'remainingBalance'=>$remainingBalance,
            'salary_loan_id'=>$salary_loan_id,
            

        ));
        return $query->row_array();
    }
    public function delete_salary_loan($id){
        $this->db->trans_start();
        $this->db->where('salary_loan_id',$id);
        $this->db->delete('tb_salary_loan');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }

    public function get_salary_loan_history_data($id){
        $this->db->select('*');
        $this->db->from('tb_salary_loan_history');
        $this->db->where('salary_loan_id',$id);
        $this->db->order_by('dateCreated', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_employee_salary_loan_history_with_zero_balance(){
        $query = $this->db->get_where('tb_salary_loan',array('remainingBalance '=>0));
        return $query->result();
    }
    public function get_employee_salary_loan_history_data_order_by_date($id){
        $this->db->select('*');
        $this->db->from('tb_salary_loan');
        $this->db->where('emp_id',$id);
        $this->db->order_by('DateCreated', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_salary_loan_data($data){
        $insert = $this->db->insert('tb_salary_loan',$data);
        return $insert;
    }

    public function update_file_salary_loan($id,$data){
        $this->db->trans_start();
        $this->db->where('file_salary_loan_id',$id);
        $this->db->update('tb_file_salary_loan',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function insert_file_salary_loan_data($data){
        $insert = $this->db->insert('tb_file_salary_loan',$data);
        return $insert;
    }
    public function get_file_salary_loan_data_order_by_date(){
        $this->db->select('*');
        $this->db->from('tb_file_salary_loan');
        $this->db->order_by('file_salary_loan_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_filed_salary_loan_to_approve(){
        $query = $this->db->get_where('tb_file_salary_loan',array('apporveStat '=>0));
        return $query->result();
    }
    public function get_filed_salary_loan($id){
        $query = $this->db->get_where('tb_file_salary_loan',array('file_salary_loan_id '=>$id));
        return $query->row_array();
    }
    
     public function salary_last_loan_id(){
        $this->db->select('*');
        $this->db->from('tb_salary_loan');
        $this->db->order_by('salary_loan_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
}