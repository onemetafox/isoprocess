<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
<!--    <link href="http://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">-->
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
    <!-- /core JS files -->

<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/plugins/tables/datatables/datatables.min.js"></script>-->
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/pages/datatables_basic.js"></script>-->

<style type="text/css">
    	.cstlist {
		    background-color:#26a69a;
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
                                $consultant_id = $this->session->userdata('consultant_id');
                                $audito1 = $this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row();

                                $dlogo = $this->db->query("select * from `default_setting` where `id`='1'")->row()->logo;

                                if ($audito1->logo == '1') {
                                    $audito = $dlogo;
	                            }else{
                                    $audito = $audito1->logo;
	                            }
							}
							?>
                            <img src="<?php echo base_url(); ?>uploads/logo/<?= $audito ?>" style="height:50px;">
								 <span class="text-semibold"><?=$title?></span>
                             <div class="pull-right">
								 <a title="Download" type="button" class="btn btn-primary btn-sm "  onclick="printDiv('ptn')" ><i class="icon-download " aria-hidden="true"></i></a>
                            <a title="Mail" href="mailto:mike.lee@csiclosures.com" class="btn btn-primary"><i class="icon-envelope "  aria-hidden="true"></i></a>
							</div>
							</h4>
						</div>
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/consultantdashboard"><i
                                    class="icon-home2 role-left"></i>Home</a></li>
                        <li><a href="#"><?= $title ?></a></li>

                    </ul>

                    <ul class="breadcrumb-elements">

                    </ul>
                </div>
            </div>
            <!-- /page header -->


				<!-- Content area -->
				<div class="content">
					<!-- Basic datatable -->
					<div class="panel panel-flat" id="ptn">
						<table class="table">
							<tbody>


							<?php
							$respo=@$this->db->query("SELECT * FROM `employees` WHERE `employee_id`='$standalone->process_owner'")->row()->employee_name;
							$auditor=@$this->db->query("SELECT * FROM `employees` WHERE `employee_id`='$standalone->auditor_id'")->row()->employee_name;
							$audit_type=@$this->db->query("SELECT * FROM `type_of_audit` WHERE `type_id`='$standalone->audit_type'")->row()->type_of_audit;
							$process=@$this->db->query("SELECT * FROM `process_list` WHERE `process_id`='$standalone->process'")->row()->process_name;

							$trigger_name=@$this->db->query("SELECT * FROM `trigger` WHERE `trigger_id`='$standalone->trigger_id'")->row()->trigger_name;
							$clause_name=@$this->db->query("SELECT * FROM `clause` WHERE `id`='$standalone->clause'")->row()->name;
							$clause_name1=@$this->db->query("SELECT * FROM `clause` WHERE `id`='$standalone->clause1'")->row()->name;
							$clause_name2=@$this->db->query("SELECT * FROM `clause` WHERE `id`='$standalone->clause2'")->row()->name;

							switch ($standalone->clause){
								case -1:
									$clause_name = "Input Step";
									break;
								case -2:
									$clause_name = "Activity";
									break;
								case -3:
									$clause_name = "Output";
									break;
								case -4:
									$clause_name = "Control";
									break;
								case -5:
									$clause_name = "Resource";
									break;
								case -6:
									$clause_name = "Effectiveness";
									break;
							}
							if ($standalone->clause == 'Not Applicable'){
								$clause_name = $standalone->clause;
							}
							switch ($standalone->clause1){
								case -1:
									$clause_name1 = "Input Step";
									break;
								case -2:
									$clause_name1 = "Activity";
									break;
								case -3:
									$clause_name1 = "Output";
									break;
								case -4:
									$clause_name1 = "Control";
									break;
								case -5:
									$clause_name1 = "Resource";
									break;
								case -6:
									$clause_name1 = "Effectiveness";
									break;
							}
							if ($standalone->clause1 == 'Not Applicable'){
								$clause_name1 = $standalone->clause1;
							}
							switch ($standalone->clause2){
								case -1:
									$clause_name2 = "Input Step";
									break;
								case -2:
									$clause_name2 = "Activity";
									break;
								case -3:
									$clause_name2 = "Output";
									break;
								case -4:
									$clause_name2 = "Control";
									break;
								case -5:
									$clause_name2 = "Resource";
									break;
								case -6:
									$clause_name2 = "Effectiveness";
									break;
							}
							if ($standalone->clause2 == 'Not Applicable'){
								$clause_name2 = $standalone->clause2;
							}
							?>
	                            <tr>
									<td colspan="2" align="center"><h1>FORM DETAILS</h1></td>
								</tr>
								<tr>
								    <td>Incident Identification Number</td>
									<td><?=$standalone->unique_id?></td>
								</tr>
							<tr>
								<td>Type of Audit:</td>
								<td><?=$audit_type?></td>
							</tr>
							<tr>
								<td>Process:</td>
								<td><?=$process?></td>
							</tr>
							<tr>
								<td>Auditee:</td>
								<td>
									<?php if ($standalone->auditor_id == "0"): ?>
										TBD
									<?php endif; ?>
									<?php if ($standalone->auditor_id == "-1"): ?>
										N/A
									<?php endif; ?>
									<?php if ($standalone->auditor_id != "0" && $standalone->auditor_id != "1"): ?>
										<?=$auditor?>
									<?php endif; ?>
								</td>
							</tr>

							<tr>
								<td>Process_owner:</td>
								<td><?=$respo?></td>
							</tr>

								<tr>
								   <td>Audit Criteria/Trigger:</td>
									<td><?=$standalone->audit_criteria?> | <?=$trigger_name?> </td>
								</tr>
								<tr>
									<td>Grade of Non-Conformity:</td>
									<td><?=$standalone->grade_nonconform?> </td>
								</tr>


								<tr>
								    <td>Customer Requirement:</td>
									<td><?=$standalone->customer_requirment?></td>
								</tr>

								<tr>
								    <td>Product:</td>
									<td><?=$standalone->product?></td>
								</tr>
								<tr>
									<td>Process Step</td>
									<td><?=$standalone->process_step?></td>
								</tr>
								<tr>
									<td>Standard</td>
									<td><?=$standalone->standard?></td>
								</tr>
								<tr>
									<td>Standard1</td>
									<td><?=$standalone->standard1?></td>
								</tr>
								<tr>
								      <td>Standard2</td>
									<td><?=$standalone->standard2?></td>
								</tr>
								<tr>
									<td>Clause</td>
									<td><?=$clause_name?></td>
								</tr>
								<tr>
									<td>Clause1</td>
									<td><?=$clause_name1?></td>
								</tr>
								<tr>
									<td>Clause2</td>
									<td><?=$clause_name2?></td>
								</tr>
								<tr>
								    <td>Regulatory Requirement:</td>
									<td><?=$standalone->regulatory_requirement?></td>
								</tr>
								<tr>
								    <tr>
								    <td>Policy/Procedure/Records</td>
									<td><?=$standalone->policy?></td>
								</tr>
								</tr>
                                <tr>
								  <td>SHIFT</td>
									<td><?=$standalone->shift?></td>
								</tr>
								<tr>
								    <td rowspan="3">Where did the complaint occur (Location)? </td>
									<td><?=$standalone->company_name?></td>
								</tr>
								<tr>
									<td><?=$standalone->company_address?></td>
								</tr>
								<tr>
									
									<td><?=$standalone->city?>   |   <?=$standalone->state?> </td>
								</tr>
								
								 

								 <tr>
								    <td>When did the complaint occur?   </td>
									<td><?=$standalone->occur_date?></td>
								</tr>
								<tr>
								    <td>What Occurred / Nonconformity?</td>
									<td><?=$standalone->prob_desc?></td>
								</tr>

								<tr>
									<td>What Occurred / Opportunity for improvement?</td>
									<td><?=$standalone->ofi_desc?></td>
								</tr>

								<tr>
									<td>Opportunity Action</td>
									<td><?=$standalone->opp_action?></td>
								</tr>

								<tr>
									<td>OPPORTUNITY FOR IMPROVEMENT</td>
									<td><?=$standalone->ofi?></td>
								</tr>

								<tr>
								    <td>Correction: </td>
									<td><?=$standalone->correction?></td>
								</tr>
								<tr>
								    <td>Grade of Non-conformity:</td>
									<td><?=$standalone->business_impact?></td>
								</tr>
								<tr>
									<td>Root cause:<a class="btn btn-info" onclick="javascript:show_root_cause()">View</a></td>
									<td><?=$standalone->root_cause?></td>
								</tr>
								<?php if($standalone->root_doc != '') { ?>
									<tr>
										<td>Root cause Document:</td>
										<td><a onclick="javascript:window.history.back()"><i class="icon-download " aria-hidden="true"></i>  <?=$standalone->root_doc?></a></td>
									</tr>
								<?php } ?>

								<tr>
									<td>Corrective Action Plan </td>
									<td><?=$standalone->action_plan?></td>
								</tr>

								<?php if($standalone->corrective_plan_doc != '') { ?>
									<tr>
										<td>Corrective Action Plan Document:</td>
										<td><a href="<?php echo base_url(); ?>uploads/Doc/<?=$standalone->corrective_plan_doc?>"><i class="icon-download " aria-hidden="true"></i>  <?=$standalone->corrective_plan_doc?></a></td>
									</tr>
								<?php } ?>

								<tr>
									<td>Corrective Action </td>
									<td><?=$standalone->corrective_action?></td>
								</tr>

								<?php if($standalone->corrective_doc != '') { ?>

									<tr>
										<td>Corrective Action Document:</td>
										<td><a href="<?php echo base_url(); ?>uploads/Doc/<?=$standalone->corrective_doc?>"><i class="icon-download " aria-hidden="true"></i>  <?=$standalone->corrective_doc?></a></td>
									</tr>
								<?php } ?>


								<tr>
									<td>Verification of Effectiveness</td>
									<td><?=$standalone->verification_effectiveness?></td>
								</tr>

								<?php if($standalone->verification_doc != '') { ?>

									<tr>
										<td>Verification of Effectiveness Document:</td>
										<td><a href="<?php echo base_url(); ?>uploads/Doc/<?=$standalone->verification_doc?>"><i class="icon-download " aria-hidden="true"></i>  <?=$standalone->verification_doc?></a></td>
									</tr>

								<?php } ?>



								<tr>
								    <td>By When: </td>
									<td><?=$standalone->by_when_date?></td>
								</tr>
							</tbody>
						</table>
					</div>

					<a onclick="javascript:window.history.back()" class="btn btn-primary pull-right">Back</a>
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
<div id="modal_root_cause" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title">Root Cause</h6>
			</div>
			<div class="modal-body">
				<form id = "add_root_cause" action="<?php echo base_url();?>index.php/consultant/add_root_cause" method="post">
					<input type="hidden" name="data_id" value="<?php echo $id?>">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group has-feedback">
								<label>WHY: </label>
								<textarea class="form-control" disabled id="why1" name="why1"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group has-feedback">
								<label>WHY: </label>
								<textarea class="form-control" disabled id="why2" name="why2"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group has-feedback">
								<label>WHY: </label>
								<textarea class="form-control" disabled id="why3" name="why3"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group has-feedback">
								<label>WHY: </label>
								<textarea class="form-control" disabled id="why4" name="why4"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group has-feedback">
								<label>WHY: </label>
								<textarea class="form-control" disabled id="why5" name="why5"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group has-feedback">
								<label>Conclusion: </label>
								<textarea class="form-control" disabled id="conclusion" name="conclusion" rows="3"></textarea>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
<!--				<button type="submit" class="btn btn-primary"><i class="icon-reply role-right"></i> Save</button>-->
			</div>
			</form>
		</div>
	</div>
</div>

    <script type="text/javascript">
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>

<script type="text/javascript">
	
console.clear();

function show_root_cause() {
	var val = '<?php echo $id; ?>';
	$.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>index.php/Consultant/get_root_cause",
		data:{ 'id' : val},
		success: function(data) {
			var datas = $.parseJSON(data)
			$("#why1").val(datas.why1);
			$("#why2").val(datas.why2);
			$("#why3").val(datas.why3);
			$("#why4").val(datas.why4);
			$("#why5").val(datas.why5);
            if(datas.conclusion != "")
		      $("#conclusion").val(datas.conclusion);
		}
	});
	$('#modal_root_cause').modal();
}
</script>
</body>

</html>
