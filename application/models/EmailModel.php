<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/core/BaseModel.php';

class EmailModel extends BaseModel {
	var $table_name = "settings_email_template";
}
