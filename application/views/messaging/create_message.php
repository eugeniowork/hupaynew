<div class="div-main-body create-message" >
    <div class="div-main-body-head">
        Create Message
    </div>
	<div class="div-main-body-content">
		<div class="d-flex flex-row justify-content-center">
			<div class="row">
				<div class="col-lg-12">
					<span>To</span><br/>
					<input type="text" class="form-control to-name" placeholder="Enter Name">
				</div>
				<div class="col-lg-12">
					<span>Subject</span>
					<input type="text" class="form-control subject" placeholder="Enter Subject">
				</div>
				<div class="col-lg-12">
					<span>Message</span>
					<textarea class="form-control message" rows="10" placeholder="Enter Message"></textarea>
				</div>
				<div class="col-lg-12">
					<br/>
					<button class="btn btn-primary send-message-btn">Send</button>
				</div>
				<div class="col-lg-7">
					<br/>
					<div class="send-message-warning">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/messaging/create_message.js"></script>