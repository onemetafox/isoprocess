<!-- Primary modal -->
<div id="audit_types" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><i class="icon-plus2 position-right"></i>Type of Audit: </h6>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group has-feedback">
                                <input type="text" placeholder="" class="form-control" name="name" id="new_audittype">
                                <div class="form-control-feedback">
                                    <i class="icon-list text-muted"></i>
                                </div>
                            </div>
                            <span id="audit_type_err" style="color:red;"></span>
                        </div>
                        <div class="col-md-2">
                            <a onclick="add_audit_type();" class="btn btn-primary">ADD</a>
                        </div>
                    </div>
                    <div class="row" style="max-height: 450px; overflow-y: auto;">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NAME</th>
                                    <th>ACTION</th>
                                </tr>
                                </thead>
                                <tbody id="audit_type_list">

                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->

<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/all_audittype",
            data:{'name' : 1},
            success: function(data) {
                $('#audit_type').html(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/all_audittype_table",
            data:{'name' : 1},
            success: function(data) {
                $('#audit_type_list').html(data);
            }
        });
        $("#new_audittype").keypress( function(event) {
            if (event.keyCode == "13") {
                add_audit_type();
            }
        });
    });

    function add_audit_type() {
        var new_audittype = $('#new_audittype').val();
        if (new_audittype.length==0) {
            $('#audit_type_err').html('* this field is required');
        }else{
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>index.php/Consultant/add_audit_type",
                data:{'type_of_audit' : new_audittype},
                success: function(data) {
                    $('#audit_type').html(data);
                    $('#new_audittype').val('');
                    callaudittype();
//                    callaudittype1();
                }
            });
        }
    }

    function deleteaudittype(val){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/delete_audittypet",
            data:{'id' :val},
            success: function(data) {
                var datas = $.parseJSON(data)
                if(datas == "success") {
                    callaudittype();
                    callaudittype1();
                } else {
                    var dialog = bootbox.dialog({
                        title: 'Warning',
                        message: "You cannot delete! It is used.",
                        size: 'small',
                        buttons: {
                            cancel: {
                                label: "Ok",
                                className: 'btn-danger',
                                callback: function() {
                                    dialog.modal('hide');
                                }
                            }
                        }
                    });
                }
            }
        });
    }

    function callaudittype(){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/all_audittype_table",
            data:{'name' : 1},
            success: function(data) {
                $('#audit_type_list').html(data);
            }
        });
    }

    function callaudittype1(){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/consultant/all_audittype",
            data:{'name' : 1},
            success: function(data) {
                $('#audit_type').html(data);
            }
        });
    }
</script>
