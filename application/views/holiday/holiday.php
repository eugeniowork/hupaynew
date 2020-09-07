<div class="div-main-body holiday">
    <div class="div-main-body-head">
        Holiday List
    </div>
	<div class="div-main-body-content ">
        <button class="btn btn-outline-success pull-right" data-toggle="modal" data-target="#addHolidayModal">Add New</button><br/><br/>
		<table class="table table-striped table-bordered" id="holidayList">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Date</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Holiday Name</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Holiday Type</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
        <div class="modal fade" id="updateHolidayModal" tabindex="-1" role="dialog" aria-labelledby="updateHolidayModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateHolidayModalLongTitle">Update Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="holiday-info">
                        <div class="row">
                            <div class="col-lg-7">
                                <span>Month</span>
                                <select class="form-control update-holiday-month">
                                    <option selected disabled>Select Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March" >March</option>
                                    <option value="April" >April</option>
                                    <option value="May" >May</option>
                                    <option value="June" >June</option>
                                    <option value="July" >July</option>
                                    <option value="August" >August</option>
                                    <option value="September" >September</option>
                                    <option value="October" >October</option>
                                    <option value="November">November</option>
                                    <option value="December" >December</option>
                                </select>
                            </div>
                            <div class="col-lg-5">
                                <span>Day</span>
                                <select class="form-control update-holiday-day">
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <span>Holiday Name</span>
                                <input type="text" class="form-control update-holiday-name" >
                            </div>
                            <div class="col-lg-12">
                                <span>Holiday Type</span>
                                <select class="form-control update-holiday-type">
                                    <option disabled >Select Type</option>
                                    <option value="Regular Holiday">Regular Holiday</option>
                                    <option value="Special non-working day">Special non-working day</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="loading-update-holiday">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p>Loading Information</p>
                        </div>
                    </div><br/>
                    <div class="update-holiday-warning">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary update-holiday-btn">Update</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addHolidayModal" tabindex="-1" role="dialog" aria-labelledby="addHolidayModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHolidayModalLongTitle">Add Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                            <div class="col-lg-7">
                                <span>Month</span>
                                <select class="form-control add-holiday-month">
                                    <option selected disabled>Select Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March" >March</option>
                                    <option value="April" >April</option>
                                    <option value="May" >May</option>
                                    <option value="June" >June</option>
                                    <option value="July" >July</option>
                                    <option value="August" >August</option>
                                    <option value="September" >September</option>
                                    <option value="October" >October</option>
                                    <option value="November">November</option>
                                    <option value="December" >December</option>
                                </select>
                            </div>
                            <div class="col-lg-5">
                                <span>Day</span>
                                <select class="form-control add-holiday-day">
                                    <option selected disabled>Select Day</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <span>Holiday Name</span>
                                <input type="text" class="form-control add-holiday-name" placeholder="Enter Holiday Name" >
                            </div>
                            <div class="col-lg-12">
                                <span>Holiday Type</span>
                                <select class="form-control add-holiday-type">
                                    <option disabled selected>Select Type</option>
                                    <option value="Regular Holiday">Regular Holiday</option>
                                    <option value="Special non-working day">Special non-working day</option>
                                </select>
                            </div>
                        
                    </div><br/>
                    <div class="update-holiday-warning">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary add-holiday-btn">Submit</button>
                </div>
                </div>
            </div>
        </div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/holiday/holiday.js"></script>