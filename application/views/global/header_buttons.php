<?php
    $this->load->helper('attendance_helper');
    $this->load->helper('hupay_helper');
    $this->load->helper('memorandum_helper');
    $this->load->helper('payroll_helper');
    $this->load->helper('events_helper');
    
    $employeeInformation = employeeInformation();
?>
<div class="top-navbar">
    <a href="">
        <img src="<?php echo base_url();?>assets/images/auth/lloydslogo.png" alt="Lloyds">
        HuPay
    </a>
    <div class="d-flex flex-row pull-right buttons">
        
        
        
        <!-- data-pt-position="bottom" data-pt-width="200" data-pt-scheme="blue" data-pt-title="Memurandum" -->
        <button class="btn-link memo-notif-btn">
            
            <i class="fa fa-file">  
                <?php $unreadMemo = unreadMemoNotif();?>
                <?php if(count($unreadMemo) > 0):?>
                    <span class="notif-count">
                        <?php echo count($unreadMemo)?>
                    </span>
                <?php endif;?>
            </i>
            &nbsp;
        </button>
        <div class="memo-notif">
            <div class="memo-head">
                <strong>Memorandum Notification</strong>
            </div>
            <hr>
            <div class="memo-body">
                <div class="notif-list">
                    <?php echo getAllMemoNotif(); ?>
                    <!-- <div class="notif-content">
                        <div class="d-flex flex-row">
                            <img src="http://localhost/hupaynew/assets/images/auth/lloydslogo.png">

                            <div class="notif-content-sub">
                                <b>Derick eugenio</b> about <b>Loan product revision</b> on
                                <b>September 06, 2019</b> at <b>4:00 pm</b>
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
        <button class="btn-link payroll-notif-btn">
            <i class="fas fa-ruble-sign">
                <?php $unreadPayroll = unreadPayrollNotif();?>
                <?php if(count($unreadPayroll) > 0):?>
                    <span class="notif-count">
                        <?php echo count($unreadPayroll)?>
                    </span>
                <?php endif;?>
            </i>
            &nbsp;
        </button>
        <div class="payroll-notif">
            <div class="payroll-head">
                <strong>Payroll Notification</strong>
            </div>
            <hr>
            <div class="payroll-body">
                <div class="notif-list">
                    <?php echo getAllPayrollNotif(); ?>
                </div>
            </div>
        </div>

        <button class="btn-link events-notif-btn" >
            <i class="fa fa-calendar">
                <?php $unreadEvents = unreadEventsNotif();?>
                <?php if(count($unreadEvents) > 0):?>
                    <span class="notif-count">
                        <?php echo count($unreadEvents)?>
                    </span>
                <?php endif;?>
            </i>
            &nbsp;
        </button>
        <div class="events-notif">
            <div class="events-head">
                <strong>Events Notification</strong>
            </div>
            <hr>
            <div class="events-body">
                <div class="notif-list">
                    <?php echo getAllEventsNotif(); ?>
                </div>
            </div>
        </div>


        <button class="btn-link attendance-notif-btn" >
            <i class="fa fa-clock">
                <?php $unreadAttendance = unreadAttendanceNotif();?>
                <?php if(count($unreadAttendance) > 0):?>
                    <span class="notif-count">
                        <?php echo count($unreadAttendance)?>
                        
                    </span>
                <?php endif;?>
            </i>
            &nbsp;
        </button>
        
        <div class="attendance-notif">
            <div class="attendance-head">
                <strong>Attendance Notification</strong>
            </div>
            <hr>
            <div class="attendance-body">
                <div class="notif-list">
                    <?php echo notificationsAttendance(); ?>
                </div>
            </div>
        </div>



        <span class="name">&nbsp;<?php echo ucwords($employeeInformation['Firstname'].' '.$employeeInformation['Middlename'].' '.$employeeInformation['Lastname'])?> | </span>
        <div class="dropdown show pull-right">
            &nbsp;
            <a class="btn-link dropdown-toggle" href="#" role="button" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-caret-down"></i>
            </a>

            <div class="dropdown-menu accountDropdown" aria-labelledby="accountDropdown">
                <a href="#" class="dropdown-item ">Profiles</a>
                <a href="<?php echo base_url();?>logout" class="dropdown-item logout">Logout</a>
            </div>
        </div>
    </div>
</div>
<div class="side-navbar">
    <div class="side-navbar-profile">
        <div class="side-navbar-profile-content d-flex flex-column justify-content-center align-items-center">
            
            <img src="<?php echo base_url();?>assets/images/<?php echo $employeeInformation['ProfilePath']?>" alt="">
            <button class="btn btn-sm btn-outline-primary change-profile-btn"><i class="fa fa-camera"></i>&nbsp;Change Profile</button>
            <span class="fullname">
                <?php echo ucwords($employeeInformation['Firstname'].' '.$employeeInformation['Middlename'].' '.$employeeInformation['Lastname'])?>
            </span>
            <span class="position">
                <?php $position = getEmployeePosition(); echo $position['Position']?>
            </span>
        </div>
    </div>
    <div class="side-navbar-buttons">
        <a class="btn" href="<?php echo base_url();?>dashboard">Dashboard</a>
        <?php if($employeeInformation['role_id'] != 4):?>
            <button class="btn employee-btn">Employee
                <i class="caret-right-employee fas fa-caret-right pull-right"></i>
                <i class="caret-down-employee fas fa-caret-down pull-right"></i>
            </button>
            <div class="side-navbar-sub-buttons employeeDropdown">
                <?php if($employeeInformation['role_id'] != 21):?>
                    <a class="btn" href="<?php echo base_url();?>employee_registration">Registration</a>  
                <?php endif;?>
                <a class="btn" href="<?php echo base_url();?>employee_list">Employee List</a>
            </div>
        <?php endif;?>
        <button class="btn messaging-btn">Messaging
            <i class="caret-right-messaging fas fa-caret-right pull-right"></i>
            <i class="caret-down-messaging fas fa-caret-down pull-right"></i>
        </button>
        <div class="side-navbar-sub-buttons messagingDropdown">
            <a class="btn" href="<?php echo base_url();?>create_message">Create</a>     
            <a  href="<?php echo base_url();?>inbox" class="btn" >Inbox</a>   
        </div>
        <?php if($employeeInformation['role_id'] != 4):?>
            <a class="btn" href="<?php echo base_url();?>atm">ATM Account No</a>
            <a class="btn" href="<?php echo base_url();?>workingschedule">Working Hours & Days</a>
        <?php endif;?>

        <?php if($employeeInformation['role_id'] == 4 || $employeeInformation['role_id'] ==1):?>
            <a class="btn" href="<?php echo base_url();?>memorandum">Memorandum</a>
        <?php endif;?>

        <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] ==3):?>
            <a class="btn" href="<?php echo base_url();?>minimumwage">Minimum Wage</a>
        <?php endif;?>
        <?php if ($employeeInformation['role_id'] == 1): ?>
            <a class="btn" href="<?php echo base_url();?>biometrics">Biometrics Registration</a>
        <?php endif ?>

        <?php if($employeeInformation['role_id'] != 4):?>
            <a class="btn" href="<?php echo base_url();?>department">Department</a>
            <a class="btn" href="<?php echo base_url();?>position">Position</a>
        <?php endif;?>
        
        <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3):?>
            <button class="btn gov-table-btn">Gov't Table
                <i class="caret-right-gov-table fas fa-caret-right pull-right"></i>
                <i class="caret-down-gov-table fas fa-caret-down pull-right"></i>
            </button>
            <div class="side-navbar-sub-buttons govTableDropdown">
                <a class="btn" href="<?php base_url();?>sss_contribution">SSS</a>     
                <a class="btn" href="<?php base_url();?>bir_contribution">BIR</a>
                <a class="btn" href="<?php base_url();?>pagibig_contribution">Pag-ibig</a>
                <a class="btn" href="<?php base_url();?>philhealth_contribution">PhilHealth</a>
            </div>
        <?php endif;?>

        <?php if($employeeInformation['role_id'] == 1):?>
            <a class="btn" href="<?php echo base_url();?>holiday">Holiday</a>
        <?php endif;?>
        
        <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 2):?>
            <a class="btn" href="<?php echo base_url();?>events">Events</a>
        <?php endif;?>

        <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 2 
            || $employeeInformation['role_id'] == 3 || $employeeInformation['role_id'] == 4
            && $employeeInformation['bio_id'] !=0):?>
            <button class="btn attendance-btn">Attendance
                <i class="caret-right-attendance fas fa-caret-right pull-right"></i>
                <i class="caret-down-attendance fas fa-caret-down pull-right"></i>
            </button>
            <div class="side-navbar-sub-buttons attendanceDropdown">
                <!-- <a class="btn" href="<?php base_url();?>attendance">View Attendance</a>     
                <a class="btn" >Sub Attendance List</a>
                <a class="btn" >File Overtime</a>
                <a class="btn" >OT List Approved</a>
                <a class="btn" >Attendance Updates</a> -->
                <?php if($employeeInformation['role_id'] == 1):?>
                    <a class="btn" href="<?php base_url();?>upload_attendance">Upload Attendance</a>
                <?php endif;?>

                <?php if($employeeInformation['role_id'] == 2 || $employeeInformation['role_id'] == 3 || 
                    $employeeInformation['role_id'] == 4 && $employeeInformation['bio_id'] !=0):?>
                    <a class="btn" href="<?php base_url();?>attendance">View Attendance</a>  
                <?php endif;?>

                <?php if($employeeInformation['role_id'] != 4):?>
                    <a class="btn" href="<?php base_url();?>attendance_list">Attendance List</a>
                <?php endif;?>
                
                <?php if(!empty(checkIfHead())):?>
                    <a class="btn" href="<?php base_url();?>subattendancelist">Sub Attendance List</a>
                <?php endif;?>

                <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 2
                    || !empty(checkIfHead())):?>
                    <?php if($employeeInformation['emp_id'] != 153):?>
                        <a class="btn" href="<?php base_url();?>file_overtime">File Overtime</a>
                    <?php endif;?>
                    
                <?php endif;?>

                <?php if($employeeInformation['role_id'] !=4 || !empty(checkIfHead())):?>
                    <?php if($employeeInformation['emp_id'] != 153):?>
                        <a class="btn" href="<?php base_url();?>ot_list_approved">OT List Approved</a>
                    <?php endif;?>
                <?php endif;?>

                <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 2 
                    || !empty(checkIfHead())):?>
                    <?php if($employeeInformation['emp_id'] != 153):?>
                        <a class="btn" href="<?php base_url();?>attendance_updates">Attendance Updates</a>
                    <?php endif;?>
                <?php endif;?>

                <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3 ):?>
                    <a class="btn" href="<?php base_url();?>add_attendance">Add Attendance</a>
                <?php endif;?>
            </div>


        <?php endif;?>

        
        <?php if($employeeInformation['role_id'] == 2 || $employeeInformation['role_id'] == 3 
            || $employeeInformation['role_id'] == 1 || !empty(checkIfHead())):?>
            <?php if($employeeInformation['emp_id'] != 153):?>
                <button class="btn leaves-btn">Leaves
                    <i class="caret-right-leaves fas fa-caret-right pull-right"></i>
                    <i class="caret-down-leaves fas fa-caret-down pull-right"></i>
                </button>
                <div class="side-navbar-sub-buttons leavesDropdown">
                    <a class="btn" href="<?php base_url();?>leave">Leave</a>     
                    
                    <?php if($employeeInformation['role_id'] == 1):?>
                        <a class="btn" href="<?php base_url();?>leave_maintenance">Leave Maintenance</a>
                    <?php endif;?>
                </div>
            <?php endif;?>
        <?php endif;?>


        <button class="btn loans-btn">Loans
            <i class="caret-right-loans fas fa-caret-right pull-right"></i>
            <i class="caret-down-loans fas fa-caret-down pull-right"></i>
        </button>
        <div class="side-navbar-sub-buttons loansDropdown">
            <a  href="<?php base_url();?>pagibig_loan" class="btn">
                <img src="<?php base_url();?>assets/images/img/government images/pag-ibig-logo.jpg" class="government-logo" alt="Pag-big-Logo"/>
                Pag-ibig Loan
            </a>
            <a href="<?php base_url();?>sss_loan" class="btn">
                <img src="<?php base_url();?>assets/images/img/government images/SSS-Logo.jpg" class="government-logo" alt="SSS-Logo"/>
                SSS Loan
            </a>
            <a href="<?php base_url();?>salary_loan" class="btn">
                Salary Loan
            </a>
            <a href="<?php base_url();?>file_loan" class="btn">
                File Loan
            </a>
        </div>
        <?php if($employeeInformation['role_id'] == 3 || $employeeInformation['role_id'] == 1):?>
            <button class="btn payroll-btn">Payroll
                <i class="caret-right-payroll fas fa-caret-right pull-right"></i>
                <i class="caret-down-payroll fas fa-caret-down pull-right"></i>
            </button>
            <div class="side-navbar-sub-buttons payrollDropdown">
                <a class="btn" href="<?php echo base_url();?>generatepayroll"> Create Salary</a>
                <a class="btn" href="<?php echo base_url();?>payrollinformation"> View Payroll Info</a>
                <a class="btn" href="<?php echo base_url();?>my_payslip">My Payslip</a>
            </div>
        <?php endif;?>

        <?php if($employeeInformation['role_id'] != 3 && $employeeInformation['role_id'] != 1):?>
            <a class="btn" href="<?php echo base_url();?>mypayslip">My Payslip</a>
        <?php endif;?>

        <a class="btn" href="<?php echo base_url();?>simkimban">SIMKIMBAN</a>
        <a class="btn" href="<?php echo base_url();?>cashbond">Cashbond</a>

        <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3):?>
            <a class="btn" href="<?php echo base_url();?>year_total_deduction">Year Total Deduction</a>
        <?php endif;?>

        <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3 || 
            $employeeInformation['emp_id'] == 47):?>
            <a class="btn" href="<?php echo base_url();?>salary_information">Salary Information</a>
        <?php endif;?>

        <?php if($employeeInformation['role_id'] == 1):?>
            <a class="btn" href="<?php echo base_url();?>audit_trail">Audit Trail</a>
        <?php endif;?>

        <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3):?>
            <button class="btn adjustment-report-btn">Adjustment Reports
                <i class="caret-right-adjustment-report fas fa-caret-right pull-right"></i>
                <i class="caret-down-adjustment-report fas fa-caret-down pull-right"></i>
            </button>
            <div class="side-navbar-sub-buttons adjustmentReportDropdown">
                <a class="btn" href="<?php echo base_url();?>loan_adjustment">Loan Adjustment</a>
                <a class="btn" href="<?php echo base_url();?>simkimban_adjustment">SIMKIMBAN Adjustment</a>
            </div>
        <?php endif;?>
        <?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3 ||
            $employeeInformation['role_id'] == 2 && $employeeInformation['emp_id'] != 153 &&
            $employeeInformation['emp_id'] != 21 || $employeeInformation['emp_id'] == 47 
            || $employeeInformation['emp_id'] == 44):?>

            <button class="btn payroll-reports-btn">Payroll Reports
                <i class="caret-right-payroll-reports fas fa-caret-right pull-right"></i>
                <i class="caret-down-payroll-reports fas fa-caret-down pull-right"></i>
            </button>
            <div class="side-navbar-sub-buttons payrollReportsDropdown">
                <a class="btn" href="<?php echo base_url();?>payrollreports"> Payroll</a>
                <?php if($employeeInformation['role_id'] != 2 && $employeeInformation['emp_id'] != 47
                    && $employeeInformation['emp_id'] != 44):?>

                    <a class="btn" href="<?php echo base_url();?>adjustmentreports">Adjustment</a>
                <?php endif;?>
                
            </div>
        <?php endif;?>

    </div>
    
</div>

<script src="<?php echo base_url();?>assets/js/global/header_buttons.js"></script>