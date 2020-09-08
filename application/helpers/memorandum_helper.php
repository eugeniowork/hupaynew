<?php 
	function getRecipientMultipleMemo($memo_id){
		$CI =& get_instance();
        $CI->load->model('memorandum_model');
        $CI->load->model('department_model');
        $multipleMemo = $CI->memorandum_model->get_multiple_memo($memo_id);
        $finalData = "";
        $countMultiple = 0;
        if(!empty($multipleMemo)){
        	$countMultiple = count($multipleMemo);
        }
        if($countMultiple == 1){
        	$count = 1;
        	foreach ($multipleMemo as $value) {
        		$checkAll = "";
				$checkDept = "";
				$checkEmp = "";

				$to = "";
				$disabled = 'disabled="disabled"';
				$choose = "";

				if ($value->recipient == "All") {
					$checkAll = "checked='checked'";
				}
				else if($value->recipient == "Department"){
                    $select_dept_qry = $CI->department_model->get_department($value->dept_id);
                    $to = $select_dept_qry['Department'];
					$choose = "<a href='#' id='choose_department_memo'>Choose</a>";

					$checkDept = "checked='checked'";
					$disabled = "";
                }
                else if($value->recipient == "Specific Employee"){
                    $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);
                    $to = $select_emp_qry['Lastname'] . ', ' . $select_emp_qry['Firstname'] . ' ' . $select_emp_qry['Middlename'];
					if ($select_emp_qry['Middlename'] == ""){
						$to = $select_emp_qry['Lastname'] . ', ' . $select_emp_qry['Firstname'];
					}
					$choose = "<a href='#' id='choose_employee_memo'>Choose</a>";

					$checkEmp = "checked='checked'";
					$disabled = "";
                }
                $remove_button = '&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-sm" id="remove_recipient'.$count.'">-</button>';
				if ($count == 1){
					$remove_button = "";
				}


				$allOption = '<label class="radio-inline"><input required="required" '.$checkAll.' type="radio" value="All" name="update_optRecipient'.$count.'">All</label>';
				if ($count > 1){
					$allOption = "";
				}

				if ($count == 1) {
					$finalData .= '<div class="form-group">
 						<label class="control-label col-sm-3 col-sm-offset-1"><b>Recipient:</b></label>
 			
 						'.$allOption.'
						<label class="radio-inline"><input required="required" '.$checkDept.'  type="radio" value="Department" name="update_optRecipient'.$count.'">Department</label>
						<label class="radio-inline"><input required="required" '.$checkEmp.' type="radio" value="Specific Employee" name="update_optRecipient'.$count.'">Specific Employee</label>
						'.$remove_button.'
					</div>';

					$finalData .= '<div class="form-group">
						<label class="control-label col-sm-3 col-sm-offset-1" style=""><b>To:</b></label>
						<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">


						<input type="text" class="input-only form-control" '.$disabled.' value="'.$to.'" name="update_to'.$count.'" placeholder="" required="required" autocomplete="off"/>


						</div>
						<label class="col-sm-1 control-label"><div id="choose'.$count.'">'.$choose.'</div></label>
					</div>';

					$finalData .= '<div id="update_div_recipient">';

					$finalData .= '</div>';

					
				}
				$count++;
        	}
        }
        else{
        	$count = 1;
        	foreach ($multipleMemo as $key => $value) {
        		$checkAll = "";
				$checkDept = "";
				$checkEmp = "";

				$to = "";
				$disabled = 'disabled="disabled"';
				$choose = "";

				if ($value->recipient == "All") {
					$checkAll = "checked='checked'";
				}
				else if($value->recipient == "Department"){
	                $select_dept_qry = $CI->department_model->get_department($value->dept_id);
	                $to = $select_dept_qry['Department'];
					$choose = "<button class='btn btn-link choose-department-memo'>Choose</button> <a href='#' id='choose_department_memo'>Choose</a>";

					$checkDept = "checked='checked'";
					$disabled = "";
	            }
	            else if($value->recipient == "Specific Employee"){
	                $select_emp_qry = $CI->employee_model->employee_information($value->emp_id);
	                $to = $select_emp_qry['Lastname'] . ', ' . $select_emp_qry['Firstname'] . ' ' . $select_emp_qry['Middlename'];
					if ($select_emp_qry['Middlename'] == ""){
						$to = $select_emp_qry['Lastname'] . ', ' . $select_emp_qry['Firstname'];
					}
					$choose = "<a href='#' id='choose_employee_memo'>Choose</a>";

					$checkEmp = "checked='checked'";
					$disabled = "";
	            }
	            if ($count == 1){
					$remove_button = "";
				}


				$allOption = '<label class="radio-inline"><input required="required" '.$checkAll.' type="radio" value="All" name="update_optRecipient'.$count.'">All</label>';
				if ($count > 1){
					$allOption = "";
				}
				if ($count == 1) {
					$finalData .= '<div class="form-group">
						<label class="control-label col-sm-3 col-sm-offset-1"><b>Recipient:</b></label>
				
							'.$allOption.'
						<label class="radio-inline"><input required="required" '.$checkDept.'  type="radio" value="Department" name="update_optRecipient'.$count.'">Department</label>
						<label class="radio-inline"><input required="required" '.$checkEmp.' type="radio" value="Specific Employee" name="update_optRecipient'.$count.'">Specific Employee</label>
						'.$remove_button.'
					</div>';

					$finalData .= '<div class="form-group">
						<label class="control-label col-sm-3 col-sm-offset-1" style=""><b>To:</b></label>
						<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">


						<input type="text" class="input-only form-control" '.$disabled.' value="'.$to.'" name="update_to'.$count.'" placeholder="" required="required" autocomplete="off"/>


						</div>
						<label class="col-sm-1 control-label"><div id="choose'.$count.'">'.$choose.'</div></label>
					</div>';

				}
				else{
					$finalData .= '<div id="update_div_recipient">';
						$finalData .= '<div id="update_recipient_mother_div'.$count.'">';
							$finalData .= '<div class="form-group">
			 						<label class="control-label col-sm-3 col-sm-offset-1"><b>Recipient:</b></label>
			 			
			 						'.$allOption.'
									<label class="radio-inline"><input required="required" '.$checkDept.'  type="radio" value="Department" name="update_optRecipient'.$count.'">Department</label>
									<label class="radio-inline"><input required="required" '.$checkEmp.' type="radio" value="Specific Employee" name="update_optRecipient'.$count.'">Specific Employee</label>
									'.$remove_button.'
								</div>';

							$finalData .= '<div class="form-group">
									<label class="control-label col-sm-3 col-sm-offset-1" style=""><b>To:</b></label>
									<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">


									<input type="text" class="input-only form-control" '.$disabled.' value="'.$to.'" name="update_to'.$count.'" placeholder="" required="required" autocomplete="off"/>


									</div>
									<label class="col-sm-1 control-label"><div id="choose'.$count.'">'.$choose.'</div></label>
								</div>';
							$finalData .= '</div>';
					$finalData .= '</div>';
				}
				$count++;
        	}
        	
        }
        return $finalData;
	}

	function getAllMemoNotif(){
		$CI =& get_instance();
        $CI->load->model('memorandum_model');
        $CI->load->model('employee_model');
        $CI->load->library('session');
   		$emp_id = $CI->session->userdata('user');
        $select_qry = $CI->memorandum_model->get_memorandum_notif_of_employee($emp_id);
        $finalData = "";
        if(!empty($select_qry)){
        	foreach ($select_qry as $value) {
        		$select_from_qry = $CI->employee_model->employee_information($value->from_emp_id);
        		$profile_path = base_url()."assets/images/".$select_from_qry['ProfilePath'];

				$fullName = $select_from_qry['Firstname'] . " " . $select_from_qry['Lastname'];

				$dateCreated = date_format(date_create($value->dateCreated), 'F d, Y');

				$time = date_format(date_create($value->dateCreated), 'g:i A');
				$finalData .= '<div class="notif-content">
                    <div class="d-flex flex-row">
                        <img src="'.$profile_path.'">

                        <div class="notif-content-sub">
                            <b>'.$fullName.'</b> about <b>'.$value->notif_type.'</b> on
							<b>'.$dateCreated.'</b> at <b>'.$time.'</b>
                        </div>
                    </div>
                </div>';

				
        	}
        }
        return $finalData;
	}
 ?>