<?php
    class Department_model extends CI_Model{
        public function get_department($id){
            $query = $this->db->get_where('tb_department',array('dept_id'=>$id));
            return $query->row_array();
        }
        public function get_all_department(){
        	$query = $this->db->get('tb_department');
            return $query->result();
        }
    }
?>