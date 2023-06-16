<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->library('session');
	}

	public function index()
	{
		$data['title']="Login";
		   $admin=$this->session->userdata('admin_id');
		   $employee=$this->session->userdata('employee_id');
		   $consultant=$this->session->userdata('consultant_id');
		      if ($admin || $employee || $consultant) {
		      	     if(isset($admin)) {
				 	   redirect('Welcome/admindashboard');
					 }
					  if (isset($employee)) {
					 	redirect('Welcome/employeedashboard');
					 }
					  if (isset($consultant)) {
					 	redirect('Welcome/consultantdashboard');
					 }
		      }else{
		          $this->load->view('login',$data);
		      }
	}
}
