<?php $employeeInformation = employeeInformation();?>

<div class="div-main-body file-loan" >
    <div class="div-main-body-head">
        Loan List History
    </div>
    <div class="div-main-body-content">
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
        <div class="modal fade" id="updateFileLoanModal" tabindex="-1" role="dialog" aria-labelledby="updateFileLoanModalTitle" aria-hidden="true">
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
                        <button class="btn btn-sm btn-primary update-file-loan-data-btn">Adjust</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/loans/file_loan.js"></script>