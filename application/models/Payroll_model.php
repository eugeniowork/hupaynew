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
}