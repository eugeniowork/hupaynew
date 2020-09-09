<?php $employeeInformation = employeeInformation();?>
<div class="div-main-body" >
    <div class="div-main-body-head">
        Department List
    </div>
	<div class="div-main-body-content ">
        <?php if ($employeeInformation['role_id'] == 1): ?>
            <button class="btn btn-outline-success pull-right add-department" data-toggle="modal" data-target="#addDepartmentModal">Add New</button><br/><br/>
        <?php endif ?>
        <p><i class="fas fa-info-circle"></i>&nbsp;Note: If the department is already in used for position, it can not be edited and deleted.</p>
		<table class="table table-striped table-bordered" id="departmentList">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Department</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
        <div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLongTitle">Add New Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span><i class="fas fa-users"></i>&nbsp;Department Name</span>
                    <input type="text" class="form-control add-department-name" placeholder="Enter Department"><br/>
                    <div class="add-department-warning">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary add-department-btn">Submit</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updateDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLongTitle">Update Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span><i class="fas fa-users"></i>&nbsp;Department Name</span>
                    <input type="text" class="form-control update-department-name" placeholder="Enter Department"><br/>
                    <div class="update-department-warning">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary update-department-btn">Update</button>
                </div>
                </div>
            </div>
        </div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/department/department_list.js"></script>