<?php $employeeInformation = employeeInformation(); ?>
<?php $this->load->helper('hupay_helper')?>
<?php $this->load->helper('cut_off_helper')?>
<div class="div-main-body" >
    <div class="div-main-body-head">
        <span>Overtime Request List - cut off: <?php echo getCutOffPeriodLatest();?></span>
    </div>
    <div class="div-main-body-content ">
        <table class="table table-striped" id="overtimeRequestList">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Date File</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;OT Date</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Time In</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Time Out</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Remarks</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/attendance/file_overtime.js"></script>