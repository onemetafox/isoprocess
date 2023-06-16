<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH. '/libraries/BaseController.php';
class Auth extends BaseController //CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
    }
	
    public function login($usertype=NULL, $username=NULL, $password=NULL){        
        $this->load->model('Authmodel');        
        $data['title'] = 'Login';
		$validationrule = 1;
		
		if($usertype == ''){
			$this->load->library('form_validation');
        	$this->form_validation->set_rules('username','Username','required');
        	$this->form_validation->set_rules('password','Password','required');
			
			$validationrule = $this->form_validation->run();
		}
		
        if($validationrule == false){
            $data['menu_title'] = 'login';
            $this->load->view('login',$data);
        }else{
			
			if($usertype == ''){
				$usertype = $this->input->post('usertype');
				$username = $this->input->post('username');
				$password = $this->input->post('password');
			}
			
						
            $data['menu_title'] = 'home';
            if(isset($usertype)){
                if($usertype == 'admin'){
                    $data = $this->Authmodel->admin_login($username);
                    if($data && verifyHashedPassword($password, $data->password)){
                        $data1 = array('admin_id' => $data->id,'user_type' => $usertype,'username' => $data->username,'is_password_updated' => $data->isPasswordUptd);
						if($data->isVerifyOTP == 0){                            
							$this->verifyOtpChk($data,$usertype);
                        }
						
                        $this->session->set_userdata($data1);
                        redirect('Welcome/admindashboard');
                    }else{
                        $this->session->set_flashdata('message','Invalid credentials');
                        redirect($_SERVER['HTTP_REFERER']);
                        exit();
                    }
                }
                if($usertype == 'Lead Auditor' || $usertype == 'Auditor' || $usertype == 'Process Owner' || $usertype == 'Auditee'){
                    $data = $this->Authmodel->employee_login($username,$usertype);
					 
                    if($data && verifyHashedPassword($password, $data->password)){
                        $data1 = array('employee_id' => $data->employee_id,'username' => $data->username,'consultant_id' => $data->consultant_id,'user_type' => $usertype,'com_status' => $data->status,'is_password_updated' => $data->isPasswordUptd,'user_type' => $usertype);
						
						if($data->isVerifyOTP == 0){                            
							$this->verifyOtpChk($data,$usertype);
                        }
						
                        $this->session->set_userdata($data1);
                        redirect('Welcome/employeedashboard');
                    }else{
                        $this->session->set_flashdata('message','Invalid credentials');
                        redirect('Welcome');
                    }
                }

                if($usertype == 'Consultant'){
                    $data = $this->Authmodel->consultant_login($username);
                    // $data1n=$this->Authmodel->consultant_ot_login($username,$password);
                    $data1n = '';
                    if($data || $data1n){
                        if($data && verifyHashedPassword($password, $data->password)){
                            if($data->is_active == 0){
                                $this->session->set_flashdata('message','Please verifiy your email to access the system');
                                redirect($_SERVER['HTTP_REFERER']);
                                exit();
                            }
                            $data1 = array('consultant_id' => $data->consultant_id,'username' => $data->username,'user_type' => '','com_status' => $data->status,'plan_type' => $data->plan_type,'is_password_updated' => $data->isPasswordUptd,'user_type' => $usertype);
                        }else{
                            $data1 = array('consultant_id' => $data1n->consultant_id,'username' => $data1n->username,'com_status' => $data1n->status,'user_type' => '','employee_id' => $data1n->employee_id,'is_password_updated' => $data->isPasswordUptd,'user_type' => $usertype);
                        }

                        if($data->is2FAEnabled == 1){
                            $this->session->set_userdata('temp_user',$data);
                            redirect('auth/securityAuth');
                        }
						
						if($data->isVerifyOTP == 0){                            
							$this->verifyOtpChk($data,$usertype);
                        }
						if($data->isVerifyOTP == 1){
							$data1['menu_title'] = 'home';
							$this->session->set_userdata($data1);
							redirect('Welcome/consultantdashboard');
						}
                    }else{
                        $this->session->set_flashdata('message','Invalid credentials');
                        redirect('Welcome');
                    }
                }
            }else{
                redirect('Welcome');
            }
        }
    }

    /*public function password_check($str){
	   if(preg_match('#[0-9]#',$str) && preg_match('#[a-z]#',$str) && preg_match('#[A-Z]#',$str)){
	     return TRUE;
	   }
	   	 $this->form_validation->set_message('password_check','Password must contain number, length limit(8),letter(small & uppercase)');
	   
	   return FALSE;
	}*/

    /**
     * Validate the password
     *
     * @param string $password
     *
     * @return bool
     */
    public function password_check($password = ''){
        $password = trim($password);
        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
        if(empty($password)){
            $this->form_validation->set_message('password_check','The {field} field is required.');
            return false;
        }
        if(preg_match_all($regex_lowercase,$password) < 1){
            $this->form_validation->set_message('password_check','The {field} field must be at least one lowercase letter.');
            return false;
        }
        if(preg_match_all($regex_uppercase,$password) < 1){
            $this->form_validation->set_message('password_check','The {field} field must be at least one uppercase letter.');
            return false;
        }
        if(preg_match_all($regex_number,$password) < 1){
            $this->form_validation->set_message('password_check','The {field} field must have at least one number.');
            return false;
        }
        if(preg_match_all($regex_special,$password) < 1){
            $this->form_validation->set_message('password_check','The {field} field must have at least one special character.'.
                    ' '.
                    htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
            return false;
        }
        if(strlen($password) < 8){
            $this->form_validation->set_message('password_check','The {field} field must be at least 8 characters in length.');
            return false;
        }
        if(strlen($password) > 32){
            $this->form_validation->set_message('password_check','The {field} field cannot exceed 32 characters in length.');
            return false;
        }
        return true;
    }
	
    public function register(){
        $this->load->model('Authmodel');
        $this->load->library('form_validation');
        $data['title'] = 'Register Now';
        $this->form_validation->set_rules('consultant_name','consultant name','required');
        $this->form_validation->set_rules('username','Username','required|is_unique[consultant.username]');
        $this->form_validation->set_rules('password','Password','required|min_length[8]|callback_password_check');
        $this->form_validation->set_rules('repassword','Password','required|min_length[8]|callback_password_check');
        $this->form_validation->set_rules('repassword','Password Confirmation','required|matches[password]');
        $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[consultant.email]');
        $this->form_validation->set_rules('phone', 'Phone', 'required|is_unique[consultant.phone]');
        $data['menu_title'] = 'signup';
        /*=-=- check user mobile number valid start =-=-*/
        $phone_response = $this->phone_rk->checkPhoneNumber($this->input->post('phone'));
        /*=-=- check user mobile number valid end =-=-*/
        if($this->form_validation->run() == false || !$phone_response['success']) {
            if (!$phone_response['success']){
                $this->session->set_flashdata('message', $phone_response['message']);
            }
            $this->load->view('sign_up',$data);
        }else{
            $date       = date('Y-m-d');
            $email      = $this->input->post('email');
            $username   = $this->input->post('username');
            $phone      = $this->input->post('phone');
            $password   = getHashedPassword($this->input->post('password'));
            $consultant_name = $this->input->post('consultant_name');

            if(stristr($email, "<script>") != false || stristr($username, "<script>") != false || stristr($consultant_name, "<script>") != false){
                $data['menu_title'] = 'signup';
                $this->load->view('sign_up',$data);
                return;
            }

            $activation_code = $this->serialkey();

            $data_array = array('email' => $email,'username' => $username,'password' => $password, 'phone' => $phone, 'consultant_name' => $consultant_name,'user_type' => 'consultant','created_at' => $date,'logo' => '1','is_active' => 0,'verification_code' => $activation_code,'isPasswordUptd' => 1);
            $data_success = $this->Authmodel->consultant_register($data_array);
            if($data_success){
                //----------------------------------------------send email----------------------------------------------
                $email_temp = $this->getEmailTemp('User sign up for subscription');
                $email_temp['message'] = str_replace("{USERNAME}",$username,$email_temp['message']);
                $email_temp['message'] = str_replace("{COURSE_NAME}",'phpstack-971964-3536769.cloudwaysapps.com',$email_temp['message']);
                $email_temp['message'] = str_replace("{LOGO}",
                    "<img src='cid:logo'>",$email_temp['message']);
                $this->sendemail($email,'User sign up for subscription',$email_temp['message'],$email_temp['subject']);
                //---------------------------------------------------------------------------------------------------------

                //-------------------Send email to registered user for Email verificaiton  ----------------------

                $verificaiton_link = base_url().'index.php/Auth/verifyEmail/'.$activation_code;
                $email_tempU = $this->getEmailTemp('email verification authentication');
                $email_tempU['message'] = str_replace("{username}",$username,$email_tempU['message']);
                $email_tempU['message'] = str_replace("{verification_link}",$verificaiton_link,$email_tempU['message']);
                // $this->sendemail($email,'Email Verification',$email_tempU['message'],$email_tempU['subject']);
                $this->send_mail($email,'Email Verification',$email_tempU['message'],$email_tempU['subject']);
                //-------------------------------------------------------

                $data1 = array('consultant_id' => $data_success,'username' => $username);
                $this->session->set_userdata($data1);
                redirect('Auth/reg_pay_plans');
            }
        }
    }
	
    public function reg_pay_plans($trialed = null){
        $data['title'] = 'Next';
        $data['menu_title'] = 'pricing';

        $this->db->where('is_trial !=', 1);
        $this->db->where('term_type', 0);
        $query = $this->db->get('plan');
        $data['month_plan'] = $query->result();
        $this->db->where('is_trial !=', 1);
        $this->db->where('term_type', 1);
        //$this->db->order_by('price','ASC');
        $data['year_plan'] = $this->db->get('plan')->result();
        $this->db->where('is_trial', 1);
        $data['trial_plan'] = $this->db->get('plan')->row();
        $this->load->view('Register/register_payment_plans',$data);
    }
	
    public function term_condition(){
        $data['title'] = 'Next';
        $query = $this->db->get('plan');
        $data['plan'] = $query->result();
        $this->load->view('Register/term_condition',$data);
    }
	
    public function next_process2(){
        $data['title'] = 'Next';
        $query = $this->db->get('plan');
        $data['plan'] = $query->result();
        $this->load->view('process_two',$data);
    }
	
    public function next_process_done(){
        $checked = $this->input->post('checkeds');
        if($checked == 'on'){
            redirect('Auth/payment');
        }else{
            redirect('Auth/reg_pay_plans');
        }
    }
	
    public function add_purchase($plan_id = null){
        $this->load->model('Companymodel');
        //$plan_id    = $this->input->post('plan_id');
        $consultant_id = $this->session->userdata('consultant_id');
        $admin_name = $this->session->userdata('username');
        $plan_name = $this->db->where('plan_id',$plan_id)->get('plan')->row()->plan_name;
        $email = $this->db->where('is_admin', 1)->get('admin')->row()->email;
        $consultant_name = $this->db->where('consultant_id',$consultant_id)->get('consultant')->row()->consultant_name;

        //-------------------- send email-------------------------
        $email_temp = $this->getEmailTemp('User Sign up to Super Admin');
        $email_temp['message'] = str_replace("{Admin Name}",$admin_name. " from ". $consultant_name,$email_temp['message']);
        $email_temp['message'] = str_replace("{COURSE NAME}",'phpstack-971964-3536769.cloudwaysapps.com',$email_temp['message']);
        $email_temp['message'] = str_replace("{Plan}",$plan_name,$email_temp['message']);
        $this->sendemail($email,'User sign up for subscription',$email_temp['message'],$email_temp['subject'],2);
        //--------------------------------------------------------

        $date = date('Y-m-d');
        if($plan_id == '0'){
            redirect('Auth/reg_pay_plans');
        }else{
            if($plan_id == '1'){
                $this->trial();
            }else{
                $data = array();
                $plandata = $this->db->where('plan_id',$plan_id)->get('plan')->row();
                $term_type = $plandata->term_type;
                if($term_type == 0){
                    $expired = date('Y-m-d', strtotime($date. ' + 30 days'));
                }
                if($term_type == 1){
                    $expired = date('Y-m-d', strtotime($date. ' + 365 days'));
                }
                //start This is a case of payed
                $data = array('status' => 1,'plan_type' => 'real','plan_id' => $plan_id,'expired' => $expired);
                $invoice = array('status' => 'paid','admin_id' => $consultant_id,'amount' => $plandata->total_amount,'plan_id' => $plan_id,'tax_rate' => 0,'create_date' => date('Y-m-d'),'due_date' => date('Y-m-d'),'invoice_num' => 'INV-'. rand());
                $this->db->insert('invoice',$invoice);
                $invoice_id = $this->db->insert_id();
                $item_data = [
                    'description' => $plandata->name. ' Membership Payment','invoice_id' => $invoice_id,'amount' => $plandata->total_amount
                ];
				
                $this->db->insert('invoice_item',$item_data);
                $result = $this->Companymodel->update_company($data,$consultant_id);
                if($result){
                    /*require_once('./config.php');
                     $data['stripe'] = $stripe;*/
                    $session_data = array('com_status' => 1,'plan_type' => 'real','plan_id' => $plan_id);
                    $this->session->set_userdata($session_data);

                    redirect('Auth/payment',$data);
                    //redirect('Auth/term_condition');
                }else{
                    redirect('Auth/reg_pay_plans');
                }
            }
        }
    }
	
    public function payment(){
        $consultant_id = $this->session->userdata('consultant_id');
        if(isset($consultant_id)){
            $this->load->model('Companymodel');
            $this->load->model('Planmodel');

            $company = $this->Companymodel->get_company($consultant_id);
            $plan = $this->Planmodel->get_plan($company->plan_id);

            $data['company'] = $company;
            $data['plan'] = $plan;
            $data['menu_title'] = 'payment';

            $data['title'] = 'Payment';
            $this->load->view('Register/reg_payment',$data);
        }else{
            redirect('Auth/reg_pay_plans');
        }
    }
	
    public function payment_option(){
        $date = date('Y-m-d');
        $total_amount = $this->input->post('total_amount');
        $company_id = $this->input->post('consultant_id');
        $plan_id = $this->input->post('plan_id');

        require_once './config.php';
        $token = $_POST['stripeToken'];
        $email = $_POST['stripeEmail'];

        $customer = \Stripe\Customer::create(array('email' => $email,'source' => $token));
        $charge = \Stripe\Charge::create(array('customer' => $customer->id,'amount' => $total_amount * 100,'currency' => 'usd'));
        $data = array('total_amount' => $total_amount,'consultant_id' => $company_id,'payment_status' => 'paid','token' => $customer->created,'transaction_id' => $customer->id,'updated_at' => date('Y-m-d', strtotime($date)),'purchase_plan_id' => $plan_id);

        $paid = $this->db->insert('payment',$data);
        if($paid){
            $expired = date('Y-m-d', strtotime($date. ' + 365 days'));
            $data2 = array('status' => 1,'plan_type' => 'real','expired' => $expired,'plan_id' => $plan_id);
            $this->db->where('consultant_id',$company_id);
            $paid1 = $this->db->update('consultant',$data2);

            $datan = array('com_status' => 1);
            $this->session->set_userdata($datan);

            // Check Email Verified OR Not

            $chkVerification = $this->db->select('is_active')->get_where('consultant', array('consultant_id' => $consultant_id))->row();

            if($chkVerification->is_active == 0){
                $this->session->unset_userdata(array('consultant_id','com_status','plan_type','plan_id','username'));
                $data1 = array();
                $this->session->set_userdata($data1);
                $this->session->set_flashdata('message','Please verifiy your email to access the system');

                redirect('Auth/login');
                exit();
            }else{
                redirect('Welcome/consultantdashboard');
            }

            redirect('Welcome/consultantdashboard');
        }else{
            redirect('Auth/reg_pay_plans');
        }
    }
	
    public function trial(){
        ////// plan_id must be value '1'.
        ///

        // echo "You have choosen trial plan";
        // exit;
        $date = date('Y-m-d');
        $company_id = $this->session->userdata('consultant_id');
        if(isset($company_id)){
            $expired = date('Y-m-d', strtotime($date. ' + 14 days'));

            $data = array('status' => 1,'plan_type' => 'trial','plan_id' => '1','expired' => $expired);

            $this->load->model('Companymodel');
            $this->Companymodel->update_company($data,$company_id);

            /*			$this->db->where('company_id',$company_id);
             $paid1 = $this->db->update('company',$data);*/

            $data1n = array('com_status' => 1);

            $this->session->set_userdata($data1n);

            // Check Email Verified OR Not

            $chkVerification = $this->db->select('is_active')->get_where('consultant', array('consultant_id' => $company_id))->row();

            if($chkVerification->is_active == 0){
                $this->session->unset_userdata(array('consultant_id','com_status','plan_type','plan_id','username'));
                $data1 = array();
                $this->session->set_userdata($data1);
                $this->session->set_flashdata('message','Please verifiy your email to access the system');

                redirect('Auth/login');
                exit();
            }else{
                redirect('Welcome/consultantdashboard');
            }

            redirect('Welcome/consultantdashboard');
        }else{
            redirect('Auth/reg_pay_plans');
        }
    }
	
    public function get_purchase($ppi = null){
        $this->db->where('purchase_plan_id',$ppi);
        $query = $this->db->get('purchase_plan');
        return $query->row();
    }
	
    public function update_process(){
        $data['title'] = 'Upgrade';
        $consultant_id = $this->session->userdata('consultant_id');

        if(!isset($consultant_id)){
            $this->session->sess_destroy();
            redirect('Welcome');
        }

        $plan_id = $this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row()->plan_id;
        $chk = $this->db->query("select * from `plan` where `plan_id`='$plan_id'")->row();

        //$chk      = @$this->db->query("SELECT  FROM `process_list` WHERE `consultant_id`='$consultant_id'")->row()->tot_pro;
        $sql = 'and no_of_user > '. $chk->no_of_user;
        if($plan_id == '1'){
            $sql = '';
        }
        $query = $this->db->query("select * from `plan` where is_trial=0 ".$sql." order by no_of_user ASC")->result();
        $data['plan'] = $query;
        $data['upgrade'] = 0;
        $this->load->view('upgrade_account',$data);
    }
	
    public function check_update_process($consultant_id){
        $data = $this->db->query("select * from `consultant` where `consultant_id`='$consultant_id' ")->row()->plan_id;

        if($data){
            $data1 = $this->db->query("select * from `plan` where `plan_id`='$data'")->row();
            return $data1;
        }
    }
	
    public function forgot_pass(){
        $this->load->model('Authmodel');
        $this->load->library('form_validation');

        $data['title'] = 'Next';
        $this->load->view('forgot_pass',$data);
    }
	
    public function forgot_pass_send_link(){
        $this->load->model('Authmodel');
        $this->load->library('form_validation');

        $data['title']  = 'Next';
        $email          = $this->input->post('email');
        $method         = $this->input->post('forget_method');

        if($email != ''){
            $very1 = $this->Authmodel->admin_email($email);
            $very2 = $this->Authmodel->employee_email($email);
            $very3 = $this->Authmodel->consultant_email($email);
            if(!empty($very1) || !empty($very2) || !empty($very3)){
                $forgot_pass_code = $this->serialkey();
                $dd = array('forget_pass_code' => $forgot_pass_code);
                $this->load->library('email');
                if(!empty($very1)){
                    $this->db->where('email',$email);
                    $query = $this->db->update('admin',$dd);
                }
                if(!empty($very2)){
                    $this->db->where('employee_email',$email);
                    $query = $this->db->update('employees',$dd);
                }
                if(!empty($very3)){
                    $this->db->where('email',$email);
                    $query = $this->db->update('consultant',$dd);
                }
                /*$this->load->library('email');

				$email   = $email;
				$subject = 'Forgot Password';

				$htmlContent = 'Dear User, Your new Password is '. $pass;


				$this->email->from('admin@rrgpos.com','New Password');
				$this->email->to($email);
				$this->email->subject($subject);
				$this->email->message($htmlContent);*/

                //-------------------Send email to registered user for resetting account password  ----------------------
                if ($method == 'user'){
                    $data['forget_method'] = 'Username';
                    $recovery_link  = base_url().'index.php/auth/resetUsername/'.$forgot_pass_code;
                }else{
                    $data['forget_method'] = 'Password';
                    $recovery_link = base_url().'index.php/Auth/resetPassword/'.$forgot_pass_code;
                }
                $email_tempF = $this->getEmailTemp('forgot password recovery');
                $email_tempF['message'] = str_replace("{forgot_pass_link}",$recovery_link,$email_tempF['message']);
                // $this->sendemail($email,'Email Verification',$email_tempU['message'],$email_tempU['subject']);
                $result = $this->sendemail($email,'Forgot Password',$email_tempF['message'],$email_tempF['subject']);
                //-------------------------------------------------------

                if($result){
                    $this->load->view('forgot_pass_send_link',$data);
                }else{
                    $this->session->set_flashdata('message','Invalid Email Address');
                    redirect('Auth/forgot_pass');
                }
            }else{
                $this->session->set_flashdata('message','Invalid Email Address');
                redirect('Auth/forgot_pass');
            }
            //$this->load->view('forgot_pass_send_link',$data);
        }else{
            redirect('Welcome');
        }
    }
	
    public function upgrade_plan(){
        $this->load->model('Companymodel');
        $plan_id = $this->input->post('plan_id');
        $consultant_id = $this->session->userdata('consultant_id');

        if(!isset($consultant_id)){
            $this->session->sess_destroy();
            redirect('Welcome');
        }

        if($plan_id == 0 || $plan_id == '0'){
            redirect('Auth/update_process');
        }else{
            $data = array();
            $data['title'] = 'agreement';
            $data['plan_id'] = $plan_id;
            //$result = $this->consultantmodel->update_consultant($data,$consultant_id);
            //redirect('Auth/term_condition');
            $this->load->view('upgrade_term_condition',$data);
        }
    }
	
    public function upgrade_process_done(){
        $checked = $this->input->post('checkeds');
        $plan_id = $this->input->post('plan_id');

        if($checked != ''){
            redirect('Auth/upgrade_payment/'. $plan_id);
        }else{
            redirect('Auth/upgrade_plan');
        }
    }
	
    public function upgrade_payment($plan_id = 0){
        $consultant_id = $this->session->userdata('consultant_id');

        if(isset($consultant_id)){
            $this->load->model('Companymodel');
            $this->load->model('Planmodel');

            $consultant = $this->Companymodel->get_company($consultant_id);
            $plan = $this->Planmodel->get_plan($plan_id);

            $data['consultant'] = $consultant;
            $data['plan'] = $plan;

            $data['title'] = 'Payment';
            $this->load->view('upgrade_reg_payment',$data);
        }else{
            redirect('Auth/reg_pay_plans');
        }
    }
	
    public function terms(){
        $data['menu_title'] = 'payment';
        $this->load->view('terms',$data);
    }
	
    public function securityAuth(){
        $data['menu_title'] = 'security';
        $this->load->view('2FAPage',$data);
    }
	
	public function verifyOtpChk($data=NULL,$userType=NULL){				
		$otp = rand(11111,99999);		
		if(!in_array($userType,['admin','Lead Auditor','Auditor','Process Owner','Auditee','Consultant'])){
			redirect('Welcome');
		}
						
		if($userType == 'admin'){
			$security_key = md5($data->email);
			$this->db->where('email',$data->email);
			$update = $this->db->update('admin', array('otp' => $otp, 'security_key' => $security_key));	
			$userEmail = $data->email;	
		}		
		if($userType == 'Lead Auditor' || $userType == 'Auditor' || $userType == 'Process Owner' || $userType == 'Auditee'){
			$security_key = md5($data->employee_email);
			$this->db->where('employee_email',$data->employee_email);
			$update = $this->db->update('employees', array('otp' => $otp, 'security_key' => $security_key));	
			$userEmail = $data->employee_email;	
		}
		if($userType == 'Consultant'){
			$security_key = md5($data->email);
			$this->db->where('email',$data->email);
			$update = $this->db->update('consultant', array('otp' => $otp, 'security_key' => $security_key));	
			$userEmail = $data->email;	
		}
		$email_tempU = $this->getEmailTemp('email otp login');
		$email_tempU['message'] = str_replace("{USERNAME}",$data->username,$email_tempU['message']);
		$email_tempU['message'] = str_replace("{OTP}",$otp,$email_tempU['message']);
		$this->sendemail($userEmail,'Email OTP',$email_tempU['message'],$email_tempU['subject']);

		$param = base64_encode($data->username.'||'.$security_key.'||'.$userType);		
		$param = str_replace("=","",$param);
		redirect('auth/verifyOtp/'.$param);
    }
	
	public function resendOtp(){	
		$otp = rand(11111,99999);		
		$security_key = $_REQUEST['key'];
		$userType = $_REQUEST['type'];
		$username = $_REQUEST['username'];
		
		if($userType == 'admin'){
			$this->db->where('security_key', $security_key);
			$this->db->where('username', $username);
			$data = $this->db->get('admin');
			$userEmail = $data->result()[0]->email;
		}		
		if($userType == 'Lead Auditor' || $userType == 'Auditor' || $userType == 'Process Owner' || $userType == 'Auditee'){				
			$this->db->where('security_key', $security_key);
			$this->db->where('username', $username);
			$data = $this->db->get('employees');
			$userEmail = $data->result()[0]->employee_email;
		}
		if($userType == 'Consultant'){
			$this->db->where('security_key', $security_key);
			$this->db->where('username', $username);
			$data = $this->db->get('consultant');	
			$userEmail = $data->result()[0]->email;
		}
		
		$email_tempU = $this->getEmailTemp('email otp login');
		$email_tempU['message'] = str_replace("{USERNAME}",$username,$email_tempU['message']);
		$email_tempU['message'] = str_replace("{OTP}",$otp,$email_tempU['message']);
		$this->sendemail($userEmail,'Email OTP',$email_tempU['message'],$email_tempU['subject']);
		
		if($userType == 'admin'){
			$security_key = md5($userEmail);
			$this->db->where('email',$userEmail);
			$update = $this->db->update('admin', array('otp' => $otp, 'security_key' => $security_key));	
		}		
		if($userType == 'Lead Auditor' || $userType == 'Auditor' || $userType == 'Process Owner' || $userType == 'Auditee'){
			$security_key = md5($userEmail);
			$this->db->where('employee_email',$userEmail);			
			$update = $this->db->update('employees', array('otp' => $otp, 'security_key' => $security_key));				
		}
		if($userType == 'Consultant'){
			$security_key = md5($userEmail);
			$this->db->where('email',$userEmail);
			$update = $this->db->update('consultant', array('otp' => $otp, 'security_key' => $security_key));	
		}		
	}
	
	public function otp_login(){	
		if($this->input->post('verify_otp') != ''){
			$this->load->model('Authmodel');
			$otp = $this->input->post('verify_otp');
			$key = $this->input->post('key');
			$usertype = $this->input->post('type');
			
			$this->db->where(['security_key' => $key, 'otp' => $otp]);
			if($usertype == 'Consultant'){
				$response = $this->db->get('consultant');
			}
			if($usertype == 'Lead Auditor' || $usertype == 'Auditor' || $usertype == 'Process Owner' || $usertype == 'Auditee'){
				$response = $this->db->get('employees');
			}
			if($usertype == 'admin'){
				$response = $this->db->get('admin');
			}
						
			if($response->num_rows() == 0){
				$this->session->set_flashdata('message','Wrong OTP');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				if($usertype == 'Consultant'){
					$this->db->where(['consultant_id' => $response->row()->consultant_id]);
					$update = $this->db->update('consultant', array('otp' => '', 'security_key' => '', 'isVerifyOTP' => 1));
					$this->login('Consultant',$response->row()->username,$response->row()->password);
				}
				if($usertype == 'Lead Auditor' || $usertype == 'Auditor' || $usertype == 'Process Owner' || $usertype == 'Auditee'){
					$this->db->where(['employee_id' => $response->row()->employee_id]);
					$update = $this->db->update('employees', array('otp' => '', 'security_key' => '', 'isVerifyOTP' => 1));
					$this->login($usertype,$response->row()->username,$response->row()->password);
				}
				if($usertype == 'admin'){
					$this->db->where(['id' => $response->row()->id]);
					$update = $this->db->update('admin', array('otp' => '', 'security_key' => '', 'isVerifyOTP' => 1));
					$this->login('admin',$response->row()->username,$response->row()->password);
				}
			}
		}else{
			$this->session->set_flashdata('message','Please enter OTP');
			redirect($_SERVER['HTTP_REFERER']);
		}	
	}
		
	public function verifyOtp($param=NULL){
		if(isset($param) && !empty($param)){
			$param = base64_decode($param);
			$urlParam = explode('||',$param);
			
			if(isset($urlParam[0]) && isset($urlParam[1]) && isset($urlParam[2])){
				$username = $urlParam[0];
				$key = $urlParam[1];
				$usertype = $urlParam[2];				
				if(!in_array($usertype,['admin','Lead Auditor','Auditor','Process Owner','Auditee','Consultant'])){
					redirect('Welcome');
				}
				$this->db->where('security_key', $key);  
				$this->db->where('username', $username);     
				if($usertype == 'Consultant'){  
					$result = $this->db->get('consultant');
				}		
				if($usertype == 'admin'){  
					$result = $this->db->get('admin');
				}		
				if($usertype == 'Lead Auditor' || $usertype == 'Auditor' || $usertype == 'Process Owner' || $usertype == 'Auditee'){  
					$result = $this->db->get('employees');
				}
				
				if($result->num_rows() == 0){
					redirect('Welcome');
				}
								
				$data['title'] = 'Verify OTP';
				$data['menu_title'] = 'verify_otp';
				$data['key'] = $key;
				$data['type'] = $usertype;
				$data['username'] = $username;
				$this->load->view('verifyOtp',$data);
				
			}else{
				redirect('Welcome');	
			}			
		}else{
			redirect('Welcome');	
		}		
	}
		
    public function submitAnswer(){
        $data['menu_title'] = 'security';

        $param = $this->input->post('security');
        // echo "<pre>";
        // 	print_r($param);
        // exit;
        if($param){
            $validation = [
                [
                    'field' => 'security[question]','label' => 'Security Question','rules' => 'required'
                ],
                [
                    'field' => 'security[answer]','label' => 'Security Answer','rules' => 'required'
                ]
            ];
            $this->form_validation->set_rules($validation);

            if(!$this->form_validation->run()){
                $this->load->view('2FAPage',$data);
            }else{
                $userData = $this->session->userdata('temp_user');
                $data1 = array();

                if($param['answer'] == $userData->security_answer){
                    $data1 = array('consultant_id' => $userData->consultant_id,'username' => $userData->username,'user_type' => '','com_status' => $userData->status,'plan_type' => $userData->plan_type);
                    $data1['menu_title'] = 'home';
                    $this->session->set_userdata($data1);
                }

                if($data1){
                    redirect('welcome/consultantdashboard');
                }else{
                    $this->session->set_flashdata('message','Invalid Answer , Please try again.');
                    $this->load->view('2FAPage',$data);
                }
            }
        }else{
            $this->load->view('2FAPage',$data);
        }
    }
	
    public function send_mail($to,$toname,$content,$title,$type = 0){
        $from_email = "support@isoprocessbasedauditexperts.com";
        $to_email = $to;

        //Load email library
        $this->load->library('email');

        $this->email->set_mailtype('html');
        $this->email->from($from_email,$toname);
        $this->email->to($to_email);
        $this->email->subject($title);
        $this->email->message($content);

        //Send mail
        if($this->email->send()){
            return true;
        }else{
            return false;
        }
    }
	
    public function random($length,$chars = ''){
        if(!$chars){
            $chars = implode(range('a','f'));
            $chars.= implode(range('0','9'));
        }
        $shuffled = str_shuffle($chars);
        return substr($shuffled, 0,$length);
    }
	
    public function serialkey(){
        return $this->random(8).'-'.$this->random(8).'-'.$this->random(8).'-'.$this->random(8);
    }
	
    public function verifyEmail($activation_code){
        $data['title'] = 'Verify Email';
        $data['menu_title'] = 'emailverify';

        $this->db->where('verification_code',$activation_code);
        $update = $this->db->update('consultant', array('is_active' => 1));

        $this->load->view('verify-email.php',$data);
    }
	
    public function resetPassword($recovery_link = ''){
        $data['title'] = 'Reset Password';
        $data['menu_title'] = 'resetpassword';
        $data['recovery_link'] = $recovery_link;

        //$this->db->where('activation_code',$activation_code);
        //$update = $this->db->update('admin', array('is_active' => 1));


        if($this->input->post()){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('password','Password','required|min_length[8]|callback_password_check');
            $this->form_validation->set_rules('repassword','Password','required|min_length[8]|callback_password_check');
            $this->form_validation->set_rules('repassword','Password Confirmation','required|matches[password]');
            if($this->form_validation->run() == false){
                $data['menu_title'] = 'resetpassword';
                $data['recovery_link'] = $this->input->post('recovery_link');
                $this->load->view('reset-password.php',$data);
            }else{
                $password = getHashedPassword($this->input->post('password'));
                $recovery_link = $this->input->post('recovery_link');
                $up = array('password' => $password);

                $this->db->where('forget_pass_code',$recovery_link);
                $update1 = $this->db->update('consultant',$up);

                $this->db->where('forget_pass_code', $recovery_link);
                $update2 = $this->db->update('admin', $up);

                $this->db->where('forget_pass_code', $recovery_link);
                $update3 = $this->db->update('employees', $up);

                if($update1 || $update2 || $update3) {
                    $this->load->view('reset-password-success',$data);
                }else{
                    $this->session->set_flashdata('message','OOPS ! Something went wrong , Please try again.');
                    $this->load->view('reset-password.php',$data);
                }
            }
        }else{
            $this->load->view('reset-password.php',$data);
        }
    }
    public function resetUsername ($recovery_link = '') {
        $data['title'] = 'Reset Username';
        $data['menu_title'] = 'resetusername';
        $data['recovery_link'] = $recovery_link;

        //$this->db->where('activation_code', $activation_code);
        //$update = $this->db->update('admin', array('is_active' => 1));

        if($this->input->post()) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Username','required|is_unique[consultant.username]|is_unique[admin.username]|is_unique[employees.username]');

            if ($this->form_validation->run() == FALSE) {
                $data['menu_title'] = 'resetusername';
                $data['recovery_link'] = $this->input->post('recovery_link');
                $this->load->view('reset-username', $data);
            } else {
                $username       = $this->input->post('username');
                $recovery_link  = $this->input->post('recovery_link');
                $up = array('username' => $username);

                $this->db->where('forget_pass_code',$recovery_link);
                $update1 = $this->db->update('consultant',$up);

                $this->db->where('forget_pass_code', $recovery_link);
                $update2 = $this->db->update('admin', $up);

                $this->db->where('forget_pass_code', $recovery_link);
                $update3 = $this->db->update('employees', $up);

                if($update1 || $update2 || $update3) {
                    $this->load->view('reset-username-success', $data);
                } else {
                    $this->session->set_flashdata('message', 'OOPS ! Something went wrong , Please try again.');
                    $this->load->view('reset-username', $data);
                }

            }

        } else {
            $this->load->view('reset-username', $data);
        }

    }
	
    public function update_password(){
        // echo "<pre>";
        // 	print_r($this->input->post());
        // exit;
        $this->load->model('Authmodel');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password','Password','required|min_length[8]|callback_password_check');
        $this->form_validation->set_rules('repassword','Password','required|min_length[8]|callback_password_check');
        $this->form_validation->set_rules('repassword','Password Confirmation','required|matches[password]');
        if($this->form_validation->run() == false){
            $errors = validation_errors();
            echo json_encode(['error' => $errors]);
        }else{
            $password = md5($this->input->post('password'));

            $user_type = $this->input->post('user_type');

            $upArr = array('password' => $password,'isPasswordUptd' => 1);

            switch ($user_type){
                case 'superadmin':
                    $this->db->where('id',$this->session->userdata('superadmin_id'));
                    $query = $this->db->update('admin',$upArr);
                    break;
                case 'admin':
                    $this->db->where('id',$this->session->userdata('admin_id'));
                    $query = $this->db->update('admin',$upArr);
                    break;
                case 'Lead Auditor':
                    $this->db->where('employee_id',$this->session->userdata('employee_id'));
                    $query = $this->db->update('employees',$upArr);
                    break;
                case 'Auditor':
                    $this->db->where('employee_id',$this->session->userdata('employee_id'));
                    $query = $this->db->update('employees',$upArr);
                    break;
                case 'Process Owner':
                    $this->db->where('employee_id',$this->session->userdata('employee_id'));
                    $query = $this->db->update('employees',$upArr);
                    break;
                case 'Auditee':
                    $this->db->where('employee_id',$this->session->userdata('employee_id'));
                    $query = $this->db->update('employees',$upArr);
                    break;
                case 'Consultant':
                    $this->db->where('consultant_id',$this->session->userdata('consultant_id'));
                    $query = $this->db->update('consultant',$upArr);
                    break;
                default:
                    $this->db->where('id',$this->session->userdata('admin_id'));
                    $query = $this->db->update('admin',$upArr);
            }
            if($query){
                $this->session->set_userdata('is_password_updated', 1);
                echo json_encode(['success' => 'Password updated successfully.']);
            }
        }
    }
}
