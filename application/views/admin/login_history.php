<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$title?></title>
<!--	<link href="http://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">-->
	<link href="<?=base_url(); ?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<!-- /global stylesheets -->
      
	<!-- Core JS files -->
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<!-- <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script> -->
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/core/app.js"></script>
	
 <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
 <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
 <script type="text/javascript" src="<?=base_url(); ?>assets/js/pages/datatables_basic.js"></script> 
   <style type="text/css">
    	/*.clist {
		    background-color:#26a69a;
		    color: #fff;
		}*/
  	.toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
 	.toggle.ios .toggle-handle { border-radius: 20px; }
 	  .paylist_1 {
		    background-color:#26a69a;
		    color: #fff;
		}

	.unknownlocation {
        color: red;
    }.samelocation {
        color: green;
    }
    </style> 

</head>


<body class="navbar-top">
	<!-- Main navbar -->
	<?php $this->load->view('Admin/main_header.php'); ?>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<?php $this->load->view('Admin/sidebar'); ?>
			<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Page header -->
				<div class="page-header page-header-default">
					<div class="page-header-content">
						<!-- <div class="page-title">
							<h4><i class="icon-lan2 position-left"></i> <span class="text-semibold"><?=$title?></span>
								<button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modal_theme_primary_add">New Company<i class="icon-lan2 position-right"></i></button>
							</h4>
						</div> -->
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>index.php/Welcome/consultantdashboard"><i class="icon-home2 position-left"></i>Home</a></li>
							<li><a href="#"><?=$title?></a></li>
						
						</ul>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">
                     <?php
                      
                      if($this->session->flashdata('message')=='success') { ?>
                      	 <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							<span class="text-semibold">Thank you!</span>Plan Successfully created.. 
				        </div>
                    <?php  $this->session->unset_userdata('message'); } ?>
                     
                        <?php if($this->session->flashdata('message')=='failed') { ?>
                      	 <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							<span class="text-semibold">Oppps!</span>Something Went Wrong Please try again.
				        </div>
                      <?php  $this->session->unset_userdata('message'); } ?>
                      <?php if($this->session->flashdata('message')=='success_del') { ?>
                      	  <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							consultant Successfully Deleted.. 
				        </div>
                      <?php  $this->session->unset_userdata('message'); } ?>

                      <?php if($this->session->flashdata('message')=='update_success') { ?>
                      	  <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							consultant Successfully Updated.. 
				        </div>
                      <?php $this->session->unset_userdata('message');  } ?>
                    <?php if($this->session->flashdata('phone_response')) { ?>
                        <div class="alert alert-danger alert-styled-right alert-arrow-right alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            <?= $this->session->flashdata('phone_response')['message'] ?>
                        </div>
                    <?php  $this->session->unset_userdata('phone_response'); } ?>

					 <!-- Basic datatable -->
                <div class="panel panel-flat" id="ptn" style="overflow:auto;">
                    <table class="table  table-bordered datatable-basic">

                        <thead>

                        <tr>
                            <th>ID</th>
                            <th>Login Area</th>
                            <th>IP Address</th>
                            <th>Login Platform</th>
                            <th>Login Device</th>
                            <th>Time</th>
                            <th style="display: none;"></th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php $count=1;
                        foreach ($login_history as $viewhistory) {
                        	$status = $viewhistory->status;
                        if($status == "1")
                        {
                            $class = "samelocation";
                        }else{
                            $class = "unknownlocation";
                        }
                            ?>


                            <tr class="<?php echo $class; ?>">
                                <td><?=$count?></td>
                                <td><?=$viewhistory->login_area?></td>
                                <td><?=$viewhistory->IP_address?></td>
                                <td><?=$viewhistory->login_platform?></td>
                                <td><?=$viewhistory->login_device?></td>
                                <td><?=$viewhistory->date_time?></td>
                                <td style="display: none;">
                                   
                                </td>
                            </tr>
                            <?php $count++; } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /basic datatable -->

					<!-- Footer -->
					<?php $this->load->view('Admin/footer'); ?>
					<!-- /footer -->

				</div>
				<!-- /content area -->
			</div>
			<!-- /main content -->
		</div>
		<!-- /page content -->
	</div>
	<!-- Primary modal -->
	<div id="modal_theme_primary1" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title"><i class="icon-home2 position-right"></i> Edit Consultant</h6>
				</div>
				<div class="modal-body">
					<form action="<?php echo base_url();?>index.php/Admin/edit_consultant"  method="post">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Company Name: </label>
									<input type="text" placeholder="Company Name" required class="form-control" name="consultant_name" id="consultant_name">
									<div class="form-control-feedback">
										<i class="icon-list text-muted"></i>
									</div>
								</div>
							</div>
						</div>

						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Username: </label>
									<input type="text" placeholder="Username" required  class="form-control" name="username" id="username">
									<div class="form-control-feedback">
										<i class="icon-user text-muted"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Email: </label>
									<input type="email"  class="form-control" name="email" id="email" placeholder="Email">
									<div class="form-control-feedback">
										<i class="icon-inbox text-muted"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Password: </label>
									<input type="password" class="form-control"  name="password" id="password" placeholder="Password">
									<input type="hidden"  class="form-control" name="consultant_id11" id="consultant_id11">
									<div class="form-control-feedback">
										<i class="icon-key text-muted"></i>
									</div>
								</div>
							</div>
						</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label>Phone: </label>
                                    <input type="text"  class="form-control" name="phone" id="phone" placeholder="Phone">
                                    <div class="form-control-feedback">
                                        <i class="icon-mobile2 text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Plan: </label>
									<select name="plan" id="edit_plan_id1" required class="form-control">
										<option value="">--Select Plan--</option>
										<?php
										if(!empty($plan))
										{
											foreach($plan as $idx => $plan_rec)
											{
												?>
												<option value="<?php echo $plan_rec['plan_id'];?>"><?php echo $plan_rec['plan_name'];?></option>
												<?php
											}
										}?>
									</select>
									<!--<input type="text" placeholder="Plan" class="form-control" name="plan" id="plan">-->
									<div class="form-control-feedback">
										<i class="icon-list text-muted"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Expired Date: </label>
									<input type="text" class="form-control" required name="expired_date" id="expired_date" placeholder="Expired Date">
									<div class="form-control-feedback">
										<i class="icon-calendar text-muted"></i>
									</div>
								</div>
							</div>
						</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label>OTP Verification Status: </label>
                                    <select name="otp_status" id="otp_status" class="form-control">
                                        <option value="0">Disabled</option>
                                        <option value="1">Enabled</option>
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-user-lock text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="icon-plus2 position-right"></i> Edit</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /primary modal -->
	<div id="modal_theme_primary_add" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title"><i class="icon-home2 position-right"></i> Add Company</h6>
				</div>
				<div class="modal-body">
					<form action="<?php echo base_url();?>index.php/Admin/add_consultant"  method="post">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Company Name: </label>
									<input type="text" placeholder="Company Name" required  class="form-control" name="consultant_name" >
									<div class="form-control-feedback">
										<i class="icon-list text-muted"></i>
									</div>
								</div>
							</div>
						</div>

						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Username: </label>
									<input type="text" placeholder="Username" required class="form-control" name="username" >
									<div class="form-control-feedback">
										<i class="icon-user text-muted"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Email: </label>
									<input type="email"  class="form-control" name="email"  placeholder="Email">
									<div class="form-control-feedback">
										<i class="icon-inbox text-muted"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Password: </label>
									<input type="password" class="form-control" required  name="password"  placeholder="Password">
									<!--<input type="hidden"  class="form-control" name="consultant_id11" id="consultant_id11">-->
									<div class="form-control-feedback">
										<i class="icon-key text-muted"></i>
									</div>
								</div>
							</div>
						</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label>Phone: </label>
                                    <input type="text"  class="form-control" name="phone" placeholder="Phone">
                                    <div class="form-control-feedback">
                                        <i class="icon-mobile2 text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Plan: </label>
									<select name="plan" id="plan_id1" required class="form-control">
										<option value="">--Select Plan--</option>
										<?php
										if(!empty($plan))
										{
											foreach($plan as $idx => $plan_rec)
											{
												?>
												<option value="<?php echo $plan_rec['plan_id'];?>"><?php echo $plan_rec['plan_name'];?></option>
												<?php
											}
										}?>
									</select>
									<!--<input type="text" placeholder="Plan" class="form-control" name="plan" id="plan">-->
									<div class="form-control-feedback">
										<i class="icon-list text-muted"></i>
									</div>
								</div>
							</div>
						</div>

						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Expired Date: </label>
									<input type="text" required class="form-control" name="expired_date" id="add_expired_date" placeholder="Expired Date">
									<div class="form-control-feedback">
										<i class="icon-calendar text-muted"></i>
									</div>
								</div>
							</div>
						</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label>OTP Verification Status: </label>
                                    <select name="otp_status" id="otp_status" class="form-control">
                                        <option value="0"<?= ($this->settings->otp_verification == 0 ? ' selected':'')?>>Disabled</option>
                                        <option value="1"<?= ($this->settings->otp_verification == 1 ? ' selected':'')?>>Enabled</option>
                                    </select>
                                    <div class="form-control-feedback">
                                        <i class="icon-user-lock text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="icon-plus2 position-right"></i> Add Owner</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/pickers/datepicker.js"></script>
	<script type="text/javascript">
	

		var today = new Date();
		$('document').ready(function(e){
			$("#expired_date").datepicker({
				startDate : today,
			});

			$("#add_expired_date").datepicker({
				startDate : today,
			});

		});
	</script>

	
	<?php $this->load->view('common/update-password-popup'); ?>

	<!-- /page container -->
</body>



</html>
