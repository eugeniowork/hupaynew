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

    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
}