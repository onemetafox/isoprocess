<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$title?></title>
<!--    <link href="http://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">-->
    <link href="<?=base_url(); ?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url(); ?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url(); ?>assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url(); ?>assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
 -->	<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/core/app.js"></script>
    <!-- <script type="text/javascript" src="<?=base_url(); ?>assets/js/pages/datatables_basic.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>

    <style type="text/css">
        .smlist {
            background-color:#26a69a;
            color: #fff;
        }
    </style>

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
                        <h4>
                            <i class="icon-lan2 position-left"></i> <span class="text-semibold"><?=$title?></span>
                        </h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>index.php/employee/mainmenu"><i class="icon-home2 position-left"></i>Home</a></li>
                        <li><a href="#"><?=$title?></a></li>
                    </ul>
                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                <!-- Basic datatable -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="panel panel-flat">
                                <table class="table datatable-basic">
                                    <thead>
                                    <tr>
                                        <th colspan="2">STATUS</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Total # of Gaps</td>
                                        <td><?=$total_gaps?></td>
                                    </tr>
                                    <tr>
                                        <td>Total # of Open Actions</td>
                                        <td><?=$total_open_actions?></td>
                                    </tr>
                                    <tr>
                                        <td>Total # of Closed Actions</td>
                                        <td><?=$total_closed_actions?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="panel panel-flat">
                                <table class="table datatable-basic">
                                    <thead>
                                    <tr>
                                        <th colspan="4">SPA</th>
                                    </tr>
                                    <tr>
                                        <th>SPA / LINK TO CAR</th>
                                        <th>Total Actions Assigned</th>
                                        <th>Total Open Items</th>
                                        <th>Total Open Items Past Due</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($spas as $spa) { ?>
                                        <tr>
                                            <td><a href="<?php echo base_url(); ?>index.php/employee/corrective_actions_log?spa_name=<?=$spa['spa_name']?>" class="invlist"><?=$spa['spa_name']?></a></td>
                                            <td><?=$spa['total']?></td>
                                            <td><?=$spa['open']?></td>
                                            <?php
                                            if($spa['past'] == 0) { ?>
                                            <td style="background-color: #34A853;"><?= $spa['past'] ?></td>
                                            <?php
                                            } else {
                                            ?>
                                            <td style="background-color: #EA4335;"><?= $spa['past'] ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="panel panel-flat">
                                <table class="table datatable-basic">
                                    <thead>
                                    <tr>
                                        <th colspan="4">SME</th>
                                    </tr>
                                    <tr>
                                        <th>SPA / LINK TO CAR</th>
                                        <th>Total Actions Assigned</th>
                                        <th>Total Open Items</th>
                                        <th>Total Open Items Past Due</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($smes as $sme) { ?>
                                        <tr>
                                            <td><a href="<?php echo base_url(); ?>index.php/employee/corrective_actions_log?sme_name=<?=$sme['sme_name']?>"><?=$sme['sme_name']?></a></td>
                                            <td><?=$sme['total']?></td>
                                            <td><?=$sme['open']?></td>
                                            <?php
                                            if($sme['past'] == 0) { ?>
                                                <td style="background-color: #34A853;"><?= $sme['past'] ?></td>
                                                <?php
                                            } else {
                                                ?>
                                                <td style="background-color: #EA4335;"><?= $sme['past'] ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
<!-- /page container -->
</body>

</html>
