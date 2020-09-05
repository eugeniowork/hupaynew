<?php $employeeInformation = employeeInformation();?>
<div class="div-main-body" >
    <div class="div-main-body-head">
        List of Memorandum
    </div>
	<div class="div-main-body-content ">
        <button class="btn btn-outline-success pull-right">Add Memorandum</button><br/><br/>
		<table class="table table-striped table-bordered" id="listOfMemorandum">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Subject</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;To</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Date</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Content</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/memorandum/memorandum.js"></script>