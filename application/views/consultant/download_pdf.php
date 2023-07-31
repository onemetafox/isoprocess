
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
                    <tr>
                        <td>
                            <p style="font-size: 25px;">Audit Dates:    <?=$audits->starttime_type?> - <?=$audits->endtime_type?></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td style="height: 850px;text-align: center;">
                <table style="text-align: center;">
                    <tr>
                        <td style="text-align: left;width: 50%;padding-left: 5px;border-width:1px 1px 1px 1px;">
                            <br>
                            Audit Report: <?=$audits->type_of_audit?> Internal Audit
                            <br>
                            Company Limited
                        </td>
                        <td style="text-align: left;width: 50%;padding-left: 5px;border-width:1px 1px 1px 1px;">
                            <br>
                            Audit: Report No. <?=$audits->log_id?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;width: 100%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                            <table>
                                <tr>
                                    <td>
                                        <br>
                                        Audited Facility:      Company Limited
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br>
                                        Address:                 <?=$consultant->address?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br>
                                        Audit Team:&nbsp;&nbsp;&nbsp;<?=$audit_team?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br>
                                        Date Of Audit:&nbsp;&nbsp;&nbsp;<?=$audits->starttime_type?> - <?=$audits->endtime_type?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br>
                                        Scope Of Audit:&nbsp;&nbsp;&nbsp;<?=$audit_brief->scope?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br>
                                        Contact Person:&nbsp;&nbsp;&nbsp;<?=$consultant->contact_no?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="text-decoration: underline;font-size: 18px;">Summary of Non-Conformities Identified in The Internal Audit Process</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    Process&nbsp;#
                                                </td>
                                                <td style="text-align: left;width: 70%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    Process Names
                                                </td>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    Non Conformances
                                                </td>
                                            </tr>
                                            <?php $temp_index = 0; ?>
                                            <?php $temp_non = 0; ?>
                                            <?php foreach ($process_non_list as $item) { ?>
                                                <?php $temp_non = $temp_non + $item->cnt; ?>
                                                <?php $temp_index++; ?>
                                                <tr>
                                                    <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                        <?=$temp_index?>
                                                    </td>
                                                    <td style="text-align: left;width: 70%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                        <?=$item->process_name?>
                                                    </td>
                                                    <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                        <?=$item->cnt?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                </td>
                                                <td style="text-align: left;width: 70%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    TOTAL
                                                </td>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    <?=$temp_non?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>


                                </tr>

                                <tr>
                                    <td>
                                        <p style="text-decoration: underline;font-size: 18px;">Summary of Conformities Identified in The Internal Audit Process</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    Process&nbsp;#
                                                </td>
                                                <td style="text-align: left;width: 70%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    Process Names
                                                </td>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    Conformances
                                                </td>
                                            </tr>
                                            <?php $temp_index = 0; ?>
                                            <?php $temp_conf = 0; ?>
                                            <?php foreach ($process_conf_list as $item) { ?>
                                                <?php $temp_conf = $temp_conf + $item->cnt; ?>
                                                <?php $temp_index++; ?>
                                                <tr>
                                                    <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                        <?=$temp_index?>
                                                    </td>
                                                    <td style="text-align: left;width: 70%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                        <?=$item->process_name?>
                                                    </td>
                                                    <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                        <?=$item->cnt?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                </td>
                                                <td style="text-align: left;width: 70%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    TOTAL
                                                </td>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    <?=$temp_conf?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>


                                </tr>

                                <tr>
                                    <td>
                                        <p style="text-decoration: underline;font-size: 18px;">Summary of Opportunities for Improvement Identified in The Internal Audit Process</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    Process&nbsp;#
                                                </td>
                                                <td style="text-align: left;width: 70%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    Process Names
                                                </td>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    Conformances
                                                </td>
                                            </tr>
                                            <?php $temp_index = 0; ?>
                                            <?php $temp_opp = 0; ?>
                                            <?php foreach ($process_opp_list as $item) { ?>
                                                <?php $temp_opp = $temp_opp + $item->cnt; ?>
                                                <?php $temp_index++; ?>
                                                <tr>
                                                    <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                        <?=$temp_index?>
                                                    </td>
                                                    <td style="text-align: left;width: 70%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                        <?=$item->process_name?>
                                                    </td>
                                                    <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                        <?=$item->cnt?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                </td>
                                                <td style="text-align: left;width: 70%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    TOTAL
                                                </td>
                                                <td style="text-align: left;width: 15%;border-width:1px 1px 1px 1px;font-size: 13px;">
                                                    <?=$temp_opp?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>


                                </tr>


                            </table>
                        </td>
                    </tr>

                    <tr style="float: left; text-align:left;">
                        <td style="float: left; text-align:left;">
                            <br>
                           <?=$audit_brief->summary?>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                <p style="font-size: 25px;">AUDIT BRIEF</p>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td style="height: 780px;text-align: center;">
                <table style="text-align: center;">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                        Audit Ref
                                    </td>
                                    <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                        <?=$audit_brief->refer_num?>
                                    </td>
                                    <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                        Audit of:
                                    </td>
                                    <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                        <?=$audits->type_of_audit?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;width: 25%;border-width: 0px 1px 1px 1px;padding-left: 5px;">
                                        Date Scheduled
                                    </td>
                                    <td style="text-align: left;width: 25%;border-width: 0px 1px 1px 1px;padding-left: 5px;">
                                        <?=$audits->starttime_type?> - <?=$audits->endtime_type?>
                                    </td>
                                    <td style="text-align: left;width: 25%;border-width: 0px 1px 1px 1px;padding-left: 5px;">
                                        Locations
                                    </td>
                                    <td style="text-align: left;width: 25%;border-width: 0px 1px 1px 1px;padding-left: 5px;">
                                        <?=$audit_brief->locations?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td style="text-align: left;vertical-align: top;width: 33%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                        Audit Team:
                                        <br>
                                        <?=$audit_team?>
                                        <br>
                                        <br>
                                        Audit Team Leader:
                                        <br>
                                        <?=$audits->employee_name?>
                                    </td>
                                    <td style="text-align: left;width: 33%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                        Process Owner(s):
                                    </td>
                                    <td style="text-align: left;width: 34%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                        <?=$process_owners?>
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
                                        Purpose:
                                        <br>
                                        <br>
                                        <?=$audit_brief->purpose?>
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
                                        Background and Context:
                                        <br>
                                        <br>
                                        <?=$audit_brief->context?>
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
                                        Scope:
                                        <br>
                                        <br>
                                        <?=$audit_brief->scope?>
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
                                        Criteria:
                                        <br>
                                        <br>
                                        <?=$audit_brief->criteria?>
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
                                        Objectives:
                                        <br>
                                        <br>
                                        <?=$audit_brief->objectives?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                <p style="font-size: 25px;">Company Limited Audit Plan</p>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td style="height: 780px;text-align: center;">
                <table style="text-align: center;">
                    <tr>
                        <td style="text-align: left;width: 100%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                            Opening Meeting:
                            <br>
                            <br>
                            Who: <?=$open_employees?>
                            <br>
                            <br>
                            When: <?=$audit_plan->opentime?>
                            <br>
                            <br>
                            Where: <?=$audit_plan->open_where?>
                            <br>
                            <br>
                            What to cover: <?=$audit_plan->open_cover?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;width: 100%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                            The Audit
                            <br>
                            <?=$audit_plan->schedule?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;width: 100%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                            Closing Meeting:
                            <br>
                            <br>
                            Who: <?php if ($close_employees == ""): ?>N/A<?php else:?><?=$close_employees?><?php endif; ?>
                            <br>
                            <br>
                            When: <?php if ($audit_plan->closetime == ""): ?>To be determined<?php else:?><?=$audit_plan->closetime?><?php endif; ?>
                            <br>
                            <br>
                            Where: <?=$audit_plan->close_where?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                <p style="font-size: 25px;">AUDIT SCHEDULE</p>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td style="height: 780px;text-align: center;">
                <table style="text-align: center;">
                    <tr>
                        <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                            Processes
                        </td>
                        <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                            Auditor
                        </td>
                        <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                            Auditee
                        </td>
                        <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                            Date/Time
                        </td>
                    </tr>
                    <?php foreach ($process_list as $item) { ?>
                        <tr>
                            <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                <?=$item->process_name?>
                            </td>
                            <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                <?=$item->auditor_name?>
                            </td>
                            <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                <?=$item->auditee_name?>
                            </td>
                            <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                <?=$item->starttime_type?> - F<?=$item->endtime_type?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>

        <?php
            $num1 = 0;
            $num2 = 1;
        foreach($process_check_list as $check) {
            $conformity_list = $this->db->query("SELECT * FROM checklist WHERE `process_id`='$check->id' AND status = 'NO ISSUE'")->result();
            $opportunity_list = $this->db->query("SELECT * FROM checklist WHERE `process_id`='$check->id' AND status = 'Opportunities'")->result();
            $auditor = $this->db->query("SELECT * FROM employees WHERE `employee_id`='$check->auditor'")->row();
            $auditor_name = ($auditor == null) ? "" : $auditor->employee_name;
            $auditee = $this->db->query("SELECT * FROM employees WHERE `employee_id`='$check->sme'")->row();
            $auditee_name = ($auditee == null) ? "" : $auditee->employee_name;
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
                                                Audit Criteria: <?=$conform_list->criteria_id?>
                                            </td>
                                            <td style="text-align: left;width: 25%;border-width:1px 1px 1px 1px;padding-left: 5px;">
                                                Auditees: <?=$auditee_name?>
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
            $non_conformity_list = $this->db->query("SELECT * FROM checklist WHERE `process_id`='$check->id' AND status = 'Non-Conformity Table'")->result();
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
                                                Audit Criteria: <?=$non_conform_list->criteria_id?>
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
                                                    Audit Criteria: <?=$opportunity->criteria_id?>
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
