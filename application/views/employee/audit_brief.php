<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
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

    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/ui/moment/moment.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/daterangepicker.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/anytime.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/legacy.js"></script>

    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>



    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/pages/picker_date.js"></script>
         <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

    <style type="text/css">
        textarea {
            min-height: 80px;
            max-height: 80px;
            min-width: 100%;
            max-width: 100%;
        }
    </style>
       <script type="text/javascript">
            $(function() {
                $('.daterange-single').daterangepicker({ 
                singleDatePicker: true,
                minDate:new Date(),
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
            $('.daterange-single1').daterangepicker({ 
                singleDatePicker: true,
                minDate:new Date(),
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
            var schedule = "<?= ($is_brief=='1') ? $audit_brief_array->date_schedule : '' ?>";
            if(schedule != ""){
                var temptime = schedule.split(" - ");
                $('.daterange-single').val(temptime[0]);
                $('.daterange-single1').val(temptime[1]);
            }else{
                $('.daterange-single1').val("");
                $('.daterange-single').val("");

            }
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
            $('.datatable-basic-1').DataTable({
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
           /* $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });*/
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
                            <img src="<?php echo base_url(); ?>uploads/logo/<?= $logo ?>" style="height:50px;">
                            <span class="text-semibold"><?= $title ?></span>

                        </h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/employeedashboard"><i
                                    class="icon-home2 role-left"></i>Home</a></li>
                        <li>Process Audit</li>
                        <li>Audit</li>
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
                        <form class="form-horizontal" method="post" action="<?php echo  base_url(); ?>index.php/Employee/audit_plan/<?=$audit_id?>"
                              enctype="multipart/form-data" name="audit_brief_form" id="audit_brief_form">
                            <fieldset>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-6" style="margin-top: 10px;">
                                                <div class="col-md-4">
                                                    <span>Audit Reference Number :</span>
                                                </div>
                                                <div class="col-md-2">
                                                    <span><?=$is_brief ? $audit_brief_array->refer_num : time()?></span>
                                                </div>
                                                <input type="hidden" name="refer_num" value="<?=$is_brief ? $audit_brief_array->refer_num : time()?>">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="col-md-2">
                                                    <span class="help-block">Audit of:</span>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" readonly placeholder="" class="form-control" name="audit_of" id="audit_of" required value="<?= $is_brief ? $audit_brief_array->audit_of : '' ?>">
                                                    <span id="audit_of_err" style="color:red;"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-6">
                                                <div class="col-md-2">
                                                    <span class="help-block">Date Scheduled:</span>
                                                </div>
                                               
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                                        <input type="text" class="form-control daterange-single" name="start_date">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                                        <input type="text" class="form-control daterange-single1" name="end_date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="col-md-2">
                                                    <span class="help-block">Locations:</span>
                                                </div>
                                                <div class="col-md-10">
                                                    <input readonly type="text" placeholder="" class="form-control" name="locations" id="locations" required value="<?= $is_brief ? $audit_brief_array->locations : '' ?>">
                                                    <span id="locations_err" style="color:red;"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-12">
                                                <div class="col-md-10">
                                                    <span class="help-block">Summary:</span>
                                                    <textarea readonly placeholder="" class="form-control" name="summary" id="summary" required><?= ($is_brief=='1') ? $audit_brief_array->summary : '' ?></textarea>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-12">
                                                <div class="col-md-10">
                                                    <span class="help-block">Purpose:</span>
                                                    <textarea readonly placeholder="" class="form-control" name="purpose" id="purpose" required><?= $is_brief ? $audit_brief_array->purpose : '' ?></textarea>
                                                    <span id="purpose_err" style="color:red;"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-12">
                                                <div class="col-md-10">
                                                    <span class="help-block">Background and Context:</span>
                                                    <textarea readonly placeholder="" class="form-control" name="context" id="context" required><?= $is_brief ? $audit_brief_array->context : '' ?></textarea>
                                                    <span id="context_err" style="color:red;"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-12">
                                                <div class="col-md-10">
                                                    <span class="help-block">Scope:</span>
                                                    <textarea readonly placeholder="" class="form-control" name="scope" id="scope" style="min-height: 100px;" required><?= $is_brief ? $audit_brief_array->scope : '' ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-12">
                                                <div class="col-md-10">
                                                    <span class="help-block">Criteria:</span>
                                                    <textarea readonly placeholder="" class="form-control" name="criteria" id="criteria" required><?= $is_brief ? $audit_brief_array->criteria : '' ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-12">
                                                <div class="col-md-10">
                                                    <span class="help-block">Objectives:</span>
                                                    <textarea readonly placeholder="" class="form-control" name="objectives" id="objectives" required><?= $is_brief ? $audit_brief_array->objectives : '' ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px;">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Select Audit Team</h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                            <!-- <input type="text" placeholder="Search By FullName" class="form-control pull-left" name="search_auditor" id="search_auditor" required> -->
                                                        </div>
                                                        <div class="col-md-8">
                                                            <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" onclick="add_auditor_modal()">New Auditor <i class="icon-user role-right"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <div class="col-md-10">
                                                            <div class="panel panel-flat" style="overflow: auto">
                                                                <table class="table datatable-basic-0" id="auditor__view" style="font-size: 13px;">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Select</th>
                                                                        <th>Full Name</th>
                                                                        <th>Email</th>
                                                                        <th>Role</th>
                                                                        <th>Username</th>
                                                                        <th width="50">Password</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="auditor_tbody">

                                                                    </tbody>
                                                                </table>
                                                                <input type="hidden" id="check_audit_list" name="check_audit_list">
                                                            </div>
                                                            <span id="audit_check_err" style="color:red;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Select Process Owners</h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                           <!--  <input type="text" placeholder="Search By FullName" class="form-control pull-left" name="search_owner" id="search_owner" required> -->
                                                        </div>
                                                        <div class="col-md-8">
                                                            <button type="button" class="btn btn-primary btn-sm pull-right" onclick="add_owner_modal()">New Process Owner <i class="icon-user role-right"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <div class="col-md-10">
                                                            <div class="panel panel-flat" style="overflow: auto">
                                                                <table class="table datatable-basic" id="owner__view" style="font-size: 13px;">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Select</th>
                                                                        <th>Full Name</th>
                                                                        <th>Email</th>
                                                                        <th>Role</th>
                                                                        <th>Username</th>
                                                                        <th>Password</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="owner_tbody">

                                                                    </tbody>
                                                                </table>
                                                                <input type="hidden" id="check_owner_list" name="check_owner_list">
                                                            </div>
                                                            <span id="owner_check_err" style="color:red;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px;">
                                            <div class="col-md-12">
                                                <div class="col-md-10">
                                                    <div class="text-right">
                                                        <a class="btn btn-primary" id="save" class="btn btn-primary" onclick="confirmData();">Next <i class="icon-arrow-right16 position-left"></i></a>
<!--                                                        <input type="button" id="save" class="btn btn-primary" onclick="confirmData();" value="Next" name="next">-->
                                                    </div>
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
    $(document).ready(function () {
        get_auditors(1);
        get_owners(1);
        $("#search_auditor").keypress( function(event) {
            if (event.keyCode == "13") {
                var val = $("#search_auditor").val();
                get_auditors(val);
            }
        });
        $("#search_owner").keypress( function(event) {
            if (event.keyCode == "13") {
                var val = $("#search_owner").val();
                get_owners(val);
            }
        });
        $("#audit_of").keypress( function(event) {
            if (event.keyCode == "13") {
                confirmData();
            }
        });
        $("#locations").keypress( function(event) {
            if (event.keyCode == "13") {
                confirmData();
            }
        });
    });
  /*  $(document).ready(function() {
        $('#auditor__view').DataTable();
    } );*/
    function get_auditors(val) {
        if(!val)    val = 1;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/all_auditors/<?=$audit_id?>",
            data:{'name' : val},
            success: function(data) {
                $('#auditor_tbody').html(data);

               $('#auditor__view').DataTable({ 
                      "destroy": true, //use for reinitialize datatable
                   });
                $(function() {
                    // Style checkboxes and radios
                    $('.styled').uniform();
                });
            }
        });
    }

    function get_owners(val) {
        if(!val)    val = 1;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/all_owners/<?=$audit_id?>",
            data:{'name' : val},
            success: function(data) {
                $('#owner_tbody').html(data);
                 $('#owner__view').DataTable({ 
                      "destroy": true, //use for reinitialize datatable
                   });
                $(function() {
                    // Style checkboxes and radios
                    $('.styled').uniform();
                });
            }
        });
    }

    function confirmData() {
        if($("#audit_of").val().length == 0) {
            $('#audit_of_err').html('* this field is required');
            return false;
        } else if($("#locations").val().length == 0) {
            $('#audit_of_err').html('');
            $('#locations_err').html('* this field is required');
            return false;
        } else if($("#purpose").val().length == 0) {
            $('#audit_of_err').html('');
            $('#locations_err').html('');
            $('#purpose_err').html('* this field is required');
            return false;
        } else if($("#context").val().length == 0) {
            $('#audit_of_err').html('');
            $('#locations_err').html('');
            $('#purpose_err').html('');
            $('#context_err').html('* this field is required');
            return false;
        } else {
            var audit_checked = "0";
            var audit_state = "false";
            var audit_list = $("#auditor_tbody > tr > td > div > span > input:checkbox");
            for(var i = 0; i < audit_list.length; i++) {
                audit_state = audit_state | $(audit_list[i]).prop("checked");
            }
            for(var i = 0; i < audit_list.length; i++) {
                if(audit_state) {
                    if($(audit_list[i]).prop("checked")) {
                        audit_checked += "," + $(audit_list[i]).val();
                    }
                } else {
                    $('#audit_of_err').html('');
                    $('#locations_err').html('');
                    $('#purpose_err').html('');
                    $('#context_err').html('');
                    $('#audit_check_err').html('* this field is required');
                    return false;
                }
            }
            $("#check_audit_list").val(audit_checked);

            var owner_checked = "0";
            var owner_state = "false";
            var owner_list = $("#owner_tbody > tr > td > div > span > input:checkbox");
            for(var i = 0; i < owner_list.length; i++) {
                owner_state = owner_state | $(owner_list[i]).prop("checked");
            }
            for(var i = 0; i < owner_list.length; i++) {
                if(owner_state) {
                    if($(owner_list[i]).prop("checked")) {
                        owner_checked += "," + $(owner_list[i]).val();
                    }
                } else {
                    $('#audit_of_err').html('');
                    $('#locations_err').html('');
                    $('#purpose_err').html('');
                    $('#context_err').html('');
                    $('#audit_check_err').html('');
                    $('#owner_check_err').html('* this field is required');
                    return false;
                }
            }
            $("#check_owner_list").val(owner_checked);

            document.audit_brief_form.submit();
        }
    }

    function confirm_data(val) {
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
        } else if(!$("#auditor").is(":checked")) {
            $('#name_err').html('');
            $('#email_err').html('');
            $('#role_err').html('');
            $('#username_err').html('');
            $('#password_err').html('');
            $('#type_err').html('* this field is required');
            return false;
        } else {
            if(val == 'add') {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/Consultant/add_auditor",
                    data:{
                        'name' : $("#add_name").val(),
                        'email' : $("#add_email").val(),
                        'role' : $("#add_role").val(),
                        'username' : $("#add_username").val(),
                        'password' : $("#add_password").val(),
                        'auditor' : $("#auditor").val()
                    },
                    success: function(data) {
                        $datas = $.parseJSON(data);
                        if($datas == 'success') {
                            $('#modal_new_auditor').modal('hide');
                            get_auditors(1);
                        } else if($datas == 'failed') {
                            var dialog = bootbox.dialog({
                                title: 'Warning',
                                message: "You have Maximum Limit reached.",
                                size: 'small',
                                buttons: {
                                    cancel: {
                                        label: "OK",
                                        className: 'btn-danger',
                                        callback: function() {
                                            dialog.modal('hide');
                                            $('#modal_new_auditor').modal('hide');
                                        }
                                    }
                                }
                            });
                        } else if($datas == 'live_err') {
                            var dialog = bootbox.dialog({
                                title: 'Warning',
                                message: "Access denied. Already exist",
                                size: 'small',
                                buttons: {
                                    cancel: {
                                        label: "OK",
                                        className: 'btn-danger',
                                        callback: function() {
                                            dialog.modal('hide');
                                            $('#modal_new_auditor').modal('hide');
                                        }
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/Consultant/edit_employee_any",
                    data:{
                        'id' : $("#employee_id").val(),
                        'name' : $("#add_name").val(),
                        'email' : $("#add_email").val(),
                        'role' : $("#add_role").val(),
                        'username' : $("#add_username").val(),
                        'password' : $("#add_password").val(),
                        'auditor' : $("#auditor").val()
                    },
                    success: function(data) {
                        var datas = $.parseJSON(data);
                        if(datas == "success") {
                            $('#modal_new_auditor').modal('hide');
                            get_auditors(1);
                        } else {
                            var dialog = bootbox.dialog({
                                title: 'Warning',
                                message: "Access denied. Already exist",
                                size: 'small',
                                buttons: {
                                    cancel: {
                                        label: "OK",
                                        className: 'btn-danger',
                                        callback: function() {
                                            dialog.modal('hide');
                                        }
                                    }
                                }
                            });
                        }
                    }
                });
            }
        }
    }

    function confirm_owner_data(val) {
        if($("#owner_name").val().length == 0) {
            $('#owner_name_err').html('* this field is required');
            return false;
        } else if($("#owner_email").val().length == 0) {
            $('#owner_name_err').html('');
            $('#owner_email_err').html('* this field is required');
            return false;
        } else if($("#owner_role").val().length == 0) {
            $('#owner_name_err').html('');
            $('#owner_email_err').html('');
            $('#owner_role_err').html('* this field is required');
            return false;
        } else if($("#owner_username").val().length == 0) {
            $('#owner_name_err').html('');
            $('#owner_email_err').html('');
            $('#owner_role_err').html('');
            $('#owner_username_err').html('* this field is required');
            return false;
        } else if($("#owner_password").val().length == 0) {
            $('#owner_name_err').html('');
            $('#owner_email_err').html('');
            $('#owner_role_err').html('');
            $('#owner_username_err').html('');
            $('#owner_password_err').html('* this field is required');
            return false;
        } else if(!$("#process_owner").is(":checked")) {
            $('#owner_name_err').html('');
            $('#owner_email_err').html('');
            $('#owner_role_err').html('');
            $('#owner_username_err').html('');
            $('#owner_password_err').html('');
            $('#owner_type_err').html('* this field is required');
            return false;
        } else {
            if(val == 'add') {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/Consultant/add_owner",
                    data:{
                        'name' : $("#owner_name").val(),
                        'email' : $("#owner_email").val(),
                        'role' : $("#owner_role").val(),
                        'username' : $("#owner_username").val(),
                        'password' : $("#owner_password").val(),
                        'process_owner' : $("#process_owner").val()
                    },
                    success: function(data) {
                        $datas = $.parseJSON(data);
                        if($datas == 'success') {
                            $('#modal_new_owner').modal('hide');
                            get_owners(1);
                        } else if($datas == 'failed') {
                            var dialog = bootbox.dialog({
                                title: 'Warning',
                                message: "You have Maximum Limit reached.",
                                size: 'small',
                                buttons: {
                                    cancel: {
                                        label: "OK",
                                        className: 'btn-danger',
                                        callback: function() {
                                            dialog.modal('hide');
                                            $('#modal_new_owner').modal('hide');
                                        }
                                    }
                                }
                            });
                        } else if($datas == 'live_err') {
                            var dialog = bootbox.dialog({
                                title: 'Warning',
                                message: "Access denied. Already exist",
                                size: 'small',
                                buttons: {
                                    cancel: {
                                        label: "OK",
                                        className: 'btn-danger',
                                        callback: function() {
                                            dialog.modal('hide');
                                        }
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/Consultant/edit_employee_any",
                    data:{
                        'id' : $("#owner_id").val(),
                        'name' : $("#owner_name").val(),
                        'email' : $("#owner_email").val(),
                        'role' : $("#owner_role").val(),
                        'username' : $("#owner_username").val(),
                        'password' : $("#owner_password").val()
                    },
                    success: function(data) {
                        var datas = $.parseJSON(data);
                        if(datas == "success") {
                            $('#modal_new_owner').modal('hide');
                            get_owners(1);
                        } else {
                            var dialog = bootbox.dialog({
                                title: 'Warning',
                                message: "Access denied. Already exist",
                                size: 'small',
                                buttons: {
                                    cancel: {
                                        label: "OK",
                                        className: 'btn-danger',
                                        callback: function() {
                                            dialog.modal('hide');
                                        }
                                    }
                                }
                            });
                        }
                    }
                });
            }
        }
    }

    $('body').on('click','.delete_auditor' ,function(e){
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
                    callback: function() {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>index.php/Consultant/confirm_assign",
                            data:{ 'id' : id},
                            success: function(data) {
                                var datas = $.parseJSON(data);
                                if(datas == '1') {
                                    $.ajax({
                                        type: "POST",
                                        url: "<?php echo base_url(); ?>index.php/Employee/delete_employee_any/"+id,
                                        data:{},
                                        success: function(data) {
                                            get_auditors(1);
                                        }
                                    });
                                } else {
                                    var dialog = bootbox.dialog({
                                        title: 'Warning',
                                        message: "This employee has been assigned to audit.",
                                        size: 'small',
                                        buttons: {
                                            cancel: {
                                                label: "OK",
                                                className: 'btn-danger',
                                                callback: function() {
                                                    dialog.modal('hide');
                                                }
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            }
        });
    });

    $('body').on('click','.delete_owner' ,function(e){
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
                    callback: function() {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>index.php/Consultant/confirm_assign",
                            data:{ 'id' : id},
                            success: function(data) {
                                var datas = $.parseJSON(data);
                                if(datas == '1') {
                                    $.ajax({
                                        type: "POST",
                                        url: "<?php echo base_url(); ?>index.php/Employee/delete_employee_any/"+id,
                                        data:{},
                                        success: function(data) {
                                            get_owners(1);
                                        }
                                    });
                                } else {
                                    var dialog = bootbox.dialog({
                                        title: 'Warning',
                                        message: "This employee has been assigned to audit.",
                                        size: 'small',
                                        buttons: {
                                            cancel: {
                                                label: "OK",
                                                className: 'btn-danger',
                                                callback: function() {
                                                    dialog.modal('hide');
                                                }
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            }
        });
    });

    function add_auditor_modal() {
        $('#modal_new_auditor').modal('show');
        $('#add_auditor_title').show();
        $('#add_auditor_btn').show();
        $('#edit_auditor_title').hide();
        $('#edit_auditor_btn').hide();
        $("#add_name").val("")
        $("#add_email").val("")
        $("#add_role").val("")
        $("#add_username").val("")
        $("#add_password").val("")
        $('#name_err').html('');
        $('#email_err').html('');
        $('#role_err').html('');
        $('#type_err').html('');
        $('#username_err').html('');
        $('#password_err').html('');
    }

    function add_owner_modal() {
        $('#modal_new_owner').modal('show');
        $('#add_owner_title').show();
        $('#add_owner_btn').show();
        $('#edit_owner_title').hide();
        $('#edit_owner_btn').hide();
        $("#owner_name").val("")
        $("#owner_email").val("")
        $("#owner_role").val("")
        $("#owner_username").val("")
        $("#owner_password").val("")
        $('#owner_name_err').html('');
        $('#owner_email_err').html('');
        $('#owner_role_err').html('');
        $('#owner_type_err').html('');
        $('#owner_username_err').html('');
        $('#owner_password_err').html('');
    }

    function edit_auditor(val){
        $('#modal_new_auditor').modal('show');
        $('#name_err').html('');
        $('#email_err').html('');
        $('#role_err').html('');
        $('#type_err').html('');
        $('#username_err').html('');
        $('#password_err').html('');
        $('#edit_auditor_title').show();
        $('#edit_auditor_btn').show();
        $('#add_auditor_title').hide();
        $('#add_auditor_btn').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Employee/finduser",
            data:{ 'id' : val},
            success: function(data) {
                var datas = $.parseJSON(data)
                $("#add_name").val(datas.employee_name);
                $("#add_email").val(datas.employee_email);
                $("#add_role").val(datas.role);
                $("#add_username").val(datas.username);
                $("#add_password").val(datas.password);
                $("#employee_id").val(datas.employee_id);
            }
        });
    }

    function edit_owner(val){
        $('#modal_new_owner').modal('show');
        $('#name_err').html('');
        $('#email_err').html('');
        $('#role_err').html('');
        $('#type_err').html('');
        $('#username_err').html('');
        $('#password_err').html('');
        $('#add_owner_title').hide();
        $('#add_owner_btn').hide();
        $('#edit_owner_title').show();
        $('#edit_owner_btn').show();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Employee/finduser",
            data:{ 'id' : val},
            success: function(data) {
                var datas = $.parseJSON(data)
                $("#owner_name").val(datas.employee_name);
                $("#owner_email").val(datas.employee_email);
                $("#owner_role").val(datas.role);
                $("#owner_username").val(datas.username);
                $("#owner_password").val(datas.password);
                $("#owner_id").val(datas.employee_id);
            }
        });
    }
    console.clear();
</script>
<!-- Primary modal -->
<div id="modal_new_auditor" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title" id="add_auditor_title"><i class="icon-user role-right"></i> New Auditor</h6>
                <h6 class="modal-title" id="edit_auditor_title"><i class="icon-user role-right"></i> Edit Auditor</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <label>Full Name: </label>
                            <input type="text" placeholder="Your Full Name" class="form-control" name="add_name" id="add_name" required value="">
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
                            <input type="text" placeholder="Your username" class="form-control" name="add_username" id="add_username" required>
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
                            <input type="password" placeholder="Your password" class="form-control" name="add_password" id="add_password" required>
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
                                <input type="checkbox" class="styled permitions" name="auditor" id="auditor" value="2" checked>
                                Auditor
                            </label>
                        </div>
                        <span id="type_err" style="color:red;"></span>
                    </div>
                </div>
                <input type="hidden" name="employee_id" id="employee_id">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="confirm_data('add');" id="add_auditor_btn"><i class="icon-plus2 role-right"></i> Add</button>
                <button type="button" class="btn btn-primary" onclick="confirm_data('edit');" id="edit_auditor_btn"><i class="icon-plus2 role-right"></i> Edit</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_new_owner" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title" id="add_owner_title"><i class="icon-user role-right"></i> New Process Owner</h6>
                <h6 class="modal-title" id="edit_owner_title"><i class="icon-user role-right"></i> Edit Process Owner</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <label>Full Name: </label>
                            <input type="text" placeholder="Your Full Name" class="form-control" name="owner_name" id="owner_name" required>
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                            <span id="owner_name_err" style="color:red;"></span>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <label>Email: </label>
                            <input type="email" placeholder="Your Email" class="form-control" name="owner_email" id="owner_email" required>
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                            <span id="owner_email_err" style="color:red;"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <label>Role: </label>
                            <input type="text" placeholder="You Role" class="form-control" name="owner_role" id="owner_role" required>
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                            <span id="owner_role_err" style="color:red;"></span>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <label>Username: </label>
                            <input type="text" placeholder="Your username" class="form-control" name="owner_username" id="owner_username" required>
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                            <span id="owner_username_err" style="color:red;"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <label>Password: </label>
                            <input type="password" placeholder="Your password" class="form-control" name="owner_password" id="owner_password" required>
                            <div class="form-control-feedback">
                                <i class="icon-lock text-muted"></i>
                            </div>
                            <span id="owner_password_err" style="color:red;"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group has-feedback" style="margin-left: 15px;">
                            <label class="checkbox" style="padding-left: 30px;">
                                <input type="checkbox" class="styled permitions" name="process_owner" id="process_owner" value="3" checked>
                                Process Owner
                            </label>
                        </div>
                        <span id="owner_type_err" style="color:red;"></span>
                    </div>
                </div>
                <input type="hidden" name="owner_id" id="owner_id">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="confirm_owner_data('add');" id="add_owner_btn"><i class="icon-plus2 role-right"></i> Add</button>
                <button type="button" class="btn btn-primary" onclick="confirm_owner_data('edit');" id="edit_owner_btn"><i class="icon-plus2 role-right"></i> Edit</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
