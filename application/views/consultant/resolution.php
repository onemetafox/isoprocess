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
                                } else {
                                    $audito = $audito1->logo;
                                }
                            }
                            ?>
                            <img src="<?php echo base_url(); ?>uploads/logo/<?= $audito ?>" style="height:50px;">
                            <span class="text-semibold"><?= $title ?></span>

                            <div class="pull-right">
                                <?php
                                /*                                $consultant_id = $this->session->userdata('consultant_id');
                                                                $plan_ids1 = @$this->db->query("select * from upgrad_plan where `consultant_id`='$consultant_id' AND `status`='1'")->row()->plan_id;
                                                                if (count($plan_ids1) > 0) {
                                                                    $d1 = @$this->db->query("select * from plan where `plan_id`='$plan_ids1'")->row()->no_of_user;
                                                                }
                                                                $d2 = @$this->db->query("select * from plan order by no_of_user DESC")->row()->plan_id;
                                                                */?><!--
                                <?php /*if ($d1 != $d2 && $d2 > $d1) { */?>
                                    <a href="<?php /*echo base_url(); */?>index.php/Auth/update_process"
                                       class="btn bg-brown"> <i class="icon-wrench" title="Main pages"></i> <span> Upgrade Plan</span></a>
                                --><?php /*} */?>
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
					<!-- Form horizontal -->
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title"><?=$title?></h5>
						</div>

						<div class="panel-body">
							<form id="target" onsubmit="return validateForm()" method="post" action="<?php echo base_url();?>index.php/consultant/update_resolution" enctype="multipart/form-data">
								<input type="hidden" name="verification_question_flag" id="verification_question_flag" value="1" />
								<input type="hidden" name="action_taken" id="action_taken" value="<?=$standalone_data->action_taken?>" />

								<?php
								$respo=@$this->db->query("SELECT * FROM `employees` WHERE `employee_id`='$standalone_data->process_owner'")->row()->employee_name;
								$auditor=@$this->db->query("SELECT * FROM `employees` WHERE `employee_id`='$standalone_data->auditor_id'")->row()->employee_name;
								$audit_type=@$this->db->query("SELECT * FROM `type_of_audit` WHERE `type_id`='$standalone_data->audit_type'")->row()->type_of_audit;

								$process=@$this->db->query("SELECT * FROM `process_list` WHERE `process_id`='$standalone_data->process'")->row()->process_name;
								$trigger_name=@$this->db->query("SELECT * FROM `trigger` WHERE `trigger_id`='$standalone_data->trigger_id'")->row()->trigger_name;
								$clause_name=@$this->db->query("SELECT * FROM `clause` WHERE `id`='$standalone_data->clause'")->row()->name;
								$clause_name1=@$this->db->query("SELECT * FROM `clause` WHERE `id`='$standalone_data->clause1'")->row()->name;
								$clause_name2=@$this->db->query("SELECT * FROM `clause` WHERE `id`='$standalone_data->clause2'")->row()->name;
								switch ($standalone_data->clause){
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
								switch ($standalone_data->clause1){
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
								switch ($standalone_data->clause2){
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
								$employee_email=@$this->db->query("SELECT * FROM `employees` WHERE `employee_id`='$standalone_data->auditor_id'")->row()->employee_email;
								?>

                        <table class="table table-lg table-bordered">
                             <tr>
                             	<td colspan="2" align="center"><b>CURRENT ATTRIBUTE INFORMATION </b></td>
                             	<td><b>REQUESTED CHANGE  TO  ATTRIBUTE INFORMATION</b></td>
                             </tr>
                        	<tr>
                        	 	<td> 
                        	 	  <span class="help-block">Corrective Action No.:</span>
                        	 	</td>
                        	 	<td align="center" style="min-width: 300px;">
    	 		                    <input type="text" class="form-control"  disabled value="<?=$standalone_data->unique_id?>">
    	 	                    </td>
                        	 	<td>
                        	 		<input type="text" class="form-control"  value="<?=$standalone_data->unique_id?>"  readonly>
                        	 	</td>
							</tr>
							<tr>
								<td>
									<span class="help-block">Type of Audit:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$audit_type?>" disabled>
								</td>
								<td>
									<input type="text" class="form-control" value="<?=$audit_type?>" readonly >
								</td>
							</tr>
							<tr>
								<td>
									<span class="help-block">Process:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$process?>" disabled>
								</td>
								<td>
									<input type="text" class="form-control" value="<?=$process?>" readonly >
								</td>
							</tr>
							<tr>
								<td>
									<span class="help-block">Process Owner:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$respo?>" disabled>
								</td>
								<td>
									<input type="text" class="form-control" value="<?=$respo?>" readonly >
								</td>
							</tr>
                        	 <tr>
                        	 	<td>
                        	 	  <span class="help-block">Auditee:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?php if ($standalone_data->auditor_id == "0"): ?>TBD<?php endif; ?>
									<?php if ($standalone_data->auditor_id == "-1"): ?>N/A<?php endif; ?>
									<?php if ($standalone_data->auditor_id != "0" && $standalone_data->auditor_id != "1"): ?><?=$auditor?><?php endif; ?>"
							    	disabled>
    	 	                    </td>
                        	 	<td>
                        	 		<input type="text" class="form-control"  value="<?php if ($standalone_data->auditor_id == "0"): ?>TBD<?php endif; ?>
									<?php if ($standalone_data->auditor_id == "-1"): ?>N/A<?php endif; ?>
									<?php if ($standalone_data->auditor_id != "0" && $standalone_data->auditor_id != "1"): ?><?=$auditor?><?php endif; ?>"
								    readonly>
                        	 	</td>
                        	 </tr>
                        	  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">TRIGGER:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$trigger_name?>"  disabled>
    	 	                    </td>
                        	 	<td>
                        	 		<select class="form-control" name="trigger_id">
                                    <?php foreach ($trigger_list as $item) { ?>
                                       <option <?php if($item->trigger_name == $trigger_name) echo 'selected'; ?>
										   value="<?=$item->trigger_id?>"><?=$item->trigger_name?>
									   </option>
                                     <?php } ?>
                                    </select>
                        	 	</td>
                        	 </tr>


							<tr>
								<td>
									<span class="help-block">Audit Criteria:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$standalone_data->audit_criteria?>"  disabled>
								</td>
								<td>

									<select class="form-control" name="audit_criteria">
										<option>Not Applicable</option>
										<?php foreach ($criteria_list as $item) { ?>
											<option <?php if($item->criteria_name == $standalone_data->audit_criteria) echo 'selected'; ?>
												value="<?=$item->criteria_name?>"><?=$item->criteria_name?>
											</option>
										<?php } ?>
									</select>

								</td>
							</tr>


							<tr>
								<td>
									<span class="help-block">Grade of Non-Conformity:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$standalone_data->grade_nonconform?>"  disabled>
								</td>
								<td>

									<select class="form-control" name="grade_nonconform">
										<option value="Major" <?php if($standalone_data->grade_nonconform == "Major") echo 'selected'; ?>>Major</option>
										<option value="Minor" <?php if($standalone_data->grade_nonconform == "Minor") echo 'selected'; ?>>Minor</option>
										<option value="Critical" <?php if($standalone_data->grade_nonconform == "Critical") echo 'selected'; ?>>Critical</option>

										<?php foreach ($grade_nonconform as $item) { ?>
											<option <?php if($item->name == $standalone_data->grade_nonconform) echo 'selected'; ?>
												value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

								</td>
							</tr>


							<tr>
								<td>
									<span class="help-block">DATE OF OCCURRANCE:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$standalone_data->occur_date?>"  disabled>
								</td>
								<td>
									<input type="date" class="form-control" name="occur_date" value="<?=$standalone_data->occur_date?>" >
								</td>
							</tr>

                        	 <tr>
                        	 	<td> 
                        	 	  <span class="help-block">CUSTOMER REQUIREMENT:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->customer_requirment?>"  disabled>
    	 	                    </td>
                        	 	<td>

									<select class="form-control" name="customer_requirment">
										<option>Not Applicable</option>
										<?php foreach ($customer_requirment_list as $item) { ?>
											<option <?php if($item->name == $standalone_data->customer_requirment) echo 'selected'; ?>
												value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>


                        	 	<!--	<input type="text" class="form-control" name="profile" value="<?/*=$standalone_data->profile*/?>" >-->
                        	 	</td>
                        	 </tr>
                        	 <tr>
                        	 	<td> 
                        	 	  <span class="help-block">PRODUCT:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->product?>"  disabled>
    	 	                    </td>
                        	 	<td>
									<select class="form-control" name="product">
										<option>Not Applicable</option>
										<?php foreach ($product_list as $item) { ?>
											<option <?php if($item->name == $standalone_data->product) echo 'selected'; ?>
												value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

                        	 	</td>
                        	 </tr>
							<tr>
								<td>
									<span class="help-block">Process Step:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$standalone_data->process_step?>"  disabled>
								</td>
								<td>

									<select class="form-control" name="process_step">
										<option>Not Applicable</option>
										<?php foreach ($process_step_list as $item) { ?>
											<option <?php if($item->name == $standalone_data->process_step) echo 'selected'; ?>
													value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

									<!--<input type="text" class="form-control" name="manufacturing_date" value="<?/*=$standalone_data->manufacturing_date*/?>" >-->
								</td>
							</tr>
                        	 <tr>
                        	 	<td> 
                        	 	  <span class="help-block">STANDARD:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->standard?>"  disabled>
    	 	                    </td>
                        	 	<td>

									<select class="form-control" name="standard">
										<option>Not Applicable</option>
										<?php foreach ($standard_list as $item) { ?>
											<option <?php if($item->name == $standalone_data->standard) echo 'selected'; ?>
												value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

                        	 		<!--<input type="text" class="form-control" name="manufacturing_date" value="<?/*=$standalone_data->manufacturing_date*/?>" >-->
                        	 	</td>
                        	 </tr>
							<tr>
								<td>
									<span class="help-block">STANDARD1:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$standalone_data->standard1?>"  disabled>
								</td>
								<td>

									<select class="form-control" name="standard1">
										<option>Not Applicable</option>
										<?php foreach ($standard_list as $item) { ?>
											<option <?php if($item->name == $standalone_data->standard1) echo 'selected'; ?>
													value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

									<!--<input type="text" class="form-control" name="manufacturing_date" value="<?/*=$standalone_data->manufacturing_date*/?>" >-->
								</td>
							</tr>
							<tr>
								<td>
									<span class="help-block">STANDARD2:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$standalone_data->standard2?>"  disabled>
								</td>
								<td>

									<select class="form-control" name="standard2">
										<option>Not Applicable</option>
										<?php foreach ($standard_list as $item) { ?>
											<option <?php if($item->name == $standalone_data->standard2) echo 'selected'; ?>
													value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

									<!--<input type="text" class="form-control" name="manufacturing_date" value="<?/*=$standalone_data->manufacturing_date*/?>" >-->
								</td>
							</tr>

							<tr>
								<td>
									<span class="help-block">CLAUSE:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$clause_name?>"  disabled>
								</td>
								<td>

									<select class="form-control" name="clause">
										<option>Not Applicable</option>
										<?php foreach ($clause_list as $item) { ?>
											<option <?php if($item->id == $standalone_data->clause) echo 'selected'; ?>
												value="<?=$item->id?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

									<!--<input type="text" class="form-control" name="manufacturing_date" value="<?/*=$standalone_data->manufacturing_date*/?>" >-->
								</td>
							</tr>
							<tr>
								<td>
									<span class="help-block">CLAUSE1:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$clause_name1?>"  disabled>
								</td>
								<td>

									<select class="form-control" name="clause1">
										<option>Not Applicable</option>
										<?php foreach ($clause_list as $item) { ?>
											<option <?php if($item->id == $standalone_data->clause1) echo 'selected'; ?>
													value="<?=$item->id?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

									<!--<input type="text" class="form-control" name="manufacturing_date" value="<?/*=$standalone_data->manufacturing_date*/?>" >-->
								</td>
							</tr>
							<tr>
								<td>
									<span class="help-block">CLAUSE2:</span>
								</td>
								<td align="center">
									<input type="text" class="form-control" value="<?=$clause_name2?>"  disabled>
								</td>
								<td>

									<select class="form-control" name="clause2">
										<option>Not Applicable</option>
										<?php foreach ($clause_list as $item) { ?>
											<option <?php if($item->id == $standalone_data->clause2) echo 'selected'; ?>
													value="<?=$item->id?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

									<!--<input type="text" class="form-control" name="manufacturing_date" value="<?/*=$standalone_data->manufacturing_date*/?>" >-->
								</td>
							</tr>

                        	  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">REGULATORY REQUIREMENT :</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->regulatory_requirement?>"  disabled>
    	 	                    </td>
                        	 	<td>
									<select class="form-control" name="regulatory_requirement">
										<option>Not Applicable</option>
										<?php foreach ($regulatory_requirement_list as $item) { ?>
											<option <?php if($item->name == $standalone_data->regulatory_requirement) echo 'selected'; ?>
												value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

                        	 		<!--<input type="text" class="form-control" name="manufacturing_lot" value="<?/*=$standalone_data->manufacturing_lot*/?>" >-->
                        	 	</td>
                        	 </tr>

                        	  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">POLICY/PROCEDURE/RECORDS :</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->policy?>"   disabled>
    	 	                    </td>
                        	 	<td>
									<select class="form-control" name="policy">
										<option>Not Applicable</option>
										<?php foreach ($policy_list as $item) { ?>
											<option <?php if($item->name == $standalone_data->policy) echo 'selected'; ?>
												value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

                        	 		<!--<input type="text" class="form-control" name="range_of_defect" value="<?/*=$standalone_data->range_of_defect*/?>"  >-->
                        	 	</td>
                        	 </tr>
                        	  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">MACHINE:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->mashine_clause?>"   disabled>
    	 	                    </td>
                        	 	<td>

									<select class="form-control" name="mashine_clause">
										<option>Not Applicable</option>
										<?php foreach ($mashine_list as $item) { ?>
											<option <?php if($item->name == $standalone_data->mashine_clause) echo 'selected'; ?>
												value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

                        	 	<!--	<input type="text" class="form-control" name="mashine_clause" value="<?/*=$standalone_data->mashine_clause*/?>" >-->
                        	 	</td>
                        	 </tr>
                        	  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">SHIFT:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->shift?>"  disabled>
    	 	                    </td>
                        	 	<td>

									<select class="form-control" name="shift">
										<option>Not Applicable</option>
										<?php foreach ($shift_list as $item) { ?>
											<option <?php if($item->name == $standalone_data->shift) echo 'selected'; ?>
												value="<?=$item->name?>"><?=$item->name?>
											</option>
										<?php } ?>
									</select>

                        	 	</td>
                        	 </tr>
                        	   <tr>
                        	 	<td> 
                        	 	  <span class="help-block">COMPANY NAME:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->company_name?>"  disabled>
    	 	                    </td>
                        	 	<td>
                        	 		<input type="text" class="form-control" name="company_name" value="<?=$standalone_data->company_name?>" >
                        	 	</td>
                        	 </tr>
                        	 <tr>
                        	 	<td> 
                        	 	  <span class="help-block">SHIP TO ADDRESS:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->company_address?>"  disabled>
    	 	                    </td>
                        	 	<td>
                        	 		<input type="text" class="form-control" name="company_address" value="<?=$standalone_data->company_address?>"  >
                        	 	</td>
                        	 </tr>
                        	  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">SHIP TO CITY:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->city?>"  disabled>
    	 	                    </td>
                        	 	<td>
                        	 		<input type="text" class="form-control" value="<?=$standalone_data->city?>" name="city" >
                        	 	</td>
                        	 </tr>
                        	  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">STATE:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->state?>"  disabled>
    	 	                    </td>
                        	 	<td>
                        	 		<input type="text" class="form-control" name="state" value="<?=$standalone_data->state?>" >
                        	 	</td>
                        	 </tr>

                        	 <tr>
                        	 	<td> 
                        	 	  <span class="help-block">DESCRIPTION OF PROBLEM / NONCONFORMITY:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <textarea class="form-control" disabled><?=$standalone_data->prob_desc?></textarea>
    	 	                    </td>
                        	 	<td>
                        	 		<textarea class="form-control" name="prob_desc"><?=$standalone_data->prob_desc?></textarea>
                        	 	</td>
                        	 </tr>
							<tr>
								<td>
									<span class="help-block">DESCRIPTION OF OPPORTUNITY</span>
								</td>
								<td align="center">
									<textarea class="form-control" disabled><?=$standalone_data->ofi_desc?></textarea>
								</td>
								<td>
									<textarea class="form-control" name="ofi_desc"><?=$standalone_data->ofi_desc?></textarea>
								</td>
							</tr>
							<tr>
								<td>
									<span class="help-block">OPPORTUNITY FOR IMPROVEMENT:</span>
								</td>
								<td align="center">
									<textarea class="form-control" disabled><?=$standalone_data->ofi?></textarea>
								</td>
								<td>
									<textarea class="form-control" readonly name="prob_desc"><?=$standalone_data->ofi?></textarea>
								</td>
							</tr>
                        	 <tr>
                        	 	<td> 
                        	 	  <span class="help-block">CORRECTION:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <textarea class="form-control" disabled><?=$standalone_data->correction?></textarea>
    	 	                    </td>
                        	 	<td>
                        	 		<textarea class="form-control" id="correction" name="correction"><?=$standalone_data->correction?></textarea>
                        	 	</td>
                        	 </tr>

                        	 <tr>
                        	 	<td> 
                        	 	  <span class="help-block">Grade of Non-conformity::</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <textarea class="form-control" disabled><?=$standalone_data->business_impact?></textarea>
    	 	                    </td>
                        	 	<td>
                        	 		<textarea class="form-control" id="business_impact" name="business_impact"><?=$standalone_data->business_impact?></textarea>
                        	 	</td>
                        	 </tr>
							<tr>
								<td>
									<span class="help-block" style="display: inline;padding-right: 40px;">ROOT CAUSE:</span>
									<a class="btn btn-info" onclick = "show_root_cause()">Root Cause Analysis</a>
								</td>
								<td align="center">
									<textarea class="form-control" disabled><?=$standalone_data->root_cause?></textarea>
									<?php if($standalone_data->root_doc != ''){ ?>
										<a href="<?php echo base_url(); ?>uploads/Doc/<?=$standalone_data->root_doc?>"><i class="icon-download " aria-hidden="true"></i>  <?=$standalone_data->root_doc?></a>
									<?php } ?>
								</td>
								<td>
									<textarea class="form-control" id="root_cause" name="root_cause"><?=$standalone_data->root_cause?></textarea>
									<input type="file" name="root_doc">
								</td>
							</tr>

							<tr style="display: none;">
								<td>
									<span class="help-block">Where was the Breakdown (Leadership, person and/ or procedure)?:</span>
								</td>
								<td align="center">
									<textarea class="form-control" disabled><?=$standalone_data->breakdown?></textarea>
								</td>
								<td>
									<textarea class="form-control" id="breakdown" name="breakdown"><?=$standalone_data->breakdown?></textarea>
								</td>
							</tr>
							<tr>
								<td>
									<span class="help-block">CORRECTIVE ACTION PLAN:</span>
								</td>
								<td align="center">
									<textarea class="form-control" disabled><?=$standalone_data->action_plan?></textarea>
									<?php if($standalone_data->corrective_plan_doc != ''){ ?>
										<a href="<?php echo base_url(); ?>uploads/Doc/<?=$standalone_data->corrective_plan_doc?>"><i class="icon-download " aria-hidden="true"></i>  <?=$standalone_data->corrective_plan_doc?></a>
									<?php } ?>
								</td>
								<td>
									<textarea class="form-control" id="action_plan" name="action_plan"><?=$standalone_data->action_plan?></textarea>
									<input type="file" name="corrective_plan_doc">
								</td>
							</tr>
							<tr>
								<td>
									<span class="help-block">CORRECTIVE ACTION:</span>
								</td>
								<td align="center">
									<textarea class="form-control" disabled><?=$standalone_data->corrective_action?></textarea>
									<?php if($standalone_data->corrective_doc != ''){ ?>
										<a href="<?php echo base_url(); ?>uploads/Doc/<?=$standalone_data->corrective_doc?>"><i class="icon-download " aria-hidden="true"></i>  <?=$standalone_data->corrective_doc?></a>
									<?php } ?>

								</td>
								<td>
									<textarea class="form-control" id="corrective_action" name="corrective_action"><?=$standalone_data->corrective_action?></textarea>
									<input type="file" name="corrective_doc">
								</td>
							</tr>

							<tr>
								<td>
									<span class="help-block">Verification of Effectiveness:</span>
								</td>
								<td align="center">
									<textarea class="form-control" disabled><?=$standalone_data->verification_effectiveness?></textarea>

									<?php if($standalone_data->verification_doc != ''){ ?>
										<a href="<?php echo base_url(); ?>uploads/Doc/<?=$standalone_data->verification_doc?>"><i class="icon-download " aria-hidden="true"></i> <?=$standalone_data->verification_doc?></a>
									<?php } ?>
								</td>
								<td>
									<textarea class="form-control" id="verification_effectiveness" name="verification_effectiveness"><?=$standalone_data->verification_effectiveness?></textarea>
									<input type="file" name="verification_doc">
								</td>
							</tr>

                        	  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">BY WHEN DATE:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$standalone_data->by_when_date?>"  disabled>
    	 	                    </td>
                        	 	<td>
                        	 		<input type="date" class="form-control"   name="by_when_date" value="<?=$standalone_data->by_when_date?>" >
                        	 	</td>
                        	 </tr>
                        	  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">STATUS:</span>
                        	 	</td>
                        	 	<?php 
                        	 	if ($standalone_data->process_status=='' || $standalone_data->process_status=='Open') {
    	 		                    	$sts='open';
    	 		                       }else{
    	 		                       	$sts=$standalone_data->process_status;
    	 		                       }
    	 		                     ?>

                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?=$sts?>" 
    	 		                      disabled>
    	 	                    </td>
                        	 	<td>
                        	 		 <select class="form-control" id="process_status" name="process_status">
                                       <option value="Open">Open</option> 
                                       <option value="Close">Close</option>        
                                    </select>
                        	 	</td>
                        	 </tr>
                        	  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">TYPE:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <select name="type" disabled class="form-control " required="">
                                        <option value="CORRECTIVE">CORRECTIVE</option>
                                    </select>
    	 	                    </td>
                        	 	<td>
                        	 		 <select name="type" class="form-control " required="">
                                        <option value="CORRECTIVE">CORRECTIVE</option>
                                    </select>

                                    <input type="hidden" name="form_id" value="<?=$standalone_data->id?>">
                        	 	</td>
                        	 </tr>

                        	<!--  <tr>
                        	 	<td> 
                        	 	  <span class="help-block">DATE CLOSED:</span>
                        	 	</td>
                        	 	<td align="center">
    	 		                    <input type="text" class="form-control" value="<?/*=date('Y-m-d')*/?>"  disabled>
    	 	                    </td>
                        	 	<td>
                        	 		<input type="text" name="closed_date" class="form-control" value="<?/*=date('Y-m-d')*/?>"  >
                        	 	</td>
                        	 </tr>-->

							<tr>
								<td>
									<span class="help-block">Verification of Effectiveness Flag:</span>
								</td>
								<td align="center">
									<input <?php if($standalone_data->verification_flag == 'Yes') echo 'checked' ?> id="verification_flag_yes" type="radio" name="verification_flag" onclick="switchStatus('close');" required value="Yes" /><span>Yes</span>
									<input <?php if($standalone_data->verification_flag == 'No') echo 'checked' ?> id="verification_flag_no" type="radio" name="verification_flag" onclick="switchStatus('open');" required value="No" >No</input>
								</td>
								<td>
									<div class="text-right" style="margin-top: 20px;">
                                     	<a data-toggle="modal" data-target="#modal_send_message" class="btn btn-primary btn-sm"><i class="icon-mail5"></i> Send Message</a>
										<a type="button" class="btn btn-info btn-sm" href="<?php echo base_url(); ?>index.php/Consultant/show_corrective_message/<?=$standalone_data->id?>">
											<i class="icon-mail-read"></i> View Message
										</a>
										<button type="button" class="btn btn-primary savedata"> Submit</button>
									</div>
								</td>
							</tr>


                        </table>


										

							</form>

						</div>
					</div>
					<!-- /form horizontal -->

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
<script type="text/javascript">
	function switchStatus(flag){
		if(flag == 'open'){
			$("#process_status").val("Open");
		}
		else{
			$("#process_status").val("Close");
		}
	}


	function find(val){
		if (val==0) {
			         $("#cust_name").val('');
                     $("#address").val('');
                     $("#city").val('');
                     $("#state").val('');
		}
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/Company/findcust",
                    data:{ 'id' : val},
                      success: function(data) {
                      var datas = $.parseJSON(data)
                     $("#cust_name").val(datas.name);
                     $("#address").val(datas.address);
                     $("#city").val(datas.city);
                     $("#state").val(datas.state);
                    }
                  });
    }
</script>

<script type="text/javascript">
	function findresponsible(val){
		if (val==0) {
			         $("#position").val('');
		}
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/Company/findresponsible",
                    data:{ 'id' : val},
                      success: function(data) {
                      	console.log(data);
                      var datas = $.parseJSON(data)
                     $("#position").val(datas.position_name);
                    }
                  });
    }
</script>


<script>
    shortcut.add("ctrl+s", function() {

        $("#save").click()
    });   
    shortcut.add("ctrl+r", function() {

        $("#reset").click()
    }); 
</script>



<script type="text/javascript">
	
$('body').on('click','.savedata',function(e){


	if($("#correction").val() == "TBD"){
		bootbox.alert("Please input CORRECTION field");
		return;
	}
	if($("#business_impact").val() == "TBD"){
		bootbox.alert("Please input Grade of Non-conformity field");
		return;
	}
	if($("#root_cause").val() == "TBD"){
		bootbox.alert("Please input ROOT CAUSE field");

		return;
	}
	if($("#action_plan").val() == "TBD"){
		bootbox.alert("Please input CORRECTIVE ACTION PLAN field");
		return;
	}
	if($("#corrective_action").val() == "TBD"){
		bootbox.alert("Please input CORRECTIVE ACTION field");

		return;
	}

	if($("#verification_effectiveness").val() == "TBD"){
		bootbox.alert("Please input  Verification of Effectiveness field");
		return;
	}

	var flag1 = document.getElementById("verification_flag_yes").checked;
	var flag2 = document.getElementById("verification_flag_no").checked;

	if(!flag1 && !flag2) {
		bootbox.alert("Please Select  Verification of Effectiveness Flag field");
		return;
	}


   var dialog = bootbox.dialog({
   title: 'Verification Form Question',
   message: "<p>If your action involves a nonconformity related to a record management issue or the need for additional training a verification form is needed to verify the training or discussion between the supervisor and involved parties. This document must be completed and signed by all involved  before the action can be closed</p> <h5>Does your action require this form ?</h5>",
   size: 'small',
   buttons: {
       cancel: {
           label: "NO",
           className: 'btn-danger',
           callback: function(){
              // dialog.modal('hide');
			   $("#verification_question_flag").val("1");
			   $( "#target" ).submit();
           }
       },

	   ok: {
		 label: "YES",
		 className: 'btn-info',
		 callback: function(){
			 $("#verification_question_flag").val("2");
			$( "#target" ).submit();
		}
	   }
    }
   });
});
function show_root_cause(){
	var val = "<?=$corrective_id?>";
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

function sendMessage(val) {
	var message = $('#message').val();
	if(message.length == 0) {
		$("#message_err").html('* this field is required');
		return false;
	} else {
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>index.php/Consultant/send_corrective_message",
			data:{ 'corrective_id' : val, 'message' : message},
			success: function(data) {
				var dialog = bootbox.dialog({
					message: "Successfully sended.",
					size: 'small',
					buttons: {
						cancel: {
							label: "Ok",
							className: 'btn-danger',
							callback: function() {
								dialog.modal('hide');
								$('#modal_send_message').modal('hide');
							}
						}
					}
				});
			}
		});
	}
}

</script>

<script type="text/javascript">
    
console.clear();

	function validateForm(){

		return true;
	}

</script>



<!-- Primary modal -->
	<div id="modal_send_message" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title"><i class="icon-envelope  role-right"></i>  Send Message</h6>

				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group has-feedback">
								<label>Message: </label>
								<textarea class="form-control" name="message" id="message"></textarea>
								<span id="message_err" style="color:red;"></span>
							</div>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="sendMessage('<?=$corrective_id?>');"><i class="icon-reply role-right"></i> Send</button>
				</div>
			</div>
		</div>
	</div>
					<div id="modal_root_cause" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header bg-primary">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h6 class="modal-title">Root Cause</h6>
								</div>
								<div class="modal-body">
									<form id = "add_root_cause" action="<?php echo base_url();?>index.php/consultant/add_root_cause" method="post">
										<input type="hidden" name="data_id" value="<?=$standalone_data->id?>">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group has-feedback">
													<label>WHY: </label>
													<textarea class="form-control" id="why1" name="why1"></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group has-feedback">
													<label>WHY: </label>
													<textarea class="form-control" id="why2" name="why2"></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group has-feedback">
													<label>WHY: </label>
													<textarea class="form-control" id="why3" name="why3"></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group has-feedback">
													<label>WHY: </label>
													<textarea class="form-control" id="why4" name="why4"></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group has-feedback">
													<label>WHY: </label>
													<textarea class="form-control" id="why5" name="why5"></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group has-feedback">
													<label>Conclusion: </label>
													<textarea class="form-control" id="conclusion" name="conclusion" rows="3"><?=$standalone_data->root_cause?></textarea>
												</div>
											</div>
										</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-primary"><i class="icon-reply role-right"></i> Save</button>
								</div>
								</form>
							</div>
						</div>
					</div>
    <!-- /primary modal -->
</body>

</html>
