<?php
class Messaging_model extends CI_Model{
	public function get_messages($id){
		// $query = $this->db->get_where('tb_message_logs',array('to_emp_id'=>$id, 'from_emp_id'=>$id));
  //       return $query->result();

        $this->db->select('*');
        $this->db->from('tb_message_logs');
        $where = '(to_emp_id ='.$id.' or from_emp_id ='.$id.')';
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
	}
	public function get_message_per_user($id){
		$query = $this->db->get_where('tb_message_logs',array('message_id'=>$id));
  		return $query->row_array();
	}
	public function read_message($id,$data){
		$this->db->trans_start();
        $this->db->where('message_id',$id);
        $this->db->update('tb_message_logs',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
	}

	public function get_reply($id){
		$query = $this->db->get_where('tb_message_reply',array('message_id'=>$id));
  		return $query->result();
	}
	public function get_all_message($id){
		$query = $this->db->get_where('tb_message_logs',array('message_id'=>$id));
  		return $query->result();
	}
	public function insert_reply($data){
        $insert = $this->db->insert('tb_message_reply',$data);
        return $insert;
    }
    public function insert_message_logs($data){
        $insert = $this->db->insert('tb_message_logs',$data);
        return $insert;
    }

}