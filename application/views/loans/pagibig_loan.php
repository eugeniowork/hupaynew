<?php $employeeInformation = employeeInformation();?>

<?php if ($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3): ?>
    <div class="div-main-body pagibig" >
        <div class="div-main-body-head">
            List of Employee With Existing Pag-ibig Loan
        </div>
        <div class="div-main-body-content">
            <table class="table table-striped" id="employeeWithExistingPagibig">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-clock"></i>&nbsp;Range Payment</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Amount Loan</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Deduction</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Outstanding Balance</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
         <div class="modal fade" id="editPagibigModal" tabindex="-1" role="dialog" aria-labelledby="editPagibigModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPagibigModalLongTitle">Update Pag-ibig Loan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="pagibig-info">
                            <div class="row">
                                <div class="col-lg-8">
                                    <span>Employee Name</span>
                                    <input type="text" class="input-only form-control employee-name" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <span>Date From</span>
                                    <input type="text" class="form-control date-from" placeholder="Select date">
                                </div>
                                <div class="col-lg-4">
                                    <span>Date To</span>
                                    <input type="text" class="form-control date-to" placeholder="Select date">
                                </div>
                                <div class="col-lg-4">
                                    <span>Amount Loan</span>
                                    <input type="text" class="float-only form-control amount-loan" placeholder="Enter amount">
                                </div>
                                <div class="col-lg-4">
                                    <span>Deduction</span>
                                    <input type="text" class="float-only form-control deduction" placeholder="Enter deduction">
                                </div>
                                <div class="col-lg-4">
                                    <span>Remaining Balance</span>
                                    <input type="text" class="float-only form-control remaining-balance" placeholder="Enter remaining balance">
                                </div>
                            </div>
                        </div>
                        <div class="loading-pagibig">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div>
                        <br/>
                        <div class="pagibig-warning">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary update-pagibig-btn">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>


<script src="<?php echo base_url();?>assets/js/loans/pagibig_loan.js"></script>