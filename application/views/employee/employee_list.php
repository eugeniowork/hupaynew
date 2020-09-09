<?php $employeeInformation = employeeInformation();?>
<div class="div-main-body employee-list" >
    <div class="div-main-body-head">
        Employee List
    </div>
	<div class="div-main-body-content ">
        <a href="<?php echo base_url();?>employee-list-print" class="btn btn-outline-success pull-right">Print Employee List Reports</a>
        <br/>
        <br/>
        <div class="col-sm-12" style="font-weight:bold;border-radius:10px;background-color: #e5e8e8;margin-bottom:10px;padding:10px;text-align:center;">
            <small>
                <!--<span style='color:#186a3b;'>Icon Legends: </span> -->
                <span class='glyphicon glyphicon-pencil' style='color:#b7950b ;margin-left:5px;'></span> - Edit Employee Info
                <span class='glyphicon glyphicon-eye-open' style='color:#2980b9 ;margin-left:5px;'></span> - Make Employee Active or Inactive  
                <span class='glyphicon glyphicon-plus-sign' style='color: #717d7e  ;margin-left:5px;'></span> - View Employee Info
                <span class='glyphicon glyphicon-adjust' style='color:#229954;margin-left:5px;'></span> - Upload 201 File 
                <span class='glyphicon glyphicon-adjust' style='color:#229954;margin-left:5px;'></span> - Print Employee Info 
                <span class='glyphicon glyphicon-adjust' style='color:#229954;margin-left:5px;'></span> - View LFC Employee Info  
                <span class='glyphicon glyphicon-adjust' style='color:#229954;margin-left:5px;'></span> - ATM Record  
                <span class='glyphicon glyphicon-adjust' style='color:#229954;margin-left:5px;'></span> - Add Increase Info 
            </small>

        </div>
		<table class="table table-striped table-bordered" id="employeeList">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Address</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Position</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Status</th>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/employee/employee_list.js"></script>