<?php $this->load->helper('cut_off_helper')?>
<?php $this->load->helper('payroll_helper')?>

<div class="div-main-body" >
    <div>
        To view <b>List of Attendance</b> click <a href="#" target="_blank">here</a>, To view <b>List of Approve OT</b> of the current cut off click <a href="#" target="_blank">here</a>
    </div>
    <div class="div-main-body-head">
        <strong>
            Generate Payroll -
            <?php 
                $cutOffPeriod = getCutOffPeriodLatest();
                echo $cutOffPeriod;
            ?>
        </strong>
        <?php 
            $payroll = getDatePayroll();
            $alreadyGeneratePayroll = ifPayrollExist($payroll);
        ?>
    </div>
    <div class="div-main-body-content generate-payroll">
        <div class="generate-payroll-notes">
            <div class="d-flex justify-content-center align-items-center ">
                <?php if($alreadyGeneratePayroll !=0): ?>
                    <span><strong>Note:</strong> Already Generated a Payroll.</span>
                <?php elseif(existGeneratePayrollcutOff($cutOffPeriod) == 0):?>
                    <span><strong>Note:</strong> Pending Generate Payroll.</span>
                <?php elseif(existGeneratePayrollcutOff($cutOffPeriod) == 1):?>
                    <button class="btn btn-primary generate-payroll-btn" >Generate Payroll</button>
                <?php endif;?>
            </div>     
        </div>
        
        
        
        <br/>
        <div class="loading-generating">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <div class="spinner-border text-primary" role="status"></div>
                <p>Generating payroll, please wait.....</p>
            </div>
        </div>
        
        <div class="generated-payroll-body">
            <!-- <button class="btn btn-success btn-sm">Submit Payroll</button>
            <br/><br/>
            <div class="generated-payroll-content">

            </div> -->
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/payroll/generate_payroll.js"></script>