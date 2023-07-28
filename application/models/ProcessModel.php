<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/core/BaseModel.php';

class ProcessModel extends BaseModel {
    
    var $table_name = "process_list";
    var $private_key = "process_id";
}

