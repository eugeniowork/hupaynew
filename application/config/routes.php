<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['create_message'] = 'messaging_controller/viewCreateMessage';
$route['inbox'] = 'messaging_controller/viewInbox';
$route['upload_attendance'] = 'attendance_controller/viewUploadAttendance';
$route['my_payslip'] = 'payroll_controller/viewMyPaySlip';
$route['employee_registration'] = 'employee_controller/viewEmployeeRegistration';
$route['employee_list'] = 'employee_controller/viewEmployeeList';
$route['memorandum'] = 'memorandum_controller';
$route['biometrics'] = 'biometrics_controller';
$route['department'] = 'department_controller';
$route['position'] = 'position_controller';
$route['philhealth_contribution'] = 'philhealth_controller';
$route['pagibig_contribution'] = 'pagibig_controller';
$route['bir_contribution'] = 'bir_controller';
$route['sss_contribution'] = 'sss_controller';
$route['holiday'] = 'holiday_controller';
$route['events'] = 'events_controller';
$route['attendance_list'] = 'attendance_controller/viewAttendanceList';
$route['file_overtime'] = 'attendance_controller/viewOtList';
$route['ot_list_approved'] = 'attendance_controller/viewOtListApproved';
$route['attendance_updates'] = 'attendance_controller/viewAttendanceUpdates';
$route['add_attendance'] = 'attendance_controller/viewAddAttendance';
$route['leave_maintenance'] = 'leave_controller/viewLeaveMaintenance';
$route['leave'] = 'leave_controller';
$route['file_loan'] = 'loans_controller/viewFileLoan';
$route['salary_loan'] = 'loans_controller/viewSalaryLoan';
$route['sss_loan'] = 'loans_controller/viewSssLoan';
$route['pagibig_loan'] = 'loans_controller';
$route['simkimban'] = 'simkimban_controller';

$route['year_total_deduction'] = 'deduction_controller';

$route['salary_information'] = 'salary_controller';
$route['audit_trail'] = 'audit_trail_controller';
$route['loan_adjustment'] = 'adjustment_reports_controller/viewLoanAdjustment';
$route['simkimban_adjustment'] = 'adjustment_reports_controller';
$route['cashbond'] = 'cashbond_controller';
$route['payrollreports'] = 'payroll_reports_controller/viewPayrollReports';
$route['download/(:any)'] = 'payroll_reports_controller/printPayrollAdjustmentReport/$1';
$route['adjustmentreports'] = 'payroll_reports_controller';
$route['minimumwage'] = 'minimum_wage_controller';
$route['payrollinformation'] = 'payroll_controller/viewPayrollInfo';
$route['generatepayroll'] = 'payroll_controller';
$route['workingschedule'] = 'working_days_controller/viewWorkingSchedule';
$route['atm'] = 'employee_controller/viewATMAccounts';
$route['attendance'] = 'attendance_controller';
$route['logout'] = 'dashboard_controller/logout';
$route['dashboard'] = 'dashboard_controller';
$route['login'] = 'login_controller';
$route['default_controller'] = 'login_controller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
