<?php $this->load->view('header');?>
<style type="text/css">
	.radio>label{font-size: 13px!important}
</style>

<section class="LoginBox">
   <form name="login_form" id="login_form" action="<?= base_url('index.php/auth/otp_login') ?>" method="post">
	   	<div class="LoginInner wow zoomIn">
		    <div class="form-group">
			     <div class="col-sm-12 LoginImg">
					  <img src="<?php echo base_url()?>assets/home/Images/userimg.png">
					  <h5 class="content-group-lg">One Time Password</h5>				    
                        <input type="hidden" id="key" name="key" value="<?php echo $key; ?>"/>
                        <input type="hidden" id="type" name="type" value="<?php echo $type; ?>"/>
                        <input type="hidden" id="username" name="username" value="<?php echo $username; ?>"/>
                        </small>
                        <small class="display-block" style="display: block;margin-top: 3px;color: red;font-weight:700;font-size:20px;">
                           <p><?php
                           	if ($this->session->flashdata('message')) {
								echo $this->session->flashdata('message');
								$this->session->unset_userdata('message');
						   	}else{
						   ?>
                           	<p>Please check OTP on your email to start login!	</p>
                           <?php } ?>
                        </small>
			     </div>
		      	 <input type="text" maxlength="5" name="verify_otp" placeholder="OTP"/>                 
		    </div>
		    <a href="javascript:login()" class="hvr-bounce-in">Submit</a>
            <a href="javascript:resendOTP();" class="hvr-bounce-in">Resend OTP</a>
		    <!-- <span>Forgot Your Password</span> -->
	   </div><!--LoginInner-->
   </form>
</section><!--LoginBox-->
<?php $this->load->view('footer');?>
<script type="text/javascript">
function login(){
	document.login_form.submit();
}

function resendOTP(){
	var type = $('#type').val();
	if(type != ''){
		$.ajax({
			type: 'POST',
			url:'<?php echo base_url('index.php/auth/resendOtp'); ?>', 
			data: $('#login_form').serialize(),
			success: function(msg){
				location.reload(true);
			}
		});
		return false;
	}
}
</script>