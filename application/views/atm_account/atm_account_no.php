<div class="div-main-body" >
    <div class="div-main-body-head">
        List of Employee with ATM (Account No. Information)
    </div>
    <div class="div-main-body-content atm-account-no">
        <button class="btn btn-sm pull-right btn-outline-success print-atm-account-report">Print ATM Account No Reports</button>
        <br/><br/>
        <table class="table table-striped" id="atmAccountNoList">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                    <th><i class="fas fa-clock"></i>&nbsp;UB ATM Account No.</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
        <div class="modal fade" id="editAtmNoModal" tabindex="-1" role="dialog" aria-labelledby="editAtmNoModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAtmNoModalLongTitle">Update ATM No</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="atm-account-info">
                            <span>Account No.</span>
                            <input type="text" class="form-control account-no" placeholder="Enter account no">
                        
                        </div>
                        <div class="loading-atm-account-no">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div><br/>
                        <div class="atm-account-no-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary update-atm-account-no-btn">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/atm_account/atm_account_no.js"></script>