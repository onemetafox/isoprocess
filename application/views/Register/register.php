

<?php $this->load->view('header_url.php'); ?>

<body class="login-container">

	<!-- Main navbar -->

	<div class="navbar navbar-inverse">

		<div class="navbar-header">

			



			<ul class="nav navbar-nav pull-right visible-xs-block">

				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>

			</ul>

		</div>



		<div class="navbar-collapse collapse" id="navbar-mobile">

			<ul class="nav navbar-nav navbar-right">

				<li>

					<a href="<?php echo base_url(); ?>index.php/Welcome/index">

						<i class="icon-user"></i> Sign In

					</a>

				</li>

			</ul>

		</div>

	</div>

	<!-- /main navbar -->





	<!-- Page container -->

	<div class="page-container">



		<!-- Page content -->

		<div class="page-content">



			<!-- Main content -->

			<div class="content-wrapper">



				<!-- Content area -->

				<div class="content">



					<!-- Advanced login -->

					<form name="login_form" action="<?php echo base_url(); ?>index.php/Auth/register" method="post">

						<div class="login-form">

							<div class="text-center">

								<div class="icon-object border-warning-400 text-warning-400"><i class="icon-people"></i></div>

								<h5 class="content-group-lg">Create account <small class="display-block" style="color: red;font-weight:700;font-size:14px;"><?php echo validation_errors(); ?></small></h5>

							</div>

                            <div class="form-group has-feedback has-feedback-left">

								<input type="text" class="form-control input-lg" placeholder="Company Name" name="company_name">

								<div class="form-control-feedback">

									<i class="icon-home text-muted"></i>

								</div>

							</div>

							<div class="form-group has-feedback has-feedback-left">

								<input type="email" class="form-control input-lg" placeholder="Email" name="email">

								<div class="form-control-feedback">

									<i class="icon-mail5 text-muted"></i>

								</div>

							</div>

							<div class="form-group has-feedback has-feedback-left">

								<input type="text" class="form-control input-lg" placeholder="Username" name="username">

								<div class="form-control-feedback">

									<i class="icon-user text-muted"></i>

								</div>

							</div>



							<div class="form-group has-feedback has-feedback-left">

								<input type="password" class="form-control input-lg" placeholder="Password" name="password">

								<div class="form-control-feedback">

									<i class="icon-lock2 text-muted"></i>

								</div>

							</div>

							<div class="form-group has-feedback has-feedback-left">

								<input type="password" class="form-control input-lg" placeholder="Retype Password" name="repassword">

								<div class="form-control-feedback">

									<i class="icon-lock2 text-muted"></i>

								</div>

							</div>
							<div class="form-group">

<script src='https://www.google.com/recaptcha/api.js'></script> 
			<div class="g-recaptcha" data-sitekey="6LeMZPkUAAAAAOY59BjLyKtYXFOH3YU4QNGKWSw4"></div> 
		<div id="errormessage"></div> </div> 
						

							<div class="form-group">

							<a href="javascript:login()"  class="btn bg-blue btn-block btn-lg">Register <i class="icon-arrow-right14 position-right"></i></a>

							</div>

							<div class="content-divider text-muted form-group"><span>Already have an account?</span></div>

							<a href="<?php echo base_url(); ?>index.php/Welcome/index" class="btn bg-slate btn-block btn-lg content-group">Login</a>

							<span class="help-block text-center">By continuing, you're confirming that you've read and agree to our <a href="#">Terms and Conditions</a> and <a href="#">Cookie Policy</a></span>

						</div>

					</form>

					<!-- /advanced login -->



				</div>

				<!-- /content area -->



			</div>

			<!-- /main content -->



		</div>

		<!-- /page content -->



	</div>

	<!-- /page container -->

<script type="text/javascript">

	

console.clear();


 
	function login(){
if(grecaptcha.getResponse() == "") { 
                 jQuery("#errormessage").text("Please Fill The Google Captcha");
              }
              else{
              			document.login_form.submit();
              } 

}

</script>

</body>



<!-- Mirrored from demo.interface.club/limitless/layout_1/LTR/default/login_transparent.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 02 Jan 2018 12:28:29 GMT -->

</html>