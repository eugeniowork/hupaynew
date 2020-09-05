<?php
    class Bir_model extends CI_Model{
        public function get_bir_status_to_payroll($dependentCount){
            $query = $this->db->get_where('tb_bir_status',array('Dependent'=>$dependentCount));
            return $query->row_array();
        }

        public function get_all_bir_contribution(){
        	$query = $this->db->get('tb_bir_contrib_table');
            return $query->result();
        }
    }
?>