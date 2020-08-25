<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_controller extends CI_Controller{
    function __construct(){
		parent::__construct();
        if(!$this->session->userdata('user')){
            redirect('login');
        }
        $this->load->model("employee_model", 'employee_model');
        $this->load->model('working_days_model','working_days_model');
        $this->load->model('company_model','company_model');
        $this->load->model('department_model','department_model');
        $this->load->model('minimum_wage_model','minimum_wage_model');
        $this->load->model('dependent_model','dependent_model');
        $this->load->model('bir_model','bir_model');
        $this->load->model('allowance_model','allowance_model');
        $this->load->model('cashbond_model','cashbond_model');
        // $this->load->model("attendance_model", "attendance_model");
        $this->load->model("payroll_model", "payroll_model");
        $this->load->model('deduction_model','deduction_model');
        // $this->load->model("attendance_model", "attendance_model");
        // $this->load->model('holiday_model','holiday_model');
        // $this->load->model('leave_model','leave_model');
        // $this->load->model('working_hours_model','working_hours_model');
        // $this->load->model('working_days_model','working_days_model');
        // $this->load->helper('hupay_helper');
        // $this->load->helper('attendance_helper');
        // $this->load->helper('date_helper');
        // $this->load->helper('leave_helper');
        //$this->load->library('../controllers/holiday_controller');
        $this->load->helper('cut_off_helper');
        $this->load->helper('holiday_helper');
        $this->load->helper('employee_helper');
        $this->load->helper('attendance_helper');
        $this->load->helper('minimum_wage_helper');
        $this->load->helper('sss_helper');
        $this->load->helper('philhealth_helper');
        $this->load->helper('pagibig_helper');
        $this->load->helper('salary_helper');
        $this->load->helper('simkimban_helper');
        $this->load->helper('tardiness_helper');
        $this->load->helper('incentives_helper');
        $this->load->helper('allowance_helper');
    }
    public function index(){
        $this->data['pageTitle'] = 'Generate Payroll';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('payroll/generate_payroll');
        $this->load->view('global/footer');
    }
    public function generatePayroll(){

        $sample = "";
        $count = $this->employee_model->get_active_employee();
        $count = count($count);
        $counter = 0;
        $cut_off_count = getCutOffAttendanceDateCount();
        $holiday_cut_off_count = holidayCutOffTotalCount();
        $all_emp_id = getEmpIdAllActiveEmp();
        $emp_values = explode("#",$all_emp_id);
        do{
            $emp_id = $emp_values[$counter];
            $row = $this->employee_model->employee_information($emp_id);
            $row_wd = $this->working_days_model->get_working_days_info($row['working_days_id']);
            $day_from = $row_wd['day_from'];
		    $day_to = $row_wd['day_to'];
            
            $working_days_count = getCutOffAttendanceDateCountToPayroll($day_from, $day_to);
            $days = $working_days_count;

            $is_increase = checkExistIncreaseCutOff($emp_id);
            $gross_income_inc = 0;

            $row_company = $this->company_model->get_company_info($row['company_id']);
            $logo_source = $row_company['logo_source'];

            $row_dept = $this->department_model->get_department($row['dept_id']);
            $min_wage = $this->minimum_wage_model->get_minimum_wage();
            $min_wage = ($min_wage['basicWage'] + $min_wage['COLA']) * 26;

            $taxCode = "";
            $tax = 0;

            if ($row['Salary'] > $min_wage){
                $dependentCount = $this->dependent_model->get_dependent_rows($emp_id);
                $taxStatus = $this->bir_model->get_bir_status_to_payroll($dependentCount)['Status'];
                $civilStatus = $row['CivilStatus'];

                if ($dependentCount == 0){
                    $dependentCount = "";
                }

                if ($civilStatus == "Single"){
                    $taxCode = "S" . $dependentCount;
                }

                else {
                    $taxCode = "ME" . $dependentCount;
                }
            }
            $regularOTmin = round(getOvertimeRegularOt($emp_id)/60,2);
            $regHolidayOTmin = round(getOvertimeRegularHolidayOt($emp_id)/60,2);
            $specialHolidayOTmin = round(getOvertimeSpecialHolidayOt($emp_id)/60,2);
            $rd_regularHolidayOTmin = round(getOvertimeRDRegularHolidayOt($emp_id)/60,2);
            $rd_specialHolidayOTmin = round(getOvertimeRDSpecialHolidayOt($emp_id)/60,2);
            $restdayOTmin = round(getOvertimeRestdayOt($emp_id)/60,2);

            $row_wh = $this->working_hours_model->get_info_working_hours($row['working_hours_id']);
            $timeFrom = $row_wh['timeFrom'];
            $timeTo = $row_wh['timeTo'];
            $timeFrom = strtotime($timeFrom);
            $timeTo = strtotime($timeTo);
            $total_hours = (($timeTo - $timeFrom) / 3600) - 1;
            $allowance = $this->allowance_model->get_info_allowance($row['emp_id']);
            $allowanceValue = 0;
            if(!empty($allowance)){
                foreach($allowance as $value){
                    if ($allowanceValue == ""){
						$allowanceValue = $value->AllowanceValue;		
					}
					else {
						$allowanceValue = $allowanceValue + $value->AllowanceValue;
					}
                }
            }
            $daily_rate =  (($row['Salary'] + $allowanceValue ) / 2)/ $days;
            $daily_rate_basic = (($row['Salary'] ) / 2)/ $days;
            $daily_rate_allowance = (($allowanceValue ) / 2)/ $days;

            $hourly_rate = round($daily_rate / $total_hours,2); 
            $inCutOff = checkMinWageEffectiveDateInCutOff($emp_id,($allowanceValue+$row['Salary']));
            $regular_ot_rate = round($hourly_rate + ($hourly_rate * .25),2);

            $reg_ot_amount = round($regular_ot_rate * $regularOTmin,2);
            if($inCutOff == 1){
                $reg_ot_amount = round(getRegOtAmount($emp_id),2);
            }
            $regHoliday_ot_rate = round($hourly_rate,2);
            $regHoliday_ot_amount = round($regHoliday_ot_rate * $regHolidayOTmin,2); 
            if ($inCutOff == 1){
                $regHoliday_ot_amount = round(getRegHolidayOtAmount($emp_id),2);
                
            }
            $specialHoliday_ot_rate = round($hourly_rate * .3,2);
            $specialHoliday_ot_amount = round($specialHoliday_ot_rate * $specialHolidayOTmin,2); 
            
            if ($inCutOff == 1){
                $specialHoliday_ot_amount = round(getSpecialHolidayOtAmount($emp_id),2);
            }
            $rdRegularHoliday_ot_rate = round($hourly_rate * 2.6,2);
            $rdRegularHoliday_ot_amount = round($rdRegularHoliday_ot_rate * $rd_regularHolidayOTmin,2);
            if ($inCutOff == 1){
                $rdRegularHoliday_ot_amount = round(getRdRegularHolidayOtAmount($emp_id),2);
            } 

            $rdSpecialHoliday_ot_rate = round($hourly_rate + ($hourly_rate * .6) ,2);
            $rdSpecialHoliday_ot_amount = round($rdSpecialHoliday_ot_rate * $rd_specialHolidayOTmin,2); 
            if ($inCutOff == 0){
                $rdSpecialHoliday_ot_amount = round(getRdSpecialHolidayOTamount($emp_id),2);
            }


            $rd_ot_rate = round($hourly_rate + ($hourly_rate * .3),2);
            $rd_ot_amount = round($rd_ot_rate * $restdayOTmin,2); 
            if ($inCutOff == 1){
                $rd_ot_amount = round(getRdOtAmount($emp_id),2);
            }
            getRdOtAmount($emp_id);
            $present = getPresentToPayroll($row['bio_id'],$day_from,$day_to);
            $payroll_count = $this->payroll_model->get_employee_payroll_info($emp_id);
            $sssContribution = 0;

            if ($row['SSS_No'] != "" && $payroll_count > 2){
                $sssContribution = round(getContribution($row['Salary']),2);
            }
            $philhealthContribution = 0;
            if ($row['PhilhealthNo'] != "" && $payroll_count > 2){
                $philhealthContribution = round(getContributionPhilHealth($row['Salary']),2);
            }
            $pagibigContribution = 0;
            if ($row['PagibigNo'] != "" && $payroll_count > 2){
                $pagibigContribution = round(getContributionPagibig($row['Salary']),2);
            }
            $has_sssLoan = existPendingSSSLoan($emp_id);
            $sss_loan_amount = 0;
            if ($has_sssLoan != 0){
                $cutOff_day = getCutOffDay();
                if ($cutOff_day == "30" || $cutOff_day == "28" || $cutOff_day == "29") {
                    $sss_loan_amount = getSSSLoanToPayroll($emp_id);
                }
            }
            $has_pagibigLoan = existPendingPagibigLoan($emp_id);
            $pagibig_loan_amount = 0;
            if ($has_pagibigLoan != 0){
                $cutOff_day = getCutOffDay();
                if ($cutOff_day == "15") {
                    $pagibig_loan_amount = getPagibigLoanToPayroll($emp_id);
                    
                }
                
            }
            $has_salaryLoan = existPendingSalaryLoan($emp_id);
            
            $salary_loan_amount = 0;
            if ($has_salaryLoan != 0){
                $salary_loan_amount = getSalaryLoanInfoToPayroll($emp_id);
                
            }
            $has_simkimban = existPendingSimkimban($emp_id);
            $simkimban_amount = 0;
            if ($has_simkimban != 0){
                $simkimban_amount = $simkimban_class->getInfoBySimkimbanEmpId($emp_id); 
                
            }
            $totalAllowance = round($allowanceValue,2); // for cut off allowance
            $dailyAllowance = round(($allowanceValue / 2)/$days,2);
            
            $attendance_rate = 0;
	        $tardinessMin = 0;
	        $tardinessAmount = 0;
            if ($row['bio_id'] != 0){
                $tardinessAmount = getTardinessLatest($row['emp_id'],$row['bio_id'],$row_wh['timeFrom'],$row_wh['timeTo']
                    ,$day_from,$day_to,$hourly_rate,$daily_rate,$total_hours);	
                
                if ($inCutOff == 1){
                    //$tardinessAmount = round($attendance_class->getTardinessAmount($row->bio_id),2);
                }
            }
            $absencesAmount = 0;
            if ($inCutOff == 1){
                //$absencesAmount = round($attendance_class->getAbsencesAmount($row->bio_id),2);
            }
            $cashBond = $this->cashbond_model->get_cashbond($emp_id);
            $cashbondValue = $cashBond['cashbondValue'];
            $basicCutOffPay = round($row['Salary'] / 2,2);
            if ($inCutOff == 1){
                //$basicCutOffPay = round($attendance_class->getBasicPayAmount($emp_id),2);
            }
            $incentives = round(getIncentives($row['bio_id'],round($daily_rate,2)),2);
            $totalGrossIncome = round((($daily_rate_basic * $present) + $reg_ot_amount + $regHoliday_ot_amount + $specialHoliday_ot_amount+ $rdRegularHoliday_ot_amount + $rdSpecialHoliday_ot_amount + $rd_ot_amount) - ($tardinessAmount + $absencesAmount) - $gross_income_inc,2);
            $cutOff_day = getCutOffDay();
            $getAllowance = $this->allowance_model->get_info_allowance($emp_id);

            $allowanceValue = round(getAllowanceInfoByEmpId($emp_id) / 2,2);
            $present_allowance = round($daily_rate_allowance * $present,2);

            $total = round($totalGrossIncome+ $present_allowance,2);
            $totalCashAdvance = $simkimban_amount + $salary_loan_amount;
            $totalDeduction = $sssContribution + $sss_loan_amount + $philhealthContribution + $pagibigContribution + $pagibig_loan_amount + $cashbondValue + $totalCashAdvance;
            $taxableIncome = $totalGrossIncome - ($sssContribution + $pagibigContribution + $philhealthContribution); 

            $last_total_gross_income = 0;
            if ($cutOff_day == "30") {
                $payrollTotalGross = $this->payroll_model->get_payroll_last_total_gross_income_rows($emp_id);
                if ($payrollTotalGross != 0){
                    $totalGrossIncome = $this->payroll_model->get_payroll_last_total_gross_income($emp_id);
                    $last_total_gross_income = $totalGrossIncome['totalGrossIncome'] - ($totalGrossIncome['sssDeduction'] + $totalGrossIncome['philhealthDeduction'] + $totalGrossIncome['pagibigDeduction']);
                }
                if (($taxableIncome + $last_total_gross_income) <= 20833){
                    $tax = 0;
                }
        
                else if (($taxableIncome + $last_total_gross_income) > 20833 && ($taxableIncome + $last_total_gross_income) < 33333){
                    $tax = round(((($taxableIncome + $last_total_gross_income) - 20833) * .20),2);
        
                }
        
                else if (($taxableIncome + $last_total_gross_income) > 33333 && $taxableIncome < 66667){
                    $tax = round(((($taxableIncome + $last_total_gross_income) - 33333) * .25) + 2500,2);
                }
                
                else if (($taxableIncome + $last_total_gross_income) > 66667 && ($taxableIncome + $last_total_gross_income) < 166667){
                    $tax = round(((($taxableIncome + $last_total_gross_income) - 66667) * .30) + 10833.33,2);
                }
        
                else if (($taxableIncome + $last_total_gross_income) > 166667 && ($taxableIncome + $last_total_gross_income) < 666667){
                    $tax = round(((($taxableIncome + $last_total_gross_income) - 166667) * .32) + 40833.33,2);
                }
        
                else if (($taxableIncome + $last_total_gross_income) >= 666667){
                    $tax = round(((($taxableIncome + $last_total_gross_income) - 666667) * .35) + 200833.33,2);
                }
            }
            $ytd_row = $this->deduction_model->get_yearly_deduction($emp_id);
            $ytdGross = $ytd_row['ytd_Gross'];
            $ytdAllowance = $ytd_row['ytd_Allowance'];
            $ytdTax = $ytd_row['ytd_Tax'];

            $final_totalDeduction = $totalDeduction + $tax;

            $netPay = round($total  -  $final_totalDeduction + $incentives,2);
            
            $counter++;
        }
        while($counter < $count);
        //$this->data['pasok'] = "assdasdasdd";
        //$this->data['sample'] = $sample;
        echo json_encode($this->data);
    }
}