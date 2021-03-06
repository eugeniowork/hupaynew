<?php
class Deduction_model extends CI_Model{
    public function get_yearly_deduction($emp_id){
        $query = $this->db->get_where('tb_year_total_deduction', array('emp_id' => $emp_id));
        return $query->row_array();
    }
    public function get_total_year_deduction_by_year($year, $id){
        $query = $this->db->get_where('tb_year_total_deduction', array('Year' => $year, 'emp_id'=>$id));
        return $query->row_array();
    }
    public function update_total_year_deduction_data($id, $year, $data){
        $this->db->trans_start();
        $this->db->where('emp_id',$id);
        $this->db->where('year',$year);
        $this->db->update('tb_year_total_deduction',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function insert_total_year_deduction_data($data){
        $insert = $this->db->insert('tb_year_total_deduction',$data);
        return $insert;
    }
    public function get_yearly_deduction_by_year_date($year){
        $query = $this->db->get_where('tb_year_total_deduction', array('Year' => $year));
        return $query->result();
    }
    public function get_yearly_deduction_by_id($id){
        $query = $this->db->get_where('tb_year_total_deduction', array('ytd_id' => $id));
        return $query->row_array();
    }
    public function if_no_changes_in_update_ytd($ytdGross, $ytdAllowance, $ytdTax, $ytdId){
        $query = $this->db->get_where('tb_year_total_deduction', array('ytd_Gross' => $ytdGross, 'ytd_Allowance' =>$ytdAllowance, 'ytd_Tax'=>$ytdTax, 'ytd_id' => $ytdId));
        return $query->row_array();
    }
    public function update_ytd($id,$data){
        $this->db->trans_start();
        $this->db->where('ytd_id',$id);
        $this->db->update('tb_year_total_deduction',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }

    public function insert_year_total_deduction($data){
        $insert = $this->db->insert('tb_year_total_deduction',$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
}