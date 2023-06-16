<!-- Primary modal -->
          <div id="grade_nonconforms" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title"><i class="icon-plus2 position-right"></i>Grade of Non-Conformity: </h6>
                </div>
                <div class="modal-body">
                <form method="post">
                       <div class="row">
                       <div class="col-md-10">
                        <div class="form-group has-feedback">
                        <input type="text" placeholder="" class="form-control" name="name" id="newgrade_nonconform">
                        <div class="form-control-feedback">
                          <i class="icon-list text-muted"></i>
                        </div>
                      </div>
                      <span id="grade_nonconformerr" style="color:red;"></span>
                       </div>
                       <div class="col-md-2">
                         <a onclick="add_grade_nonconform();" class="btn btn-primary">ADD</a>
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
                            <tbody id="grade_nonconformlist">
                              
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
	function add_grade_nonconform() {
	var newgrade_nonconform = $('#newgrade_nonconform').val();
	if (newgrade_nonconform.length==0) {
     $('#grade_nonconformerr').html('* this field is required');
 	}else{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>index.php/consultant/add_grade_nonconform",
			data:{'name' : newgrade_nonconform},
			success: function(data) {

			$('#grade_nonconform').html(data);
			$('#newgrade_nonconform').val('');
			callgrade_nonconform();
			callgrade_nonconform1();
			}
		  });
	}          
  }
  $(document).ready(function () {
      var name = "0";
      <?php if (!empty($checklist_id)): ?>
      name = "<?=$checklist_id?>";
      <?php endif; ?>
              $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_grade_nonconform",
                    data:{'name' : name},
                    success: function(data) {

                    $('#grade_nonconform').html(data);
                    }
                  });


  });

  $(document).ready(function () { 
              $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_grade_nonconform_table",
                    data:{'name' : 1},
                    success: function(data) {
                    $('#grade_nonconformlist').html(data);
                    }
                  });
  });

  function callgrade_nonconform(){
  	$.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_grade_nonconform_table",
                    data:{'name' : 1},
                    success: function(data) {
                    $('#grade_nonconformlist').html(data);
                    }
                  });

  	          
  }
  function deletegrade_nonconforms(val){
  	$.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/delete_grade_nonconform",
                    data:{'id' :val},
                    success: function(data) {
						callgrade_nonconform();
						callgrade_nonconform1();


                    }
                  });
  }

  function callgrade_nonconform1(){
  	 $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_grade_nonconform",
                    data:{'name' : 1},
                    success: function(data) {

                    $('#grade_nonconform').html(data);
                    }
                  });
  }
</script>
