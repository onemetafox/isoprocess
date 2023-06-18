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
    <link href="<?=base_url(); ?>assets/css/jquery.multiselect.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/jquery.multiselect.js"></script>
    <!-- /core JS files -->

<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/plugins/ui/moment/moment.min.js"></script>-->
<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/plugins/pickers/daterangepicker.js"></script>-->
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/anytime.min.js"></script>
<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/plugins/pickers/pickadate/picker.js"></script>-->
<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/plugins/pickers/pickadate/picker.date.js"></script>-->
<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/plugins/pickers/pickadate/picker.time.js"></script>-->
<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/plugins/pickers/pickadate/legacy.js"></script>-->

    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/pages/form_bootstrap_select.js"></script>

    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/core/app.js"></script>
    <!-- <script type="text/javascript" src="<?=base_url(); ?>assets/js/pages/datatables_basic.js"></script> -->
<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/pages/picker_date.js"></script>-->
 <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
    <script type="text/javascript">
        var rangeDemoFormat = "%Y-%m-%d %H:%i:%s";
        var rangeDemoConv = new AnyTime.Converter({format:rangeDemoFormat});
        $(function() {
            // Style checkboxes and radios
            $(".styled, .multiselect-container input").uniform({
                radioClass: 'choice'
            });
            $(".file-styled").uniform({
                fileButtonClass: 'action btn btn-default'
            });
        });
        $(function(){
            // On demand picker
            $("#ButtonCreationDemoInput").AnyTime_picker({
                format: rangeDemoFormat
            });
            $("#ButtonCreationDemoInput3").AnyTime_picker({
                format: rangeDemoFormat
            });

            // On value change
            $("#ButtonCreationDemoInput3").change(function(e) {
                try {
                    var fromDay = rangeDemoConv.parse($("#ButtonCreationDemoInput3").val()).getTime();

                    var dayLater = new Date(fromDay);

                    // End date
                    $("#ButtonCreationDemoInput4")
                        .AnyTime_noPicker()
                        .removeAttr("disabled")
                        .val(rangeDemoConv.format(dayLater))
                        .AnyTime_picker({
                            earliest: dayLater,
                            format: rangeDemoFormat
                        });
                }
                catch(e) {

                    // Disable End date field
                    $("#ButtonCreationDemoInput4").val("").attr("disabled","disabled");
                }
            });

            // On value change
            $("#ButtonCreationDemoInput").change(function(e) {
                try {
                    var fromDay = rangeDemoConv.parse($("#ButtonCreationDemoInput").val()).getTime();

                    var dayLater = new Date(fromDay);

                    // End date
                    $("#ButtonCreationDemoInput1")
                        .AnyTime_noPicker()
                        .removeAttr("disabled")
                        .val(rangeDemoConv.format(dayLater))
                        .AnyTime_picker({
                            earliest: dayLater,
                            format: rangeDemoFormat
                        });
                }
                catch(e) {

                    // Disable End date field
                    $("#ButtonCreationDemoInput1").val("").attr("disabled","disabled");
                }
            });
        });

    </script>
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

    <style type="text/css">
        textarea {
            min-width: 100%;
            max-width: 100%;
            min-height: 100px;
            max-height: 100px;;
        }
        .AnyTime-win{
            z-index: 9999;
            position: absolute !important;
        }
        .datetime-picker{
            min-height: 20px !important;
        }
        .modal-open{
            overflow: auto;
        }
         .audit_sh{display: flex;}
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
                                }else{
                                    $logo=$logo1->logo;
                                }
                            }
                            ?>
                            <img src="<?php echo base_url(); ?>uploads/logo/<?=$logo?>" style="height:50px;">
                            <span class="text-semibold"><?=$title?></span>

                            <div class="pull-right">
                                <select class="form-conrtol" onchange="mails(this.value);">
                                    <option><?=$admin_emails?></option>
                                    <option><?=$comp_email?></option>
                                    <?php
                                    foreach ($auditors_email as $auditor_email) {?>
                                        <option><?=$auditor_email->employee_email?></option>
                                    <?php }?>
                                </select>
                                <a title="Download" type="button" class="btn btn-primary btn-sm "  onclick="printDiv('ptn')" ><i class="icon-download " aria-hidden="true"></i></a>
                                <a title="Mail" id="mails" href="mailto:<?=$admin_emails?>" class="btn btn-primary"><i class="icon-envelope "  aria-hidden="true"></i></a>
                            </div>
                        </h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/consultantdashboard"><i class="icon-home2 role-left"></i>Home</a></li>
                        <li>Process Audit</li>
                        <li>Audit</li>
                        <li><a href="#"><?=$title?></a></li>
                    </ul>

                    <ul class="breadcrumb-elements">

                    </ul>
                </div>
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content">
                <form action="<?php echo base_url();?>index.php/Consultant/audits_finish/<?=$pa_id?>" method="post" name="process_form">
                    <!-- Basic datatable -->
                    <div class="panel panel-flat" style="overflow: auto;">
                        <table class="table datatable-basic" id="ptn">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Process Name</th>
                                <th>Auditor</th>
                                <th>Process Owner</th>
                                <th>Auditee</th>
                                <th>Date/Time</th>
                                <th>Map Type</th>
                                <th width="200">Action</th>
                            </tr>
                            </thead>
                            <tbody id="process_tbody">
                            <?php  $count=1;
                            $assign_flag = true;
                            foreach ($processes as $process) { ?>
                                <tr>
                                    <td><?=$count?></td>
                                    <td><?=$process->process_name?></td>
                                    <td><?=$process->auditor_name?></td>
                                    <td><?=$process->process_owner_name?></td>
                                    <td>
                                        <?php if($process->sme != null): ?>
                                            <?php if($process->sme == 0): ?>
                                                TBD
                                            <?php elseif($process->sme == -1): ?>
                                                N/A
                                            <?php else: ?>
                                                <?=$process->sme_name?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($process->starttime_type != null || $process->endtime_type != null): ?>
                                            <?=$process->starttime_type . ' - ' . $process->endtime_type?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($process->map_type != null): ?>
                                            <?php if($process->map_type == 0): ?>
                                                TBD
                                            <?php elseif($process->map_type == 1): ?>
                                                Mind Map
                                            <?php elseif($process->map_type == 2): ?>
                                                Process Map
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td width="200">
                                        <?php if($process->map_type == null): ?>
                                            <?php $assign_flag = false; ?>
                                            <button type="button" class="btn btn-primary" onclick="assign('<?=$process->process_id?>')">Assign</button>
                                        <?php else: ?>
                                            <div class="audit_sh">
                                            <button type="button" class="btn btn-primary" onclick="edit('<?=$process->process_id?>')">Edit</button> &nbsp;
                                            <button type="button" class="btn btn-danger" onclick="delete_pro('<?=$process->id?>')">Delete</button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $count++; } ?>
                            </tbody>
                        </table>
                        <input type="hidden" id="submit_state" name="submit_state">

                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="text-right">
                                            <a class="watermark btn btn-primary" style="margin-right: 20px;"><i class="icon-file-picture position-left"></i> Watermark</a>
                                            <a type="button" class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>index.php/Consultant/select_process/<?=$pa_id?>" style="margin-right: 20px;"><i class="   icon-arrow-left16"></i> Back</a>
                                            <?php if (!$assign_flag): ?>
                                            <input type="submit" id="finish" class="btn btn-primary" value="Finish" name="finish" disabled>
                                            <?php else: ?>
                                            <input type="submit" id="finish" class="btn btn-primary" value="Finish" name="finish">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /basic datatable -->
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

<script type="text/javascript">
    $(document).ready(function(){
        $('#auditee').multiselect({
            columns : 1,
            placeholder : 'Select Auditee...'
        });

        $('#edit_auditee').multiselect({
            columns : 1,
            placeholder : 'Select Auditee...'
        });
    });
/****************Delete process******************/
    function delete_pro(val){
     $('#modal_schedule_delete').modal('show');
     $("#assign_schedule_id").val(val);

    }

    /*********************End***********************/
    function edit(val) {
        $('#modal_schedule_edit').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/find_process",
            data: {'id': val, 'pa_id':<?=$pa_id?>},
            success: function (data) {
                var datas = $.parseJSON(data);
                var auditee_array = datas.sme.split(", ");
                $("#edit_auditor").val(datas.auditor);
                $("#edit_owner").val(datas.process_owner);
                $("#edit_auditee").val(auditee_array);
                $("#edit_auditee").multiselect("reload");
                $("#edit_map_type").val(datas.map_type);
                $("#edit_process_id").val(datas.process_id);
                $("#ButtonCreationDemoInput").val(datas.start_time);
                $("#ButtonCreationDemoInput1").val(datas.end_time);
            }
        });
    }

    function assign(val) {
        $('#modal_schedule_assign').modal('show');
        $('#assign_process_id').val(val);
    }

    $('.watermark').on('click', function() {
        $('input[name="contract_id"]').val("<?=$audit_log->log_id?>");
        $('input[name="header_text"]').val("<?=$audit_log->header_text?>");
        $('input[name="footer_text"]').val("<?=$audit_log->footer_text?>");
        $('#set_watermark').modal("show");
    });

    function setWatermark() {
        var header_align = footer_align = 'left';
        for (x in $('input[name="header_align"]')) {
            if ($($('input[name="header_align"]')[x]).is(':checked')) {
                header_align = $('input[name="header_align"]')[x].value;
                break;
            }
        }
        for (x in $('input[name="footer_align"]')) {
            if ($($('input[name="footer_align"]')[x]).is(':checked')) {
                footer_align = $('input[name="footer_align"]')[x].value;
                break;
            }
        }
        var params = {
            'contract_id': $('input[name="contract_id"]').val(),
            'header_text': $('input[name="header_text"]').val(),
            'header_align': header_align,
            'footer_text': $('input[name="footer_text"]').val(),
            'footer_align': footer_align,
//            'logo_filename': $('.filename').html()
        };
        $.post("<?php echo base_url(); ?>index.php/Consultant/setWatermark", params, function(res) {
            var data = $.parseJSON(res);
            if (data) {
                var logoData = $('input[name="logo_file"]')[0].files[0];
                if (logoData) {
                    var nicURI = "<?php echo base_url(); ?>index.php/Consultant/upload_logo";
                    var A = new FormData();
                    A.append("id", params.contract_id);
                    A.append("logo", logoData);
                    var C = new XMLHttpRequest();
                    C.open("POST", nicURI);
                    C.onload = function() {
                        var E;
                        E = $.parseJSON(C.responseText);
                        if (E == "SUCCESS") {
//                            current_tr.attr('data-logo', $('.filename').html());
                            $('#set_watermark').modal("hide");
                            $('#watermark-form')[0].reset();
//                            $('.filename').html('');
                        } else if (E == "FAILED") {
                            alert('Sorry! Logo file was not uploaded');
                        }
                        return;
                    };
                    C.send(A);
                } else {
                    $('#set_watermark').modal("hide");
                    $('#watermark-form')[0].reset();
//                    $('.filename').html('');
                }
            }
        });
    }

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = "<table class='table table-framed'>" + printContents + "</table>";

        window.print();

        document.body.innerHTML = originalContents;
    }

    console.clear();
</script>

<!-- Primary modal -->
<div id="modal_schedule_assign" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?php echo base_url(); ?>index.php/Consultant/assign_process/<?=$pa_id?>" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Auditor: </label>
                                <select class="form-control" name="auditor" id="auditor" required>
                                    <?php foreach ($auditors as $auditor) { ?>
                                        <option value="<?= $auditor->employee_id ?>"><?= $auditor->employee_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Process Owner: </label>
                                <select class="form-control" name="process_owner" id="process_owner" required>
                                    <?php foreach ($owners as $owner) { ?>
                                        <option value="<?= $owner->employee_id ?>"><?= $owner->employee_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Auditee: </label>
                                <select class="form-control" name="auditee[]" multiple id="auditee" required>
                                    <option value="0">TBD</option>
                                    <option value="-1">N/A</option>
                                    <?php foreach ($smes as $sme) { ?>
                                        <option value="<?= $sme->employee_id ?>"><?= $sme->employee_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Map Type: </label>
                                <select class="form-control" name="map_type" id="map_type" required>
                                    <option value="0">TBD</option>
                                    <option value="2">Process Map</option>
                                    <option value="1">Mind Map</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Start Time: </label>
                                <div class="page-container datetime-picker">
                                    <div class="content-group">
                                        <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-icon" id="ButtonCreationDemoButton3"><i class="icon-calendar3"></i></button>
                                        </span>
                                            <input type="text" class="form-control" id="ButtonCreationDemoInput3" placeholder="Select a date" name="startTimeInput" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>End Time: </label>
                                <div class="page-container datetime-picker">
                                    <div class="content-group">
                                        <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-icon" id="ButtonCreationDemoButton4"><i class="icon-calendar3"></i></button>
                                        </span>
                                            <input type="text" class="form-control" id="ButtonCreationDemoInput4" placeholder="Select a date" name="endTimeInput" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="assign_process_id" name="assign_process_id">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="assign_submit"><i class="icon-plus2 role-right"></i> Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_schedule_edit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?php echo base_url(); ?>index.php/Consultant/edit_process/<?=$pa_id?>" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Auditor: </label>
                                <select class="form-control" name="edit_auditor" id="edit_auditor" required>
                                    <?php foreach ($auditors as $auditor) { ?>
                                        <option value="<?= $auditor->employee_id ?>"><?= $auditor->employee_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Process Owner: </label>
                                <select class="form-control" name="edit_owner" id="edit_owner" required>
                                    <?php foreach ($owners as $owner) { ?>
                                        <option value="<?= $owner->employee_id ?>"><?= $owner->employee_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Employees: </label>
                                <select class="form-control" name="edit_auditee[]" multiple id="edit_auditee" required>
                                    <option value="0">TBD</option>
                                    <option value="-1">N/A</option>
                                    <?php foreach ($smes as $sme) { ?>
                                        <option value="<?= $sme->employee_id ?>"><?= $sme->employee_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Map Type: </label>
                                <select class="form-control" name="edit_map_type" id="edit_map_type" required>
                                    <option value="0">TBD</option>
                                    <option value="2">Process Map</option>
                                    <option value="1">Mind Map</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Start Time: </label>
                                <div class="page-container datetime-picker">
                                    <div class="content-group">
                                        <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-icon" id="ButtonCreationDemoButton"><i class="icon-calendar3"></i></button>
                                        </span>
                                            <input type="text" class="form-control" id="ButtonCreationDemoInput" placeholder="Select a date" name="edit_startTimeInput" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>End Time: </label>
                                <div class="page-container datetime-picker">
                                    <div class="content-group">
                                        <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-icon" id="ButtonCreationDemoButton1"><i class="icon-calendar3"></i></button>
                                        </span>
                                            <input type="text" class="form-control" id="ButtonCreationDemoInput1" placeholder="Select a date" name="edit_endTimeInput" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="edit_process_id" name="edit_process_id">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="edit_submit"><i class="icon-plus2 role-right"></i> Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete schedule -->
<div class="modal fade" id="modal_schedule_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
 <form action="<?php echo base_url(); ?>index.php/Consultant/delete_schedule_id/?id=<?=$pa_id?>" method="post">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="exampleModalLabel">Delete Process</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <input type="hidden" id="assign_schedule_id" name="assign_schedule_id">
      <div class="modal-body">
          <h4 class="modal-title w-100 center">Are you sure?</h4>
        Do you really want to delete these records? This process cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </div>
</form>
  </div>
</div>
<!---------------End------------->
<div id="set_watermark" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Watermark</h6>
            </div>
            <!-- <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group has-feedback">
                        <input type="text" class="form-control" name="watermark" id="watermark">
                      </div>
                    </div>
                </div>
            </div> -->
            <div class="modal-body">
                <form id="watermark-form" class="form-horizontal" action="#">
                    <div class="form-group">
                        <label class="control-label col-lg-2">Logo File: </label>
                        <div class="col-lg-10">
                            <input type="hidden" name="contract_id">
                            <input type="file" class="file-styled" name="logo_file">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Header Text: </label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" name="header_text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="radio-inline radio-right">
                                <input type="radio" name="header_align" value="left" class="styled" checked="checked">
                                Left
                            </label>
                            <label class="radio-inline radio-right">
                                <input type="radio" name="header_align" value="center" class="styled">
                                Center
                            </label>
                            <label class="radio-inline radio-right">
                                <input type="radio" name="header_align" value="right" class="styled">
                                Right
                            </label>
                            <label class="radio-inline radio-right">
                                <input type="radio" name="header_align" value="hide" class="styled">
                                Hide
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Footer Text: </label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" name="footer_text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="radio-inline radio-right">
                                <input type="radio" name="footer_align" value="left" class="styled" checked="checked">
                                Left
                            </label>
                            <label class="radio-inline radio-right">
                                <input type="radio" name="footer_align" value="center" class="styled">
                                Center
                            </label>
                            <label class="radio-inline radio-right">
                                <input type="radio" name="footer_align" value="right" class="styled">
                                Right
                            </label>
                            <label class="radio-inline radio-right">
                                <input type="radio" name="footer_align" value="hide" class="styled">
                                Hide
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="setWatermark();">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->
</body>
</html>
