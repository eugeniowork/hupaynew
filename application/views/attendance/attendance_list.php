<div class="div-main-body attendance-list" >
    <div class="div-main-body-head">
        Attendance List
    </div>
    <div class="div-main-body-content ">
        <span>Specific Date:</span>
        <div class="row">
            <div class="col-lg-4">
                <input type="text" class="date-from datepicker form-control" placeholder="Enter Date From">
            </div>
            <div class="col-lg-4">
                <input type="text" class="date-to datepicker form-control" placeholder="Enter Date To">
            </div>
            <div class="col-lg-4">
                <button class="btn btn-primary search-attendance-btn">Search</button>
            </div>
        </div>
        <br/>
        <div class="search-attendance-warning">
            
        </div>
        <br/>
        <div class="loading-search-attendance">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <div class="spinner-border text-primary" role="status"></div>
                <p>Loading Information</p>
            </div>
        </div>
        <br/>
        <div class="search-attendance">
            <table class="table table-striped" id="searchAttendance">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Date</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Time In</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Time Out</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
            <br/>
            <br/>
            <button class="btn btn-outline-success pull-right">Print Absent Reports</button>
            <span class="pull-right">&nbsp;</span>
            <button class="btn btn-outline-success pull-right">Print Late Attendance Reports</button>
            <span class="pull-right">&nbsp;</span>
            <button class="btn btn-outline-success pull-right">Print Attendance Reports</button>
            <br/><br/><br/>
        </div>
    </div>
</div>
<br/><br/>
<script src="<?php echo base_url();?>assets/js/attendance/attendance_list.js"></script>