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
    }
?>