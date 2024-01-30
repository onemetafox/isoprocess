<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$title?></title>
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
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
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/pages/form_bootstrap_select.js"></script>
    <!-- /core JS files -->

    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/core/app.js"></script>
    <!-- <script type="text/javascript" src="<?=base_url(); ?>assets/js/pages/datatables_basic.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
    <script type="text/javascript">
        $(function() {
            // Style checkboxes and radios
            $('.styled').uniform();

        });
    </script>

    <style type="text/css">
        textarea {
            min-width: 100%;
            max-width: 100%;
            min-height: 100px;
            max-height: 100px;;
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
                        <h4><?php
                            if($this->session->userdata('employee_id')) {
                                $consultant_id1= $this->session->userdata('consultant_id');
                                $logo1=$this->db->query("select * from `consultant` where `consultant_id`='$consultant_id1'")->row();
                                $dlogo=$this->db->query("select * from `default_setting` where `id`='1'")->row()->logo;
                                if ($logo1->logo=='1') {
                                    $logo=$dlogo;
                                }else{
                                    $logo=$logo1->logo;
                                }
                            }
                            ?>
                            <img src="<?php echo base_url(); ?>uploads/logo/<?=$logo?>" style="height:50px;">
                            <span class="text-semibold"><?=$title?></span>
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_new_process">New Process</button>
                            </div>
                        </h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/employeedashboard"><i class="icon-home2 role-left"></i> Home</a></li>
                        <li>Manage</li>
                        <li><a href="#"><?=$title?></a></li>
                    </ul>

                    <ul class="breadcrumb-elements">

                    </ul>
                </div>
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content">
                <?php if($this->session->flashdata('message')=='success') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        Process Successfully Created..
                    </div>
                <?php $this->session->unset_userdata('message');  } ?>
                <?php if($this->session->flashdata('message')=='update_success') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        Process Successfully Updated..
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
                        Process Successfully Deleted..
                    </div>
                <?php  $this->session->unset_userdata('message'); } ?>
                <form class="form-horizontal" method="post" action="<?php echo  base_url(); ?>index.php/Employee/process_manage" enctype="multipart/form-data" name="process_form">
                    <div class="panel panel-body  text-left" style="padding-bottom: 0px;">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="btn-group bootstrap-select dropdown">
                                            <select id="audit_type_sel" name="audit_type_sel" class="bootstrap-select" onchange="filterAuditType();">
                                                <?php
                                                foreach ($type_of_audits as $type_of_audit) {
                                                    ?>
                                                    <option value="<?= $type_of_audit->type_id ?>"
                                                        <?php
                                                        $type_id = $this->input->post('audit_type_sel');
                                                        if($type_id == $type_of_audit->type_id) {
                                                        ?>
                                                            selected
                                                        <?php } ?>
                                                    >
                                                        <?= $type_of_audit->type_of_audit ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Basic datatable -->
                <div class="panel panel-flat">
                    <table class="table datatable-basic">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Process Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="process_tbody">
                        <?php  $count=1;
                        foreach ($processes as $process) { ?>
                            <tr>
                                <td style="min-width: 5%;"><?=$count?></td>
                                <td style="min-width: 15%;"><?=$process->process_name?></td>
                                <td style="min-width: 75%;"><?=$process->description?></td>
                                <td style="min-width: 5%;">
                                    <ul class="icons-list">
                                        <li class="text-primary-600" onclick="edit(<?=$process->process_id?>);"><a href="#"><i class="icon-pencil7"></i></a></li>
                                        <li class="text-danger-600"><a href="#" id="<?=$process->process_id?>" class="delete" ><i class="icon-trash"></i></a></li>
                                    </ul>
                                </td>
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
<!-- /page container -->

<script type="text/javascript">
    function filterAuditType() {
        document.process_form.submit();
    }

    function edit(val) {
        $('#modal_edit_process').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Employee/findprocess",
            data: {'id': val},
            success: function (data) {
                var datas = $.parseJSON(data)
                $("#edit_process_name").val(datas.process_name);
                $("#edit_description").val(datas.description);
                $("#process_id").val(datas.process_id);
            }
        });
    }

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
                        window.location.href = "<?php echo base_url();?>index.php/Employee/delete_process/" + id;
                    }
                }
            }
        });
    });

    console.clear();
</script>

</body>

<!-- Primary modal -->
<div id="modal_new_process" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-plus2 role-right"></i> New Process</h6>
            </div>
            <form action="<?php echo base_url();?>index.php/Employee/add_process_mng" method="post" name="add_process">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Process Name: </label>
                                <input type="text" placeholder="Process Name" class="form-control" name="process_name" id="process_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Process Description: </label>
                                <textarea placeholder="" class="form-control" name="description" id="description" required></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="type_sel_id" name="type_sel_id" value="<?=$audit_type_sel?>">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="icon-plus2 role-right"></i> Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_edit_process" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-plus2 role-right"></i> Edit Process</h6>
            </div>
            <form action="<?php echo base_url();?>index.php/Employee/edit_process_list" method="post" name="edit_process">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Process Name: </label>
                                <input type="text" placeholder="Process Name" class="form-control" name="edit_process_name" id="edit_process_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Process Description: </label>
                                <textarea placeholder="" class="form-control" name="edit_description" id="edit_description" required></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="process_id" id="process_id">
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
</html>
