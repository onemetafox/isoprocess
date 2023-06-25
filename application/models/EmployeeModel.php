<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeModel extends CI_Model {
	public function getSMES($consultant_id){
        $sql = "SELECT *, GROUP_CONCAT(t.utype_name) type_name
                FROM employees e
                LEFT JOIN permision p ON e.employee_id = p.employee_id
                LEFT JOIN user_type t ON p.type_id = t.utype_id
                WHERE e.consultant_id = " . $consultant_id . "
                GROUP BY e.employee_id";
        return $this->db->query($sql)->result();
    }

    public function getOne($id){
        $this->db->where("id", $id);
        return $this->db->get("checklist")->row();
    }
}

