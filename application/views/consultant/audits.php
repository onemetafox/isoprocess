<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
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
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/pages/form_bootstrap_select.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
    <!-- /core JS files -->

    <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/ui/moment/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/daterangepicker.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<style type="text/css">
    .audits_del_sub{
        display: flex;
    }
    .audits_del_sub a.btn.btn-primary.btn-sm {
    margin-right: 10px;
}
</style>
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
    </script>
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
                            <div class="pull-right"></div>
                        </h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/consultantdashboard"><i
                                    class="icon-home2 role-left"></i>Home</a></li>
                        <li>Process Audit</li>
                        <li><a href="#"><?= $title ?></a></li>
                    </ul>
                    <ul class="breadcrumb-elements"></ul>
                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">

                <form class="form-horizontal" method="post" action="<?php echo  base_url(); ?>index.php/Consultant/audits" enctype="multipart/form-data" name="byaudit_form">
                    <div class="panel panel-body  text-left" style="padding-bottom: 0px;">
                      <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="col-md-5" style="margin-left: 10px;">
                                <div class="form-group">
                                    <label class="control-label col-lg-4">Lead Auditor</label>
                                    <div class="col-lg-6">
                                        <select id="leadauditor_sel" name="leadauditor_sel" class="form-control bootstrap-select" onchange="filterleadauditor();">
                                          <option value="-1"
                                            <?php
                                                $leadauditor_sel = $this->input->post('leadauditor_sel');
                                                if($leadauditor_sel == -1) {
                                            ?>
                                            selected
                                              <?php } ?>>ALL</option>
                                          <?php foreach ($leadauditors as $key => $leadauditor) {?>
                                            <option value="<?php echo $leadauditor->employee_id ?>"
                                              <?php
                                                  $leadauditor_sel = $this->input->post('leadauditor_sel');
                                                  if($leadauditor_sel == $leadauditor->employee_id) {
                                              ?>
                                              selected
                                              <?php } ?>
                                              ><?php echo $leadauditor->employee_name?></option>
                                          <?php } ?>
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <!-- <div class="col-md-2">
                                    <span style="margin-right:15px">Date Range: </span>
                                </div> -->
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                        <input type="text" class="form-control daterange-single" name="company_start" value="<?= $filter['company_start']?>">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                        <input type="text" class="form-control daterange-single1" name="company_end" value="<?= $filter['company_end']?>">
                                    </div>
                                </div>
                                <!-- <button type="button" class="btn btn-default daterange-all-company">
                                    <i class="icon-calendar22 position-left"></i>
                                    <span></span>
                                    <b class="caret"></b>
                                </button> -->
                                <!-- <input type="hidden" id="company_end" name="company_end">
                                <input type="hidden" id="company_start" name="company_start"> -->
                            </div>
                                <div class="col-md-1" style="margin-left: 10px;">
                                    <div class="form-group">
                                        <button type="submit" class="form-control btn btn-primary btn-sm pull-right">Search</button>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </form>
                 <?php if($this->session->flashdata('message')=='success_del') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold"></span> Process Deleted Successfully...
                    </div>
                <?php   } ?>
                <!-- Basic datatable -->
                <div class="panel panel-flat" style="overflow:auto;">
                    <table class="table datatable-basic">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Type of Audit</th>
                                <th>Lead Auditor</th>
                                <th>FREQUENCY</th>
                                <th>Days</th>
                                <th>TRIGGER</th>
                                <th>TYPE</th>
                                <th>Last Audit Date</th>
                                <th>Status</th>
                                <th width="300">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1;
                       /* echo "<pre>";
                        print_r($audits);
                        echo "</pre>"*/
                        foreach ($audits as $audit) { ?>
                            <tr>
                                <td><?= $count ?></td>
                                <td><?= $audit->type_of_audit ?></td>
                                <td><?= $audit->employee_name ?></td>
                                <td><?= $audit->frequency_name ?></td>
                                <td><?= $audit->days ?></td>
                                <td><?= $audit->trigger_name ?></td>
                                <td><?= $audit->type ?></td>
                                <td><?= $audit->created_at ?></td>
                                <td>
                                    <?php if ($audit->status_days < 0): ?>
                                        <div style="color:#fff;background-color: #f44336;padding-left: 3px;padding-right: 3px;border-radius:2px;max-width: 90px;">
                                            PAST DUE <?=$audit->status_days?> Days
                                        </div>
                                    <?php else: ?>
                                        <?php if ($audit->status_days < 7 && $audit->status_days > 0): ?>
                                            <div style="color:#fff;background-color: #4CAF50;padding-left: 3px;padding-right: 3px;border-radius:2px;max-width: 90px;">
                                                DUE IN <?=$audit->status_days?> days
                                        <?php elseif ($audit->status_days >= 7): ?>
                                            <div style="color:#fff;background-color: #2196f3;padding-left: 3px;padding-right: 3px;border-radius:2px;max-width: 90px;">
                                                OK
                                        <?php elseif ($audit->status_days == 0): ?>
                                            <div style="color:#fff;background-color: #4CAF50;padding-left: 3px;padding-right: 3px;border-radius:2px;max-width: 90px;">
                                                DUE
                                        <?php endif; ?>
                                            </div>
                                    <?php endif; ?>
                                </td>
                                <td width="300">
                                    <div class="audits_del_sub">
                                    <a type="button" class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>index.php/Consultant/add_audit_log/<?=$audit->pa_id?>">Submit</a>
                                    <a type="button" class="btn btn-danger btn-sm" href="#" onclick="delete_audit('<?=$audit->pa_id?>')">Delete</a>
                                     </div>
                                </td>
                            </tr>
                            <?php $count++;
                        } ?>
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

<!-- Modal -->
<div class="modal fade" id="modal_audit_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?php echo base_url();?>index.php/Consultant/del_audit/?>" method="post" name="add_employee">
    <div class="modal-content">  
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="exampleModalLabel">Delete Process</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <input type="hidden"  class="form-control" name="process_id_del" id="process_id_del" value="" required>
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

<style type="text/css">
    #s2id_autogen1{display:none;}
</style>

<script type="text/javascript">
    $(function(){
        init_daterange_c();
    })

    function filterleadauditor() {
        document.byaudit_form.submit();
    }
    function init_daterange_c(){
        $('.daterange-single').daterangepicker({ 
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $('.daterange-single1').daterangepicker({ 
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    }
    // function init_daterange_c(start,end){

    //     if (start == '' && end == '') {
    //         $('#company_start').val(moment().subtract(1200, 'days').format('YYYY-MM-D'));
    //         $('#company_end').val(moment().format('YYYY-MM-D'));
    //     } else {
    //         $('#company_start').val(start);
    //         $('#company_end').val(end);
    //     }
        
    //     $('.daterange-all-company').daterangepicker(
    //         {
    //             startDate: moment().subtract(1200, 'days'),
    //             endDate: moment(),
    //             maxDate: <?php echo date('d/m/Y')?>,
    //             dateLimit: { days: 1000000000 },
    //             ranges: {
    //                 'Today': [moment(), moment()],
    //                 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //                 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //                 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //                 'This Month': [moment().startOf('month'), moment().endOf('month')],
    //                 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //             },
    //             opens: 'right',
    //             applyClass: 'btn-small bg-slate',
    //             cancelClass: 'btn-small btn-default'
    //         },
    //         function(start, end) {
    //             $('.daterange-all-company span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY'));
    //             $("#company_start").val(start.format('YYYY-MM-D'));
    //             $("#company_end").val(end.format('YYYY-MM-D'));
    //         }
    //     );
    //     if (start != '' && end != '') {
    //         $("#company_start").val(start );
    //         $("#company_end").val(end);
    //         $('.daterange-all-company span').html(start + ' &nbsp; - &nbsp; ' + end);
    //     }
    //     else
    //         $('.daterange-all-company span').html(moment().subtract(1200, 'days').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY'));
    // }

/************Delete Audit*******************/
    function delete_audit(val) {
        var pa_id = val;
        $('#modal_audit_delete').modal('show');
        $('#process_id_del').val(pa_id);
    }
    /*****************End************************/
</script>
</body>
</html>
