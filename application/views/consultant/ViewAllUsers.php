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
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
       <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
     <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('.styled').uniform();
        });

        function filterUserType() {
            document.employee_form.submit();
        }

        function setFocusEnd(id){
            var node=document.getElementById(id);
            pos=node.value.length;
            node.focus();
            if(!node){
                return false;
            }else if(node.createTextRange){
                var textRange = node.createTextRange();
                textRange.collapse(true);
                textRange.moveEnd(pos);
                textRange.moveStart(pos);
                textRange.select();
                return true;
            }else if(node.setSelectionRange){
                node.setSelectionRange(pos,pos);
                return true;
            }
            return false;
        }
    </script>

     <script type="text/javascript">
        $(function() {
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: '150px',
                    targets: [4 ]
                }],
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Search by Name..',
                  
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
                order: [5,"desc"]
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
           /* $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });*/
        });
    </script>
    <style type="text/css">
        .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
    .toggle.ios .toggle-handle { border-radius: 20px; }
    .view_his{
        color: white !important;
    }
    </style>
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
                                $consultant_id= $this->session->userdata('consultant_id');
                                $logo1=$this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row();

                                $dlogo=$this->db->query("select * from `default_setting` where `id`='1'")->row()->logo;

                                if ($logo1->logo=='1') {
                                    $logo=$dlogo;
                                } else {
                                    $logo=$logo1->logo;
                                }
                            }
                            ?>
                            <img src="<?php echo base_url(); ?>uploads/logo/<?=$logo?>" style="height:50px;">
                            <span class="text-semibold"><?=$title?></span>
                            
                        </h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/consultantdashboard"><i class="icon-home2 role-left"></i>Home</a></li>
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
                <?php  $this->session->unset_userdata('message'); } ?>
                <?php if($this->session->flashdata('message')=='success_del') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        Employee Successfully Deleted..
                    </div>
                <?php  $this->session->unset_userdata('message'); } ?>
                <?php if($this->session->flashdata('message')=='update_success') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                       Employee Login Status Successfully Updated..
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
                <?php $this->session->unset_userdata('phone_response');  } ?>

                <?php //echo "<pre>"; print_r($getAllUser_1); echo "</pre>"; ?>
                <!-- Basic datatable -->
                <div class="panel panel-flat" style="overflow: auto">
                    <table class="table datatable-basic">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>                         
                            <th>Status</th>                         
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                         $count=1;
                         foreach ($getAllUser_1 as $val) {
                            $user__id = $val->user_id;
                          ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $val->employee_name; ?></td>
                                <td><?php echo $val->username; ?></td>
                                <td><?php echo $val->employee_email; ?></td>
                                <td><?php echo $val->role; ?></td>
                                <td>
                                   <input type="checkbox" data-toggle="toggle" <?= ($val->status == 0) ? '' : 'checked' ?> data-style="ios" data-id="<?=$val->user_id?>" class="active-deactive" data-on="Active" data-off="Deactive">

                                  <!--  <input id="toggle-one" type="checkbox" data-toggle="toggle" data-on="Active" data-off="Deactive"> --><?php //echo $status = $val->status; ?>

                                </td>
                                <td>
                                    <ul class="icons-list">
                                        <li class="text-primary-600" onclick="viewloginhistory(<?//=$employee->employee_id?>);"><a type="button" href="<?php echo base_url();?>index.php/Consultant/ViewUsersHistory?user_id=<?php echo $user__id; ?>" class="btn btn-primary view_his" href="">View</a></li>
                                    </ul>
                                </td>

                            </tr>
                            <?php $count++; } ?>
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
<div id="modal_theme_primary1" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-user role-right"></i> Edit Employee</h6>
            </div>
            <form action="<?php echo base_url();?>index.php/Consultant/edit_employee" method="post" name="edit_employee">
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
                                    <i class="icon-mobile text-muted"></i>
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
                                <label class="checkbox" style="padding-left: 30px;" id="lead_audit_checker">
                                    <input type="checkbox" class="styled permisions" name="edit_lead_auditor" id="edit_lead_auditor" value="1">
                                    Lead Auditor
                                </label>
                                <label class="checkbox" style="padding-left: 30px;" id="audit_checker">
                                    <input type="checkbox" class="styled permisions" name="edit_auditor" id="edit_auditor" value="2">
                                    Auditor
                                </label>
                                <label class="checkbox" style="padding-left: 30px;" id="process_owner_checker">
                                    <input type="checkbox" class="styled permisions" name="edit_process_owner" id="edit_process_owner" value="3">
                                    Process Owner
                                </label>
                                <label class="checkbox" style="padding-left: 30px;" id="auditee_checker">
                                    <input type="checkbox" class="styled permisions" name="edit_auditee" id="edit_auditee" value="4">
                                    Auditee
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

</script>

<script type="text/javascript">
    //console.clear();
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
            <form action="<?php echo base_url();?>index.php/Consultant/add_employee" method="post" name="add_employee">
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
                                    <i class="icon-mobile text-muted"></i>
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
                                    <input type="checkbox" class="styled permisions" name="lead_auditor" id="lead_auditor" value="1">
                                    Lead Auditor
                                </label>
                                <label class="checkbox" style="padding-left: 30px;">
                                    <input type="checkbox" class="styled permisions" name="auditor" id="auditor" value="2">
                                    Auditor
                                </label>
                                <label class="checkbox" style="padding-left: 30px;">
                                    <input type="checkbox" class="styled permisions" name="process_owner" id="process_owner" value="3">
                                    Process Owner
                                </label>
                                <label class="checkbox" style="padding-left: 30px;">
                                    <input type="checkbox" class="styled permisions" name="auditee" id="auditee" value="4">
                                    Auditee
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
<script type="text/javascript">
 /*$(function() {
    $('#toggle-one').bootstrapToggle();
  })*/
$(function() {
    $('#toggle-one').bootstrapToggle({
      on: 'Active',
      off: 'Deactive'
    });
  })

    // On Change Toogle Switch Active/Deactive button
        $('body').on('change','.active-deactive' ,function(e){
            var id = $(this).data('id');
            console.log(id);
            if ($(this).is(":checked"))
            {
              console.log('It is active now');
              var checkedValue = 1;
              var checkedMsg = 'Activate';

            }
            else {
              console.log('It is deactive now');
              var checkedValue = 0;
              var checkedMsg = 'Deactivate';
            }
            var dialog = bootbox.dialog({
                title: 'Confirmation',
                message: "<h4>Are You Sure want to "+checkedMsg+" this user ?</h4>",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "Cancel",
                        className: 'btn-danger',
                        callback: function(){
                            dialog.modal('hide');
                            if(checkedValue == 1) {
                                console.log('comes here');
                                $(this).prop('checked', false).change();
                            } else {
                                console.log('comes here elseeeee');
                                $(this).prop('checked', true).change();
                            }
                        }
                    },

                    ok: {
                        label: "OK",
                        className: 'btn-success',
                        callback: function(){
                            window.location.href="<?php echo base_url();?>index.php/Consultant/update_status/"+id+'?is_active='+checkedValue;
                        }
                    }
                }
            });
        });
 
</script>
</html>
