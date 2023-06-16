<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->library('session');
        $this->settings = $this->db->query("select * from `default_setting` where `id`='1'")->row();
	}
	public function index()
	{
        $data['menu_title'] = 'home';
        $this->load->view('home', $data);
	}

    public function login() {
        $superadmin=$this->session->userdata('superadmin_id');
        $admin=$this->session->userdata('admin_id');
        $employee=$this->session->userdata('employee_id');
        $consultant=$this->session->userdata('consultant_id');
	    if ($superadmin || $admin || $employee || $consultant) {
			if(isset($superadmin)) {
				redirect('Welcome/superadmindashboard');
			}
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
            $data['menu_title'] = 'login';
            $data['otp_status'] = $this->settings->otp_verification ? true:false;
            $this->load->view('login', $data);
	    }
	}
	public function aboutus(){
		$data['menu_title'] = 'about';
		$this->load->view('about-us',$data);
	}
	public function register()
	{
		$data['menu_title'] = 'signup';
		$data['title']="Register";
		$this->load->view('sign_up',$data);
	}
	public function admindashboard()
	{

		$admin_id=$this->session->userdata('admin_id');
		if ($admin_id!='') {
	
		redirect('Admin/consultant_list');
		}else{
    		redirect('Welcome');
    	}
	}
	public function employeedashboard()
	{
		$user_type  = $this->session->userdata('user_type');
		$data['user_type'] = $user_type;
        $employee_id=$this->session->userdata('employee_id');
        $consultant_id1=$this->session->userdata('consultant_id');
		$open_status = 1;
		$closed_status = 0;
		if(!isset($employee_id)){
			$this->logout();
		}
		if ($employee_id!='') {
			$data['title'] = 'Home';
    		$data['menu_title']="Home";
            $date=date('Y-m-d');
		    $this->db->where('consultant_id',$consultant_id1);
         	$data['comp']=$this->db->get('consultant')->row();

            $date1=explode('-', $data['comp']->expired);
            $date2=explode('-', $date);
            $ndate1=$date1[0].$date1[1].$date1[2];
            $ndate2=$date2[0].$date2[1].$date2[2];
             if ($ndate1 > $ndate2) {

                 $this->load->view('employee/dashboard',$data);
             }else{
				 $data['menu_title']="login";

				 $this->session->set_flashdata('message', 'You company account has expired. please contact consultant.');
				 $this->load->view('login',$data);
             }
    	}else{
    		redirect('Welcome');
    	}
	}

	public function consultantdashboard()
	{
		$com_status=$this->session->userdata('com_status');
	 	$consultant_id=$this->session->userdata('consultant_id');
     	$date=date('Y-m-d');

	  	if(!isset($consultant_id)){
		  	$this->logout();
	  	}
     	if ($consultant_id) {
		 	$data['title'] = 'Home';
		 	$data['menu_title'] = 'Home';

		 	$this->db->where('consultant_id',$consultant_id);
         	$data['comp']=$this->db->get('consultant')->row();
            $date1=explode('-', $data['comp']->expired);
            $date2=explode('-', $date);

            if(sizeof($date1) > 2 && sizeof($date2) > 2){
            $ndate1=$date1[0].$date1[1].$date1[2];
            $ndate2=$date2[0].$date2[1].$date2[2];

         	if ($com_status=='1' && $consultant_id!='' && $ndate1 > $ndate2) {
				$this->load->view('consultant/dashboard',$data);
			} else {
				$data = array();
				$data['title'] = 'Next';
				$data['menu_title'] = 'Home';
				$chk      = @$this->db->query("SELECT COUNT(employee_id) as tot_pro FROM `employees` WHERE `consultant_id`='$consultant_id'")->row()->tot_pro;

				$this->db->order_by('no_of_user', 'ASC');
				$this->db->where('no_of_user >= ', $chk);
				$this->db->where('is_trial','0');
				$query        = $this->db->get('plan');
				$data['plan'] = $query->result();

				$this->db->where('is_trial !=',1);
				$this->db->where('term_type',0);
				$query = $this->db->get('plan');
				$data['month_plan'] = $query->result();
				$this->db->where('is_trial !=',1);
				$this->db->where('term_type',1);
				$query = $this->db->get('plan');
				$data['year_plan'] = $query->result();
				
				$this->load->view('Register/register_payment_plans', $data);
			}
		}
		else {
				$this->load->view('consultant/dashboard',$data);
		}
         }else{
         	redirect('Welcome');
         }
		
		
	}
	public function logout()
	{
		$usertype = $this->session->userdata('user_type');
		if($usertype == 'Consultant'){
			if($this->session->userdata('consultant_id') != null && !empty($this->session->userdata('consultant_id'))){
				$this->db->where('consultant_id', $this->session->userdata('consultant_id'));
				$update = $this->db->update('consultant', array('isVerifyOTP' => 0));			
			}
		}
		if($usertype == 'admin'){
			if($this->session->userdata('admin_id') != null && !empty($this->session->userdata('admin_id'))){
				$this->db->where('id', $this->session->userdata('admin_id'));
				$update = $this->db->update('admin', array('isVerifyOTP' => 0));			
			}
		}
		if($usertype == 'Lead Auditor' || $usertype == 'Auditor' || $usertype == 'Process Owner' || $usertype == 'Auditee'){
			if($this->session->userdata('employee_id') != null && !empty($this->session->userdata('employee_id'))){
				$this->db->where('employee_id', $this->session->userdata('employee_id'));
				$update = $this->db->update('employees', array('isVerifyOTP' => 0));			
			}
		}	
		$this->session->sess_destroy();
		redirect('Welcome/login');
	}
}
