<div class="div-main-body working-schedule" >
    <div class="div-main-body-head">
        <span class="working-schedule-value">Working Hours and Days</span>
    </div>
    <div class="div-main-body-content">
        <button class="btn btn-success pull-left show-working-hours">Working Hours</button>
        <span class="pull-left">&nbsp;&nbsp;</span>
        <button class="btn btn-success pull-left show-working-days">Working Days</button>
        <br/>
        <br/>
        <hr>
        <div class="working-hours">
            <button class="btn btn-outline-success btn-sm pull-right" data-toggle="modal" data-target="#addWorkingHoursModal">Add Working Hours</button><br/><hr/>
            <p class="title">Working Hours</p><br/>
            <table class="table table-striped" id="workingHoursList">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Working Hours</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $finalWorkingHours; ?>
                </tbody>
            </table>
            <div class="modal fade" id="addWorkingHoursModal" tabindex="-1" role="dialog" aria-labelledby="addWorkingHoursModalTitle" aria-hidden="true">
                <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addWorkingHoursModalLongTitle">Add Working Hours</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span><strong>Note:</strong>Use Military Time</span>
                            <br/>
                            <span>Time In <span class='text-danger'>*</span></span>
                            <div class="row">
                                
                                <div class="col-sm-3">
                                    <input type="text" class="form-control number-only time-in-h" name="hour_time_in" placeholder="H" >
                                </div>
                                <div class="col-sm-1">
                                    :
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control number-only time-in-m" name="min_time_in" placeholder="M" >
                                </div>
                                <div class="col-sm-1">
                                    :
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control input-only time-in-s" name="sec_time_in" placeholder="S" value="00">
                                </div>
                            </div>
                            <br/>
                            <span>Time Out <span class='text-danger'>*</span></span>
                            <div class="row">
                                
                                <div class="col-sm-3">
                                    <input type="text" class="form-control number-only time-out-h" name="hour_time_out" placeholder="H" >
                                </div>
                                <div class="col-sm-1">
                                    :
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control number-only time-out-m" name="min_time_out" placeholder="M" >
                                </div>
                                <div class="col-sm-1">
                                    :
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control input-only time-out-s" name="sec_time_out" placeholder="S" value="00">
                                </div>
                            </div><br/>
                            <div class="add-working-hours-warning">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary add-working-hours-btn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="updateWorkingHoursModal" tabindex="-1" role="dialog" aria-labelledby="updateWorkingHoursModalTitle" aria-hidden="true">
                <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateWorkingHoursModalLongTitle">Update Working Hours</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            
                            <div class="update-working-hours-info">
                                <span><strong>Note:</strong>Use Military Time</span>
                                <br/>
                                <span>Time In <span class='text-danger'>*</span></span>
                                <div class="row">
                                    
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control number-only update-time-in-h" name="hour_time_in" maxlength="2" placeholder="H" >
                                    </div>
                                    <div class="col-sm-1">
                                        :
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control number-only update-time-in-m" name="min_time_in" maxlength="2" placeholder="M" >
                                    </div>
                                    <div class="col-sm-1">
                                        :
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-only update-time-in-s" name="sec_time_in" placeholder="S" value="00">
                                    </div>
                                </div>
                                <br/>
                                <span>Time Out <span class='text-danger'>*</span></span>
                                <div class="row">
                                    
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control number-only update-time-out-h" name="hour_time_out" name="hour_time_out" maxlength="2" placeholder="H" >
                                    </div>
                                    <div class="col-sm-1">
                                        :
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control number-only update-time-out-m" maxlength="2" name="min_time_out" placeholder="M" >
                                    </div>
                                    <div class="col-sm-1">
                                        :
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-only update-time-out-s" placeholder="S" name="sec_time_out" value="00">
                                    </div>
                                </div>
                            </div>
                            <div class="loading-update-working-hours">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p>Loading Information</p>
                                </div>
                            </div><br/>
                            <div class="update-working-hours-warning">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary update-working-hours-btn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="working-days">
            <button class="btn btn-outline-success btn-sm pull-right" data-toggle="modal" data-target="#addWorkingDaysModal">Add Working Days</button><br/><hr/>
            <p class="title">Working Days</p><br/>
            <table class="table table-striped" id="workingDaysList">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Working Days</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($finalWorkingDays as $value):?>
                        <tr class="row<?php echo $value['working_days_id']?>">
                            <td class="working-days-name<?php echo $value['working_days_id']?>"><?php echo $value['working_days']?></td>
                            <?php if($value['action'] == "no"):?>
                                <td>No Actions</td>
                            <?php else:?>
                                <td>
                                    <button data-toggle="modal" data-target="#updateWorkingDaysModal" id="<?php echo $value['working_days_id']?>" class="btn btn-sm btn-outline-success open-update-working-day"><i id="<?php echo $value['working_days_id']?> class="fas fa-pencil-alt"></i>&nbsp;Edit</button>
                                    <button  id="<?php echo $value['working_days_id']?>" class="btn btn-sm btn-outline-danger remove-working-days-btn"><i id="<?php echo $value['working_days_id']?>" class="fas fa-trash"></i>&nbsp;Delete</button>
                                </td>
                            <?php endif;?>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <div class="modal fade" id="addWorkingDaysModal" tabindex="-1" role="dialog" aria-labelledby="addWorkingDaysModalTitle" aria-hidden="true">
                <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addWorkingDaysModalLongTitle">Add Working Days</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <?php
                                $day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
                            ?>
                        <div>
                            <span>Day From <span class="text-danger">*</span</span>
                            <select class="day-from form-control">
                                <option selected disabled>Please select a day</option>
                                <?php for($day = 0; $day<count($day_of_the_week); $day++):?>
                                    <option value="<?php echo $day?>"><?php echo $day_of_the_week[$day]?></option>
                                <?php endfor;?>
                            </select>
                        </div><br/>
                        <div>
                            <span>Day To <span class="text-danger">*</span</span>
                            <select class="day-to form-control">
                                <option selected disabled>Please select a day</option>
                                
                                <?php for($day = 0; $day<count($day_of_the_week); $day++):?>
                                    <option value="<?php echo $day?>"><?php echo $day_of_the_week[$day]?></option>
                                <?php endfor;?>
                            </select>
                        </div><br/>
                        <div class="add-working-days-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary add-working-days-btn">Submit</button>
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="updateWorkingDaysModal" tabindex="-1" role="dialog" aria-labelledby="updateWorkingDaysModalTitle" aria-hidden="true">
                <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateWorkingDaysModalLongTitle">Update Working Days</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="update-working-days-info">
                                <?php
                                    $day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
                                ?>
                                <div>
                                    <span>Day From <span class="text-danger">*</span</span>
                                    <select class="day-from-update form-control">
                                        <option selected disabled>Please select a day</option>
                                        <?php for($day = 0; $day<count($day_of_the_week); $day++):?>
                                            <option value="<?php echo $day?>"><?php echo $day_of_the_week[$day]?></option>
                                        <?php endfor;?>
                                    </select>
                                </div><br/>
                                <div>
                                    <span>Day To <span class="text-danger">*</span</span>
                                    <select class="day-to-update form-control">
                                        <option selected disabled>Please select a day</option>
                                        
                                        <?php for($day = 0; $day<count($day_of_the_week); $day++):?>
                                            <option value="<?php echo $day?>"><?php echo $day_of_the_week[$day]?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                            </div>


                            <div class="loading-update-working-days">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p>Loading Information</p>
                                </div>
                            </div><br/>
                            <div class="update-working-days-warning">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary update-working-days-btn">Submit</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/working_schedule/working_schedule.js"></script>