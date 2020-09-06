<?php $employeeInformation = employeeInformation(); ?>
<?php $this->load->helper('hupay_helper')?>
<?php $this->load->helper('cut_off_helper')?>
<div class="div-main-body file-overtime" >
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
        <div class="modal fade" id="approveOtModal" tabindex="-1" role="dialog" aria-labelledby="approveOtModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveOtModalLongTitle">Request File Overtime</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please enter your password to approve the overtime.</p>
                    <input type="password" class="form-control approve-ot-password" placeholder="Enter your password">
                    <br/>
                    <div class="approve-ot-warning">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary approve-ot-btn">Approve</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="disapproveOtModal" tabindex="-1" role="dialog" aria-labelledby="disapproveOtModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="disapproveOtModalLongTitle">Request File Overtime</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please enter your password to disapprove the overtime.</p>
                    <input type="password" class="form-control disapprove-ot-password" placeholder="Enter your password">
                    <br/>
                    <div class="disapprove-ot-warning">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary disapprove-ot-btn">Disapprove</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/attendance/file_overtime.js"></script>