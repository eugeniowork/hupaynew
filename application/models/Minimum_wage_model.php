<?php
    class Minimum_wage_model extends CI_Model{
        public function get_minimum_wage(){
            $this->db->select('*');
            $this->db->from('tb_minimum_wage');
            $this->db->order_by('effectiveDate', 'desc');
            $this->db->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        public function get_minimum_wage_last(){
            $this->db->select('*');
            $this->db->from('tb_minimum_wage');
            $this->db->order_by('effectiveDate', 'asc');
            $this->db->limit(2);
            $query = $this->db->get();
            return $query->row_array();
        }
        public function get_same_minimum_wage($effectiveDate,$basicWage,$cola){
            $query = $this->db->get_where('tb_minimum_wage',array('effectiveDate'=>$effectiveDate, 'basicWage'=>$basicWage, 'COLA'=>$cola));
            return $query->result();
        }
        public function insert_minimum_wage($data){
            $insert = $this->db->insert('tb_minimum_wage',$data);
            return $insert;
        }
        public function get_all_min_wage(){
            $query = $this->db->get('tb_minimum_wage');
            return $query->result();
        }
        public function get_min_wage_id($id){
            $query = $this->db->get_where('tb_minimum_wage',array('min_wage_id'=>$id));
            return $query->row_array();
        }
        public function update_minimum_wage($data, $id){
            $this->db->trans_start();
            $this->db->where('min_wage_id',$id);
            $this->db->update('tb_minimum_wage',$data);
            $this->db->trans_complete();
            if($this->db->trans_status() === TRUE){
                return "success";
            }
            else{
                return "error";
            }
        }
        public function delete_min_wage($id){
            $this->db->trans_start();
            $this->db->where('min_wage_id',$id);
            $this->db->delete('tb_minimum_wage');
            $this->db->trans_complete();
            if($this->db->trans_status() === TRUE){
                return "success";
            }
            else{
                return "error";
            }
        }
        public function get_all_minimum_wage_sorted(){
            $this->db->select('*');
            $this->db->from('tb_minimum_wage');
            $this->db->order_by('effectiveDate', 'desc');
            $query = $this->db->get();
            return $query->result();
        }
    }
?>