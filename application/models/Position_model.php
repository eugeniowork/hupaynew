<?php
class Position_model extends CI_Model{
    public function get_employee_position($id){
        $query = $this->db->get_where('tb_position',array('position_id'=>$id));
        return $query->row_array();
    }
    public function get_all_position(){
    	$query = $this->db->get('tb_position');
        return $query->result();
    }
    public function get_department_in_position($id){
    	$query = $this->db->get_where('tb_position',array('dept_id'=>$id));
        return $query->row_array();
    }

    public function get_all_position_in_department($id){
        $query = $this->db->get_where('tb_position',array('dept_id'=>$id));
        return $query->result();
    }

    public function insert_position_history($data){
        $insert = $this->db->insert('tb_history_position',$data);
        return $insert;
    }
    public function check_position($deptId, $position){
        $query = $this->db->get_where('tb_position',array('dept_id'=>$deptId, 'Position'=>$position));
        return $query->result();
    }
    public function insert_position($data){
        $insert = $this->db->insert('tb_position',$data);
        return $insert;
    }
    public function delete_position($id){
        $this->db->trans_start();
        $this->db->where('position_id',$id);
        $this->db->delete('tb_position');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
}