<?php $employeeInformation = employeeInformation();?>
<div class="div-main-body" >
    <div class="div-main-body-head">
        SSS Contribution Table
    </div>
	<div class="div-main-body-content ">
        <?php if ($employeeInformation['role_id'] == 1): ?>
            <button class="btn btn-outline-success pull-right">Add New</button><br/><br/>
        <?php endif ?>
        
		<table class="table table-striped table-bordered" id="sssContributionList">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Range of Compensation</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Contribution</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/gov_table/sss_contribution.js"></script>