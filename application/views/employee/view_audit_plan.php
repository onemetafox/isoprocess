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
                        <li>Process Audit</li>
                        <li>Open Audit Log</li>
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
                <div class="panel panel-flat" style="overflow:auto;">
                    <table class="table datatable-basic">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Process Name</th>
                            <th>Auditor</th>
                            <th>Process Owner</th>
                            <th>Auditee</th>
                            <th>Date/Time</th>
                            <th>Past Due</th>
                            <th>Efficiency Rating</th>
                            <th>Status</th>
                            <th>Map Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1;
                        $close_count = 0;
                        foreach ($process as $process) { ?>
                            <tr>
                                <td><?= $count ?></td>
                                <td><?= $process->process_name ?></td>
                                <td><?= $process->auditor_name ?></td>
                                <td><?= $process->process_owner_name ?></td>
                                <td>
                                    <?php if($process->sme != null): ?>
                                        <?php if($process->sme == 0): ?>
                                            TBD
                                        <?php elseif($process->sme == -1): ?>
                                            N/A
                                        <?php else: ?>
                                            <?=$process->sme_name?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td style="width: 15%;">
                                    <?php if ($process->starttime_type != ""): ?>
                                        <?= $process->starttime_type ?> - <?= $process->endtime_type ?>
                                    <?php endif; ?>
                                </td>
                                <td  style="min-width: 110px">
                                    <?php if ($process->past_due > 0): ?>
                                    <div style="color:#fff;background-color: #4CAF50;padding-left: 3px;padding-right: 3px;border-radius:2px;">
                                        Task Due in <?=$process->past_due?> days
                                    <?php elseif ($process->past_due == 0): ?>
                                    <div style="color:#fff;background-color: #2196f3;padding-left: 3px;padding-right: 3px;border-radius:2px;">
                                        Task Due
                                    <?php elseif ($process->past_due < 0): ?>
                                    <div style="color:#fff;background-color: #f44336;padding-left: 3px;padding-right: 3px;border-radius:2px;">
                                        Task Past Due <?=$process->past_due?> days
                                    <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    if($process->efficiency != "" && $process->efficiency > 100) {
                                        ?>
                                        <?php echo ((int)$process->efficiency == 200) ? 100 : ((int)$process->efficiency)?>%
                                    <?php } ?>
                                    <?php
                                    if($process->efficiency != "" && $process->efficiency < 100) {
                                        ?>
                                        <?php echo abs((int)($process->efficiency-100));?>%
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($process->status == 1): ?>
                                        Close
                                    <?php endif; ?>
                                    <?php if ($process->status == 2): ?>
                                        <?php $close_count++; ?>
                                        Open
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($process->map_type == 0): ?>
                                        TBD
                                    <?php endif; ?>
                                    <?php if ($process->map_type == 2): ?>
                                        Process Map
                                    <?php endif; ?>
                                    <?php if ($process->map_type == 1): ?>
                                        Mind Map
                                    <?php endif; ?>
                                </td>
                                <td style="min-width: 145px;">
                                    <a type="button" class="btn btn-primary btn-sm" onclick = "redirect_view_map(<?=$process->sp_id?>,<?=$process->map_type?>)">View</a>
                                    <a type="button" class="btn btn-primary btn-sm" <?php if ($process->map_type == 0): ?> onclick = "check_map('<?=$process->sp_id?>')" <?php else: ?> onclick = "redirect_map(<?=$process->sp_id?>,<?=$process->map_type?>)" <?php endif; ?>>Edit</a>
                                </td>
                            </tr>
                            <?php $count++;
                        } ?>
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
<div id="SelectMap_Modal" class="modal fade">
    <div class="modal-dialog" style = "width: 300px;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title">Select Map Type</h6>
            </div>
            <div class="modal-body">
                    <div class="radio" >
                        <label><input type="radio" class="styled" id="mind_map" name="map" value="1" checked>Mind Map</label>
                    </div>
                    <div class="radio" >
                        <label><input type="radio" class="styled" id="process_map" name="map" value="2">Process Map</label>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="edit_checklist()">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- /page container -->

<script type="text/javascript">
    var process_id = 0;
//    console.clear();
    function check_close_audit(){
        var close_count = "<?=$close_count?>";
        if (close_count != "0"){
            var dialog = bootbox.dialog({
                title: 'Warning',
                message: "You cannot close audit before all process status is close.",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "Ok",
                        className: 'btn-danger',
                        callback: function() {
                            dialog.modal('hide');
                        }
                    }
                }
            });
            return false;
        }
        location.href = "<?php echo base_url(); ?>index.php/employee/close_audit_plan/<?=$audit_id?>";
    }
    function redirect_map(id,map_type){
        if (map_type == 1){
            location.href = "<?php echo base_url(); ?>index.php/employee/edit_checklist_mind/"+id;
        }
        if (map_type == 2){
            location.href = "<?php echo base_url(); ?>index.php/employee/edit_checklist_process/"+id;
        }
    }
    function redirect_view_map(id,map_type){
        if (map_type == 1){
            location.href = "<?php echo base_url(); ?>index.php/employee/view_checklist_mind/"+id;
        }
        if (map_type == 2){
            location.href = "<?php echo base_url(); ?>index.php/employee/view_checklist_process/"+id;
        }
        if (map_type == 0){
            var dialog = bootbox.dialog({
                title: 'Warning',
                message: "No map.",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "Ok",
                        className: 'btn-danger',
                        callback: function() {
                            dialog.modal('hide');
                        }
                    }
                }
            });
        }
    }
    function edit_checklist(){
        var map_type = $("#mind_map").prop("checked");
        if (map_type == true){
            location.href = "<?php echo base_url(); ?>index.php/employee/edit_checklist_mind/"+process_id;
        }else if (map_type == false){
            location.href = "<?php echo base_url(); ?>index.php/employee/edit_checklist_process/"+process_id;
        }
    }
    function check_map(id){
        process_id = id;
        $("#SelectMap_Modal").modal();
        $("#SelectMap_Modal").show();
    }
</script>

</body>
</html>
