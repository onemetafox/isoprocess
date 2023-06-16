<?php $this->load->view('header');?>
<style type="text/css">
	.radio>label{font-size: 13px!important}
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>
<section class="LoginBox">
<?php
if(!empty($comp))
	$otp_status = $comp->otp_status;
?>
   <form name="login_form" action="<?php echo base_url(); ?>index.php/Auth/<?= $otp_status ? 'verifyMethod':'login' ?>" method="post">
       <input type="hidden" name="v" value="<?=$otp_status?>">
	   	<div class="LoginInner wow zoomIn">
		    <div class="form-group">
			     <div class="col-sm-12 LoginImg">
					  <img src="<?php echo base_url()?>assets/home/Images/userimg.png">
					  <small class="display-block" style="display: block;margin-top: 3px;color: red;font-weight:700;font-size:20px;">
					    <?php echo validation_errors();
								if ($this->session->flashdata('message')) {
									echo $this->session->flashdata('message');
									$this->session->unset_userdata('message');
								}
					    ?>
					  </small>
			     </div>
		      	 <input type="text" name="username" placeholder="User Name">
		    </div>
		    <div class="form-group">
		      <input type="password" id="password" data-toggle="password" data-eye-open-class="fa fa-eye-slash" data-eye-close-class="fa fa-eye" style="outline:none;border: 1px solid #e1e4e5;width: 80%;padding: 10px 12px;" name="password" placeholder="Password">
		    </div>
		    <div class="form-group" style="text-align:left;padding-left:41px">
				<div class="radio">
					<label>
						<input type="radio" name="usertype" class="control-warning" value="Consultant" checked>
						Admin
					</label>
				</div>


				<div class="radio">
					<label>
						<input type="radio" name="usertype" class="control-info" value="Lead Auditor">
						Lead Auditor
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="usertype" class="control-info" value="Auditor">
						Auditor
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="usertype" class="control-info" value="Process Owner">
						Process Owner
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="usertype" class="control-info" value="Auditee">
						Auditee
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="usertype" class="control-custom" value="admin">
							Super Administrator
					</label>
				</div>
				<!-- <script src='https://www.google.com/recaptcha/api.js'></script>  -->
				<!----Email ID for this key solutions.provider.dev@gmail.com-- 6LdedfsgAAAAAAA1MqJra9k5eEvtf_gp1gz6oi7h-->
			<!-- <div class="g-recaptcha" data-sitekey="6LdedfsgAAAAAAA1MqJra9k5eEvtf_gp1gz6oi7h"></div>  -->
			<!----OLD Key :  6LeEZfkUAAAAABP2q9CE8VuLPG2eNTvhO8NfLCcS---->
			<!--------    :  6LfFAvsgAAAAAAjak90G1MG8y0W6HwOcSOYNH5z1 --->
		<div id="errormessage" style="color: red; margin: 5px 0 0 0px"></div> 
			</div>
		    <a href="javascript:login()" class="hvr-bounce-in">Login</a>
            <a href="<?= base_url('index.php/Auth/forgot_pass'); ?>" class="hvr-bounce-in"><span>Forget Account <small>(Username & Password)</small></span></a>
	   </div><!--LoginInner-->
   </form>
</section><!--LoginBox-->
<?php $this->load->view('footer');?>
<script type="text/javascript">
	$("#password").password('toggle');
</script>
<script type="text/javascript">
function login(){
    // if(grecaptcha.getResponse() == "") {
    //     jQuery("#errormessage").text("Please Fill The Google Captcha");
    // }
    // else{
        document.login_form.submit();
    // }

}
</script>