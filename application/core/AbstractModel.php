<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AbstractModel extends CI_Model {
    var $table_name = '';

    public function getOne($id){
        $this->db->where("id", $id);
        return $this->db->get($table_name)->row();
    }
}

