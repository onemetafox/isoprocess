<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BaseModel extends CI_Model {
    var $table_name = '';
    var $private_key = "id";

    public function getOne($id){
        $this->db->where($this->private_key, $id);
        $data = $this->db->get($this->table_name)->row();
        return $data;
    }
}

