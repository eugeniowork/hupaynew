<div class="top-navbar">
    <a href="">
        <img src="<?php echo base_url();?>assets/images/auth/lloydslogo.png" alt="Lloyds">
        HuPay
    </a>
    <div class="d-flex flex-row pull-right buttons">
        <button class="protip memurandumBtn" data-pt-width="200" data-pt-scheme="blue" data-pt-title="Memurandum"><i class="fa fa-file"></i></button>
        <button class="protip payrollBtn" data-pt-width="200" data-pt-scheme="blue" data-pt-title="Payroll"><i class="fas fa-ruble-sign"></i></button>
        <button class="protip eventsBtn" data-pt-width="200" data-pt-scheme="blue" data-pt-title="Events"><i class="fa fa-calendar"></i></button>
        <button class="protip attendaceBtn" data-pt-width="200" data-pt-scheme="blue" data-pt-title="Attendance"><i class="fa fa-clock"></i></button>
        <?php
            $sessionData = $this->session->userdata('user');
        ?>
        <span><?php echo ucwords($sessionData['Firstname'].' '.$sessionData['Middlename'].' '.$sessionData['Lastname'])?> | </span>
        <div class="dropdown show pull-right">
            &nbsp;
            <a class="btn-link dropdown-toggle" href="#" role="button" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

            <div class="dropdown-menu accountDropdown" aria-labelledby="accountDropdown">
                <button class="dropdown-item ">Profiles</button>
                <button class="dropdown-item logout">Logout</button>
            </div>
        </div>
    </div>
</div>