<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');

    function getDatePayroll(){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');
        //$CI->load->library('session');

        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        
        $current_date_time = date_format($date, 'Y-m-d');

        $year = date("Y");
        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
        $cutOff = $CI->cut_off_model->get_cut_off();
        $date_payroll = "N/A";
        if(!empty($cutOff)){
            foreach($cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom),'Y-m-d');
				if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
					//echo "wew";
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
					//echo $date_from . "sad";
					//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($value->datePayroll),'Y-m-d');
				}
            }
        }
        return $date_payroll;
    }

    function getCutOffPeriodLatest(){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');
        //$CI->load->library('session');

        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        
        $current_date_time = date_format($date, 'Y-m-d');

        $year = date("Y");
        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
        $cutOff = $CI->cut_off_model->get_cut_off();
        $date_payroll = "N/A";
        if(!empty($cutOff)){
            foreach($cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom),'Y-m-d');
				if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
					//echo "wew";
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
					//echo $date_from . "sad";
					//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($value->datePayroll),'Y-m-d');
				}
            }
        }
        return date_format(date_create($final_date_from),'F d, Y') . " - " . date_format(date_create($final_date_to),'F d, Y');
    }
    function getCutOffAttendanceDateCount(){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');
        $dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');

		$year = date("Y");

        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
        $cutOff = $CI->cut_off_model->get_cut_off();
        if(!empty($cutOff)){
            foreach($cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom),'Y-m-d');
				if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
					//echo "wew";
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');
					//echo $date_from . "sad";
					//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($value->datePayroll),'Y-m-d');
				}
            }
        }
        $dates = array();
	    $from = strtotime($final_date_from);
	    $last = strtotime($final_date_to);
	    $output_format = 'Y-m-d';
	    $step = '+1 day';

	    $count = 0;
	    while( $from <= $last ) {

    		$count++;
	        $dates[] = date($output_format, $from);
	        $from = strtotime($step, $from);
	       
        }
        $count = $count- 1;
	    
	    $weekdays = array();

	    $counter = 0;

	    $weekdays_count = 0;
        $name_count = 0;
        do {
            $date_create = date_create($dates[$counter]);
            $attendance_date = date_format($date_create, 'F d, Y');

            $day = date_format($date_create, 'l');

           if ($day != "Saturday" && $day != "Sunday"){
               $name_count++;    			    	
           }

           /*echo '<div class="col-sm-3">';
               echo '<b>' . $attendance_date . " :</b>";
           echo "</div>";
           */

            //$attendance_date . "<br/>";

           //echo $dates[$counter];
           
           $counter++;
           

       }while($counter <= $count);

       return $name_count;
    }
    function getCutOffAttendanceDateCountToPayroll($day_from,$day_to){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');

        $dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		$current_date_time = date_format($date, 'Y-m-d');

		$year = date("Y");

        
        $cutOff = $CI->cut_off_model->get_cut_off();
        if(!empty($cutOff)){
            foreach($cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom),'Y-m-d');
				if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
				}
            }
        }
        $dates = array();
	    $from = strtotime($final_date_from);
	    $last = strtotime($final_date_to);
	    $output_format = 'Y-m-d';
	    $step = '+1 day';

	    $count = 0;
	    while( $from <= $last ) {

    		$count++;
	        $dates[] = date($output_format, $from);
	        $from = strtotime($step, $from);
	       
        }
        
        $count = $count;
	    
	    $weekdays = array();

	    $counter = 0;

	    $weekdays_count = 0;
        $name_count = 0;
        do {
            $date_create = date_create($dates[$counter]);
            $attendance_date = date_format($date_create, 'F d, Y');

            $day = date_format($date_create, 'w');
            if ($day >= $day_from && $day <= $day_to){
               $name_count++;    			    	
            }
           $counter++;
           

        }while($counter < $count);
        return $name_count;
    }

    function getCutOffDay(){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');
        $dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		$current_date_time = date_format($date, 'Y-m-d');

        $year = date("Y");
        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
        $select_qry = $CI->cut_off_model->get_cut_off();
        if(!empty($select_qry)){
            foreach($select_qry as $valueCutOff){
                $date_from = date_format(date_create($valueCutOff->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($valueCutOff->dateFrom),'m-d') == "12-26"){
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($valueCutOff->dateFrom),'m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($valueCutOff->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                    $final_date_from = $date_from;
                    $final_date_to = $date_to;
                    $date_payroll = date_format(date_create($valueCutOff->datePayroll),'d');
                }
                    
            }
        }
        return $date_payroll;
    }
    function checkIfHiredWithinCutOff($date_hired){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');
        $dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");

        $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
        $select_qry = $CI->attendance_model->get_cut_off();
        $final_date_from = "";
        $final_date_to = "";
        if(!empty($select_cutoff_qry)){
            foreach($select_cutoff_qry as $value){
                $date_from = date_format(date_create($value->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                    $final_date_from = $date_from;
                    $final_date_to = $date_to;
                    $date_payroll = date_format(date_create($value->datePayroll),'Y-m-d');
                }
                    
            }
        }
        $status = 0;
        if ($date_hired >= $final_date_from && $date_hired <= $final_date_to){
			$status = 1;
		}
		return $status;
    }
    function getCutOffAttendanceDateCountToRunningBalance($day_from,$day_to){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');

        $dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		$current_date_time = date_format($date, 'Y-m-d');

		$year = date("Y");

        
        $cutOff = $CI->cut_off_model->get_cut_off();
        if(!empty($cutOff)){
            foreach($cutOff as $value){
                $date_from = date_format(date_create($value->dateFrom),'Y-m-d');
				if (date_format(date_create($value->dateFrom),'m-d') == "12-26"){
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($value->dateFrom),'m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($value->dateTo),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
				}
            }
        }
        $dates = array();
	    $from = strtotime($final_date_from);
	    $last = strtotime($final_date_to);
	    $output_format = 'Y-m-d';
	    $step = '+1 day';

	    $count = 0;
	    while( $from <= $last ) {

    		$count++;
	        $dates[] = date($output_format, $from);
	        $from = strtotime($step, $from);
	       
        }
        
        $count = $count - 1;
	    
	    $weekdays = array();

	    $counter = 0;

	    $weekdays_count = 0;
        $name_count = 0;
        do {
            $date_create = date_create($dates[$counter]);
            $attendance_date = date_format($date_create, 'F d, Y');

            $day = date_format($date_create, 'w');
            if ($day >= $day_from && $day <= $day_to){
               $name_count++;    			    	
            }
           $counter++;
           

        }while($counter < $count);
        return $name_count;
    }

    function checkExistCutOffPeriod($cutOffPeriod){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');
        $has_error = 1;
        $select_qry = $CI->cut_off_model->get_cut_off();
        if(!empty($select_qry)){
            foreach($select_qry as $value){
                $date_from = date_format(date_create($value->dateFrom),'F d');
				$date_to = date_format(date_create($value->dateTo),'F d');
				$cut_off_period_db = $date_from . " - " . $date_to;

				if ($cutOffPeriod == $cut_off_period_db){
					$has_error = 0;
				}
            }
        }
        return $has_error;
    }
?>