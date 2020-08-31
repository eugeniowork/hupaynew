<div class="div-main-body audit-trail" >
    <div class="div-main-body-head">
        Audit Trail Logs
    </div>
    <div class="div-main-body-content">
        <div class="loading-audit-trail">
            <div class="d-flex flex-column justify-content-center align-items-center loading-audit-trail-content">
                <div class="spinner-border text-primary" role="status"></div><br/>
                <p>Fetching Audit Trail Logs . . . .</p>
            </div>
        </div><br/>
    	<div class="audit-trail-content">
            <table class="table table-striped" id="auditTrail">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i>&nbsp;Module</th>
                        <th><i class="fas fa-clock"></i>&nbsp;Description</th>
                        <th><i class="fas fa-wrench"></i>&nbsp;Date Time Info</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/audit_trail/audit_trail.js"></script>