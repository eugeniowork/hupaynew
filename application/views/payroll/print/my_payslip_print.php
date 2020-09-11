<?php 

	$payroll_class = new Payroll_model;
	$department_class = new Department_model;
	$payroll = $payroll_class->get_payroll_info_for_payslip($id);
	$min_wage_class = new Minimum_wage_model;
	$emp_info_class = new Employee_model;
	$this->load->helper('hupay_helper');
	$this->load->helper('allowance_helper');
	$this->load->helper('date_helper');
	
	if(!empty($payroll)){
		foreach ($payroll as $value) {
			if($value->datePayroll <= '2018-06-15'){
				$empInfo = $emp_info_class->employee_information($value->emp_id);
				$pdf = new PDF_MC_Table("l");
				$pdf->SetMargins("65","35");
				$pdf->AddPage();

				$pdf->SetFillColor(220,220,220); // GRAY
				$pdf->Rect(65, 35, 167, 118, 'F'); //margin-left,margin-top,width,height

				$pdf->Image("assets/images/img/logo/lloyds logo.png",178,38,8,8);// margin-left,margin-top,width,height
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(0,5,"","LRT",1); // FOR SPACING
				$pdf->Cell(0,5,"LLOYDS FINANCING CORPORATION","LR",1,"R");

				$pdf->Cell(0,5,"","LR",1); // FOR SPACING
				//$pdf->Cell(0,5,"",0,1); // FOR SPACING

				$pdf->SetFont("Arial","BU","7");
				$pdf->Cell(0,5,"Employee No: " . $value->emp_id,"L",0,"L");
				$pdf->Cell(0,5,"Payroll Period: ". $value->CutOffPeriod	,"R",1,"R");

				$pdf->Cell(0,5,"Department: " . $department_class->get_department($value->dept_id)['Department'],"L",0,"L");

				$pdf->Cell(0,5,"","R",1,"R");

				$min_wage = $min_wage_class->get_minimum_wage();
				$current_salary = $empInfo['Salary'];

				$min_wage = ($min_wage['basicWage'] + $min_wage['COLA']) * 26;


				if($min_wage >= $current_salary){
					$withTax = 0;
				}
				else{
					$withTax = 1;
				}
				if($withTax == 1){
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(0,5,"Name: " . utf8_decode($empInfo['Lastname'] . ", " . $empInfo['Firstname'] . " " . $empInfo['Middlename']),1,0,"L");
					$pdf->Cell(0,5,"Tax Code: ". $value->taxCode,0,1,"R");
				}
				else {
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(0,5,"Name: " . utf8_decode($empInfo['Lastname'] . ", " . $empInfo['Firstname'] . " " . $empInfo['Middlename']),1,1,"L");
					//$pdf->Cell(0,5,"Tax Code: " . $taxCode,0,1,"R");
				}

				$row_th = $emp_info_class->get_employee_with_cut_off_row_array($value->emp_id,$value->CutOffPeriod);
				$cut_off_13_pay_basic = $row_th['cut_off_13_pay_basic'];
				$cut_off_13_pay_allowance = $row_th['cut_off_13_pay_allowance'];
				// $december_15_2019_13_pay_basic = 0;
				// $december_15_2019_13_pay_allowance = 0;

				// $december_30_2019_13_pa_basic = 0;
				// $december_30_2019_13_pay_allowance = 0;

				// $january_15_2020_13_pay_basic = 0;
				// $january_15_2020_13_pay_allowance = 0;

				// if ($row->CutOffPeriod == "January 11, 2020 - January 25, 2020"){

				// }
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,5,"Earnings","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,5,"Hour",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,5,"Rate",0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,5,"Amount","R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,5,"Deductions",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,5,"Amount","R",1);
				if ($value->datePayroll <= "2020-01-30"){

					// one line
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(30,2,"BASIC PAY","L",0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,2,"",0,0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,2,"",0,0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(20,2,moneyConvertion($value->salary / 2),"R",0,"R");

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(44,2,"",0,0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(43,2,"","R",1);
					
				}
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,5,"","L",0);


				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,5,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,5,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,5,"","R",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,5,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,5,"","R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"REG_OT","L",0);


				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->reg_OThour,2),0,0);
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->reg_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4, moneyConvertion($value->regularOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"WITHHOLDING TAX",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->Tax),"R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"RD_OT","L",0);

				$pdf->SetFont("Arial","B","7");
				//$pdf->Cell(15,4,($attendance_ot_class->getOvertimeHolidayOt($row->emp_id)/60),0,0);
				$pdf->Cell(15,4,round($value->rd_OThour),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->rd_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				//$pdf->Cell(20,4,$money_class->getMoney($row->holidayOT),"R",0,"R");
				$pdf->Cell(20,4,moneyConvertion($value->restdayOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"SSSPREM",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->sssDeduction),"R",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"REG_HLDY_OT","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->reg_holiday_OThour,2),0,0);


				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->reg_holiday_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->reg_holidayOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"SSSLOAN",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->sssLoan),"R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"SPE_HLDY_OT","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->special_holiday_OThour,2),0,0);


				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->special_holiday_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				//$pdf->Cell(20,4,$money_class->getMoney($row->holidayRestdayOT),"R",0,"R");
				$pdf->Cell(20,4,moneyConvertion($value->special_holidayOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"PHILHEALTH",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->philhealthDeduction),"R",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"RD_REG_HLDY_OT","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->rd_reg_holiday_OThour,2),0,0);
				$allowance = getAllowanceInfoToPayslip($value->emp_id);

				$basic_salary = $empInfo['Salary'];

				$tardiness_rate = (($basic_salary + $allowance)/26)/8;

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->rd_reg_holiday_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->rd_reg_holidayOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"PAGIBIG",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->pagibigDeduction),"R",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"RD_SPE_HLDY_OT","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->rd_special_holiday_OThour,2),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->rd_special_holiday_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->rd_special_holidayOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"PAGIBIGLOAN",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->pagibigLoan),"R",1);

				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"TARDINESS","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->tardinessHour,2),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->tardinessRate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->Tardiness),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"CASHBOND",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->CashBond),"R",1);

				if ($value->datePayroll <= "2020-01-30"){
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(30,4,"ABSENCES","L",0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,4,round($row->absencesHour,2),0,0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,4,round($row->absencesRate,2),0,0,"C");

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(20,4,moneyConvertion($value->Absences),"R",0,"R");
				}
				else if ($value->datePayroll >= "2020-02-15"){
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(30,4,"PRESENT","L",0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,4,"-",0,0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,4,"-",0,0,"C");

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(20,4,moneyConvertion($value->present_amount),"R",0,"R");
				}

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"CASHADVANCE",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->cashAdvance),"R",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"ADJUSTMENT","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->adjustmentAfter + $value->adjustmentBefore),"R",0,"R");

				$total = $value->Allowance + $value->totalGrossIncome;

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,"","R",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"GROSS INCOME","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);




				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion(round($value->totalGrossIncome,2)),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,"","R",1);

				//comment this start

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"NONTAX ALLOWANCE","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->NontaxAllowance),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,"","R",1);
				//comment this end

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,"","R",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,"","R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"TOTAL","LB",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion(round($value->adjustmentAfter + $value->adjustmentBefore + $value->totalGrossIncome + $value->NontaxAllowance,2)),"RB",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"TOTAL DEDUCTIONS","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->sssDeduction + $value->sssLoan + $value->philhealthDeduction + $value->pagibigLoan + $value->CashBond + $value->cashAdvance + $value->pagibigDeduction +$value->Tax),"RB",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(80,4,"",1,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(87,4,"",1,1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"BASIC RATE","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->basicRate),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"DAILY RATE",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->dailyRate),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"YTD GROSS",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->ytdGross),0,0);

				if ($value->datePayroll == "2020-01-30"){

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(25,4,"13th Month Pay(12/15/2019-01/30/2020)",0,0);
				}


				if ($value->datePayroll > "2020-01-30"){
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(25,4,"13th Month Pay(".$value->datePayroll.")",0,0);
				}
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(22,4,"","R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"ALLOWANCE","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->Allowance),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"DAILY ALLOW",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->dailyAllowance),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"YTD ALLOWANCE",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->ytdAllowance),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"BASIC",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(22,4,$cut_off_13_pay_basic,"R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"RATE/ PAY PRD",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->ratePayPrd),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"YTD W/ TAX",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->ytdWithTax),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"ALLOWANCE",0,0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(22,4,$cut_off_13_pay_allowance,"R",1);



				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"","BL",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"ALLOW/ PAY","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->allowancePay),"RB",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"CASH ADV. BAL","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->cashAdvBal),"B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"NET PAY","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(22,4,moneyConvertion($value->netPay),"RB",1);



				// for single row
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(80,4,dateFormat($value->datePayroll)." PAYOUT","LBR",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(87,4,"","RB",1);

				echo $pdf->output("I",dateFormat($value->datePayroll) . "_" .$empInfo['Lastname'] . ", " . $empInfo['Firstname'] . " " . $empInfo['Middlename'].'.pdf');
			}
			else{
				$empInfo = $emp_info_class->employee_information($value->emp_id);
				$pdf = new PDF_MC_Table("l");
				$pdf->SetMargins("65","35");
				$pdf->AddPage();

				$pdf->SetFillColor(220,220,220); // GRAY
				$pdf->Rect(65, 35, 167, 118, 'F'); //margin-left,margin-top,width,height

				$pdf->Image("assets/images/img/logo/lloyds logo.png",178,38,8,8);// margin-left,margin-top,width,height
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(0,5,"","LRT",1); // FOR SPACING
				$pdf->Cell(0,5,"LLOYDS FINANCING CORPORATION","LR",1,"R");

				$pdf->Cell(0,5,"","LR",1); // FOR SPACING
				//$pdf->Cell(0,5,"",0,1); // FOR SPACING

				$pdf->SetFont("Arial","BU","7");
				$pdf->Cell(0,5,"Employee No: " . $value->emp_id,"L",0,"L");
				$pdf->Cell(0,5,"Payroll Period: ". $value->CutOffPeriod	,"R",1,"R");

				$pdf->Cell(0,5,"Department: " . $department_class->get_department($value->dept_id)['Department'],"L",0,"L");

				$pdf->Cell(0,5,"","R",1,"R");

				$min_wage = $min_wage_class->get_minimum_wage();
				$current_salary = $empInfo['Salary'];

				$min_wage = ($min_wage['basicWage'] + $min_wage['COLA']) * 26;


				if($min_wage >= $current_salary){
					$withTax = 0;
				}
				else{
					$withTax = 1;
				}
				if($withTax == 1){
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(0,5,"Name: " . utf8_decode($empInfo['Lastname'] . ", " . $empInfo['Firstname'] . " " . $empInfo['Middlename']),1,0,"L");
					$pdf->Cell(0,5,"Tax Code: ". $value->taxCode,0,1,"R");
				}
				else {
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(0,5,"Name: " . utf8_decode($empInfo['Lastname'] . ", " . $empInfo['Firstname'] . " " . $empInfo['Middlename']),1,1,"L");
					//$pdf->Cell(0,5,"Tax Code: " . $taxCode,0,1,"R");
				}

				$row_th = $emp_info_class->get_employee_with_cut_off_row_array($value->emp_id,$value->CutOffPeriod);
				$cut_off_13_pay_basic = $row_th['cut_off_13_pay_basic'];
				$cut_off_13_pay_allowance = $row_th['cut_off_13_pay_allowance'];
				// $december_15_2019_13_pay_basic = 0;
				// $december_15_2019_13_pay_allowance = 0;

				// $december_30_2019_13_pa_basic = 0;
				// $december_30_2019_13_pay_allowance = 0;

				// $january_15_2020_13_pay_basic = 0;
				// $january_15_2020_13_pay_allowance = 0;

				// if ($row->CutOffPeriod == "January 11, 2020 - January 25, 2020"){

				// }
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,5,"Earnings","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,5,"Hour",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,5,"Rate",0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,5,"Amount","R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,5,"Deductions",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,5,"Amount","R",1);
				if ($value->datePayroll <= "2020-01-30"){

					// one line
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(30,2,"BASIC PAY","L",0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,2,"",0,0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,2,"",0,0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(20,2,moneyConvertion($value->salary / 2),"R",0,"R");

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(44,2,"",0,0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(43,2,"","R",1);
					
				}
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,5,"","L",0);


				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,5,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,5,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,5,"","R",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,5,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,5,"","R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"REG_OT","L",0);


				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->reg_OThour,2),0,0);
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->reg_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4, moneyConvertion($value->regularOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"WITHHOLDING TAX",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->Tax),"R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"RD_OT","L",0);

				$pdf->SetFont("Arial","B","7");
				//$pdf->Cell(15,4,($attendance_ot_class->getOvertimeHolidayOt($row->emp_id)/60),0,0);
				$pdf->Cell(15,4,round($value->rd_OThour),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->rd_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				//$pdf->Cell(20,4,$money_class->getMoney($row->holidayOT),"R",0,"R");
				$pdf->Cell(20,4,moneyConvertion($value->restdayOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"SSSPREM",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->sssDeduction),"R",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"REG_HLDY_OT","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->reg_holiday_OThour,2),0,0);


				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->reg_holiday_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->reg_holidayOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"SSSLOAN",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->sssLoan),"R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"SPE_HLDY_OT","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->special_holiday_OThour,2),0,0);


				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->special_holiday_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				//$pdf->Cell(20,4,$money_class->getMoney($row->holidayRestdayOT),"R",0,"R");
				$pdf->Cell(20,4,moneyConvertion($value->special_holidayOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"PHILHEALTH",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->philhealthDeduction),"R",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"RD_REG_HLDY_OT","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->rd_reg_holiday_OThour,2),0,0);
				$allowance = getAllowanceInfoToPayslip($value->emp_id);

				$basic_salary = $empInfo['Salary'];

				$tardiness_rate = (($basic_salary + $allowance)/26)/8;

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->rd_reg_holiday_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->rd_reg_holidayOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"PAGIBIG",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->pagibigDeduction),"R",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"RD_SPE_HLDY_OT","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->rd_special_holiday_OThour,2),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->rd_special_holiday_OTrate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->rd_special_holidayOT),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"PAGIBIGLOAN",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->pagibigLoan),"R",1);

				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"TARDINESS","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->tardinessHour,2),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,round($value->tardinessRate,2),0,0,"C");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->Tardiness),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"CASHBOND",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->CashBond),"R",1);

				if ($value->datePayroll <= "2020-01-30"){
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(30,4,"ABSENCES","L",0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,4,round($row->absencesHour,2),0,0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,4,round($row->absencesRate,2),0,0,"C");

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(20,4,moneyConvertion($value->Absences),"R",0,"R");
				}
				else if ($value->datePayroll >= "2020-02-15"){
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(30,4,"PRESENT","L",0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,4,"-",0,0);

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(15,4,"-",0,0,"C");

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(20,4,moneyConvertion($value->present_amount),"R",0,"R");
				}

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"CASHADVANCE",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->cashAdvance),"R",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"ADJUSTMENT","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion($value->adjustmentAfter + $value->adjustmentBefore),"R",0,"R");

				$total = $value->Allowance + $value->totalGrossIncome;

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,"","R",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"GROSS INCOME","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);




				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion(round($value->totalGrossIncome,2)),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,"","R",1);

				//comment this start

				// $pdf->SetFont("Arial","B","7");
				// $pdf->Cell(30,4,"NONTAX ALLOWANCE","L",0);

				// $pdf->SetFont("Arial","B","7");
				// $pdf->Cell(15,4,"",0,0);

				// $pdf->SetFont("Arial","B","7");
				// $pdf->Cell(15,4,"",0,0);

				// $pdf->SetFont("Arial","B","7");
				// $pdf->Cell(20,4,moneyConvertion($value->NontaxAllowance),"R",0,"R");

				// $pdf->SetFont("Arial","B","7");
				// $pdf->Cell(44,4,"",0,0);

				// $pdf->SetFont("Arial","B","7");
				// $pdf->Cell(43,4,"","R",1);
				//comment this end

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,"","R",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,"","R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,"TOTAL","LB",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,moneyConvertion(round($value->adjustmentAfter + $value->adjustmentBefore + $value->totalGrossIncome + $value->NontaxAllowance,2)),"RB",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"TOTAL DEDUCTIONS","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,moneyConvertion($value->sssDeduction + $value->sssLoan + $value->philhealthDeduction + $value->pagibigLoan + $value->CashBond + $value->cashAdvance + $value->pagibigDeduction +$value->Tax),"RB",1);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(80,4,"",1,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(87,4,"",1,1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"BASIC RATE","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->basicRate),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"DAILY RATE",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->dailyRate),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"YTD GROSS",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->ytdGross),0,0);

				if ($value->datePayroll == "2020-01-30"){

					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(25,4,"13th Month Pay(12/15/2019-01/30/2020)",0,0);
				}


				if ($value->datePayroll > "2020-01-30"){
					$pdf->SetFont("Arial","B","7");
					$pdf->Cell(25,4,"13th Month Pay(".$value->datePayroll.")",0,0);
				}
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(22,4,"","R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"ALLOWANCE","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->Allowance),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"DAILY ALLOW",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->dailyAllowance),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"YTD ALLOWANCE",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->ytdAllowance),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"BASIC",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(22,4,$cut_off_13_pay_basic,"R",1);


				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"","L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"RATE/ PAY PRD",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->ratePayPrd),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"YTD W/ TAX",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->ytdWithTax),0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"ALLOWANCE",0,0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(22,4,$cut_off_13_pay_allowance,"R",1);



				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"","BL",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"ALLOW/ PAY","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->allowancePay),"RB",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"CASH ADV. BAL","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,moneyConvertion($value->cashAdvBal),"B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(25,4,"NET PAY","B",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(22,4,moneyConvertion($value->netPay),"RB",1);



				// for single row
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(80,4,dateFormat($value->datePayroll)." PAYOUT","LBR",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(87,4,"","RB",1);

				echo $pdf->Output("I",dateFormat($value->datePayroll) . "_" .$empInfo['Lastname'] . ", " . $empInfo['Firstname'] . " " . $empInfo['Middlename'].'.pdf');
			}
		}
	}















 ?>