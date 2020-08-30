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
                            <input type="text" id="float_only" class="form-control cashbond-value" placeholder="Enter cashbond">
                        
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
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/cashbond/cashbond.js"></script>