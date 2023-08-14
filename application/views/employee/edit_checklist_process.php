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
            $('.dataTables_length select').select({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });
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
                        </h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/employeedashboard"><i
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
            <form id="create_checklist" action="<?php echo base_url(); ?>index.php/employee/create_checklist" method="post">
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
                        <a type="button" class="btn btn-primary btn-sm" style="margin-right : 20px; float: right;" onclick = "fn_downloadReport(<?=$process_id?>)">Download Report</a>
                        &nbsp;
                        <a data-toggle="modal" data-target="#modal_send_message" class="btn btn-primary btn-sm"><i class="icon-mail5"></i> Send Message</a>
                        <a type="button" class="btn btn-info btn-sm" href="<?php echo base_url(); ?>index.php/employee/show_process_message/<?=$process_id?>">
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
                                    <td >
                                        <p style=" text-overflow: ellipsis; white-space: nowrap; overflow: hidden; max-width:250px;">
                                            <?= implode(",", array_filter(json_decode($input_step->evidence))); ?>
                                        </p>
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
                                        <!-- <?php if ($input_step->status != 'Opportunity for Improvement'): ?>
                                            <a type="button" onclick = "logic_show(<?=$input_step->checklist_id?>)" class="btn btn-primary btn-sm">Logic</a>
                                        <?php endif;?> -->
                                        <?php if ($input_step->status != 'Conformity Table'): ?>
                                            <a type="button" onclick = "logic_show(<?=$input_step->checklist_id?>)" class="btn btn-primary btn-sm">Logic</a>

                                            <?php if($input_step->load_status == 0){?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$input_step->checklist_id?>" class="btn btn-primary btn-sm">Load</a>
                                            <?php } else?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form_view/<?=$input_step->checklist_id?>" class="btn btn-primary btn-sm">View</a>
                                        <?php endif; ?>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "edit_checklist(-1,<?=$input_step->checklist_id?>)">Edit</a>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "delete_checklist(-1,<?=$input_step->checklist_id?>)">Delete</a>
                                        <?php if ($input_step->corrective_id != ''): ?>
                                        <a type="button" href = "<?php echo base_url(); ?>index.php/employee/resolution/<?=$input_step->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
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
                                    <td >
                                        <p style=" text-overflow: ellipsis; white-space: nowrap; overflow: hidden; max-width:250px;">
                                            <?= implode(",", array_filter(json_decode($activity->evidence))); ?>
                                        </p>
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
                                        <?php if ($activity->status != 'Conformity Table'): ?>
                                            <a type="button" onclick = "logic_show(<?=$activity->checklist_id?>)" class="btn btn-primary btn-sm">Logic</a>
                                            <?php if($activity->load_status == 0){?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$activity->checklist_id?>" class="btn btn-primary btn-sm">Load</a>
                                            <?php }else?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$activity->checklist_id?>" class="btn btn-primary btn-sm">View</a>
                                        <?php endif; ?>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "edit_checklist(-2,<?=$activity->checklist_id?>)">Edit</a>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "delete_checklist(-1,<?=$activity->checklist_id?>)">Delete</a>
                                        <?php if ($activity->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/employee/resolution/<?=$activity->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
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
                                    <td >
                                        <p style=" text-overflow: ellipsis; white-space: nowrap; overflow: hidden; max-width:250px;">
                                            <?= implode(",", array_filter(json_decode($output->evidence))); ?>
                                        </p>
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
                                        <?php if ($output->status != 'Conformity Table'): ?>
                                            <a type="button" onclick = "logic_show(<?=$output->checklist_id?>)" class="btn btn-primary btn-sm">Logic</a>
                                            <?php if($output->load_status == 0){?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$output->checklist_id?>" class="btn btn-primary btn-sm">Load</a>
                                            <?php } else ?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$output->checklist_id?>" class="btn btn-primary btn-sm">View</a>
                                        <?php endif; ?>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "edit_checklist(-3,<?=$output->checklist_id?>)">Edit</a>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "delete_checklist(-1,<?=$output->checklist_id?>)">Delete</a>
                                        <?php if ($output->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/employee/resolution/<?=$output->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
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
                                    <td >
                                        <p style=" text-overflow: ellipsis; white-space: nowrap; overflow: hidden; max-width:250px;">
                                            <?= implode(",", array_filter(json_decode($control->evidence))); ?>
                                        </p>
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
                                        <?php if ($control->status != 'Conformity Table' || $control->status == ""): ?>
                                            <a type="button" onclick = "logic_show(<?=$control->checklist_id?>)" class="btn btn-primary btn-sm">Logic</a>
                                            <?php if($control->load_status == 0) {?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$control->checklist_id?>" class="btn btn-primary btn-sm">Load</a>
                                            <?php } else?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$control->checklist_id?>" class="btn btn-primary btn-sm">View</a>
                                        <?php endif; ?>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "edit_checklist(-4,<?=$control->checklist_id?>)">Edit</a>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "delete_checklist(-1,<?=$control->checklist_id?>)">Delete</a>
                                        <?php if ($control->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/employee/resolution/<?=$control->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
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
                                    <td >
                                        <p style=" text-overflow: ellipsis; white-space: nowrap; overflow: hidden; max-width:250px;">
                                            <?= implode(",", array_filter(json_decode($resource->evidence))); ?>
                                        </p>
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
                                        <?php if ($resource->status != 'Conformity Table'): ?>
                                            <a type="button" onclick = "logic_show(<?=$resource->checklist_id?>)" class="btn btn-primary btn-sm">Logic</a>
                                            <?php if($resource->load_status == 0){?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$resource->checklist_id?>" class="btn btn-primary btn-sm">Load</a>
                                            <?php } else?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$resource->checklist_id?>" class="btn btn-primary btn-sm">View</a>
                                        <?php endif; ?>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "edit_checklist(-5,<?=$resource->checklist_id?>)">Edit</a>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "delete_checklist(-1,<?=$resource->checklist_id?>)">Delete</a>
                                        <?php if ($resource->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/employee/resolution/<?=$resource->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
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
                                        <!-- <?php if ($resource->status != 'Opportunities'): ?>
                                            <a type="button" onclick = "logic_show(<?=$effectiveness->checklist_id?>)" class="btn btn-primary btn-sm">Logic</a>
                                        <?php endif; ?>
                                        <?php if($effectiveness->load_status == 0){?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$effectiveness->checklist_id?>" class="btn btn-primary btn-sm">Load</a>
                                        <?php } else?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$effectiveness->checklist_id?>" class="btn btn-primary btn-sm">Load</a>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "edit_checklist(-6,<?=$effectiveness->checklist_id?>)">Edit</a>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "delete_checklist(-1,<?=$effectiveness->checklist_id?>)">Delete</a>
                                        <?php if ($effectiveness->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/employee/resolution/<?=$effectiveness->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
                                        <?php endif; ?> -->

                                        <?php if ($effectiveness->status != 'Conformity Table'): ?>
                                            <a type="button" onclick = "logic_show(<?=$effectiveness->checklist_id?>)" class="btn btn-primary btn-sm">Logic</a>
                                            <?php if($effectiveness->load_status == 0){?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$effectiveness->checklist_id?>" class="btn btn-primary btn-sm">Load</a>
                                            <?php } else?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form/<?=$effectiveness->checklist_id?>" class="btn btn-primary btn-sm">View</a>
                                        <?php endif; ?>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "edit_checklist(-5,<?=$effectiveness->checklist_id?>)">Edit</a>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "delete_checklist(-1,<?=$effectiveness->checklist_id?>)">Delete</a>
                                        <?php if ($effectiveness->corrective_id != ''): ?>
                                            <a type="button" href = "<?php echo base_url(); ?>index.php/employee/resolution/<?=$effectiveness->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
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

                <form id = "download_pdf_checklist" action="<?php echo base_url(); ?>index.php/Consultant/download_pdf_checklist" method="post">
                    <input type = "hidden" id = "download_id" name = "download_id">
                    <textarea id = "download_text" name="download_text" style="display: none;"></textarea>
                </form>

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

<div id="modal_sign" class="modal fade">
    <div class="modal-dialog">
        <center>
            <fieldset style="width: 500px;">
                <br/>
                <br/>
                <H1 style="color: white;">You have to sign your name.</H1>
                <div id="signaturePad" style="background-color:white; border: 1px solid #ccc; height: 250px; width: 500px;">
                </div>
                <br/>
                <button id="saveSig" type="button" class="btn bg-primary"><i class="icon-floppy-disk position-left"></i> Save Signature</button>&nbsp;
                <button id="clearSig" type="button" class="btn bg-primary"><i class="icon-trash position-left"></i> Clear Signature</button>&nbsp;
                <button id="saveSig" type="button" class="btn bg-primary" data-dismiss="modal"><i class="icon-cancel-square2 position-left"></i> Cancel</button>
                <div id="imgData"></div>
                <div id="imgData"></div>
                <br/>
                <br/>
            </fieldset>
        </center>
    </div>
</div>

<!-- /primary modal -->

<script type="text/javascript">
    var process_id = 0;
    var base_url = "<?php echo  base_url(); ?>index.php/Consultant/";
    //console.clear();
    function redirect(){
        location.href = "<?php echo base_url(); ?>index.php/employee/open_audit";
    }
    function edit_checklist(){


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

    function fn_downloadReport(val){
    $.ajax({
        type: "GET",
        url: "<?php echo base_url(); ?>index.php/Consultant/get_download_temp_checklist/"+val,
        success: function(data) {
            $("#download_text").val(data);
            $("#download_id").val(val);
            document.forms['download_pdf_checklist'].submit();
        }
    });
//        $("#modal_sign").modal();
    }

    $(document).ready(function () {
        /** Set Canvas Size **/
        var canvasWidth = 498;
        var canvasHeight = 248;

        /** IE SUPPORT **/
        var canvasDiv = document.getElementById('signaturePad');
        canvas = document.createElement('canvas');
        canvas.setAttribute('width', canvasWidth);
        canvas.setAttribute('height', canvasHeight);
        canvas.setAttribute('id', 'canvas');
        canvasDiv.appendChild(canvas);
        if (typeof G_vmlCanvasManager != 'undefined') {
            canvas = G_vmlCanvasManager.initElement(canvas);
        }
        context = canvas.getContext("2d");

        var clickX = new Array();
        var clickY = new Array();
        var clickDrag = new Array();
        var paint;

        /** Redraw the Canvas **/
        function redraw() {
            canvas.width = canvas.width; // Clears the canvas

            context.strokeStyle = "#000000";

            context.lineWidth = 2;

            for (var i = 0; i < clickX.length; i++) {
                context.beginPath();
                if (clickDrag[i] && i) {
                    context.moveTo(clickX[i - 1], clickY[i - 1]);
                } else {
                    context.moveTo(clickX[i] - 1, clickY[i]);
                }
                context.lineTo(clickX[i], clickY[i]);
                context.closePath();
                context.stroke();
            }
        }

        /** Save Canvas **/
        $("#saveSig").click(function saveSig() {
            var id = "<?=$process_id?>";
            var dialog = bootbox.dialog({
                message: "<h4>Are You Sure to Save Your Signature ?</h4>",
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
                            var sigData = canvas.toDataURL("image/png");
                            var nicURI = base_url+"save_signature_monitoring";
                            var A = new FormData();
                            A.append("id", id);
                            A.append("sign", sigData);
                            var C = new XMLHttpRequest();
                            C.open("POST", nicURI);
                            C.onload = function() {
                                var E;
                                E = C.responseText;
                                if (E.indexOf("fail") >= 0) {
                                    $("#imgData").html('Sorry! Your signature was not saved');
                                    return;
                                }else{
                                    $("#modal_sign").modal("hide");
//                                    $("#sign_info").val(E);
//                                    $("#submit_form").submit();
                                    var val = "<?=$process_id?>";
                                    $.ajax({
                                        type: "GET",
                                        url: "<?php echo base_url(); ?>index.php/Consultant/get_download_temp_checklist/"+val+"?img_name="+E,
                                        success: function(data) {
                                            $("#download_text").val(data);
                                            $("#download_id").val(val);
                                            document.forms['download_pdf_checklist'].submit();
                                        }
                                    });
                                }
                            };
                            C.send(A);
                        }
                    }
                }
            });
        });

        $('#clearSig').click(
            function clearSig() {
                clickX = new Array();
                clickY = new Array();
                clickDrag = new Array();
                context.clearRect(0, 0, canvas.width, canvas.height);
            });

        /**Draw when moving over Canvas **/
        $('#signaturePad').mousemove(function (e) {
            this.style.cursor = 'pointer';
            if (paint) {
                var left = $(this).offset().left;
                var top = $(this).offset().top;

                addClick(e.pageX - left, e.pageY - top, true);
                redraw();
            }
        });

        /**Stop Drawing on Mouseup **/
        $('body').mouseup(function (e) {
            paint = false;
        });

        /** Starting a Click **/
        function addClick(x, y, dragging) {
            clickX.push(x);
            clickY.push(y);

            clickDrag.push(dragging);
        }

        $('#signaturePad').mousedown(function (e) {
            paint = true;

            var left = $(this).offset().left;
            var top = $(this).offset().top;

            addClick(e.pageX - left, e.pageY - top, false);
            redraw();
        });
    });

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
                        document.forms['create_checklist'].action = "<?php echo base_url(); ?>index.php/employee/submit_checklist";
                        document.forms['create_checklist'].submit();
                    }
                }
            }
        });

    }
    function edit_checklist(id,checklist_id){
        $("#clause_id").val(id);
        $("#checklist_id").val(checklist_id);
        document.forms['create_checklist'].action = "<?php echo base_url(); ?>index.php/employee/edit_checklist";
        document.forms['create_checklist'].submit();
    }
    function delete_checklist(id,checklist_id){

        var dialog = bootbox.dialog({
            title: 'Warning',
            message: "<p>Are you sure?</p>",
            size: 'small',
            buttons: {
                cancel: {
                    label: "NO",
                    className: 'btn-danger',
                    callback: function(){
                    }
                },

                ok: {
                    label: "YES",
                    className: 'btn-info',
                    callback: function(){
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>index.php/employee/delete_checklist",
                            data:{ 'checklist_id' : checklist_id},
                            success: function(data) {
                                location.reload();
                            }
                        });
                    }
                }
            }
        });
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
                 url: "<?php echo base_url(); ?>index.php/employee/send_process_message",
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
<?php $this->load->view('consultant/manage/logic'); ?>
</body>
</html>
