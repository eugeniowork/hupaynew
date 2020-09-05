<div class="div-main-body registration" >
    <div class="div-main-body-head">
        Employee Registration Form
    </div>
	<div class="div-main-body-content ">
		<br/>
		<div class="add-employee-warning">
			
		</div>
		<br/>
		<form method="POST" class="new-employee-form">
			<div class="basic-information">
				<div class="basic-information-head">
					<span>BASIC INFORMATION</span>
				</div>
				
				<div class="basic-information-body">
					<div class="row">
						<div class="col-lg-4">
							<span>Lastname <span class="text-danger">*</span></span>
							<input type="text" name="lastname" class="text-only form-control" placeholder="Enter Lastname">
						</div>
						<div class="col-lg-4">
							<span>Firstname <span class="text-danger">*</span></span>
							<input type="text" name="firstname" class="text-only form-control" placeholder="Enter Firstname">
						</div>
						<div class="col-lg-4">
							<span>Middle Name</span>
							<input type="text" name="middlename" class="text-only form-control" placeholder="Enter Middle Name">
						</div>
						<div class="col-lg-8">
							<span>Address <span class="text-danger">*</span></span>
							<textarea class="form-control" name="address" placeholder="Enter Address"></textarea>
						</div>
						<div class="col-lg-4">
							<span>Civil Status <span class="text-danger">*</span></span>
							<select class="form-control" name="civilStatus">
								<option selected disabled>Select Status</option>
								<option value="Single">Single</option>
								<option value="Married">Married</option>
							</select>
						</div>
						<div class="col-lg-4" >
							<span>Birthday <span class="text-danger">*</span></span>
							<input type="text" name="birthday" class="date-only datepicker form-control" placeholder="Select Enter Date (dd/mm/yy)">
						</div>
						<div class="col-lg-4" >
							<span>Select Gender <span class="text-danger">*</span></span>
							<select class="form-control" name="gender">
								<option selected disabled>Select Gender</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>
						<div class="col-lg-4">
							<span>Contact No</span>
							<input type="text" name="contactNo" class="number-only form-control" placeholder="Enter Contact No">
						</div>
						<div class="col-lg-4">
							<span>Email Address</span>
							<input type="text" name="email" class="form-control" placeholder="Enter Email">
						</div>
					</div>
				</div>
				
			</div>
			<div class="pet-information">
				<div class="pet-information-head">
					<span>PET INFORMATION</span>
				</div>
				<div class="pet-information-body">
					<button class="btn btn-success pull-right add-pet-btn" type="button">Add</button>
					<div class="row">
						<div class="col-lg-4">
							<span>Pet Type </span>
							<input type="text" name="petType[]" class="text-only form-control" placeholder="Enter Pet Type (Dog/Cat/etc.)">
						</div>
						<div class="col-lg-4">
							<span>Pet Name </span>
							<input type="text" name="petName[]" class="text-only form-control" placeholder="Enter Pet Name">
						</div>
					</div>
					<div class="pet-type-2">
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
							<span>Department <span class="text-danger">*</span></span>
							<select class="form-control department" name="department" >
								
							</select>
						</div>
						<div class="col-lg-4">
							<span>Position <span class="text-danger">*</span></span>
							<select class="form-control position-dropdown" name="position" >
								<option selected disabled>Select Position</option>
							</select>
						</div>
						<div class="col-lg-4">
							<span>Salary <span class="text-danger">*</span></span>
							<input type="text" name="salary" class="number-only form-control" placeholder="Enter Salary">
						</div>
						<div class="col-lg-4">
							<span>Date Hired <span class="text-danger">*</span></span>
							<input type="text" name="dateHired" class="date-only datepicker form-control" placeholder="Select Date">
						</div>
						<div class="col-lg-4">
							<span>Working Hours <span class="text-danger">*</span></span>
							<select class="form-control working-hours" name="workingHours" >
								<option selected disabled>Select Working Hours</option>
							</select>
						</div>
						<div class="col-lg-4">
							<span>Immediate Head's Name</span>
							<input type="text" name="headsName" class="text-only form-control heads-name" placeholder="Enter Name">
						</div>
						<div class="col-lg-4">
							<span>Company <span class="text-danger">*</span></span>
							<select class="form-control company" name="company" >
								<option selected disabled>Select Company</option>
							</select>
						</div>
						<div class="col-lg-4">
							<span>Employment Type <span class="text-danger">*</span></span>
							<select class="form-control employment-type" name="employmentType" >
								<option selected disabled>Select Employment Type</option>
								<option value="OJT/Training">OJT/Training</option>
								<option value="Provisional">Probational</option>
								<option value="Regular">Regular</option>
							</select>
						</div>
						<div class="col-lg-4">
							<span>Working Days <span class="text-danger">*</span></span>
							<select class="form-control working-days" name="workingDays" >
								<option selected disabled>Select Working Days</option>
							</select>
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
							<span><img src="<?php base_url();?>assets/images/img/government images/SSS-Logo.jpg" class="government-logo" alt="SSS-Logo"/> SSS No.</span>
							<input type="text" name="sssNo" class="number-only-sss form-control" placeholder="Enter SSS No">
						</div>
						<div class="col-lg-4">
							<span><img src="<?php base_url();?>assets/images/img/government images/pag-ibig-logo.jpg" class="government-logo" alt="Pag-big-Logo"/> Pag-ibig No.</span>
							<input type="text" name="pagibigNo" class="number-only-pagibig form-control" placeholder="Enter Pag-ibig No">
						</div>
						<div class="col-lg-4">
							<span><img src="<?php base_url();?>assets/images/img/government images/bir-Logo.jpg" class="government-logo" alt="BIR-Logo"/> Tin No.</span>
							<input type="text" name="tinNo" class="number-only-tin form-control" placeholder="Enter TIN No">
						</div>
						<div class="col-lg-4">
							<span><img src="<?php base_url();?>assets/images/img/government images/philhealth-logo.jpg" class="government-logo" alt="Philhealth-Logo"/> Philhealth No.</span>
							<input type="text" name="philhealthNo" class="number-only-philhealth form-control" placeholder="Enter Philhealth No">
						</div>
					</div>
				</div>
			</div>
			<div class="school-information">
				<div class="school-information-head">
					<span>SCHOOL INFORMATION</span>
				</div>
				<div class="school-information-body">
					<div class="row">
						<div class="col-lg-4">
							<span>Highest Educational Attainment <span class="text-danger">*</span></span>
							<select class="form-control education-attainment" name="educationAttainment" >
								<option selected disabled>Select Attainment</option>
								<option value="Secondary">Secondary</option>
								<option value="Tertiary">Tertiary</option>
							</select>
						</div>
					</div>
					<div class="education-div">
						
					</div>
				</div>
			</div>
			<div class="employment-information">
				<div class="employment-information-head">
					<span>EMPLOYMENT INFORMATION</span>
				</div>
				<div class="employment-information-body">
					<button class="btn btn-sm btn-success pull-right add-work-btn" type="button">Add</button><br/><br/>
					<div class="row">
						<div class="col-lg-4">
							<span>Position <span class="text-danger">*</span></span>
							<input type="text" name="work_position[]" class="text-only form-control" placeholder="Enter Position" >
						</div>
						<div class="col-lg-4">
							<span>Company Name <span class="text-danger">*</span></span>
							<input type="text" name="company_name[]" class="text-only form-control" placeholder="Enter Company Name" >
						</div>
						<div class="col-lg-4">
							<span>Job Description</span>
							<textarea class="form-control" name="job_description[]" placeholder="Enter job Description" ></textarea>
						</div>
						<div class="col-lg-2">
							<span> Year From:&nbsp;<span class="text-danger">*</span></span>
							<input type="text" name="work_year_from[]" class="year-only form-control" placeholder="Year from"/>
						</div>
						<div class="col-lg-2">
							<span> Year To:&nbsp;<span class="text-danger">*</span></span>
							<input type="text" name="work_year_to[]" class="year-only form-control" placeholder="Year to"/>
						</div>
					</div>
					<div class="add-work-div">
						
					</div>
				</div>
			</div>
			<div class="user-account-information">
				<div class="user-account-information-head">
					<span>USER ACCOUNT INFORMATION</span>
				</div>
				<div class="user-account-information-body">
					<div class="row">
						<div class="col-lg-4">
							<span>Username <span class="text-danger">*</span></span>
							<input type="text" name="username" class="form-control" placeholder="Enter Username">
						</div>
						<div class="col-lg-4">
							<span>Password <span class="text-danger">*</span></span>
							<input type="password" name="password" class="form-control" placeholder="Enter Password">
						</div>
						<div class="col-lg-4">
							<span>Confirm Password <span class="text-danger">*</span></span>
							<input type="password" name="confirmPassword" class="form-control" placeholder="Confirm Password">
						</div>
						<div class="col-lg-4">
							<span>Role <span class="text-danger">*</span></span>
							<select class="form-control role" name="role" >
								<option selected disabled>Select Role</option>
							</select>
						</div>

					</div>
				</div>
			</div>
		</form>
		<br/>
		<button class="btn btn-primary register-btn">Register</button>
	</div>

</div>
<script src="<?php echo base_url();?>assets/js/employee/employee_registration.js"></script>