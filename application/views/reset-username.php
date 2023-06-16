<?php $this->load->view('header');?>
<style type="text/css">
	.radio>label{font-size: 13px!important}
	h5.content-group-lg {
	    margin: 10px 0 0 0px;
	    font-size: 20px;
	}
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
    .divider-with-text {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 15px;
    }
    .divider-with-text::before {
        content: "";
        height: 1px;
        background: rgba(0, 0, 0, 0.10);
        flex: 1;
        margin-right: 10px;
        margin-top: 4px;
    }
    .divider-with-text::after {
        content: "";
        height: 1px;
        background: rgba(0, 0, 0, 0.10);
        flex: 1;
        margin-left: 10px;
        margin-top: 4px;
    }
    .custom-input{
        outline: none;
        border: 1px solid #e1e4e5;
        padding: 10px 12px;
        border-radius: 0;
        height: 42px;
        box-shadow: none;
    }
    .custom-input:focus{border: 1px solid #e1e4e5;box-shadow: none;}
    .c-row{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .c-column{
        width: 80%;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .c-column > .form-group{
        margin-bottom: 0;
        flex-grow: 1;
    }
    .c-column > .help-tip{
        margin-left: 12px;
    }
    .LoginInner input[type="text"]{
        width: 100% !important;
    }
</style>
<section class="LoginBox">
    <form name="reset_pass_form" action="<?=base_url();?>index.php/Auth/resetUsername" method="post">
        <input type="hidden" name="recovery_link" value="<?= $recovery_link ?>">
        <div class="LoginInner wow zoomIn">
            <div class="row">
                <div class="col-sm-12 LoginImg tooltip-section">
                    <img src="<?php echo base_url()?>assets/home/Images/userimg.png">
                    <h5 class="content-group-lg">Reset Your Username</h5>
                    <small class="display-block" style="display: block;margin-top: 3px;color: red;font-weight:700;font-size:20px;">
                        <?php echo validation_errors();
                            if ($this->session->flashdata('message')) {
                                echo $this->session->flashdata('message');
                                $this->session->unset_userdata('message');
                            }
                        ?>
                    </small>

                </div>
            </div>
            <div class="clearfix"></div>
            <div class="c-row">
                <div class="c-column">
                    <div class="form-group">
                        <input class="form-control custom-input" type="text" name="username" placeholder="Enter Username" />
                    </div>
                </div>
            </div>
            <!--<div class="form-group float-right">
                <div class="help-tip">
                    <p>Must include at least 8 chracters <br/>Must include at least 1 uppercase letter(A-Z) <br/>Must include at least 1 lowercase letter(a-z) <br/>Must include at least 1 numeric digit(0-9) <br/>Must include at least 1 special character(!@#$%^*)</p>
                </div>
            </div>-->
            <div class="c-row">
                <div class="c-column">
                    <div class="form-group text-center">
                        <script src='https://www.google.com/recaptcha/api.js'></script>
                        <div class="g-recaptcha" data-sitekey="6LeEZfkUAAAAABP2q9CE8VuLPG2eNTvhO8NfLCcS"></div>
                        <div id="errormessage" style="color: red; margin: 5px 0 0 0px"></div>
                    </div>
                </div>
            </div>

            <a href="javascript:reset()" class="hvr-bounce-in">Reset Username</a>
            <p class="divider-with-text text-muted text-center">Already have an account?</p>
            <a href="<?= base_url('index.php/Welcome/login'); ?>"><span>Login</span></a>
            <!--    <span>Forgot Your Password</span>-->
        </div><!--LoginInner-->
    </form>
</section><!--LoginBox-->
<?php $this->load->view('footer');?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>
<script type="text/javascript">
    function reset(){
        if(grecaptcha.getResponse() == "") {
            jQuery("#errormessage").text("Please Fill The Google Captcha");
        }
        else{
            document.reset_pass_form.submit();
        }
    }
</script>