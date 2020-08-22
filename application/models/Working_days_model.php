<?php
class Working_days_model extends CI_Model{
    public function get_working_days_info($id){
        $query = $this->db->get_where('tb_working_days',array('working_days_id'=>$id));
        return $query->row_array();
    }
    public function get_all_working_days(){
        $query = $this->db->get('tb_working_days');
        return $query->result();
    }
    public function check_working_days($dayFrom, $dayTo){
        $query = $this->db->get_where('tb_working_days',array('day_from'=>$dayFrom, 'day_to'=>$dayTo));
        return $query->row_array();
    }
    public function insert_working_days($data){
        $insert = $this->db->insert('tb_working_days',$data);
        return $insert;
    }
    public function update_working_days($id, $data){
        $this->db->trans_start();
        $this->db->where('working_days_id',$id);
        $this->db->update('tb_working_days',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function check_working_days_has_no_changes($day_from,$day_to, $id){
        $query = $this->db->get_where('tb_working_days',array('day_from'=>$day_from, 'day_to'=>$day_to, 'working_days_id' => $id));
        return $query->row_array();
    }
    public function delete_working_days($id){
        $this->db->trans_start();
        $this->db->where('working_days_id',$id);
        $this->db->delete('tb_working_days');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
}