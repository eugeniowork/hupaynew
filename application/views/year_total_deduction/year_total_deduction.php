<div class="div-main-body year-total-deduction" >
    <div class="div-main-body-head">
        List of Employee With Year Total Deduction For the Year of <?php echo date("Y"); ?>
    </div>
    <div class="div-main-body-content">
    	<table class="table table-striped" id="yearTotalDeduction">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                    <th><i class="fas fa-clock"></i>&nbsp;YTD Gross</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;YTD Allowance</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;YTD w/ Tax</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
        <div class="modal fade" id="updateYearTotalDeductionModal" tabindex="-1" role="dialog" aria-labelledby="updateYearTotalDeductionModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateYearTotalDeductionModalLongTitle">Update Employee YTD</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="year-total-deduction-info">
                            <div class="row">
                                <div class="col-lg-12">
                                    <span>Employee Name</span>
                                    <input readonly type="text" class="form-control ytd-employee-name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <span>Year</span>
                                    <input readonly type="text"  class="form-control ytd-year">
                                </div>
                                <div class="col-lg-6">
                                    <span>YTD Gross</span>
                                    <input type="text"  class="float-only form-control ytd-gross" placeholder="Enter YTD Gross">
                                </div>
                                <div class="col-lg-6">
                                    <span>YTD Allowance</span>
                                    <input type="text"  class="float-only form-control ytd-allowance" placeholder="Enter YTD Allowance">
                                </div>
                                <div class="col-lg-6 ytd-tax-info">
                                    <!-- <span>YTD W/Tax</span>
                                    <input type="text"  class="form-control" placeholder="Enter YTD Tax"> -->
                                </div>
                                
                            </div>
                        </div>
                        <div class="loading-total-deduction">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div><br/>
                        <div class="year-total-deduction-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary update-year-total-deduction">Update</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/year_total_deduction/year_total_deduction.js"></script>