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

				<!-- /page header -->


				<!-- Content area -->
				<div class="content">
					<div class="page-header page-header-default" style="margin-bottom:5px">
						<div class="page-header-content">
							<div class="page-title" style="padding-bottom:10px;padding-top:10px">
								<h4><i class="icon-lan2 position-left"></i> <span class="text-semibold"><?=$title?></span>
									<button type="button" onclick="invoice_back()"class="btn btn-primary btn-sm pull-right" style="margin-left:10px">Back </button>
									<a title="Download" type="button" class="btn btn-primary btn-sm pull-right" onclick="printDiv('ptn')" ><i class="icon-download " aria-hidden="true"></i>Print</a>
									<a title="Download" type="button" class="btn btn-primary btn-sm pull-right" onclick="printPdf('ptn')" style="margin-right:10px"><i class="icon-download " aria-hidden="true"></i>Pdf</a>
								</h4>
							</div>
						</div>
					</div>
					<div class="panel panel-white" id="ptn">
<!-- 						<div class="panel-heading" style="text-align:right;">
								<button type="button" class="btn btn-default btn-xs heading-btn"><i class="icon-file-check position-left"></i> Save</button>
								<button type="button" class="btn btn-default btn-xs heading-btn"><i class="icon-printer position-left"></i> Print</button>
						</div> -->
						<form id="invoice_form" class="form-horizontal" action="<?php echo base_url('index.php/Admin/invoice_edit_action')?>" method="post">
						<input type="hidden" id="view_invoice_id"　name="invoice_id" value="<?php echo $invoice->id?>">
						<input type="hidden" name="admin_id" value="<?php echo $customer_admin->consultant_id?>">
						<div id="invoice-editable">
							<div class="panel-body no-padding-bottom">
								<div style="row">
									<div class="col-sm-6 content-group">
										<!-- <img src="assets/images/logo_demo.png" class="content-group mt-10" alt="" style="width: 120px;"> -->
			 							<ul class="list-condensed list-unstyled" style="padding-left: 0;list-style: none;" >
			 								<?php if(isset($super->company_name) && $super->company_name != '') {?>
			 									<li><h5 style="font-size: 25px;">[Company Name]:<?php echo $super->company_name?></h5></li>
			 								<?php } ?>
			 								<?php if(isset($super->address) && $super->address != '') {?>
												<li>[Stress Address]:<?php echo $super->address?></li>
											<?php } ?>
											<?php if(isset($super->city) && $super->city != '') {?>
												<li>[City ST ZIP]:<?php echo $super->city?></li>
											<?php } ?>
											<?php if(isset($super->phone) && $super->phone != '') {?>
												<li>[Phone]:<?php echo $super->phone?></li>
											<?php } ?>
											<?php if(isset($super->fax) && $super->fax != '') {?>
												<li>[Fax]:<?php echo $super->fax?></li>
											<?php } ?>
										</ul>
										<div style="background:#3e4f83;font-weight: 700;">
											<span class="text-muted" style="font-size:20px;color:white">BILL TO:</span>
										</div>
                                    	<?php if(isset($customer_admin->consultant_name) && $customer_admin->consultant_name != '') {?>
                                    		<h6 style="margin:0;text-align:left">[Name]:<?php echo $customer_admin->consultant_name?></h6>
                                    	<?php } ?>
                                    	
			 							<ul class="list-condensed list-unstyled" style="padding-left: 0;list-style: none;">
			 								<?php if(isset($customer_admin->consultant_name) && $customer_admin->name != '') {?>
			 									<li><h5 style="margin:0" class="admin_com_name">[Company Name]:<?php echo $customer_admin->name?></h5></li>
											<?php } ?>
											<?php if(isset($customer_admin->consultant_name) && $customer_admin->address != '') {?>
												<li class="admin_address">[Stress Address]:<?php echo $customer_admin->address?></li>
											<?php } ?>
											<?php if(isset($customer_admin->consultant_name) && $customer_admin->city != '') {?>
												<li class="admin_city">[City ST ZIP]:<?php echo $customer_admin->city?></li>
											<?php } ?>
											<?php if(isset($customer_admin->consultant_name) && $customer_admin->phone != '') {?>
												<li class="admin_phone">[Phone]:<?php echo $customer_admin->phone?></li>
											<?php } ?>
										</ul>
									</div>
									<div class="col-sm-6 content-group">
										<div class="invoice-details">
											<h5 class="text-uppercase text-semibold" style="font-size: 25px;color: #8796C5;">Invoice</h5>
											<ul class="list-condensed list-unstyled" style="padding-left: 0;list-style: none;">
												<li>Invoice Date: <span class="text-semibold"><?php echo $invoice->create_date?></span></li>
												<li>INVOICE: <span class="text-semibold"><?php echo $invoice->invoice_num?></span></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive">
							    <table class="table datatable-basic">
							        <thead>
							            <tr>
							                <th>Description</th>
							                <th class="col-sm-1">Taxed</th>
							                <th class="col-sm-1">Amount</th>
							            </tr>
							        </thead>
							        <tbody>
							        	<?php $index=0;
							        		foreach($items as $item){ ?>
									            <tr>
									                <td>
									                	<?php echo $item->description?>
									                </td>
									                <td>
														<?php if($item->is_tax == 0):?>
														<i class="text-danger glyphicon glyphicon-remove"></i>
														<?php endif;?>
														<?php if($item->is_tax == 1):?>
														<i class="text-success icon-circle"></i>
														<?php endif;?>
									                </td>
									                <td>
									                	<?php echo $item->amount?>
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
											<textarea name="comment" readonly rows="10" cols="10" class="form-control" placeholder=""><?php echo $invoice->comment?></textarea>
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
															<td class="text-right">$<span class="subtotal_span"><?php echo $amount_list['subtotal']?></span></td>
														</tr>
														<tr>
															<th>Taxable: <span class="text-regular"></span></th>
															<td class="text-right">$<span class="taxable_span"><?php echo $amount_list['taxable']?></span></td>
														</tr>
														<tr>
															<th>Tax Rate(%): </th>
															<td class="text-right">
																<?php echo $invoice->tax_rate?>
															</td>
														</tr>
														<tr>
															<th>Tax Due:</th>
															<td class="text-right">
																$<span class="taxdue_span"><?php echo $amount_list['taxdue']?></span>
															</td>
														</tr>
														<tr>
															<th><h6>Total:</h6></th>
															<td class="text-right text-primary">
																<h5 class="text-semibold">
																	$<span class="total_span"><?php echo $invoice->amount?></span>
																</h5>
															</td>
														</tr>
													</tbody>
												</table>
											</div>

										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-md-offset-4">
										<textarea name="footer_comment"　rows="10" cols="10" class="form-control" placeholder="" style="height:100px"><?php echo $invoice->footer_comment?></textarea>
									</div>
								</div>
<!-- 								<p class="text-muted text-center" style="font-size:14px">If you have any question about this invoice, please contact</p>
								<p class="text-muted text-center"><?php echo $super->username.', '.$super->phone.', '.$super->email?></p>
								<h4 class="text-center" style="font-family:cursive">Thank You For Your Business!</h4> -->
							</div>
						</div>
						</form>
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
	<form name="pdf_form"　style="display:none" action="<?php echo base_url('index.php/Admin/invoice_pdf')?>" method="post">
		<input type="hidden" name="view_invoice_id" id="view_invoice_id" value="<?php echo $invoice->id?>">
	</form>
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
            $('#new_item').click(function (e) {
	            e.preventDefault();
	            var added_index = oTable.data().length;
	            var datas = new Array();
	            datas[0] = "<input type='text' class='form-control' name='description["+added_index+"]' required>";
	            datas[1] = "<div class='checkbox checkbox-switchery switchery-xs'>"+
								"<label>"+
									"<input type='checkbox' class='switchery' name='tax["+added_index+"]' onclick='tax_change()'>"+
								"</label>"+
							"</div>";
	            datas[2] = "<input type='number' class='form-control' name='amount["+added_index+"]' onchange='add_amount()' value='0' required>";
	            datas[3] = "<ul class='icons-list'>"+
	            				"<li class='text-danger-600' onclick='delete();'>"+
									"<a title='Remove'　href='#'><i class='icon-trash'></i></a></li>"+
							"</ul>";
	            oTable.row.add(datas);
	            oTable.draw();
			    if (Array.prototype.forEach) {
			        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
			        elems.forEach(function(html) {
			            var switchery = new Switchery(html);
			        });
		    	}
        		$(".switch").bootstrapSwitch();

        	});
            
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
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function printPdf(divName){
        	document.forms.pdf_form.submit();
        }
	</script>
</body>
</html>
