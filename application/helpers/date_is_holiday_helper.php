<?php
    function dateIsHoliday($month,$day,$year){
        $CI =& get_instance();
        $CI->load->model('holiday_model');
        $holiday_date = $month . " " . $day;
        $date_create_leave = date_create($month . " " . $day . ", " . $year );
        $date_format_leave = date_format($date_create_leave,"l");
        $holiday = $CI->holiday_model->get_holiday_date($holiday_date);
        if ($date_format_leave == "Sunday" || $date_format_leave == "Saturday"){
			$holiday = 0;
        }
        return $holiday;
    }
?>