<?php
    function getContributionPhilHealth($salary){
        $CI =& get_instance();
        $CI->load->model('attendance_model');

        date_default_timezone_set("Asia/Manila");
        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        $current_date_time = date_format($date, 'Y-m-d');
        $year = date("Y");
        $select_cutoff_qry = $CI->attendance_model->get_cut_off();
        if(!empty($select_cutoff_qry)){
            foreach($select_cutoff_qry as $valueCutOff){
                $date_from = date_format(date_create($valueCutOff->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($valueCutOff->dateFrom),'m-d') == "12-26"){
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($valueCutOff->dateFrom),'m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($valueCutOff->dateTo. ", " .$year),'Y-m-d');
                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                    $final_date_from = $date_from;
                    $final_date_to = $date_to;
                    $date_payroll = date_format(date_create($valueCutOff->datePayroll . ", " .$year),'d');
                }
                    
            }
        }
        $contribution = 0;
        if ($date_payroll == "15") { 

            if ($year == "2019"){

                $contribution = (($salary * .0275) / 2);
            }

            if ($year == "2020"){

                $contribution = (($salary * .03) / 2);
            }

            if ($year == "2021"){

                $contribution = (($salary * .035) / 2);
            }

            if ($year == "2022"){

                $contribution = (($salary * .04) / 2);
            }

            if ($year == "2023"){

                $contribution = (($salary * .045) / 2);
            }

            if ($year == "2024" || $year == "2025"){

                $contribution = (($salary * .005) / 2);
            }
        }
        return $contribution;
    }
?>