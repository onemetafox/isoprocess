<?php $this->load->view('consultant/header')?>
<link href="<?=base_url(); ?>assets/home/css/font-awesome.min.css" rel="stylesheet" type="text/css">	
<style>
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
</style>
<body class="navbar-top">
	<!-- Main navbar -->
	<?php $this->load->view('consultant/main_header')?>
	<!-- /main navbar -->
	<!-- Page container -->
	<div class="page-container">
		<!-- Page content -->
		<div class="page-content">
			<!-- Main sidebar -->
			<?php $this->load->view('consultant/sidebar')?>
			<!-- /main sidebar -->
			<!-- Main content -->
			<div class="content-wrapper">
				<!-- Page header -->
				<div class="page-header page-header-default">
					<div class="page-header-content">
						<div class="page-title">
							<h4>
								<?php
									if ($this->session->userdata('consultant_id')) {
										$consultant_id= $this->session->userdata('consultant_id');
										$logo1=$this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row();
										$dlogo=$this->db->query("select * from `default_setting` where `id`='1'")->row()->logo;
										if ($logo1->logo=='1') {
											$logo=$dlogo;
										}else{
											$logo=$logo1->logo;
										}
									}
								?>
								<img src="<?php echo base_url(); ?>uploads/logo/<?=$logo?>" style="height:50px;">
								 MAIN INFO
							</h4>
						</div>
					</div>
					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
							<li class="active">MAIN INFO</li>
						</ul>
					</div>
				</div>
				<!-- Content area -->
				<div class="content">
                   <?php if($this->session->flashdata('message')=='update_success') { ?>
                      	  <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							Profile Successfully Updated.. 
				        </div>
                      <?php  $this->session->unset_userdata('message');  } ?>
                    <?php if($this->session->flashdata('phone_response')) { ?>
                        <div class="alert alert-danger alert-styled-right alert-arrow-right alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            <?= $this->session->flashdata('phone_response')['message'] ?>
                        </div>
                    <?php  $this->session->unset_userdata('phone_response');  } ?>
					<!-- Form horizontal -->
					<div class="panel panel-flat">
						<div class="panel-heading">
							<div class="heading-elements">
								<ul class="icons-list">
			                		<li><a data-action="collapse"></a></li>
			                	</ul>
		                	</div>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" action="<?php echo base_url();?>index.php/consultant/update_main_info" method="post" enctype="multipart/form-data">
								<fieldset class="content-group">
									<legend class="text-bold">MAIN INFO</legend>
									<div class="form-group">
										<label class="control-label col-lg-2">Username</label>
										<div class="col-lg-10">
											<input type="text" class="form-control" name="username" value="<?=$profile->username?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-lg-2">consultant Name</label>
										<div class="col-lg-10">
											<input type="text" class="form-control" name="consultant_name" value="<?=$profile->consultant_name?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-lg-2">City</label>
										<div class="col-lg-10">
											<input type="text" class="form-control" name="city" value="<?=$profile->city?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-lg-2">State</label>
										<div class="col-lg-10">
											<input type="text" class="form-control" name="state" value="<?=$profile->state?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-lg-2">Address</label>
										<div class="col-lg-10">
											<input type="text" class="form-control" name="address" value="<?=$profile->address?>">
										</div>
									</div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Phone</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" name="phone" value="<?=$profile->phone?>">
                                        </div>
                                    </div>
									<?php
									if ($this->session->userdata('consultant_id')) {
										$consultant_id= $this->session->userdata('consultant_id');
										$plan_types=$this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row();
									}
									?>
									<?php if($plan_types->plan_type=='real') { ?>
										<div class="form-group">
											<label class="control-label col-lg-2">consultant Logo</label>
											<div class="col-lg-10">
												<img src="<?php echo base_url(); ?>uploads/logo/<?=$plan_types->logo?>" style="margin-bottom: 10px;height: 44px;">
												<input type="file" class="form-control" name="picture" >
											</div>
										</div>
									<?php } ?>
									<!-- sponsor.png -->
								</fieldset>
								<div class="text-right">
									<button type="submit" class="btn btn-primary">UPDATE <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</form>
						</div>
					</div>

					<!-- setion to update the password -->
                    <?php if($this->session->flashdata('password')) { $password_message = $this->session->flashdata('password'); ?>
                        <div class="alert alert-<?= ($password_message['success'] ? 'success':'danger') ?> alert-styled-right alert-arrow-right alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            <?= $password_message['message'] ?>
                        </div>
                    <?php  $this->session->unset_userdata('password'); } ?>
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" action="<?php echo base_url();?>index.php/consultant/update_main_info_password" method="post" enctype="multipart/form-data">
                                <fieldset class="content-group">
                                    <legend class="text-bold">Update Password</legend>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Old Password</label>
                                        <div class="col-lg-10">
                                            <input type="password" class="form-control" name="old_password" placeholder="Enter Your Previous Password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Password</label>
                                        <div class="col-lg-10">
                                            <!-- <input type="password" class="form-control custom-input" data-toggle="password" data-eye-open-class="fa fa-eye-slash" data-eye-close-class="fa fa-eye" name="password" placeholder="Enter Your New Password"> -->
											<div class="form-group">
												<input class="form-control custom-input" type="password" data-toggle="password" data-eye-open-class="fa fa-eye-slash" data-eye-close-class="fa fa-eye" name="password" placeholder="Enter Your New Password" />
											</div>
											<div class="help-tip">
												<p>Must include at least 8 chracters <br/>Must include at least 1 uppercase letter(A-Z) <br/>Must include at least 1 lowercase letter(a-z) <br/>Must include at least 1 numeric digit(0-9) <br/>Must include at least 1 special character(!@#$%^*)</p>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Confirm Password</label>
                                        <div class="col-lg-10">
                                            <input type="password" class="form-control" name="repassword" placeholder="Enter New Confirm Password">
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Update <i class="icon-arrow-right14 position-right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>


                   <?php if($this->session->flashdata('message')=='update_security_success') { ?>
						<div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							Security Successfully Updated.. 
				        </div>
					<?php  $this->session->unset_userdata('message'); } ?>

					<div class="panel panel-flat">
						<div class="panel-heading">
							<div class="heading-elements">
								<ul class="icons-list">
			                		<li><a data-action="collapse"></a></li>
			                		<li><a data-action="reload"></a></li>
			                		<li><a data-action="close"></a></li>
			                	</ul>
		                	</div>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" action="<?php echo base_url();?>index.php/consultant/update_security_question" method="post">
								<fieldset class="content-group">
									<legend class="text-bold">Two-step verification <br/> (Help protect your account by enabling extra layers of security.)</legend>
									<div class="form-group">
										<label class="control-label col-lg-2">New Question</label>
										<div class="col-lg-10">
											<!--<input type="text" class="form-control" name="username" value="<?=$profile->username?>">-->
											<select required="" name="question" class="form-control">
												<option value="">Choose Question</option>
												<option <?= isset($profile->security_question) && $profile->security_question == "Your mother's maiden name" ? 'selected' : '' ?> value="Your mother's maiden name">Your mother's maiden name</option>
												<option <?= isset($profile->security_question) && $profile->security_question == "Your first pet's name" ? 'selected' : '' ?> value="Your first pet's name">Your first pet's name</option>
												<option <?= isset($profile->security_question) && $profile->security_question == "The name of your elementary school" ? 'selected' : '' ?> value="The name of your elementary school">The name of your elementary school</option>
												<option <?= isset($profile->security_question) && $profile->security_question == "Your favorite sports team" ? 'selected' : '' ?> value="Your favorite sports team">Your favorite sports team</option>
												<option <?= isset($profile->security_question) && $profile->security_question == "Your best friend's nickname" ? 'selected' : '' ?> value="Your best friend's nickname">Your best friend's nickname</option>
												<option <?= isset($profile->security_question) && $profile->security_question == "The city where you first met your spouse" ? 'selected' : '' ?> value="The city where you first met your spouse">The city where you first met your spouse</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-lg-2">Answer</label>
										<div class="col-lg-10">
											<input required="" type="text" placeholder="Give answer based upon the question" class="form-control" name="answer" value="<?= isset($profile->security_answer) && ($profile->security_answer) ? $profile->security_answer : '' ?>">
										</div>
									</div>
								</fieldset>
								<div class="text-right">
									<button type="submit" class="btn btn-primary">UPDATE <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</form>
						</div>
					</div>
					<?php $this->load->view('consultant/footer')?>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
	$("#password").password('toggle');
</script>
</html>

