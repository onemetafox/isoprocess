<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Authmodel extends CI_Model {
	/**  remove index php because it is not used  Dante */
	// public function index()
	// {
	// }
   public function admin_login($username)
	{
		$this->db->where('username',$username);
		$query=$this->db->get('admin');
	 	if ($query->num_rows()>0) {
			return $query->row();
		}else{
			return false;
		}
	}

	public function employee_login($username,$usertype)
	{
		$this->db->join('permision', 'permision.employee_id = employees.employee_id', 'left');
		$this->db->join('user_type', 'permision.type_id = user_type.utype_id', 'left');
		$this->db->where('employees.status',1);
		$this->db->where('employees.username',$username);
		$this->db->where('user_type.utype_name',$usertype);
		$query=$this->db->get('employees');

		if ($query->num_rows()>0) {
			return $query->row();
		}else{
			return false;
		}
	}

	public function consultant_login($username)
	{
        $this->db->where('username',$username);
	    $query=$this->db->get('consultant');
	 	if ($query->num_rows()>0) {
	 		return $query->row();
	 	}else{
	 		return false;
	 	}
	}

	///////////////////check last login IP///////////
	public function last_login_ip($userid,$ipaddress)
	{
        $this->db->where('user_id',$userid);
       /* $this->db->where('IP_address',$ipaddress);*/
        $this->db->order_by('id','desc');
        $this->db->limit(1);  
	    $query=$this->db->get('login_history');
	 	if ($query->num_rows()>0) {
	 		return $query->row();
	 	}else{
	 		return false;
	 	}
	}
	//////////////////////////END////////////////////

	public function consultant_register($data=NULL)
	{   
	 $query=$this->db->insert('consultant',$data);
	  return $this->db->insert_id();
	}

    public function consultant_purchase($data=NULL,$consultant_id=NULL)
	{   
		$this->db->where('consultant_id',$consultant_id);
		$query=$this->db->get('purchase_plan');
		if ($query->num_rows()>0) {
			$this->db->where('consultant_id',$consultant_id);
			$this->db->update('purchase_plan',$data);
			return 1;
		}else{
			$this->db->insert('purchase_plan',$data);
			return $this->db->insert_id();
		}
	}

	public function consultant_ot_login($username,$password)
	{   
		$this->db->where('password',$password);
		$this->db->where('username',$username);
		$query=$this->db->get('employees');
		if ($query->num_rows()>0) {
			return $query->row();
		}else{
			return false;
		}
	}

	public function admin_email($email)
	{   
		$this->db->where('email',$email);
		$query=$this->db->get('admin');
		if ($query->num_rows()>0) {
			return $query->row();
		}else{
			return false;
		}
	}

	public function employee_email($email)
	{   
		$this->db->where('employee_email',$email);
		$query=$this->db->get('employees');
		if ($query->num_rows()>0) {
			return $query->row();
		}else{
			return false;
		}

	}

	public function consultant_email($email)
	{   
		$this->db->where('email',$email);
		$query=$this->db->get('consultant');
		if ($query->num_rows()>0) {
			return $query->row();
		}else{
			return false;
		}

	}
}

