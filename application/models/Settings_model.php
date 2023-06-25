<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/core/BaseModel.php';

class Settings_model extends BaseModel {

    var $table_name = "default_setting";
    public function __constrcut(){
        print_r("This is the setting model");
        die();
    }
    function getEmailTemplate($where){
        $result = $this->db->get_where('settings_email_template', $where);
        $res=$result->row_array();
        return $res;
    }

   #--------------- Email for expired subscription------------------------
    function getEmailTemplate_1($where){
        $result = $this->db->get_where('subscription_email_template', $where);
        $res=$result->row_array();
        return $res;
    }

   #-----------------------------------------------------------------------

}
