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
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/core/app.js"></script>
    <!-- <script type="text/javascript" src="<?=base_url(); ?>assets/js/pages/datatables_basic.js"></script> -->
     <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
    <script type="text/javascript">
        $(function() {
            // Style checkboxes and radios
            $('.styled').uniform();

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
                    targets: [3 ]
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
                order: [4,"desc"]
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
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_theme_primary">New Process</button>
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
                <?php if($this->session->flashdata('message')=='success') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        Process Successfully Created..
                    </div>
                <?php $this->session->unset_userdata('message');  } ?>
                <?php if($this->session->flashdata('message')=='failed') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">Oppps!</span> Something Went Wrong Please try again.
                    </div>
                <?php $this->session->unset_userdata('message');  } ?>
                  <?php if($this->session->flashdata('message')=='update_success') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold"></span> Process updated Successfully...
                    </div>
                <?php  $this->session->unset_userdata('message'); } ?>
                  <?php if($this->session->flashdata('message')=='success_del') { ?>
                    <div class="alert alert-styled-right alert-styled-custom alert-arrow-right alpha-teal alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold"></span> Process Deleted Successfully...
                    </div>
                <?php $this->session->unset_userdata('message');  } ?>

                <form action="<?php echo base_url();?>index.php/Consultant/audit_schedule/<?=$pa_id?>" method="post" name="process_form">
                    <!-- Basic datatable -->
                    <div class="panel panel-flat">
                        <table class="table datatable-basic">
                            <thead>
                            <tr>
                                <th width="120">No</th>
                                <th width="120">Check</th>
                                <th width="300">Process Name</th>
                                <th width="500">Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="process_tbody">
                            <?php  $count=1;
                            foreach ($processes as $process) { ?>
                                <tr>
                                    <td width="120"><?=$count?></td>
                                    <?php
                                    $flag = false;
                                    if($is_process) {
                                        $check_list = explode(",", $process_list);
                                        for($i = 0; $i < count($check_list); $i++) {
                                            if($process->process_id == $check_list[$i]) {
                                                $flag = true;
                                                echo '<td width="120"><input class="styled" type="checkbox" checked value="' . $process->process_id . '"></td>';
                                                break;
                                            }
                                        }
                                        if(!$flag)  {
                                            echo '<td width="120"><input class="styled" type="checkbox" value="' . $process->process_id . '"></td>';
                                        }
                                    } else {
                                        echo '<td width="120"><input class="styled" type="checkbox" value="' . $process->process_id . '"></td>';
                                    }
                                    ?>
                                    <td width="300"><?=$process->process_name?></td>
                                    <td width="500"><?=$process->description?></td>
                                    <td>
                                        <ul class="icons-list">
                                            <li class="text-primary-600" onclick="edit_pro('<?=$process->process_id?>?>');"><a href="#"><i class="icon-pencil7"></i></a></li>
                                            <li class="text-danger-600"><a href="#" id="<?/*=$employee->employee_id*/?>" onclick="delete_process('<?=$process->process_id?>')" class="delete" ><i class="icon-trash"></i></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <?php $count++; } ?>
                            </tbody>
                        </table>
                        <div class="panel-body">
                            <span id="process_err" style="color:red;"></span>
                            <input type="hidden" id="process_list" name="process_list" />
                        </div>

                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="text-right">
                                            <a type="button" class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>index.php/Consultant/audit_plan/<?=$pa_id?>" style="margin-right: 40px;"><i class="icon-arrow-left16"></i> Back</a>
                                            <a type="button" class="btn btn-primary btn-sm" onclick="confirmData();">Next <i class="icon-arrow-right16 position-left"></i></a>
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

    function confirmData() {
        var process_checked = "0";
        var check_state = "false";
        var check_list = $("#process_tbody > tr > td > div > span > input:checkbox");
        if(check_list.length > 0) {
            for(var i = 0; i < check_list.length; i++) {
                check_state = check_state | $(check_list[i]).prop("checked");
            }
            for(var i = 0; i < check_list.length; i++) {
                if(check_state) {
                    if($(check_list[i]).prop("checked")) {
                        process_checked += "," + $(check_list[i]).val();
                    }
                } else {
                    $('#process_err').html('* this field is required');
                    return false;
                }
            }
            $("#process_list").val(process_checked);
            document.process_form.submit();
        } else {
            var dialog = bootbox.dialog({
                title: 'Warning',
                message: "You should select at least more than 1 process.",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "OK",
                        className: 'btn-danger',
                        callback: function() {
                            dialog.modal('hide');
                        }
                    }
                }
            });
        }
    }

    console.clear();

    /****************Edit process Ajax*********************/
        function edit_pro(val) {
        var pa_id = val;
        //alert(sw_id);
        $('#modal_schedule_edit').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/edit_single_process",
            data: {'pa_id': pa_id},
            success: function (data) {
                //console.log(data);
                var datas = JSON.parse(data);
                $('#process_name_1').val(datas.edit_single_pro[0].process_name);
                $('#description_1').val(datas.edit_single_pro[0].description);
                $('#process_id').val(datas.edit_single_pro[0].process_id);
               // $('#process_name_1').attr('value', datas.edit_single_pro[0].process_name);
                //$('#description_1').attr('value', datas.edit_single_pro[0].description);
               // $('#process_id').attr('value', datas.edit_single_pro[0].process_id);
            }
        });
    }
    /**********End**************/
    /************Delete process*******************/
    function delete_process(val) {
        var pa_id = val;
        $('#modal_schedule_delete').modal('show');
        $('#process_id_del').val(pa_id);
    }
    /*****************End************************/
</script>

</body>

<!-- Primary modal -->
<div id="modal_theme_primary" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-plus2 role-right"></i> New Process</h6>
            </div>
            <form action="<?php echo base_url();?>index.php/Consultant/add_process/<?=$pa_id?>" method="post" name="add_employee">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Process Name: </label>
                                <input type="text" placeholder="Process Name" class="form-control" name="process_name" id="process_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Process Description: </label>
                                <textarea placeholder="" class="form-control" name="description" id="description" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="icon-plus2 role-right"></i> Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->
<!-- Delete Process -->
<div class="modal fade" id="modal_schedule_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?php echo base_url();?>index.php/Consultant/del_process/?id=<?=$pa_id?>" method="post" name="add_employee">
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
<!-------------Edit process------>
<div id="modal_schedule_edit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-plus2 role-right"></i>Edit Process</h6>
            </div>
           <form action="<?php echo base_url();?>index.php/Consultant/update_process/?id=<?=$pa_id?>" method="post" name="add_employee">
            <?php
             /*echo "<pre>"; 
            print_r($edit_single_pro); 
            echo "</pre>";*/
            ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Process Name: </label>
                                <input type="text" placeholder="Process Name" class="form-control" name="process_name_1" id="process_name_1" value="" required>
                                <input type="hidden" placeholder="Process Name" class="form-control" name="process_id" id="process_id" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Process Description: </label>
                                <textarea placeholder="" value="" class="form-control" name="description_1" id="description_1" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="icon-plus2 role-right"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-----Edit process end------>
</html>
