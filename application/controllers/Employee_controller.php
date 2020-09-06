<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("employee_model", 'employee_model');
        $this->load->model("position_model", "position_model");
        $this->load->model("company_model", "company_model");
        $this->load->model("pet_model", "pet_model");
        $this->load->model('department_model','department_model');
        $this->load->model('position_model','position_model');
        $this->load->model('working_hours_model','working_hours_model');
        $this->load->model('company_model','company_model');
        $this->load->model('working_days_model','working_days_model');
        $this->load->model('role_model','role_model');
        $this->load->model('deduction_model','deduction_model');
        $this->load->model('cashbond_model','cashbond_model');
        $this->load->model('audit_trial_model','audit_trial_model');
        $this->load->helper('hupay_helper');
        // $this->load->helper('attendance_helper');
        $this->load->helper('date_helper');
        $this->load->helper('leave_helper');
        //$this->load->library('../controllers/holiday_controller');

    }
    public function viewATMAccounts(){
        $this->data['pageTitle'] = 'ATM Account No';
        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('atm_account/atm_account_no',$this->data);
        $this->load->view('global/footer');
    }
    public function getAtmAccountNoList(){
        $id = $this->session->userdata('user');
        $employeeInfo = $this->employee_model->employee_information($id);

        $employeeAtm = $this->employee_model->get_employee_atm();
        $finalListOfEmployeeAtm = array();
        if(!empty($employeeAtm)){
            foreach($employeeAtm as $value){
                $empName = ucwords($value->Lastname.", ".$value->Firstname." ".$value->Middlename);
                if($value->Middlename == ""){
                    $empName = ucwords($value->Lastname." ".$value->Firstname);
                }
                if($id != 21){
                    array_push($finalListOfEmployeeAtm, array(
                        'emp_id'=>$value->emp_id,
                        'emp_name'=>$empName,
                        'atmAccountNumber'=>$value->atmAccountNumber,
                        'action'=>'yes',
                    ));
                }
                else{
                    array_push($finalListOfEmployeeAtm, array(
                        'emp_id'=>$value->emp_id,
                        'emp_name'=>$empName,
                        'atmAccountNumber'=>$value->atmAccountNumber,
                        'action'=>'no',
                    ));
                }
            }
            $this->data['status'] = "success";
            $this->data['finalListOfEmployeeAtm'] = $finalListOfEmployeeAtm;
        }
        else{
            $this->data['status'] = "error";
        }
        echo json_encode($this->data);
    }
    public function getInformationOfAtmAccount(){
        $id = $this->input->post('id');
        $employeeInfo = $this->employee_model->employee_information($id);
        if(!empty($employeeInfo)){
            $this->data['status'] = "success";
            $this->data['atmAccountNumber'] = $employeeInfo['atmAccountNumber'];
        }
        else{
            $this->data['status'] ="error";
            $this->data['msg'] = "There was a problem, please try again.";
        }

        echo json_encode($this->data);
    }
    public function updateAtmAccountNo(){
        $id = $this->input->post('id');
        $atmAccountNo = $this->input->post('atmAccountNo');

        if($id != "" || $id != null){
            $minmaxMsg = "Please enter a 12 digit account number.";
            $this->form_validation->set_rules('atmAccountNo', 'atmAccountNo', 'required|min_length[12]|max_length[12]',
            array('required'=>'Please enter a atm account number.', 'min_length'=>$minmaxMsg, 'max_length'=>$minmaxMsg));
            if($this->form_validation->run() == FALSE){
                $this->data['status'] = "error";
                $this->data['msg'] = validation_errors();
            }
            else{
                $checkAtmNo = $this->employee_model->check_atm_no($id, $atmAccountNo);
                if(!empty($checkAtmNo)){
                    $this->data['status'] = "error";
                    $this->data['msg'] = "No changes, no updates were taken.";
                }
                else{
                    $updateAtmNoData = array('atmAccountNumber'=>$atmAccountNo);
                    $updateAtmNo = $this->employee_model->update_atm_no($id, $updateAtmNoData);
                    if($updateAtmNo == "success"){
                        $this->data['msg'] = "ATM Account No was successfully updated.";
                    }
                    else{
                        $this->data['status'] = "error";
                        $this->data['msg'] = "There was a problem updating the ATM No, please try again.";
                    }
                    $this->data['status'] = $updateAtmNo;
                }
            }
        }
        else{
            $this->data['status'] = "error";
            $this->data['msg'] = "There was a problem updating the ATM No, please try again.";
        }
        echo json_encode($this->data);
        
    }
    public function printAtmAccountreports(){
        $this->load->library('excel');
        $filename = "atm_account_number_reports";
		/*********************Add column headings START**********************/
		$this->excel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Employee Name')
                    ->setCellValue('B1', 'ATM Account Number');
        $count = 1;
        $atmAccounts = $this->employee_model->get_employee_atm();
        if(!empty($atmAccounts)){
            foreach($atmAccounts as $value){
                $empName = ucwords($value->Lastname.", ".$value->Firstname." ".$value->Middlename);
                $count++;
                $this->excel->setActiveSheetIndex(0) 
                    ->setCellValue('A'.$count, $empName)
                    ->setCellValue('B'.$count, $value->atmAccountNumber);
            }
        }
        foreach(range('A','B') as $columnID){
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $this->excel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()
            ->getStyle('A1:B1')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('abb2b9');
        $this->excel->getActiveSheet()->setTitle('atm_account_number_reports'); //give title to sheet
        $this->excel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    public function getEmployeeNames(){
        $array= array();
        $select_qry = $this->employee_model->get_active_employee();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $name = $value->Lastname . ", " . $value->Firstname . " " . $value->Middlename;

				if ($value->Middlename == ""){
					$name = $value->Lastname . ", " . $value->Firstname;
				}

				$array[] = $name;
            }
        }
        $this->data['status'] = "success";
        $this->data['employeeNames'] = $array;

        echo json_encode($this->data);
    }


    public function getAllEmployee(){
        $select_qry = $this->employee_model->get_all_employee();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                if (($value->role_id != 1 || $value->dept_id != 1) && $value->ActiveStatus == 1) {
                    // $finalData .="<option value=".$value->emp_id.">".$value->Lastname .", " . $value->Firstname . " " . $value->Middlename."</option>";
                    $finalData .= "<tr id='".$value->emp_id."' style='text-align:center;'>";
                        $finalData .= "<td><button class='btn btn-link employee-btn' id=".$value->emp_id." data-dismiss='modal'>" . $value->Lastname .", " . $value->Firstname . " " . $value->Middlename . "</button></td>";
                    $finalData .= "</tr>";
                }
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function getEmployeeWithoutBioId(){
        $select_qry = $this->employee_model->get_active_employee_with_no_bio();
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $fullName = $value->Lastname . ", " . $value->Firstname . " " . $value->Middlename;
                if ($value->Middlename == ""){
                    $fullName = $value->Lastname . ", " . $value->Firstname;
                }

                $finalData .= "<tr class='".$value->emp_id."'>";
                    $finalData .= "<td class='employee-name-".$value->emp_id."'>".$fullName."</td>";
                    $finalData .= "<td>";
                        $finalData .= "<button id=".$value->emp_id." class='add-attendance-btn btn btn-sm btn-outline-success' data-toggle='modal' data-target='#generateCutOffAttendanceModal'>Add Attendance</button>";
                    $finalData .= "</td>";
                $finalData .= "</tr>";
            }
        }

        $this->data['finalData'] = $finalData;
        $this->data['status'] ="success";
        echo json_encode($this->data);

    }

    public function viewEmployeeList(){
        $this->data['pageTitle'] = 'Employee List';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('employee/employee_list');
        $this->load->view('global/footer');
    }

    public function getEmployeeList(){
        $user_id = $this->session->userdata('user');
        $select_qry = $this->employee_model->get_all_list_of_employee();
        $employeeInfo = $this->employee_model->employee_information($user_id);
        $role = $employeeInfo['role_id'];
        $finalData = "";
        if(!empty($select_qry)){
            foreach ($select_qry as $value) {
                $select_position_qry = $this->position_model->get_employee_position($value->position_id);

                $position_val = $select_position_qry['Position'];

                // for active or inactive
                $active_stat = $value->ActiveStatus;
                // if active
                $active_value_action = "";
                if ($active_stat == 1){
                    // value is dynamic
                    $active_value_action = "Inactive";
                }

                // if inactive
                if ($active_stat == 0){
                    // value is dynamic
                    $active_value_action = "&nbsp;&nbsp;Active&nbsp;&nbsp;";
                }

                $active_value = "";
                if ($active_stat == 1){
                    // value is dynamic
                    $active_value = "Active";
                }

                if ($active_stat == 0){
                    // value is dynamic
                    $active_value = "Inactive";
                    if ($value->resignation_date != ""){
                        $active_value .= "<br/>";
                        $active_value .= "<small><span class='color-gray'><i>".date_format(date_create($value->resignation_date),"F d, Y")."</i></span></small>";
                    }
                }

                $middleName = $value->Middlename;
                $contactNo = $value->ContactNo;
                $emailAddress = $value->EmailAddress;
                $sssNo = $value->SSS_No;
                $pagibigNo = $value->PagibigNo;
                $tinNo = $value->TinNo;
                $philhealthNo = $value->PhilhealthNo;

                $unfillUp_fields = 0;
                if ($middleName == ""){
                    $unfillUp_fields++;
                }

                if ($contactNo == ""){
                    $unfillUp_fields++;
                }

                if ($emailAddress == ""){
                    $unfillUp_fields++;
                }

                if ($sssNo == ""){
                    $unfillUp_fields++;
                }

                if ($pagibigNo == ""){
                    $unfillUp_fields++;
                }

                if ($tinNo == ""){
                    $unfillUp_fields++;
                }

                if ($philhealthNo == ""){
                    $unfillUp_fields++;
                }


                $unfill_info ='<i class="fas fa-check" style="color:#196F3D"></i>';
                if ($unfillUp_fields != 0){
                    $unfill_info = "<label class='badge badge-danger' id='unfill_fields'>".$unfillUp_fields."</label><span>";
                }


                $withAtm = '<i class="fas fa-times" style="color:#CB4335"></i>';
                if ($value->WithAtm == 1) {
                    $withAtm = '<i class="fas fa-check" style="color:#196F3D"></i>';
                }

                $select_company_qry = $this->company_model->get_company_info($value->company_id);
                $imagesPath = base_url().'assets/images/';
                if ($value->role_id != 1 || $value->dept_id != 1){
                    if ($user_id != 21){
                        $finalData .= "<tr id=".$value->emp_id.">";
                        $finalData .= "<td><img id='profile_pic_table' class='logo-company-image-table' src='".$imagesPath. $select_company_qry['logo_source'] . "'/></td>";
                        $finalData .= "<td><img id='profile_pic_table' class='profile-image-table' src='".$imagesPath. $value->ProfilePath . "'/></td>";
                        $finalData .= "<td>".$unfill_info."</td>";
                        $finalData .= "<td>".$withAtm."</td>";
                        $finalData .= "<td>".$value->Lastname. ", " .  $value->Firstname. " " .$value->Middlename . "</td>";
                        $finalData .= "<td id='readmoreValue' style='font-size:small;'>".htmlspecialchars($value->Address)."</td>";
                        $finalData .= "<td style='font-size:small;'>".$position_val."</td>";
                        $finalData .= "<td>".$active_value."</td>";
                        $finalData .= "<td>";
                        if ($value->ActiveStatus == 1){

                            $finalData .= '<button class="protip btn btn-sm btn-outline-success" data-pt-title="Edit <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong> Info" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-pencil-alt"></i></button>';
                            
                            $finalData .= '<button class="protip btn btn-sm btn-outline-primary" data-pt-title="Make <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong> '.$active_value_action.' Employee" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-signal"></i></button>';
                            $finalData .= '<button class="protip btn btn-sm btn-outline-success" data-pt-title="View <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong> Info" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-eye"></i></button>';
                            $finalData .= '<button class="protip btn btn-sm btn-outline-warning" data-pt-title="Upload <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong> 201 File" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-upload"></i></button>';
                            $finalData .= '<button class="protip btn btn-sm btn-outline-secondary" data-pt-title="Print <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong> Info" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-print"></i></button>';
                            $finalData .= '<button class="protip btn btn-sm btn-outline-danger" data-pt-title="View LFC Employment History of <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong>" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-briefcase"></i></button>';
                            $finalData .= '<button class="protip btn btn-sm btn-outline-success" data-pt-title="Update ATM records of <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong>" data-pt-scheme="blue" data-pt-position="left"><i class="far fa-credit-card"></i></button>';
                            $finalData .= '<button class="protip btn btn-sm btn-outline-success" data-pt-title="Add Increase Information of <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong>" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-ruble-sign"></i></button>';

                            if ($role == 1){


                                if ($value->generated_code == ""){

                                    $finalData .= '<button class="protip btn btn-sm btn-outline-primary" data-pt-title="Generate Code for Change Password of <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong>" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-lock"></i></button>';
                                }

                                else {
                                    $finalData .= '<button class="protip btn btn-sm btn-outline-primary" data-pt-title="View Generated Code for Change Password of <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong>" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-lock"></i></button>';
                                }
                            }
                        }
                        else {
                            $finalData .= '<button class="protip btn btn-sm btn-outline-primary" data-pt-title="Make <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong> '.$active_value_action.' Employee" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-signal"></i></button>';


                            $finalData .= '<button class="protip btn btn-sm btn-outline-success" data-pt-title="View <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong> Info" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-eye"></i></button>';
                            $finalData .= '<button class="protip btn btn-sm btn-outline-secondary" data-pt-title="Print <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong> Info" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-print"></i></button>';

                            $finalData .= '<button class="protip btn btn-sm btn-outline-danger" data-pt-title="View LFC Employment History of <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong>" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-briefcase"></i></button>';
                        }
                    }
                    else {
                        $finalData .= "<tr id=".$value->emp_id.">";
                            $finalData .= "<td><img id='profile_pic_table' class='logo-company-image-table' src='".$imagesPath. $select_company_qry['logo_source'] . "'/></td>";
                            $finalData .= "<td><img id='profile_pic_table' class='profile-image-table' src='".$imagesPath. $value->ProfilePath . "'/></td>";
                            $finalData .= "<td>".$unfill_info."</td>";
                            $finalData .= "<td>".$withAtm."</td>";
                            $finalData .= "<td>".$value->Lastname. ", " .  $value->Firstname. " " .$value->Middlename . "</td>";
                            $finalData .= "<td id='readmoreValue' style='font-size:small;'>".htmlspecialchars($value->Address)."</td>";
                            $finalData .= "<td style='font-size:small;'>".$position_val."</td>";
                            $finalData .= "<td>".$active_value."</td>";
                            $finalData .= "<td>";
                                $finalData .= '<button class="protip btn btn-sm btn-outline-success" data-pt-title="View <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong> Info" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-eye"></i></button>';
                                $finalData .= '<button class="protip btn btn-sm btn-outline-secondary" data-pt-title="Print <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong> Info" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-print"></i></button>';

                                
                                $finalData .= '<button class="protip btn btn-sm btn-outline-danger" data-pt-title="View LFC Employment History of <strong>'.$value->Firstname. " " .  $value->Middlename. " " .$value->Lastname.'</strong>" data-pt-scheme="blue" data-pt-position="left"><i class="fas fa-briefcase"></i></button>';
                            $finalData .= "</td>";
                        $finalData .= "</tr>";
                    }
                }
            }
        }


        $this->data['finalData'] = $finalData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }

    public function viewEmployeeRegistration(){
        $this->data['pageTitle'] = 'Employee Registration';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('employee/employee_registration');
        $this->load->view('global/footer');
    }

    public function addEmployee(){
        $lastname = $this->input->post('lastname');
        $firstname = $this->input->post('firstname');
        $middlename = $this->input->post('middlename');
        $contactNo = $this->input->post('contactNo');
        $address = $this->input->post('address');
        $civilStatus = $this->input->post('civilStatus');
        $birthday = $this->input->post('birthday');
        $gender = $this->input->post('gender');
        $email = $this->input->post('email');
        $petName = $this->input->post('petName');
        $petType = $this->input->post('petType');

        $department = $this->input->post('department');
        $position = $this->input->post('position');
        $salary = $this->input->post('salary');
        $dateHired = $this->input->post('dateHired');
        $workingHours = $this->input->post('workingHours');
        $headsName = $this->input->post('headsName');
        $company = $this->input->post('company');
        $employmentType = $this->input->post('employmentType');
        $workingDays = $this->input->post('workingDays');

        $sssNo = $this->input->post('sssNo');
        $pagibigNo = $this->input->post('pagibigNo');
        $tinNo = $this->input->post('tinNo');
        $philhealthNo = $this->input->post('philhealthNo');

        $educationAttainment = $this->input->post('educationAttainment');

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $confirmPassword = $this->input->post('confirmPassword');
        $role = $this->input->post('role');



        $birthday_month = substr($birthday,0,2);
        $birthday_day = substr(substr($birthday, -7), 0,2);
        $birthday_year = substr($birthday, -4);

        $datehired_month = substr($dateHired,0,2);
        $datehired_day = substr(substr($dateHired, -7), 0,2);
        $datehired_year = substr($dateHired, -4);

        $validationRequiredMsg = "Please enter a ";
        $validationProvideMsg = "Please provide a ";
        $validationSelectMsg = "Please select a ";
        $this->form_validation->set_rules('lastname','lastname','required',array(
            'required'=>$validationRequiredMsg.'lastname.'
        ));
        $this->form_validation->set_rules('firstname','firstname','required',array(
            'required'=>$validationRequiredMsg.'firstname.'
        ));
        $this->form_validation->set_rules('address','address','required',array(
            'required'=>$validationRequiredMsg.'address.'
        ));
        $this->form_validation->set_rules('civilStatus','civilStatus','required',array(
            'required'=>$validationSelectMsg.'a civil status.'
        ));
        $this->form_validation->set_rules('birthday','birthday','required',array(
            'required'=>$validationProvideMsg.'birthday.'
        ));
        $this->form_validation->set_rules('gender','gender','required',array(
            'required'=>$validationSelectMsg.'a gender.'
        ));
        $this->form_validation->set_rules('email','email','trim|valid_email|is_unique[tb_employee_info.EmailAddress]',array(
            'valid_email'=>"Please enter a valid email address.",
            'is_unique'=>"Email already exist.",
        ));
        $this->form_validation->set_rules('department','department','required',array(
            'required'=>$validationSelectMsg.'department.'
        ));
        $this->form_validation->set_rules('position','position','required',array(
            'required'=>$validationSelectMsg.'position.'
        ));
        $this->form_validation->set_rules('salary','salary','required',array(
            'required'=>$validationRequiredMsg.'salary.'
        ));
        $this->form_validation->set_rules('dateHired','dateHired','required',array(
            'required'=>$validationSelectMsg.'date hired.'
        ));
        $this->form_validation->set_rules('workingHours','workingHours','required',array(
            'required'=>$validationSelectMsg.'working hours.'
        ));
        $this->form_validation->set_rules('company','company','required',array(
            'required'=>$validationSelectMsg.'company.'
        ));
        $this->form_validation->set_rules('employmentType','employmentType','required',array(
            'required'=>$validationSelectMsg.'employment type.'
        ));
        $this->form_validation->set_rules('workingDays','workingDays','required',array(
            'required'=>$validationSelectMsg.'working days.'
        ));

        $this->form_validation->set_rules('sssNo','sssNo','trim|is_unique[tb_employee_info.SSS_No]',array(
            'is_unique'=>"SSS No. already exist.",
        ));
        $this->form_validation->set_rules('pagibigNo','pagibigNo','trim|is_unique[tb_employee_info.PagibigNo]',array(
            'is_unique'=>"Pag-ibig No. already exist.",
        ));
        $this->form_validation->set_rules('tinNo','tinNo','trim|is_unique[tb_employee_info.TinNo]',array(
            'is_unique'=>"SSS No. already exist.",
        ));
        $this->form_validation->set_rules('philhealthNo','philhealthNo','trim|is_unique[tb_employee_info.PhilhealthNo]',array(
            'is_unique'=>"SSS No. already exist.",
        ));


        $this->form_validation->set_rules('educationAttainment','educationAttainment','required',array(
            'required'=>$validationSelectMsg.'education attainment.'
        ));

        $this->form_validation->set_rules('username','username','trim|required|is_unique[tb_employee_info.Username]',array(
            'is_unique'=>"Username already exist.",
            'required'=>$validationRequiredMsg."username.",
        ));
        $this->form_validation->set_rules('password','password','required',array(
            'required'=>$validationRequiredMsg."password.",
        ));
        $this->form_validation->set_rules('confirmPassword','confirmPassword','required|matches[password]',array(
            'required'=>$validationRequiredMsg."confirm passwod.",
            'matches'=>"Password does not match.",
        ));
        $this->form_validation->set_rules('role','role','required',array(
            'required'=>$validationSelectMsg."role.",
        ));

        $errorMessages = "";
        $errorCount = 0;
        $contactNoLength = strlen($contactNo);

        //for contact no validation start
        if($contactNoLength != 7 && $contactNoLength != 9 && $contactNoLength != 11){
            $errorCount +=1;
            $errorMessages .= '<p>Please provide a valid contact no.</p>';
        }
        if ($contactNoLength == 11) {
            $regex = '/^[0]{1}[9]{1}[0-9]{9}$/i';

            if (!preg_match($regex, $contactNo)) {
                $errorCount += 1;
                $errorMessages .= '<p>Please provide a valid contact no that starts with 09.</p>';
            }

        }
        //for contact no validation end

        //for birthday validation start
        if($birthday != ""){

        
            if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$birthday)) {
                $errorMessages .= "<p>Birthdate not math to the current format mm/dd/yyyy</p>";
                $errorCount += 1;
            }
            if ($birthday_year % 4 == 0 && $birthday_month == 2 && $birthday_day >= 30){
                $errorCount += 1;
                $errorMessages .= "<p>Invalid Birthday date.</p>";
            }
            if ($birthday_year % 4 != 0 && $birthday_month == 2 && $birthday_day >= 29){
                $errorCount += 1;
                $errorMessages .= "<p>Invalid Birthday date.</p>";
            }
            if (($birthday_month == 4 || $birthday_month == 6 || $birthday_month == 9 || $birthday_month == 11)
                    && $birthday_day  >= 31){
                $errorCount += 1;
                $errorMessages .= "<p>Invalid Birthday date.</p>";

            }
        }
        //for birthday validation end

        //for civil status vliadation start
        if($civilStatus != "Single" && $civilStatus !="Married"){
            $errorCount += 1;
            $errorMessages .= "<p>Invalid civil status.</p>";
        }
        //for civil status vliadation end

        //forgender vliadation start
        if($gender != "Male" && $gender !="Female"){
            $errorCount += 1;
            $errorMessages .= "<p>Invalid gender.</p>";
        }
        //for gender vliadation end

        //for validation of role department and position start

        //add !is_numeric($role) later
        $checkDepartment = $this->department_model->get_department($department);
        $checkPosition = $this->position_model->get_employee_position($position);
        $checkRole = $this->role_model->get_role($role);
        $dept_id = null;
        if($department != ""){
            if(!is_numeric($role) || !is_numeric($department) || !is_numeric($position)){
                $errorCount += 1;
                $errorMessages .= "<p>There was a problem on the selected Department, Position or Roles.</p>";
            }
            else if (empty($checkRole) || empty($checkDepartment) || empty($checkPosition)){
                $errorCount += 1;
                $errorMessages .= "<p>There was a problem on the selected Department, Position or Roles.</p>";
            }
            else{
                $dept_id = $checkPosition['dept_id'];
            }
        }
        
        //for validation of role department and position end


        //for valiadtion of date hired start
        if($dateHired != ""){
            if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$dateHired)) {
                $errorMessages .= "<p>Date hired not math to the current format mm/dd/yyyy</p>";
                $errorCount += 1;
            }
            if ($datehired_year % 4 == 0 && $datehired_month == 2 && $datehired_day >= 30){
                $errorCount += 1;
                $errorMessages .= "<p>Invalid Date Hired date.</p>";
            }
            if ($datehired_year % 4 != 0 && $datehired_month == 2 && $datehired_day >= 29){
                $errorCount += 1;
                $errorMessages .= "<p>Invalid Date Hired date.</p>";
            }
            if (($datehired_month == 4 || $datehired_month == 6 || $datehired_month == 9 || $datehired_month == 11)
                    && $datehired_day  >= 31){
                $errorCount += 1;
                $errorMessages .= "<p>Invalid Date Hired date.</p>";

            }
        }
        //for validation of date hired end

        //for validation of working hours start
        if($workingHours != ""){
            $checkWorkingsHours = $this->working_hours_model->get_info_working_hours($workingHours);
            if(empty($checkWorkingsHours)){
                $errorCount += 1;
                $errorMessages .= "<p>There was a problem on the selected working hours.</p>";
            }
        }
        //for validation of working hours end

        //for validation of heads name start
        $head_emp_id = null;
        if($headsName != ""){
            $errorHeadsName = false;
            $checkHeadsName = $this->employee_model->get_active_employee();
            if(!empty($checkHeadsName)){
                foreach ($checkHeadsName as $key => $valueHeadsName) {
                    $name = $valueHeadsName->Lastname . ", " . $valueHeadsName->Firstname . " " . $valueHeadsName->Middlename;
                    if ($valueHeadsName->Middlename == ""){
                        $name = $valueHeadsName->Lastname . ", " . $valueHeadsName->Firstname;
                    }
                    if($headsName == $name){
                        $errorHeadsName = false;
                        $head_emp_id = $valueHeadsName->emp_id;
                    }
                    
                }

            }
            else{
                $errorHeadsName = true;
            }
            if($errorHeadsName){
                $errorCount += 1;
                $errorMessages .= "<p>There was a problem on the immediate head's name.</p>";
            }
            //$this->data['pasok'] = $head_emp_id;
        }
        //for validation of heads name end

        //for validation of company start
        $checkCompany = $this->company_model->get_company_info($company);
        if($company != ""){
            if(empty($checkCompany)){
                $errorCount += 1;
                $errorMessages .= "<p>There was a problem on the selected company.</p>";
            }
        }
        //for validation of company end

        //for employment type validation start
        $employment_type_stat = 1;
        if ($employmentType == "Provisional"){
            $employment_type_stat = 0;
        }
        if ($employmentType == "OJT/Training"){
            $employment_type_stat = 2;
        }
        //for employment type validation end

        //for working days validation start
        $checkWorkingDays = $this->working_days_model->get_working_days_info($workingDays);
        if($workingDays != "" ){
            if(empty($checkWorkingDays)){
                $errorCount += 1;
                $errorMessages .= "<p>There was a problem on the selected working days.</p>";
            }
        }
        //for working days vlaiation end

        if($this->form_validation->run() == FALSE){
            $this->data['status'] = "error";
            $errorCount += 1;
            $errorMessages .= validation_errors();
        }


        //for sssNo validation start

        //for sssNo validation end


        //for educational validation start
        // if($educationAttainment != ""){
        //     $this->form_validation->set_rules('school_name[]','school_name','required',array(
        //         'required'=>"Please fill-up all school name field"
        //     ));
        // }

        //for educational validation end

        //for success
        if($errorCount == 0){

            $final_birthdate = setDateFormat($birthday);
            $final_date_hired = setDateFormat($dateHired);
            if ($gender == "Male"){
                $profileImageName = "male.jpg";
                $profilePath = "img/profile images/default/" . $profileImageName;
            }
            if ($gender == "Female"){
                $profileImageName = "female.jpg";
                $profilePath = "img/profile images/default/" . $profileImageName;
            }
            $insertEmployeeData = array(
                'emp_id'=>'',
                'bio_id'=>'',
                'Lastname'=>$lastname,
                'Firstname'=>$firstname,
                'Middlename'=>$middlename,
                'Address'=>$address,
                'Birthdate'=>$final_birthdate,
                'Gender'=>$gender,
                'ContactNo'=>$contactNo,
                'EmailAddress'=>$email,
                'CivilStatus'=>$civilStatus,
                'dept_id'=>$department,
                'position_id'=>$position,
                'Salary'=>$salary,
                'DateHired'=>$final_date_hired,
                'working_hours_id'=>$workingHours,
                'head_emp_id'=>$head_emp_id,
                'company_id'=>$company,
                'employment_type'=>$employment_type_stat,
                'working_days_id'=>$workingDays,
                'SSS_No'=>$sssNo,
                'PagibigNo'=>$pagibigNo,
                'TinNo'=>$tinNo,
                'PhilhealthNo'=>$philhealthNo,
                'highest_educational_attain'=>$educationAttainment,
                'role_id'=>$role,
                'Username'=>$username,
                'Password'=>password_hash($password, PASSWORD_DEFAULT),
                'ActiveStatus'=>'1',
                'ProfilePath'=>$profilePath,
                'ProfileImage'=>$profileImageName,
                'DateCreated'=>getDateDate(),
            );
            $insertEmployee = $this->employee_model->insert_employee($insertEmployeeData);
            //$insertEmployee = 'asd';
            //for insertertion of pet
            $counter = 0;
            foreach ($petName as $value) {
                $insertPetData = array(
                    'emp_id'=>$insertEmployee,
                    'pet_type'=>ucwords($petType[$counter]),
                    'pet_name'=>ucwords($petName[$counter]),
                );
                $insertPet = $this->pet_model->insert_pet($insertPetData);
                $counter++;
            }

            //for insertion of position history 
            $insertPositionHistoryData = array(
                'history_position_id'=>'',
                'emp_id'=>$insertEmployee,
                'dept_id'=>$department,
                'position_id'=>$position,
                'Salary'=>$salary,
                'DateHired'=>$final_date_hired,
                'DateCreated'=>getDateDate(),
            );
            $insertPositionHistory = $this->position_model->insert_position_history($insertPositionHistoryData);

            //for insertion of eduction start
            if($educationAttainment == "Secondary"){
                $insertEmployeeEducationData = array(
                    'emp_id'=>$insertEmployee,
                    'type'=>0,
                    'school_name'=>$this->input->post('school_name')[0],
                    'course'=>'',
                    'year_from'=>$this->input->post('year_from')[0],
                    'year_to'=>$this->input->post('year_to')[0],

                );
                $insertEmployeeEducation = $this->employee_model->insert_employee_education($insertEmployeeEducationData);
            }
            if($educationAttainment == "Tertiary"){
                $count = 1;
                $counter = 0;
                foreach($this->input->post('school_name') as $row) {
                    $type = 0;
                    if ($counter > 0){
                        $type = 1;
                    }   
                    if($type == 1){
                        $insertEmployeeEducationData = array(
                            'emp_id'=>$insertEmployee,
                            'type'=>$type,
                            'school_name'=>$row,
                            'course'=>$this->input->post('course')[0],
                            'year_from'=>$this->input->post('year_from')[$counter],
                            'year_to'=>$this->input->post('year_to')[$counter],

                        );
                    }
                    else{
                        $insertEmployeeEducationData = array(
                            'emp_id'=>$insertEmployee,
                            'type'=>$type,
                            'school_name'=>$row,
                            'course'=>"",
                            'year_from'=>$this->input->post('year_from')[$counter],
                            'year_to'=>$this->input->post('year_to')[$counter],

                        );
                    }
                    
                    $insertEmployeeEducation = $this->employee_model->insert_employee_education($insertEmployeeEducationData);
                    $counter ++;
                    $count ++;
                }
            }

            //for insertion of education end


            //for insertion of work start
            $count = 1;
            $counter = 0;
            foreach($this->input->post('work_position') as $row) {
                $insertEmployeeWorkExpData = array(
                    'emp_id'=>$insertEmployee,
                    'position'=>$row,
                    'company_name'=>$this->input->post('company_name')[$counter],
                    'job_description'=>$this->input->post('job_description')[$counter],
                    'year_from'=>$this->input->post('work_year_from')[$counter],
                    'year_to'=>$this->input->post('work_year_to')[$counter],
                );
                $insertEmployeeWorkExp = $this->employee_model->insert_employee_work_exp($insertEmployeeWorkExpData);
                $counter ++;
                $count++;
            }
            
            //for insertion of work end

            //for insertion of year total deduction start
            
            $insertYtdData = array(
                'ytd_id'=>'',
                'emp_id'=>$insertEmployee,
                'ytd_Gross'=>0,
                'ytd_Allowance'=>0,
                'ytd_Tax'=>0,
                'Year'=>date("Y"),
                'DateCreated'=>getDateDate(),
            );
            $insertYtd = $this->deduction_model->insert_year_total_deduction($insertYtdData);
            //for insertion of year total deduction end

            //for insertion of cashbond start
            $cashBondTmp = ($salary * .02)/2;
            $cashbond = round($cashBondTmp, 2);
            $insertCashbondData = array(
                'cashbond_id'=>'',
                'emp_id'=>$insertEmployee,
                'cashbondValue'=>$cashbond,
                'DateCreated'=>getDateDate(),
            );
            $insertCashbond = $this->cashbond_model->insert_cashbond($insertCashbondData);
            //for insertion of cashbond  end


            //for insertion of leave start
            insertEmpDefaultLeave($insertEmployee);
            //for insertion of leave end

            //for insertion of audit trail start
            $module = "Employee Registration";
            $dateTime = getDateTime();
            $involve_emp_id =$this->session->userdata('user');
            $insertAuditTrialData = array(
                'audit_trail_id'=>'',
                'file_emp_id'=>$insertEmployee,
                'approve_emp_id'=>0,
                'involve_emp_id'=>$involve_emp_id,
                'module'=>$module,
                'task_description'=>"Add Employee",
            );
            $insertAuditTrial = $this->audit_trial_model->insert_audit_trial($insertAuditTrialData);
            //for insertion of audit trail end

            $this->data['msg'] = "Employee ".$firstname." ".$middlename." ".$lastname. " was successfully registered";
            $this->data['status'] = "success";

        }
        else{
            //$this->data['msg'] = $errorMessages;
            $this->data['msg'] = $errorMessages;
        }
        //$this->data['asd'] = $petName;
        
        echo json_encode($this->data);
    }
}