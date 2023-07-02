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
    <script type="text/javascript" src="<?= base_url();?>assets/js/plugins/pickers/anytime.min.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.date.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
	   <style type="text/css">
	    	.imlist {
			    background-color:#26a69a;
			    color: #fff;
			}
	    	.select2-container{
	            display:none;
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
<!-- 				<div class="page-header page-header-default">
					<div class="page-header-content">
						<div class="page-title">
							<h4><i class="icon-lan2 position-left"></i> <span class="text-semibold"><?=$title?></span>
								<button type="button" onclick="invoice_back()"class="btn btn-primary btn-sm pull-right">Back </button>
							</h4>
						</div>
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>index.php/Welcome/admindashboard"><i class="icon-home2 position-left"></i>Home</a></li>
							<li><a href="#"><?=$title?></a></li>
						
						</ul>
					</div>
				</div> -->
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">
					<div class="panel panel-white">
<!-- 						<div class="panel-heading" style="text-align:right;">
								<button type="button" class="btn btn-default btn-xs heading-btn"><i class="icon-file-check position-left"></i> Save</button>
								<button type="button" class="btn btn-default btn-xs heading-btn"><i class="icon-printer position-left"></i> Print</button>
						</div> -->
						<form id="invoice_form" class="form-horizontal" action="<?php echo base_url('index.php/Admin/invoice_edit_action')?>" name="add_form" method="post">
						<input type="hidden" name="invoice_id" value="<?php echo $invoice->id?>">
						<input type="hidden" name="admin_id" value="<?php echo $customer_admin->consultant_id ?>">
						<div id="invoice-editable">
							<div class="panel-body no-padding-bottom">
								<div class="row">
									<div class="col-sm-6 content-group">
										<!-- <img src="assets/images/logo_demo.png" class="content-group mt-10" alt="" style="width: 120px;"> -->
			 							<ul class="list-condensed list-unstyled">
			 								<li style="margin-bottom:20px"><h5 style="font-size: 25px;"><?php echo $super->company_name?></h5></li>
											<li><?php echo $super->address?></li>
											<li><?php echo $super->city?></li>
											<li><?php echo $super->phone?></li>
											<li><?php echo $super->fax?></li>
										</ul>
									</div>

									<div class="col-md-6 content-group">
										<div class="invoice-details" >
											<div class="form-group" style="margin-bottom:5px!important">
												<div class="col-md-7 col-md-offset-5" style="text-align:center;">
													<h5 class="text-uppercase text-semibold" style="font-size: 25px;color: #8796C5;">Invoice</h5>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2 col-md-offset-5">Invoice Date: </label>
												<div class="col-md-5">
													<div class="input-group">
														<input type="text" class="form-control" id="anytime-month-numeric" name="create_date" value="<?php echo $invoice->create_date?>" style="min-width:95px">
														<span class="input-group-addon"><i class="icon-calendar3"></i></span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2 col-sm-offset-5">INVOICE: </label>
												<div class="col-md-5">
													<input class="form-control"　type="text" name="invoice_num" value="<?php echo $invoice->invoice_num?>" style="min-width:135px">
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 col-lg-9 content-group">
										<span class="text-muted" style="font-size:20px">Bill To:</span>
                                        	<h6 style="text-align:left"><?php echo $customer_admin->consultant_name?></h6>
				 							<ul class="list-condensed list-unstyled">
				 								<li style="margin-bottom:20px"><h5 class="admin_com_name"><?php echo $customer_admin->name?></h5></li>
												<li class="admin_address"><?php echo $customer_admin->address?></li>
												<li class="admin_city"><?php echo $customer_admin->city?></li>
												<li class="admin_phone"><?php echo $customer_admin->phone?></li>
											</ul>
									</div>
								</div>

							</div>
							<div class="table-responsive">
								<!-- <div class="col-md-1 col-md-offset-11" >
									<button id="new_item" class="btn btn-primary" style="margin:20px 0">
									Add New <i class="fa fa-plus"></i>
									</button>
								</div> -->
							    <table class="table datatable-basic">
							        <thead>
							            <tr>
							                <th>Description</th>
							                <th class="col-sm-1">Tax</th>
							                <th class="col-sm-1">Amount</th>
							                <th class="col-sm-1"></th>
							            </tr>
							        </thead>
							        <tbody>
							        	<?php $index=0;
							        		foreach($items as $item){ ?>
									            <tr>
									                <td>
									                	<input type='text' class='form-control' name='description[<?php echo $index?>]' value="<?php echo $item->description?>" required>
									                </td>
									                <td>
									                	<div class="checkbox checkbox-switchery switchery-xs">
															<label>
																<?php if($item->is_tax == 0):?>
																<input type="checkbox" class="switchery tax_switch" name="tax[<?php echo $index?>]" onclick="tax_change()">
																<?php endif;?>
																<?php if($item->is_tax == 1):?>
																<input type="checkbox" class="switchery tax_switch" name="tax[<?php echo $index?>]" checked="checked" onclick="tax_change()">
																<?php endif;?>
															</label>
														</div>
									                </td>
									                <td>
									                	<input type='number' class='form-control' name='amount[<?php echo $index?>]' min="0" onchange="add_amount(this)" value="<?php echo $item->amount?>" required>
									                </td>
									                <td>
								                		<!-- <ul class="icons-list">
															<li class="text-danger-600" onclick="delete();"><a title="Remove"　href="#"><i class="icon-trash"></i></a></li>
														</ul> -->
									                </td>
									            </tr>
							        	<?php $index++; }?>
							        </tbody>
							    </table>
							</div>

							<div class="panel-body">
								<div class="row invoice-payment">
									<div class="col-sm-4">
										<div class="content-group">
											<h6>Other Comments</h6>
											<textarea name="comment" rows="10" cols="5" class="form-control" placeholder="" style="width:200px"><?php echo $invoice->comment?></textarea>
										</div>
									</div>
									<div class="col-sm-5 col-sm-offset-3">
										<div class="content-group">
											<h6>Total due</h6>
											<div class="table-responsive no-border">
												<table class="table">
													<tbody>
														<tr>
															<th>Sub Total:</th>
															<td class="text-right">$<span class="subtotal_span"></span></td>
														</tr>
														<tr>
															<th>Taxable: <span class="text-regular"></span></th>
															<td class="text-right">$<span class="taxable_span"></span></td>
														</tr>
														<tr>
															<th>Tax Rate<span class="text-regular">(%)</span>: </th>
															<td class="text-right">
																<input type="number" class="form-control" name="tax_rate" value="<?php echo $invoice->tax_rate?>" min="0" max="100" onchange="taxrate_change(this)">
															</td>
														</tr>
														<tr>
															<th>Tax Due:</th>
															<td class="text-right">
																$<span class="taxdue_span"></span>
															</td>
														</tr>
														<tr>
															<th><h6>Total:</h6></th>
															<td class="text-right text-primary">
																<h5 class="text-semibold">
																	$<span class="total_span"></span>
																	<input type="hidden" value="0" name="total_amount">
																</h5>
															</td>
														</tr>

													</tbody>
												</table>
											</div>

											<div class="text-right">
												<button type="submit" class="btn btn-primary btn-labeled"><b><i class="icon-paperplane"></i></b> Save invoice</button>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-md-offset-4">
										<textarea name="footer_comment"　rows="10" cols="10" class="form-control" placeholder="" style="height:100px"><?php echo $invoice->footer_comment?></textarea>
									</div>
								</div>
							</div>
						</div>
						</form>
					</div>
					<div class="panel-heading">
						<div class="col-md-2 pull-right">
							<button type="button" onclick="invoice_back()"class="btn btn-primary btn-sm pull-right">Back </button>
						</div>
					</div>
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
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.bootstrap.js"></script>
	<script type="text/javascript">
		var oTable;
		$(function(){
			//datatable
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                dom: '<"datatable-scroll"t>',
                drawCallback: function () {
                    $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
                },
                preDrawCallback: function() {
                    $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
                }
            });
            // Basic datatable
            oTable = $('.datatable-basic').DataTable({
    	        columnDefs: [{
		            orderable: false,
		            targets: [0,1,2,3]
		        }],
            });
            // $('#new_item').click(function (e) {
	        //     e.preventDefault();
	        //     var added_index = oTable.data().length;
	        //     var datas = new Array();
	        //     datas[0] = "<input type='text' class='form-control' name='description["+added_index+"]' required>";
	        //     datas[1] = "<div class='checkbox checkbox-switchery switchery-xs'>"+
			// 					"<label>"+
			// 						"<input type='checkbox' class='switchery' name='tax["+added_index+"]' onclick='tax_change()'>"+
			// 					"</label>"+
			// 				"</div>";
	        //     datas[2] = "<input type='number' class='form-control' name='amount["+added_index+"]' onchange='add_amount()' value='0' required>";
	        //     datas[3] = "<ul class='icons-list'>"+
	        //     				"<li class='text-danger-600' onclick='delete();'>"+
			// 						"<a title='Remove'　href='#'><i class='icon-trash'></i></a></li>"+
			// 				"</ul>";
	        //     oTable.row.add(datas);
	        //     oTable.draw();
			//     if (Array.prototype.forEach) {
			//         var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
			//         elems.forEach(function(html) {
			//             var switchery = new Switchery(html);
			//         });
		    // 	}
        	// 	$(".switch").bootstrapSwitch();

        	// });
            
            $('.datatable-scroll-y').DataTable({
                autoWidth: true,
                scrollY: 300
            });
    		
            oTable.on('click', 'a[title="Remove"]', function (){
	            var nRow = $(this).parents('tr')[0];
	            oTable.row(nRow).remove().draw();
	            add_amount();
			});
			//end datatable

    		//datepicker js
    	    $("#anytime-month-numeric").AnyTime_picker({
		        format: "%Z-%m-%d"
    		});
    		//switch
		    if (Array.prototype.forEach) {
		        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
		        elems.forEach(function(html) {
		            var switchery = new Switchery(html);
		        });
		    }
    		$(".switch").bootstrapSwitch();
    		add_amount();

		});
		function add_amount(){
			var len = oTable.data().length;
			var rowamount = 0,
				subtotal = 0,
				taxable = 0,
				taxrate = 0,
				taxdue = 0,
				total_amount;
			for(i=0; i<len; i++){
				rowamount = Number($('#invoice_form input[name="amount['+i+']"]').val());
				subtotal = subtotal + rowamount;
				tax = $('#invoice_form input[name="tax['+i+']"]')[0].checked;
				if(tax){
					taxable = taxable + rowamount;
				}
			}
			taxrate = $('#invoice_form input[name="tax_rate"]').val();
			$('.taxable_span').html(taxable);
			$('.taxdue_span').html(taxable*taxrate/100);
			$('.subtotal_span').html(subtotal);
			$('.total_span').html(subtotal+taxable*taxrate/100);
			$('#invoice_form input[name="total_amount"]').val(subtotal+taxable*taxrate/100);
		}
		function tax_change(e){
			add_amount();
		}
		function taxrate_change(e){
			add_amount();
		}
		function invoice_back(){
			location.href = "<?php echo base_url('index.php/Admin/invoice');?>";
		}
	</script>
</body>
</html>
