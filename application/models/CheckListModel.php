<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CheckListModel extends CI_Model {
	public function getOne($id){
        $this->db->where("id", $id);
        return $this->db->get("checklist")->row();
    }
}

