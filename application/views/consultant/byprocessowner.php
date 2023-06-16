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

<!--    <script type="text/javascript" src="--><?//= base_url(); ?><!--assets/js/plugins/tables/datatables/datatables.min.js"></script>-->
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/ui/moment/moment.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/daterangepicker.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/pickers/pickadate/picker.date.js"></script>

<style type="text/css">
    	.cstlist {
		    background-color:#26a69a;
		    color: #fff;
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

                            <div class="pull-right">
                            <select class="form-conrtol" onchange="mails(this.value);">
							        
                                       <option><?=$admin_emails?></option>
                                  
                                    <option><?=$comp_email?></option>
                                    <?php 
                                    foreach ($employees_email as $employees_emails) {?>
                                       <option><?=$employees_emails->employee_email?></option>
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
                        <li><a href="<?php echo base_url(); ?>index.php/Welcome/consultantdashboard"><i
                                    class="icon-home2 role-left"></i>Home</a></li>
                        <li><a href="#"><?= $title ?></a></li>

                    </ul>

                    <ul class="breadcrumb-elements">

                    </ul>
                    <div style="padding-top: 2px;">
                      <button type="button" id="detail_btn" class="btn btn-primary" style="margin-left:30px ">CHART</button>
                    </div>
                </div>
            </div>
            <!-- /page header -->


				<!-- Content area -->
				<div class="content">

				  <form class="form-horizontal" method="post" action="<?php echo  base_url(); ?>index.php/Consultant/byprocessowner" enctype="multipart/form-data" name="byaudit_form">
            <div class="panel panel-body  text-left" style="padding-bottom: 0px;">
              <div class="col-md-11">
                  <div class="row">
                    <div class="col-md-10">
                      <div class="form-group col-md-5">
                        <select id="processowner_sel" name="processowner_sel" class="form-control bootstrap-select" onchange="filterProcessowner();">
                            <option value="-1"
                              <?php
                                  $processowner_sel = $this->input->post('processowner_sel');
                                  if($processowner_sel == -1) {
                              ?>
                              selected
                                <?php } ?>>ALL</option>
                            <?php foreach ($byprocessowner_data as $key => $processowner) {?>
                              <option value="<?php echo $processowner->employee_id ?>"
                                <?php
                                    $processowner_sel = $this->input->post('processowner_sel');
                                    if($processowner_sel == $processowner->employee_id) {
                                ?>
                                selected
                                <?php } ?>
                                ><?php echo $processowner->employee_name?></option>
                            <?php } ?>
                        </select>
                      </div>
                      <div class="form-group col-md-5" style="margin-left:10px">
                          <label style="margin-right:15px">Date Range: </label>
                          <button type="button" class="btn btn-default daterange-all-company">
                              <i class="icon-calendar22 position-left"></i>
                              <span></span>
                              <b class="caret"></b>
                          </button>
                          <input type="hidden" id="company_end" name="company_end">
                          <input type="hidden" id="company_start" name="company_start">
                      </div>
                      <div class="col-md-2" style="margin-left: 10px;">
                          <div class="form-group">
                              <button type="submit" class="form-control btn btn-primary btn-sm pull-right">Search</button>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </form>

					<!-- Basic datatable -->
					<div class="panel panel-flat" style="overflow: auto;" id="ptn">
						<table class="table table-lg table-bordered">
							<thead>
								<tr>
								  <th>NO</th>
									<th>PROCESS OWNER</th>
									<th>NONCOMFORMITY</th>
									<th>OPPORTUNITY FOR IMPROVEMENT</th>
                  <th>CORRECTIVE</th>
								</tr>
							</thead>
							<tbody>
								<?php $count=1;
                foreach ($byprocessowner_data as $byprocessowner) { ?>
  								<tr style="background:#0097a7">
  								  <td><?php echo $count?></td>
                    <td><?php echo $byprocessowner->employee_name?></td>
                    <td><?php echo $byprocessowner->noncomformity?></td>
                    <td><?php if (isset($byprocessowner->ofi)) echo $byprocessowner->ofi; else echo '';?></td>
                    <td><?php if (isset($byprocessowner->corrective)) echo $byprocessowner->corrective; else echo '';?></td>
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
<script type="text/javascript">
	function mails(val){
   
   $("#mails").prop("href","mailto:"+val);


	}
</script>
<script type="text/javascript">
  $('body').on('click','.delete' ,function(e){
     var id = $(this).attr('id');
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
               window.location.href="<?php echo base_url();?>index.php/consultant/delete_byprocessownerform/"+id;
            }
        }
     }
    });
});
</script>

<script type="text/javascript">
	function edit(val){
		 $('#modal_theme_primary1').modal('show'); 
               $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/findcust",
                    data:{ 'id' : val},
                      success: function(data) {
                      var datas = $.parseJSON(data)
                     $("#name").val(datas.name);
                     $("#address").val(datas.address);
                     $("#city").val(datas.city);
                     $("#state").val(datas.state);
                     $("#cust_id").val(datas.id);
                    }
                  });
    }
</script>


<script type="text/javascript">
	
  console.clear();
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
  }

  $('#detail_btn').click(function(){
        location.href="<?php echo base_url('index.php/Consultant/statistic/2')?>"
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
	<!-- /page container -->
</body>
</html>
