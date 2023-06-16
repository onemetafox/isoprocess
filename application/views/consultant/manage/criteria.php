<!-- Primary modal -->
          <div id="criterias" class="modal fade">
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
                        <input type="text" placeholder="" class="form-control" name="name" id="newcriteria">
                        <div class="form-control-feedback">
                          <i class="icon-list text-muted"></i>
                        </div>
                      </div>
                      <span id="criteriaerr" style="color:red;"></span>
                       </div>
                       <div class="col-md-2">
                         <a onclick="add_criteria();" class="btn btn-primary">ADD</a>
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
                            <tbody id="criterialist">
                              
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
	function add_criteria() { 
	var newcriteria = $('#newcriteria').val(); 
	if (newcriteria.length==0) {
     $('#criteriaerr').html('* this field is required');
 	}else{
	            $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/add_criteria",
                    data:{'name' : newcriteria},
                    success: function(data) {

	                    $('#criteria').html(data);
    	                $('#newcriteria').val('');
						callcriteria();
						callcriteria1();

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
                    url: "<?php echo base_url(); ?>index.php/consultant/all_criteria",
                    data:{'name' : name},
                    success: function(data) {
                    $('#audit_criteria').html(data);
                    }
                  });
  });
  $(document).ready(function () {
              $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_criteria_table",
                    data:{'name' : 1},
                    success: function(data) {
                    $('#criterialist').html(data);
                    }
                  });
  });

  function callcriteria(){
  	$.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_criteria_table",
                    data:{'name' : 1},
                    success: function(data) {
                    $('#criterialist').html(data);
                    }
                  });


  }
  function deletecriteria(val){
  	$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>index.php/consultant/delete_criteria",
			data:{'id' :val},
			success: function(data) {
				callcriteria();
				callcriteria1();
			}
		  });

  }

  function callcriteria1(){
  	 $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_criteria",
                    data:{'name' : 0},
                    success: function(data) {

                    $('#audit_criteria').html(data);
                    }
                  });
  }
</script>
