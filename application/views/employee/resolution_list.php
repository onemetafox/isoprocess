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

    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/pages/datatables_basic.js"></script>-->

    <script type="text/javascript">
        $(function() {
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: '150px',
                    targets: [6 ]
                }],
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
                order: [7,"desc"]
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
//            $('.dataTables_length select').select2({
//                minimumResultsForSearch: Infinity,
//                width: 'auto'
//            });
        });
    </script>
</head>
<body class="navbar-top">


	<!-- Main navbar -->
<?php $this->load->view('employee/main_header.php'); ?>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<?php $this->load->view('employee/sidebar'); ?>
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
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/employeedashboard"><i
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
					<div class="panel panel-flat" style="overflow: scroll">
						<table class="table datatable-basic">
							<thead>
								<tr>
								  
								    <th>No</th>
								    <th>DATE</th>
								    <th>Type of Audit</th>
								    <th>Process</th>
									<th>Process Owner</th>
                                    <th>Auditor</th>
									<th>Auditee</th>
									<th>Trigger</th>
									<th>Audit Criteria:</th>
									<th>Machine</th>
									<th>Date Of Occurrance</th>
									<th>NonConformity Description</th>
									<th>Correction</th>
									<th>Correction Action Plan:</th>
									<th>Correction Action:</th>
									<th>Action</th>

								</tr>
							</thead>
							<tbody>
								<?php $count=1;
                                 foreach ($standalone_data as $standalone) { ?>
									 <?php
									 $respo=@$this->db->query("SELECT * FROM `employees` WHERE `employee_id`='$standalone->process_owner'")->row()->employee_name;
									 $auditee=@$this->db->query("SELECT * FROM `employees` WHERE `employee_id`='$standalone->auditor_id'")->row()->employee_name;

                                     $auditor_id = $this->db->query("Select c.auditor
                                                                    FROM corrective_action_data AS a
                                                                    LEFT JOIN checklist AS b ON a.checklist_id = b.id
                                                                    LEFT JOIN select_process as c on b.process_id = c.id
                                                                    LEFT JOIN employees as e on c.auditor = e.employee_id
                                                                    where a.checklist_id = '$standalone->checklist_id'")->row()->auditor;
                                     if($auditor_id == 0)
                                         $auditor_id = $standalone->auditor_real_id;
                                     $auditor = 'TBD';
                                     if($auditor_id == -1)
                                         $auditor = 'N/A';
                                     else if($auditor_id != 0)
                                         $auditor = $this->db->query("SELECT employee_name FROM employees where employee_id = '$auditor_id'")->row()->employee_name;
									 $audit_type=@$this->db->query("SELECT * FROM `type_of_audit` WHERE `type_id`='$standalone->audit_type'")->row()->type_of_audit;
									 $process=@$this->db->query("SELECT * FROM `process_list` WHERE `process_id`='$standalone->process'")->row()->process_name;

									 $trigger_name=@$this->db->query("SELECT * FROM `trigger` WHERE `trigger_id`='$standalone->trigger_id'")->row()->trigger_name;

									 ?>
	
								<tr>
								    <td><?=$standalone->unique_id?></td>
								    <td><?=$standalone->by_when_date?></td>
									<td><?=$audit_type?></td>
									<td><?=$process?></td>
									<td><?=$respo?></td>
                                    <td><?=$auditor?></td>
									<td>
										<?php if ($standalone->auditor_id == "0"): ?>
											TBD
										<?php endif; ?>
										<?php if ($standalone->auditor_id == "-1"): ?>
											N/A
										<?php endif; ?>
										<?php if ($standalone->auditor_id != "0" && $standalone->auditor_id != "1"): ?>
											<?=$auditee?>
										<?php endif; ?>
									</td>
									<td><?=$trigger_name?></td>
									<td><?=$standalone->audit_criteria?></td>
                                    <td><?=$standalone->mashine_clause?></td> 
                                    <td><?=$standalone->occur_date?></td>
                                    <td>
                                        <?php
                                        if (strlen($standalone->prob_desc) > 45){
                                            echo substr($standalone->prob_desc,0,45)."...";
                                        }else{
                                            echo $standalone->prob_desc;
                                        }
                                        ?>
                                    </td>
                                    <td><?=$standalone->correction?></td>
                                    <td><?=$standalone->action_plan?></td>
									<td><?=$standalone->corrective_action?></td>
									<td>
										<a href="<?php echo base_url(); ?>index.php/employee/resolution/<?=$standalone->id?>" class="btn btn-primary">Load</a>
									</td> 
									<!-- <td>
									<ul class="icons-list">
										<li class="text-primary-600"> <a href="<?php echo base_url(); ?>index.php/Employee/standaloneform_view/<?=$standalone->id?>/"><i class="icon-eye"></i></a></li>

										<li class="text-primary-600"> <a href="<?php echo base_url(); ?>index.php/Employee/edit_standaloneform/<?=$standalone->id?>/"><i class="icon-pencil7"></i></a></li>

										<li class="text-danger-600"><a href="#" id="<?=$standalone->id?>" class="delete" ><i class="icon-trash"></i></a></li>
									</ul>
									</td> -->
								</tr>
								  <?php $count++; } ?>
							</tbody>
						</table>
					</div>
					<!-- /basic datatable -->

                <!-- Footer -->
                <?php $this->load->view('employee/footer'); ?>
                <!-- /footer -->

				</div>
				<!-- /content area -->
			</div>
			<!-- /main content -->
		</div>
		<!-- /page content -->
	</div>

<script type="text/javascript">
  $('body').on('click','.delete' ,function(e){
     var id = $(this).attr('id');
    var dialog = bootbox.dialog({
    title: 'Confirmation',
    message: "<h4>Are You Sure ?</h4>",
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
               window.location.href="<?php echo base_url();?>index.php/Company/delete_standaloneform/"+id;
            }
        }
     }
    });
});
</script>

<script type="text/javascript">
	function edit(val){
		 $('#modal_theme_primary1').modal('show'); 
               $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/Company/findcust",
                    data:{ 'id' : val},
                      success: function(data) {
                      var datas = $.parseJSON(data)
                     $("#name").val(datas.name);
                     $("#address").val(datas.address);
                     $("#city").val(datas.city);
                     $("#state").val(datas.state);
                     $("#cust_id").val(datas.id);
                    }
                  });
    }
</script>
	<!-- /page container -->

	
<script type="text/javascript">
	
//console.clear();
$(document).ready(function() {
    $('.datatable-scroll').attr('style','overflow-x:scroll;');
    });
</script>

<script type="text/javascript">
	
//	$('.table').DataTable({
//    order: [[1, 'desc']],
//});

</script>
</body>




</html>
