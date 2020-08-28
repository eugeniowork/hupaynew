<div class="div-main-body">
    <div class="div-main-body-head ">
        Adjustment Reports List
    </div>
    <div class="div-main-body-content ">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="background-color: #85929e;color:#fff;">Cutt Off Period</th>
                    <th style="background-color: #85929e;color:#fff;">Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach($finalAdjustmentReportData as $value):?>
                    <tr>
                        <td><?php echo $value['cut_off_period']?></td>
                        <td>
                            <button id="<?php echo $value['id']?>" class="btn btn-link btn-sm print-adjustment-report-btn">Print</button>
                        </td>
                    </tr>
                <?php endforeach;?>          
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/payroll_reports/adjustment_reports.js"></script>