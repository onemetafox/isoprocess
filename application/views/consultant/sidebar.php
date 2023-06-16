<style type="text/css">
	.act1 {
    background-color: rgb(38, 39, 39);
    color: #fff;
}
</style>


<?php
if(isset($aa1)){ $aa1=$aa1; }else{ $aa1='0'; }
	if(isset($a1)){ $a1=$a1; }else{ $a1='0'; }
	if(isset($a2)){ $a2=$a2; }else{ $a2='0'; }
	if(isset($a3)){ $a3=$a3; }else{ $a3='0'; }
if(isset($bb1)){ $bb1=$bb1; }else{ $bb1='0'; }
	if(isset($b1)){ $b1=$b1; }else{ $b1='0'; }
	if(isset($b2)){ $b2=$b2; }else{ $b2='0'; }
	if(isset($b3)){ $b3=$b3; }else{ $b3='0'; }
	if(isset($b4)){ $b4=$b4; }else{ $b4='0'; }
	if(isset($b5)){ $b5=$b5; }else{ $b5='0'; }
	if(isset($b6)){ $b6=$b6; }else{ $b6='0'; }
if(isset($cc1)){ $cc1=$cc1; }else{ $cc1='0'; }
	if(isset($c1)){ $c1=$c1; }else{ $c1='0'; }
	if(isset($c2)){ $c2=$c2; }else{ $c2='0'; }
	if(isset($c3)){ $c3=$c3; }else{ $c3='0'; }
	if(isset($c4)){ $c4=$c4; }else{ $c4='0'; }
	if(isset($c5)){ $c5=$c5; }else{ $c5='0'; }
if(isset($dd1)){ $dd1=$dd1; }else{ $dd1='0'; }
	if(isset($d1)){ $d1=$d1; }else{ $d1='0'; }
	if(isset($d2)){ $d2=$d2; }else{ $d2='0'; }
	if(isset($d3)){ $d3=$d3; }else{ $d3='0'; }
if(isset($ee1)){ $ee1=$ee1; }else{ $ee1='0'; }
	if(isset($e1)){ $e1=$e1; }else{ $e1='0'; }
	if(isset($e2)){ $e2=$e2; }else{ $e2='0'; }
	if(isset($e3)){ $e3=$e3; }else{ $e3='0'; }

if(isset($dd4)){ $dd4=$dd4; }else{ $dd4='0'; }
	if(isset($d4)){ $d4=$d4; }else{ $d4='0'; }
	if(isset($d5)){ $d5=$d5; }else{ $d5='0'; }
?>

<div class="sidebar sidebar-main sidebar-fixed">
				<div class="sidebar-content">
					<!-- User menu -->

					<!-- /user menu -->

					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">

								<!-- Main -->
								<li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>
								<li><a href="<?php echo base_url(); ?>index.php/Welcome/consultantdashboard"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
								<li class="<?=$aa1?>">
									<a href="#"><i class="icon-calculator"></i>PROCESS AUDIT</a>
									<ul>
										<li class="<?=$a1?>"><a href="<?php echo base_url(); ?>index.php/Consultant/audits">AUDIT</a></li>
										<li class="<?=$a2?>"><a href="<?php echo base_url(); ?>index.php/Consultant/open_audit">Open AUDIT LOG</a></li>
										<li class="<?=$a3?>"><a href="<?php echo base_url(); ?>index.php/Consultant/close_audit">Close AUDIT LOG</a></li>
									</ul>
								</li>
								<li class="<?=$bb1?>">
									<a href="#"><i class="icon-dice"></i>Corrective Action</a>
									<ul>
										<li class="<?=$b1?>"><a href="<?php echo base_url(); ?>index.php/Consultant/corrective_action_form">Corrective Action Form</a></li>
										<!-- <li class="<?=$b2?>"><a href="<?php echo base_url(); ?>index.php/Consultant/corrective_action_report">Corrective Action Report</a></li> -->
										<li class="<?=$b3?>"><a href="<?php echo base_url(); ?>index.php/Consultant/resolution_list">Corrective Action Resolution Log</a></li>
										<li class="<?=$b6?>"><a href="<?php echo base_url(); ?>index.php/Consultant/resolution_list_ofi">Opportunity for Improvement Resolution Log</a></li>
										<li class="<?=$b4?>"><a href="<?php echo base_url(); ?>index.php/Consultant/resolved_list/OFI">Opportunity for Improvement Resolution History</a></li>
										<li class="<?=$b5?>"><a href="<?php echo base_url(); ?>index.php/Consultant/resolved_list/CORRECTIVE">Corrective Action Resolution History</a></li>
									</ul>
								</li>
								<li class="<?=$cc1?>">
									<a href="#"><i class="icon-list"></i>Manage</a>
									<ul>
										<li class="<?=$c1?>"><a href="<?php echo base_url(); ?>index.php/Consultant/employees"><span>Employees</span></a></li>
										<li class="<?=$c2?>"><a href="<?php echo base_url(); ?>index.php/Consultant/process_audit_list"><span>Process Audit Manage</span></a></li>
										<li class="<?=$c3?>"><a href="<?php echo base_url(); ?>index.php/Consultant/main_info"><span>Profile</span></a></li>
										<li class="<?=$c4?>"><a href="<?php echo base_url(); ?>index.php/Auth/reg_pay_plans"><span> Upgrade Plan</span></a></li>
										<li class="<?=$c5?>"><a href="<?php echo base_url(); ?>index.php/Consultant/invoice" >INVOICE</a></li>
									</ul>
								</li>
								<li class="<?=$dd1?>">
									<a href="#"><i class="icon-envelope"></i>Inbox</a>
									<ul>
										<li class="<?=$d3?>"><a href="<?php echo base_url(); ?>index.php/Consultant/process_message" >Process</a></li>
										<li class="<?=$d1?>"><a href="<?php echo base_url(); ?>index.php/Consultant/corrective_message"><span> Corrective Action</span></a></li>
										<li class="<?=$d2?>"><a href="<?php echo base_url(); ?>index.php/Consultant/individual_message" >Individual</a></li>
									</ul>
								</li>
								<li class="<?=$ee1?>">
									<a href="#"><i class="icon-mirror"></i>Corrective Action Report</a>
									<ul>
										<li class="<?=$e1?>"><a href="<?php echo base_url(); ?>index.php/Consultant/byprocess">By Process</a></li>
										<li class="<?=$e2?>"><a href="<?php echo base_url(); ?>index.php/Consultant/byprocessowner"><span>By Process Owner</span></a></li>
										<li class="<?=$e3?>"><a href="<?php echo base_url(); ?>index.php/Consultant/byauditee" >By Auditor</a></li>
									</ul>
								</li>

								<li class="<?=$dd4?>">
									<a href="#"><i class="icon-history"></i>Login History</a>
									<ul>
										<li class="<?=$d4?>"><a href="<?php echo base_url(); ?>index.php/Consultant/login_history" >View History</a></li>
										<li class="<?=$d4?>"><a href="<?php echo base_url(); ?>index.php/Consultant/ViewAllUsers" >View All Users</a></li>
									</ul>
								</li>

							</ul>
						</div>
					</div>
					<!-- /main navigation -->

				</div>
			</div>
