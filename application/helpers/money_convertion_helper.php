<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    function is_decimal($val){
        return is_numeric($val) && floor($val) != $val;
    }


?>