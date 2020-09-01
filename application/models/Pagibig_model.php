<?php
class Pagibig_model extends CI_Model{
    // public function get_employee_position($id){
    //     $query = $this->db->get_where('tb_position',array('position_id'=>$id));
    //     return $query->row_array();
    // }
    public function check_if_has_pagibig($id){
        // $query = $this->db->get_where('tb_pagibig_loan',array('emp_id'=>$id, 'remainingBalance'));
        // return $query->row_array();
        // $query = $this->db->select('*')->from('tb_pagibig_loan')
        //     ->where('tb_pagibig_loan.emp_id', $id)
        //     ->where('tb_pagibig_loan.remainingBalance !=',0)
        //     ->get()->result();
        // ;
        $query = $this->db->get_where('tb_pagibig_loan',array('emp_id'=>$id, 'remainingBalance !='=>0));
        return $query->row_array();
    }
    public function get_pagibig_contribution(){
        $query = $this->db->get('tb_pagibig_contrib_table');
        return $query->result();
    }
    public function get_pending_pagibig_loan($date_payroll, $emp_id){
        $this->db->select('*');
        $this->db->from('tb_pagibig_loan');
        $this->db->where('dateFrom <=', $date_payroll);
        $this->db->where('dateTo >=', $date_payroll);
        $this->db->where('emp_id',$emp_id);
        $this->db->where('remainingBalance !=',0);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_employee_pagibig_loan($emp_id){
        $query = $this->db->get_where('tb_pagibig_loan',array('emp_id'=>$emp_id));
        return $query->result();
    }
    public function get_pagibig_with_balance(){
        $query = $this->db->get_where('tb_pagibig_loan',array('remainingBalance !='=>0));
        return $query->result();
    }
    public function update_pagibig_loan_data($id,$data){
        $this->db->trans_start();
        $this->db->where('pagibig_loan_id',$id);
        $this->db->update('tb_pagibig_loan',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function get_all_pagibig_loan(){
        $query = $this->db->get_where('tb_pagibig_loan');
        return $query->result();
    }
    public function get_pagibig_loan($id){
        $query = $this->db->get_where('tb_pagibig_loan',array('pagibig_loan_id'=>$id));
        return $query->row_array();
    }
}