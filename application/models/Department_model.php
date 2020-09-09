<?php
    class Department_model extends CI_Model{
        public function get_department($id){
            $query = $this->db->get_where('tb_department',array('dept_id'=>$id));
            return $query->row_array();
        }
        public function get_all_department(){
        	$query = $this->db->get('tb_department');
            return $query->result();
        }
        public function insert_department($data){
            $insert = $this->db->insert('tb_department',$data);
            return $insert;
        }
        public function update_department($id,$data){
            $this->db->trans_start();
            $this->db->where('dept_id',$id);
            $this->db->update('tb_department',$data);
            $this->db->trans_complete();
            if($this->db->trans_status() === TRUE){
                return "success";
            }
            else{
                return "error";
            }
        }
        public function delete_department($id){
            $this->db->trans_start();
            $this->db->where('dept_id',$id);
            $this->db->delete('tb_department');
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