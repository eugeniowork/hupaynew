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
                            <td><?php echo $value['working_days']?></td>
                            <?php if($value['action'] == "no"):?>
                                <td>No Actions</td>
                            <?php else:?>
                                <td>
                                    <button class="btn btn-sm btn-outline-success"><i class="fas fa-pencil-alt"></i>&nbsp;Edit</button>
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i>&nbsp;Delete</button>
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
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/working_schedule/working_schedule.js"></script>