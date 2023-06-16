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

    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/pages/picker_date.js"></script>
    <script type="text/javascript">
        $(function() {
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: '150px',
                    targets: [1]
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
                order: []
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

        $(function() {
            // Style checkboxes and radios
            $('.styled').uniform();
        });
    </script>

    <style type="text/css">
        textarea {
            min-height: 80px;
            max-height: 80px;
            min-width: 100%;
            max-width: 100%;
        }
        .dataTables_length, .datatable-footer {
            display:none;
        }
        .datatable-scroll {
            height: 300px;
            overflow-y: auto;
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
                <form class="form-horizontal" method="post" action="<?php echo  base_url(); ?>index.php/Consultant/select_process/<?=$pa_id?>" enctype="multipart/form-data" name="audit_plan_form">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Opening Meeting</h5>
                        </div>

                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-1">
                                            <span class="help-block">Who :</span>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="open_who" id="open_who" readonly required value="<?= $is_plan ? $audit_plan_array['open_who'] : '' ?>" />
                                            <span id="open_who_err" style="color:red;"></span>
                                            <input type="hidden" name="open_who_list" id="open_who_list" value="<?= $is_plan ? $audit_plan_array['open_employees'] : '' ?>" />
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="modal_employee_sel('open');">Select</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <div class="col-md-1">
                                            <span class="help-block">When :</span>
                                        </div>
                                        <div class="col-md-4">
                                            <input readonly="" class="form-control daterange-single" type="text" required name="open_when" value="<?= $is_plan ? $audit_plan_array['open_when'] : '' ?>"/>
                                            <span id="open_when_err" style="color:red;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <div class="col-md-1">
                                            <span class="help-block">Where :</span>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="" class="form-control" name="open_where" id="open_where" value="<?= $is_plan ? $audit_plan_array['open_where'] : '' ?>" required />
                                            <span id="open_where_err" style="color:red;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <span class="help-block">What to cover :</span>
                                        <div class="col-md-10">
                                            <textarea placeholder="" class="form-control" name="open_cover" id="open_cover" required><?= $is_plan ? $audit_plan_array['open_cover'] : '' ?></textarea>
                                            <span id="open_cover_err" style="color:red;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <div class="col-lg-12">
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <div class="col-md-8">
                                            <h5 class="panel-title">Audit Plan</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-10">
                                            <?php if($is_schedule): ?>
                                                <a type="button" class="btn btn-primary btn-sm pull-right" href="<?php echo base_url(); ?>index.php/Consultant/audit_schedule/<?=$pa_id?>">View Schedule</a>
                                            <?php else: ?>
                                                <a type="button" class="btn btn-primary btn-sm pull-right" disabled>View Schedule</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <div class="col-md-10">
                                            <textarea placeholder="" class="form-control" name="schedule" id="schedule" required><?= $is_plan ? $audit_plan_array['schedule'] : '' ?></textarea>
                                            <span id="schedule_err" style="color:red;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Closing Meeting</h5>
                        </div>

                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-1">
                                            <span class="help-block">Who :</span>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="close_who" id="close_who" readonly required value="<?= $is_plan ? $audit_plan_array['close_who'] : '' ?>" />
                                            <span id="close_who_err" style="color:red;"></span>
                                            <input type="hidden" name="close_who_list" id="close_who_list" value="<?= $is_plan ? (($audit_plan_array['close_employees'] != '') ? $audit_plan_array['close_employees'] : '') : '' ?>" />
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="selectNA();">N/A</button>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="modal_employee_sel('close');">Select</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <div class="col-md-1">
                                            <span class="help-block">When :</span>
                                        </div>
                                        <div class="col-md-4">
                                            <input readonly="" class="form-control daterange-single" type="text" required name="close_when" id="close_when" />
                                            <span id="close_when_err" style="color:red;"></span>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="selectTBD();">TBD</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <div class="col-md-1">
                                            <span class="help-block">Where :</span>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="" class="form-control" name="close_where" id="close_where" value="<?= $is_plan ? $audit_plan_array['close_where'] : '' ?>" required />
                                            <span id="close_where_err" style="color:red;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="text-right">
                                    <a type="button" class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>index.php/Consultant/audit_brief/<?=$pa_id?>" style="margin-right: 40px;"><i class="icon-arrow-left16"></i> Back</a>
                                    <a type="button" class="btn btn-primary btn-sm" onclick="confirmData();">Next <i class="icon-arrow-right16 position-left"></i></a>
<!--                                    <input type="button" id="next" class="btn btn-primary" onclick="confirmData();" value="Next" name="next">-->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


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
<div id="modal_open_employee_sel" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-user role-right"></i> Select Employee</h6>
            </div>
            <div class="modal-body">
                <!-- Basic datatable -->
                <div class="panel panel-flat">
                    <table class="table datatable-basic">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Select</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                        </thead>
                        <tbody id="open_employee_tbody">

                        </tbody>
                    </table>
                </div>
                <span id="open_employee_check_err" style="color:red;"></span>
                <!-- /basic datatable -->
            </div>
            <input type="hidden" name="open_check_list" id="open_check_list">
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="check_submit('open');" id="open_check"><i class="icon-plus2 role-right"></i> OK</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_close_employee_sel" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-user role-right"></i> Select Employee</h6>
            </div>
            <div class="modal-body">
                <!-- Basic datatable -->
                <div class="panel panel-flat">
                    <table class="table datatable-basic">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Select</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                        </thead>
                        <tbody id="close_employee_tbody">

                        </tbody>
                    </table>
                </div>
                <span id="close_employee_check_err" style="color:red;"></span>
                <!-- /basic datatable -->
            </div>
            <input type="hidden" name="open_check_list" id="open_check_list">
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="check_submit('close');" id="close_check"><i class="icon-plus2 role-right"></i> OK</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

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
    var modal_state = "";
    var is_checked_open = 0;
    var is_checked_close = 0;
    $(document).ready(function () {
        var is_plan = "<?=$is_plan?>";
        if(is_plan) {
            $("#close_when").val("<?=$audit_plan_array['close_when'] ?>");
        }

        get_open_employees();
        get_close_employees();

        $(".datatable-header").append("<button type=\"button\" class=\"btn btn-primary btn-sm pull-right\" onclick=\"all_open_checker();\">Select ALL</button>");
        $(".datatable-header").append("<button type=\"button\" style='margin-right: 10px;' class=\"btn btn-primary btn-sm pull-right\" onclick=\"add_meeting_employee();\">Add Employee</button>");
    });

    function get_open_employees(){
        var is_plan = "<?=$is_plan?>";
        var val = "<?php
                    if(isset($audit_plan_array['open_employees']))
                        echo $audit_plan_array['open_employees'];
                    else
                        echo "";?>";
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/all_open_employees",
            data:{'is_plan' : val, 'open_employees' : val},
            success: function(data) {
                $('#open_employee_tbody').html(data);
                $(function() {
                    // Style checkboxes and radios
                    $('.styled').uniform();
                });
            }
        });
    }

    function get_close_employees(){
        var is_plan = "<?=$is_plan?>";
        var val = "<?php
                    if(isset($audit_plan_array['close_employees']))
                        echo $audit_plan_array['close_employees'];
                    else
                        echo "";?>";
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/all_close_employees",
            data:{'is_plan' : val, 'close_employees' : val},
            success: function(data) {
                $('#close_employee_tbody').html(data);
                $(function() {
                    // Style checkboxes and radios
                    $('.styled').uniform();
                });
            }
        });
    }

    function add_meeting_employee(){
        $('#modal_theme_primary').modal('show');
        $("#add_name").val("")
        $("#add_email").val("")
        $("#add_role").val("")
        $("#add_username").val("")
        $("#add_password").val("")
        $('#name_err').html('');
        $('#email_err').html('');
        $('#role_err').html('');
        $('#username_err').html('');
        $('#password_err').html('');
        $('#type_err').html('');

        $("#checked_div > label > div > span").removeClass("checked");
        $(".permisions").prop("checked", false);
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
        } else if(!$("#lead_auditor").is(":checked") && !$("#auditor").is(":checked") && !$("#process_owner").is(":checked") && !$("#auditee").is(":checked")) {
            $('#name_err').html('');
            $('#email_err').html('');
            $('#role_err').html('');
            $('#username_err').html('');
            $('#password_err').html('');
            $('#type_err').html('* this field is required');
            return false;
        } else {
            var auditor = 2;
            var lead_auditor = 1;
            var process_owner = 3;
            var auditee = 4;
            if(!$("#lead_auditor").is(":checked"))
                lead_auditor = null;
            if(!$("#auditor").is(":checked"))
                auditor = null;
            if(!$("#process_owner").is(":checked"))
                process_owner = null;
            if(!$("#auditee").is(":checked"))
                auditee = null;
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>index.php/Consultant/add_meeting_employee",
                data:{
                    'add_name' : $("#add_name").val(),
                    'add_email' : $("#add_email").val(),
                    'add_role' : $("#add_role").val(),
                    'add_username' : $("#add_username").val(),
                    'add_password' : $("#add_password").val(),
                    'auditor' : auditor,
                    'lead_auditor' : lead_auditor,
                    'process_owner' : process_owner,
                    'auditee' : auditee
                },
                success: function(data) {
                    $datas = $.parseJSON(data);
                    if($datas == 'success') {
                        $('#modal_theme_primary').modal('hide');
                        get_open_employees();
                        get_close_employees();
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
                                        $('#modal_theme_primary').modal('hide');
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
                                        $('#modal_theme_primary').modal('hide');
                                    }
                                }
                            }
                        });
                    }
                }
            });
        }
    }

    function modal_employee_sel(val) {
        modal_state = val;
        if(val == 'open') {
            $('#open_employee_check_err').html('');
//            $("#open_employee_tbody > tr > td> div >span").removeClass("checked");
//            $(".open_checker").prop("checked", false);
            $("#modal_open_employee_sel").modal('show');
        } else if(val == 'close') {
            $('#close_employee_check_err').html('');
//            $("#close_employee_tbody > tr > td> div >span").removeClass("checked");
//            $(".open_checker").prop("checked", false);
            $("#modal_close_employee_sel").modal('show');
        }
    }

    function all_open_checker() {
        if(modal_state == 'open') {
            is_checked_open = (is_checked_open + 1) % 2;
            if(is_checked_open == 1)
                $("#open_employee_tbody > tr > td> div >span").addClass("checked");
            else
                $("#open_employee_tbody > tr > td> div >span").removeClass("checked");
        } else if(modal_state == 'close') {
            is_checked_close = (is_checked_close + 1) % 2;
            if(is_checked_close == 1)
                $("#close_employee_tbody > tr > td> div >span").addClass("checked");
            else
                $("#close_employee_tbody > tr > td> div >span").removeClass("checked");
        }
        $(".open_checker").prop("checked", true);
    }

    function check_submit(state) {
        var employee_checked = "0";
        var check_state = "false";
        if(state == 'open') {
            var check_list = $("#open_employee_tbody > tr > td > div > span > input:checkbox");
        } else if(state == 'close') {
            var check_list = $("#close_employee_tbody > tr > td > div > span > input:checkbox");
        }
        for(var i = 0; i < check_list.length; i++) {
            check_state = check_state | $(check_list[i]).prop("checked");
        }
        for(var i = 0; i < check_list.length; i++) {
            if(check_state) {
                if($(check_list[i]).prop("checked")) {
                    employee_checked += "," + $(check_list[i]).val();
                }
            } else {
                if(state == 'open') {
                    $('#open_employee_check_err').html('* this field is required');
                } else if(state == 'close') {
                    $('#close_employee_check_err').html('* this field is required');
                }
                return false;
            }
        }
        if(state == 'open') {
            $("#open_who_list").val(employee_checked);
        } else if(state == 'close') {
            $("#close_who_list").val(employee_checked);
        }
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/checked_list",
            data:{'check_list' : employee_checked},
            success: function(data) {
                if(state == 'open') {
                    $("#modal_open_employee_sel").modal('hide');
                    $('#open_who').val(data);
                } else if(state == 'close') {
                    $("#modal_close_employee_sel").modal('hide');
                    $('#close_who').val(data);
                }
            }
        });
    }

    function selectNA() {
        $("#close_who_list").val('N/A');
        $('#close_who').val('N/A');
    }

    function selectTBD() {
        $("#close_when").val('TBD');
    }

    function confirmData() {
        if($("#open_who").val().length == 0) {
            $('#open_who_err').html('* this field is required');
            return false;
        } else if($("#open_where").val().length == 0) {
            $('#open_who_err').html('');
            $('#open_where_err').html('* this field is required');
            return false;
        } else if($("#open_cover").val().length == 0) {
            $('#open_who_err').html('');
            $('#open_where_err').html('');
            $('#open_cover_err').html('* this field is required');
            return false;
        } else if($("#schedule").val().length == 0) {
            $('#open_who_err').html('');
            $('#open_where_err').html('');
            $('#open_cover_err').html('');
            $('#schedule_err').html('* this field is required');
            return false;
        } else if($("#close_who").val().length == 0) {
            $('#open_who_err').html('');
            $('#open_where_err').html('');
            $('#open_cover_err').html('');
            $('#schedule_err').html('');
            $('#close_who_err').html('* this field is required');
            return false;
        } else if($("#close_where").val().length == 0) {
            $('#open_who_err').html('');
            $('#open_where_err').html('');
            $('#open_cover_err').html('');
            $('#schedule_err').html('');
            $('#close_who_err').html('');
            $('#close_where_err').html('* this field is required');
            return false;
        } else {
            document.audit_plan_form.submit();
        }
    }

    console.clear();
</script>

</body>

<!---!-->


</html>
