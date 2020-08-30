<?php $this->load->helper('cashbond_helper')?>
<?php $this->load->helper('hupay_helper')?>
<?php $this->load->helper('salary_helper')?>
<?php $this->load->helper('simkimban_helper')?>
<?php $this->load->helper('date_helper')?>
<?php $employeeInformation = employeeInformation();?>
<div class="div-main-body cashbond" >
    <div class="div-main-body-head">
        List of Cashbond Employee
    </div>
    <div class="div-main-body-content">
        <div class="col-sm-12" style="font-weight:bold;border-radius:10px;background-color: #e5e8e8;margin-bottom:10px;padding:10px;text-align:center;">
            <small>
                <!--<span style='color:#186a3b;'>Icon Legends: </span> -->
                <span class='glyphicon glyphicon-pencil' style='color:#b7950b ;margin-left:5px;'></span> - Edit Salary Loan Info
                <span class='glyphicon glyphicon-eye-open' style='color:#2980b9 ;margin-left:5px;'></span> - View Cashbond Reports 
                <span class='glyphicon glyphicon-plus-sign' style='color: #717d7e  ;margin-left:5px;'></span> - Add Cashbond Deposit
                <span class='glyphicon glyphicon-adjust' style='color:#229954;margin-left:5px;'></span> - Adjust Cashbond Info 
            </small>

        </div>
        <button class="btn btn-success btn-sm pull-right">Print Cashbond Reports</button>
        <br/><br/>
        <table class="table table-striped" id="cashbondList">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                    <th><i class="fas fa-clock"></i>&nbsp;Cashbond Value</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Total Cashbond</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <div class="modal fade" id="editCashbondValueNoModal" tabindex="-1" role="dialog" aria-labelledby="editCashbondValueNoModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCashbondValueNoModalLongTitle">Update Cashbond Value</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="cashbond-info">
                            <span>Cashbond.</span>
                            <input type="text" class="float-only form-control cashbond-value" placeholder="Enter cashbond">
                        
                        </div>
                        <div class="loading-cashbond">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div><br/>
                        <div class="update-cashbond-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary update-cashbond-btn">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="viewCashbondHistoryModal" tabindex="-1" role="dialog" aria-labelledby="viewCashbondHistoryModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="width:1250px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewCashbondHistoryModalLongTitle">Employee Cashbond History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="cashbond-history-info">
                            <div class="row cashbond-history-info-employee">

                            </div><br/>
                            <table class="table table-striped" id="cashbondHistoryList">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Posting Date</th>
                                        <th><i class="fas fa-clock"></i>&nbsp;Deposit</th>
                                        <th><i class="fas fa-wrench"></i>&nbsp;Interest</th>
                                        <th><i class="fas fa-wrench"></i>&nbsp;Withdrawal</th>
                                        <th><i class="fas fa-wrench"></i>&nbsp;Balance</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="loading-cashbond-history">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div><br/>
                        <div class="update-cashbond-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-link print-cashbond-history-btn ">Print Cashbond History</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addCashbondDepositModal" tabindex="-1" role="dialog" aria-labelledby="addCashbondDepositModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCashbondDepositModalLongTitle">Deposit Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="deposit-cashbond-info">
                            <span>Cashbond:</span>
                            <input type="text" readonly class="deposit-cashbond-value form-control" placeholder="Enter cashbond">
                            <span>Deposit:</span>
                            <input type="text" class="float-only deposit-value form-control" placeholder="Enter deposit">
                            <span>Remarks:</span>
                            <textarea class="form-control deposit-remarks" placeholder="Enter remarks"></textarea>
                        </div>
                        <div class="loading-deposit-cashbond">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div><br/>
                        <div class="update-deposit-cashbond-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary submit-deposit-cashbond-btn">Deposit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="adjustCashbondModal" tabindex="-1" role="dialog" aria-labelledby="adjustCashbondModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adjustCashbondModalLongTitle">Adjust Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="adjust-cashbond-info">
                            <span>Cashbond:</span>
                            <input type="text" readonly class="adjust-cashbond-value form-control" placeholder="Enter cashbond">
                            <span>Adjust:</span>
                            <input type="text" class="float-only adjust-value form-control" placeholder="Enter cashbond">
                            <span>Remarks:</span>
                            <textarea class="form-control adjust-remarks" placeholder="Enter remarks"></textarea>
                        </div>
                        <div class="loading-adjust-cashbond">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div><br/>
                        <div class="update-adjust-cashbond-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary submit-adjust-cashbond-btn">Adjust</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $row_cashbond = getInfoByEmpId();
    $totalCashbond = $row_cashbond['totalCashbond'];
    $total_salary_loan = getAllSalaryLoan($employeeInformation['emp_id']);
    $total_simkimban_loan = getAllRemainingBalanceSimkimban($employeeInformation['emp_id']);
    $amount_can_withdraw = ($totalCashbond - 5000) - ($total_salary_loan + $total_simkimban_loan);

    if ($amount_can_withdraw < 0){
        $amount_can_withdraw = 0;
    }
?>
<!-- etong zero dapat one to -->
<?php if(checkExistCashBondByEmpId() == 1):?>
    
    <div class="div-main-body cash-withdrawal" >
        <div class="div-main-body-head">
            Cash Withdraw History
        </div>
        <div class="div-main-body-content">
            <span>
                <i class="fas fa-info-circle"></i>&nbsp;Note: Cashbond rate increase 2% from 3% become 5% per annum and upon 
                reaching 30,000 and above rate also increase by 2% from 5% become 7%.
            </span><br/>
            <button class="btn-outline-success btn btn-sm pull-right" data-toggle="modal" data-target="#cashWithdrawalModal">File Cashbond Withdrawal</button>
            <br/><br/>
            <table class="table table-striped" id="cashbondWithdrawHistoryApprove">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Date File</th>
                        <th><i class="fas fa-clock"></i>&nbsp;Date Approve</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Amount Withdraw</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="modal fade" id="cashWithdrawalModal" tabindex="-1" role="dialog" aria-labelledby="cashWithdrawalModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cashWithdrawalModalLongTitle">File Cash Withdrawal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <span>
                            <strong>Total Cashbond: </strong>
                            Php&nbsp;<?php echo moneyConvertion($totalCashbond)?>
                        </span>
                        <br/>
                        <span>
                            <strong>Pending Loans Total Amount: </strong>
                            Php.&nbsp;<?php echo number_format($total_salary_loan + $total_simkimban_loan,2); ?>
                        </span>
                        <br/>
                        <span>
                            <strong>Withdrawable amount: </strong>
                            Php.&nbsp;<?php echo number_format($amount_can_withdraw,2);?>
                        </span><br/><hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php $current_date = dateFormat(getDateDate()); ?>
                                <span>Date</span>
                                <input type="text" class="form-control" readonly value="<?php echo $current_date?>">
                            </div>
                            <div class="col-lg-6">
                                <span>Amount</span>
                                <input type="text" class="float-only form-control amount-withdraw" placeholder="Enter amount">
                            </div>
                        </div><br/>
                        <div class="file-withdraw-warning">
                            
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary file-withdraw-btn">File Withdrawal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif;?>
<?php if($employeeInformation['role_id'] == 1 || $employeeInformation['role_id'] == 3): ?>
    <div class="div-main-body list-of-filed-cash-withdrawal" >
        <div class="div-main-body-head">
            List of Filed Cashbond Withdrawal
        </div>
        <div class="div-main-body-content">
            <table class="table table-striped" id="pendingCashbondWithdrawal">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Employee Name</th>
                        <th><i class="fas fa-clock"></i>&nbsp;Amount Withdraw</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Date File</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
<?php endif;?>
<?php $file_amount_withdraw = 0;?>
<?php if(checkExistFileCashbondWithdrawal($employeeInformation['emp_id']) == 1):?>
    <?php
        $row_file_cashbond_withdrawal = getLastestFileCashbondWithdrawal($employeeInformation['emp_id']);
        $file_amount_withdraw = $row_file_cashbond_withdrawal['amount_withdraw'];
    ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="div-main-body pending-cashbond-withdrawable-information" >
                <div class="div-main-body-head">
                    Pending File Cashbond Withdrawal Information
                </div>
                <div class="div-main-body-content">
                    <span><strong>Date File:</strong>&nbsp;<?php echo dateFormat($row_file_cashbond_withdrawal['dateCreated'])?></span>
                    <br/>
                    <span><strong>Amount:</strong>&nbsp;Php.&nbsp;<?php echo moneyConvertion($row_file_cashbond_withdrawal['amount_withdraw']) ?></span>
                    <br/><br/>
                    <button class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#editCashbonWithdrawalModal">Edit</button>
                    <button class="btn btn-sm btn-outline-danger cancel-cashbond-withdrawal-btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editCashbonWithdrawalModal" tabindex="-1" role="dialog" aria-labelledby="editCashbonWithdrawalModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCashbonWithdrawalModalLongTitle">Update Cash Withdrawal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span><strong>Note</strong>: Available amount that can withdraw <strong>Php.&nbsp;<?php echo number_format($amount_can_withdraw,2);?></strong></span>
                    <br/><br/>
                    <div class="row">
                        <div class="col-lg-6">
                            <span>Amount to Withdraw</span>
                            <input type="text" class="float-only form-control update-cash-withdraw-amount" value="<?php echo $row_file_cashbond_withdrawal['amount_withdraw']?>" placeholder="Enter amount">
                        </div>
                    </div>
                    <br/>
                    <div class="update-cash-withdraw-warning">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary update-cash-withdrawal-btn">Update</button>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>


<br/><br/>
<br/><br/>
<script src="<?php echo base_url();?>assets/js/cashbond/cashbond.js"></script>
<script src="<?php echo base_url();?>assets/js/cashbond/cash_withdrawal.js"></script>