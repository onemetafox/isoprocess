<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/core/BaseModel.php';

class AuditLogModel extends BaseModel {
    
    var $table_name = "audit_log_list";
    var $private_key = "log_id";
}

