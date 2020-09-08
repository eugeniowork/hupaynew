<?php
class Working_hours_model extends CI_Model{
    public function get_info_working_hours($id){
        $query = $this->db->get_where('tb_working_hours',array('working_hours_id'=>$id));
        return $query->row_array();
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }

    public function get_all_working_hours(){
    	$query = $this->db->get('tb_working_hours');
        return $query->result();
    }
    public function delete_working_hours($id){
        $this->db->trans_start();
        $this->db->where('working_hours_id',$id);
        $this->db->delete('tb_working_hours');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function check_working_hours($timeFrom, $timeTo){
        $query = $this->db->get_where('tb_working_hours',array('timeFrom'=>$timeFrom, 'timeTo'=>$timeTo));
        return $query->row_array();
    }
    public function check_working_hours_own($timeFrom, $timeTo,$id){
        $query = $this->db->get_where('tb_working_hours',array('timeFrom'=>$timeFrom, 'timeTo'=>$timeTo, 'working_hours_id !='=>$id));
        return $query->row_array();
    }
    public function insert_working_hours($data){
        $insert = $this->db->insert('tb_working_hours',$data);
        return $insert;
    }
    public function update_working_days($id, $data){
        $this->db->trans_start();
        $this->db->where('working_hours_id',$id);
        $this->db->update('tb_working_hours',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
}