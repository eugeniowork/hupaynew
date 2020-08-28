<div class="div-main-body payroll-reports">
    <div class="div-main-body-head ">
        Payroll Reports List
    </div>
    <div class="div-main-body-content ">
        <table class="table table-bordered table-payroll-reports">
            <thead>
                <tr>
                    <th style="background-color: #85929e;color:#fff;">Cutt Off Period</th>
                    <th style="background-color: #85929e;color:#fff;">Status</th>
                    <th style="background-color: #85929e;color:#fff;">Action</th>
                    
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div class="modal fade" id="preApprovePayrollModal" tabindex="-1" role="dialog" aria-labelledby="preApprovePayrollModalTitle" aria-hidden="true">
        <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preApprovePayrollModalLongTitle">Payroll Pre Approval</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please enter your password to approve the payroll.</p>
                <input type="password" class="form-control pre-approve-password" placeholder="Enter your password">
                <br/>
                <div class="pre-approve-payroll-warning">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary pre-approve-payroll-btn">Approve</button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approvePayrollModal" tabindex="-1" role="dialog" aria-labelledby="approvePayrollModalTitle" aria-hidden="true">
        <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvePayrollModalLongTitle">Payroll Approval</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please enter your password to approve the payroll.</p>
                <input type="password" class="form-control approve-password" placeholder="Enter your password">
                <br/>
                <div class="approve-payroll-warning">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary approve-payroll-btn">Approve</button>
            </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/payroll_reports/payroll_reports.js"></script>