<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/core/BaseModel.php';

class PlanModel extends BaseModel {
	var $table_name = "plan";
	var $private_key = "plan_id";

	public function get_plan($id = 0)
	{

		$this->db->where('plan_id', $id);
		$plan = $this->db->get('plan')->row();
		if($plan != null) 	return $plan;
		else					return false;

	}

}
