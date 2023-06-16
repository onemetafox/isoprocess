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
    <!-- /core JS files -->

    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/core/app.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
 <script type="text/javascript" src="<?= base_url(); ?>assets/js/pages/datatables_basic.js"></script>
<style type="text/css">
    
    .unknownlocation {
        color: red;
    }.samelocation {
        color: green;
    }
        .redlocation {
       color: #f89c0e;
    }
    /************************/
     a.notif {
      position: relative;
      display: block;
      height: 50px;
      width: 50px;
      background: url('http://i.imgur.com/evpC48G.png');
      background-size: contain;
      text-decoration: none;
    }
    .num {
      position: absolute;
      right: 11px;
      top: 6px;
      color: #fff;
    }
    .breadcrumb-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
ul.breadcrumb {
    width: 100%;
}
    /************************/
    /* CSS used here will be applied after bootstrap.css */

.dropdown {
    display:inline-block;
    margin-left:20px;
    padding:10px;
  }


.glyphicon-bell {
   
    font-size:1.5rem;
  }

.notifications {
   min-width:350px; 
  }
  
  .notifications-wrapper {
     overflow:auto;
      max-height:250px;
    }
    
 .menu-title {
     color:#ff7788;
     font-size:1.5rem;
      display:inline-block;
      }
 
.glyphicon-circle-arrow-right {
      margin-left:10px;     
   }
  
   
 .notification-heading, .notification-footer  {
    padding:2px 10px;
       }
      
        
.dropdown-menu.divider {
  margin:5px 0;          
  }

.item-title {
  
 font-size:1.3rem;
 color:#000;
    
}

.notifications a.content_1 {
 text-decoration:none;
 /*background:#ccc;*/

 }
 .content_1 {
    padding: 0 0px 0px 0px;
}   
.notification-item {
 padding:10px;
 margin:5px;
 background:#ccc;
 border-radius:4px;
 }
ul.dropdown-menu.notifications {
    left: auto;
    right: 0;
}



    </style>
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
                <div class="dropdown">
                  <a id="dLabel" role="button" class="notif" data-toggle="dropdown" data-target="#" href="/page.html">
                   <span class="num"><?php  echo $count_notification; ?></span>
                  </a>
                  
                  <ul class="dropdown-menu notifications" role="menu" aria-labelledby="dLabel">
                    <div class="notification-heading"><h4 class="menu-title">Suspicious Login</h4><!-- <h4 class="menu-title pull-right">View all<i class="glyphicon glyphicon-circle-arrow-right"></i></h4> -->
                    </div>
                    <li class="divider"></li>
                   <div class="notifications-wrapper">
                    <?php 
                    if ($count_notification == "0") { ?>
                       <a class="content_1" href="#" >
                    <div class="notification-item">
                    <h4 class="item-title"> There is no new notification : </h4>
                    </div>
                    </a>
                   <?php } else{
                     foreach ($login_history as $viewhistory) { 
                         $status = $viewhistory->status;
                        if($status == "2")
                      {
                        ?>
                     <a class="content_1" href="#" onclick="updatelogin_noti('<?=$viewhistory->id?>')">
                       <div class="notification-item">
                         <h4 class="item-title"> There is suspicious login IP: <b style="color: red;"><?=$viewhistory->IP_address?></b> AND location : <b style="color: red;"><?=$viewhistory->login_area?></b></h4>
                
                      </div>
                    </a>
                    
               <?php } } } ?>
                   
                   </div>
                    <!-- <li class="divider"></li>
                    <div class="notification-footer"><h4 class="menu-title">View all<i class="glyphicon glyphicon-circle-arrow-right"></i></h4></div> -->
                  </ul>
                </div>
                </div>
            </div>
            <!-- /page header -->


				<!-- Content area -->
				<div class="content">


					 <!-- Basic datatable -->
                <div class="panel panel-flat" id="ptn" style="overflow:auto;">
                    <table class="table  table-bordered datatable-basic">

                        <thead>

                        <tr>
                            <th>ID</th>
                            <th>Login Area</th>
                            <th>IP Address</th>
                            <th>Login Platform</th>
                            <th>Login Device</th>
                            <th>Time</th>
                           <th style="display: none;"></th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php $count=1;
                        foreach ($login_history as $viewhistory) {
                        $status = $viewhistory->status;
                        if($status == "1")
                        {
                            $class = "samelocation";
                        }elseif($status == "2"){
                            $class = "unknownlocation";
                        }else{
                           $class = "redlocation"; 
                        }
                            ?>


                            <tr class="<?php echo $class; ?>">
                                <td><?=$count?></td>
                                <td><?=$viewhistory->login_area?></td>
                                <td><?=$viewhistory->IP_address?></td>
                                <td><?=$viewhistory->login_platform?></td>
                                <td><?=$viewhistory->login_device?></td>
                                <td><?=$viewhistory->date_time?></td>
                                  <td style="display: none;">                             
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



<script type="text/javascript">
	function edit(val){
		 $('#modal_theme_primary1').modal('show'); 
               $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/Company/findcust",
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


         function updatelogin_noti(val){
        //alert(val);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/consultant/update_notification",
            data:{ 'Notification_id' : val},
            success: function(data) {
              window.location.reload();
             
            }
        });
    }
</script>
	<!-- /page container -->

	
<script type="text/javascript">
	
//console.clear();
$(document).ready(function() {
    $('.datatable-scroll').attr('style','overflow-x:scroll;');
    });
</script>

<script type="text/javascript">
	
//	$('.table').DataTable({
//    order: [[1, 'desc']],
//});

</script>
</body>




</html>
