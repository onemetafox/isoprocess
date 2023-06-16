<?php $this->load->view('employee/header.php'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>

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
                            <img src="<?php echo base_url(); ?>uploads/logo/<?=$logo?>" style="height:50px;"><?=$title?></h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
                        <li>Individual Inbox</li>
                        <li class="active"><?=$title?></li>
                    </ul>
                </div>
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content">
                <!-- Form horizontal -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title"><?=@$title_msz->title?>

                            <a class="btn btn-primary pull-right" onclick="printDiv('ptn');" > Print</a></h5>

                    </div>

                    <div class="panel-body">
                        <div class="row">

                            <div class="col-md-12" id="ptn">
                                <!-- Basic layout -->

                                <div class="panel panel-flat">


                                    <div class="panel-body">
                                        <ul class="media-list chat-list content-group">

                                            <?php
                                            $consultant_id = $this->session->userdata('consultant_id');
                                            $employee_id= $this->session->userdata('employee_id');

                                            foreach ($message as $messages) { ?>

                                                <?php
                                                if (@$messages->from_role=='consultant') { ?>


                                                    <li class="media " style="margin-right: 10px;">
                                                        <div class="media-body" >
                                                            <div class="media-content"><?=@$messages->message?>.</div>
                                        <span class="media-annotation display-block mt-10"><?php
                                            $consultant_id = $this->session->userdata('consultant_id');
                                            echo $from_users=@$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->username; ?>  (Consultant Owner)<i class="icon-user position-right text-muted"></i></span>
                                                        </div>

                                                    </li>

                                                    <?php




                                                } elseif($messages->from_user==$employee_id){?>
                                                    <?php

                                                    $user=@$this->db->query("select * from `employees` where `employee_id`='$messages->from_user'")->row();
                                                    $name=$user->employee_name;


                                                    ?>
                                                    <li class="media reversed" style="margin-left: 10px;">
                                                        <div class="media-body">
                                                            <div class="media-content"><?=@$messages->message?></div>
                                                            <span class="media-annotation display-block mt-10"> <i class="icon-user position-right text-muted"></i> <?=$name?>  </span>
                                                        </div>
                                                    </li>

                                                <?php } else{?>

                                                    <?php

                                                    $user=@$this->db->query("select * from `employees` where `employee_id`='$messages->from_user'")->row();
                                                    $name=$user->employee_name;


                                                    ?>
                                                    <li class="media" style="margin-left: 10px;">
                                                        <div class="media-body">
                                                            <div class="media-content"><?=@$messages->message?></div>
                                                            <span class="media-annotation display-block mt-10"> <i class="icon-user position-right text-muted"></i> <?=$name?>  </span>
                                                        </div>
                                                    </li>


                                                <?php } ?>

                                            <?php  }?>
                                        </ul>


                                        <form action="<?php echo base_url();?>index.php/Employee/mails_to_indi_data" method="post">
                                            <textarea  class="form-control content-group" rows="3" cols="1" name="message" placeholder="Enter your message..."></textarea>
                                            <input type="hidden" name="title" value="<?=$title_msz->title?>">

                                            <input type="hidden" name="to_user" value="<?=$title_msz->to_user?>">
                                            <input type="hidden" name="data_id" value="<?=$title_msz->id?>">

                                            <div class="row">
                                                <div class="col-xs-6">
                                                </div>
                                                <div class="col-xs-6 text-right">
                                                    <button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right"><b><i class="icon-circle-right2"></i></b> Send</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                                <!-- /basic layout -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /form horizontal -->


                <!-- Footer -->

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

    console.clear();

    function validateForm(){

        return true;
    }

</script>





<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
</body>

</html>
