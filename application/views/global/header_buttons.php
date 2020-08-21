<?php
    $this->load->helper('hupay_helper');
    $employeeInformation = employeeInformation();
?>
<div class="top-navbar">
    <a href="">
        <img src="<?php echo base_url();?>assets/images/auth/lloydslogo.png" alt="Lloyds">
        HuPay
    </a>
    <div class="d-flex flex-row pull-right buttons">
        
        
        
        <!-- data-pt-position="bottom" data-pt-width="200" data-pt-scheme="blue" data-pt-title="Memurandum" -->
        <button class="btn-link">
            
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
        <button class="btn-link ">
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
        <button class="btn-link " >
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
        <button class="btn-link " >
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
        
        <span class="name">&nbsp;<?php echo ucwords($employeeInformation['Firstname'].' '.$employeeInformation['Middlename'].' '.$employeeInformation['Lastname'])?> | </span>
        <div class="dropdown show pull-right">
            &nbsp;
            <a class="btn-link dropdown-toggle" href="#" role="button" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-caret-down"></i>
            </a>

            <div class="dropdown-menu accountDropdown" aria-labelledby="accountDropdown">
                <button class="dropdown-item ">Profiles</button>
                <button class="dropdown-item logout">Logout</button>
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
        <button class="btn messaging-btn">Messaging
            <i class="caret-right-messaging fas fa-caret-right pull-right"></i>
            <i class="caret-down-messaging fas fa-caret-down pull-right"></i>
        </button>
        <div class="side-navbar-sub-buttons messagingDropdown">
            <button class="btn" >Create</button>     
            <button class="btn" >Inbox</button>   
        </div>
        <button class="btn attendance-btn">Attendance
            <i class="caret-right-attendance fas fa-caret-right pull-right"></i>
            <i class="caret-down-attendance fas fa-caret-down pull-right"></i>
        </button>
        <div class="side-navbar-sub-buttons attendanceDropdown">
            <a class="btn" href="<?php base_url();?>attendance">View Attendance</a>     
            <a class="btn" >Sub Attendance List</a>
            <a class="btn" >File Overtime</a>
            <a class="btn" >OT List Approved</a>
            <a class="btn" >Attendance Updates</a>

        </div>
        <button class="btn leaves-btn">Leaves
            <i class="caret-right-leaves fas fa-caret-right pull-right"></i>
            <i class="caret-down-leaves fas fa-caret-down pull-right"></i>
        </button>
        <div class="side-navbar-sub-buttons leavesDropdown">
            <a class="btn" >Leave</a>     
            
            <?php if($employeeInformation['role_id'] == 1):?>
                <a class="btn" >Leave Maintenance</a>
            <?php endif;?>
        </div>
        <button class="btn loans-btn">Loans
            <i class="caret-right-loans fas fa-caret-right pull-right"></i>
            <i class="caret-down-loans fas fa-caret-down pull-right"></i>
        </button>
        <div class="side-navbar-sub-buttons loansDropdown">
            <a class="btn">
                <img src="<?php base_url();?>assets/images/img/government images/pag-ibig-logo.jpg" class="government-logo" alt="Pag-big-Logo"/>
                Pag-ibig Loan
            </a>
            <a class="btn">
                <img src="<?php base_url();?>assets/images/img/government images/SSS-Logo.jpg" class="government-logo" alt="SSS-Logo"/>
                SSS Loan
            </a>
            <a class="btn">
                Salary Loan
            </a>
            <a class="btn">
                File Loan
            </a>
        </div>
        <a class="btn" href="<?php echo base_url();?>mypayslip">My Payslip</a>
        <a class="btn" href="<?php echo base_url();?>simkimban">SIMKIMBAN</a>
        <a class="btn" href="<?php echo base_url();?>cashbond">Cashbond</a>
    </div>
    
</div>

<script src="<?php echo base_url();?>assets/js/global/header_buttons.js"></script>