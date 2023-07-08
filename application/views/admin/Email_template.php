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

	<script type="text/javascript" src="<?=base_url(); ?>assets/js/core/app.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.18.0/ckeditor.js" integrity="sha512-woYV6V3QV/oH8txWu19WqPPEtGu+dXM87N9YXP6ocsbCAH1Au9WDZ15cnk62n6/tVOmOo0rIYwx05raKdA4qyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" type="text/css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

   <style type="text/css">
		.toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
		.toggle.ios .toggle-handle { border-radius: 20px; }
		.email_1 {
				background-color:#26a69a;
				color: #fff;
			}

		.unknownlocation {
			color: red;
		}.samelocation {
			color: green;
		}
		.tox.tox-silver-sink.tox-tinymce-aux {
			display: none;
		}
    </style> 
</head>


<body class="navbar-top">
	<?php $this->load->view('Admin/main_header.php'); ?>
	<div class="page-container">
		<div class="page-content">
			<?php $this->load->view('Admin/sidebar'); ?>
			<div class="content-wrapper">
				<div class="page-header page-header-default">
					<div class="page-header-content">
						<div class="page-title">
							<h4><i class="icon-lan2 position-left"></i> <span class="text-semibold"><?=$title?></span>
								<button type="button" class="btn btn-primary btn-sm pull-right" onclick="addEmail()">Add Email Template<i class="icon-lan2 position-right"></i></button>
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
				<div class="content">
					<div class="panel panel-flat" id="ptn" style="overflow:auto;">
						<table class="table  table-bordered datatable-basic" id="email__template">
							<thead>
							<tr>
								<th style="display: none;">ID</th>
								<th>Template Name </th>
								<th>Subject </th>
								<th>Action </th>
							</tr>
							</thead>
							<tbody>
							<?php $count=1;
								foreach ($emails as $val) {
							?>
								<tr>
									<td style="display: none;"><?php echo $count; ?></td>
									<td><?php echo $val->action; ?></td>
									<td><?php echo $val->subject; ?></td>
									<td>
										<ul class="icons-list">
											<li class="text-primary-600" onclick="editEmail('<?=$val->id?>');"><i class="icon-pencil7"></i></a></li>
										</ul>
									</td>
								</tr>
								<?php $count++; } ?>
							</tbody>
						</table>
					</div>
					<?php $this->load->view('Admin/footer'); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="email_modal" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title"><i class="icon-home2 position-right"></i> Edit Email Template</h6>
				</div>
				<div class="modal-body">
					<form method="post">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Template Name: </label>
									<input type="text" placeholder="Template Name" required  class="form-control" name="action">
									<input type="hidden" placeholder="Process Name" class="form-control" name="id" required>
									<div class="form-control-feedback">
										<i class="icon-list text-muted"></i>
									</div>
								</div>
							</div>
						</div>

						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Subject: </label>
									<input type="text" placeholder="Subject" required class="form-control" name="subject">
									<div class="form-control-feedback">
										<i class="icon-user text-muted"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Description: </label>
									<div>
										<textarea class="ckeditor form-control" name="message" rows="30" ></textarea>
									</div>
								</div>
							</div>
						</div>   
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="icon-plus2 position-right"></i> Save</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
			$("form").submit(function (event) {
				var formData = {
					message: CKEDITOR.instances['message'].getData(),
					id: $("input[name='id']").val(),
					action: $("input[name='action']").val(),
					subject: $("input[name='subject']").val(),
				};

				$.ajax({
					type: "POST",
					url: "<?= base_url()?>index.php/Admin/saveEmail",
					data: formData,
					dataType: "json",
					encode: true,
				}).done(function (data) {
					if(data.success){
						toastr.success(data.msg);
					}else{
						toastr.error(data.msg);
					}
					$("#email_modal").modal('hide');
				});
				event.preventDefault();
			});
		});
		function editEmail(id) {
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>index.php/Admin/getEmail",
				data: {'id': id},
				success: function (data) {
					$('input[name="action"]').val(data.action);
					$('input[name="subject"]').val(data.subject);
					$('input[name=id]').val(data.id);
					CKEDITOR.instances['message'].setData(data.message);
					$("#email_modal").modal('show');
				}
			});
		}
		function addEmail(){
			CKEDITOR.instances['message'].setData('');
			$("form")[0].reset();
			$("#email_modal").modal('show');
		}
	</script>
	
	<?php $this->load->view('common/update-password-popup'); ?>
</body>
</html>
