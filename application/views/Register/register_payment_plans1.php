
<?php $this->load->view('header_url.php'); ?>
<style type="text/css">
	.thumb-rounded, .thumb-rounded .caption-overflow, .thumb-rounded img {
    border-radius: 0%!important;
}
</style>
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
					<a href="<?php echo base_url(); ?>index.php/Welcome/logout">
						<i class="icon-lock"></i> Logout
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

					<!-- Unlock user -->
					<form action="<?php echo base_url(); ?>index.php/Auth/add_purchase" class="login-form" method="post">
						<div class="panel">
							<div class="panel-body">
								<div class="thumb thumb-rounded">
									<img src="<?php echo base_url(); ?>assets/images/company.jpg" alt="">
									<div class="caption-overflow">
										<span>
											<a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded btn-xs"><i class="icon-collaboration"></i></a>
											<a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded btn-xs ml-5"><i class="icon-question7"></i></a>
										</span>
									</div>
								</div>
						  <h6 class="content-group text-center text-semibold no-margin-top">
							<small class="display-block">How many accounts are you going to create?</small></h6>

								<div class="form-group has-feedback">

									 <div class="row">
                            	         <div class="col-md-12">

											 <?php if(isset($trial_plan)) { ?>
												 <div class="radio">
													 <label>
														 <input type="radio" name="plan_id" class="control-warning"
																value="<?= $trial_plan->plan_id ?>">
														 <?= $trial_plan->plan_name ?>

													 </label>
												 </div>
												 <div style="padding-left: 27px;">You can use trial for 14 days</div>
												 <div style="padding-left: 27px;">User Limit: <?=$trial_plan->no_of_user?></div>

												 <?php
											 }
											$cnt = 0;
											foreach ($plan as $plans) {
												$cnt ++;
												?>
												<div class="radio">
													<label>
														<input type="radio" name="plan_id" class="control-warning" value="<?=$plans->plan_id?>" <?php if($cnt == 1) echo "checked"; ?>>
														<?=$plans->plan_name?>

													</label>
												</div>
												<div style="padding-left: 27px;">Employees Limit: <?=$plans->no_of_user?></div>
												<div style="padding-left: 27px;">Price: <span style="color: red;">$<?=$plans->total_amount?></span></div>
											     <?php }?>
											</div>
                                     </div>
								</div>
								<button type="submit" class="btn btn-primary btn-block">Next <i class="icon-arrow-right14 position-right"></i></button>
							</div>
						</div>
					</form>
					<!-- /unlock user -->
					<!-- Footer -->
				
					<!-- /footer -->

				</div>
			</div>
		</div>
	</div>
</body>
</html>
