<?php $this->load->helper('hupay_helper')?>
<div class="div-main-body loans-balance">

    <div class="div-main-body-head">
        Loans Remaining Balance Information
    </div>
    <div class="div-main-body-content">
        <div class="row">
            <div class="col-lg-4">
                <strong>Pag-ibig Loan: </strong>
                <?php if($pagibig):?>
                    <?php $convertedRemainingBalancePagibig = moneyConvertion($pagibig['remainingBalance'])?>
                    <p>Php. <?php echo $convertedRemainingBalancePagibig?></p>
                <?php else:?>
                    <p>Php. 0.00</p>
                <?php endif;?>
            </div>
            <div class="col-lg-4">
                <strong>SSS Loan: </strong>
                <?php if($sssInfo):?>
                    <?php
                        $convertedRemainingBalanceSss = 0;
                        foreach($sssInfo as $value){
                            $convertedRemainingBalanceSss += $value->remainingBalance;
                            
                        }
                    ?>
                    <?php $convertedRemainingBalanceSss = moneyConvertion($convertedRemainingBalanceSss)?>
                    <p>
                        Php. <?php echo $convertedRemainingBalanceSss?>
                        <?php if($sss > 1):?>
                            &nbsp;<button class="show-sss-info-balance-btn"><i class="fas fa-eye"></i></button>
                        <?php endif;?>
                    </p>
                    
                <?php else:?>
                    <p>Php. 0.00</p>
                <?php endif;?>
                <div class="div-sss-info-balance" >
                    <div class="div-sss-info-balance-head">
                        Loan Information Balance
                    </div>
                   <div class="div-sss-info-balance-content">
                    <table class="table table-sm table-bordered table-striped" border="1" cellpadding="15">
                            
                            <thead>
                                <tr>
                                    <th style="padding: 5px;">Loan Type</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($sssInfo as $value):?>
                                    <tr>
                                        <td>
                                            <?php echo $value->loan_type?>
                                        </td>
                                        <td>
                                            <?php echo moneyConvertion($value->remainingBalance)?>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                   </div>
                </div>
            </div>
            <div class="col-lg-4">
                <strong>Cash Advance: </strong>
                <?php if($simkimban != 0 || $salary !=0):?>
                    <?php 
                        $simkimbanRemainingBalance = 0;
                        if($simkimban !=0){
                            foreach($simkimbanInfo as $value){
                                $simkimbanRemainingBalance += $value->remainingBalance;
                            }
                        }
                        $salaryRemainingBalance = 0;
                        if($salary !=0){
                            foreach($salaryInfo as $value){
                                $salaryRemainingBalance += $value->remainingBalance;
                            }
                        }
                    ?>
                    <p>Php. <?php echo moneyConvertion($simkimbanRemainingBalance + $salaryRemainingBalance)?></p>
                <?php else: ?>
                    <p>Php. 0.00</p>
                <?php endif;?>
            </div>
            <div class="col-lg-4">
                <strong>Cashbond: </strong>
                <?php if($cashbond):?>
                    <p>Php. <?php echo moneyConvertion($cashbond['totalCashbond'])?></p>
                <?php else:?>
                    <p>Php. 0.00</p>
                <?php endif;?>
            </div>
            <div class="col-lg-4">
                <?php 
                    // $dayFrom = $workingDays['day_from'];
                    // $dayTo = $workingDays['day_to'];
                    // $workingDaysCount = $cut_off_attendance_count;
                    // $holidayCutOffCount = $holiday_cut_off_count;
                    // $allowance = $allowanceValue;
                    echo $present;
                ?>
                <strong>Running Balance: </strong>
                
            </div>
        </div>
    </div>
</div>
<div class="eventsList">
    
</div>
<br/><br/>
<script src="<?php echo base_url();?>assets/js/dashboard/dashboard.js"></script>