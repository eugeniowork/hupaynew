<?php
class Holiday_model extends CI_Model{
    public function get_cut_off(){
        $query = $this->db->get('tb_cut_off');
        return $query->result();
    }
    public function get_holiday(){
        $query = $this->db->get('tb_holiday');
        return $query->result();
    }
    public function get_holiday_date($holiday){
        $query = $this->db->get_where('tb_holiday', array('holiday_date'=>$holiday));
        return $query->row_array();
    }
    public function get_holiday_date_rows($holiday){
        $query = $this->db->get_where('tb_holiday', array('holiday_date'=>$holiday));
        return $query->num_rows();
    }
    public function get_holiday_date_all($holiday){
        $query = $this->db->get_where('tb_holiday', array('holiday_date'=>$holiday));
        return $query->result();
    }

    public function get_holiday_types($holiday){
        $query = $this->db->get_where('tb_holiday', array('holiday_type'=>$holiday));
        return $query->result();
    }

    public function get_holiday_data($id){
        $query = $this->db->get_where('tb_holiday', array('holiday_id'=>$id));
        return $query->row_array();
    }

    public function check_holiday($date, $id){
        $query = $this->db->get_where('tb_holiday', array('holiday_date'=>$date, 'holiday_id'=>$id));
        return $query->row_array();
    }
    // public function get_info_simkimban($id){
    //     $query = $this->db->get_where('tb_simkimban',array('emp_id'=>$id, 'status' => 1));
    //     return $query->result();
    // }
    public function update_holiday($id,$data){
        $this->db->trans_start();
        $this->db->where('holiday_id',$id);
        $this->db->update('tb_holiday',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }

    public function delete_holiday($id){
        $this->db->trans_start();
        $this->db->where('holiday_id',$id);
        $this->db->delete('tb_holiday');
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function insert_holiday($data){
        $insert = $this->db->insert('tb_holiday',$data);
        return $insert;
    }
}