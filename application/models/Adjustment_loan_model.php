<?php
class Adjustment_loan_model extends CI_Model{

	public function get_all_adjustment_loan_with_type($type){
        $query = $this->db->get_where('tb_adjustment_loan',array('loanType'=>$type));
        return $query->result();
    }
    public function get_all_adjustment_loan_with_type_but_not_simkimban(){
        $query = $this->db->get_where('tb_adjustment_loan',array('loanType !='=>'Simkimban'));
        return $query->result();
    }

    public function insert_adjustment_loan_data($data){
        $insert = $this->db->insert('tb_adjustment_loan',$data);
        return $insert;
    }
}
?>