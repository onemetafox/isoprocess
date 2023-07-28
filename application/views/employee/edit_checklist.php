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
      <link href="<?=base_url(); ?>assets/css/jquery.multiselect.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
      <script type="text/javascript" src="<?=base_url(); ?>assets/js/jquery.multiselect.js"></script>
    <!-- /core JS files -->
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>

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
                            if ($this->session->userdata('consultant_id')) {
                                $consultant_id = $this->session->userdata('consultant_id');
                                $audito1 = $this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row();

                                $dlogo = $this->db->query("select * from `default_setting` where `id`='1'")->row()->logo;

                                if ($audito1->logo == '1') {
                                    $audito = $dlogo;
                                } else {
                                    $audito = $audito1->logo;
                                }
                            }
                            ?>
                            <img src="<?php echo base_url(); ?>uploads/logo/<?= $audito ?>" style="height:50px;">
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
            <div class="content">
                <!-- Basic datatable -->
                <?php
                foreach ($checklist as $checklist) { ?>
                <div class="panel panel-body text-left" id="main-data">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-flat text-left">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>Process Name</td>
                                            <td><?=$process_name?></td>
                                        </tr>
                                        <tr>
                                            <td>Date</td>
                                            <td><?=$start_time?></td>
                                        </tr>
                                        <tr>
                                            <td>Auditor</td>
                                            <td><?=$auditor?></td>
                                        </tr>
                                        <tr>
                                            <td>Process Owner</td>
                                            <td><?=$process_owner?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php   $user_type = $this->session->userdata("user_type"); 
                            $evidences = json_decode($checklist->evidence);
                    ?>
                    <form id="save_checklist" action="<?php echo base_url(); ?>index.php/employee/update_checklist" method="post" style="display: inline-block;">
                        <input type="hidden" id = "process_id" name="process_id" value = "<?=$process_id?>">
                        <input type="hidden" id = "clause_id" name="clause_id" value = "<?=$clause_id?>">
                        <input type="hidden" id = "checklist_id" name="checklist_id" value = "<?=$checklist_id?>">
                        <div class="panel panel-flat text-left">
                            <div class="col-md-12" style="margin-top: 15px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label>Process Step</label>
                                                <input type="text" class="form-control" id = "process_step" name = "process_type" style="width: 100%;" required value="<?=$checklist->process_step?>">
                                            </div>
                                            <?php if ($clause_id != '6'): ?>
                                            <div class="col-md-4">
                                                <label>Expected Answer</label>
                                                <div class="radio" >
                                                    <label><input type="radio" class="styled" id="e_a4" name="expected_answer" onchange="change_status()" value="-1" <?php if ($checklist->answer == '-1'): ?> checked <?php endif; ?>>TBD</label>
                                                </div>
                                                <div class="radio" >
                                                    <label><input type="radio" class="styled" id="e_a1" name="expected_answer" onchange="change_status()" value="2"<?php if ($checklist->answer == '2'): ?> checked <?php endif; ?>>Yes</label>
                                                </div>
                                                <div class="radio" >
                                                    <label><input type="radio" class="styled" id="e_a2" name="expected_answer" onchange="change_status()" value="1" <?php if ($checklist->answer == '1'): ?> checked <?php endif; ?>>No</label>
                                                </div>
                                                <div class="radio" >
                                                    <label><input type="radio" class="styled" id="e_a3" name="expected_answer" onchange="change_status()" value="0" <?php if ($checklist->answer == '0'): ?> checked <?php endif; ?>>Not Sure</label>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="control-label">Questions</label>
                                                    <?php if($user_type == "Lead Auditor") : ?>
                                                        <button type="button" class="btn btn-primary btn-sm pull-right" onclick="TBD_btn('questions')">TBD</button>
                                                    <?php endif; ?>
                                                </div>
                                                <textarea placeholder="" class="form-control" name="questions" id="questions" rows="4"><?=$checklist->questions?></textarea>
                                            </div>
                                            <?php if ($clause_id != '4'): ?>
                                                <div class="col-md-4">
                                                    <label>Audit Trail</label>
                                                    <div class="radio" >
                                                        <label><input type="radio" class="styled" id="a_t4" name="audit_trail" onchange="change_status()" value="-1" <?php if ($checklist->audit_trail == '-1'): ?> checked <?php endif; ?>>TBD</label>
                                                    </div>
                                                    <div class="radio" >
                                                        <label><input type="radio" class="styled" id="a_t1" name="audit_trail" onchange="change_status()" value="2"<?php if ($checklist->audit_trail == '2'): ?> checked <?php endif; ?>>Yes</label>
                                                    </div>
                                                    <div class="radio" >
                                                        <label><input type="radio" class="styled" id="a_t2" name="audit_trail" onchange="change_status()" value="1" <?php if ($checklist->audit_trail == '1'): ?> checked <?php endif; ?>>No</label>
                                                    </div>
                                                    <div class="radio" >
                                                        <label><input type="radio" class="styled"id="a_t3" name="audit_trail" onchange="change_status()" value="0" <?php if ($checklist->audit_trail == '0'): ?> checked <?php endif; ?>>Not Sure</label>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-md-4">
                                                    <label>Effectiveness</label>
                                                    <textarea placeholder="" class="form-control" name="effectiveness" id="effectiveness"><?=$checklist->effectiveness?></textarea>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group has-feedback">
                                                    <label>Auditee: </label>
                                                    <select class="form-control" name="edit_auditee[]" multiple id="edit_auditee" required>
                                                        <option value="0">TBD</option>
                                                        <option value="-1">N/A</option>
                                                        <?php foreach ($smes as $sme) { ?>
                                                            <option value="<?= $sme->employee_id ?>" name="sjdfjsdfjk"><?= $sme->role ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Evidence1</label>
                                            <textarea placeholder="" class="form-control" <?=  $user_type=="Lead Auditor"?"":"readonly" ?> name="evidence[]" id="evidence1"><?=!$evidences?"":$evidences[0]?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Evidence2</label>
                                            <textarea placeholder="" class="form-control" <?=  $user_type=="Lead Auditor"?"":"readonly" ?> name="evidence[]" id="evidence2"><?=!$evidences?"":$evidences[1]?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Evidence3</label>
                                            <textarea placeholder="" class="form-control" <?=  $user_type=="Lead Auditor"?"":"readonly" ?> name="evidence[]" id="evidence3"><?=!$evidences?"":$evidences[2]?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Evidence4</label>
                                            <textarea placeholder="" class="form-control" <?=  $user_type=="Lead Auditor"?"":"readonly" ?> name="evidence[]" id="evidence4"><?=!$evidences?"":$evidences[3]?></textarea>
                                        </div>
                                        
                                    </div>

                                    
                                    <input type="hidden" id="edit_process_id" name="edit_process_id" value="<?=$process__id?>">
                                    <input type="hidden" id="audit_id" name="audit_id" value="<?=$audit_id?>">
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top: 25px;">
                                <div class="row">
                                    
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top: 25px;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="control-label">Audit Criteria</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="audit_criteria" id="audit_criteria" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <a data-toggle="modal" data-target="#criterias" class="btn btn-primary">MANAGE</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <select class="form-control" name="audit_criteria2" id="audit_criteria2" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <a data-toggle="modal" data-target="#criterias2" class="btn btn-primary">MANAGE</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <select class="form-control" name="audit_criteria3" id="audit_criteria3" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <a data-toggle="modal" data-target="#criterias3" class="btn btn-primary">MANAGE</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <select class="form-control" name="audit_criteria4" id="audit_criteria4" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <a data-toggle="modal" data-target="#criterias4" class="btn btn-primary">MANAGE</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" id="label-comments"><?=$checklist->status?></label>
                                            <input type="hidden" name = "label-report" id="label-report"/>
                                        </div>
                                        <textarea placeholder="" class="form-control" name="notes" id="notes" rows="5"><?=$checklist->note?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top: 25px;">
                                <div class="row">
                                    <div class="col-md-9">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" class="btn btn-primary btn-sm" style="float: left;margin-right: 10px;" href="<?php echo base_url(); ?>index.php/employee/save_criteria">
                                        <a type="button" class="btn btn-primary btn-sm" style="float: left;" onclick="page_cancel()">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
                } ?>
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
    var process_id = 0;
    if( Date.now() <= Date.parse('<?=$start_time?>')){
        $('#e_a1').prop("disabled", true);
        $('#e_a2').prop("disabled", true);
        $('#e_a3').prop("disabled", true);
        $('#e_a4').prop("disabled", true);
    }
    $('#a_t1').prop("disabled", true);
    $('#a_t2').prop("disabled", true);
    $('#a_t3').prop("disabled", true);
    $('#a_t4').prop("disabled", true);
    function page_cancel(){
        window.history.back();
    }
    function change_status(value){
        var expected_answer = $('#e_a3').prop("checked");
        if (expected_answer == true){
            $("#e_a4").prop("disabled", false);
            $('#a_t1').prop("disabled", false);
            $('#a_t2').prop("disabled", false);
            $('#a_t3').prop("disabled", false);
            $('#a_t4').prop("disabled", false);
            if(value){
                $('#a_t4').prop("checked", true);
            }
            if($('#a_t1').prop('checked')){
                $('#label-comments').text("Conformity table");
                $('#notes').text('');
                $('#notes').attr("readonly", false);
            }
            if($('#a_t2').prop('checked')){
                $('#label-comments').text("Nonconformity");
                $('#notes').text('');
                $('#notes').attr("readonly", false);
            }
            if($('#a_t3').prop('checked')){
                $('#label-comments').text("Opportunity For Improvement (OFI)");
                $('#notes').attr("readonly", false);
                $('#notes').text('');
            }
            if($('#a_t4').prop('checked')){
                $('#label-comments').text("TBD");
                $("#notes").text('This table is locked until you search for further evidence to determine if there is "conformity" select "yes" "nonconformity" select "No" or if you are still not sure you need to input an "Opportunity for Improvement" OFI');
                $('#notes').attr("readonly", true);
            }
        }else{
            $('#a_t1').prop("disabled", true);
            $('#a_t2').prop("disabled", true);
            $('#a_t3').prop("disabled", true);
            $('#a_t4').prop("disabled", true);
            $("#e_a3").prop("disabled", false);
        }
        if($('#e_a1').prop('checked')){
            $('#label-comments').text("Conformity table");
            $('#notes').text('');
            $('#notes').attr("readonly", false);
        }
        if($('#e_a2').prop('checked')){
            $('#label-comments').text("Nonconformity");
            $('#notes').text('');
            $('#notes').attr("readonly", false);
        }
        
        if($('#e_a4').prop('checked')){
            $('#label-comments').text("TBD");
            $('#notes').text('This table is locked until you search for evidence to determine if there is "conformity" select "yes" "nonconformity" select "No" or if you are still not sure you need to input an "Opportunity for Improvement" OFI')
            $('#notes').attr("readonly", true);
        }
    }
    function TBD_btn(id) {
        $("#" + id).val("TBD");
    }

 /////////////////////////////////////////////////////////////
$(document).ready(function(){
    $.ajax({
        type: "POST",
        // url: "<?php echo base_url(); ?>index.php/Employee/findprocess",
        // data: {'id': <?=$process__id?>, 'pa_id':<?=$audit_id?>},
        url: "<?php echo base_url(); ?>index.php/Employee/findAuditee",
        data: {'id': <?= $checklist_id ?>},
        success: function (data) {
            var datas = $.parseJSON(data);
            var auditee_array = datas.auditees.split(", ");
            $("#edit_auditee").val(auditee_array);
            $("#edit_auditee").multiselect("reload");
            
        }
    });

    $('#edit_auditee').multiselect({
        columns : 1,
        placeholder : 'Select Auditee...'
    });
    jQuery(document).on("click","#ms-list-1 button",function(){
        var val = $(".ms-options ul li.selected input").val();
        if (val == "0") {
            $("input[value='281']").prop('disabled', true);
            $("input[value='302']").prop('disabled', true);
            $("input[value='306']").prop('disabled', true);
            $("input[value='307']").prop('disabled', true);
            $("input[value='-1']").prop('disabled', true);

        } else if ((val == "281") || (val == "302") || (val == "306") || (val == "307")) {

            $("input[value='0']").prop('disabled', true);
            $("input[value='-1']").prop('disabled', true);

        }else if(val == "-1"){
            $("input[value='281']").prop('disabled', true);
            $("input[value='302']").prop('disabled', true);
            $("input[value='306']").prop('disabled', true);
            $("input[value='307']").prop('disabled', true);
            $("input[value='0']").prop('disabled', true);
        }
    
    });
    $(document.body).delegate('input','click', function() {
        var val = $(this).val();
        if (val == "0") {
            $("input[value='281']").prop('checked', false);
            $("input[value='302']").prop('checked', false);
            $("input[value='306']").prop('checked', false);
            $("input[value='307']").prop('checked', false);
            $("input[value='-1']").prop('checked', false);
            $("input[value='281']").prop("disabled", $(this).is(":checked"));
            $("input[value='302']").prop("disabled", $(this).is(":checked"));
            $("input[value='306']").prop("disabled", $(this).is(":checked"));
            $("input[value='307']").prop("disabled", $(this).is(":checked"));
            $("input[value='-1']").prop("disabled", $(this).is(":checked"));
        } else if ((val == "281") || (val == "302") || (val == "306") || (val == "307")) {     
            $("input[value='0']").prop("disabled", $(this).is(":checked"));
            $("input[value='-1']").prop("disabled", $(this).is(":checked"));
            $("input[value='0']").prop('checked', false);
            $("input[value='-1']").prop('checked', false);

        }else if(val == "-1"){
            $("input[value='281']").prop("disabled", $(this).is(":checked"));
            $("input[value='302']").prop("disabled", $(this).is(":checked"));
            $("input[value='306']").prop("disabled", $(this).is(":checked"));
            $("input[value='307']").prop("disabled", $(this).is(":checked"));
            $("input[value='0']").prop("disabled", $(this).is(":checked"));
            $("input[value='281']").prop('checked', false);
            $("input[value='302']").prop('checked', false);
            $("input[value='306']").prop('checked', false);
            $("input[value='307']").prop('checked', false);
            $("input[value='0']").prop('checked', false);
        }
    });
});
 /////////////////////////////////////////////////////////////    
</script>
<?php $this->load->view('consultant/manage/criteria'); ?>
<?php $this->load->view('consultant/manage/criteria2'); ?>
<?php $this->load->view('consultant/manage/criteria3'); ?>
<?php $this->load->view('consultant/manage/criteria4'); ?>
</body>
</html>
