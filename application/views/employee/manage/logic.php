<!-- Primary modal -->
<div id="Audit_Plan_Modal" class="modal fade">
    <div class="modal-dialog" style = "width: 300px;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title">Select Audit Plan</h6>
            </div>
            <div class="modal-body">
                <div class="radio" >
                    <label><input type="radio" class="styled" id="quality" name="plan" value="1" checked>Quality</label>
                </div>
                <div class="radio" >
                    <label><input type="radio" class="styled" id="food" name="plan" value="2">Food</label>
                </div>
                <div class="radio" >
                    <label><input type="radio" class="styled" id="environment" name="plan" value="3">Environment</label>
                </div>
            </div>
            <div class="modal-footer">
                <button id = "plan_cancel" type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="plan_next()">Next</button>
            </div>
        </div>
    </div>
</div>
<div id="question_modal" class="modal fade">
    <div class="modal-dialog" style = "width: 300px;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title">Question</h6>
            </div>
            <div class="modal-body">
                <div id = "question_div">
                    <p id="question_body"></p>
                </div>
                <div class="radio" >
                    <label><input type="radio" class="styled" id="question_yes" name="question" value="1" checked>Yes</label>
                </div>
                <div class="radio" id = "question_no_div">
                    <label><input type="radio" class="styled" id="question_no" name="question" value="2">No</label>
                </div>
                <div class="radio" >
                    <label><input type="radio" class="styled" id="question_notsure" name="question" value="3">Not Sure</label>
                </div>
            </div>
            <div class="modal-footer">
                <button id = "question_cancel" type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="question_next()">Next</button>
            </div>
        </div>
    </div>
</div>
<div id="step1_modal" class="modal fade">
    <div class="modal-dialog" style = "width: 300px;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title">Question</h6>
            </div>
            <div class="modal-body">
                <div id="step1_div">
                    <p id="step1_body"></p>
                </div>
                <div class="radio" >
                    <label><input type="radio" class="styled" id="step1_yes" name="step1" value="1" checked>Yes</label>
                </div>
                <div class="radio" >
                    <label><input type="radio" class="styled" id="step1_no" name="step1" value="2">No</label>
                </div>
            </div>
            <div class="modal-footer">
                <button id = "step1_cancel" type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="step1_next()">Next</button>
            </div>
        </div>
    </div>
</div>
<div id="step2_modal" class="modal fade">
    <div class="modal-dialog" style = "width: 300px;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title">Question</h6>
            </div>
            <div class="modal-body">
                <div>
                    <p id="step2_body"></p>
                </div>
                <div class="radio" >
                    <label><input type="radio" class="styled" id="step2_yes" name="step2" value="1" checked>Yes</label>
                </div>
                <div class="radio" >
                    <label><input type="radio" class="styled" id="step2_no" name="step2" value="2">No</label>
                </div>
            </div>
            <div class="modal-footer">
                <button id = "step2_cancel" type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="step2_next()">Next</button>
            </div>
        </div>
    </div>
</div>
<div id="step3_modal" class="modal fade">
    <div class="modal-dialog" style = "width: 300px;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title">Question</h6>
            </div>
            <div class="modal-body">
                <div>
                    <p id="step3_body"></p>
                </div>
                <div class="radio" >
                    <label><input type="radio" class="styled" id="step3_yes" name="step3" value="1" checked>Yes</label>
                </div>
                <div class="radio" >
                    <label><input type="radio" class="styled" id="step3_no" name="step3" value="2">No</label>
                </div>
            </div>
            <div class="modal-footer">
                <button id = "step3_cancel" type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="step3_next()">Next</button>
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->


<script type="text/javascript">
    var quality = false;
    var food = false;
    var environment = false;
    var question = false;
    var step1 = false;
 function plan_next(){
     quality = $("#quality").prop("checked");
     food = $("#food").prop("checked");
     environment = $("#environment").prop("checked");
     if (quality == false && food == false && environment == false){
        return;
     }
     if (quality == true){
         $('#question_body').html("Is there a clear breach of requirements e.g. ISO 9001 Customer Legal and Regulatory Policy Objectives.");
     }else if (food == true){
         $('#question_no_div').attr("style","display:none");
         $('#question_body').html("Clear Evidence?");
     }else if (environment == true){
         $('#question_div').html('<p id = "question_body"></p>');
         $('#question_body').html("Is there a clear breach of requirements e.g.");
         $('#question_div').append("<ul><li>ISO Standard</li><li>Policy and Objectives</li><li>Compliance obligation</li><li>Customer</li></ul><p>?</p>");
     }
     $('#plan_cancel').click();
     question_show();
 }
    function question_show(){
        $('#question_modal').modal();
    }
    function question_next(){
        if ($("#question_yes").prop("checked") == true){
            question = true;
            if (quality == true){
                $('#step1_div').html('<p id = "step1_body"></p>');
                $('#step1_body').html("Does this present a serious risk to System Integrity, Product Quality or Customer Satisfaction?");
            }else if (food == true){
                $('#step1_div').html('<p id = "step1_body"></p>');
                $('#step1_body').html("Breach of REQUIREMENT?<br>Customer, ISO, legislation");
            }else if (environment == true){
                $('#step1_div').html('<p id = "step1_body"></p>');
                $('#step1_body').html("Does this present a serious risk to the environment, system integrity or compliance obligations?");
            }
        }else if($("#question_no").prop("checked") == true){
            question = false;
            if (quality == true){
                $('#step1_div').html('<p id = "step1_body"></p>');
                $('#step1_body').html("Investigate further, look at");
                $('#step1_div').append("<ul><li>System requirements</li><li>Process Performance</li><li>Interfaces</li><li>Resources, etc.</li></ul><p>Problem found?</p>");
            }else if (food == true){
                $('#question_cancel').click();
                return;
            }else if (environment == true){
                $('#step1_div').html('<p id = "step1_body"></p>');
                $('#step1_body').html("Investigate further, look at");
                $('#step1_div').append("<ul><li>System requirements</li><li>Process Performance</li><li>Interfaces</li><li>Resources, etc.</li></ul><p>Confirm the Problem found?</p>");
            }
        }else if ($("#question_notsure").prop("checked") == true){
            question = false;
            if (quality == true){
                $('#step1_div').html('<p id = "step1_body"></p>');
                $('#step1_body').html("Investigate further, look at");
                $('#step1_div').append("<ul><li>System requirements</li><li>Process Performance</li><li>Interfaces</li><li>Resources, etc.</li></ul><p>Problem found?</p>");
            }else if (food == true){
                $('#question_cancel').click();
                return;
            }else if (environment == true){
                $('#step1_div').html('<p id = "step1_body"></p>');
                $('#step1_body').html("Investigate further, look at");
                $('#step1_div').append("<ul><li>System requirements</li><li>Process Performance</li><li>Interfaces</li><li>Resources, etc.</li></ul><p>Confirm the Problem found?</p>");
            }
        }
        $('#question_cancel').click();
        $('#question_no_div').attr("style","display:inline");
        step1_show();
    }
    function step1_show(){
        $('#step1_modal').modal();
    }
    function step2_show(){
        $('#step2_modal').modal();
    }
    function step3_show(){
        $('#step3_modal').modal();
    }
    function step1_next(){
        if ($("#step1_yes").prop("checked") == true){
            $('#step1_cancel').click();
            if (quality == true){
                if (question == true){
                    create_grade("Major");
                }else{
                    plan_next();
                }
            }else if (food == true){
                $('#step1_cancel').click();
                $('#step2_body').html("DIRECT IMPACT:<br>food safety, certification integrity legality");
                step2_show();
            }else if (environment == true){
                if (question == true){
                    create_grade("Major");
                }else{
                    plan_next();
                }
            }
        }else{
            $('#step1_cancel').click();
            if (quality == true){
                if (question == true){
                    create_grade("Minor");
                }else{

                }
            }else if (food == true){
                plan_next();
            }else if (environment == true){
                if (question == true){
                    create_grade("Major");
                }else{

                }
            }
        }
    }
    function step2_next(){
        $('#step2_cancel').click();
        if ($("#step2_yes").prop("checked") == true){
            create_grade("Critical");
        }else{
            $('#step3_body').html("IMPACT on:<br>capability to achieve intended results?");
            step3_show();
        }
    }
    function step3_next(){
        $('#step3_cancel').click();
        if ($("#step3_yes").prop("checked") == true){
            create_grade("Major");
        }else{
            create_grade("Minor");
        }
    }
    function create_grade(grade){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/Consultant/create_grade",
            data:{ 'id' : logic_checklist_id, 'grade' : grade},
            success: function(data) {
                var dialog = bootbox.dialog({
                    message: "Success.",
                    size: 'small',
                    buttons: {
                        cancel: {
                            label: "Ok",
                            className: 'btn-danger',
                            callback: function() {
                                location.href = location.href;
                            }
                        }
                    }
                });
            }
        });
    }
</script>
