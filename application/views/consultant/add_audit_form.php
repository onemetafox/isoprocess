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
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="<?php echo  base_url(); ?>index.php/Consultant/add_audit_action" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span class="help-block">Type of Audit :</span>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="audit_type" id="audit_type" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <a data-toggle="modal" data-target="#audit_types" class="btn btn-primary">MANAGE</a>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="help-block">Lead Auditor :</span>
                                                <div class="col-md-12">
                                                    <select class="form-control" name="lead_auditor" id="lead_auditor" required>
                                                        <?php foreach ($lead_audits as $lead) { ?>
                                                            <option value="<?= $lead->employee_id ?>"><?= $lead->employee_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-4">
                                                <span class="help-block">FREQUENCY :</span>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="frequency" id="frequency" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <a data-toggle="modal" data-target="#frequencys" class="btn btn-primary">MANAGE</a>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="help-block">Type :</span>
                                                <div class="col-md-12">
                                                    <select class="form-control" name="type" id="type" required>
                                                        <option value="Audit">Audit</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-4">
                                                <span class="help-block">TRIGGER :</span>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="trigger" id="trigger" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <a data-toggle="modal" data-target="#triggers" class="btn btn-primary">MANAGE</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-4">
                                                <div class="text-right">
                                                    <input type="submit" id="save" class="btn btn-primary" value="Save" name="save">
                                                    <input type="reset" id="reset" class="btn btn-danger" value="Reset" name="Reset">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>

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
    console.clear();
</script>

<?php $this->load->view('consultant/manage/audit_type'); ?>
<?php $this->load->view('consultant/manage/frequency'); ?>
<?php $this->load->view('consultant/manage/trigger'); ?>

</body>
</html>
