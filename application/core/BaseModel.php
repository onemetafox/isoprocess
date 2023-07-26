<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BaseModel extends CI_Model {
    var $table_name = '';
    var $private_key = "id";

    public function where($filters){

        foreach($filters as $key => $value){
            $this->db->where($key, $value);
        }
    }
    public function selectOne ($filters){
        if($filters)
            $this->where($filters);
        $this->db->select($this->table_name.".*");
        $data = $this->db->get($this->table_name)->row();
        return $data;
    }
    public function getOne($id){
        $this->db->where($this->private_key, $id);
        $data = $this->db->get($this->table_name)->row();
        return $data;
    }
    
    public function deleteOne($id){
        $this->db->where($this->private_key, $id);
        return $this->db->delete('plan');
    }

    public function updateOne($id, $data){
        $this->db->where($this->private_key, $id);
        return $this->db->update($this->table_name, $data);
    }

    public function getAll($filters = NULL, $order = NULL, $direction = NULL){
        if($filters)
            $this->where($filters);
        if($order)
            $this->db->order_by($order, "DESC");
        if($direction)
            $this->db->order_by($order, $direction);
        $this->db->select($this->table_name.".*");
        $data = $this->db->get($this->table_name)->result();
        return $data;
    }

    public function save($data){
        if(isset($data[$this->private_key])){
            return $this->update($data);
        }else{
            return $this->insert($data);
        }
    }

    public function insert($data){
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    public function update($data){
        $this->db->where($this->private_key, $data[$this->private_key]);
        $this->db->update($this->table_name, $data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE; 
    }
    public function updateWithFilter($data, $filters){
        if($filters)
            $this->where($filters);
        $this->db->update($this->table_name, $data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE; 
    }
}

