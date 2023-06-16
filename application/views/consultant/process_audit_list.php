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

    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
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
                                $logo1 = $this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row();

                                $dlogo = $this->db->query("select * from `default_setting` where `id`='1'")->row()->logo;

                                if ($logo1->logo == '1') {
                                    $logo = $dlogo;
                                } else {
                                    $logo = $logo1->logo;
                                }
                            }
                            ?>
                            <img src="<?php echo base_url(); ?>uploads/logo/<?= $logo ?>" style="height:50px;">
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

                                <!--<button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal_theme_primary">New Audit</button>-->
                                <a type="button" class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>index.php/Consultant/add_audit_form">New Audit</a>
                            </div>
                        </h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/consultantdashboard"><i
                                    class="icon-home2 role-left"></i>Home</a></li>
                        <li>Manage</li>
                        <li><a href="#"><?= $title ?></a></li>

                    </ul>

                    <ul class="breadcrumb-elements">

                    </ul>
                </div>
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content">
                <?php
                if($this->session->flashdata('message')=='success') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">Thank you!</span> Process Audit Successfully created..
                    </div>
                <?php  $this->session->unset_userdata('message'); } ?>
                <?php if ($this->session->flashdata('message') == 'overflow') { ?>
                    <div class="alert alert-warning alert-styled-left alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">Oppps!</span> You have Maximum Limit reached..
                    </div>
                <?php  $this->session->unset_userdata('message'); } ?>
                <?php if($this->session->flashdata('message')=='failed') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">Oppps!</span> Something Went Wrong Please try again.
                    </div>
                <?php  $this->session->unset_userdata('message'); } ?>
                <?php if ($this->session->flashdata('message') == 'success_del') { ?>
                    <div
                        class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        Process Audit Successfully Deleted..
                    </div>
                <?php $this->session->unset_userdata('message'); } ?>
                <?php if ($this->session->flashdata('message') == 'update_success') { ?>
                    <div
                        class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        Process Audit Successfully Updated..
                    </div>
                <?php $this->session->unset_userdata('message'); } ?>
                <div class="panel panel-body border-top-danger text-center">
                    <h6 class="no-margin text-semibold">Account Status</h6>

                    <div class="pace-demo" style="padding-bottom: 30px;">
                        <div class="theme_bar_sm">
                            <div class="pace_progress" data-progress-text="60%" data-progress="60"
                                 style="width:<?= $reached ?>%;"> <?= $total_account ?>/<?= $limit ?></div>
                        </div>
                    </div>
                </div>

                <!-- Basic datatable -->
                <div class="panel panel-flat" style="overflow:auto;">
                    <table class="table datatable-basic">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Type of Audit</th>
                            <th>Lead Auditor</th>
                            <th>FREQUENCY</th>
                            <th>Trigger</th>
                            <th>TYPE</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1;
                        foreach ($audits as $audit) { ?>
                            <tr>
                                <td><?= $count ?></td>
                                <td><?= $audit->type_of_audit ?></td>
                                <td><?= $audit->employee_name ?></td>
                                <td><?= $audit->frequency_name ?></td>
                                <td><?= $audit->trigger_name ?></td>
                                <td><?= $audit->type ?></td>
                                <td>
                                    <ul class="icons-list">
                                        <li class="text-primary-600" onclick="edit(<?= $audit->pa_id ?>);"><a
                                                href="#"><i class="icon-pencil7"></i></a></li>
                                        <li class="text-danger-600"><a href="#" id="<?= $audit->pa_id ?>"
                                                                       class="delete"><i class="icon-trash"></i></a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <?php $count++;
                        } ?>
                        </tbody>
                    </table>
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
<!-- /page container -->

<!-- Primary modal -->
<div id="modal_edit_audit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-user role-right"></i> Edit Process Audit</h6>
            </div>
            <form action="<?php echo base_url(); ?>index.php/Consultant/edit_audit" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Type of Audit: </label>
                                <select class="form-control" name="audit_type" id="audit_type" required>
                                    <?php foreach ($audit_types as $audit_type) { ?>
                                        <option value="<?= $audit_type->type_id ?>"><?= $audit_type->type_of_audit ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Lead Auditor: </label>
                                <select class="form-control" name="lead_audit" id="lead_audit" required>
                                    <?php foreach ($lead_audits as $lead_audit) { ?>
                                        <option value="<?= $lead_audit->employee_id ?>"><?= $lead_audit->employee_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Frequency: </label>
                                <select class="form-control" name="frequency" id="frequency" required>
                                    <?php foreach ($frequences as $frequency) { ?>
                                        <option value="<?= $frequency->frequency_id ?>"><?= $frequency->frequency_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Trigger: </label>
                                <select class="form-control" name="trigger" id="trigger" required>
                                    <?php foreach ($triggers as $trigger) { ?>
                                        <option value="<?= $trigger->trigger_id ?>"><?= $trigger->trigger_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="pa_id" id="pa_id">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="icon-plus2 role-right"></i> Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->

<script type="text/javascript">
    $('body').on('click', '.delete' ,function(e){
        var id = $(this).attr('id');
        var dialog = bootbox.dialog({
            title: 'Confirmation',
            message: "<h4>Are You Sure ?</h4>",
            size: 'small',
            buttons: {
                cancel: {
                    label: "Cancel",
                    className: 'btn-danger',
                    callback: function() {
                        dialog.modal('hide');
                    }
                },
                ok: {
                    label: "OK",
                    className: 'btn-success',
                    callback: function() {
                        window.location.href = "<?php echo base_url();?>index.php/Consultant/delete_audit/" + id;
                    }
                }
            }
        });
    });
    function edit(val) {
        $('#modal_edit_audit').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/findaudit",
            data: {'id': val},
            success: function (data) {
                var datas = $.parseJSON(data)
                $("#audit_type").val(datas.audit_type);
                $("#lead_audit").val(datas.lead_auditor);
                $("#frequency").val(datas.frequency);
                $("#trigger").val(datas.trigger);
                $("#pa_id").val(datas.pa_id);
            }
        });
    }
</script>

<script type="text/javascript">
    console.clear();
</script>

</body>
</html>
