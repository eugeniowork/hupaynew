<div class="div-main-body" >
    <div class="div-main-body-head">
        Payroll Information
    </div>
    <div class="div-main-body-content payroll-info">
        <div class="row">
            <div class="col-lg-4">
                <p>Employee Name</p>
                <input type="text " autocomplete="off" id="txt_only" class="emp-name form-control typeahead tt-query">
            </div>
            <div class="col-lg-4">
                <p>Cut Off Period</p>
                <select class="form-control cut-off-period"></select>
            </div>
            <div class="col-lg-4">
                <p>Year</p>
                <input type="text" class="form-control year" maxlength="4">
            </div>
            <button class="btn btn-sm btn-success search-payroll-info-btn">Search</button>
        </div>
        <div class="loading-generating">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <div class="spinner-border text-primary" role="status"></div>
                <p>Generating payroll, please wait.....</p>
            </div>
        </div>
        <div class="generated-payroll-info-body">
            <!-- <button class="btn btn-success btn-sm">Submit Payroll</button>
            <br/><br/>
            <div class="generated-payroll-content">

            </div> -->
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/payroll/payroll_info.js"></script>