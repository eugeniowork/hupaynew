<?php $employeeInformation = employeeInformation();?>
<?php $this->load->helper('employee_helper')?>
<div class="div-main-body memorandum" >
    <div class="div-main-body-head">
        List of Memorandum
    </div>
	<div class="div-main-body-content ">
        <button class="btn btn-outline-success pull-right">Add Memorandum</button><br/><br/>
		<table class="table table-striped table-bordered" id="listOfMemorandum">
            <thead>
                <tr>
                    <th><small><i class="fas fa-calendar-alt"></i>&nbsp;Subject</small></th>
                    <th><small><i class="fas fa-calendar-alt"></i>&nbsp;To</small></th>
                    <th><small><i class="fas fa-wrench"></i>&nbsp;Date</small></th>
                    <th><small><i class="fas fa-wrench"></i>&nbsp;Content</small></th>
                    <th><small><i class="fas fa-wrench"></i>&nbsp;Action</small></th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>

        <div class="modal fade" id="editMemoModal" tabindex="-1" role="dialog" aria-labelledby="editMemoModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMemoModalLongTitle">Update Memorandum</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="update-memo-info">
                            <button class='btn btn-primary pull-right update-add-recipient'>Add Recipient</button>
                            <br/><br/>
                            <div class="recipient">
                                
                            </div>
                            <span>From:</span>
                            <input type="text" class="input-only form-control update-from" placeholder="From ..." />
                            <span>Subject</span>
                            <input type="text" class="form-control update-subject" >
                            <span>Content:</span>
                            <textarea rows="13" class="form-control update-content" placeholder="Write Content ..." id="" name="update_content" required="required"></textarea>
                        </div>
                        <div class="loading-update-memo">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div><br/>
                        <div class="update-memo-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary update-memo-btn">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="emp_list_modal" tabindex="-1" role="dialog" aria-labelledby="editMemoModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMemoModalLongTitle">Update Memorandum</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="">
                        <table id="attendance_list" class="memo-employee-list table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
                            <thead>
                                <tr>
                                    <th class="no-sort"><center><span class="glyphicon glyphicon-user" style="color:#186a3b"></span> Employee Name</center></th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php echo getAllEmployeesNameToTable(); ?>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>

        <div class="modal fade" id="memoImagesModal" tabindex="-1" role="dialog" aria-labelledby="memoImagesModalTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="memoImagesModalLongTitle">Memorandum Images</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="memo-images-info">
                            <span><strong>Subject: </strong><span class="memo-subject"></span></span>
                            
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="image-section">
                                
                                </div>
                            </div>
                        </div>
                        <div class="loading-images-memo">
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Loading Information</p>
                            </div>
                        </div><br/>
                        <div class="memo-images-warning">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary update-memo-image-btn">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div id="emp_list_modal" class="modal fade" role="dialog" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#158cba;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-user' style='color:#fff'></span>&nbsp;Employee List</h5>
                    </div> 
                    
                </div>

            </div>
        </div> -->
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/memorandum/memorandum.js"></script>