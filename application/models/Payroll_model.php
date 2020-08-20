<?php
class Payroll_model extends CI_Model{
    public function get_payroll_info($cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_approval',array('CutOffPeriod'=>$cutOffPeriod));
        return $query->row_array();
    }
}