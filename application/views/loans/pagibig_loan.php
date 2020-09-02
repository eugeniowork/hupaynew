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
        <div class="modal fade" id="adjustPagibigModal" tabindex="-1" role="dialog" aria-labelledby="adjustPagibigModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adjustPagibigModalLongTitle">Adjust Pag-ibig</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="adjust-pagibig-info">
                            <div class="row">
                                <div class="col-lg-12">
                                    <span>Employee Name</span>
                                    <input type="text" class="input-only form-control adjust-employee-name">
                                </div>
                                
                                <div class="col-lg-6">
                                    <span>Date Payment</span>
                                    <input type="text" class="form-control adjust-date-payment" placeholder="Enter date">
                                </div>
                                <div class="col-lg-6">
                                    <span>Outstanding Balance</span>
                                    <input type="text" class="input-only form-control adjust-outstanding-balance">
                                </div>
                                <div class="col-lg-6">
                                    <span>Cash Payment</span>
                                    <input type="text" class="float-only form-control adjust-cash-payment" placeholder="Enter payment">
                                </div>
                                <div class="col-lg-6">
                                    <span>New Outstanding Balance</span>
                                    <input type="text" class="input-only form-control adjust-new-outstanding-balance" placeholder="New outstanding balance">
                                </div>
                                <div class="col-lg-6">
                                    <span>Remarks</span>
                                    <textarea type="text" class="form-control adjust-remarks" placeholder="Enter remarks"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="adjust-loading-pagibig">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div>
                        <br/>
                        <div class="adjust-pagibig-warning">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary adjust-pagibig-btn">Adjust</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<div class="div-main-body pagibig" >
    <div class="div-main-body-head">
        Pag-ibig Loan List History
    </div>
    <div class="div-main-body-content">
        <table class="table table-striped" id="pagibigHistoryList">
                <thead>
                    <tr>
                        <th><i class="fas fa-clock"></i>&nbsp;Range Payment</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Amount Loan</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Deduction</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Outstanding Balance</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Status</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
    </div>
</div>
<?php if ($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3): ?>
    

    <div class="div-main-body pagibig">
        
        <div class="row">
            <div class="col-lg-3">
                
            </div>
            <div class="col-lg-6">
                <div class="d-flex flex-column justify-content-center">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addEmployeeToPagibigModal">Add Employee with Pag-ibig Loan</button>
                </div>
                
            </div>
            <div class="col-lg-3">
                
            </div>
        </div>
        <div class="modal fade" id="addEmployeeToPagibigModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeToPagibigModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeToPagibigModalLongTitle">Add Employee with Pag-ibig Loan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <span>Employee Name</span>
                                <div class="d-flex flex-row">
                                    <input type="text" class="input-only form-control add-employee-name" placeholder="Select name">&nbsp;
                                    <button class="btn btn-outline-primary pull-right" data-toggle="modal" data-target="#selectEmployeeModal">Choose</button>
                                </div>
                                
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <span>Date From</span>
                                <input type="text" class="datepicker form-control add-date-from" placeholder="Select Date">
                            </div>
                            <div class="col-lg-4">
                                <span>Date To</span>
                                <input type="text" class="datepicker form-control add-date-to" placeholder="Select Date">
                            </div>
                            <div class="col-lg-4">
                                <span>Amount Loan</span>
                                <input type="text" class="float-only form-control add-amount-loan" placeholder="Enter amount">
                            </div>
                            <div class="col-lg-4">
                                <span>Deduction</span>
                                <input type="text" class="float-only form-control add-deduction" placeholder="Enter deduction">
                            </div>
                            <div class="col-lg-4">
                                <span>Outstanding Balance</span>
                                <input type="text" class="float-only form-control add-remaining-balance" placeholder="Enter outstanding balance">
                            </div>
                        </div>
                        <br/>
                        <div class="add-pagibig-warning">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary add-pagibig-btn">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="selectEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="aselectEmployeeModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="selectEmployeeModalLongTitle">Employee List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped" id="employeeList">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>
                    </div>
                    <!-- <div class="modal-footer">
                        <button class="btn btn-primary ">Submit</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<br/><br/>
<script src="<?php echo base_url();?>assets/js/loans/pagibig_loan.js"></script>