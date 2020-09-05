<?php
class Employee_model extends CI_Model{
    public function employee_information($id){
        $query = $this->db->get_where('tb_employee_info',array('emp_id'=>$id));
        return $query->row_array();
    }
    public function get_employee_by_bio_id($id){
        $query = $this->db->get_where('tb_employee_info',array('bio_id'=>$id));
        return $query->num_rows();
    }
    public function get_employee_by_bio_id_data($id){
        $query = $this->db->get_where('tb_employee_info',array('bio_id'=>$id));
        return $query->row_array();
    }
    public function get_employee_by_role(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $where = '(role_id ="1" or role_id ="2")';
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_employee_by_role_one(){
        $query = $this->db->get_where('tb_employee_info',array('role_id'=>1));
        return $query->result();
    }

    public function check_if_head($id){
        $query = $this->db->get_where('tb_employee_info',array('head_emp_id'=>$id));
        return $query->row_array();
    }
    public function get_employee_atm(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $this->db->where('withAtm', 1);
        $this->db->order_by('Lastname', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function check_atm_no($emp_id, $atmAccountNumber){
        $query = $this->db->get_where('tb_employee_info',array('emp_id'=>$emp_id, 'atmAccountNumber'=>$atmAccountNumber));
        return $query->row_array();

    }
    public function update_atm_no($emp_id,$data){
        $this->db->trans_start();
        $this->db->where('emp_id',$emp_id);
        $this->db->update('tb_employee_info',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function get_working_days_of_employee($working_days_id){
        $query = $this->db->get_where('tb_employee_info',array('working_days_id'=>$working_days_id));
        return $query->result();
    }
    public function get_active_employee(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $this->db->where('ActiveStatus', 1);
        $where = '(role_id !="1" or dept_id !="1")';
        $this->db->where($where);
        $this->db->order_by('Lastname', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_active_employee_row_array(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $this->db->where('ActiveStatus', 1);
        $where = '(role_id !="1" or dept_id !="1")';
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }
    public function get_employee_with_cut_off($emp_id, $cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_info',array('emp_id'=>$emp_id, 'CutOffPeriod'=>$cutOffPeriod));
        return $query->result();
    }
    public function get_employee_with_cut_off_row_array($emp_id, $cutOffPeriod){
        $query = $this->db->get_where('tb_payroll_info',array('emp_id'=>$emp_id, 'CutOffPeriod'=>$cutOffPeriod));
        return $query->row_array();
    }

    public function get_active_admin(){
        $query = $this->db->get_where('tb_employee_info',array('role_id'=>1, 'ActiveStatus'=>1));
        return $query->result();
    }
    public function get_all_employee(){
        $query = $this->db->get('tb_employee_info');
        return $query->result();
    }
    public function get_employee_order_by_and_limit($id, $orderBy, $orderType, $limit){
        $this->db->select('*');
        $this->db->from('tb_payroll_info');
        $this->db->where('emp_id',$id);
        $this->db->order_by($orderBy, $orderType);
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }
    public function update_employee_info($emp_id,$data){
        $this->db->trans_start();
        $this->db->where('emp_id',$emp_id);
        $this->db->update('tb_employee_info',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }

    public function get_all_employee_order_by_lastname(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $this->db->where('ActiveStatus', 1);
        $this->db->order_by('Lastname', 'asc');
        //$this->db->join('tb_emp_allowance', 'tb_emp_allowance.emp_id=tb_employee_info.emp_id ','inner');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_file_loan_data($refNo){
        $query = $this->db->get_where('tb_emp_file_loan',array('ref_no'=>$refNo));
        return $query->row_array();
    }

    public function get_employee_file_loan_data_order_by_date($id){
        $this->db->select('*');
        $this->db->from('tb_emp_file_loan');
        $this->db->where('emp_id', $id);
        $this->db->order_by('date_created', 'desc');
        //$this->db->join('tb_emp_allowance', 'tb_emp_allowance.emp_id=tb_employee_info.emp_id ','inner');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_employee_file_loan_data($id){
        $query = $this->db->get_where('tb_emp_file_loan',array('file_loan_id'=>$id));
        return $query->row_array();
    }

    public function update_employee_file_loan_data($id,$data){
        $this->db->trans_start();
        $this->db->where('file_loan_id',$id);
        $this->db->update('tb_emp_file_loan',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function update_employee_file_loan_data_using_ref_no($refNo,$data){
        $this->db->trans_start();
        $this->db->where('ref_no',$refNo);
        $this->db->update('tb_emp_file_loan',$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE){
            return "success";
        }
        else{
            return "error";
        }
    }
    public function get_all_employee_file_loan_data(){
        $query = $this->db->get_where('tb_emp_file_loan');
        return $query->result();
    }
    public function get_all_employee_file_loan_data_order_by(){
        $this->db->select('*');
        $this->db->from('tb_emp_file_loan');
        $this->db->order_by('file_loan_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function insert_file_loan($data){
        $insert = $this->db->insert('tb_emp_file_loan',$data);
        return $insert;
    }

    public function get_employee_file_loan_data_status_zero(){
        $this->db->select('*');
        $this->db->from('tb_emp_file_loan');
        $this->db->where('status', 0);
        $this->db->order_by('date_created', 'desc');
        //$this->db->join('tb_emp_allowance', 'tb_emp_allowance.emp_id=tb_employee_info.emp_id ','inner');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_active_employee_with_no_bio(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $this->db->where('no_bio', 1);
        $this->db->where('ActiveStatus', 1);
        $where = '(role_id !="1" or dept_id !="1")';
        $this->db->where($where);
        $this->db->order_by('Lastname', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_position_of_employee($id){
        $query = $this->db->get_where('tb_employee_info',array('position_id'=>$id));
        return $query->row_array();
    }

    public function get_all_employee_for_bio(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $this->db->order_by('Firstname', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_all_list_of_employee(){
        $this->db->select('*');
        $this->db->from('tb_employee_info');
        $this->db->order_by('Lastname', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_employee($data){
        $insert = $this->db->insert('tb_employee_info',$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function insert_employee_education($data){
        $insert = $this->db->insert('tb_emp_education_attain',$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function insert_employee_work_exp($data){
        $insert = $this->db->insert('tb_emp_work_experience',$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

}