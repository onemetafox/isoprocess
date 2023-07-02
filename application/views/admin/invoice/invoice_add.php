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
	<?php $this->load->view('Admin/main_header.php'); ?>
	<div class="page-container">
		<div class="page-content">
			<?php $this->load->view('Admin/sidebar'); ?>
			<div class="content-wrapper">
				<div class="content">
					<div class="panel panel-white">
						<form id="invoice_form" class="form-horizontal" action="<?php echo base_url('index.php/Admin/invoice_add_action')?>" name="add_form" method="post">
						<div id="invoice-editable">
							<div class="panel-body no-padding-bottom">
								<div class="row">
									<div class="col-sm-6 content-group">
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
														<input type="text" class="form-control" id="anytime-month-numeric" name="create_date" value="<?php echo date('Y-m-d')?>" style="min-width:95px">
														<span class="input-group-addon"><i class="icon-calendar3"></i></span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2 col-md-offset-5">INVOICE: </label>
												<div class="col-md-5">
													<input class="form-control" type="text" name="invoice_num" value="<?php echo 'INV-'.rand()?>" style="min-width:135px">
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 col-lg-9 content-group">
										<span class="text-muted" style="font-size:20px">Bill To:</span>
	                                    <div class="form-group">
	                                        <label class="control-label col-md-2">Customer Name: </label>
	                                        <div class="col-md-4" style="padding:0">
		                                        <div class="col-md-12 btn-group bootstrap-select dropdown" style="padding-left:0;padding-right:0">
		                                            <select data-width="100%" id="admin_id" name="admin_id" class="bootstrap-select">
														<option value=""></option>
		                                                <?php foreach ($admins as $admin) { ?>
		                                                    <option 
																data-name = "<?= $admin->consultant_name ?>" 
																data-address ="<?= $admin->address ?>" 
																data-city = "<?= $admin->city ?>" 
																data-phone = "<?= $admin->phone ?>"
																value="<?= $admin->consultant_id ?>">
		                                                        <?php echo $admin->consultant_name ?>
		                                                    </option>
		                                                <?php } ?>
		                                            </select>
		                                        </div>
												
	                                        </div>
	                                    </div>
										<div class="form-group">
											<label class="control-label col-md-2">MemberShip Name:</label>
											<div class="col-md-4" style="padding:0">
												<div class="col-md-12 btn-group bootstrap-select dropdown" style="padding-left:0;padding-right:0">
		                                            <select data-width="100%" id="plan_id" name="plan_id" class="bootstrap-select">
														<option value=""></option>
		                                                <?php foreach ($plans as $plan) { ?>
		                                                    <option 
																data-name="<?= $plan->plan_name ?>"
																data-amount = "<?= $plan->total_amount ?>"
																value="<?= $plan->plan_id ?>" >
		                                                        <?php echo $plan->plan_name ?>
		                                                    </option>
		                                                <?php } ?>
		                                            </select>
		                                        </div>
											</div>
										</div>
	                                    <div class="col-md-4 col-md-offset-2" style="padding-left:0;">
				 							<ul class="list-condensed list-unstyled" style="text-align: center;background-color: #FCFBFB;">
				 								<li style="margin-bottom:20px"><h5 class="admin_com_name"></h5></li>
												<li class="admin_address"></li>
												<li class="admin_city"></li>
												<li class="admin_phone"></li>
											</ul>
										</div>
									</div>
								</div>

							</div>
							<div class="table-responsive">
								<!-- <div class="col-md-2" >
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
							            <tr>
							                <td>
							                	<input type='text' class='form-control' name='description[0]' id="description" required>
							                </td>
							                <td>
							                	<div class="checkbox checkbox-switchery switchery-xs">
													<label>
														<input type="checkbox" class="switchery tax_switch" name="tax[0]" onclick="tax_change()">
													</label>
												</div>
							                </td>
							                <td>
							                	<input type='number' class='form-control' name='amount[0]' id="amount" min="0" onchange="add_amount(this)" value="0" required>
							                </td>
							                <td>
						                		<!-- <ul class="icons-list">
													<li class="text-danger-600" onclick="delete();"><a title="Remove" href="#"><i class="icon-trash"></i></a></li>
												</ul> -->
							                </td>
							            </tr>
							        </tbody>
							    </table>
							</div>

							<div class="panel-body">
								<div class="row invoice-payment">
									<div class="col-sm-4">
										<div class="content-group">
											<h6>Other Comments</h6>
											<textarea name="comment" rows="10" cols="5" class="form-control" placeholder="" style="height:180px;width:200px"></textarea>
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
																<input type="number" class="form-control" name="tax_rate" value="0" min="0" max="100" onchange="taxrate_change(this)">
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
												<button type="submit" class="btn btn-primary btn-labeled"><b><i class="icon-paperplane"></i></b> Send invoice</button>
											</div>
										</div>
									</div>
								</div>	
								<div class="row">
									<div class="col-md-4 col-md-offset-4">
										<textarea name="footer_comment" rows="10" cols="10" class="form-control" placeholder="" style="height:100px"></textarea>
									</div>
								</div>

							</div>

						</div>
						</form>
					</div>
					<div class="page-header page-header-default">
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
		    if (Array.prototype.forEach) {
		        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
		        elems.forEach(function(html) {
		            var switchery = new Switchery(html);
		        });
	    	}
    		$(".switch").bootstrapSwitch();
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
			// 						"<a title='Remove' href='#'><i class='icon-trash'></i></a></li>"+
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
    		
            // oTable.on('click', 'a[title="Remove"]', function (){
	        //     var nRow = $(this).parents('tr')[0];
	        //     oTable.row(nRow).remove().draw();
	        //     add_amount();
			// });
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
		});
		$("#admin_id").on('change', function(){
			$('.admin_com_name').html($(this).find(':selected').data('name'));
			$('.admin_address').html($(this).find(':selected').data('address'));
			$('.admin_city').html($(this).find(':selected').data('city'));
			$('.admin_phone').html($(this).find(':selected').data('phone'));
		})
		$("#plan_id").on('change', function(){
			$('#amount').val($(this).find(':selected').data('amount'));
			$('#description').val($(this).find(':selected').data('name') + " Membership Payment");
			// $('.admin_address').html($(this).find(':selected').data('amount'));
			
		})
		
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
