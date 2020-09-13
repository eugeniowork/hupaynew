<?php $employeeInformation = employeeInformation();?>
<div class="div-main-body position-list" >
    <div class="div-main-body-head">
        Position List
    </div>
	<div class="div-main-body-content ">
        
        
        <div class="d-flex">
            <div class="p-2">
                <p><i class="fas fa-info-circle"></i>&nbsp;Note: If the position is already in used for creating an employee, it can not be edited and deleted.</p>
            </div>
            <div class="ml-auto p-2">
                <?php if ($employeeInformation['role_id'] == 1): ?>
                    <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addPositionModal">Add New</button><br/><br/>
                <?php endif ?>
            </div>
            
        </div>
		<table class="table table-striped table-bordered" id="positionList">
            <thead class="table-dark">
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Position</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Department</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>

        <div class="modal fade" id="addPositionModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLongTitle">Add Position</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span><i class="fas fa-users"></i>&nbsp;Position Name</span>
                    <input type="text" class="form-control add-position-name" placeholder="Enter Name">
                    <span>Department</span>
                    <select class="form-control add-position-department">
                        <option selected disabled>Select Department</option>
                        <?php 
                            $dept_class = new Department_model; 
                            $department = $dept_class->get_all_department();
                        ?>
                        <?php if (!empty($department)): ?>
                            <?php foreach ($department as $value): ?>
                                <option value="<?php echo $value->dept_id; ?>"><?php echo $value->Department ?></option>
                            <?php endforeach ?>
                        <?php endif ?>

                    </select>
                    <br/>
                    <div class="add-position-warning">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary add-poisition-btn">Submit</button>
                </div>
                </div>
            </div>
        </div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/position/position_list.js"></script>