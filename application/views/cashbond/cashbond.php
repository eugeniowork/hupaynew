<div class="div-main-body" >
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
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/cashbond/cashbond.js"></script>