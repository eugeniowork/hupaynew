<div class="div-main-body add-attendance" >
    <div class="div-main-body-head">
        Employee List with no actual Registered BIOMETRICS ID
    </div>
	<div class="div-main-body-content ">
        <span><strong>Note: </strong>To view the <strong>list of attendance</strong>, please click <a href="<?php echo base_url();?>attendance_list">here</a></span>
        <br/><br/>
		<table class="table table-striped" id="addAttendance">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
        <div class="modal fade" id="generateCutOffAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="generateCutOffAttendanceModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="generateCutOffAttendanceModalLongTitle">Add Attendance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="generate-attendance-info">
                            <span><strong>Employee Name: </strong><span class='add-attendance-employee-name-value'></span></span>
                            
                            <form class="add-attendance-form" method="POST">
                                <div class="generated-attendance-div">
                                
                                </div>
                            </form>
                        </div>
                        <div class="loading-generate-attendance">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary submit-generate-attendance-btn">Submit</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/attendance/add_attendance.js"></script>