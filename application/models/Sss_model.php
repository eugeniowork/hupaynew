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
    public function get_sss_contribution(){
        $query = $this->db->get('tb_sss_contrib_table');
        return $query->result();
    }
    public function get_pending_sss_loan($date_payroll, $emp_id){
        $this->db->select('*');
        $this->db->from('tb_sss_loan');
        $this->db->where('dateFrom <=', $date_payroll);
        $this->db->where('emp_id',$emp_id);
        $this->db->where('remainingBalance !=',0);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_employee_sss_loan($emp_id){
        $query = $this->db->get_where('tb_sss_loan',array('emp_id'=>$emp_id));
        return $query->result();
    }

    public function get_sss_with_balance(){
        $query = $this->db->get_where('tb_sss_loan',array('remainingBalance !='=>0));
        return $query->result();
    }
    public function update_sss_loan_data($id,$data){
        $this->db->trans_start();
        $this->db->where('sss_loan_id',$id);
        $this->db->update('tb_sss_loan',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function get_all_sss_loan(){
        $query = $this->db->get_where('tb_sss_loan');
        return $query->result();
    }
    public function get_sss_loan($id){
        $query = $this->db->get_where('tb_sss_loan',array('sss_loan_id'=>$id));
        return $query->row_array();
    }

    public function delete_sss_loan($id){
        $this->db->trans_start();
        $this->db->where('sss_loan_id',$id);
        $this->db->delete('tb_sss_loan');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }

    public function get_sss_history($id){
        $this->db->select('*');
        $this->db->from('tb_sss_loan');
        $this->db->where('emp_id',$id);
        $this->db->order_by('DateCreated', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_employee_sss_loan_type($emp_id,$type){
        $query = $this->db->get_where('tb_sss_loan',array('emp_id'=>$emp_id, 'loan_type'=>$type));
        return $query->result();
    }
    public function insert_sss_loan($id,$data){
        $insert = $this->db->insert('tb_sss_loan',$data);
        return $insert;
    }
}