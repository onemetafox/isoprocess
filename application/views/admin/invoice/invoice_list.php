<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="<?= base_url(); ?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/pages/form_bootstrap_select.js"></script>
    <!-- /core JS files -->

    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/daterangepicker.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
    <!--<script src="http://malsup.github.com/jquery.form.js"></script>-->
	    <script type="text/javascript">
        $(function() {
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                },
                drawCallback: function () {
                    $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
                },
                preDrawCallback: function() {
                    $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
                }
            });
            // Basic datatable
            $('.datatable-basic').DataTable({
                buttons: {
                    dom: {
                        button: {
                            className: 'btn btn-primary'
                        }
                    },
                    buttons: [
                        {extend: 'csv'}
                    ]
                }
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
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });
        });
    </script>
   <style type="text/css">
    	.imlist {
		    background-color:#26a69a;
		    color: #fff;
		}
    	.select2-container{
            display:none;
        }
        .paylist {
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
								<button type="button" class="btn btn-primary btn-sm pull-right" onclick="add()">New Invoice <i class="icon-lan2 position-right"></i></button>
							</h4>
						</div>
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>index.php/Welcome/admindashboard"><i class="icon-home2 position-left"></i>Home</a></li>
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
							Administrator Successfully Deleted.. 
				        </div>
                      <?php  $this->session->unset_userdata('message'); } ?>

                      <?php if($this->session->flashdata('message')=='update_success') { ?>
                      	  <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							Administrator Successfully Updated.. 
				        </div>
                      <?php  $this->session->unset_userdata('message'); } ?>

                    <div class="panel panel-white">
                    	<div class="panel-body text-left" style="padding-bottom: 0px;">
                    		<form name="filter_form" action="<?php echo base_url('index.php/Admin/invoice')?>" method="post">
							<div class="col-md-2">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><i class="icon-calendar22"></i></span>
										<input type="text" class="form-control daterange-single" name="filter_start" value="<?php echo $start_date?>">
									</div>
								</div>
	                        </div>
							<div class="col-md-2">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><i class="icon-calendar22"></i></span>
										<input type="text" class="form-control daterange-single1" name="filter_end" value="<?php echo $end_date?>">
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Filter</button>
								</div>
							</div>
                        	</form>
                        </div>
                    </div>  
                    <div class="row">
                    	<div class="col-sm-6 col-md-4">
							<div class="panel panel-body panel-body-accent">
								<div class="media no-margin">
									<div class="media-left media-middle">
										<i class="icon-coin-euro icon-3x text-orange-400"></i>
									</div>

									<div class="media-body text-right">
										<h3 class="no-margin text-semibold">$<?php echo $total_amount?></h3>
										<span class="text-uppercase text-size-mini text-muted">Total Invoice Amount</span>
									</div>
								</div>
							</div>
						</div>
                    	<div class="col-sm-6 col-md-4">
							<div class="panel panel-body panel-body-accent">
								<div class="media no-margin">
									<div class="media-left media-middle">
										<i class="icon-coin-euro icon-3x text-danger-400"></i>
									</div>

									<div class="media-body text-right">
										<h3 class="no-margin text-semibold">$<?php echo $total_open_amount?></h3>
										<span class="text-uppercase text-size-mini text-muted">Total Open Amount</span>
									</div>
								</div>
							</div>
						</div>
                    	<div class="col-sm-6 col-md-4">
							<div class="panel panel-body panel-body-accent">
								<div class="media no-margin">
									<div class="media-left media-middle">
										<i class="icon-coin-euro icon-3x text-success-400"></i>
									</div>

									<div class="media-body text-right">
										<h3 class="no-margin text-semibold">$<?php echo $total_paid_amount?></h3>
										<span class="text-uppercase text-size-mini text-muted">Total Paid Amount</span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Basic datatable -->
					<div class="panel panel-flat" style="overflow:auto;">
						<table class="table datatable-basic">
							<thead>
							<tr>
								<th>No</th>
								<th>Invoice Date</th>
								<th>Company Name</th>
								<th>Description</th>
								<th>Invoie#</th>
								<th>Due Date</th>
								<th>Payment Type</th>
								<th>Amount</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody>
							<?php $count=1;
							foreach ($invoices as $invoice) { ?>
								<tr>
									<td><?=$count?></td>
									<td><?=$invoice->create_date?></td>
									<td><?=$invoice->company_name?></td>
									<td><?=$invoice->description?></td>
									<td><?=$invoice->invoice_num?></td>
									<td><?=$invoice->due_date?></td>
									<td><?=$invoice->payment_type?></td>
									<td>$<?=$invoice->amount?></td>
									<td><span class="label <?php echo $invoice->status=='pending'?'label-info':'label-success'?>"><?= @$invoice->status?></span></td>
									<td>
										<ul class="icons-list">
											<?php if($invoice->status == 'paid'):?>
												<li><button ype="button" onclick="pending(<?php echo $invoice->id?>);" class="btn btn-primary">Pending<a href="#"></a></button></li>
											<?php endif;?>
											<li><button type="button" onclick="view(<?php echo $invoice->id?>);" class="btn btn-primary">View</button></li>
											<?php if($invoice->status != 'paid'):?>
												<li><button ype="button" onclick="edit(<?php echo $invoice->id?>);" class="btn btn-primary">Edit<a href="#"></a></button></li>
											<?php endif;?>
											<li><button ype="button" onclick="invoice_delete(<?php echo $invoice->id?>);" class="btn btn-primary">Delete<a href="#"></a></button></li>
											<?php if($invoice->status == 'pending'):?>
												<li><button ype="button" onclick="paid(<?php echo $invoice->id?>);" class="btn btn-primary">Pay<a href="#"></a></button></li>
											<?php endif;?>
										</ul>
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


	<script type="text/javascript">
		function pending (val) {
			location.href = "<?php echo base_url('index.php/Admin/invoice_pending/')?>"+val;
		}
		function paid(val) {
			location.href = "<?php echo base_url('index.php/Admin/invoice_paid/')?>"+val;
		}
		function view(val){
			location.href = "<?php echo base_url('index.php/Admin/invoice_view/')?>"+val;
		}
		function add(){
			location.href="<?php echo base_url('index.php/Admin/invoice_add')?>"
		}
		function edit(val){
			location.href = "<?php echo base_url('index.php/Admin/invoice_edit/')?>"+val;
		}
		function pay(val){
			location.href = "<?php echo base_url('index.php/Admin/invoice_pay/')?>"+val;
		}
		function invoice_delete(val){
			var dialog = bootbox.dialog({
				title: 'Confirmation',
				message: "<h4>Are You Sure want to delete ?</h4> <br><p>Warning ! you will be loss All records..</p>",
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
							window.location.href= "<?php echo base_url('index.php/Admin/invoice_delete/')?>"+val;
						}
					}
				}
			});
		}
	</script>
	<script type="text/javascript">
		$(function(){
			init_daterange_c();
			$('.daterange-single').val("<?php echo $start_date?>");
			$('.daterange-single1').val("<?php echo $end_date?>");
		})
        function init_daterange_c(){
		    $('.daterange-single').daterangepicker({ 
		        singleDatePicker: true,
                locale: {
            		format: 'YYYY-MM-DD'
       			}
		    });
		    $('.daterange-single1').daterangepicker({ 
		        singleDatePicker: true,
                locale: {
            		format: 'YYYY-MM-DD'
       			}
		    });
    }
	</script>

		<?php $this->load->view('common/update-password-popup'); ?>

</body>



</html>
