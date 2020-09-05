<?php
class Philhealth_model extends CI_Model{

	public function get_all_philhealth_contribution(){
		$this->db->select('*');
        $this->db->from('tb_philhealth_contrib_table');
        $this->db->order_by('Contribution', 'asc');
        $query = $this->db->get();
        return $query->result();
	}
}