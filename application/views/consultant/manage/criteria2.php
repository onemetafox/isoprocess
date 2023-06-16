<!-- Primary modal -->
          <div id="criterias2" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title"><i class="icon-plus2 position-right"></i> AUDIT CRITERIA: </h6>
                </div>
                <div class="modal-body">
                <form method="post">
                       <div class="row">
                       <div class="col-md-10">
                        <div class="form-group has-feedback">
                        <input type="text" placeholder="" class="form-control" name="name" id="newcriteria2">
                        <div class="form-control-feedback">
                          <i class="icon-list text-muted"></i>
                        </div>
                      </div>
                      <span id="criteriaerr2" style="color:red;"></span>
                       </div>
                       <div class="col-md-2">
                         <a onclick="add_criteria2();" class="btn btn-primary">ADD</a>
                       </div>
                     </div>
                  <div class="row">
                       <div class="col-md-12">
                         <table class="table">
                           <thead>
                            <tr>
                              <th>VALUE</th>
                            <th>ACTION</th>
                            </tr>
                            </thead>
                            <tbody id="criterialist2">
                              
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
	function add_criteria2() {
        var newcriteria = $('#newcriteria2').val();
        if (newcriteria.length==0) {
         $('#criteriaerr2').html('* this field is required');
        } else{
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>index.php/consultant/add_criteria2",
                data:{'name' : newcriteria},
                success: function(data) {
                    $('#newcriteria2').val('');
                    callcriteria_2();
                    callcriteria1_2();

                }
            });
        }
  }
  $(function () {
      var name = "0";
      <?php if (!empty($checklist_id)): ?>
      name = "<?=$checklist_id?>";
      <?php endif; ?>
      $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/consultant/all_criteria2",
            data:{'name' : name},
            success: function(data) {
                $('#audit_criteria2').html(data);
            }
      });
  });
  $(document).ready(function () {
      $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/consultant/all_criteria_table2",
            data:{'name' : 1},
            success: function(data) {
            $('#criterialist2').html(data);
            }
      });
  });

  function callcriteria_2(){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/consultant/all_criteria_table2",
        data:{'name' : 1},
        success: function(data) {
            $('#criterialist2').html(data);
        }
      });
  }
  function deletecriteria2(val){
  	$.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/consultant/delete_criteria2",
        data:{'id' :val},
        success: function(data) {
            callcriteria_2();
            callcriteria1_2();
        }
      });
  }

  function callcriteria1_2(){
  	 $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/consultant/all_criteria2",
        data:{'name' : 0},
        success: function(data) {

            $('#audit_criteria2').html(data);
        }
      });
  }
</script>
