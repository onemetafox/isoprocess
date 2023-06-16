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
    <!-- /core JS files -->

	<script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/ui/moment/moment.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/daterangepicker.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/charts/Chart.js" type="text/javascript"></script>
    <!-- begining of page level js -->
    <script src="<?=base_url();?>assets/js/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>

    
    <!-- end of global js -->
    <!-- begining of page level js -->
    <script type="text/javascript">
        $(function() {

        });
    </script>
    <style type="text/css">
        canvas {
            width: 100% !important;
            max-width: 2000px;
            height: auto !important;
        }
        .cstlist {
            background-color: #26a69a;
            color: #fff;
        }

        textarea {
            max-width: 100%;
            min-width: 100%;
            max-height: 70px;
            min-height: 70px;
        }
        .select2-container{
            display:none;
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
                            if ($this->session->userdata('admin_id')) {
                                $admin_id = $this->session->userdata('admin_id');
                                $logo1 = $this->db->query("select * from `admin` where `id`='$admin_id'")->row();

                                $dlogo = $this->db->query("select * from `default_setting` where `id`='1'")->row()->logo;

                                if ($logo1->logo == '1') {
                                    $logo = $dlogo;
                                } else {
                                    $logo = $logo1->logo;
                                }
                            }
                            ?>
                            <span class="text-semibold"><?= $title ?></span>
                            <div class="pull-right">
                                <button type="button" id="back_btn" class="btn btn-primary" style="margin-left:80px; ">BACK</button>
                            </div>
                        </h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url('index.php/Welcome/'.$view_string.'dashboard'); ?>"><i
                                    class="icon-home2 position-left"></i>Home</a></li>
                        <li><i class="position-left"></i>Statistic</li>
                        <li><a href="#"><?= $title ?></a></li>
                    </ul>
                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title"><?= $title ?></h6>
                    </div>
                    <div class="row panel-body text-left" style="padding-bottom: 0px;">
                        <div class="col-md-4">
                            <label style="margin-right:15px">Date Range: </label>
                            <button type="button" class="btn btn-default daterange-all-company">
                                <i class="icon-calendar22 position-left"></i>
                                <span></span>
                                <b class="caret"></b>
                            </button>
                            <input type="hidden" id="company_end">
                            <input type="hidden" id="company_start">
                        </div>
                        <div class="col-md-2" style="margin-left: 10px;">
                          <div class="">
                              <button class="btn btn-primary btn-sm" onclick="initCharts();">Search</button>
                          </div>
                        </div>
                        <div id="line-chart" class="chart"  style="height: 250px;">
                        </div>
                    </div>
                </div>
                <div class="panel panel-white">
                    <div class="panel-body text-left" style="padding-bottom: 0px;">
                        <div class="bar_chart">
                            <canvas id="bar-chart1" width="2000" height="300"></canvas> 
                        </div>
                    </div>
                </div>
                <div class="panel panel-white">
                    <div class="panel-body text-left">
                        <div class="pie_chart">
                            <canvas id="pie-chart" width="2000" height="300"></canvas>
                        </div>
                    </div>
                </div>
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
    <script type="text/javascript">
        //date range 
        
        function init_daterange_c(){
            $('#company_start').val(moment().subtract(1200, 'days').format('YYYY-MM-D'));
            $('#company_end').val(moment().format('YYYY-MM-D'));
            $('.daterange-all-company').daterangepicker(
                {
                    startDate: moment().subtract(1200, 'days'),
                    endDate: moment(),
                    //minDate: '01/01/2014',
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
                    init_barchart();
                }
            );
            $('.daterange-all-company span').html(moment().subtract(1200, 'days').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().format('MMMM D, YYYY'));
        }

        function init_armchart(ajaxUrl) {
            var params = {
                'start':$('#company_start').val(),
                'end':$('#company_end').val(),
            };
            $.post(ajaxUrl, params, function(data)
            {
                var data = $.parseJSON(data);
                chart_all_company = AmCharts.makeChart("line-chart", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": "<?php echo base_url('assets/js/plugins/amcharts/amcharts/images/')?>",
                    "autoMargins": false,
                    "marginLeft": 30,
                    "marginRight": 8,
                    "marginTop": 10,
                    "marginBottom": 26,
                    "fontFamily": 'Open Sans',
                    "color":    '#888',
                    "dataProvider": data.real_data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b>[[value]]</b> [[additional]]</span>",
                        "bullet": "round",
                        "dashLengthField": "dashLengthLine",
                        "lineThickness": 3,
                        "bulletSize": 7,
                        "bulletBorderAlpha": 1,
                        "bulletColor": "#FFFFFF",
                        "useLineColorForBulletBorder": true,
                        "bulletBorderThickness": 3,
                        "fillAlphas": 0,
                        "lineAlpha": 1,
                        "title": "COUNT",
                        "valueField": "count"
                    }],
                    "categoryField": "employee_name",
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0
                    }
                });
            });
        }
        function init_piechart(ajaxUrl) {
            var params = {
                'start':$('#company_start').val(),
                'end':$('#company_end').val(),
            };
            $(".pie_chart").html("<canvas id='pie-chart' width='2000' height='500'></canvas>");
            $.post(ajaxUrl, params, function(data)
            {
                var result = $.parseJSON(data);
                var barChartData = {
                    labels: result.employee_name,
                    datasets: [
                        {
                            label:"Numbers of Audit",
                            backgroundColor: ["#f89a14","#1E88E5","#4CAF50","#F4513E","#f89a04","#1E88A5","#4CAF00","#F451AE"],
                            hoverBackgroundColor: ["#f89a14","#1E88E5","#4CAF50","#F4513E","#f89a04","#1E88A5","#4CAF00","#F451AE"],
                            data : result.count
                        }
                    ]
                };
                var selector = '#pie-chart';
                $(selector).attr('width', $(selector).parent().width());
                var myBar = new Chart($("#pie-chart"), {
                    type: 'pie',
                    data: barChartData
                });
            });
        }
        function init_barchart(ajaxUrl) {

            var chartCount = 8;

            var params = {
                'start':$('#company_start').val(),
                'end':$('#company_end').val(),
            };
            $(".bar_chart").html('');
            $.post(ajaxUrl, params, function(data)
            {
                var result = $.parseJSON(data);

                var barChartData = {
                labels: result.employee_name,
                datasets: [
                    {
                        label:"Numbers of Audit",
                        backgroundColor: "#f89a14",
                        hoverBackgroundColor: "#f89a14",
                        data : result.count
                    }
                ]
                };
                
                $('.bar_chart').append('<canvas id="bar-chart" width="2000" height="500"></canvas>');
                var selector = '#bar-chart';
                $(selector).attr('width', $(selector).parent().width());
                var myBar = new Chart($("#bar-chart"), {
                    type: 'bar',
                    data: barChartData
                });
            });
        }
        
        $(function(){
            init_daterange_c();
            initCharts();
            $('#back_btn').click(function(){
                location.href = "<?php echo base_url('index.php/Consultant/close_audit')?>";
            })
        });

        function initCharts() {
            ajaxUrl = "<?php echo base_url('index.php/Consultant/close_auditstring')?>";
            init_barchart(ajaxUrl);
            init_piechart(ajaxUrl);
            init_armchart(ajaxUrl);
        }
    </script>
</body>

</html>
