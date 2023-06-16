<!-- Primary modal -->
          <div id="clauses" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title"><i class="icon-plus2 position-right"></i> CLAUSE: </h6>
                </div>
                <div class="modal-body">
                <form method="post">
                   <div class="row">
                       <div class="col-md-10">
                        <div class="form-group has-feedback">
                        <input type="text" placeholder="" class="form-control" name="name" id="newclause">
                        <div class="form-control-feedback">
                          <i class="icon-list text-muted"></i>
                        </div>
                      </div>
                      <span id="clauseerr" style="color:red;"></span>
                       </div>
                       <div class="col-md-2">
                         <a onclick="add_clause();" class="btn btn-primary">ADD</a>
                       </div>
                   </div>
                  <div style="width: 0px;">
                       <div class="col-md-12" id = "jqxWidget_manage" style="min-width: 460px;">

                       </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <a onclick="editclause();" class="btn btn-primary">EDIT</a>
                    <a onclick="deleteclause();" class="btn btn-primary">DELETE</a>
                  <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
                  </form>
              </div>
            </div>
          </div>
<!-- /primary modal -->


<script type="text/javascript">
    var manage_id = 0;
    var theme = 'shinyblack';
	function add_clause() { 
	var newclause = $('#newclause').val(); 
	if (newclause.length==0) {
     $('#clauseerr').html('* this field is required');
 	}else
    {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/consultant/add_clause",
            data:{
                'name' : newclause,
                'manage_id' : manage_id
            },
            success: function(data) {
                callclause(data);
            }
        });
	}          
  }
  $(document).ready(function () {
      var data = <?=$clause_list?>;
      // prepare the data
      var source =
      {
          datatype: "json",
          datafields: [
              { name: 'id' },
              { name: 'parent_id' },
              { name: 'name' },
              { name: 'symbol' }
          ],
          id: 'id',
          localdata: data
      };
      var dataAdapter = new $.jqx.dataAdapter(source);
      dataAdapter.dataBind();
      var records = dataAdapter.getRecordsHierarchy('id', 'parent_id', 'items', [{ name: 'name', map: 'label'}]);
      $('#jqxWidget_manage').jqxTree({ source: records, width: '300px', theme: theme });
      $('#jqxWidget_manage').on('select', function (event) {
          var args = event.args;
          var item = $('#jqxWidget_manage').jqxTree('getItem', args.element);
          manage_id = item.id;
      });
  });

  function callclause(data){
      var source =
      {
          datatype: "json",
          datafields: [
              { name: 'id' },
              { name: 'parent_id' },
              { name: 'name' },
              { name: 'symbol' }
          ],
          id: 'id',
          localdata: data
      };
      var dataAdapter = new $.jqx.dataAdapter(source);
      dataAdapter.dataBind();
      var records = dataAdapter.getRecordsHierarchy('id', 'parent_id', 'items', [{ name: 'name', map: 'label'}]);
      $('#jqxWidget_manage').jqxTree({ source: records, width: '300px', theme: theme });
      $('#jqxWidget').jqxTree({ source: records, width: '300px', theme: theme });
      $("#jqxWidget_manage").jqxTree('expandItem', $("#"+manage_id)[0]);
      callclause1();
  }
  function deleteclause(){
      if (manage_id == '0'){
          var dialog = bootbox.dialog({
              title: 'Warning',
              message: "You must select a clause.",
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
      }else{
          $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>index.php/consultant/delete_clause",
              data:{
                  'manage_id' : manage_id
              },
              success: function(data) {
                  callclause(data);
              }
          });
      }
  }
  function editclause(){
      var newclause = $('#newclause').val();
      if (newclause.length==0) {
          $('#clauseerr').html('* this field is required');
      }else
      {
          if (manage_id == '0'){
              var dialog = bootbox.dialog({
                  title: 'Warning',
                  message: "You must select a clause.",
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
          }else{
              $.ajax({
                  type: "POST",
                  url: "<?php echo base_url(); ?>index.php/consultant/edit_clause",
                  data:{
                      'manage_id' : manage_id,
                      'name' : newclause
                  },
                  success: function(data) {
                      callclause(data);
                  }
              });
          }
      }
  }
  function callclause1(){
  	 $.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>index.php/consultant/all_clause",
			data:{'name' : 1},
			success: function(data) {

			$('#clause').html(data);
			$('#clause1').html(data);
			$('#clause2').html(data);
			}
		  });
  }
</script>
