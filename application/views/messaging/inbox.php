<div class="div-main-body inbox" >
    <div class="div-main-body-head">
        Message Inbox
    </div>
	<div class="div-main-body-content">
		<table class="table table-striped" id="inboxList">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-alt"></i>&nbsp;Date</th>
                    <th><i class="fas fa-clock"></i>&nbsp;From</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;To</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Subject</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Message</th>
                    <th><i class="fas fa-wrench"></i>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
        <div class="modal fade" id="inboxModal" tabindex="-1" role="dialog" aria-labelledby="inboxModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="inboxModalLongTitle">Message History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="message-history-info">
                            <div class="messages-section">
                            	<div class="messages-head">
                            		<p class='subject-name'></p>
                            	</div>
                            	<div class="messages-body">
                            		
                            	</div>
                            	<textarea class="form-control message-reply" placeholder="Enter your reply" required="required" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="loading-message-history">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary submit-reply-btn">Submit</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/messaging/inbox.js"></script>