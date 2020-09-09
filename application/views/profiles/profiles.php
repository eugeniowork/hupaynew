<?php 
	$this->load->helper('hupay_helper');
	$this->load->helper('date_helper');
	$this->load->helper('dependent_helper');
	$this->load->helper('allowance_helper');
 ?>
<div class="div-main-body profiles" >
    <div class="div-main-body-head">
        My Information
    </div>
    <div class="div-main-body-content ">
    	
		<div class="basic-information">
			<div class="basic-information-head">
				<span>BASIC INFORMATION</span>
			</div>
			<div class="basic-information-body">
				<div class="row">
					<div class="col-lg-4">
						<span class="title">Employee Name</span>
						<span readonly class="form-control value" ><?php echo $empInfo['Lastname'] . ", " . $empInfo['Firstname'] . " " . $empInfo['Middlename']?></span>
					</div>
					<div class="col-lg-8">
						<span class="title">Address</span>
						<span readonly class="form-control value">
							<?php echo $empInfo['Address']?>
						</span>

					</div>
					<div class="col-lg-4">
						<span class="title">Civil Status</span>
						<span readonly class="form-control value">
							<?php echo $empInfo['CivilStatus']?>
						</span>

					</div>
					<div class="col-lg-4">
						<span class="title">Birthdate</span>
						<span readonly class="form-control value">
							<?php echo dateFormat($empInfo['Birthdate'])?>
						</span>
					</div>
					<div class="col-lg-4">
						<span class="title">Gender</span>
						<span readonly class="form-control value">
							<?php echo $empInfo['Gender']?>
						</span>
					</div>
					<div class="col-lg-4">
						<?php 
							$contactNo = "N/A";
							if($empInfo['ContactNo'] !=""){
								$contactNo = $empInfo['ContactNo'];
							}
						?>
						<span class="title">Contact No</span>
						<span readonly class="form-control value">
							<?php echo $contactNo?>
						</span>
					</div>
					<div class="col-lg-4">
						<?php 
							$email = "N/A";
							if($empInfo['EmailAddress'] !=""){
								$email = $empInfo['EmailAddress'];
							}
						?>
						<span class="title">Contact No</span>
						<span readonly class="form-control value">
							<?php echo $email?>
						</span>
					</div>
				</div>
			</div>
		</div>
    	<div class="company-information">
			<div class="company-information-head">
				<span>COMPANY INFORMATION</span>
			</div>
			<div class="company-information-body">
				<div class="row">
					<div class="col-lg-4">
						<span class="title">Department</span>
						<span readonly class="form-control value">
							<?php echo $department?>
						</span>
					</div>
					<div class="col-lg-4">
						<span class="title">Position</span>
						<span readonly class="form-control value">
							<?php echo $position?>
						</span>
					</div>
					<div class="col-lg-4">
						<span class="title">Date Hired</span>
						<span readonly class="form-control value">
							<?php echo dateFormat($empInfo['DateHired'])?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="government-information">
			<div class="government-information-head">
				<span>GOVERNMENT INFORMATION</span>
			</div>
			<div class="government-information-body">
				<div class="row">
					<div class="col-lg-4">
						<?php 
							$sssNo = "N/A";
							if($empInfo['SSS_No'] != ""){
								$sssNo = $empInfo['SSS_No'];
							}

						?>
						<span class="title"><img src="<?php base_url();?>assets/images/img/government images/SSS-Logo.jpg" class="government-logo" alt="SSS-Logo"/> SSS No:</span>
						<span readonly class="form-control value">
							<?php echo sssNoFormat($sssNo)?>
						</span>
					</div>
					<div class="col-lg-4">
						<?php 
							$pagibigNo = "N/A";
							if($empInfo['PagibigNo'] != ""){
								$pagibigNo = pagibigNoFormat($empInfo['PagibigNo']);
							}

						?>
						<span class="title"><img src="<?php base_url();?>assets/images/img/government images/pag-ibig-logo.jpg" class="government-logo" alt="Pag-big-Logo"/> Pag-ibig No</span>
						<span readonly class="form-control value">
							<?php echo $pagibigNo?>
						</span>
					</div>
					<div class="col-lg-4">
						<?php 
							$tinNo = "N/A";
							if($empInfo['TinNo'] != ""){
								$tinNo = tinNoFormat($empInfo['TinNo']);
							}

						?>
						<span class="title"><img src="<?php base_url();?>assets/images/img/government images/bir-Logo.jpg" class="government-logo" alt="BIR-Logo"/> Tin No</span>
						<span readonly class="form-control value">
							<?php echo $tinNo?>
						</span>
					</div>
					<div class="col-lg-4">
						<?php 
							$philhealthNo = "N/A";
							if($empInfo['PhilhealthNo'] != ""){
								$philhealthNo = philhealthNoFormat($empInfo['PhilhealthNo']);
							}

						?>
						<span class="title"><img src="<?php base_url();?>assets/images/img/government images/philhealth-logo.jpg" class="government-logo" alt="Philhealth-Logo"/> Philhealth No</span>
						<span readonly class="form-control value">
							<?php echo $philhealthNo?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="dependent-information">
			<div class="dependent-information-head">
				<span>Dependent INFORMATION</span>
			</div>
			<div class="dependent-information-body">
				<div class="row">
					<?php echo getDependentInfoToProfile() ?>
				</div>
			</div>
		</div>
		<div class="compensation-information">
			<div class="compensation-information-head">
				<span>Compensation INFORMATION</span>
			</div>
			<div class="compensation-information-body">
				<div class="row">
					<?php echo getAllowanceInfoToProfile(); ?>
					<div class="col-lg-4">
						<span class="title">Salary</span>
						<span readonly class="form-control value">
							<?php echo $salary?>
						</span>
					</div>
					<div class="col-lg-4">
						<?php 
							$total_allowance = round(getTotalAllowance(),2);
							$totalMonthly = round($empInfo['Salary'] + $total_allowance,2);
						?>
						<span class="title">Total Monthly Pay</span>
						<span readonly class="form-control value">
							Php. <?php echo moneyConvertion($totalMonthly)?>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>