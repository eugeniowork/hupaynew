<div class="div-main-body working-schedule" >
    <div class="div-main-body-head">
        <span class="working-schedule-value">Working Hours and Days</span>
    </div>
    <div class="div-main-body-content">
        <div class="working-days">
            <button class="btn btn-success btn-sm pull-right">Add Working Days</button><br/><hr/>
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
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/working_schedule/working_schedule.js"></script>