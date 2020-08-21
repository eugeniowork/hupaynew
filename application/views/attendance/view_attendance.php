<div class="div-main-body view-attendance">
    <div class="div-main-body-head attendance-menu-head">
        attendance menu 
    </div>
    <div class="div-main-body-content attendance-menu-content">
        <button data-toggle="modal" data-target="#addOverTimeModal">File Overtime</button>
        <button data-toggle="modal" data-target="#addAttendanceModal">Add Attendance</button>
        <button data-toggle="modal" data-target="#fileLeaveOptionModal">File Leave</button>
        <button>View Leave Status and History</button>
        <div class="modal fade" id="addOverTimeModal" tabindex="-1" role="dialog" aria-labelledby="addOverTimeModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOverTimeModalLongTitle">File Overtime</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <span><i class="far fa-calendar-alt"></i>&nbsp;Date: <span class="text-danger">*</span></label></span>
                        <input type="text" class="form-control datepicker attendance-date-ot" >
                    </div>
                    <div class="row time-in-ot">
                        <div class="col-sm-12">
                            <label class="control-label"><i class="fas fa-clock"></i>&nbsp;Time In:&nbsp;<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
                            <input type="text" class="form-control hour-time-in-ot number-only" placeholder="H">
                        </div>
                        <div class="col-sm-1" style="margin-top:10px;">
                            :
                        </div>
                        <div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
                            <input type="text" class="form-control min-time-in-ot number-only" placeholder="M">
                        </div>
                        <div class="col-sm-5" style="">
                            <select class="form-control period-time-in-ot" required="required">
                                <option selected disabled>Select </option>
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                            <!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

                        </div>
                    </div>
                    <div class="row time-out-ot">
                        <div class="col-sm-12">
                            <label class="control-label"><i class="fas fa-clock"></i>&nbsp;Time Out:&nbsp;<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
                            <input type="text" class="form-control hour-time-out-ot number-only" placeholder="H" >
                        </div>
                        <div class="col-sm-1" style="margin-top:10px;">
                            :
                        </div>
                        <div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
                            <input type="text" class="form-control min-time-out-ot number-only" placeholder="M" >
                        </div>
                        <div class="col-sm-5" style="">
                            <select class="form-control period-time-out-ot" >
                                <option selected disabled>Select</option>
                                <option value="AM" >AM</option>
                                <option value="PM">PM</option>
                            </select>
                            <!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label"><i class="fas fa-comment"></i>&nbsp;Remarks:&nbsp;<span class="text-danger">*</span></label>
                            <textarea class="form-control remarks-ot" placeholder="Input Remarks"></textarea>
                        </div>
                    </div><br/>
                    <div class="add-ot-warning">

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary submit-ot-btn">Submit</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="addAttendanceModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAttendanceModalLongTitle">Add Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <span><i class="far fa-calendar-alt"></i>&nbsp;Date: <span class="text-danger">*</span></label></span>
                        <input type="text" class="form-control datepicker add-attendance-date" >
                    </div>
                    <div class="row time-in-attendance">
                        <div class="col-sm-12">
                            <label class="control-label"><i class="fas fa-clock"></i>&nbsp;Time In:&nbsp;<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
                            <input type="text" class="form-control hour-time-in-attendance number-only" placeholder="H">
                        </div>
                        <div class="col-sm-1" style="margin-top:10px;">
                            :
                        </div>
                        <div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
                            <input type="text" class="form-control min-time-in-attendance number-only" placeholder="M">
                        </div>
                        <div class="col-sm-5" style="">
                            <select class="form-control period-time-in-attendance" required="required">
                                <option selected disabled>Select</option>
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                            <!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

                        </div>
                    </div>
                    <div class="row time-out-attendance">
                        <div class="col-sm-12">
                            <label class="control-label"><i class="fas fa-clock"></i>&nbsp;Time Out:&nbsp;<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
                            <input type="text" class="form-control hour-time-out-attendance number-only" placeholder="H" >
                        </div>
                        <div class="col-sm-1" style="margin-top:10px;">
                            :
                        </div>
                        <div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
                            <input type="text" class="form-control min-time-out-attendance number-only" placeholder="M" >
                        </div>
                        <div class="col-sm-5" style="">
                            <select class="form-control period-time-out-attendance" >
                                <option selected disabled>Select</option>
                                <option value="AM" >AM</option>
                                <option value="PM">PM</option>
                            </select>
                            <!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label"><i class="fas fa-comment"></i>&nbsp;Remarks:&nbsp;<span class="text-danger">*</span></label>
                            <textarea class="form-control remarks-attendance" placeholder="Input Remarks"></textarea>
                        </div>
                    </div>
                    <br/>
                    <div class="add-attendance-warning">

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary submit-attendance-btn">Submit</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="fileLeaveOptionModal" tabindex="-1" role="dialog" aria-labelledby="fileLeaveOptionModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileLeaveOptionModalLongTitle">File Leave Option</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button class="open-file-leave-btn" data-toggle="modal" data-target="#fileLeaveModal">File Leave</button>
                </div>
                <!-- <div class="modal-footer">
                    <button class="btn btn-sm btn-primary submit-attendance-btn">Submit</button>
                </div> -->
                </div>
            </div>
        </div>
        <div class="modal fade" id="fileLeaveModal" tabindex="-1" role="dialog" aria-labelledby="fileLeaveModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileLeaveModalLongTitle">File Leave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <span><i class="far fa-calendar-alt"></i>&nbsp;Type of Leave: <span class="text-danger">*</span></label></span>
                        <select class="form-control leave-type">

                        </select>
                    </div>
                    <br/>
                    <div>
                        <span><i class="far fa-calendar-alt"></i>&nbsp;Date From: <span class="text-danger">*</span></label></span>
                        <input type="text" class="form-control datepicker date-from-leave" placeholder="Input Date">
                    </div>
                    <br/>
                    <div>
                        <span><i class="far fa-calendar-alt"></i>&nbsp;Date To: <span class="text-danger">*</span></label></span>
                        <input type="text" class="form-control datepicker date-to-leave" placeholder="Input Date">
                    </div><br/>
                    <div >
                        <label class="control-label"><i class="fas fa-comment"></i>&nbsp;Remarks:&nbsp;<span class="text-danger">*</span></label>
                        <textarea class="form-control remarks-leave" placeholder="Input Remarks"></textarea>
                    </div>
                    <br/>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary submit-leave-btn">Submit</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
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
                <input type="text" class="datepicker date-from" placeholder="Input Date From">
            </div>
            <div class="p-2">
                <i class="fas fa-long-arrow-alt-right"></i>
            </div>
            <div class="p-2">
                <input type="text" class="datepicker date-to" placeholder="Input Date To">
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
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label"><i class="fas fa-comment"></i>&nbsp;Remarks:&nbsp;<span class="text-danger">*</span></label>
                                <textarea class="form-control remarks" placeholder="Input Remarks"></textarea>
                            </div>
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