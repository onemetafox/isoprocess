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
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script> -->
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/core/app.js"></script>
	<!-- <script type="text/javascript" src="<?=base_url(); ?>assets/js/pages/datatables_basic.js"></script> -->
 <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script> 

    <style type="text/css">
    	.invlist {
		    background-color: rgba(75, 171, 191, 0.5);
		    color: #fff;
		}
    </style> 
</head>

<body class="navbar-top">
	<!-- Main navbar -->
	<?php $this->load->view('consultant/main_header.php'); ?>
	<!-- /main navbar -->

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<?php $this->load->view('consultant/sidebar'); ?>
			<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Page header -->
				<div class="page-header page-header-default">
					<div class="page-header-content">
						<div class="page-title">
							<h4><?php
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
								<img src="<?php echo base_url(); ?>uploads/logo/<?=$logo?>" style="height:50px;"> <span class="text-semibold"><?=$title?></span>
                            <!--  <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modal_theme_primary">New Plan <i class="icon-lan2 position-right"></i></button> -->
							</h4>
						</div>
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
                    <?php $this->session->unset_userdata('message');  } ?>
                     
                        <?php if($this->session->flashdata('message')=='failed') { ?>
                      	 <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							<span class="text-semibold">Oppps!</span>Something Went Wrong Please try again.
				        </div>
                      <?php  $this->session->unset_userdata('message'); } ?>
                      <?php if($this->session->flashdata('message')=='success_del') { ?>
                      	  <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							Plan Successfully Deleted.. 
				        </div>
                      <?php  $this->session->unset_userdata('message'); } ?>

                      <?php if($this->session->flashdata('message')=='update_success') { ?>
                      	  <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							Plan Successfully Updated.. 
				        </div>
                      <?php $this->session->unset_userdata('message');  } ?>
					<!-- Basic datatable -->
					<div class="panel panel-flat" style="overflow:auto;">
						<table class="table datatable-basic" >
							<thead>
							<tr>
								<th>No</th>
								<th>Pay Date</th>
								<th>Description</th>
								<th>Amount</th>
								<th>Status</th>
							</tr>
							</thead>
							<tbody>
							<?php $count=1;
							$consultant_id = $this->session->userdata('consultant_id');
							$consultant = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row();

							foreach ($payment as $payments) { ?>
								<?php
								$rowdata=@$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$payments->consultant_id'")->row();

								$plandata=@$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$payments->purchase_plan_id'")->row();

								?>
								<tr>
									<td><?php echo $count; ?></td>
									<td><?php echo $payments->date_time; ?></td>
									<td><?php echo $plandata->plan_name; ?></td>
									<td><?php echo $payments->total_amount; ?></td>
									<td><?=$payments->payment_status?></td>
								</tr>
								<?php $count++; } ?>
							<tr>
								<td colspan="2"></td>
								<td colspan="1">Your account will be expire in <font style="color:red;"><?php echo $consultant->expired ; ?></font></td>
								<td colspan="1"></td>
								<td colspan="1"><font style="color:red;">Pending</font></td>
							</tr>
							</tbody>
						</table>
					</div>
					<!-- /basic datatable -->

					<!-- Footer -->
					<?php $this->load->view('consultant/footer'); ?>
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
