<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/core/BaseModel.php';

class ConsultantModel extends BaseModel {
	var $table_name = "consultant";
	var $private_key = "consultant_id";
}
