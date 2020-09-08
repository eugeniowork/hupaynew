
<div class="div-main-body" >
    <div class="div-main-body-head">
        Employee List
    </div>
	<div class="div-main-body-content ">
        <p><i class="fas fa-info-circle"></i>&nbsp;Note: Updating biometrics ID is for employee who has totally registered biometrics while Creating biometrics id is for employee who has not.</p>
		<table class="table table-striped table-bordered" id="biometrics">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Bio ID</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Department</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Position</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
        <div class="modal fade" id="updateBio" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLongTitle">Update/ Biometrics Registration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span><i class="fas fa-users"></i>&nbsp;Biometrics ID</span>
                    <input type="text" class="form-control update-bio-id number-only" placeholder="Enter Biometrics ID"><br/>
                    <div class="update-bio-warning">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary update-bio-btn">Update</button>
                </div>
                </div>
            </div>
        </div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/biometrics/biometrics.js"></script>