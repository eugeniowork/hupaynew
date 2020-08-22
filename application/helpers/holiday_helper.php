<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    function holidayCutOffTotalCount(){
        $CI =& get_instance();
        $CI->load->model('cut_off_model');
        $CI->load->model('holiday_model');
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
        $count = 0;
        $holiday = $CI->holiday_model->get_holiday();
        if(!empty($holiday)){
            foreach($holiday as $value){
                $holiday_date = date_format(date_create($value->holiday_date. ", " . $year),"Y-m-d");

                $day = date_format(date_create($holiday_date), 'l');
				if ($holiday_date >= $final_date_from && $holiday_date <= $final_date_to && $day != "Saturday" && $day != "Sunday"){
					$count++;
					
				}
            }
        }
        return $count;
    }
?>