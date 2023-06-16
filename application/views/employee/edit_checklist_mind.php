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
    <link href="<?= base_url(); ?>assets/css/jqx.base.css" rel="stylesheet" type="text/css">
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
<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/pages/datatables_basic.js"></script>-->
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/jqxcore.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/jqxdata.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/jqxdropdownbutton.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/jqxpanel.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/jqxtree.js"></script>

    <style>
        #paneljqxWidget{
            min-height: 400px;
            border-width: 0px !important;
        }
        #jqxWidget{
            width: 100% !important;
        }
    </style>
    <script type="text/javascript">
        $(function() {
            // Setting datatable defaults

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
            <form id="create_checklist" action="<?php echo base_url(); ?>index.php/Employee/create_checklist" method="post">
                <input type="hidden" id = "process_id" name="process_id" value = "<?=$process_id?>">
                <input type="hidden" id = "clause_id" name="clause_id" value = "0">
                <input type="hidden" id = "checklist_id" name="checklist_id" value = "0">
            </form>
            <form id="get_checklist" action="<?php echo base_url(); ?>index.php/Employee/get_checklist_mind" method="post">
                <input type="hidden" id = "process_id" name="process_id" value = "<?=$process_id?>">
                <input type="hidden" id = "clause_mind_id" name="clause_id" value = "0">
            </form>
            <div class="content">
                <!-- Basic datatable -->
                <div class="panel" style="min-height: 60px;">
                    <div style="min-height: 10px;height: 10%;"></div>
                    <div style="height: 80%;padding-left: 1%;padding-right: 1%;">
                        <a type="button" class="btn btn-primary btn-sm" style="float: right;" onclick = "submit_checklist()">Submit CheckList</a>
                        &nbsp;
                        <a type="button" class="btn btn-primary btn-sm" style="margin-right : 20px; float: right;" onclick = "fn_downloadReport(<?=$process_id?>)">Download Report</a>
                        <a data-toggle="modal" data-target="#modal_send_message" class="btn btn-primary btn-sm"><i class="icon-mail5"></i> Send Message</a>
                        <a type="button" class="btn btn-info btn-sm" href="<?php echo base_url(); ?>index.php/Employee/show_process_message/<?=$process_id?>">
                            <i class="icon-mail-read"></i> View Message
                        </a>
                    </div>
                    <div style="min-height: 10px;height: 10%;"></div>
                </div>
                <div class="panel" style="width: 18%;min-height: 400px;float: left;">
                    <div style="width:100%;height: 50px;padding-top: 8px;padding-left: 10px;">
                        <a data-toggle="modal" data-target="#clauses" class="btn btn-primary">MANAGE</a>
                    </div>
                    <div id = "jqxWidget" style="width: 300px;min-height: 400px;">

                    </div>
                </div>
                <div class="panel" style="margin-left:10px;padding-left:10px;padding-right: 10px;float: left;width: 80%;">
                    <div style="padding-left: 10px;display: inline-block;padding-top: 10px;">
                        <a type="button" class="btn btn-primary btn-sm" style="float:left;margin-left: 0px;" onclick="create_checklist()">NEW</a>
                    </div>
                    <div class="panel panel-flat" style="">
                        <table class="table datatable-basic" style="width: 1400px;">
                            <thead>
                            <tr>
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
                            foreach ($checklist as $checklist) { ?>
                                <tr>
                                    <td><?= $checklist->questions ?></td>
                                    <td><?= $checklist->criteria_id ?></td>
                                    <td>
                                        <?php if ($checklist->answer == 0): ?>
                                            Not Sure
                                        <?php endif; ?>
                                        <?php if ($checklist->answer == 1): ?>
                                            NO
                                        <?php endif; ?>
                                        <?php if ($checklist->answer == 2): ?>
                                            YES
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($checklist->audit_trail == 0): ?>
                                            Not Sure
                                        <?php endif; ?>
                                        <?php if ($checklist->audit_trail == 1): ?>
                                            NO
                                        <?php endif; ?>
                                        <?php if ($checklist->audit_trail == 2): ?>
                                            YES
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            if (strlen($checklist->evidence) > 45){
                                                echo substr($checklist->evidence,0,45)."...";
                                            }else{
                                                echo $checklist->evidence;
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?= $checklist->status ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($checklist->note) > 45){
                                            echo substr($checklist->note,0,45)."...";
                                        }else{
                                            echo $checklist->note;
                                        }
                                        ?>
                                    </td>
                                    <td style="width: 19%;">
                                        <a type="button" onclick = "logic_show(<?=$checklist->checklist_id?>)" class="btn btn-primary btn-sm">Logic</a>
                                        <?php if ($checklist->status != 'NO ISSUE'): {?>
                                            <?php if($checklist->load_status == 0):?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/Employee/corrective_action_form/<?=$checklist->checklist_id?>" class="btn btn-primary btn-sm">Load</a>
                                            <?php enif;?>
                                            <?php else : ?>
                                                <a type="button" href = "<?php echo base_url(); ?>index.php/employee/corrective_action_form_view/<?=$checklist->checklist_id?>" class="btn btn-primary btn-sm">View</a>
                                            <?php endif;?>
                                        <?php } endif;?>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "edit_checklist(1,<?=$checklist->checklist_id?>)">Edit</a>
                                        <a type="button" class="btn btn-primary btn-sm" onclick = "delete_checklist(-1,<?=$checklist->checklist_id?>)">Delete</a>
                                        <?php if ($checklist->corrective_id != ''): ?>
                                        <a type="button" href = "<?php echo base_url(); ?>index.php/Employee/resolution/<?=$checklist->corrective_id?>" class="btn btn-primary btn-sm">Log</a>
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
<!-- /primary modal -->

<script type="text/javascript">
    var selected_id = 0;
    var logic_checklist_id = 0;
    $(document).ready(function () {
        var theme = 'shinyblack';

        var data = <?=$clause_list?>;
        selected_id = "<?=$clause_id?>";

        // prepare the data
        var source =
        {
            datatype: "json",
            datafields: [
                { name: 'id' },
                { name: 'parent_id' },
                { name: 'name' },
                { name: 'symbol' }
            ],
            id: 'id',
            localdata: data
        };

        // create data adapter.
        var dataAdapter = new $.jqx.dataAdapter(source);
        dataAdapter.dataBind();
        var records = dataAdapter.getRecordsHierarchy('id', 'parent_id', 'items', [{ name: 'name', map: 'label'}]);
        $('#jqxWidget').jqxTree({ source: records, width: '300px', theme: theme });
        $('#jqxWidget').jqxTree('selectItem', $("#"+selected_id)[0]);
        $("#jqxWidget").jqxTree('expandItem', $("#"+selected_id)[0]);

        $('#jqxWidget').on('select', function (event) {
            var args = event.args;
            var item = $('#jqxWidget').jqxTree('getItem', args.element);
            var symbol = item.id;
            $("#clause_mind_id").val(symbol);
            document.forms['get_checklist'].submit();
        });
    });

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
//            $('.dataTables_length select').select2({
//                minimumResultsForSearch: Infinity,
//                width: 'auto'
//            });
    });
    $(document).ready(function() {
        $('.datatable-scroll').attr('style','overflow-x:scroll;');
    });
    var process_id = 0;
//    console.clear();
    function redirect(){
        location.href = "<?php echo base_url(); ?>index.php/Employee/open_audit";
    }
//    function edit_checklist(){
//        var map_type = $("#mind_map").prop("checked");
//        if (map_type == true){
//            location.href = "<?php //echo base_url(); ?>//index.php/Employee/edit_checklist_mind/"+process_id;
//        }else if (map_type == false){
//            location.href = "<?php //echo base_url(); ?>//index.php/Employee/edit_checklist_process/"+process_id;
//        }
//
    function logic_show(id){
        logic_checklist_id = id;
        $('#Audit_Plan_Modal').modal();
    }
    function check_map(id){
        process_id = id;
        $("#SelectMap_Modal").modal();
        $("#SelectMap_Modal").show();
    }
    function create_checklist(){
        if (selected_id == 0){
            return;
        }
        $("#clause_id").val(selected_id);
        $("#checklist_id").val('0');
        document.forms['create_checklist'].submit();
    }
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
                        document.forms['create_checklist'].action = "<?php echo base_url(); ?>index.php/Employee/submit_checklist";
                        document.forms['create_checklist'].submit();
                    }
                }
            }
        });

    }
    function edit_checklist(id,checklist_id){
        $("#clause_id").val(selected_id);
        $("#checklist_id").val(checklist_id);
        document.forms['create_checklist'].action = "<?php echo base_url(); ?>index.php/Employee/edit_checklist";
        document.forms['create_checklist'].submit();
    }
    function sendMessage(val) {
        var message = $('#message').val();
        if(message.length == 0) {
            $("#message_err").html('* this field is required');
            return false;
        } else {
            $.ajax({
                 type: "POST",
                 url: "<?php echo base_url(); ?>index.php/Employee/send_process_message",
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
    }

</script>
<?php $this->load->view('employee/manage/clause'); ?>
<?php $this->load->view('employee/manage/logic'); ?>
</body>
</html>
