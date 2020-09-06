<div class="div-main-body leave-maintenance" >
    <div class="div-main-body-head">
        Leave Maintenance
    </div>
	<div class="div-main-body-content leaves">
        <button class="btn btn-outline-success pull-right protip" data-toggle="modal" data-target="#addLeaveMaintenanceModal">Add New</button><br/><br/>
		<table class="table table-striped" id="leaveMaintenance">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Name</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Info</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Status</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
        <div class="modal fade" id="addLeaveMaintenanceModal" tabindex="-1" role="dialog" aria-labelledby="addLeaveMaintenanceModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLeaveMaintenanceModalLongTitle">Add Leave Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <span >Name</span>
                                <input type="text" class="form-control leave-name" placeholder="Enter Name">
                            </div>
                            <div class="col-lg-6">
                                <span >Validation</span>
                                <select class="form-control leave-validation">
                                    <option selected disabled>Select Validation</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <span >No. of Days To Be Filed</span>
                                <input type="text" class="number-only form-control no-of-days-to-file" placeholder="Enter Days" disabled>
                            </div>
                            <div class="col-lg-6">
                                <span >Leave Count</span>
                                <input type="text" class="number-only form-control leave-count" placeholder="Enter Count">
                            </div>
                            <div class="col-lg-6">
                                <br/>
                                <input type="checkbox" class="is-convertable-to-cash" id="isConvertable">
                                <label for="isConvertable">Is Convertable To Cash</label>
                            </div>
                        </div>

                        <br/>
                        <div class="add-leave-maintenance-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary submit-new-leave-maintenance-btn">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editLeaveMaintenanceModal" tabindex="-1" role="dialog" aria-labelledby="editLeaveMaintenanceModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLeaveMaintenanceModalLongTitle">Update Leave Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="update-leave-info">
                            <div class="row">
                                <div class="col-lg-6">
                                    <span >Name</span>
                                    <input type="text" class="form-control update-leave-name" placeholder="Enter Name">
                                </div>
                                <div class="col-lg-6">
                                    <span >Validation</span>
                                    <select class="form-control update-leave-validation">
                                        <option selected disabled>Select Validation</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <span >No. of Days To Be Filed</span>
                                    <input type="text" class="number-only form-control update-no-of-days-to-file" placeholder="Enter Days" disabled>
                                </div>
                                <div class="col-lg-6">
                                    <span >Leave Count</span>
                                    <input type="text" class="number-only form-control update-leave-count" placeholder="Enter Count">
                                </div>
                                <div class="col-lg-6">
                                    <br/>
                                    <input type="checkbox" class="update-is-convertable-to-cash" id="updateIsConvertable">
                                    <label for="isConvertable">Is Convertable To Cash</label>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="loading-update-leave">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div><br/>
                        <div class="update-leave-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary submit-update-leave-btn">Update</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/leaves/leave_maintenance.js"></script>