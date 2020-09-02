<?php $employeeInformation = employeeInformation();?>

<?php if ($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3 || $employeeInformation['role_id'] == 2): ?>
	<div class="div-main-body salary" >
        <div class="div-main-body-head">
            List of Employee With Existing Salary Loan / Employee Benifit Program
        </div>
        <div class="div-main-body-content">
        	<div class="col-sm-12" style="font-weight:bold;border-radius:10px;background-color: #e5e8e8;margin-bottom:10px;padding:10px;text-align:center;">
	            <small>
	                <!--<span style='color:#186a3b;'>Icon Legends: </span> -->
	                <span class='glyphicon glyphicon-pencil' style='color:#b7950b ;margin-left:5px;'></span> - Edit Salary Loan Info
	                <span class='glyphicon glyphicon-eye-open' style='color:#2980b9 ;margin-left:5px;'></span> - Adjust Salary Loan Info
	                <span class='glyphicon glyphicon-plus-sign' style='color: #717d7e  ;margin-left:5px;'></span> - Delete Salary Loan Info
	                <span class='glyphicon glyphicon-adjust' style='color:#229954;margin-left:5px;'></span> - View Salary Loan Info
	                <span class='glyphicon glyphicon-adjust' style='color:#229954;margin-left:5px;'></span> - Print Salary Loan Info 
	            </small>

	        </div>
	        <table class="table table-striped" id="employeeWithExistingSalaryLoan">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-clock"></i>&nbsp;Range Payment</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Amount Loan</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Deduction</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Outstanding Balance</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Deduction Type</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Info</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
            <div class="modal fade" id="editSalaryLoanModal" tabindex="-1" role="dialog" aria-labelledby="editSalaryLoanModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSalaryLoanModalLongTitle">Update Salary Loan Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="salary-loan-info">
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
                                    <div class="col-lg-7">
                                        <span>Remarks</span>
                                        <textarea type="text" class="form-control remarks"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="loading-salary-loan">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p>Loading Information</p>
                                </div>
                            </div>
                            <br/>
                            <div class="salary-loan-warning">
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary update-salary-loan-btn">Update</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="adjustSssModal" tabindex="-1" role="dialog" aria-labelledby="adjustSssModalTitle" aria-hidden="true">
	            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <h5 class="modal-title" id="adjustSssModalLongTitle">Adjust Salary Loan</h5>
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                        <span aria-hidden="true">&times;</span>
	                        </button>
	                    </div>
	                    <div class="modal-body">
	                        <div class="adjust-salary-loan-info">
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
	                        <div class="adjust-loading-salary-loan">
	                            <div class="d-flex flex-column justify-content-center align-items-center">
	                                <div class="spinner-border text-primary" role="status"></div>
	                                <p>Loading Information</p>
	                            </div>
	                        </div>
	                        <br/>
	                        <div class="adjust-salary-loan-warning">
	                            
	                        </div>
	                    </div>
	                    <div class="modal-footer">
	                        <button class="btn btn-sm btn-primary adjust-salary-loan-btn">Adjust</button>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>
	</div>
<?php endif ?>

<script src="<?php echo base_url();?>assets/js/loans/salary_loan.js"></script>