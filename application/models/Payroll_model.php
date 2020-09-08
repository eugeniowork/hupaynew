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
    public function get_all_payroll_approval(){
        $query = $this->db->get('tb_payroll_approval');
        return $query->result();
    }
    public function get_payroll_approval_adjustment($adjustment){
        $query = $this->db->get_where('tb_payroll_info',array('Adjustment !='=>$adjustment));
        return $query->result();
    }
    public function get_payroll_approval_id($id){
        $query = $this->db->get_where('tb_payroll_approval',array('approve_payroll_id'=>$id));
        return $query->row_array();
    }
    public function get_payroll_info_adjustment_cut_off_period($cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_info',array('CutOffPeriod'=>$cutOffPeriod, 'Adjustment !='=> 0));
        return $query->result();
    }

    public function get_all_payroll_approval_by_date(){
        $this->db->select('*');
        $this->db->from('tb_payroll_approval');
        $this->db->order_by('DateCreated', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_approve_payroll_by_id($id){
        $query = $this->db->get_where('tb_payroll_approval',array('approve_payroll_id'=>$id, 'approveStat'=> 1));
        return $query->row_array();
    }
    public function update_payroll_approval($id,$data){
        $this->db->trans_start();
        $this->db->where('approve_payroll_id',$id);
        $this->db->update('tb_payroll_approval',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function insert_payroll_notifications($data){
        $insert = $this->db->insert('tb_payroll_notif',$data);
        return $insert;
    }
    public function update_payroll_info($cutOffPeriod,$data){
        $this->db->trans_start();
        $this->db->where('CutOffPeriod',$cutOffPeriod);
        $this->db->update('tb_payroll_info',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function get_payroll_info_id_sort_date($id){
        $this->db->select('*');
        $this->db->from('tb_payroll_info');
        $this->db->where('emp_id',$id);
        $this->db->order_by('DateCreated', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
    // public function get_payroll_info($cutOffPeriod){
    //     $query = $this->db->get_where('tb_payroll_approval',array('CutOffPeriod'=>$cutOffPeriod));
    //     return $query->row_array();
    // }
    public function get_all_payroll_info($cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_info',array('CutOffPeriod'=>$cutOffPeriod));
        return $query->result();
    }
    public function get_notif_payroll_by_admin($id){
        $query = $this->db->get_where('tb_payroll_notif',array('approve_payroll_id'=>$id));
        return $query->row_array();
    }

    public function get_payroll_list($id){
        $this->db->select('*');
        $this->db->from('tb_payroll_info');
        $this->db->where('emp_id',$id);
        $this->db->where('payrollStatus',1);
        $this->db->order_by('DateCreated', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_payroll_notif($id){
        $this->db->select('*');
        $this->db->from('tb_payroll_notif');
        $this->db->where('emp_id',$id);
        $this->db->order_by('dateCreated', 'desc');
        $query = $this->db->get();
        return $query->result();

    }
}