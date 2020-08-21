<div class="div-main-body view-attendance">

    <div class="div-main-body-head">
        Attendance List
    </div>
    <div class="div-main-body-content">
        <p class="search-by">Search by:</p>
        
        <input type="radio" id="optionSearchAll" class="optionSearch" value="All">
        <label for="optionSearchAll">All</label>
        &nbsp;&nbsp;
        <input type="radio" id="optionSearchCurrentCutOff" class="optionSearch" value="Current Cut off">
        <label for="optionSearchCurrentCutOff">Current Cut Off</label>
        &nbsp;&nbsp;
        <input type="radio" id="optionSearchSpecificDate" class="optionSearch" value="Specific Date">
        <label for="optionSearchSpecificDate">Specific Date</label>
        <br/>
        <div class="d-flex flex-row ">
            <div class="p-2">
                <input type="text" class="datepicker date-from" placeholder="Input Date">
            </div>
            <div class="p-2">
                <i class="fas fa-long-arrow-alt-right"></i>
            </div>
            <div class="p-2">
                <input type="text" class="datepicker date-to" placeholder="Input Date">
            </div>
            <div class="p-2">
                <button class="btn btn-primary btn-sm searchBtn">Search</button>
            </div>
            
        </div>
        
        
        
        <br/>
        
        <div class="attendance">
            <div class="attendance-loading">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p>Loading Attendance</p>
                </div>
            </div>
            <div class="attendance-body">
                <div class="attendance-head">
                    <span>Search Result: <span class="searchByValue"></span></span>
                </div>
                <div class="attendance-content">
                    <table class="table table-striped" id="attendanceTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar-alt"></i>&nbsp;Date</th>
                                <th><i class="fas fa-clock"></i>&nbsp;Time In</th>
                                <th><i class="fas fa-clock"></i>&nbsp;Time Out</th>
                                <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal fade" id="editAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="editAttendanceModalTitle" aria-hidden="true">
                <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAttendanceModalLongTitle">Update Attendance Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <span><i class="far fa-calendar-alt"></i>&nbsp;Date:</span>&nbsp;<span class="dateValue"></span>
                        </div>
                        <div class="row time-in">
                            <div class="col-sm-12">
                                <label class="control-label"><i class="fas fa-clock"></i>&nbsp;Time In:&nbsp;<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
                                <input type="text" value="23" class="form-control hour_time_in number-only" placeholder="H">
                            </div>
                            <div class="col-sm-1" style="margin-top:10px;">
                                :
                            </div>
                            <div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
                                <input type="text" value="23" class="form-control min_time_in number-only" placeholder="M">
                            </div>
                            <div class="col-sm-5" style="">
                                <select class="form-control period_time_in" required="required">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                                <!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

                            </div>
                        </div>
                        <div class="row time-out">
                            <div class="col-sm-12">
                                <label class="control-label"><i class="fas fa-clock"></i>&nbsp;Time Out:&nbsp;<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
                                <input type="text" value="23" class="form-control hour_time_out number-only" placeholder="H" >
                            </div>
                            <div class="col-sm-1" style="margin-top:10px;">
                                :
                            </div>
                            <div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
                                <input type="text" value="23" class="form-control min_time_out number-only" placeholder="M" >
                            </div>
                            <div class="col-sm-5" style="">
                                <select class="form-control period_time_out" >
                                    <option value="AM" selected>AM</option>
                                    <option value="PM">PM</option>
                                </select>
                                <!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label class="control-label"><i class="fas fa-comment"></i>&nbsp;Remarks:&nbsp;<span class="text-danger">*</span></label>
                            <textarea class="form-control remarks" placeholder="Input Remarks"></textarea>
                        </div><br/>
                        <div class="update-attendance-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary save-change-btn">Request Update</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/attendance/view_attendance.js"></script>