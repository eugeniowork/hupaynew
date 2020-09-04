<?php $employeeInformation = employeeInformation();?>

<div class="div-main-body" >
    <div class="div-main-body-head">
        Leave Request List
    </div>
	<div class="div-main-body-content leaves">
		<table class="table table-striped" id="leaveRequestList">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Date File</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Date Range</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Leave Type</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;File Type</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Remarks</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
	</div>
</div>
<?php if ($employeeInformation['role_id'] != 4): ?>
	<div class="div-main-body" >
	    <div class="div-main-body-head">
	        Leave List History
	    </div>
		<div class="div-main-body-content leaves">
			<button class="btn btn-outline-success pull-right">Print Leave History Reports</button>
			<span class="pull-right">&nbsp;</span>
			<button class="btn btn-outline-success pull-right">Print Cut Off Approve Leave List</button>
			<br/><br/>
			<table class="table table-striped" id="leaveListHistory">
	            <thead>
	                <tr>
	                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Date Hired</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Date Range</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Leave Type</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;File Type</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Remarks</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Status</th>
	                </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
		</div>
	</div>
<?php endif ?>
<?php if ($employeeInformation['role_id'] != 4 && $employeeInformation['role_id'] != 2): ?>
	<div class="div-main-body" >
	    <div class="div-main-body-head">
	        Employee Leave List Available
	    </div>
		<div class="div-main-body-content leaves">
			<table class="table table-striped" id="employeeLeaveList">
	            <thead>
	                <tr>
	                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Date Hired</th>
	                    <th><i class="fas fa-wrench"></i>&nbsp;Leave Count Info</th>
	                </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
		</div>
	</div>
<?php endif ?>
<script src="<?php echo base_url();?>assets/js/leaves/leave.js"></script>