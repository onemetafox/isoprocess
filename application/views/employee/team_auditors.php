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
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <!-- <script type="text/javascript" src="<?=base_url(); ?>assets/js/pages/datatables_basic.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
    <script type="text/javascript">
        $(function() {
            // Style checkboxes and radios
            $('.styled').uniform();

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
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_theme_primary">New Auditors <i class="icon-user role-right"></i></button>
                            </div>
                        </h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/employeedashboard"><i class="icon-home2 role-left"></i>Home</a></li>
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
                <?php if($this->session->flashdata('message')=='failed') { ?>
                    <div class="alert alert-warning alert-styled-left alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">Oppps!</span> You have Maximum Limit reached .
                    </div>
                <?php $this->session->unset_userdata('message'); } ?>
                <?php if($this->session->flashdata('message')=='success_del') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        Employee Successfully Deleted..
                    </div>
                <?php  $this->session->unset_userdata('message'); } ?>
                <?php if($this->session->flashdata('message')=='update_success') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        Employee Successfully Updated..
                    </div>
                <?php  $this->session->unset_userdata('message'); } ?>
                <?php if($this->session->flashdata('message')=='error') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">Oppps!</span> Something Went Wrong Please try again.
                    </div>
                <?php $this->session->unset_userdata('message');  } ?>
                <?php if($this->session->flashdata('message')=='live_err') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">Oppps!</span> Access denied. Already exist.
                    </div>
                <?php $this->session->unset_userdata('message');  } ?>
                <?php if($this->session->flashdata('phone_response')) { ?>
                    <div class="alert alert-danger alert-styled-right alert-arrow-right alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <?= $this->session->flashdata('phone_response')['message'] ?>
                    </div>
                <?php  $this->session->unset_userdata('message'); } ?>

                <div class="panel panel-body border-top-danger text-center">
                    <h6 class="no-margin text-semibold">Account Status</h6>
                    <div class="pace-demo" style="padding-bottom: 30px;">
                        <div class="theme_bar_sm"><div class="pace_progress" data-progress-text="60%" data-progress="60" style="width:<?=$reached?>%;"> <?=$total_account?>/<?=$limit?></div></div>
                    </div>
                </div>

                <!-- Basic datatable -->
                <div class="panel panel-flat">
                    <table class="table datatable-basic">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>User Type</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php  $count=1;
                        foreach ($employees as $employee) { ?>
                            <tr>
                                <td><?=$count?></td>
                                <td><?=$employee->employee_name?></td>
                                <td><?=$employee->employee_email?></td>
                                <td><?=$employee->role?></td>
                                <td><?=$employee->type_name?></td>
                                <td><?=$employee->username?></td>
                                <td><?=$employee->password?></td>
                                <td>
                                    <ul class="icons-list">
                                        <li class="text-primary-600" onclick="edit(<?=$employee->employee_id?>);"><a href="#"><i class="icon-pencil7"></i></a></li>
                                        <li class="text-danger-600"><a href="#" id="<?=$employee->employee_id?>" class="delete" ><i class="icon-trash"></i></a></li>
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

<!-- Primary modal -->
<div id="modal_theme_primary1" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-user role-right"></i> Edit Employee</h6>
            </div>
            <form action="<?php echo base_url();?>index.php/Employee/edit_auditors" method="post" name="edit_employee">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Full Name: </label>
                                <input type="text" placeholder="Your Full Name" class="form-control" name="edit_name" id="edit_name" required>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                <span id="edit_name_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Email: </label>
                                <input type="email" placeholder="Your Email" class="form-control" name="edit_email" id="edit_email" required>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                <span id="edit_email_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Phone: </label>
                                <input type="text" placeholder="+12345678910" class="form-control" name="edit_phone" id="edit_phone" required>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                <span id="edit_email_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Role: </label>
                                <input type="text" placeholder="You Role" class="form-control" name="edit_role" id="edit_role" required>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                <span id="edit_role_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Username: </label>
                                <input type="text" placeholder="Your username" class="form-control" name="edit_username" id="edit_username" required>
                                <input type="hidden" name="old_username" id="old_username">
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                <span id="edit_username_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Password: </label>
                                <input type="password" placeholder="Your password" class="form-control" name="edit_password" id="edit_password">
                                <div class="form-control-feedback">
                                    <i class="icon-lock text-muted"></i>
                                </div>
                                <span id="edit_password_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback" style="margin-left: 15px;" id="checked_div">
                                <label class="checkbox" style="padding-left: 30px;" id="audit_checker">
                                    <input type="checkbox" class="styled permisions" name="edit_auditor" id="edit_auditor" value="2">
                                    Auditor
                                </label>
                                <label class="checkbox" style="padding-left: 30px;" id="process_owner_checker">
                                    <input type="checkbox" class="styled permisions" name="edit_process_owner" id="edit_process_owner" value="3">
                                    Process Owner
                                </label>
                            </div>
                            <span id="edit_type_err" style="color:red;"></span>
                        </div>
                    </div>
                    <input type="hidden" name="employee_id" id="employee_id">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirm_edit_data();"><i class="icon-plus2 role-right"></i> Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /primary modal -->
<script type="text/javascript">
    $(document).ready(function() {
        setFocusEnd("search_name");
    });

    $('body').on('click','.delete' ,function(e){
        var id = $(this).attr('id');
        var dialog = bootbox.dialog({
            title: 'Confirmation',
            message: "<h4>Are You Sure Want to delete ?</h4>",
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
                        window.location.href="<?php echo base_url();?>index.php/Employee/delete_auditors/"+id;
                    }
                }
            }
        });
    });

    function edit(val){
        $('#modal_theme_primary1').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Employee/finduser",
            data:{ 'id' : val},
            success: function(data) {
                var datas = $.parseJSON(data)
                $("#edit_name").val(datas.employee_name);
                $("#edit_email").val(datas.employee_email);
                $("#edit_phone").val(datas.employee_phone);
                $("#edit_role").val(datas.role);
                $("#old_username").val(datas.username);
                $("#edit_username").val(datas.username);
                $("#employee_id").val(datas.employee_id);

                $("#checked_div > label > div > span").removeClass("checked");
                $(".permisions").prop("checked", false);

                var user_types = datas.type_ids.split(",");
                for(var i=0; i<user_types.length; i++) {if(user_types[i] == '2') {
                        $("#uniform-edit_auditor > span").addClass("checked");
                        $("#edit_auditor").prop("checked", true);
                    } else if(user_types[i] == '3') {
                        $("#uniform-edit_process_owner > span").addClass("checked");
                        $("#edit_process_owner").prop("checked", true);
                    }
                }
            }
        });
    }

    function confirm_add_data() {
        if($("#add_name").val().length == 0) {
            $('#name_err').html('* this field is required');
            return false;
        } else if($("#add_email").val().length == 0) {
            $('#name_err').html('');
            $('#email_err').html('* this field is required');
            return false;
        } else if($("#add_role").val().length == 0) {
            $('#name_err').html('');
            $('#email_err').html('');
            $('#role_err').html('* this field is required');
            return false;
        } else if($("#add_username").val().length == 0) {
            $('#name_err').html('');
            $('#email_err').html('');
            $('#role_err').html('');
            $('#username_err').html('* this field is required');
            return false;
        } else if($("#add_password").val().length == 0) {
            $('#name_err').html('');
            $('#email_err').html('');
            $('#role_err').html('');
            $('#username_err').html('');
            $('#password_err').html('* this field is required');
            return false;
        } else if(!$("#auditor").is(":checked") && !$("#process_owner").is(":checked")) {
            $('#name_err').html('');
            $('#email_err').html('');
            $('#role_err').html('');
            $('#username_err').html('');
            $('#password_err').html('');
            $('#type_err').html('* this field is required');
            return false;
        } else {
            document.add_auditors.submit();
        }
    }

    function confirm_edit_data() {
        if($("#edit_name").val().length == 0) {
            $('#edit_name_err').html('* this field is required');
            return false;
        } else if($("#edit_email").val().length == 0) {
            $('#edit_name_err').html('');
            $('#edit_email_err').html('* this field is required');
            return false;
        } else if($("#edit_role").val().length == 0) {
            $('#edit_name_err').html('');
            $('#edit_email_err').html('');
            $('#edit_role_err').html('* this field is required');
            return false;
        } else if($("#edit_username").val().length == 0) {
            $('#edit_name_err').html('');
            $('#edit_email_err').html('');
            $('#edit_role_err').html('');
            $('#edit_username_err').html('* this field is required');
            return false;
        } else if($("#edit_password").val().length == 0) {
            $('#edit_name_err').html('');
            $('#edit_email_err').html('');
            $('#edit_role_err').html('');
            $('#edit_username_err').html('');
            $('#edit_password_err').html('* this field is required');
            return false;
        } else if(!$("#edit_auditor").is(":checked") && !$("#edit_process_owner").is(":checked")) {
            $('#edit_name_err').html('');
            $('#edit_email_err').html('');
            $('#edit_role_err').html('');
            $('#edit_username_err').html('');
            $('#edit_password_err').html('');
            $('#edit_type_err').html('* this field is required');
            return false;
        } else {
            document.edit_employee.submit();
        }
    }
</script>

<script type="text/javascript">
    console.clear();
</script>

</body>

<!-- Primary modal -->
<div id="modal_theme_primary" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-user role-right"></i> New Employee</h6>
            </div>
            <form action="<?php echo base_url();?>index.php/Employee/add_auditors" method="post" name="add_auditors">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Full Name: </label>
                                <input type="text" placeholder="Your Full Name" class="form-control" name="add_name" id="add_name" required>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                <span id="name_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Email: </label>
                                <input type="email" placeholder="Your Email" class="form-control" name="add_email" id="add_email" required>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                <span id="email_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Phone: </label>
                                <input type="text" placeholder="+12345678910" class="form-control" name="add_phone" id="add_phone" required>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                <span id="email_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Role: </label>
                                <input type="text" placeholder="You Role" class="form-control" name="add_role" id="add_role" required>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                <span id="role_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Username: </label>
                                <input type="text" placeholder="Your username" class="form-control" name="add_username" id="add_username" required value="">
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                <span id="username_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Password: </label>
                                <input type="password" placeholder="Your password" class="form-control" name="add_password" id="add_password" required value="">
                                <div class="form-control-feedback">
                                    <i class="icon-lock text-muted"></i>
                                </div>
                                <span id="password_err" style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback" style="margin-left: 15px;">
                                <label class="checkbox" style="padding-left: 30px;">
                                    <input type="checkbox" class="styled permisions" name="auditor" id="auditor" value="2">
                                    Auditor
                                </label>
                                <label class="checkbox" style="padding-left: 30px;">
                                    <input type="checkbox" class="styled permisions" name="process_owner" id="process_owner" value="3">
                                    Process Owner
                                </label>
                            </div>
                            <span id="type_err" style="color:red;"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirm_add_data();"><i class="icon-plus2 role-right"></i> Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->
</html>
