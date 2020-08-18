
<div class="d-flex flex-column justify-content-center align-items-center login">
    <img src="<?php echo base_url();?>assets/images/auth/loginbg.jpg" class="loginbg" alt="Lloyds">
    <div class="loginForm">
        <div class="d-flex p-2 justify-content-center">
            <img src="<?php echo base_url();?>assets/images/auth/lloydslogo.png" alt="Lloyds">
            
        </div>
        <p class="loginTitle">Sign in to <span>HuPay System</span></p>
        <i class="fa fa-user"></i>
        <input type="text" class="form-control username" placeholder="Username">
        <i class="fa fa-lock"></i>
        <input type="password" class="form-control password" placeholder="Password">
        <i class="fa fa-building"></i>
        <select class="form-control companySelect">
        </select>
        <button class="form-control btn btn-primary btn-sm loginBtn">LOG IN</button>
        <button data-toggle="modal" data-target="#forgotPasswordModal" class="btn btn-link form-control forgotPasswordBtn">Forgot Password?</button>
        <br/>
        <div class="login-warning">

        </div>
    </div>
    <div class="copyright">
        <img src="<?php echo base_url();?>assets/images/auth/lloydslogo.png" alt="Lloyds">
        <small>Copyright <span class="glyphicon glyphicon-copyright-mark"></span> 
            <?php echo $version; ?>
        </small>
    </div>
</div>
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLongTitle">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control forgot-username" placeholder="Username">
                <input type="text" class="form-control forgot-code" placeholder="Code">
                <div class="forgot-warning"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm form-control submitForgotBtn">Submit</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/auth/login.js"></script>