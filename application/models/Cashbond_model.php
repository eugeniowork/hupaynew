<?php
class Cashbond_model extends CI_Model{
    public function get_info_simkimban($id){
        $query = $this->db->get_where('tb_cashbond',array('emp_id'=>$id));
        return $query->row_array();
    }
    public function get_cashbond($emp_id){
        $query = $this->db->get_where('tb_cashbond',array('emp_id'=>$emp_id));
        return $query->row_array();
    }
    public function get_cashbond_num_rows($emp_id){
        $query = $this->db->get_where('tb_cashbond',array('emp_id'=>$emp_id));
        return $query->num_rows();
    }
    public function get_cashbond_current_ending_balance_order_by($emp_id){
        // $query = $this->db->get_where('tb_emp_cashbond_history',array('emp_id'=>$emp_id));
        // return $query->row_array();

        $this->db->select('*');
        $this->db->from('tb_emp_cashbond_history');
        $this->db->where('emp_id',$emp_id);
        $this->db->order_by('emp_cashbond_history', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function insert_cashbond_data($data){
        $insert = $this->db->insert('tb_emp_cashbond_history',$data);
        return $insert;
    }
    public function update_cashbond_data($id,$data){
        $this->db->trans_start();
        $this->db->where('emp_id',$id);
        $this->db->update('tb_cashbond',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function get_all_cashbond(){
        $query = $this->db->get_where('tb_cashbond');
        return $query->result();
    }
    public function get_cashbond_by_id($id){
        $query = $this->db->get_where('tb_cashbond',array('cashbond_id'=>$id));
        return $query->row_array();
    }
    public function get_cashbond_id_value($cashbond_id,$cashbond_value){
        $query = $this->db->get_where('tb_cashbond',array('cashbondValue'=>$cashbond_value,'cashbond_id'=>$cashbond_id));
        return $query->row_array();
    }
    public function get_all_employee_cashbond($emp_id){
        $query = $this->db->get_where('tb_emp_cashbond_history',array('emp_id'=>$emp_id));
        return $query->result();
    }
    public function get_all_employee_cashbond_history($emp_id,$orderBy,$orderType){
        $this->db->select('*');
        $this->db->from('tb_emp_cashbond_history');
        $this->db->where('emp_id',$emp_id);
        $this->db->order_by($orderBy, $orderType);
        $query = $this->db->get();
        return $query->result();
    }
    public function update_cashbond_history_data($id,$data){
        $this->db->trans_start();
        $this->db->where('emp_cashbond_history',$id);
        $this->db->update('tb_emp_cashbond_history',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function get_all_employee_cashbond_history_limit($emp_id,$orderBy,$orderType, $limit){
        $this->db->select('*');
        $this->db->from('tb_emp_cashbond_history');
        $this->db->where('emp_id',$emp_id);
        $this->db->order_by($orderBy, $orderType);
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function insert_cashbond_history_data($data){
        $insert = $this->db->insert('tb_emp_cashbond_history',$data);
        return $insert;
    }

    public function get_pending_cashbond_withdrawal($emp_id){
        $query = $this->db->get_where('tb_file_cashbond_withdrawal',array('emp_id'=>$emp_id, 'approve_stats'=>0));
        return $query->row_array();
    }
    public function insert_cashbond_withdrawal_data($data){
        $insert = $this->db->insert('tb_file_cashbond_withdrawal',$data);
        return $insert;
    }
    public function get_approve_cashbond_withdraw($emp_id){
        $query = $this->db->get_where('tb_file_cashbond_withdrawal',array('emp_id'=>$emp_id, 'approve_stats'=>1));
        return $query->result();
    }
    public function get_pending_cashbond_withdraw($emp_id){
        $query = $this->db->get_where('tb_file_cashbond_withdrawal',array('emp_id'=>$emp_id, 'approve_stats'=>0));
        return $query->result();
    }
    public function get_cashbond_withdrawal_by_withdrawal_id($id){
        $query = $this->db->get_where('tb_file_cashbond_withdrawal',array('file_cashbond_withdrawal_id'=>$id));
        return $query->row_array();
    }

    public function update_cashbond_withdrawal_data($id,$data){
        $this->db->trans_start();
        $this->db->where('file_cashbond_withdrawal_id',$id);
        $this->db->update('tb_file_cashbond_withdrawal',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function get_latest_file_cashbond_withdrawal($emp_id, $approve_stats, $orderBy, $orderType, $limit){
        $this->db->select('*');
        $this->db->from('tb_file_cashbond_withdrawal');
        $this->db->where('emp_id',$emp_id);
        $this->db->where('approve_stats',$approve_stats);
        $this->db->order_by($orderBy, $orderType);
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function get_all_pending_cashbond_withdrawal(){
        $query = $this->db->get_where('tb_file_cashbond_withdrawal',array('approve_stats'=>0));
        return $query->result();
    }
    public function update_cashbond_withdrawal_data_by_emp_id($id,$data){
        $this->db->trans_start();
        $this->db->where('emp_id',$id);
        $this->db->where('approve_stats',0);
        $this->db->update('tb_file_cashbond_withdrawal',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
}