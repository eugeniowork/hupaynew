<?php $employeeInformation = employeeInformation();?>
<div class="div-main-body" >
    <div class="div-main-body-head">
        Department List
    </div>
	<div class="div-main-body-content ">
        <?php if ($employeeInformation['role_id'] == 1): ?>
            <button class="btn btn-outline-success pull-right">Add New</button><br/><br/>
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
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/department/department_list.js"></script>