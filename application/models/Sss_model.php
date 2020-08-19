<?php
class Sss_model extends CI_Model{
    // public function get_employee_position($id){
    //     $query = $this->db->get_where('tb_position',array('position_id'=>$id));
    //     return $query->row_array();
    // }
    public function check_if_has_sss($id){
        // $query = $this->db->get_where('tb_pagibig_loan',array('emp_id'=>$id, 'remainingBalance'));
        // return $query->row_array();
        // $query = $this->db->select('*')->from('tb_pagibig_loan')
        //     ->where('tb_pagibig_loan.emp_id', $id)
        //     ->where('tb_pagibig_loan.remainingBalance !=',0)
        //     ->get()->result();
        // ;
        $query = $this->db->get_where('tb_sss_loan',array('emp_id'=>$id, 'remainingBalance !='=>0));
        return $query->num_rows();
    }
    public function get_info_sss($id){
        $query = $this->db->get_where('tb_sss_loan',array('emp_id'=>$id, 'remainingBalance !='=>0));
        return $query->result();
    }

}