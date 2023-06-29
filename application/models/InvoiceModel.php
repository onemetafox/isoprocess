<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/core/BaseModel.php';

class InvoiceModel extends BaseModel {
    
    var $table_name = "invoice";

    public function where($filters){
        if(isset($filters['from'])){
            $this->db->where('create_date >= ' . $filters['from']);
            unset($filters['from']);
        }
        if(isset($filters['to'])){
            $this->db->where('create_date <= ' . $filters['from']);
            unset($filters['to']);
        }
        return parent::where($filters);
    }

    public function getAll($filters = null, $order = null, $dir = null){
        $this->db->select("consultant.user_type company_name, invoice_item.amount, invoice_item.description");
        $this->db->join("consultant", "consultant.consultant_id = invoice.admin_id");
        $this->db->join("invoice_item", "invoice.id = invoice_item.invoice_id");
        return parent::getAll($filters = null, $order = null, $dir = null);
    }
}

