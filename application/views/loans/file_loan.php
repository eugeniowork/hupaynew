<?php $employeeInformation = employeeInformation();?>

<div class="div-main-body file-loan" >
    <div class="div-main-body-head">
        Loan List History
    </div>
    <div class="div-main-body-content">
    	<button class="btn btn-outline-success pull-right" data-toggle="modal" data-target="#addFileLoanModal">File Loan</button><br/><br/>
    	<table class="table table-striped" id="loanListHistory">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Reference No.</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Amount Loan</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Purpose</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Type</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Status</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
        <div class="modal fade" id="updateFileLoanModal" tabindex="-1" role="dialog" aria-labelledby="addFileLoanModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateFileLoanModalTitle">Upate File Loan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="update-file-loan-info">
                            <div class="row">
                            	<div class="col-lg-4">
                            		<span>Amount</span>
									<input type="text" class="float-only form-control file-loan-amount" placeholder="Enter amount" />
                            	</div>
                            	<div class="col-lg-4">
                            		<span>Type</span>
									<select class="form-control file-loan-type">
										<option disabled selected>Select a Loan Type</option>
										<option value="1">Salary Loan</option>
										<option value="2">SIMKIMBAN</option>
										<option value="3">Employee Benifit Program Loan</option>
									</select>
                            	</div>
                            	<div class="col-lg-4 program-section">
                            		<span>Program</span>
									<select class="form-control file-loan-program">
										<option disabled selected>Select a Program</option>
										<option value="1">Service Rewards</option>
										<option value="2">Tulong Pangkabuhayan Program</option>
										<option value="3">Education Assistance Program</option>
										<option value="4">Housing Renovation Program</option>
										<option value="5">Emergency and Medical Assistance Program</option>
									</select>
                            	</div>
                            	<div class="col-lg-12">
                            		<span>Purpose</span>
									<textarea class="form-control file-loan-purpose" placeholder="Enter purpose"></textarea>
                            	</div>
                            </div>
                        </div>
                        <div class="loading-file-loan">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div>
                        <br/>
                        <div class="file-loan-warning">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary update-file-loan-data-btn">Adjust</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addFileLoanModal" tabindex="-1" role="dialog" aria-labelledby="addFileLoanModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFileLoanModalTitle">Loan Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        	<div class="col-lg-4">
                        		<span>Amount</span>
								<input type="text" class="float-only form-control add-file-loan-amount" placeholder="Enter amount" />
                        	</div>
                        	<div class="col-lg-4">
                        		<span>Type</span>
								<select class="form-control add-file-loan-type">
									<option disabled selected>Select a Loan Type</option>
									<option value="1">Salary Loan</option>
									<option value="2">SIMKIMBAN</option>
									<option value="3">Employee Benifit Program Loan</option>
								</select>
                        	</div>
                        	<div class="col-lg-4 add-program-section">
                        		<span>Program</span>
								<select class="form-control add-file-loan-program">
									<option disabled selected>Select a Program</option>
									<option value="1">Service Rewards</option>
									<option value="2">Tulong Pangkabuhayan Program</option>
									<option value="3">Education Assistance Program</option>
									<option value="4">Housing Renovation Program</option>
									<option value="5">Emergency and Medical Assistance Program</option>
								</select>
                        	</div>
                        	<div class="col-lg-12">
                        		<span>Purpose</span>
								<textarea class="form-control add-file-loan-purpose" placeholder="Enter purpose"></textarea>
                        	</div>
                        </div>
                        <br/>
                        <div class="add-file-loan-warning">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary add-file-loan-data-btn">Submit</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<?php if ($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 2): ?>
	<div class="div-main-body file-loan" >
		<div class="div-main-body-head">
	        File Loan List History
	    </div>
		<div class="div-main-body-content">
			<table class="table table-striped" id="fileLoanListHistory">
	            <thead>
	                <tr>
	                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Reference No.</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Amount</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Purpose</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Type</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
	                </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
		</div>
		<div class="modal fade" id="scheduleFileLoanModal" tabindex="-1" role="dialog" aria-labelledby="scheduleFileLoanModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scheduleFileLoanModalTitle">Loan Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="schedule-file-loan-info">
                            <center><p class="loan-text">FILE <span class="loan-type-text"></span> FORM</p></center>
                            
                            <div class="for-simkimban">
                                <div class="row ">
                                    <div class="col-lg-6">
                                        <span>Employee Name</span>
                                        <input type="text" readonly class="form-control employee-name-simkimban">
                                    </div>
                                    <div class="col-lg-4">
                                        <span>Items</span>
                                        <input type="text" class="form-control item-simkimban" placeholder="Enter item">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <span>Deduction Type</span>
                                    <select class="schedule-deduction-type form-control">
                                        <option selected disabled>Select Type</option>
                                        <option value="Semi-monthly">Semi-monthly</option>
                                        <option value="Monthly">Monthly</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <span>Deduction Day (If monthly):</span><br/>
                                   
                                    <input type="checkbox" id="scheduleFifteen">
                                    <label for="scheduleFifteen">15</label>
                                    <input type="checkbox" id="scheduleThirty">
                                    <label for="scheduleThirty">30</label>
                                </div>
                                <div class="col-lg-4">
                                    <span>Date From</span>
                                    <div class="d-flex flex-column justify-content-center">
                                        <select class="form-control schedule-date-from-month">
                                            <option disabled>Select option</option>
                                            <?php for($value = 1; $value < 25; $value++): ?>
                                                <option value="<?php echo $value?>"><?php echo $value?></option>
                                            <?php endfor;?>
                                        </select>
                                        <select class="form-control schedule-date-from-day">
                                            <option disabled>Select option</option>
                                            <option value="15">15</option>
                                            <option value="30">30</option>
                                        </select>
                                        <select class="form-control schedule-date-from-year">
                                            <option selected disabled>Select option</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <span>Total Months</span>
                                    <select class="form-control schedule-total-months">
                                        <option disabled>Select months</option>
                                        <?php for($value = 0; $value < 25; $value++): ?>
                                            <option value="<?php echo $value?>"><?php echo $value?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <span>Date To</span>
                                    <input type="text" readonly class="input-only form-control schedule-date-to" >
                                </div>
                                <div class="col-lg-4">
                                    <span>Amount Loan</span>
                                    <input type="text" class="float-only form-control schedule-amount-loan" placeholder="Enter amount">
                                </div>
                                <div class="col-lg-4">
                                    <span>Total Payment</span>
                                    <input type="text" readonly class="input-only form-control schedule-total-payment" >
                                </div>
                                <div class="col-lg-4">
                                    <span>Deduction</span>
                                    <input type="text" readonly class="input-only form-control schedule-deduction" >
                                </div>
                                <div class="col-lg-6 for-salary-employment">
                                    <span>Remarks</span>
                                    <textarea type="text" class="form-control schedule-remarks" placeholder="Enter remarks"></textarea>
                                </div>
                                
                            </div>
                        </div>
                        <div class="loading-schedule-file-loan">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div>
                        <br/>
                        <div class="schedule-file-loan-warning">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary schedule-file-loan-data-btn">Submit</button>
                        <button class="btn btn-primary schedule-file-loan-simkimban-data-btn">Submit Simkimban</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
<?php endif ?>

<?php if ($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3): ?>
    <div class="div-main-body file-loan" >
        <div class="div-main-body-head">
            List of Filed Salary Loan & Employment Benifit Program
        </div>
        <div class="div-main-body-content">
            <table class="table table-striped" id="fileLoanSalaryAndEmployment">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Range of Payment</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Amount Loan</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Deduction</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Info</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Remarks</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
    </div>

    <div class="div-main-body file-loan" >
        <div class="div-main-body-head">
            List of Filed SIMKIMBAN LOAN
        </div>
        <div class="div-main-body-content">
            <table class="table table-striped" id="fileLoanSimkimban">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Range of Payment</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Item</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Amount Loan</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Deduction</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Info</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
    </div>
<?php endif ?>
<script src="<?php echo base_url();?>assets/js/loans/file_loan.js"></script>