<?php $this->load->view('header');?>

<style type="text/css">
.help-tip{
    position: relative;
    /*top: 18px;
    right: 18px;*/
    text-align: center;
    background-color: #BCDBEA;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    font-size: 14px;
    line-height: 26px;
    cursor: default;
}

.help-tip:before{
    content:'?';
    font-weight: bold;
    color:#fff;
}

.help-tip:hover p{
    display:block;
    transform-origin: 100% 0%;

    -webkit-animation: fadeIn 0.3s ease-in-out;
    animation: fadeIn 0.3s ease-in-out;

}

.help-tip p {
    display: none;
    text-align: left;
    background-color: #1E2021;
    padding: 15px;
    width: 300px;
    position: absolute;
    border-radius: 3px;
    box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.2);
    right: -4px;
    color: #FFF;
    font-size: 12px;
    line-height: 25px;
    z-index: 99;
}

.help-tip p:before{ /* The pointer of the tooltip */
    position: absolute;
    content: '';
    width:0;
    height: 0;
    border:6px solid transparent;
    border-bottom-color:#1E2021;
    right:10px;
    top:-12px;
}

.help-tip p:after{ /* Prevents the tooltip from being hidden */
    width:100%;
    height:40px;
    content:'';
    position: absolute;
    top:-40px;
    left:0;
}

/* CSS animation */

@-webkit-keyframes fadeIn {
    0% { 
        opacity:0; 
        transform: scale(0.6);
    }

    100% {
        opacity:100%;
        transform: scale(1);
    }
}

@keyframes fadeIn {
    0% { opacity:0; }
    100% { opacity:100%; }
}

.form-group.float-right {
    float: right;
    margin: -26px 0px 0px 0px;
    position: relative;
    top: 37px;
}

.g-recaptcha {
    display: block;
    width: auto;
    margin: 0 0 0 50px;
}
</style>


<section class="LoginBox">
	<form name="sign_form" action="<?php echo base_url();?>index.php/Auth/register" method="post">
		<div class="LoginInner wow zoomIn">
			<div class="form-group">
				<div class="col-sm-12 LoginImg">
					<img src="<?php echo base_url()?>assets/home/Images/userimg.png">
					<h1>Sign Up</h1>
					<small class="display-block" style="display: block;margin-top: 15px;color: red;font-weight:700;font-size:20px;"><?php echo validation_errors(); ?></small>
				</div>
				<input type="text" value="<?= set_value('consultant_name') ?>" placeholder="Company Name" name="consultant_name">
			</div>
			<div class="form-group">
				<input value="<?= set_value('username') ?>" type="text" placeholder="Username" name="username">
			</div>		
			<div class="form-group">
				<input value="<?= set_value('email') ?>" type="email" style="outline:none;border: 1px solid #e1e4e5;width: 80%;padding: 10px 12px;" placeholder="Email" name="email">
			</div>
            <div class="form-group">
                <input type="text" value="<?= set_value('phone') ?>" style="outline:none;border: 1px solid #e1e4e5;width: 80%;padding: 10px 12px;" placeholder="Phone" name="phone">
                <div class="form-group float-right">
                    <div class="help-tip">
                        <p>Must Be in International Format <br/> Only Mobile Number Accepted</p>
                    </div>
                </div>
            </div>
			<div class="form-group">
				<input value="<?= set_value('password') ?>" type="password" style="outline:none;border: 1px solid #e1e4e5;width: 80%;padding: 10px 12px;" placeholder="Password" name="password">

				<div class="form-group float-right">
					<div class="help-tip">
						<p>Must include at least 8 chracters <br/>Must include at least 1 uppercase letter(A-Z) <br/>Must include at least 1 lowercase letter(a-z) <br/>Must include at least 1 numeric digit(0-9) <br/>Must include at least 1 special character(!@#$%^*)</p>
					</div>
				</div>		   
			</div>
			<div class="form-group">
				<input value="<?= set_value('repassword') ?>"  type="password" style="outline:none;border: 1px solid #e1e4e5;width: 80%;padding: 10px 12px;" placeholder="Re-Enter Password" name="repassword">
			</div>

			<div class="form-group">

				<script src='https://www.google.com/recaptcha/api.js'></script> 
				<div class="g-recaptcha" data-sitekey="6LeEZfkUAAAAABP2q9CE8VuLPG2eNTvhO8NfLCcS"></div> 
				<div id="errormessage" style="color: red; margin: 5px 0 0 0px"></div> </div> 
				<div class="form-group">
					<label class="checkcontainer">I Agree
						<a style="margin-left: -5px; background-image: none; display: inline; color: #a307a5; font-size: 17px; font-style: italic; text-decoration:underline"
						target="_blank" href = "<?php echo base_url("index.php/auth/terms")?>"
						>Terms</a>for QCIL
						<input style="margin-left : 15px" type="checkbox" checked="checked">
						<span style="margin-left : 15px" class="checkmark"></span>
					</label>
				</div>
				<a href="javascript:signup()" class="SignUp hvr-bounce-in">Sign Up</a> 
			</div>
		</form>
	</section>
<?php $this->load->view('footer');?>
<script type="text/javascript">
	function signup(argument) {
		if(grecaptcha.getResponse() == "") { 
			jQuery("#errormessage").text("Please Fill The Google Captcha");
		}
		else{
			document.sign_form.submit();
		}  
	}
</script>