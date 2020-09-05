<?php
class Role_model extends CI_Model{
	public function get_all_role(){
        $query = $this->db->get('tb_role');
        return $query->result();
    }
    public function get_role($id){
    	$query = $this->db->get_where('tb_role',array('role_id'=>$id));
        return $query->row_array();
    }
}

?>