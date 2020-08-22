<div class="div-main-body working-schedule" >
    <div class="div-main-body-head">
        <span class="working-schedule-value">Working Hours and Days</span>
    </div>
    <div class="div-main-body-content">
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