<?php
    function dateDefault($date){
		$date_create = date_create($date);
		$date_format = date_format($date_create, 'm/d/Y');
		return $date_format;
	}


	function dateDefaultDb($date){
		$date_create = date_create($date);
		$date_format = date_format($date_create, 'Y-m-d');
		return $date_format;
	}

?>