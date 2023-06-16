<?php $this->load->view('employee/header.php'); ?>

<style type="text/css">
	
.panel {
    margin-bottom: 20px;
    border-color: rgba(55, 71, 79, 0.64)!important;
    color: #333;
}
</style>
<body class="navbar-top">
	<!-- Main navbar -->
	<?php $this->load->view('employee/main_header.php'); ?>
	<!-- /main navbar -->
	<!-- Page container -->
	<div class="page-container">
		<!-- Page content -->
		<div class="page-content">
			<!-- Main sidebar -->
			<?php $this->load->view('employee/sidebar'); ?>
			<!-- /main sidebar -->
			<!-- Main content -->
			<div class="content-wrapper">
				<!-- Page header -->
				<div class="page-header page-header-default">
					<div class="page-header-content">
						<div class="page-title">
							<h4>

                            <?php
							if($this->session->userdata('employee_id')) {
								$consultant_id1= $this->session->userdata('consultant_id');
	                            $logo1=$this->db->query("select * from `consultant` where `consultant_id`='$consultant_id1'")->row();
	                             $dlogo=$this->db->query("select * from `default_setting` where `id`='1'")->row()->logo;
	                            if ($logo1->logo=='1') {
	                            	$logo=$dlogo;
	                            }else{
	                            	 $logo=$logo1->logo;
	                            }
							}
							?>
								<img src="<?php echo base_url(); ?>uploads/logo/<?=$logo?>" style="height:50px;">

							</h4>
						</div>
					</div>
					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
							<li class="active">Dashboard</li>
						</ul>
					</div>
				</div>
				<!-- /page header -->
				<!-- Content area -->
				<div class="content">
					<!-- Dashboard content -->
                    
					<div class="row">
						<div class="col-lg-12">
							<!-- Latest posts -->
							<div class="panel panel-flat">

							 <div class="row">
				    		</div>
					<div class="panel-body">
					<!-- Notifications and dialogs -->
				            <div class="row">
										<div class="col-md-6">
											<div class="panel panel-body border-top-brown text-center">

											   <div class="row">
											   <a href="#">
												  <div class="col-lg-12">
													<div class="panel panel-body border-top-brown text-center">
													<h6 class="no-margin text-semibold">
														<a href="<?php echo base_url(); ?>index.php/Employee/corrective_action_report">Corrective Action Report</a>
													</h6>
													</div>
												  </div>
												</a>
											   </div>
											  <!--  <div class="row">
											   <a href="#">
												  <div class="col-lg-12">
													<div class="panel panel-body border-top-brown text-center">
													<h6 class="no-margin text-semibold">
														<a href="<?php //echo base_url(); ?>index.php/Employee/login_history">Login History</a>
													</h6>
													</div>
												  </div>
												</a>
											   </div> -->
											
										   </div>
										</div>
								<?php if ($user_type != 'Process Owner'):?>
										 <div class="col-md-6">
											<div class="panel panel-body border-top-brown text-center">
												 <div class="row">
												  <div class="col-lg-12">
												  <a href="#" >
													<div class="panel panel-body border-top-brown text-center">
													<h6 class="no-margin text-semibold">
														<a href="<?php echo base_url(); ?>index.php/Employee/open_audit">Open Audit Log</a>
													</h6>
													</div>
												</a>
												  </div>
											   </div>
												<div class="row">

												<a href="#" >
												  <div class="col-lg-12">
													<div class="panel panel-body border-top-brown text-center">
													<h6 class="no-margin text-semibold">
														<a href="<?php echo base_url(); ?>index.php/Employee/close_audit">Close Audit Log</a>
													</h6>
													</div>
												  </div>
												</a>
											   </div>
											</div>
										</div>
								<?php endif?>
									</div>                            
								</div>
							</div>
						</div>
					</div>
					<!-- /dashboard content -->

					<!-- Footer -->
					<?php $this->load->view('employee/footer'); ?>
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->
	</div>
	<!-- /page container -->

</body>
</html>
