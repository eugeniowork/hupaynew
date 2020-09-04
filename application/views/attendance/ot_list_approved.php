<?php $employeeInformation = employeeInformation(); ?>
<?php $this->load->helper('hupay_helper')?>
<?php $this->load->helper('cut_off_helper')?>
<?php if ($employeeInformation['role_id'] == 2 || !empty(checkIfHead())): ?>
    <div class="div-main-body" >
        <div class="div-main-body-head">
            <span>Overtime Request List - cut off: <?php echo getCutOffPeriodLatest();?></span>
        </div>
        <div class="div-main-body-content ">
            <table class="table table-striped" id="overtimeRequestList">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Date</th>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Time From</th>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Time Out</th>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Type of OT</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
    </div>
<?php endif ?>
<?php if ($employeeInformation['role_id'] != 4): ?>
    <div class="div-main-body" >
        <div class="div-main-body-head">
            <span>All Overtime Approve List - cut off: <?php echo getCutOffPeriodLatest();?></span>
        </div>
        <div class="div-main-body-content ">
            <table class="table table-striped" id="allOvertimeApproveList">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Date</th>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Time From</th>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Time Out</th>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Type of OT</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
            <br/><br/>
            <button class="btn btn-outline-success pull-right">Print OT History Reports</button>
            <span class="pull-right">&nbsp;</span>
            <button class="btn btn-outline-success pull-right">Print Cut Off Approve OT List</button>
            <br/><br/>
        </div>
    </div>
<?php endif ?>

<script src="<?php echo base_url();?>assets/js/attendance/ot_list_approved.js"></script>