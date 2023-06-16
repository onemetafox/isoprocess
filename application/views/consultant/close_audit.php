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
                    <ul class="breadcrumb-elements" style="float:left">
                        <div style="padding-top: 2px;">
                            <button type="button" id="detail_btn" class="btn btn-primary" style="margin-left:30px ">CHART</button>
                        </div>
                    </ul>
                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">

                <form class="form-horizontal" method="post" action="<?php echo  base_url(); ?>index.php/Consultant/close_audit" enctype="multipart/form-data" name="byaudit_form">
                    <div class="panel panel-body  text-left" style="padding-bottom: 0px;">
                      <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col-md-4" style="margin-left: 10px;">
                                  <label class="control-label col-md-4">Lead Auditor</label>
                                  <div class="col-md-8">
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
                              <div class="form-group col-md-6">
                                <label style="margin-right:15px">Date Range: </label>
                                <button type="button" class="btn btn-default daterange-all-company">
                                    <i class="icon-calendar22 position-left"></i>
                                    <span></span>
                                    <b class="caret"></b>
                                </button>
                                <input type="hidden" id="company_end" name="company_end">
                                <input type="hidden" id="company_start" name="company_start">
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

                 <div class="panel panel-flat" style="overflow:auto;">
                    <table class="table">
                        <thead>
                            <th>Number of Audits</th>
                            <th>Type of Audit</th>
                            <th>Lead Auditor</th>
                            <th>Trigger</th>
                            <th>Frequency</th>
                        </thead>
                        <tbody>
                        <?php foreach ($close_audits as $audit) { ?>
                            <tr>
                                <td><?php echo $audit->count ?></td>
                                <td><?php echo $audit->type_of_audit ?></td>
                                <td><?php echo $audit->employee_name ?></td>
                                <td><?php echo $audit->trigger_name ?></td>
                                <td><?php echo $audit->frequency_name ?></td> 
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <form id = "download_pdf" action="<?php echo base_url(); ?>index.php/Consultant/download_pdf" method="post">
                    <input type = "hidden" id = "download_id" name = "download_id">
                    <textarea id = "download_text" name="download_text" style="display: none;"></textarea>
                </form>
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

<style type="text/css">
    #s2id_autogen1{display:none;}
</style>

<script type="text/javascript">
    console.clear();

      function filterleadauditor() {
        document.byaudit_form.submit();
      }

    $('#detail_btn').click(function(){
        location.href="<?php echo base_url('index.php/Consultant/statistic/4')?>"
    });
function init_daterange_c(start,end){

        if (start == '' && end == '') {
            $('#company_start').val(moment().subtract(1200, 'days').format('YYYY-MM-D'));
            $('#company_end').val(moment().format('YYYY-MM-D'));
        } else {
            $('#company_start').val(start);
            $('#company_end').val(end);
        }
        
        $('.daterange-all-company').daterangepicker(
            {
                startDate: moment().subtract(1200, 'days'),
                endDate: moment(),
                maxDate: <?php echo date('d/m/Y')?>,
                dateLimit: { days: 1000000000 },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'right',
                applyClass: 'btn-small bg-slate',
                cancelClass: 'btn-small btn-default'
            },
            function(start, end) {
                $('.daterange-all-company span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY'));
                $("#company_start").val(start.format('YYYY-MM-D'));
                $("#company_end").val(end.format('YYYY-MM-D'));
            }
        );
        if (start != '' && end != '') {
            $("#company_start").val(start );
            $("#company_end").val(end);
            $('.daterange-all-company span').html(start + ' &nbsp; - &nbsp; ' + end);
        }
        else
            $('.daterange-all-company span').html(moment().subtract(1200, 'days').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY'));
    }

    $(function() {
        var company_start = '<?php echo $this->input->post("company_start")?>';
        var company_end = '<?php echo $this->input->post("company_end")?>';
        init_daterange_c(company_start, company_end);
    });
</script>
</body>
</html>
