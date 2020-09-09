<?php 

	function getDependentInfoToProfile(){
		$CI =& get_instance();
        $CI->load->model('dependent_model');
        $CI->load->library('session');
        $emp_id = $CI->session->userdata('user');
        $finalData = "";
        $dependent = $CI->dependent_model->get_dependent_data($emp_id);
        if(!empty($dependent)){
        	foreach ($dependent as $value) {
        		if ($value->Birthdate != "0000-00-00") {
					$date_create = date_create($value->Birthdate);
					$date_format = date_format($date_create, 'F d, Y');
				}

				else {
					$date_format = "No Info";
				}
				$finalData .='
					<div class="col-lg-4">
						<span class="title">Full Name</span>
						<span class="form-control value">
							'.$value->Fullname.'
						</span>
					</div>
					<div class="col-lg-4">
						<span class="title">Birthdate</span>
						<span class="form-control value">
							'.$date_format.'
						</span>
					</div>

				';
        	}
        }
        else{
        	$finalData .='<div class="col-lg-12">
        		<strong>There is no declare dependent</strong>
        	</div>';
        }

        return $finalData;
	}



 ?>