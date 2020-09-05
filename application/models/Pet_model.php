<?php 

class Pet_model extends CI_Model{
	public function insert_pet($data){
        $insert = $this->db->insert('tb_pet_info',$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
}





 ?>