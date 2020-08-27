<?php
class Payroll_model extends CI_Model{
    public function get_payroll_info($cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_approval',array('CutOffPeriod'=>$cutOffPeriod));
        return $query->row_array();
    }
    public function get_payroll_date($datePayroll){
        $query = $this->db->get_where('tb_payroll_info',array('datePayroll'=>$datePayroll));
        return $query->row_array();
    }
    public function get_employee_payroll_info($emp_id){
        $query = $this->db->get_where('tb_payroll_info',array('emp_id'=>$emp_id));
        return $query->row_array();
    }
    public function get_payroll_last_total_gross_income($emp_id){
        $this->db->select('*');
        $this->db->from('tb_payroll_info');
        $this->db->where('emp_id',$emp_id);
        $this->db->order_by('payroll_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function get_payroll_last_total_gross_income_rows($emp_id){
        $this->db->select('*');
        $this->db->from('tb_payroll_info');
        $this->db->where('emp_id',$emp_id);
        $this->db->order_by('payroll_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function get_cut_off_13_month_pay_old($emp_id, $cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_info',array('emp_id'=>$emp_id, 'CutOffPeriod'=>$cutOffPeriod));
        return $query->num_rows();
    }
    public function get_cut_off_13_month_pay_old_data($emp_id, $cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_info',array('emp_id'=>$emp_id, 'CutOffPeriod'=>$cutOffPeriod));
        return $query->row_array();
    }
    public function insert_payroll_approval($data){
        $insert = $this->db->insert('tb_payroll_approval',$data);
        return $insert;
    }
    public function insert_payroll($data){
        $insert = $this->db->insert('tb_payroll_info',$data);
        return $insert;
    }
    public function generate_payroll_cutoff($cutOffPeriod){
        $query = $this->db->get_where('tb_ready_generate_payroll',array('CutOffPeriod'=>$cutOffPeriod));
        return $query->result();
    }
    public function get_payroll_approval_by_cut_off_period($cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_approval',array('CutOffPeriod'=>$cutOffPeriod, 'approveStat'=>'3'));
        return $query->row_array();
    }
}