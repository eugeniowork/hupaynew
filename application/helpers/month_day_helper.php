<?php 
	function getDayOfMonthUpdate($month,$day){
		$finalData = "";
		if ($month == "January" || $month == "March" || $month == "May" || $month == "July" || $month == "August" || $month == "October" || $month == "December") {
			$total_day = 31;
			$counter = 1;
		


			do {
				// if equal
				$selected = "";
				if ($day == $counter) {
					$selected = "selected=selected";
				}	
				
				
				$finalData .= "<option value='".$counter."' ".$selected.">";
					$finalData .= $counter;
				$finalData .= "</option>";
				$counter++;
			}while($counter <= $total_day);
		}

		// 1 month
		if ($month == "February") {

			$year = date("Y");

			// if has leap year
			if ($year % 4 == 0) {
				$total_day = 29;
			}
			// if not a leap year
			else {
				$total_day = 28;
			}
			
			$counter = 1;
			do {
				// if equal
				$selected = "";
				if ($day == $counter) {
					$selected = "selected=selected";
				}	
				
				
				$finalData .= "<option value='".$counter."' ".$selected.">";
					$finalData .= $counter;
				$finalData .= "</option>";
				$counter++;
			}while($counter <= $total_day);
		}

		// 4 months
		if ($month == "April" || $month == "June" || $month == "September" || $month == "November") {
			$total_day = 30;
			$counter = 1;
			do {
				// if equal
				$selected = "";
				if ($day == $counter) {
					$selected = "selected=selected";
				}	
				
				
				$finalData .= "<option value='".$counter."' ".$selected.">";
					$finalData .= $counter;
				$finalData .= "</option>";
				$counter++;
			}while($counter <= $total_day);
		}
		return $finalData;
	}

	function getDayOfMonth($month){
		$finalData = "";
		if ($month == "January" || $month == "March" || $month == "May" || $month == "July" || $month == "August" || $month == "October" || $month == "December") {
			$total_day = 31;
			$counter = 1;
			do {
				$finalData .= "<option value='".$counter."'>";
					$finalData .= $counter;
				$finalData .= "</option>";
				$counter++;
			}while($counter <= $total_day);
		}

		// 1 month
		if ($month == "February") {

			$year = date("Y");

			// if has leap year
			if ($year % 4 == 0) {
				$total_day = 29;
			}
			// if not a leap year
			else {
				$total_day = 28;
			}
			
			$counter = 1;
			do {
				$finalData .= "<option value='".$counter."'>";
					$finalData .= $counter;
				$finalData .= "</option>";
				$counter++;
			}while($counter <= $total_day);
		}

		// 4 months
		if ($month == "April" || $month == "June" || $month == "September" || $month == "November") {
			$total_day = 30;
			$counter = 1;
			do {
				$finalData .= "<option value='".$counter."'>";
					$finalData .= $counter;
				$finalData .= "</option>";
				$counter++;
			}while($counter <= $total_day);
		}

		return $finalData;
	}

 ?>