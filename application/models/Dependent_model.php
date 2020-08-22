<?php
    class Dependent_model extends CI_Model{
        public function get_dependent_rows($emp_id){
            $query = $this->db->get_where('tb_dependent',array('emp_id'=>$emp_id));
            return $query->num_rows();
        }
    }
?>