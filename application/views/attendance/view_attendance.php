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
        <button class="btn btn-primary btn-sm searchBtn">Search</button>
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
                    <span>Search Result</span>
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
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAttendanceModalLongTitle">Update Attendance Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-sm btn-primary">Save changes</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/attendance/view_attendance.js"></script>