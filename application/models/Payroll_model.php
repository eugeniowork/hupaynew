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
}