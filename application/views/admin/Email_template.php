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
   <script type="text/javascript">
     /*   $(function() {
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: '150px',
                    targets: [1,2,3,4]
                }],
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Search by Name..',
                  
                },
                drawCallback: function () {
                    $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
                },
                preDrawCallback: function() {
                    $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
                }
            });

            // Basic datatable
            $('.datatable-basic-2').DataTable({
                order: [5,"desc"]
            });

            // Alternative pagination
            $('.datatable-pagination').DataTable({
                pagingType: "simple",
                language: {
                    paginate: {'next': 'Next &rarr;', 'previous': '&larr; Prev'}
                }
            });

            // Datatable with saving state
            $('.datatable-save-state').DataTable({
                stateSave: true
            });

            // Scrollable datatable
            $('.datatable-scroll-y').DataTable({
                autoWidth: true,
                scrollY: 300
            });

            // External table additions
            // ------------------------------

            // Enable Select2 select for the length option
           /* $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });*/
      /*  }); */

        $('#email__template').dataTable({
        // parameters
			});
    </script>
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
								<button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modal_theme_primary_add">Add Email Template<i class="icon-lan2 position-right"></i></button>
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
							<span class="text-semibold">Thank you!</span>Email Template Successfully created.. 
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
							Email Template Successfully Deleted.. 
				        </div>
                      <?php  $this->session->unset_userdata('message'); } ?>

                      <?php if($this->session->flashdata('message')=='update_success') { ?>
                      	  <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							Email Template Updated.. 
				        </div>
                      <?php $this->session->unset_userdata('message');  } ?>
                    <?php if($this->session->flashdata('phone_response')) { ?>
                        <div class="alert alert-danger alert-styled-right alert-arrow-right alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            <?= $this->session->flashdata('phone_response')['message'] ?>
                        </div>
                    <?php $this->session->unset_userdata('phone_response');  } ?>

					 <!-- Basic datatable -->
                <div class="panel panel-flat" id="ptn" style="overflow:auto;">
                    <table class="table  table-bordered datatable-basic" id="email__template">

                        <thead>

                        <tr>
                            <th style="display: none;">ID</th>
                            <th>Template Name </th>
                            <th>Subject </th>
                            <th style="display: none;">description </th>
                            <th>Action </th>
                           <th style="display: none;">Action </th> 
                           <th style="display: none;">Action </th> 
                           
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count=1;
                      foreach ($Email_template as $val) {
                         ?>
                             <tr>
                             	<td style="display: none;"><?php echo $count; ?></td>
                                <td><?php echo $val->template_name; ?></td>
                                <td><?php echo $val->subject; ?></td>
                                <td style="display: none;"><?php echo $val->description; ?></td>
                              
                                <td><ul class="icons-list">
											<li class="text-primary-600" onclick="edit_temp(<?=$val->id?>);"><a href="#" data-toggle="modal" data-target="#edit_email_temp"><i class="icon-pencil7"></i></a></li>
											<!-- <li class="text-danger-600" onclick="del_temp(<?=$val->id?>);"><a href="#" id="<?=$val->id?>" class="delete" ><i class="icon-trash"></i></a></li> -->
										</ul>
									</td>
								<td style="display: none;"></td>
								<td style="display: none;"></td> 
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

<!-- Delete Template -->
<div class="modal fade" id="modal_schedule_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?php echo base_url();?>index.php/Admin/Del_email_template" method="post" name="add_employee">
    <div class="modal-content">  
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="exampleModalLabel">Delete Template</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <input type="hidden"  class="form-control" name="email_id_del" id="email_id_del" value="" required>
      <div class="modal-body">
          <h4 class="modal-title w-100 center">Are you sure?</h4>
        Do you really want to delete these records? This Template cannot be undo.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </form>
  </div>
</div>
<!---------------End------------->
	<!-- Primary modal -->
	<div id="edit_email_temp" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title"><i class="icon-home2 position-right"></i> Edit Template</h6>
				</div>
				<div class="modal-body">
					<form action="<?php echo base_url();?>index.php/Admin/update_email_template"  method="post">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Template Name: </label>
									<input type="text" placeholder="Template Name" required  class="form-control" name="template_name" id="template_name" readonly="readonly">
									<input type="hidden" placeholder="Process Name" class="form-control" name="email__id" id="email__id" value="" required>
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
									<input type="text" placeholder="Subject" required class="form-control" name="subject" id="subject">
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
									  <textarea id="tinyedit" name="desc"></textarea>
									</div>
									<div class="form-control-feedback">
										<i class="icon-inbox text-muted"></i>
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
					<h6 class="modal-title"><i class="icon-home2 position-right"></i> Add Template</h6>
				</div>
				<div class="modal-body">
					<form action="<?php echo base_url();?>index.php/Admin/Add_email_template"  method="post">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group has-feedback">
									<label>Template Name: </label>
									<input type="text" placeholder="Template Name" required  class="form-control" name="template_name" >
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
									<input type="text" placeholder="Subject" required class="form-control" name="subject" >
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
									  <textarea id="tiny" name="desc"></textarea>
									</div>
									<div class="form-control-feedback">
										<i class="icon-inbox text-muted"></i>
									</div>
								</div>
							</div>
						</div>   
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="icon-plus2 position-right"></i> Submit</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/pickers/datepicker.js"></script>
  <script src="https://cdn.tiny.cloud/1/1rmdcv53rvsgnqc36sq7u38nbkeqz75uuoxihvyezw14qxfx/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      selector: 'textarea#tiny',
      plugins: 'a11ychecker advcode casechange export formatpainter image editimage linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tableofcontents tinycomments tinymcespellchecker',
      toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter image editimage pageembed permanentpen table tableofcontents',
      toolbar_mode: 'floating',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
    });
        tinymce.init({
      selector: 'textarea#tinyedit',
      plugins: 'a11ychecker advcode casechange export formatpainter image editimage linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tableofcontents tinycomments tinymcespellchecker',
      toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter image editimage pageembed permanentpen table tableofcontents',
      toolbar_mode: 'floating',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
    });
  </script>
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


/****************Edit Email Template *********************/
    function edit_temp(val) {
    var email_id = val;
   // alert(email_id);
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/Admin/edit_email_template",
        data: {'pa_id': email_id},
        success: function (data) {
            //console.log(data);
            var datas = JSON.parse(data);
            $('#template_name').val(datas.edit_single_temp[0].template_name);
            $('#subject').val(datas.edit_single_temp[0].subject);
            $('#email__id').val(datas.edit_single_temp[0].id);
            var tinyedit = datas.edit_single_temp[0].description;
             tinyMCE.activeEditor.setContent(tinyedit);
            }
        });
    }
    /**********End**************/
    /************Delete process*******************/
    function del_temp(val) {
        var pa_id = val;
        $('#modal_schedule_delete').modal('show');
        $('#email_id_del').val(pa_id);
    }
    /*****************End************************/
	</script>

	
	<?php $this->load->view('common/update-password-popup'); ?>

	<!-- /page container -->
</body>



</html>
