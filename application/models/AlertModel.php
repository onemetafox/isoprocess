<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/core/BaseModel.php';

class AlertModel extends BaseModel {
	var $table_name = "alerts";
	var $private_key = "id";
}
