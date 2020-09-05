<?php $employeeInformation = employeeInformation();?>
<div class="div-main-body" >
    <div class="div-main-body-head">
        Position List
    </div>
	<div class="div-main-body-content ">
        <?php if ($employeeInformation['role_id'] == 1): ?>
            <button class="btn btn-outline-success pull-right">Add New</button><br/><br/>
        <?php endif ?>
        <p><i class="fas fa-info-circle"></i>&nbsp;Note: If the position is already in used for creating an employee, it can not be edited and deleted.</p>
		<table class="table table-striped table-bordered" id="positionList">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Position</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Department</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/position/position_list.js"></script>