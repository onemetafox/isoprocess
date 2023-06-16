<div class="sidebar sidebar-main sidebar-fixed">
	<div class="sidebar-content">

		<!-- Main navigation -->
		<div class="sidebar-category sidebar-category-visible">
			<div class="category-content no-padding">
				<ul class="navigation navigation-main navigation-accordion">
					<!-- Main -->
					<li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>
					<li><a href="<?php echo base_url(); ?>index.php/Welcome"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
					<li>
						<a href="<?php echo base_url(); ?>index.php/Admin/consultant_list" class="clist"><i class="icon-stack2"></i> <span>Owner Management</span></a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>index.php/Admin/plan_list" class="smlist"><i class="icon-lan2"></i> <span>Subscription Management</span></a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>index.php/Admin/invoice" class="paylist"><i class="icon-coin-dollar"></i> <span>Invoice Management</span></a>
					</li>
					<li class="">
						<a href="<?php echo base_url(); ?>index.php/Admin/login_history" class="paylist_1"><i class="icon-history"></i>Login History</a>
						<!-- <ul>
							<li class="<?=$d4?>"><a href="<?php //echo base_url(); ?>index.php/Employee/login_history" >View History</a></li>
						</ul> -->
					</li>
					<li>
						<a href="<?php echo base_url(); ?>index.php/Admin/Email_template" class="email_1"><i class="icon-envelope"></i>Email Template</a>
						<!-- <ul>
							<li class="<?=$d4?>"><a href="<?php echo base_url(); ?>index.php/Admin/Email_template" >All Template</a></li>
						</ul> -->
					</li>
					<!-- /main -->
				</ul>
			</div>
		</div>
		<!-- /main navigation -->
	</div>
</div>
