<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/core/BaseModel.php';

class AuditBriefModel extends BaseModel {
    
    var $table_name = "audit_brief";
    var $private_key = "brief_id";
}

