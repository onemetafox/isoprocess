<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/core/BaseModel.php';

class SelectProcessModel extends BaseModel {
    
    var $table_name = "select_process";
    var $private_key = "id";
}

