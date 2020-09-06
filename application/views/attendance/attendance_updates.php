<?php $this->load->helper('attendance_helper')?>
<?php 
    $attendance_notif_count = attendanceNotifToTableCount();

?>

<div class="div-main-body attendance-updates" >
    <input type="hidden" class="attendance-count" value="<?php echo $attendance_notif_count?>">
    <div class="div-main-body-head">
        Attendance Request List
    </div>
	<div class="div-main-body-content attendance-updates">
		<form class="attendance-updates-form" method="POST">
            <table class="table table-striped" id="attendanceUpdates">
                <thead>
                    <tr>
                        <th></th>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Date</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Original Attendance</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Requested Attendance</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Remarks</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </form>
        <div class="modal fade" id="approveAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="approveAttendanceModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveAttendanceModalLongTitle">Request Update Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please enter your password to approve the update attendance.</p>
                    <input type="password" class="form-control approve-attendance-password" placeholder="Enter your password">
                    <br/>
                    <div class="approve-attendance-warning">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary approve-all-btn">Approve</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="disapproveAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="disapproveAttendanceModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="disapproveAttendanceModalLongTitle">Request Update Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please enter your password to disapprove the update attendance.</p>
                    <input type="password" class="form-control disapprove-attendance-password" placeholder="Enter your password">
                    <br/>
                    <div class="disapprove-attendance-warning">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary disapprove-all-btn">Disapprove</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="singleApproveAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="singleApproveAttendanceModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="singleApproveAttendanceModalLongTitle">Request Update Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please enter your password to approve the update attendance.</p>
                    <input type="password" class="form-control single-approve-attendance-password" placeholder="Enter your password">
                    <br/>
                    <div class="single-approve-attendance-warning">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary single-approve-all-btn">Approve</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="singleDisapproveAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="singleDisapproveAttendanceModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="singleDisapproveAttendanceModalLongTitle">Request Update Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please enter your password to approve the update attendance.</p>
                    <input type="password" class="form-control single-disapprove-attendance-password" placeholder="Enter your password">
                    <br/>
                    <div class="single-disapprove-attendance-warning">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary single-disapprove-all-btn">Disapprove</button>
                </div>
                </div>
            </div>
        </div>

        <br/>
        <button class="open-approve-all-modal btn btn-outline-success pull-left" data-toggle="modal" >Approve</button>
        <span class="pull-left">&nbsp;</span>
        <button class="open-disapprove-all-modal btn btn-outline-danger pull-left">Disapprove</button><br/><br/>
	</div>
</div>
<br/><br/>
<script src="<?php echo base_url();?>assets/js/attendance/attendance_updates.js"></script>