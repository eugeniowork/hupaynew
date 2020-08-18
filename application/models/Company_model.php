<?php
class Company_model extends CI_Model{
    public function get_all_company_for_dropdown(){
        $query = $this->db->get('tb_company');
        $row = $query->result();
        return $row;
    }
    public function get_company_info($id){
        $query = $this->db->get_where('tb_company',array('company_id'=>$id));
        return $query->row_array();
    }
}