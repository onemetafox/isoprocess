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
if(isset($dd1)){ $dd1=$dd1; }else{ $dd1='0'; }
	if(isset($d1)){ $d1=$d1; }else{ $d1='0'; }
	if(isset($d2)){ $d2=$d2; }else{ $d2='0'; }
	if(isset($d3)){ $d3=$d3; }else{ $d3='0'; }
	//if(isset($d4)){ $d4=$d4; }else{ $d4='0'; }
if(isset($dd4)){ $dd4=$dd4; }else{ $dd4='0'; }
	if(isset($d4)){ $d4=$d4; }else{ $d4='0'; }
$user_type=$this->session->userdata('user_type');
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
					<li><a href="<?php echo base_url(); ?>index.php/Welcome/employeedashboard"><i class="icon-home4"></i> <span>Dashboard</span></a></li>

					<?php if($user_type != 'Process Owner'):?>
					<li class="<?=$aa1?>">
						<a href="#"><i class="icon-calculator"></i>PROCESS AUDIT</a>
						<ul>
							<?php if ($user_type == "Lead Auditor"): ?>
								<li class="<?=$a1?>"><a href="<?php echo base_url(); ?>index.php/Employee/audits">AUDIT</a></li>
							<?php else: ?>
								<li class="<?=$a1?>"><a href="<?php echo base_url(); ?>index.php/Employee/audit_report">AUDIT Report</a></li>
							<?php endif; ?>
							<li class="<?=$a2?>"><a href="<?php echo base_url(); ?>index.php/Employee/open_audit">Open AUDIT LOG</a></li>
							<li class="<?=$a3?>"><a href="<?php echo base_url(); ?>index.php/Employee/close_audit">Close AUDIT LOG</a></li>
						</ul>
					</li>
					<?php endif;?>
					<li class="<?=$bb1?>">
						<a href="#"><i class="icon-dice"></i>Corrective Action</a>
						<ul>
							<?php if($user_type != 'Process Owner'):?>
								<li class="<?=$b1?>"><a href="<?php echo base_url(); ?>index.php/Employee/corrective_action_form">Corrective Action Form</a></li>
							<?php endif;?>
							<li class="<?=$b2?>"><a href="<?php echo base_url(); ?>index.php/Employee/corrective_action_report">Corrective Action Report</a></li>
							<li class="<?=$b3?>"><a href="<?php echo base_url(); ?>index.php/Employee/resolution_list">Corrective Action Resolution Log</a></li>
							<li class="<?=$b6?>"><a href="<?php echo base_url(); ?>index.php/Employee/resolution_list_ofi">Opportunity of Improvement Resolution Log</a></li>
							<li class="<?=$b4?>"><a href="<?php echo base_url(); ?>index.php/Employee/resolved_list/OFI">Opportunity of Improvement Resolution History</a></li>
							<li class="<?=$b5?>"><a href="<?php echo base_url(); ?>index.php/Employee/resolved_list/CORRECTIVE">Corrective Action Resolution History</a></li>
						</ul>
					</li>
					<?php if ($user_type == "Lead Auditor"): ?>
						<li class="<?=$cc1?>">
							<a href="#"><i class="icon-list"></i>Manage</a>
							<ul>
								<li class="<?=$c1?>"><a href="<?php echo base_url(); ?>index.php/Employee/process_manage"><span>Process Manage</span></a></li>
								<li class="<?=$c2?>"><a href="<?php echo base_url(); ?>index.php/Employee/team_auditors"><span>Add Employees</span></a></li>
							</ul>
						</li>
					<?php endif; ?>
					<?php if ($user_type == "Process Owner"): ?>
						<li class="<?=$cc1?>">
							<a href="#"><i class="icon-list"></i>Manage</a>
							<ul>
								<li class="<?=$c2?>"><a href="<?php echo base_url(); ?>index.php/Employee/auditees"><span>Auditees</span></a></li>
							</ul>
						</li>
					<?php endif; ?>
					<li class="<?=$dd1?>">
						<a href="#"><i class="icon-envelope"></i>Inbox</a>
						<ul>
							<li class="<?=$d3?>"><a href="<?php echo base_url(); ?>index.php/Employee/process_message" >Process</a></li>
							<li class="<?=$d1?>"><a href="<?php echo base_url(); ?>index.php/Employee/corrective_message"><span> Corrective Action</span></a></li>
							<li class="<?=$d2?>"><a href="<?php echo base_url(); ?>index.php/Employee/individual_message" >Individual</a></li>
							<!-- <li class="<?=$d4?>"><a href="<?php echo base_url(); ?>index.php/Employee/login_history" >login History</a></li> -->
						</ul>
					</li>
					<li class="<?=$dd4?>">
						<a href="#"><i class="icon-history"></i>Login History</a>
						<ul>
							<li class="<?=$d4?>"><a href="<?php echo base_url(); ?>index.php/Employee/login_history" >View History</a></li>
						</ul>
					</li>

					<!-- /main -->
				</ul>
			</div>
		</div>
		<!-- /main navigation -->
	</div>
</div>
