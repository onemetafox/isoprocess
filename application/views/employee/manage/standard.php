<!-- Primary modal -->
          <div id="standards" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title"><i class="icon-plus2 position-right"></i> STANDARD: </h6>
                </div>
                <div class="modal-body">
                <form method="post">
                       <div class="row">
                       <div class="col-md-10">
                        <div class="form-group has-feedback">
                        <input type="text" placeholder="" class="form-control" name="name" id="newstandard">
                        <div class="form-control-feedback">
                          <i class="icon-list text-muted"></i>
                        </div>
                      </div>
                      <span id="standarderr" style="color:red;"></span>
                       </div>
                       <div class="col-md-2">
                         <a onclick="add_standard();" class="btn btn-primary">ADD</a>
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
                            <tbody id="standardlist">
                              
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
	function add_standard() { 
	var newstandard = $('#newstandard').val(); 
	if (newstandard.length==0) {
     $('#standarderr').html('* this field is required');
 	}else{
	            $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/add_standard",
                    data:{'name' : newstandard},
                    success: function(data) {
                    	
                    $('#standard').html(data);
                    $('#standard1').html(data);
                    $('#standard2').html(data);
                    $('#newstandard').val('');
						callstandard();
						callstandard1();
                    }
                  });
	}          
  }
  $(document).ready(function () { 
   
              $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_standard",
                    data:{'name' : 1},
                    success: function(data) {

                    $('#standard').html(data);
                    $('#standard1').html(data);
                    $('#standard2').html(data);
                    }
                  });


  });

  $(document).ready(function () { 
              $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_standard_table",
                    data:{'name' : 1},
                    success: function(data) {
                    $('#standardlist').html(data);
                    }
                  });
  });

  function callstandard(){
  	$.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_standard_table",
                    data:{'name' : 1},
                    success: function(data) {
                    $('#standardlist').html(data);
                    }
                  });

  	          
  }
  function deletestandard(val){
  	$.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/delete_standard",
                    data:{'id' :val},
                    success: function(data) {

						callstandard();
						callstandard1();

                    }
                  });
  }

  function callstandard1(){
  	 $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/consultant/all_standard",
                    data:{'name' : 1},
                    success: function(data) {

                    $('#standard').html(data);
                    $('#standard1').html(data);
                    $('#standard2').html(data);
                    }
                  });
  }
</script>
