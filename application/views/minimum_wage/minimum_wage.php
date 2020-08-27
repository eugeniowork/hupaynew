<?php $this->load->helper('minimum_wage_helper')?>
<div class="div-main-body minimum-wage">
    
    <div class="row">
        <div class="col-lg-5">
            <div class="div-main-body-head">
                Minimum Wage Form
            </div>
            <div class="div-main-body-content">
                <p>Stay updated with the latest minimum wage issued by the government</p>
                <span>Effective Date:</span>
                <input type="text" class="form-control effective-date" placeholder="Enter Effective Date">
                <span>Basic Wage:</span>
                <input type="text" class="form-control float-only basic-wage" placeholder="Enter Basic Wage">
                <span>COLA:</span>
                <input type="text" class="form-control float-only cola" placeholder="Enter COLA"><br/>
                <button class="btn btn-primary btn-sm minimum-wage-btn">Submit</button>
                <br/><br/>
                <div class="add-min-wage-warning">
                    
                </div>
                Your may visit this site in order to view the latest minimum wage from government in different sector, Click <a href="http://www.nwpc.dole.gov.ph/pages/ncr/cmwr.html" target="_blank">here</a>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="div-main-body-head">
                <?php $minWage = getMinWageEffectiveDate();?>
                <strong>CURRENTLY DAILY MINIMUM WAGES RATES</strong>
                
                
            </div>
            <div class="div-main-body-content">
                <?php if(existMinWage() != 0):?>
                    (Effective: <?php echo $minWage?>)
                <?php endif;?>
                <br/><br/>
                <?php if(empty(getLatestMinimumWage())):?>
                    <p>There is no data</p>
                <?php else:?>
                    <table class="table table-bordered table-hover table-responsive table-sm">
                        <thead>
                            <tr>
                            <th style="background-color: #85929e;color:#fff;"><center>Basic Wage</center></th>
                            <th style="background-color: #85929e;color:#fff;"><center>COLA</center></th>
                            <th style="background-color: #85929e;color:#fff;"><center>Minimum Wage Rates</center></th>
                            <th style="background-color: #85929e;color:#fff;"><center>Action</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(getLatestMinimumWage() as $value):?>
                                <tr class="min-wage-tr" style='text-align:center;'>
                                    <td>Php.&nbsp;<?php echo $value['basic_wage']?></td>
                                    <td>Php.&nbsp;<?php echo $value['cola']?></td>
                                    <td>Php.&nbsp;<?php echo $value['min_wage_rates']?></td>
                                    <?php if($value['action'] == 'no'):?>
                                        <td>No Actions</td>
                                    <?php else:?>
                                        <td>
                                            <button id="<?php echo $value['min_wage_id']?>" data-toggle="modal" data-target="#editMinWageModal" class="btn btn-sm btn-outline-success edit-wage-btn"><i class="fas fa-pencil-alt"></i>&nbsp;Edit</button>
                                            <button id="<?php echo $value['min_wage_id']?>" class="btn btn-sm btn-outline-danger remove-min-wage"><i class="fas fa-trash"></i>&nbsp;Remove</button>
                                        </td>
                                    <?php endif;?>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <div class="modal fade" id="editMinWageModal" tabindex="-1" role="dialog" aria-labelledby="editMinWageModalTitle" aria-hidden="true">
                        <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editMinWageModalLongTitle">Update Minimum Wage</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <span>Effective Date:</span>
                                <input type="text" class="form-control update-effective-date" placeholder="Enter Effective Date">
                                <span>Basic Wage:</span>
                                <input type="text" class="form-control float-only update-basic-wage" placeholder="Enter Basic Wage">
                                <span>COLA:</span>
                                <input type="text" class="form-control float-only update-cola" placeholder="Enter COLA">
                                <br/>
                                <div class="update-min-wage-warning">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-sm btn-primary update-min-wage-btn">Submit</button>
                            </div>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
            </div>
            <br/>
            <div class="div-main-body-head">
                <?php $minWage = getMinWageEffectiveDate();?>
                <strong>Minimum Wage History</strong>
            </div>
            <div class="div-main-body-content">
                <table class="table table-bordered table-hover table-responsive table-sm">
                    <thead>
                        <tr>
                        <th style="background-color: #85929e;color:#fff;"><center>Effective Date</center></th>
                        <th style="background-color: #85929e;color:#fff;"><center>Basic Wage</center></th>
                        <th style="background-color: #85929e;color:#fff;"><center>COLA</center></th>
                        <th style="background-color: #85929e;color:#fff;"><center>Minimum Wage Rates</center></th>
                        <th style="background-color: #85929e;color:#fff;"><center>Monthly Rate</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(getMinimumWageHistory() as $value):?>
                            <tr class="min-wage-tr" style='text-align:center;'>
                                <td><?php echo $value['date_format']?></td>
                                <td>Php.&nbsp;<?php echo $value['basic_wage']?></td>
                                <td>Php.&nbsp;<?php echo $value['cola']?></td>
                                <td>Php.&nbsp;<?php echo $value['min_wage_rates']?></td>
                                <td>Php.&nbsp;<?php echo $value['monthly_rate']?></td>
                            </tr>
                        <?php endforeach;?>               
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/minimum_wage/minimum_wage.js"></script>