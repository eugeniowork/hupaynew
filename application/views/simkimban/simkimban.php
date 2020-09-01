<?php $employeeInformation = employeeInformation();?>
<?php if($employeeInformation['role_id'] == 3 || $employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 2):?>
    <div class="div-main-body simkimban">
        <div class="div-main-body-head">
            List of Employee With Existing SIMKIMBAN
        </div>
        <div class="div-main-body-content">
        	<table class="table table-striped" id="employeeWithExistingSimkimban">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-clock"></i>&nbsp;Range of Payment</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Item</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Loan Amount</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Deduction</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Outstanding Balance</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
            <div class="modal fade" id="editSimkimbanModal" tabindex="-1" role="dialog" aria-labelledby="editSimkimbanModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSimkimbanModalLongTitle">Update SIMKIMBAN Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="simkimban-info">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span>Employee Name</span>
                                        <input readonly type="text" class="form-control employee-name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <span>Deduction Type</span>
                                        <select class="deduction-type form-control">
                                            <option selected disabled>Select Type</option>
                                            <option value="Semi-monthly">Semi-monthly</option>
                                            <option value="Monthly">Monthly</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <span>Deduction Day (If monthly):</span><br/>
                                       
                                        <input type="checkbox" id="fifteen">
                                        <label for="fifteen">15</label>
                                        <input type="checkbox" id="thirty">
                                        <label for="thirty">30</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <span>Total Months</span>
                                        <select class="form-control total-months">
                                            <option selected disabled>Select months</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <span>Date From</span>
                                        <input type="text" class="datepicker form-control date-from" placeholder="Select Date">
                                    </div>
                                    <div class="col-lg-4">
                                        <span>Date To</span>
                                        <input type="text" class="datepicker form-control date-to" placeholder="Select Date">
                                    </div>
                                    <div class="col-lg-4">
                                        <span>Items</span>
                                        <input type="text" class="form-control item" placeholder="Enter item">
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
                            <div class="loading-simkimban">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p>Loading Information</p>
                                </div>
                            </div>
                            <br/>
                            <div class="simkimban-warning">
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary update-simkimban-btn">Update</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="adjustSimkimbanModal" tabindex="-1" role="dialog" aria-labelledby="adjustSimkimbanModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="adjustSimkimbanModalLongTitle">Adjust Simkimban</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="adjust-simkimban-info">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span>Employee Name</span>
                                        <input type="text" class="input-only form-control adjust-employee-name">
                                    </div>
                                    <div class="col-lg-6">
                                        <span>Outstanding Balance</span>
                                        <input type="text" class="input-only form-control adjust-outstanding-balance">
                                    </div>
                                    <div class="col-lg-6">
                                        <span>Date Payment</span>
                                        <input type="text" class="form-control adjust-date-payment" placeholder="Enter date">
                                    </div>
                                    <div class="col-lg-6">
                                        <span>Cash Payment</span>
                                        <input type="text" class="float-only form-control adjust-cash-payment" placeholder="Enter payment">
                                    </div>
                                    <div class="col-lg-6">
                                        <span>New Outstanding Balance</span>
                                        <input type="text" class="input-only form-control adjust-new-outstanding-balance">
                                    </div>
                                    <div class="col-lg-6">
                                        <span>Remarks</span>
                                        <textarea type="text" class="form-control adjust-remarks" placeholder="Enter remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="adjust-loading-simkimban">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p>Loading Information</p>
                                </div>
                            </div>
                            <br/>
                            <div class="adjust-simkimban-warning">
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary adjust-simkimban-btn">Adjust</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="simkimbanHistoryModal" tabindex="-1" role="dialog" aria-labelledby="simkimbanHistoryModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="simkimbanHistoryModalLongTitle">Simkimban Loan History</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="simkimban-history-info">
                                <table class="table table-striped" id="simkimbanLoanHistory">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-calendar-alt"></i>&nbsp;Payroll Date</th>
                                            <th><i class="fas fa-clock"></i>&nbsp;Deduction</th>
                                            <th><i class="fas fa-wrench"></i>&nbsp;Outstanding Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>
                            <div class="loading-simkimban-history">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p>Loading Information</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="div-main-body simkimban">
        <div class="div-main-body-head">
            Employee SIMKIMBAN List History
        </div>
        <div class="div-main-body-content">
            <table class="table table-striped" id="employeeSimkimbanListHistory">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-clock"></i>&nbsp;Range of Payment</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Item</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Loan Amount</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Deduction</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
    </div>
<?php endif;?>

<div class="div-main-body simkimban">
    <div class="div-main-body-head">
        SIMKIMBAN List History
    </div>
    <div class="div-main-body-content">
        <table class="table table-striped" id="simkimbanListHistory">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Range of Payment</th>
                    <th><i class="fas fa-clock"></i>&nbsp;Items</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Amount Loan</th>
                    <th><i class="fas fa-wrench"></i>&nbsp; Deduction</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Outstanding Balance</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Status</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/simkimban/simkimban.js"></script>
<script src="<?php echo base_url();?>assets/js/simkimban/simkimban_history_list.js"></script>