<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?=$title?></title>
	
	<link href="<?=base_url(); ?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url(); ?>assets/css/colors.css" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script> 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" type="text/css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

	<style type="text/css">
    	.smlist {
		    background-color:#26a69a;
		    color: #fff;
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
                             <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal">New Plan</button>
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
					<div class="panel panel-flat" style="overflow:auto;">
						<table class="table datatable-basic">
							<thead>
								<tr>
								    <th>No</th>
									<th>Key</th>
									<th>Type</th>
									<th>Title</th>
									<th>Content</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $count=1;
                                 foreach ($alerts as $alert) { ?>
								<tr>
								    <td><?=$count?></td>
									<td><?=$alert->key?></td>
									<td><?=$alert->type==1?"Notification":"Place Holder"?></td>
									<td><?=$alert->title?> </td>
									<td><?=$alert->content?> </td>
									<td>
										<ul class="icons-list">
											<li class="text-primary-600" onclick="edit(<?=$alert->id?>);"><a href="#"><i class="icon-pencil7"></i></a></li>
											<li class="text-danger-600"><a href="#" id="<?=$alert->id?>" class="delete" ><i class="icon-trash"></i></a></li>

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
	<div id="form_modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title"><i class="icon-lan2 position-right"></i> Edit  Plan</h6>
				</div>
				<form method="post">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Title: </label>
									<input type="text"  class="form-control" name="title" id="title">
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Content: </label>
									<textarea class="form-control" name="content" id="content" rows="30" ></textarea>
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Key: </label>
									<input type="text" class="form-control" name="key" id="key">
									<input type="hidden"  class="form-control" name="id" id="id">
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Type: </label>
									<select class="form-control" name="type" id="type">
										<option value="1">Notification</option>
										<option value="2">Place Holder</option>
								 	</select>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary add-btn"><i class="icon-plus2 position-right"></i> Edit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$(".pull-right").on('click', function(e) {
			$("form")[0].reset();
			$('#form_modal').modal('show'); 

		})
		$(".add-btn").on('click', function(e){
			if($("#key").val() == ""){
				toastr.error("Key is required");
				return;
			}
			if($("#title").val() == ""){
				toastr.error("Title is required");
				return;
			}
			
			const data = {
				title: $('#title').val(),
				key: $('#key').val(),
				content: $("#content").val(),
				type: $('#type').val(),
				id: $('#id').val()
			}
			$.ajax({
				type: "POST",
				url: "<?= base_url(); ?>index.php/admin/save_alert",
				data:data,
				success: function(res) {
					var result = JSON.parse(res)
					if(result.success){
						toastr.success(result.msg);
						window.location.href="<?php echo base_url();?>index.php/Admin/alert/";
					}else{
						toastr.error(result.msg);
					}
				},
				error: function(er){
					toastr.error("Server internal error");
				}
			});

		})
		$('body').on('click','.delete' ,function(e){
			var id = $(this).attr('id');
			var dialog = bootbox.dialog({
			title: 'Confirmation',
			message: "<h4>Are You Sure ?</h4>",
			size: 'small',
			buttons: {
				cancel: {
					label: "Cancel",
					className: 'btn-danger',
					callback: function(){
						dialog.modal('hide');
					}
				},
			
				ok: {
					label: "OK",
					className: 'btn-success',
					callback: function(){
						window.location.href="<?php echo base_url();?>index.php/Admin/delete_alert/"+id;
					}
				}
			}
			});
		});
		function edit(val){
			$('#form_modal').modal('show'); 
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>index.php/admin/find_alert",
				data:{ 'id' : val},
				success: function(data) {
					var datas = $.parseJSON(data)
					$("#title").val(datas.title);
					$("#type").val(datas.type);
					$("#content").val(datas.content);
					$("#key").val(datas.key);
					$("#id").val(datas.id);
				}
			});
		}
	</script>
	<?php $this->load->view('common/update-password-popup'); ?>
</body>
</html>
