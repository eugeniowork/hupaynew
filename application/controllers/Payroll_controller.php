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
        $this->load->model('working_hours_model','working_hours_model');
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
        $this->load->model('salary_model','salary_model');
        $this->load->model('department_model','department_model');
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
        $this->load->helper('hupay_helper');
        $this->load->helper('payroll_helper');
    }
    public function index(){
        $this->data['pageTitle'] = 'Generate Payroll';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('payroll/generate_payroll');
        $this->load->view('global/footer');
    }
    public function generatePayroll(){
        $finalPayrollData = array();
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
                    $totalGrossIncomeData = $this->payroll_model->get_payroll_last_total_gross_income($emp_id);
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
            $basicPay = 0;
            if($min_wage >= $row['Salary']){
                $basicPay = round(($row['Salary']/2),2);
            }
            else{
                $basicPay = $basicCutOffPay;
            }
            $december_15_2019_13_pay_basic = 0;
            $december_15_2019_13_pay_allowance = 0;

            $december_30_2019_13_pa_basic = 0;
            $december_30_2019_13_pay_allowance = 0;

            $january_15_2020_13_pay_basic = 0;
            $january_15_2020_13_pay_allowance = 0;

            $cut_off_13_pay_basic = $basicCutOffPay;		
            $cut_off_13_pay_allowance = $allowanceValue;	
            if(checkIfHiredWithinCutOff($row['DateHired']) == 1){
                $daily_basic_13_month_pay = round($basicCutOffPay / $working_days_count,2);
                $dayily_allowance_13_month_pay = round($allowance / $working_days_count,2);
                $cut_off_13_pay_basic = round($daily_basic_13_month_pay * $present,2);	
                $cut_off_13_pay_allowance = round($dayily_allowance_13_month_pay * $present,2);
            }	
            if(getCutOffPeriodLatest() == "January 11, 2020 - January 25, 2020"){
                if($this->payroll_model->get_cut_off_13_month_pay_old($emp_id,"November 26, 2019 - December 10, 2019") != 0){
                    $row_13 = $this->payroll_model->get_cut_off_13_month_pay_old_data($emp_id,"November 26, 2019 - December 10, 2019");
                    $december_15_2019_13_pay_basic = $row_13['ratePayPrd'];
                    $december_15_2019_13_pay_allowance = $row_13['allowancePay'];
                }
                if ($this->payroll_model->get_cut_off_13_month_pay_old($emp_id,"December 11, 2019 - December 25, 2019") != 0){
                    $row_13 = $this->payroll_model->get_cut_off_13_month_pay_old_data($emp_id,"December 11, 2019 - December 25, 2019");

                    $december_30_2019_13_pa_basic = $row_13['ratePayPrd'];
                    $december_30_2019_13_pay_allowance = $row_13['allowancePay'];
                }
                if ($this->payroll_model->get_cut_off_13_month_pay_old($emp_id,"December 26, 2019 - January 10, 2020") != 0){

                    $row_13 = $this->payroll_model->get_cut_off_13_month_pay_old_data($emp_id,"December 26, 2019 - January 10, 2020");

                    $january_15_2020_13_pay_basic = $row_13['ratePayPrd'];
                    $january_15_2020_13_pay_allowance = $row_13['allowancePay'];
                }
            }
            $total_13_basic_pay = round($december_15_2019_13_pay_basic/12,2) + round($december_30_2019_13_pa_basic/12,2) + round($january_15_2020_13_pay_basic /12,2) + round($cut_off_13_pay_basic/12,2);
            $total_13_allowance_pay = round($december_15_2019_13_pay_allowance/12,2) + round($december_30_2019_13_pay_allowance/12,2) + round($january_15_2020_13_pay_allowance/12,2) + round($cut_off_13_pay_allowance/12,2);
            $netPay += ($total_13_basic_pay + $total_13_allowance_pay);
            array_push($finalPayrollData, array(
                'logo_source'=>$logo_source,
                'emp_id'=>$emp_id,
                'row_salary'=>$row['Salary'],
                'payroll_period'=>getCutOffPeriodLatest(),
                'getDatePayroll'=>getDatePayroll(),
                'department'=>$row_dept['Department'],
                'basic_pay'=>$basicPay,
                'name'=>ucwords($row['Lastname'].", ". $row['Firstname']. " ". $row['Middlename']),
                'tax_code'=>$taxCode,
                'bio_id'=>$row['bio_id'],
                'reg_ot_amount'=>$reg_ot_amount,
                'rd_ot_amount'=>$rd_ot_amount,
                'regHoliday_ot_amount'=>$regHoliday_ot_amount,
                'specialHoliday_ot_amount'=>$specialHoliday_ot_amount,
                'rdRegularHoliday_ot_amount'=>$rdRegularHoliday_ot_amount,
                'rdSpecialHoliday_ot_amount'=>$rdSpecialHoliday_ot_amount,
                'tardinessAmount'=>$tardinessAmount,
                'absencesAmount'=>$absencesAmount,
                'present'=>round($daily_rate_basic * $present,2),
                'basicCutOffPay'=>$basicCutOffPay,
                'totalGrossIncome'=>$totalGrossIncome,
                'sssContribution'=>$sssContribution,
                'sss_loan_amount'=>$sss_loan_amount,
                'philhealthContribution'=>$philhealthContribution,
                'pagibigContribution'=>$pagibigContribution,
                'pagibig_loan_amount'=>$pagibig_loan_amount,
                'cashBond'=>$cashbondValue,
                'totalCashAdvance'=>$totalCashAdvance,
                'totalDeduction'=>round($totalDeduction,2),
                'tax'=>$tax,
                'incentives'=>number_format($incentives,2),
                'present_allowance'=>$present_allowance,
                'dec_15_2019_basic'=>number_format($december_15_2019_13_pay_basic,2),
                'dec_15_2019_allowance'=>number_format($december_15_2019_13_pay_allowance,2),
                'dec_30_2019_basic'=>number_format($december_30_2019_13_pa_basic,2),
                'dec_30_2019_allowance'=>number_format($december_30_2019_13_pay_allowance,2),
                'jan_15_2019_basic'=>number_format($january_15_2020_13_pay_basic,2),
                'jan_15_2019_allowance'=>number_format($january_15_2020_13_pay_allowance,2),
                'current_cut_off_13th_basic_date'=>getDatePayroll(),
                'current_cut_off_13th_basic_value'=>number_format($cut_off_13_pay_basic,2),
                'current_cut_off_13th_allowance_date'=>getDatePayroll(),
                'current_cut_off_13th_allowance_value'=>number_format($cut_off_13_pay_allowance,2),
                'total_basic'=>number_format($december_15_2019_13_pay_basic + $december_30_2019_13_pa_basic + $january_15_2020_13_pay_basic + $cut_off_13_pay_basic,2),
                'total_allowance'=>number_format($december_15_2019_13_pay_allowance + $december_30_2019_13_pay_allowance + $january_15_2020_13_pay_allowance + $cut_off_13_pay_allowance,2),
                'total_13_basic_pay'=>number_format($total_13_basic_pay,2),
                'total_13_allowance_pay'=>number_format($total_13_allowance_pay,2),
                'net_pay'=>$netPay,
                'last_total_gross_income'=>$last_total_gross_income,

            ));
            $counter++;
        }
        while($counter < $count);
        //$this->data['pasok'] = "assdasdasdd";
        //$this->data['sample'] = $sample;
        $this->data['finalPayrollData'] = $finalPayrollData;
        $this->data['status'] = "success";
        echo json_encode($this->data);
    }
    public function appendTaxValue(){
        $totalGrossIncome = $this->input->post('totalGrossIncome');
        $empId = $this->input->post('empId');
        $lastTotalGrossIncome = $this->input->post('lastTotalGrossIncome');
        $cutOffDay = $this->input->post('cutOffDay');
        $sssContribution = $this->input->post('sssContribution');
        $pagibigContribution = $this->input->post('pagibigContribution');
        $philhealthContribution = $this->input->post('philhealthContribution');
        $check = $this->employee_model->employee_information($empId);
        if(empty($check)){
            $this->data['status'] = "error";
        }
        else{
            $taxable_income = $totalGrossIncome - ($sssContribution + $pagibigContribution + $philhealthContribution);
            $tax = 0;
			$taxableIncome = $taxable_income;
			if ($cutOffDay == "30"){

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
            $this->data['status'] = "success";
            $this->data['tax'] = $tax;
        }

        echo json_encode($this->data);
    }
    public function savePayroll(){
        $finalData = $this->input->post('finalData');
        $cut_off_count = getCutOffAttendanceDateCount();
        $holiday_cut_off_count = holidayCutOffTotalCount();
        if(!empty($finalData)){
            foreach($finalData as $finalDataValue){
                $emp_id = $finalDataValue['emp_id'];
                $row = $this->employee_model->employee_information($emp_id);
                $row_wd = $this->working_days_model->get_working_days_info($row['working_days_id']);
                $day_from = $row_wd['day_from'];
                $day_to = $row_wd['day_to'];
                $working_days_count = getCutOffAttendanceDateCountToRunningBalance($day_from, $day_to);
                $days = $working_days_count;
                $dept_id = $row['dept_id'];
                $company_id = $row['company_id'];
                $cutOffPeriod = getCutOffPeriodLatest();
                $salary = round($row['Salary'],2);
                $minimumWage = $this->minimum_wage_model->get_minimum_wage();
                $min_wage = ($minimumWage['basicWage'] + $minimumWage['COLA']) * 26;
                $taxCode = "";
                if ($row['Salary'] > $min_wage) {
                    $dependentCount = $this->dependent_model->get_dependent_rows($emp_id);
                    $taxStatus = $this->bir_model->get_bir_status_to_payroll($dependentCount);
                    $taxStatus = $taxStatus['Status'];
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
                $allowance = getAllowanceInfoToPayslip($row['emp_id']);
                $dailyAllowance = round(($allowance / 2)/$days,2);
                $row_wh = $this->working_hours_model->get_info_working_hours($row['working_hours_id']);
                $timeFrom = $row_wh['timeFrom'];
                $timeTo = $row_wh['timeTo'];

                $timeFrom = strtotime($timeFrom);
                $timeTo = strtotime($timeTo);

                $total_hours = (($timeTo - $timeFrom) / 3600) - 1;
                $daily_rate =  (($row['Salary'] + $allowance ) / 2)/ $days;
                $hourly_rate = round($daily_rate / $total_hours,2);
                $regular_ot_rate = round($hourly_rate + ($hourly_rate * .25),2);
                $reg_ot_amount = $finalDataValue['regOT'];
                $regularOTmin = round($reg_ot_amount / $regular_ot_rate,2);

                $regHoliday_ot_rate = round($hourly_rate,2);
			    $regHoliday_ot_amount = round($finalDataValue['regHolidayOT'],2); 
                $regHolidayOTmin = round($regHoliday_ot_amount/$regHoliday_ot_rate,2);
                
                $specialHoliday_ot_rate = round($hourly_rate * .3,2);
			    $specialHoliday_ot_amount = round($finalDataValue['specialHolidayOT'],2); 
                $specialHolidayOTmin = round($specialHoliday_ot_amount/$specialHoliday_ot_rate,2);
                
                $rdRegularHoliday_ot_rate = round($hourly_rate * 2.6,2);
			    $rdRegularHoliday_ot_amount = round($finalDataValue['rdREgHolidayOT'],2); 
                $rd_regularHolidayOTmin = round($rdRegularHoliday_ot_amount / $rdRegularHoliday_ot_rate,2);
                
                $rdSpecialHoliday_ot_rate = round($hourly_rate + ($hourly_rate * .6) ,2);
			    $rdSpecialHoliday_ot_amount = round($finalDataValue['rdSpecialHolidayOT'],2); 
			    $rd_specialHolidayOTmin = round($rdSpecialHoliday_ot_amount / $rdSpecialHoliday_ot_rate,2);

                $rd_ot_rate = round($hourly_rate + ($hourly_rate * .3),2);
                $rd_ot_amount = round($finalDataValue['rdOT'],2); 
                $restdayOTmin = round($rd_ot_amount / $rd_ot_rate,2);

                $attendance_rate = 0;
                $tardinessAmount = round($finalDataValue['tardiness'],2);
                $tardinessMin = 0;

                $absencesRate = 0;

                $absencesAmount = 0;
                $absencesMin = 0;
                
                $present = getPresentToPayroll($row['bio_id'],$day_from,$day_to);
                $present_amount = round($finalDataValue['present'],2);
                
                $adjustmentEarnings = $finalDataValue['adjustment'];
			    $adjustmentDeduction = $finalDataValue['adjustmentDeduction'];
                $adjustmentAfterTax = $finalDataValue['adjustmentAfter'];
                
                $adjustmentBefore = $adjustmentEarnings;
			
                $totalAdjustment = ($adjustmentEarnings - $adjustmentDeduction) + $adjustmentAfterTax; 

                $adjustmentAfterTax = $adjustmentAfterTax + $adjustmentDeduction;
                $totalGrossIncome = $finalDataValue['grossIncome'];
                $nontaxAllowance = $finalDataValue['nontaxAllowance'];

                $totalEarnings = $totalGrossIncome + $nontaxAllowance + $totalAdjustment;
                $tax = $finalDataValue['witholdingTax'];
                $sssContribution = $finalDataValue['sssContrib'];
                $philhealthContribution = $finalDataValue['philhealthContrib'];
                $pagibigContribution = $finalDataValue['pagibigContrib'];
                $sss_loan_amount = $finalDataValue['sssLoan'];
                $pagibig_loan_amount = $finalDataValue['pagibigLoan'];
                $totalCashAdvance = $finalDataValue['cashAdvance'];
                $cashBond = $finalDataValue['cashBond'];
                $totalDeductions = $finalDataValue['totalDeductions'];
                $netPay = $finalDataValue['netPay'];

                $basicRate = $salary;
                $dailyRate = (($row['Salary']) / 2)/ $days;
                $dailyAllowance =  (($allowance ) / 2)/ $days;
                $ratePayPeriod = round($salary / 2,2);
                $allowancePay = round($allowance/2,2);

                $row_ytd = $this->deduction_model->get_yearly_deduction($emp_id);

                $existSimkimban = existPendingSimkimban($emp_id);
                $simkimban_bal = 0;
                if ($existSimkimban != 0) {
                    $simkimban_bal = getAllRemainingBalanceSimkimban($emp_id);
                }
                $existSalaryLoan = $this->salary_model->check_if_has_salary($emp_id);
                $salary_loan_bal = 0;
                if ($existSalaryLoan != 0){

                    $salary_loan_bal = getAllSalaryLoan($emp_id);
                    //$salary_loan_bal = $salary_loan_row->remainingBalance;
                }
                $currentRemainingBal = $simkimban_bal + $salary_loan_bal;
                $cashAdvanceBal = $currentRemainingBal - $totalCashAdvance;
                $datePayroll = getDatePayroll();
                if (date_format(date_create($datePayroll),'m-d') == "01-15"){
                    $ytdGross =  $totalGrossIncome;
                    $ytdAllowance = $nontaxAllowance;
                    $ytdTax = $tax;
                }
                else {
                    $ytdGross = $row_ytd['ytd_Gross'] + $totalGrossIncome;
                    $ytdAllowance = $row_ytd['ytd_Allowance'] + $nontaxAllowance;
                    $ytdTax = $row_ytd['ytd_Tax'] + $tax;
                }
                $approveStat = 0;
		   	    $dateCreated = getDateDate();
                $remarks = $finalDataValue['adjustmentRemarks'];
                $december_15_2019_13_pay_basic = 0;
                $december_15_2019_13_pay_allowance = 0;

                $december_30_2019_13_pa_basic = 0;
                $december_30_2019_13_pay_allowance = 0;

                $january_15_2020_13_pay_basic = 0;
                $january_15_2020_13_pay_allowance = 0;

                $cut_off_13_pay_basic = round($ratePayPeriod / 12,2);		
                $cut_off_13_pay_allowance = round($allowancePay / 12,2);
                $insertPayrollData = array(
                    'payroll_id'=>'',
                    'emp_id'=>$emp_id,
                    'dept_id'=>$dept_id,
                    'company_id'=>$company_id,
                    'CutOffPeriod'=>$cutOffPeriod,
                    'salary'=>$salary,
                    'taxCode'=>$taxCode,
                    'reg_OThour'=>$regularOTmin,
                    'reg_OTrate'=>$regular_ot_rate,
                    'regularOT'=>$reg_ot_amount,
                    'rd_OThour'=>$restdayOTmin,
                    'rd_OTrate'=>$rd_ot_rate,
                    'restdayOT'=>$rd_ot_amount,
                    'reg_holiday_OThour'=>$regHolidayOTmin,
                    'reg_holiday_OTrate'=>$regHoliday_ot_rate,
                    'reg_holidayOT'=>$regHoliday_ot_amount,
                    'special_holiday_OThour'=>$specialHolidayOTmin,
                    'special_holiday_OTrate'=>$specialHoliday_ot_rate,
                    'special_holidayOT'=>$specialHoliday_ot_amount,
                    'rd_reg_holiday_OThour'=>$rd_regularHolidayOTmin,
                    'rd_reg_holiday_OTrate'=>$rdRegularHoliday_ot_rate,
                    'rd_reg_holidayOT'=>$rdSpecialHoliday_ot_amount,
                    'rd_special_holiday_OThour'=>$rd_specialHolidayOTmin,
                    'rd_special_holiday_OTrate'=>$rdSpecialHoliday_ot_rate,
                    'rd_special_holidayOT'=>$rdSpecialHoliday_ot_amount,
                    'tardinessHour'=>$tardinessMin,
                    'tardinessRate'=>$attendance_rate,
                    'Tardiness'=>$tardinessAmount,
                    'absencesHour'=>$absencesMin,
                    'absencesRate'=>$absencesRate,
                    'Absences'=>$absencesAmount,
                    'present'=>$present,
                    'present_amount'=>$present_amount,
                    'adjustmentEarnings'=>$adjustmentEarnings,
                    'adjustmentDeductions'=>$adjustmentDeduction,
                    'adjustmentBefore'=>$adjustmentBefore,
                    'adjustmentAfter'=>$adjustmentAfterTax,
                    'Adjustment'=>$totalAdjustment,
                    'totalGrossIncome'=>$totalGrossIncome,
                    'NontaxAllowance'=>$nontaxAllowance,
                    'totalEarnings'=>$totalEarnings,
                    'Tax'=>$tax,
                    'sssDeduction'=>$sssContribution,
                    'philhealthDeduction'=>$philhealthContribution,
                    'pagibigDeduction'=>$pagibigContribution,
                    'sssLoan'=>$sss_loan_amount,
                    'pagibigLoan'=>$pagibig_loan_amount,
                    'cashAdvance'=>$totalCashAdvance,
                    'CashBond'=>$cashBond,
                    'totalDeductions'=>$totalDeductions,
                    'netPay'=>$netPay,
                    'basicRate'=>$basicRate,
                    'Allowance'=>$allowance,
                    'dailyRate'=>$dailyRate,
                    'dailyAllowance'=>$dailyAllowance,
                    'ratePayPrd'=>$ratePayPeriod,
                    'allowancePay'=>$allowancePay,
                    'cut_off_13_pay_basic'=>$cut_off_13_pay_basic,
                    'cut_off_13_pay_allowance'=>$cut_off_13_pay_allowance,
                    'ytdGross'=>$ytdGross,
                    'ytdAllowance'=>$ytdAllowance,
                    'ytdWithTax'=>$ytdTax,
                    'cashAdvBal'=>$cashAdvanceBal,
                    'datePayroll'=>$datePayroll,
                    'remarks'=>$remarks,
                    'payrollStatus'=>$approveStat,
                    'DateCreated'=>$dateCreated,
                );
                $insertPayroll = $this->payroll_model->insert_payroll($insertPayrollData);
                
                
            }
            $insertPayrollApprovalData = array(
                'approve_payroll_id'=>'',
                'CutOffPeriod'=>$cutOffPeriod,
                'approveStat'=>'3',
                'DateCreated'=>$dateCreated
            );
            $insertPayrollApproval = $this->payroll_model->insert_payroll_approval($insertPayrollApprovalData);
            $this->data['msg'] = 'The Payroll for the <strong>Cut off '.$cutOffPeriod.'</strong> was successfully submitted';
            $this->data['status'] = "success";
        }
        else{
            $this->data['status'] = "error";
        
        }

        echo json_encode($this->data);
    }
   
    public function getActive(){
        $all_emp_id = getEmpIdAllActiveEmp();
        $current_emp_id = explode("#",$all_emp_id);
        $ids = array();
        foreach($current_emp_id as $value){
            array_push($ids, array('id'=>$value));
        }

        $this->data['ids'] = $ids;
        echo json_encode($this->data);
    }

    public function viewPayrollInfo(){
        $this->data['pageTitle'] = 'Payroll Information';

        $this->load->view('global/header', $this->data);
        $this->load->view('global/header_buttons');
        $this->load->view('payroll/payroll_info');
        $this->load->view('global/footer');
    }
    public function generatePayrollPerEmployee(){
        $empName = $this->input->post('empName');
        $cutOffPeriod = $this->input->post('cutOffPeriod');
        $year = $this->input->post('year');
        $employeePayrollData = array();
        if(checkExistCutOffPeriod($cutOffPeriod) == 1){
            $this->data['status'] = "error";
            $this->data['msg'] = '<strong>'.$cutOffPeriod.'</strong> doest not exist on Cut Off Period List.';
        }
        else{
            if ($cutOffPeriod == "December 26 - January 10"){
                $cut_off = explode("-",$cutOffPeriod);
                $dateFrom = substr($cut_off[0],0,-1);
                $dateFrom = $dateFrom.", " .($year - 1);
    
                $dateTo = substr($cut_off[1],1);
                $dateTo = $dateTo.", " .$year;
            }
    
            else {
                $cut_off = explode("-",$cutOffPeriod);
                $dateFrom = substr($cut_off[0],0,-1);
                $dateFrom = $dateFrom.", " .$year;
    
                $dateTo = substr($cut_off[1],1);
                $dateTo = $dateTo.", " .$year;
            }
            $final_cut_off_period = $dateFrom . " - " . $dateTo;
            if(checkExistPayrollInformation($empName, $final_cut_off_period) == 0){
                $this->data['status'] = "error";
                $this->data['msg'] = 'No information found.';
            }
            else{
                $row = getPayrollInfoByCutOffPeriodEmpName($empName,$final_cut_off_period);
                $payroll_id = $row['payroll_id'];
                $allowance = getAllowanceInfoToPayslip($row['emp_id']);

                $row_emp = $this->employee_model->employee_information($row['emp_id']);
                $inCutOff = checkMinWageEffectiveDateInCutOff($row['emp_id'],($allowance+$row_emp['Salary']));
                $min_wage = getMinimumWage();
                $has_tax = 0;
                if ($min_wage < $row['salary']){
                    $has_tax = 1;
                }
                $row_dept = $this->department_model->get_department($row['dept_id']);
                $department = $row_dept['Department'];
                //$row_emp
                $current_cut_off = getCutOffPeriodLatest();
                $is_cut_off = 0;
                if ($final_cut_off_period == $current_cut_off){
                    $payrollApproval = $this->payroll_model->get_payroll_approval_by_cut_off_period($current_cut_off);
                    
                    if (!empty($payrollApproval)){
                        $is_cut_off = 1;
                    }
                }
                $totalDeductions = $row['sssDeduction'] + $row['sssLoan'] + $row['philhealthDeduction']+ $row['pagibigDeduction'] + $row['pagibigLoan']+ $row['CashBond']+ $row['cashAdvance'];
                $basicCutOffPay = round($row_emp['Salary'] / 2,2);
                if ($inCutOff == 1){
                    $basicCutOffPay = round(getBasicPayAmount($row_emp['emp_id']),2);
                }
                $incentives = $row['totalGrossIncome'] - ($row['ratePayPrd'] + ($row['regularOT'] + $row['restdayOT'] + $row['reg_holidayOT'] + $row['special_holidayOT'] + $row['rd_reg_holidayOT'] + $row['rd_special_holidayOT']) - ($row['Tardiness'] + $row['Absences']));
                if ($row['datePayroll'] >= "2020-01-15"){
                    $incentives = $row['netPay'] - $row['totalGrossIncome'] + $row['totalDeductions'] - $row['Tax'] - $row['NontaxAllowance'] - $row['adjustmentAfter'] - $row['cut_off_13_pay_basic'] - $row['cut_off_13_pay_allowance'];
                }
                if ($row['datePayroll'] == "2020-01-30"){
                    $cut_off_13_pay_basic = $row['cut_off_13_pay_basic'];
                    $cut_off_13_pay_allowance = $row['cut_off_13_pay_allowance'];

                    $december_15_2019_13_pay_basic = 0;
                    $december_15_2019_13_pay_allowance = 0;

                    $december_30_2019_13_pa_basic = 0;
                    $december_30_2019_13_pay_allowance = 0;

                    $january_15_2020_13_pay_basic = 0;
                    $january_15_2020_13_pay_allowance = 0;
                    if ($this->payroll_model->get_cut_off_13_month_pay_old($row['emp_id'],"November 26, 2019 - December 10, 2019") != 0){

                        $row_13 = $this->payroll_model->get_cut_off_13_month_pay_old($row['emp_id'],"November 26, 2019 - December 10, 2019");
    
                        $december_15_2019_13_pay_basic = $row_13['ratePayPrd'];
                        $december_15_2019_13_pay_allowance = $row_13['allowancePay'];
                    }
                    if ($this->payroll_model->get_cut_off_13_month_pay_old($row['emp_id'],"December 11, 2019 - December 25, 2019") != 0){
                        $row_13 = $this->payroll_model->get_cut_off_13_month_pay_old($row['emp_id'],"December 11, 2019 - December 25, 2019");
    
                        $december_30_2019_13_pa_basic = $row_13['ratePayPrd'];
                        $december_30_2019_13_pay_allowance = $row_13['allowancePay'];
                    }
                    if ($this->payroll_model->get_cut_off_13_month_pay_old($row['emp_id'],"December 26, 2019 - January 10, 2020") != 0){

                        $row_13 = $this->payroll_model->get_cut_off_13_month_pay_old($row['emp_id'],"December 26, 2019 - January 10, 2020");
    
                        $january_15_2020_13_pay_basic = $row_13['ratePayPrd'];
                        $january_15_2020_13_pay_allowance = $row_13['allowancePay'];
                    }

                    $cut_off_13_pay_basic += round($december_15_2019_13_pay_basic/12,2) + round($december_30_2019_13_pa_basic/12,2) + round($january_15_2020_13_pay_basic/12,2);


				    $cut_off_13_pay_allowance += round($december_15_2019_13_pay_allowance /12 ,2) + round($december_30_2019_13_pay_allowance /12,2) + round($january_15_2020_13_pay_allowance/12,2);


				    //echo $cut_off_13_pay_basic . " " . $cut_off_13_pay_allowance;


			 	    $incentives = $row['netPay'] - $row['totalGrossIncome'] + $row['totalDeductions'] - $row['Tax'] - $row['NontaxAllowance'] - $row['adjustmentAfter'] - $cut_off_13_pay_basic - $cut_off_13_pay_allowance;
                }
                if ($row['datePayroll'] >= "2020-02-15"){

                    $incentives = $row['netPay'] - $row['totalGrossIncome'] + $row['totalDeductions'] - $row['Tax'] - $row['NontaxAllowance'] - $row['adjustmentAfter'] - $row['cut_off_13_pay_basic'] - $row['cut_off_13_pay_allowance'];
                }
                $last_total_gross_income = 0;
			    if (date_format(date_create($row['datePayroll']),"d") == "30"){
                    if($this->payroll_model->get_payroll_last_total_gross_income_rows($row['emp_id']) !=0){
                        $payrollGross = $this->payroll_model->get_payroll_last_total_gross_income($row['emp_id']);
                        $last_total_gross_income = $payrollGross['totalGrossIncome'] - ($payrollGross['sssDeduction'] + $payrollGross['philhealthDeduction'] + $payrollGross['pagibigDeduction']);
                    }
                }
                $december_15_2019_13_pay_basic = 0;
                $december_15_2019_13_pay_allowance = 0;

                $december_30_2019_13_pa_basic = 0;
                $december_30_2019_13_pay_allowance = 0;

                $january_15_2020_13_pay_basic = 0;
                $january_15_2020_13_pay_allowance = 0;
                $cut_off_13_pay_basic = $row['ratePayPrd'];		
                $cut_off_13_pay_allowance = $row['allowancePay'];
                
                if ($row['CutOffPeriod'] == "January 11, 2020 - January 25, 2020"){
                    if($this->payroll_model->get_cut_off_13_month_pay_old($row['emp_id'],"November 26, 2019 - December 10, 2019") != 0){
                        $row_13 = $this->payroll_model->get_cut_off_13_month_pay_old_data($row['emp_id'],"November 26, 2019 - December 10, 2019");
                        $december_15_2019_13_pay_basic = $row_13['ratePayPrd'];
                        $december_15_2019_13_pay_allowance = $row_13['allowancePay'];
                    }
                    if ($this->payroll_model->get_cut_off_13_month_pay_old($row['emp_id'],"December 11, 2019 - December 25, 2019") != 0){
                        $row_13 = $this->payroll_model->get_cut_off_13_month_pay_old_data($row['emp_id'],"December 11, 2019 - December 25, 2019");
    
                        $december_30_2019_13_pa_basic = $row_13['ratePayPrd'];
                        $december_30_2019_13_pay_allowance = $row_13['allowancePay'];
                    }
                    if ($this->payroll_model->get_cut_off_13_month_pay_old($row['emp_id'],"December 26, 2019 - January 10, 2020") != 0){

                        $row_13 = $this->payroll_model->get_cut_off_13_month_pay_old_data($row['emp_id'],"December 26, 2019 - January 10, 2020");
    
                        $january_15_2020_13_pay_basic = $row_13['ratePayPrd'];
                        $january_15_2020_13_pay_allowance = $row_13['allowancePay'];
                    }
                    
                }
                $total_13_basic_pay = round($december_15_2019_13_pay_basic/12,2) + round($december_30_2019_13_pa_basic/12,2) + round($january_15_2020_13_pay_basic /12,2) + round($cut_off_13_pay_basic/12,2);
                $total_13_allowance_pay = round($december_15_2019_13_pay_allowance/12,2) + round($december_30_2019_13_pay_allowance/12,2) + round($january_15_2020_13_pay_allowance/12,2) + round($cut_off_13_pay_allowance/12,2);
                array_push($employeePayrollData, array(
                    'emp_id'=>$row['emp_id'],
                    'cutOffPeriod'=>$row['CutOffPeriod'],
                    'department'=>$department,
                    'basic_pay'=>round($row['salary']/2,2),
                    'basic_cut_off_pay'=>$basicCutOffPay,
                    'name'=>ucwords($row_emp['Lastname'] . ", " . $row_emp['Firstname'] . " " . $row_emp['Middlename']),
                    'tax_code'=>$row['taxCode'],
                    'has_tax'=>$has_tax,
                    'date_payroll'=>$row['datePayroll'],
                    'incentives'=>number_format($incentives,2),
                    'is_cut_off'=>$is_cut_off,
                    'regularOT'=>$row['regularOT'],
                    'restdayOT'=>$row['restdayOT'],
                    'reg_holidayOT'=>$row['reg_holidayOT'],
                    'special_holidayOT'=>$row['special_holidayOT'],
                    'rd_reg_holidayOT'=>$row['rd_reg_holidayOT'],
                    'rd_special_holidayOT'=>$row['rd_special_holidayOT'],
                    'tardiness'=>$row['Tardiness'],
                    'absences'=>$row['Absences'],
                    'present_amount'=>$row['present_amount'],
                    'totalGrossIncome'=>$row['totalGrossIncome'],
                    'adjustmentEarnings'=>$row['adjustmentEarnings'],
                    'sssDeduction'=>$row['sssDeduction'],
                    'sssLoan'=>$row['sssLoan'],
                    'philhealthDeduction'=>$row['philhealthDeduction'],
                    'pagibigDeduction'=>$row['pagibigDeduction'],
                    'pagibigLoan'=>$row['pagibigLoan'],
                    'cashBond'=>$row['CashBond'],
                    'cashAdvance'=>$row['cashAdvance'],
                    'totalDeductions'=>round($totalDeductions,2),
                    'adjustmentDeductions'=>$row['adjustmentDeductions'],
                    'tax'=>$row['Tax'],
                    'nontaxAllowance'=>$row['NontaxAllowance'],
                    'adjustmentAfter'=>$row['adjustmentAfter'],
                    'dec_15_2019_basic'=>number_format($december_15_2019_13_pay_basic,2),
                    'dec_15_2019_allowance'=>number_format($december_15_2019_13_pay_allowance,2),
                    'dec_30_2019_basic'=>number_format($december_30_2019_13_pa_basic,2),
                    'dec_30_2019_allowance'=>number_format($december_30_2019_13_pay_allowance,2),
                    'jan_15_2019_basic'=>number_format($january_15_2020_13_pay_basic,2),
                    'jan_15_2019_allowance'=>number_format($january_15_2020_13_pay_allowance,2),
                    'current_cut_off_13th_basic_date'=>getDatePayroll(),
                    'current_cut_off_13th_basic_value'=>number_format($cut_off_13_pay_basic,2),
                    'current_cut_off_13th_allowance_date'=>getDatePayroll(),
                    'current_cut_off_13th_allowance_value'=>number_format($cut_off_13_pay_allowance,2),
                    'total_basic'=>number_format($december_15_2019_13_pay_basic + $december_30_2019_13_pa_basic + $january_15_2020_13_pay_basic + $cut_off_13_pay_basic,2),
                    'total_allowance'=>number_format($december_15_2019_13_pay_allowance + $december_30_2019_13_pay_allowance + $january_15_2020_13_pay_allowance + $cut_off_13_pay_allowance,2),
                    'total_13_basic_pay'=>number_format($total_13_basic_pay,2),
                    'total_13_allowance_pay'=>number_format($total_13_allowance_pay,2),
                    'net_pay'=>$row['netPay'],
                    'remarks'=>$row['remarks'],
                ));
                $this->data['status'] = "success";
                $this->data['employeePayrollData'] = $employeePayrollData;
            }

        }
        echo json_encode($this->data);
    }
}