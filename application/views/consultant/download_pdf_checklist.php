
<div class="container page-content">
    <table style="margin-left: 20px;width: 600px;">
        <tr style="text-align: center;">
            <td style="border: 10px double;padding-left: 40px;padding-right: 40px;height: 850px;text-align: center;">
                <table style="text-align: center;">
                    <tr>
                        <td style="height: 150px;">
                        </td>
                    </tr>
                    <tr style="height: 300px;">
                        <td>
                            <p style="font-size: 40px;"><?=$consultant_name?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 30px;"><?=$audits->type_of_audit?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 25px;">Internal Audit Report</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 100px;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <?php
            $num1 = 0;
            $num2 = 1;
        foreach($process_check_list as $check) {
            $conformity_list = $this->db->query("SELECT * FROM checklist WHERE `process_id`='$check->id' AND status = 'NO ISSUE'")->result();
            $opportunity_list = $this->db->query("SELECT * FROM checklist WHERE `process_id`='$check->id' AND status = 'Opportunities'")->result();
            $auditee_array = explode (",", $conformity_list->auditees); 
            $auditor = $this->db->query("SELECT * FROM employees WHERE `employee_id`='$check->auditor'")->row();
            $auditor_name = ($auditor == null) ? "" : $auditor->employee_name;
            // $auditee = $this->db->query("SELECT * FROM employees WHERE `employee_id`='$check->sme'")->row();
            // $auditee_name = ($auditee == null) ? "" : $auditee->employee_name;
            $owner = $this->db->query("SELECT * FROM employees WHERE `employee_id`='$check->process_owner'")->row();
            $owner_name = ($owner == null) ? "" : $owner->employee_name;
            if($conformity_list != null) {
                foreach($conformity_list as $conform_list) {
        ?>
                <tr>
                    <td style="text-align: center">
                        <p style="font-size: 18px;">CONFORMITY REPORTS – <?=strtoupper($check->process_name)?></p>
                    </td>
                </tr>
                <tr style="text-align: center;">
                    <td style="text-align: center;">
                        <table style="text-align: center;">
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Audit of: <?=$check->process_name?>
                                            </td>
                                            <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Auditor: <?=$auditor_name?>
                                            </td>
                                            <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Audit Criteria: <?=$conform_list->criteria_id?>,<?=$conform_list->criteria_id2?>,<?=$conform_list->criteria_id3?>,<?=$conform_list->criteria_id4?>
                                            </td>
                                            <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Auditees: <?php 
                                                    foreach( $auditee_array as $auditee_id){
                                                        $auditee = $this->employee->getOne($auditee_id);
                                                        echo($auditee->name . ", ");
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: left;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Audit Evidence:
                                                <br>
                                                <br>
                                                <?=$conform_list->evidence?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: left;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Evaluation:
                                                <br>
                                                <br>
                                                <?=$conform_list->note?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: left;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Effectiveness:
                                                <br>
                                                <br>
                                                <?=$conform_list->effectiveness?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
        <?php
                }
            }
            $non_conformity_list = $this->db->query("SELECT * FROM checklist WHERE `process_id`='$check->id' AND status = 'NON COMFORMITY'")->result();
            $num3 = 1;
            if($non_conformity_list != null) {
                foreach($non_conformity_list as $non_conform_list) {
        ?>
                <tr><td style="height: 10px;"></td></tr>
                <tr style="text-align: center;">
                    <td style="text-align: center;">
                        <table style="text-align: center;">
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: center;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                <label style="font-size: 18px;">NONCONFORMITY REPORT</label>
                                                <br>
                                                <br>
                                                <label>Incident Identification Number: <?=sprintf("%06d", $num1)?>.<?=sprintf("%05d", $num2)?></label>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: left;width: 30%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Non-Conformity Report #: <?=$num3?>
                                            </td>
                                            <td style="text-align: left;width: 35%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Auditor (s): <?=$auditor_name?>
                                            </td>
                                            <td style="text-align: left;width: 35%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Date: <?=date("F j, Y", strtotime($check->end_time))?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;width: 30%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Audit of : <?=$check->process_name?>
                                            </td>
                                            <td style="text-align: left;width: 35%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Audit Criteria: <?=$non_conform_list->criteria_id?>,<?=$non_conform_list->criteria_id2?>,<?=$non_conform_list->criteria_id3?>,<?=$non_conform_list->criteria_id4?>
                                            </td>
                                            <td style="text-align: left;width: 35%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: left;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Statement of Nonconformity:
                                                <br>
                                                <br>
                                                <?=$non_conform_list->note?>
                                                <br>
                                                <br>
                                                <br>
                                                Responsible Party: <?=$owner_name?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px; width: 50%;">
                                                Auditor Signature:
                                                <br>
                                                <?php if($img_name != ""):?>
                                                    <img id = "sign_img" src="<?php echo base_url().'/uploads/sign/'.$img_name;?>" style=""/>
                                                <?php endif;?>
                                                <br>
                                            </td>
                                            <td style="text-align: left;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px; width: 50%;">
                                                Signature:
                                                <br>
                                                <br>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
        <?php
                    $num2 ++;
                    $num3 ++;
                    if($num2 > 99999) {
                        $num1 ++;
                        $num2 = 1;
                    }
                }
            }
            $num4 = 1;
            $num5 = 0;
            $num6 = 1;
            if($opportunity_list != null){
                foreach($opportunity_list as $opportunity){
            ?>
                    <tr><td style="height: 10px;"></td></tr>
                    <tr style="text-align: center;">
                        <td style="text-align: center;">
                            <table style="text-align: center;">
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="text-align: center;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                    <label style="font-size: 18px;">OPPORTUNITY REPORT</label>
                                                    <br>
                                                    <br>
                                                    <label>Incident Identification Number: <?=sprintf("%06d", $num5)?>.<?=sprintf("%05d", $num6)?></label>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="text-align: left;width: 30%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                    Opportunity Report #: <?=$num4?>
                                                </td>
                                                <td style="text-align: left;width: 35%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                    Auditor (s): <?=$auditor_name?>
                                                </td>
                                                <td style="text-align: left;width: 35%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                    Date: <?=date("F j, Y", strtotime($check->end_time))?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;width: 30%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                    Audit of : <?=$check->process_name?>
                                                </td>
                                                <td style="text-align: left;width: 35%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                    Audit Criteria: <?=$opportunity->criteria_id?>,<?=$opportunity->criteria_id2?>,<?=$opportunity->criteria_id3?>,<?=$opportunity->criteria_id4?>
                                                </td>
                                                <td style="text-align: left;width: 35%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="text-align: left;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                    Statement of Opportunity:
                                                    <br>
                                                    <br>
                                                    <?=$opportunity->note?>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    Responsible Party: <?=$owner_name?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px; width: 50%;">
                                                    Auditor Signature:
                                                    <br>
                                                    <?php if($img_name != ""):?>
                                                        <img id = "sign_img" src="<?php echo base_url().'/uploads/sign/'.$img_name;?>" style=""/>
                                                    <?php endif;?>
                                                    <br>
                                                </td>
                                                <td style="text-align: left;vertical-align: top;border-width:1px 1px 1px 1px;padding-left: 5px; width: 50%;">
                                                    Signature:
                                                    <br>
                                                    <br>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
        <?php
                    $num6 ++;
                    $num4 ++;
                    if($num6 > 99999) {
                        $num5 ++;
                        $num6 = 1;
                    }
                }
            }
        ?>
        <?php } ?>
    </table>
</div>
