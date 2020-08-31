<?php
class Audit_trial_model extends CI_Model{
    // public function get_info_allowance($id){
    //     $query = $this->db->get_where('tb_emp_allowance',array('emp_id'=>$id));
    //     return $query->result();
    // }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
    public function insert_audit_trial($data){
        $insert = $this->db->insert('tb_audit_trail',$data);
        return $insert;
    }

    public function get_audit_trail_order_by_date(){
        $this->db->select('*');
        $this->db->from('tb_audit_trail');
        $this->db->order_by('dateCreated', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
}