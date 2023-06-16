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
</head>
<body style="width:800px">
	<div id="invoice-editable">
			<div class="panel-body no-padding-bottom">
				<div style="row">
					<div class="col-sm-6 content-group">
						<ul class="list-condensed list-unstyled">
							<li style="margin-bottom:20px"><h5 style="font-size: 25px;"><?php echo $super->company_name?></h5></li>
							<li><?php echo $super->address?></li>
							<li><?php echo $super->city?></li>
							<li><?php echo $super->phone?></li>
							<li><?php echo $super->fax?></li>
						</ul>
						<span class="text-muted" style="font-size:20px">Bill To:</span>
                    	<h6 style="text-align:left"><?php echo $customer_admin->admin_name?></h6>
						<ul class="list-condensed list-unstyled">
							<li style="margin-bottom:20px"><h5 class="admin_com_name"><?php echo $customer_admin->company_name?></h5></li>
							<li class="admin_address"><?php echo $customer_admin->address?></li>
							<li class="admin_city"><?php echo $customer_admin->city?></li>
							<li class="admin_phone"><?php echo $customer_admin->phone?></li>
						</ul>
					</div>
					<div class="col-sm-6 content-group">
						<div class="invoice-details">
							<h5 class="text-uppercase text-semibold" style="font-size: 25px;color: #8796C5;">Invoice</h5>
							<ul class="list-condensed list-unstyled">
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
					<div class="col-sm-4 col-sm-offset-4">
						<textarea name="footer_comment"　rows="10" cols="10" class="form-control" placeholder="" style="height:100px"><?php echo $invoice->footer_comment?></textarea>
					</div>
				</div>
			</div>
	</div>
</body>
</html>
