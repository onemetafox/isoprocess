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

		<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.18.0/ckeditor.js" integrity="sha512-woYV6V3QV/oH8txWu19WqPPEtGu+dXM87N9YXP6ocsbCAH1Au9WDZ15cnk62n6/tVOmOo0rIYwx05raKdA4qyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" type="text/css">
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

		<style type="text/css">
			.policy {
				background-color:#26a69a;
				color: #fff;
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
							<div class="page-title">
								<h4><i class="icon-lan2 position-left"></i> <span class="text-semibold"><?=$title?></span>
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

					<!-- Content area -->
					<div class="content">
						
						<form>
							<textarea class="ckeditor form-control" name="policy" rows="30" ><?= $setting->policy?$setting->policy:''; ?></textarea>
							<br>
							<input type="submit" value = "<?= $setting->policy?"Update Policy":'Add Policty'; ?>" class="btn btn-primary">
						</form>
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
		<script>
			$(document).ready(function () {
				$("form").submit(function (event) {
					var formData = {
						policy: CKEDITOR.instances['policy'].getData()
					};

					$.ajax({
						type: "POST",
						url: "<?= base_url()?>index.php/Admin/updatePolicy",
						data: formData,
						dataType: "json",
						encode: true,
					}).done(function (data) {
						if(data.status){
							toastr.success(data.msg);
						}else{
							toastr.error(data.msg);
						}
					});

					event.preventDefault();
				});
			});
		</script>
	</body>
</html>
