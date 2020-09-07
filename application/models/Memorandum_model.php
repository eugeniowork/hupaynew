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
	public function get_memorandum($id){
		$query = $this->db->get_where('tb_memorandum',array('memo_id'=>$id));
        return $query->result();
	}
	public function get_multiple_memo_recipient($id){
		$query = $this->db->get_where('tb_multiple_memo',array('memo_id'=>$id, 'recipient'=>"All"));
        return $query->result();
	}
	public function get_memorandum_data($id){
		$query = $this->db->get_where('tb_memorandum',array('memo_id'=>$id));
        return $query->row_array();
	}
	public function delete_memo_single($id){
		$this->db->trans_start();
        $this->db->where('memo_id',$id);
        $this->db->delete('tb_memorandum');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
	}
	public function delete_memo_multiple($id){
		$this->db->trans_start();
        $this->db->where('memo_id',$id);
        $this->db->delete('tb_multiple_memo');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
	}
	public function delete_memo_notif($id){
		$this->db->trans_start();
        $this->db->where('memo_id',$id);
        $this->db->delete('tb_memo_notif');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
	}
    public function get_memo_image_data($id){
        $query = $this->db->get_where('tb_memo_images',array('memo_img_id'=>$id));
        return $query->row_array();
    }

    public function delete_memo_image($id){
        $this->db->trans_start();
        $this->db->where('memo_img_id',$id);
        $this->db->delete('tb_memo_images');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
}
?>