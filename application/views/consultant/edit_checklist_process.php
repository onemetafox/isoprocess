<?php
function view_criteria($criteria1, $criteria2, $criteria3, $criteria4) {
    $total = '';
    $first = true;
    if($criteria1 != NULL && $criteria1 != 'N/A') {
        if($first) {
            $total .= $criteria1;
            $first = false;
        }
        else    $total .= ', ' . $criteria1;
    }
    if($criteria2 != NULL && $criteria2 != 'N/A') {
        if($first) {
            $total .= $criteria2;
            $first = false;
        }
        else    $total .= ', ' . $criteria2;
    }
    if($criteria3 != NULL && $criteria3 != 'N/A') {
        if($first) {
            $total .= $criteria3;
            $first = false;
        }
        else    $total .= ', ' . $criteria3;
    }
    if($criteria4 != NULL && $criteria4 != 'N/A') {
        if($first) {
            $total .= $criteria4;
            $first = false;
        }
        else    $total .= ', ' . $criteria4;
    }
    return $total;
}
?>
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
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/pages/datatables_basic.js"></script>

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
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });
        });
    </script>
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
                        <li>Process Audit</li>
                        <li>Open Audit Log</li>
                        <li>Edit Audit Plan</li>
                        <li><a href="#"><?= $title ?></a></li>

                    </ul>

                    <ul class="breadcrumb-elements">

                    </ul>
                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <form id="create_checklist" action="<?php echo base_url(); ?>index.php/Consultant/create_checklist" method="post">
                <input type="hidden" id = "process_id" name="process_id" value = "<?=$process_id?>">
                <input type="hidden" id = "clause_id" name="clause_id" value = "0">
                <input type="hidden" id = "checklist_id" name="checklist_id" value = "0">
            </form>
            <div class="content">
                <!-- Basic datatable -->
                <div class="panel" style="min-height: 60px;">
                    <div style="min-height: 10px;height: 10%;"></div>
                    <div style="height: 80%;padding-left: 1%;padding-right: 1%;">
                        <a type="button" class="btn btn-primary btn-sm" style="float: right;" onclick = "submit_checklist()">Submit CheckList</a>
                        &nbsp;
                        <a data-toggle="modal" data-target="#modal_send_message" class="btn btn-primary btn-sm"><i class="icon-mail5"></i> Send Message</a>
                        <a type="button" class="btn btn-info btn-sm" href="<?php echo base_url(); ?>index.php/Consultant/show_process_message/<?=$process_id?>">
                            <i class="icon-mail-read"></i> View Message
                        </a>
                    </div>
                    <div style="min-height: 10px;height: 10%;"></div>
                </div>
                <div class="panel" style="padding-left:10px;padding-right: 10px;">
                    <div style="padding-left: 10px;">
                        <span><h4>Input Step</h4></span>
                    </div>
                    <div style="padding-left: 10px;display: inline-block;">
                        <a type="button" class="btn btn-primary btn-sm" style="float:left;margin-left: 10px;" onclick="create_checklist('-1')">NEW</a>
                    </div>
                    <div class="panel panel-flat" style="overflow:auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Process Step</th>
                                <th>Questions</th>
                                <th>Audit Criteria</th>
                                <th>Expected Answer</th>
                                <th>Audit Trail</th>
                                <th>Evidence</th>
                                <th>Status</th>
                                <th>Comments/Notes</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($input_step as $input_step) { ?>
                                <tr>
                                    <td><?= $input_step->process_step ?></td>
                                    <td><?= $input_step->questions ?></td>
                                    <td><?= view_criteria($input_step->criteria_id, $input_step->criteria_id2, $input_step->criteria_id3, $input_step->criteria_id4) ?></td>
                                    <td>
                                        <?php if ($input_step->answer == 0): ?>
                                            Not Sure
                                        <?php endif; ?>
                                        <?php if ($input_step->answer == 1): ?>
                                            NO
                                        <?php endif; ?>
                                        <?php if ($input_step->answer == 2): ?>
                                            YES
                                        <?php endif; ?>
                                        <?php if ($input_step->answer == -1): ?>
                                            TBD
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($input_step->audit_trail != null && $input_step->audit_trail != ""): ?>
                                            <?php if ($input_step->audit_trail == 0): ?>
                                                Not Sure
                                            <?php endif; ?>
                                            <?php if ($input_step->audit_trail == 1): ?>
                                                NO
                                            <?php endif; ?>
                                            <?php if ($input_step->audit_trail == 2): ?>
                                                YES
                                            <?php endif; ?>
                                            <?php if ($input_step->audit_trail == -1): ?>
                                                TBD
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            if (strlen($input_step->evidence) > 45){
                                                echo substr($input_step->evidence,0,45)."...";
                                            }else{
                                                echo $input_step->evidence;
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?= $input_step->status ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($input_step->note) > 45){
                                            echo substr($input_step->note,0,45)."...";
                                        }else{
                                            echo $input_step->note;
                                        }
                                        ?>
                                    </td>
                                    <td style="width: 19%;">
                                        <?php if ($input_step->corrective_id != ''): ?>
                                        <a type="button" href = "<?php echo base_url(); ?>index.php/Consultant/resolution/<?=$input_step->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel" style="padding-left:10px;padding-right: 10px;">
                    <div style="padding-left: 10px;">
                        <span><h4>Activity</h4></span>
                    </div>
                    <div style="padding-left: 10px;display: inline-block;">
                        <a type="button" class="btn btn-primary btn-sm" style="float:left;margin-left: 10px;" onclick="create_checklist('-2')">NEW</a>
                    </div>
                    <div class="panel panel-flat" style="overflow:auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Process Step</th>
                                <th>Questions</th>
                                <th>Audit Criteria</th>
                                <th>Expected Answer</th>
                                <th>Audit Trail</th>
                                <th>Evidence</th>
                                <th>Status</th>
                                <th>Comments/Notes</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($activity as $activity) { ?>
                                <tr>
                                    <td><?= $activity->process_step ?></td>
                                    <td><?= $activity->questions ?></td>
                                    <td><?= view_criteria($activity->criteria_id, $activity->criteria_id2, $activity->criteria_id3, $activity->criteria_id4) ?></td>
                                    <td>
                                        <?php if ($activity->answer == 0): ?>
                                            Not Sure
                                        <?php endif; ?>
                                        <?php if ($activity->answer == 1): ?>
                                            NO
                                        <?php endif; ?>
                                        <?php if ($activity->answer == 2): ?>
                                            YES
                                        <?php endif; ?>
                                        <?php if ($activity->answer == -1): ?>
                                            TBD
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($activity->audit_trail != null && $activity->audit_trail != ""): ?>
                                            <?php if ($activity->audit_trail == 0): ?>
                                                Not Sure
                                            <?php endif; ?>
                                            <?php if ($activity->audit_trail == 1): ?>
                                                NO
                                            <?php endif; ?>
                                            <?php if ($activity->audit_trail == 2): ?>
                                                YES
                                            <?php endif; ?>
                                            <?php if ($activity->audit_trail == -1): ?>
                                                TBD
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($activity->evidence) > 45){
                                            echo substr($activity->evidence,0,45)."...";
                                        }else{
                                            echo $activity->evidence;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?= $activity->status ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($activity->note) > 45){
                                            echo substr($activity->note,0,45)."...";
                                        }else{
                                            echo $activity->note;
                                        }
                                        ?>
                                    </td>
                                    <td style="width: 19%;">
                                        <?php if ($activity->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/Consultant/resolution/<?=$activity->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel" style="padding-left:10px;padding-right: 10px;">
                    <div style="padding-left: 10px;">
                        <span><h4>Output</h4></span>
                    </div>
                    <div style="padding-left: 10px;display: inline-block;">
                        <a type="button" class="btn btn-primary btn-sm" style="float:left;margin-left: 10px;" onclick="create_checklist('-3')">NEW</a>
                    </div>
                    <div class="panel panel-flat" style="overflow:auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Process Step</th>
                                <th>Questions</th>
                                <th>Audit Criteria</th>
                                <th>Expected Answer</th>
                                <th>Audit Trail</th>
                                <th>Evidence</th>
                                <th>Status</th>
                                <th>Comments/Notes</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($output as $output) { ?>
                                <tr>
                                    <td><?= $output->process_step ?></td>
                                    <td><?= $output->questions ?></td>
                                    <td><?= view_criteria($output->criteria_id, $output->criteria_id2, $output->criteria_id3, $output->criteria_id4) ?></td>
                                    <td>
                                        <?php if ($output->answer == 0): ?>
                                            Not Sure
                                        <?php endif; ?>
                                        <?php if ($output->answer == 1): ?>
                                            NO
                                        <?php endif; ?>
                                        <?php if ($output->answer == 2): ?>
                                            YES
                                        <?php endif; ?>
                                        <?php if ($output->answer == -1): ?>
                                            TBD
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($output->audit_trail != null && $output->audit_trail != ""): ?>
                                            <?php if ($output->audit_trail == 0): ?>
                                                Not Sure
                                            <?php endif; ?>
                                            <?php if ($output->audit_trail == 1): ?>
                                                NO
                                            <?php endif; ?>
                                            <?php if ($output->audit_trail == 2): ?>
                                                YES
                                            <?php endif; ?>
                                            <?php if ($output->audit_trail == -1): ?>
                                                TBD
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($output->evidence) > 45){
                                            echo substr($output->evidence,0,45)."...";
                                        }else{
                                            echo $output->evidence;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?= $output->status ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($output->note) > 45){
                                            echo substr($output->note,0,45)."...";
                                        }else{
                                            echo $output->note;
                                        }
                                        ?>
                                    </td>
                                    <td style="width: 19%;">
                                        <?php if ($output->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/Consultant/resolution/<?=$output->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel" style="padding-left:10px;padding-right: 10px;">
                    <div style="padding-left: 10px;">
                        <span><h4>Control</h4></span>
                    </div>
                    <div style="padding-left: 10px;display: inline-block;">
                        <a type="button" class="btn btn-primary btn-sm" style="float:left;margin-left: 10px;" onclick="create_checklist('-4')">NEW</a>
                    </div>
                    <div class="panel panel-flat" style="overflow:auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Process Step</th>
                                <th>Questions</th>
                                <th>Audit Criteria</th>
                                <th>Expected Answer</th>
                                <th>Audit Trail</th>
                                <th>Evidence</th>
                                <th>Status</th>
                                <th>Comments/Notes</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($control as $control) { ?>
                                <tr>
                                    <td><?= $control->process_step ?></td>
                                    <td><?= $control->questions ?></td>
                                    <td><?= view_criteria($control->criteria_id, $control->criteria_id2, $control->criteria_id3, $control->criteria_id4) ?></td>
                                    <td>
                                        <?php if ($control->answer == 0): ?>
                                            Not Sure
                                        <?php endif; ?>
                                        <?php if ($control->answer == 1): ?>
                                            NO
                                        <?php endif; ?>
                                        <?php if ($control->answer == 2): ?>
                                            YES
                                        <?php endif; ?>
                                        <?php if ($control->answer == -1): ?>
                                            TBD
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($control->audit_trail != null && $control->audit_trail != ""): ?>
                                            <?php if ($control->audit_trail == 0): ?>
                                                Not Sure
                                            <?php endif; ?>
                                            <?php if ($control->audit_trail == 1): ?>
                                                NO
                                            <?php endif; ?>
                                            <?php if ($control->audit_trail == 2): ?>
                                                YES
                                            <?php endif; ?>
                                            <?php if ($control->audit_trail == -1): ?>
                                                TBD
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($control->evidence) > 45){
                                            echo substr($control->evidence,0,45)."...";
                                        }else{
                                            echo $control->evidence;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?= $control->status ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($control->note) > 45){
                                            echo substr($control->note,0,45)."...";
                                        }else{
                                            echo $control->note;
                                        }
                                        ?>
                                    </td>
                                    <td style="width: 19%;">
                                        <?php if ($control->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/Consultant/resolution/<?=$control->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel" style="padding-left:10px;padding-right: 10px;">
                    <div style="padding-left: 10px;">
                        <span><h4>Resource</h4></span>
                    </div>
                    <div style="padding-left: 10px;display: inline-block;">
                        <a type="button" class="btn btn-primary btn-sm" style="float:left;margin-left: 10px;" onclick="create_checklist('-5')">NEW</a>
                    </div>
                    <div class="panel panel-flat" style="overflow:auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Process Step</th>
                                <th>Questions</th>
                                <th>Audit Criteria</th>
                                <th>Expected Answer</th>
                                <th>Audit Trail</th>
                                <th>Evidence</th>
                                <th>Status</th>
                                <th>Comments/Notes</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($resource as $resource) { ?>
                                <tr>
                                    <td><?= $resource->process_step ?></td>
                                    <td><?= $resource->questions ?></td>
                                    <td><?= view_criteria($resource->criteria_id, $resource->criteria_id2, $resource->criteria_id3, $resource->criteria_id4) ?></td>
                                    <td>
                                        <?php if ($resource->answer == 0): ?>
                                            Not Sure
                                        <?php endif; ?>
                                        <?php if ($resource->answer == 1): ?>
                                            NO
                                        <?php endif; ?>
                                        <?php if ($resource->answer == 2): ?>
                                            YES
                                        <?php endif; ?>
                                        <?php if ($resource->answer == -1): ?>
                                            TBD
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($resource->audit_trail != null && $resource->audit_trail != ""): ?>
                                            <?php if ($resource->audit_trail == 0): ?>
                                                Not Sure
                                            <?php endif; ?>
                                            <?php if ($resource->audit_trail == 1): ?>
                                                NO
                                            <?php endif; ?>
                                            <?php if ($resource->audit_trail == 2): ?>
                                                YES
                                            <?php endif; ?>
                                            <?php if ($resource->audit_trail == -1): ?>
                                                TBD
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($resource->evidence) > 45){
                                            echo substr($resource->evidence,0,45)."...";
                                        }else{
                                            echo $resource->evidence;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?= $resource->status ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($resource->note) > 45){
                                            echo substr($resource->note,0,45)."...";
                                        }else{
                                            echo $resource->note;
                                        }
                                        ?>
                                    </td>
                                    <td style="width: 19%;">
                                        <?php if ($resource->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/Consultant/resolution/<?=$resource->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel" style="padding-left:10px;padding-right: 10px;">
                    <div style="padding-left: 10px;">
                        <span><h4>Effectiveness</h4></span>
                    </div>
                    <div style="padding-left: 10px;display: inline-block;">
                        <a type="button" class="btn btn-primary btn-sm" style="float:left;margin-left: 10px;" onclick="create_checklist('-6')">NEW</a>
                    </div>
                    <div class="panel panel-flat" style="overflow:auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Process Step</th>
                                <th>Questions</th>
                                <th>Audit Criteria</th>
                                <th>Evidence</th>
                                <th>Status</th>
                                <th>Comments/Notes</th>
                                <th>Effectiveness</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($effectiveness as $effectiveness) { ?>
                                <tr>
                                    <td><?= $effectiveness->process_step ?></td>
                                    <td><?= $effectiveness->questions ?></td>
                                    <td><?= view_criteria($effectiveness->criteria_id, $effectiveness->criteria_id2, $effectiveness->criteria_id3, $effectiveness->criteria_id4) ?></td>
                                    <td>
                                        <?php if ($effectiveness->answer == 0): ?>
                                            Not Sure
                                        <?php endif; ?>
                                        <?php if ($effectiveness->answer == 1): ?>
                                            NO
                                        <?php endif; ?>
                                        <?php if ($effectiveness->answer == 2): ?>
                                            YES
                                        <?php endif; ?>
                                        <?php if ($effectiveness->answer == -1): ?>
                                            TBD
                                        <?php endif; ?>
                                    </td>
                                    <td>opportunities</td>
                                    <td>
                                        <?php
                                        if (strlen($effectiveness->note) > 45){
                                            echo substr($effectiveness->note,0,45)."...";
                                        }else{
                                            echo $effectiveness->note;
                                        }
                                        ?>
                                    </td>
                                    <td><?=$effectiveness->effectiveness?></td>
                                    <td style="width: 19%;">
                                        <?php if ($effectiveness->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/Consultant/resolution/<?=$effectiveness->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            } ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-bottom: 10px;text-align: right;padding-right: 35px;">
                        <a type="button" class="btn btn-primary btn-sm" onclick="javascript:window.history.back()">Back</a>
                    </div>
                </div>
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
                <button type="button" class="btn btn-primary" onclick="sendMessage('<?=$process_id?>');"><i class="icon-reply role-right"></i> Send</button>
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->

<script type="text/javascript">
    var process_id = 0;
    console.clear();
    function redirect(){
        location.href = "<?php echo base_url(); ?>index.php/Consultant/open_audit";
    }
    function edit_checklist(){
        var map_type = $("#mind_map").prop("checked");
        if (map_type == true){
            location.href = "<?php echo base_url(); ?>index.php/Consultant/edit_checklist_mind/"+process_id;
        }else if (map_type == false){
            location.href = "<?php echo base_url(); ?>index.php/Consultant/edit_checklist_process/"+process_id;
        }

    }
    function check_map(id){
        process_id = id;
        $("#SelectMap_Modal").modal();
        $("#SelectMap_Modal").show();
    }
    function create_checklist(id){
        $("#clause_id").val(id);
        $("#checklist_id").val('0');
        document.forms['create_checklist'].submit();
    }
    function submit_checklist(){
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
                        document.forms['create_checklist'].action = "<?php echo base_url(); ?>index.php/Consultant/submit_checklist";
                        document.forms['create_checklist'].submit();
                    }
                }
            }
        });

    }
    function edit_checklist(id,checklist_id){
        $("#clause_id").val(id);
        $("#checklist_id").val(checklist_id);
        document.forms['create_checklist'].action = "<?php echo base_url(); ?>index.php/Consultant/edit_checklist";
        document.forms['create_checklist'].submit();
    }
    function logic_show(id){
        logic_checklist_id = id;
        $('#Audit_Plan_Modal').modal();
    }
    function sendMessage(val) {
        var message = $('#message').val();
        if(message.length == 0) {
            $("#message_err").html('* this field is required');
            return false;
        } else {
            $.ajax({
                 type: "POST",
                 url: "<?php echo base_url(); ?>index.php/Consultant/send_process_message",
                 data:{ 'process_id' : val, 'message' : message},
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
<?php $this->load->view('consultant/manage/clause'); ?>
<?php $this->load->view('consultant/manage/logic'); ?>
</body>
</html>
