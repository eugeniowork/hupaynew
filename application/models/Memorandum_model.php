<?php 

class Memorandum_model extends CI_Model{
	public function get_all_memorandum(){
		$this->db->select('*');
        $this->db->from('tb_memorandum');
        $this->db->order_by('DateCreated', 'desc');
        $query = $this->db->get();
        return $query->result();
	}
	public function get_multiple_memo($id){
		$query = $this->db->get_where('tb_multiple_memo',array('memo_id'=>$id));
        return $query->result();
	}

	public function get_memo_image($id){
		$query = $this->db->get_where('tb_memo_images',array('memo_id'=>$id));
        return $query->result();
	}
}



 ?>