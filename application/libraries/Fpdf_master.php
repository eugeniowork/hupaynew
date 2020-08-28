<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'third_party/fpdf/fpdf.php';
class Fpdf_master {
		
	public function __construct() {
		
		
		
		$pdf = new FPDF();
		$pdf->AddPage();
		
		$CI =& get_instance();
		$CI->fpdf = $pdf;
		
	}
	
}