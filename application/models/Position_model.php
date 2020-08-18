<?php
class Position_model extends CI_Model{
    public function get_employee_position($id){
        $query = $this->db->get_where('tb_position',array('position_id'=>$id));
        return $query->row_array();
    }
}