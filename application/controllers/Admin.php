<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/BaseController.php';
class Admin extends BaseController//CI_Controller
{
	public function __construct(){
		parent::__construct();

		$this->load->library('session');
	}

	public function plan_list()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$data['title'] = "Plans";
			$data['plans'] = $this->db->get('plan')->result();
			$this->load->view('Admin/plan_list', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function add_plan()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$plan_name     = $this->input->post('plan_name');
			$no_of_user    = $this->input->post('no_of_user');
			//$total_process = $this->input->post('total_process');
			$total_amount  = $this->input->post('total_amount');
			$created_at    = date('Y-m-d');

			$data = array(
				'plan_name' => $plan_name,
				'no_of_user' => $no_of_user,
				'total_amount' => $total_amount,
				'created_at' => $created_at
			);
			$done = $this->db->insert('plan', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'success');
				redirect('Admin/plan_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Admin/plan_list');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function delete_plan($id = Null)
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$this->db->where('plan_id', $id);
			$done = $this->db->delete('plan');
			if ($done) {
				$this->session->set_flashdata('message', 'success_del');
				redirect('Admin/plan_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Admin/plan_list');
			}
		} else {
			redirect('Welcome');
		}
	}
	public function findplan()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$id = $this->input->post('id');
			$this->db->where('plan_id', $id);
			$done = $this->db->get('plan')->row();
			echo json_encode($done);
		} else {
			redirect('Welcome');
		}
	}
	public function findcomp()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$id = $this->input->post('id');
			$this->db->where('consultant_id', $id);
			$done = $this->db->get('consultant')->row();

			$done->expired = date('m/d/Y', strtotime($done->expired));

			echo json_encode($done);
		} else {
			redirect('Welcome');
		}
	}

	public function edit_plan()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$plan_id      = $this->input->post('plan_id1');
			$plan_name    = $this->input->post('plan_name');
			$no_of_user   = $this->input->post('no_of_user');
			$total_amount = $this->input->post('total_amount');
			$created_at   = date('Y-m-d');

			if($plan_id == '1'){
				$plan_name = 'Trial';
			}
			$data = array(
				'plan_name' => $plan_name,
				'no_of_user' => $no_of_user,
				'total_amount' => $total_amount
			);

			$this->db->where('plan_id', $plan_id);
			$done = $this->db->update('plan', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Admin/plan_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Admin/plan_list');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function policy(){
		$data['setting'] = $this->setting->getOne('1');
		$data['title'] = "Policy Management";
		$this->load->view('Admin/policy', $data);
	}
	public function updatePolicy(){
		$data = $this->input->post();
		$result = $this->setting->updateOne(1, $data);
		if($result){
			$response['status'] = true;
			$response['msg'] = "Updated Successfully";
			echo json_encode($response);
		}
	}
	public function consultant_list()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$data['title']   = "Owner Mangement";
			$this->db->where('plan_id !=','');
			$this->db->where('plan_id !=','0');
			$data['consultant'] = $this->db->get('consultant')->result();

			$this->db->where('is_trial','0');
			$this->db->order_by('no_of_user','asc');
			$data['plan'] = $this->db->get('plan')->result_array();

			$this->load->view('Admin/consultant_list', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function delete_consultant($id = Null)
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {

			$this->db->where('consultant_id', $id);
			$this->db->delete('employees');

			$this->db->where('consultant_id', $id);
			$this->db->delete('payment');

			$this->db->where('consultant_id', $id);
			$this->db->delete('purchase_plan');

			$this->db->where('consultant_id', $id);
			$this->db->delete('consultant');

			$this->session->set_flashdata('message', 'success_del');
			redirect('Admin/consultant_list');
		} else {
			redirect('Welcome');
		}
	}

	public function payment_list()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$data['title']   = "Payment History";
			$data['payment'] = $this->db->get('payment')->result();
			$this->load->view('Admin/payment_list', $data);
		} else {
			redirect('Welcome');
		}
	}


	public function edit_profile()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {

			$this->db->where('id', $admin_id);
			$data['profile'] = $this->db->get('admin')->row();
			$data['title']   = "Edit Profile";
			$this->load->view('Admin/edit_profile', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function update_profile()
	{
		$admin_id = $this->session->userdata('admin_id');
		$username = $this->input->post('username');
		$email    = $this->input->post('email');
        $phone    = $this->input->post('phone');
        /*=-=- check user mobile number valid start =-=-*/
        $phone_response = $this->phone_rk->checkPhoneNumber($phone);
        if (!$phone_response['success']){
            $this->session->set_flashdata('phone_response', $phone_response);
            redirect('Admin/edit_profile');
            return;
        }
        /*=-=- check user mobile number valid end =-=-*/
		$up       = array(
			'username' => $username,
			'email' => $email,
            'phone' => $phone
		);

		if ($admin_id) {
			$this->db->where('id', $admin_id);
			$done = $this->db->update('admin', $up);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Admin/edit_profile');
			} else {
				redirect('Admin/edit_profile');
			}

		} else {
			redirect('Welcome');
		}
	}
    public function update_password() {
        $param  = $this->input->post();
        $this->db->where('id', $this->session->userdata('admin_id'));
        $user   = $this->db->get('admin')->row();
        if ($user){
            if (!verifyHashedPassword($param['old_password'], $user->password)){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'Old Password did\'nt matched'));
                redirect('Admin/edit_profile');
            }
            if (empty(trim($param['password'])) && empty(trim($param['repassword']))){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'New Password Cannot be Empty'));
                redirect('Admin/edit_profile');
            }
            if ($param['password'] != $param['repassword']){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'New Password didn\'t matched with confirm password'));
                redirect('Admin/edit_profile');
            }
            $password   = getHashedPassword($param['password']);
            $this->db->where(['id' => $user->id]);
            $result     = $this->db->update(array('password' => $password));
            if ($result){
                $this->session->set_flashdata('password', array('success' => true, 'message' => 'Password Updated Successfully'));
            }else{
                $this->session->set_flashdata('password', array('success' => true, 'message' => 'Password Not Updated'));
            }

            redirect('Admin/edit_profile');
        }
        redirect('Welcome');
    }
	public function default_logo()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$this->db->where('id', '1');
			$data['logo']  = $this->db->get('default_setting')->row();
			$data['title'] = "Default Logo";
			$this->load->view('Admin/default_logo', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function update_default()
	{
		$admin_id = $this->session->userdata('admin_id');

		if (!empty($_FILES['picture']['name'])) {
			$config['upload_path']   = 'uploads/logo/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['file_name']     = time() . $_FILES['picture']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('picture')) {
				$uploadData = $this->upload->data();
				$picture    = $uploadData['file_name'];
			} else {
				$picture = '';
			}
		} else {
			$picture = @$this->db->query("SELECT * FROM default_setting WHERE id='1'")->row()->logo;
		}

		$up = array(
			'logo' => $picture
		);

		if ($admin_id) {
			$this->db->where('id', '1');
			$done = $this->db->update('default_setting', $up);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Admin/default_logo');
			} else {
				redirect('Admin/default_logo');
			}

		} else {
			redirect('Welcome');
		}
	}

	public function edit_consultant()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$consultant_name = $this->input->post('consultant_name');
			$username     = $this->input->post('username');
			$email        = $this->input->post('email');
            /*=-=- check user mobile number valid start =-=-*/
            $this->load->library('phone_RK');
            $phone        = $this->input->post('phone');
            $phone_response = $this->phone_rk->checkPhoneNumber($phone);
            if (!$phone_response['success']){
                $this->session->set_flashdata('phone_response', $phone_response);
                redirect('Admin/consultant_list');
                return;
            }
            /*=-=- check user mobile number valid end =-=-*/
			$password     = getHashedPassword($this->input->post('password'));
			$consultant_id11 = $this->input->post('consultant_id11');
			$plan_id     = $this->input->post('plan');
			$expired = date('Y-m-d', strtotime($this->input->post('expired_date')));

			$data = array(
				'consultant_name' => $consultant_name,
				'user_type'     => $consultant_name,
				'username'      => $username,
				'email'         => $email,
                'phone'         => $phone,
				'password'      => $password,
				'plan_type'     => 'real',
				'expired'       => $expired,
				'plan_id'       => $plan_id
			);
            //check main otp verification status
            if ($this->settings->otp_verification){
                $data['otp_status'] = $this->input->post('otp_status');
            }

            if (empty(trim($this->input->post('password')))){
                unset($data['password']);
            }
			$this->db->where('consultant_id', $consultant_id11);
			$done = $this->db->update('consultant', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Admin/consultant_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Admin/consultant_list');
			}
		} else {
			redirect('Welcome');
		}
	}


	public function add_consultant()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$consultant_name = $this->input->post('consultant_name');
			$username     = $this->input->post('username');
			$email        = $this->input->post('email');
            /*=-=- check user mobile number valid start =-=-*/
            $this->load->library('phone_RK');
            $phone        = $this->input->post('phone');
            $phone_response = $this->phone_rk->checkPhoneNumber($phone);
            if (!$phone_response['success']){
                $this->session->set_flashdata('phone_response', $phone_response);
                redirect('Admin/consultant_list');
                return;
            }
            /*=-=- check user mobile number valid end =-=-*/
			$password     = getHashedPassword($this->input->post('password'));
			$consultant_id11 = $this->input->post('consultant_id11');
			$plan_id     = $this->input->post('plan');
			$expired = date('Y-m-d', strtotime($this->input->post('expired_date')));
			$date             = date('Y-m-d');

			$data = array(
				'consultant_name'   => $consultant_name,
				'user_type'         => $consultant_name,
				'username'          => $username,
				'email'             => $email,
                'phone'             => $phone,
				'password'          => $password,
				'plan_type'         => 'real',
				'expired'           => $expired,
				'plan_id'           => $plan_id,
				'status'            => '1',
				'created_at'        => $date
			);
            if (empty(trim($this->input->post('password')))){
                unset($data['password']);
            }
			$done = $this->db->insert('consultant', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				//---------------------send email to user(admin)-----------------------
				$email_temp = $this->getEmailTemp('Super Admin assign subscription');
				$email_temp['message'] = str_replace("{USERNAME}", $username, $email_temp['message']);
				$email_temp['message'] = str_replace("{COURSE_NAME}", 'phpstack-971964-3536769.cloudwaysapps.com', $email_temp['message']);
				$email_temp['message'] = str_replace("{firstname1}", 'firstname1', $email_temp['message']);
				$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
				$this->sendemail($email, 'Super Admin assigned a subscription', $email_temp['message'], $email_temp['subject']);
				//---------------------------------------------------------------------
                //---------------------------------------------- send sms ----------------------------------------------
                if (!empty($phone) && $this->settings->otp_verification){
                    $phone = formatMobileNumber($phone, true);
                    /*=-=- check user mobile number valid start =-=-*/
                    $phone_response = $this->phone_rk->checkPhoneNumber($phone);
                    if ($phone_response['success']){
                        $message = "Hi {$username}".PHP_EOL;
                        $message.= "Congratulations you have been assigned to ".APP_NAME." Quality Circle’s Process and Risk Based Software. The software is the first of its kind globally. qIt is a cloud based automated tool which does all the work for you. No more paper checklist. Grab your tablet or smartphone, input your data and the SMART platform does the rest, Including generating your reports.";
                        $this->twill_rk->sendMsq($phone,$message);
                    }
                }

				redirect('Admin/consultant_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Admin/consultant_list');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function cases_list()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$data['title'] = "Case Type";
			$data['case']  = $this->db->get('cases')->result();
			$this->load->view('Admin/cases_list', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function add_case()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$case_name = $this->input->post('case_name');
			$data      = array(
				'case_name' => $case_name
			);
			$done      = $this->db->insert('cases', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'success');
				redirect('Admin/cases_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Admin/cases_list');
			}
		} else {
			redirect('Welcome');
		}
	}
	public function delete_case($id = Null)
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$this->db->where('case_id', $id);
			$done = $this->db->delete('cases');
			if ($done) {
				$this->session->set_flashdata('message', 'success_del');
				redirect('Admin/cases_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Admin/cases_list');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function findcase()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$id = $this->input->post('id');
			$this->db->where('case_id', $id);
			$done = $this->db->get('cases')->row();
			echo json_encode($done);
		} else {
			redirect('Welcome');
		}
	}
	public function edit_case()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$case_id   = $this->input->post('case_id');
			$case_name = $this->input->post('case_name');
			$data      = array(
				'case_name' => $case_name
			);

			$this->db->where('case_id', $case_id);
			$done = $this->db->update('cases', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Admin/cases_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Admin/cases_list');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function invoice(){
		$admin_id = $this->session->userdata('admin_id');
		$start_date = $this->input->post('filter_start');
		$end_date = $this->input->post('filter_end');
		$date = date('Y-m-d');
		if($start_date == NULL){
			$start_date = date('Y-m-d', strtotime($date . ' - 29 days'));
		}
		if($end_date == NULL){
			$end_date = date('Y-m-d');
		}
		if($admin_id){
			$data['title'] = 'Invoice';
			$filters["from"] = $start_date;
			$filters["to"] = $end_date;
			$data['invoices'] = $this->invoice->getAll($filters, "create_date", "DESC");
			// $this->db->order_by('create_date','DESC');
			// $data['invoices'] = $this->db->where('create_date >=', $start_date)->where('create_date <=',$end_date)->get('invoice')->result();
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;

			$this->db->select('SUM(amount) as amount');
			$this->db->where('create_date >=', $start_date)->where('create_date <=',$end_date);
			$data['total_amount'] = $this->db->get('invoice')->row()->amount;
			$this->db->select('SUM(amount) as amount');
			$this->db->where('status','pending');
			$this->db->where('create_date >=', $start_date)->where('create_date <=',$end_date);
			$data['total_open_amount'] = $this->db->get('invoice')->row()->amount;
			$this->db->select('SUM(amount) as amount');
			$this->db->where('status','paid');
			$this->db->where('create_date >=', $start_date)->where('create_date <=',$end_date);
			$data['total_paid_amount'] = $this->db->get('invoice')->row()->amount;
			$this->load->view('Admin/invoice/invoice_list',$data);
		}else{
			redirect('Welcome');
		}
	}

		/*************For last login details***********************/
	public function login_history()
	{
		$data['dd4'] = 'active';
		$data['d4'] = 'act1';
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$data['title'] = "Login History";
			$sql = "SELECT
							*
						FROM
							login_history
						WHERE
							user_id = " . $admin_id . "
						
						ORDER BY
							date_time DESC";
			$data['login_history'] = $this->db->query($sql)->result();
			$this->load->view('Admin/login_history', $data);
		} else {
			redirect('Welcome');
		}
	}
	/************************End*******************************/
	/*************For last login details***********************/
	public function Email_template()
	{
		$data['dd5'] = 'active';
		$data['d5'] = 'act1';
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$data['title'] = "Email Template";
			$sql = "SELECT * FROM subscription_email_template ORDER BY date_time DESC";
			$data['Email_template'] = $this->db->query($sql)->result();
			$this->load->view('Admin/Email_template', $data);
		} else {
			redirect('Welcome');
		}
	}
	/************************End*******************************/
	/*************For add email template***********************/
	public function Add_email_template()
	{
		$admin_id = $this->session->userdata('admin_id');
		if ($admin_id) {
			$template_name = $this->input->post('template_name');
			$subject       = $this->input->post('subject');
			$desc          = $this->input->post('desc');
			$created_at    = date('Y-m-d H:i:s');

			$data = array(
				'template_name' => $template_name,
				'subject' => $subject,
				'description' => $desc,
				'date_time' => $created_at
			);
			$done = $this->db->insert('subscription_email_template', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'success');
				redirect('Admin/Email_template');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Admin/Email_template');
			}
		} else {
			redirect('Welcome');
		}
	}
/*****************************End************************************/
 /*************Edit Email Template ***********************/
    public function edit_email_template()
    {
        $pa_id = $_POST['pa_id'];
        $admin_id = $this->session->userdata('admin_id');
        if ($admin_id) {
            $sql = "SELECT * FROM subscription_email_template WHERE id ='$pa_id'";
            $data['edit_single_temp'] = $this->db->query($sql)->result();
            //$this->load->view('employee/select_process', $data);
            echo json_encode($data);
        } else {
            redirect('Welcome');
        }
    }
    /************************End*******************************/
/************************Update Email Template********************************/
   public function update_email_template()
    {
    	$admin_id = $this->session->userdata('admin_id');
        $template_name    = $this->input->post('template_name');
        $subject          = $this->input->post('subject');
        $desc             = $this->input->post('desc');
        $email__id             = $this->input->post('email__id');
        $up = array(
            'template_name' => $template_name,
            'subject' => $subject,
            'description' => $desc,
        );
        if ($admin_id) {
            $this->db->where('id', $email__id);
            $done = $this->db->update('subscription_email_template', $up);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Admin/Email_template'.$pro_id);
            } 
        } else {
            redirect('Welcome');
        }
    }

/************************END********************************/
/**************************Delete Process****************************/
     public function Del_email_template(){
       $admin_id = $this->session->userdata('admin_id');
       $email_id    = $this->input->post('email_id_del');
        if ($admin_id) {
            $this->db->where('id', $email_id);
            $done = $this->db->delete('subscription_email_template');
            if ($done) {
                    $this->session->set_flashdata('message', 'success_del');
                    redirect('Admin/Email_template');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Admin/Email_template');
            }
        } else {
            redirect('Welcome');
        }
    }
/***************************END**********************************/
#--------------------------Send Email for expired subscription---------------

     public function sendEmail_expired(){
     		$this->db->where('plan_id !=','');
			$this->db->where('plan_id !=','0');
			$data['consultant'] = $this->db->get('consultant')->result();
	     	$currentdate = date('Y-m-d');// or your date as well
		     $finalArray=array();
	     	foreach ($data['consultant'] as $key => $value) {
	     		if($value->expired > $currentdate){
	     		$startTimeStamp = strtotime($value->expired);
				$endTimeStamp = strtotime($currentdate);
				$timeDiff = abs($endTimeStamp - $startTimeStamp);
				$numberDays = $timeDiff/86400;  // 86400 seconds in one day
	     		$finalArray[] = array(
		            'username' => $value->username,
		            'days' => intval($numberDays),
		            'expired' => $value->expired,
		            'email' => $value->email,
		          );
		     	}
		     	
		     	}
		     
				/*$finalArray_1 = Array(
				    0 => Array
				        (
				            'username' => 'oglave',
				            'days' => '28',
				            'expired' => '2022-08-07',
				            'email' => 'oglave_13@yahoo.com'
				        ),

				    1 => Array
				        (
				            'username' => 'oglave',
				            'days' => '27',
				            'expired' => '2022-09-31',
				            'email' => 'oglave_13@yahoo.com'
				        ),
				   
				    
				);*/

              /*  echo "<pre>";
		     	print_r($finalArray_1);
		     	echo "</pre>";

		     	echo "<pre>";
		     	print_r($finalArray);
		     	echo "</pre>";
		     	die("test");*/
			    //echo in_array_r("28", $finalArray) ? 'found' : 'not found';
				//$this->load->view('Admin/consultant_list', $data);
				/*	30,23,16,9,7,6,5,4,3,2,1*/
				/*foreach ($finalArray_1 as $key => $value) {
					$days = $value['expired'];
					if($days > $currentdate){
					$startTimeStamp_1 = strtotime($value['expired']);
					$endTimeStamp = strtotime($currentdate);
					$timeDiff = abs($endTimeStamp - $startTimeStamp_1);
					$days = $timeDiff/86400;  // 86400 seconds in one day
					//echo "____";
					if($days == "3"){
					$email_1 = "solutions.provider.dev@gmail.com";
					$email_2 = "oglave_13@yahoo.com";
					$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail_1($email,$email_1,$email_2,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					    echo "Email sent Successfully 3";
					 } elseif ($days == "2") {

					$email_1 = "solutions.provider.dev@gmail.com";
					$email_2 = "oglave_13@yahoo.com";
					$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail_1($email,$email_1,$email_2,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					    echo "Email sent Successfully 2";
					 } 
					 elseif ($days == "1") {

					$email_1 = "solutions.provider.dev@gmail.com";
					$email_2 = "oglave_13@yahoo.com";
					$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail_1($email,$email_1,$email_2,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					    echo "Email sent Successfully 1";
					} 
					elseif ($days == "0") {
					$email_1 = "solutions.provider.dev@gmail.com";
					$email_2 = "oglave_13@yahoo.com";
					$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail_1($email,$email_1,$email_2,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					    echo "Email sent Successfully 0";
					}


					}elseif($days < $currentdate){

						$email_1    = "solutions.provider.dev@gmail.com";
						$email_2    = "oglave_13@yahoo.com";
						$email      = $value['email'];
						$username   = $value['username'];
						$expired    = $value['expired'];
		                $email_temp = $this->getEmailTemp_1('Email for already expired subscription');
						$old        = ["{CompanyAdmin}"];
		                $new        = [$username];
						//Replacing part of string
						$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
						$this->sendemail_1($email,$email_1,$email_2,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
						   echo "Email sent Successfully 3";

					}*/


			foreach ($finalArray as $key => $value) {
				$days = $value['expired'];
				if($days > $currentdate){

				  if($days == "30"){
					$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);

					 }elseif ($days == "23") {
					 	$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					 }
					 elseif ($days == "16") {
					 	$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					 }
					  elseif ($days == "9") {
					 	$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					 }  elseif ($days == "7") {
					 	$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					 }
					  elseif ($days == "6") {
					 	$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					 }
					  elseif ($days == "5") {
					 	$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					 }
					elseif ($days == "4") {
					$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					 }
					elseif ($days == "3") {
					$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					 }
					elseif ($days == "3") {
					$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					 }
					   elseif ($days == "2") {
					 	$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					 } elseif ($days == "1") {
					$email = $value['email'];
					$username = $value['username'];
					$expired = $value['expired'];
	                $email_temp = $this->getEmailTemp_1('Email for expired subscription');
					$old = ["{CompanyAdmin}", "{plandate}"];
	                $new   = [$username, $days];
					// Replacing part of string
					$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
					$this->sendemail($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
					}

					} elseif($days < $currentdate){

						$email_1    = "solutions.provider.dev@gmail.com";
						$email_2    = "oglave_13@yahoo.com";
						$email      = $value['email'];
						$username   = $value['username'];
						$expired    = $value['expired'];
		                $email_temp = $this->getEmailTemp_1('Email for already expired subscription');
						$old        = ["{CompanyAdmin}"];
		                $new        = [$username];
						//Replacing part of string
						$email_temp['message'] = str_replace($old,$new,$email_temp['description']);
						$this->sendemail_1($email,$email_temp['subject'], $email_temp['message'], $email_temp['subject']);
						    echo "Email sent Successfully 3";

					}
				}
			}
#-----------------------------------End--------------------------------------

	public function invoice_add(){
		$admin_id = $this->session->userdata('admin_id');
		if($admin_id){
			$data['title'] = 'Create Invoice';
			$data['super'] = $this->db->select('*')->get('admin')->row();
			$data['admins'] = $this->consultant->getAll();
			$data['plans'] = $this->plan->getAll();
			$this->load->view('Admin/invoice/invoice_add',$data);
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_add_action(){
		$admin_id = $this->session->userdata('admin_id');
		if($admin_id){
			$invoice_data=[
				'admin_id' => $this->input->post('admin_id'),
				'invoice_num' => $this->input->post('invoice_num'),
				'comment' => $this->input->post('comment'),
				'tax_rate' => $this->input->post('tax_rate'),
				'amount' => $this->input->post('total_amount'),
				'create_date' => $this->input->post('create_date'),
				'due_date' => $this->input->post('create_date'),
				'footer_comment' => $this->input->post('footer_comment'),
				'status' => 'pending',
				'payment_type' => strtoupper('Manually')
			];
			$done = $this->db->insert('invoice',$invoice_data);
			if($done){
				$invoice_id = $this->db->insert_id();
				$item_data = array();
				$tax = array();
				$amount = array();
				$description = array();

				$tax = $this->input->post('tax');
				$amount = $this->input->post('amount');
				$description = $this->input->post('description');

				// while(list($key,$val) = each($amount)){
				while (TRUE) {
	                $key = key($amount);
	                if($key === null)
	                    break;
	                $val = current($amount);
					$item_data = [
						'invoice_id' => $invoice_id,
						'description' => $description[$key],
						'amount' => $amount[$key]
					];
					if(isset($tax[$key])){
						if($tax[$key] == 'on'){
							$item_data['is_tax'] = 1;
						}
					}
					$this->db->insert('invoice_item', $item_data);
					next($amount);
				}
				redirect('admin/invoice');
			}else{
				redirect('admin/invoice');
			}
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_paid($id = NULL){
		$admin_id = $this->session->userdata('admin_id');
		if($admin_id){
			$this->db->where('id',$id);
			$this->db->update('invoice',array('status'=>'paid'));
			redirect('admin/invoice');
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_pending($id = NULL) {
		$admin_id = $this->session->userdata('admin_id');
		if($admin_id){
			$this->db->where('id',$id);
			$this->db->update('invoice',array('status'=>'pending'));
			redirect('admin/invoice');
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_edit($id = NULL) {
		$admin_id = $this->session->userdata('admin_id');
		if($admin_id){
			$data['title'] = 'Edit Invoice';
			$data['super'] = $this->db->select('*')->get('admin')->row();
			$invoice_data = $this->db->where('id',$id)->get('invoice')->row();
			$data['invoice'] = $invoice_data;
			$invoice_items = $this->db->where('invoice_id',$id)->get('invoice_item')->result();
			$data['items'] = $invoice_items;
			$admin = $this->db->query("select consultant.*,company.name name,company.phone from consultant left join company on consultant.consultant_id=company.consultant_id 
                    where consultant.consultant_id='$invoice_data->admin_id'")->row();
			$data['customer_admin'] = $admin;
			$this->load->view('Admin/invoice/invoice_edit',$data);
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_edit_action(){
		$admin_id = $this->session->userdata('admin_id');
		if($admin_id){
			$invoice_id = $this->input->post('invoice_id');
			$invoice_data=[
				'admin_id' => $this->input->post('admin_id'),
				'invoice_num' => $this->input->post('invoice_num'),
				'comment' => $this->input->post('comment'),
				'footer_comment' => $this->input->post('footer_comment'),
				'tax_rate' => $this->input->post('tax_rate'),
				'amount' => $this->input->post('total_amount'),
				'create_date' => $this->input->post('create_date'),
				'due_date' => $this->input->post('create_date'),
			];
			$this->db->where('id',$invoice_id);
			$done = $this->db->update('invoice',$invoice_data);
			if($done){
				$this->db->where('invoice_id',$invoice_id);
				$this->db->delete('invoice_item');

				$item_data = array();
				$tax = array();
				$amount = array();
				$description = array();

				$tax = $this->input->post('tax');
				$amount = $this->input->post('amount');
				$description = $this->input->post('description');

				// while(list($key,$val) = each($amount)){
				while (TRUE) {
	                $key = key($amount);
	                if($key === null)
	                    break;
	                $val = current($amount);
					$item_data = [
						'invoice_id' => $invoice_id,
						'description' => $description[$key],
						'amount' => $amount[$key]
					];
					if(isset($tax[$key])){
						if($tax[$key] == 'on'){
							$item_data['is_tax'] = 1;
						}
					}
					$this->db->insert('invoice_item', $item_data);
					next($amount);
				}
				redirect('admin/invoice');
			}else{
				redirect('admin/invoice');
			}
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_pay($id = NULL){
		$admin_id = $this->session->userdata('admin_id');
		if($admin_id){
			$this->db->where('id',$id);
			$this->db->update('invoice',array('status'=>'paid'));
			redirect('admin/invoice');
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_delete($id = NULL){
		$admin_id = $this->session->userdata('admin_id');
		if($admin_id){
			$this->db->where('id',$id);
			$this->db->delete('invoice');
			$this->db->where('invoice_id',$id);
			$this->db->delete('invoice_item');
			redirect('admin/invoice');
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_view($id = NULL){
		$admin_id = $this->session->userdata('admin_id');
		if($admin_id){
			$data['title'] = 'View Invoice';
			$data['super'] = $this->db->select('*')->get('admin')->row();
			$invoice_data = $this->db->where('id',$id)->get('invoice')->row();
			$data['invoice'] = $invoice_data;
			$invoice_items = $this->db->where('invoice_id',$id)->get('invoice_item')->result();
			$data['items'] = $invoice_items;
			$subtotal = 0;
			$taxable = 0;
			$taxdue = 0;
			// while(list($key,$val) = each($invoice_items)){
			while (TRUE) {
                $key = key($invoice_items);
                if($key === null)
                    break;
                $val = current($invoice_items);
				$subtotal = $subtotal + $val->amount;
				if($val->is_tax == 1)
					$taxable = $taxable + $val->amount;
				next($invoice_items);
			}
			$amount_list = array();
			$amount_list = [
				'subtotal' => $subtotal,
				'taxable' => $taxable,
				'taxdue' => $taxable*$invoice_data->tax_rate/100
			];
			$data['amount_list'] = $amount_list;
			//$admin = $this->db->select('id,admin_name,company_name,address,city,phone,fax,logo')->where('id',$invoice_data->admin_id)->where('is_admin',1)->get('admin')->row();
			$admin = $this->db->query("select consultant.*,company.name name,company.phone from consultant left join company on consultant.consultant_id=company.consultant_id 
                    where consultant.consultant_id='$invoice_data->admin_id'")->row();
			$data['customer_admin'] = $admin;
			$this->load->view('Admin/invoice/invoice_view',$data);
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_pdf(){
		//$out_html = $this->input->post('pdf_val');
		$id = $this->input->post('view_invoice_id');
		$admin_id = $this->session->userdata('admin_id');
		if($admin_id){
			$super = $this->db->select('*')->get('admin')->row();
			$invoice = $this->db->where('id',$id)->get('invoice')->row();
			$items = $this->db->where('invoice_id',$id)->get('invoice_item')->result();
			$subtotal = 0;
			$taxable = 0;
			$taxdue = 0;
			// while(list($key,$val) = each($items)){
			while (TRUE) {
                $key = key($items);
                if($key === null)
                    break;
                $val = current($items);
				$subtotal = $subtotal + $val->amount;
				if($val->is_tax == 1)
					$taxable = $taxable + $val->amount;
				next($items);
			}
			$amount_list = array();
			$amount_list = [
				'subtotal' => $subtotal,
				'taxable' => $taxable,
				'taxdue' => $taxable*$invoice->tax_rate/100,
			];
			$customer_admin = $this->db->query("select consultant.*,company.name name,company.phone from consultant left join company on consultant.consultant_id=company.consultant_id 
                    where consultant.consultant_id='$invoice->admin_id'")->row();

			$out_html = '';
			$out_html = '<table style="width:100%;border:none!important">';
							$out_html .= '<tr>';
								$out_html .= '<td style="width:50%;vertical-align:top">';
									$out_html .= '<h1 style="padding-left:10px!important;">'.$super->company_name.'</h1>';
									$out_html .= '<p style="margin:1px;padding-left:10px!important;">'.$super->address.'</p>';
									$out_html .= '<p style="margin:1px;padding-left:10px!important;">'.$super->city.'</p>';
									$out_html .= '<p style="margin:1px;padding-left:10px!important;">'.$super->phone.'</p>';
									$out_html .= '<p style="margin:1px;padding-left:10px!important;">'.$super->fax.'</p>';
									$out_html .= '<h3 style="margin-bottom:5px;padding-left:10px;width:50%;color:#A5A0A0">Bill To:</h3>';
									$out_html .= '<p style="margin:1px;padding-left:10px;!important">'.$customer_admin->consultant_name.'</p>';
									$out_html .= '<p style="margin:1px;padding-left:10px;!important">'.$customer_admin->address.'</p>';
									$out_html .= '<p style="margin:1px;padding-left:10px;!important">'.$customer_admin->city.'</p>';
									$out_html .= '<p style="margin:1px;padding-left:10px;!important">'.$customer_admin->phone.'</p>';
								$out_html .= '</td>';
								$out_html .= '<td style="width:50%;vertical-align:top;text-align:right">';
									$out_html .= '<h1 style="font-size: 25px;color: #8796C5;padding-right:30px" >INVOICE</h1>';
									$out_html .= '<p style="margin:1px;">Invoice Date:'.$invoice->create_date.'</p>';
									$out_html .= '<p style="margin:1px;">INVOICE:'.$invoice->invoice_num.'</p>';
								$out_html .= '</td>';
							$out_html .= '</tr>';	
						$out_html .= '</table>';
						$out_html .= '<table style="border:none!important;width:100%!important;margin-top:20px">';
							$out_html .= '<tr>';
								$out_html .= '<td style="background-color:#F8F8F8!important;border:none!important;padding:10px!important;text-align:center">Description</td>';
								$out_html .= '<td style="background-color:#F8F8F8!important;border:none!important;padding:10px!important;text-align:center">Taxed</td>';
								$out_html .= '<td style="background-color:#F8F8F8!important;border:none!important;padding:10px!important;text-align:center">Amount</td>';
							$out_html .= '</tr>';
							$index=0;
							foreach($items as $item){ 
							$out_html .= '<tr>';
								$out_html .= '<td style="border-bottom:1px solid #eee;padding:2px!important;text-align:center">'.$item->description.'</td>';
								if($item->is_tax == 0){
								$out_html .= '<td style="border-bottom:1px solid #eee;padding:2px!important;text-align:center;color:red">No</td>';
								}
								if($item->is_tax == 1){
								$out_html .= '<td style="border-bottom:1px solid #eee;padding:2px!important;important;text-align:center;color:green">Yes</td>';
								}
								$out_html .= '<td style="border-bottom:1px solid #eee;padding:2px!important;important;text-align:center">$'.$item->amount.'</td>';	
							$out_html .= '</tr>';
							$index++;
							}
						$out_html .= '</table>';
						$out_html .= '<table style="width:100%;border:none!important;margin-top:20px">';
							$out_html .= '<tr>';
								$out_html .= '<td style="width:65%;vertical-align:top;">';
								$out_html .= '<table style="width:70%;border:none!important;">';
									$out_html .= '<tr>';
										$out_html .= '<td style="vertical-align:top;text-align:left;width:100%;">';
											$out_html .= '<h3 style="margin:0;padding:10px;width:90%;background-color:#F8F8F8">Other Comments</h3>';
										$out_html .= '</td>';
									$out_html .= '</tr>';
									$out_html .= '<tr>';
										$out_html .= '<td style="margin-top:10px;white-space:pre-line;vertical-align:top;text-align:left;width:100%;word-break:break-word;">'.$invoice->comment.'</td>';
									$out_html .= '</tr>';
								$out_html .= '</table>';
								$out_html .= '</td>';
								$out_html .= '<td style="width:35%;vertical-align:top;text-align:right">';
								$out_html .= '<table style="width:100%;border:none!important;">';
									$out_html .= '<tr>';
										$out_html .= '<td style="text-align:right;border-bottom:1px solid #eee;padding:10px;width:50%;">Sub Total:</td>';
										$out_html .= '<td style="text-align:center;border-bottom:1px solid #eee;padding:10px;width:50%">$'.$amount_list['subtotal'].'</td>';
									$out_html .= '</tr>';
									$out_html .= '<tr>';
										$out_html .= '<td style="text-align:right;border-bottom:1px solid #eee;padding:10px;width:50%;">Taxable:</td>';
										$out_html .= '<td style="text-align:center;border-bottom:1px solid #eee;padding:10px;width:50%">$'.$amount_list['taxable'].'</td>';
									$out_html .= '</tr>';
									$out_html .= '<tr>';
										$out_html .= '<td style="text-align:right;border-bottom:1px solid #eee;padding:10px;width:50%;">Tax Rate:</td>';
										$out_html .= '<td style="text-align:center;border-bottom:1px solid #eee;padding:10px;width:50%">$'.$invoice->tax_rate.' (%)</td>';
									$out_html .= '</tr>';
									$out_html .= '<tr>';
										$out_html .= '<td style="text-align:right;border-bottom:1px solid #eee;padding:10px;width:50%;">Tax Due:</td>';
										$out_html .= '<td style="text-align:center;border-bottom:1px solid #eee;padding:10px;width:50%">$'.$amount_list['taxdue'].'</td>';
									$out_html .= '</tr>';
									$out_html .= '<tr>';
										$out_html .= '<td style="text-align:right;padding:10px;width:50%;"><h4 style="margin:0;font-size:20px;font-weight:400;">Total Due:</h4></td>';
										$out_html .= '<td style="text-align:center;padding:10px;width:50%"><h4 style="margin:0;font-size:20px;font-weight:400;">$'.$invoice->amount.'</h4></td>';
									$out_html .= '</tr>';
								$out_html .= '</table>';
								$out_html .= '</td>';
							$out_html .= '</tr>';	
						$out_html .= '</table>';
			$out_html .= '<div style="width:100%!important;text-align:center;white-space:pre;font-family:monospace;padding-top:30px">';
			$out_html .= ''.$invoice->footer_comment.'';
			$out_html .= '</div>';

		    $this->load->library('html2pdf');
		    $this->html2pdf->folder($_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/doc/');
		    $this->html2pdf->filename('invoice.pdf');
		    $this->html2pdf->paper('a4', 'portrait');
		    $this->html2pdf->html($out_html);
		    if($this->html2pdf->create('download')) {
		    	echo 'PDF saved';
		    }
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_to_pdf($id = NULL){
			$data['super'] = $this->db->select('username,email,company_name,address,city,phone,fax,logo')->where('is_admin',0)->get('admin')->row();
			$invoice_data = $this->db->where('id',$id)->get('invoice')->row();
			$data['invoice'] = $invoice_data;
			$invoice_items = $this->db->where('invoice_id',$id)->get('invoice_item')->result();
			$data['items'] = $invoice_items;
			$subtotal = 0;
			$taxable = 0;
			$taxdue = 0;
			// while(list($key,$val) = each($invoice_items)){
			while (TRUE) {
                $key = key($invoice_items);
                if($key === null)
                    break;
                $val = current($invoice_items);
				$subtotal = $subtotal + $val->amount;
				if($val->is_tax == 1)
					$taxable = $taxable + $val->amount;
				next($invoice_items);
			}
			$amount_list = array();
			$amount_list = [
				'subtotal' => $subtotal,
				'taxable' => $taxable,
				'taxdue' => $taxable*$invoice_data->tax_rate/100
			];
			$data['amount_list'] = $amount_list;
			$admin = $this->db->select('id,admin_name,company_name,address,city,phone,fax,logo')->where('id',$invoice_data->admin_id)->where('is_admin',1)->get('admin')->row();
			$data['customer_admin'] = $admin;
			$this->load->view('Admin/invoice/invoice_view_pdf',$data);
	}


	public function update_status($id) {
		$upArr = array('is_active' => $_GET['is_active']);
		$this->db->where('consultant_id', $id);
		$done = $this->db->update('consultant', $upArr);

		if($done) {
			$this->session->set_flashdata('message', 'update_success');
			redirect('Admin/consultant_list');
		}
	}
    public function update_otp_setting(){
        $response = array('success' => false, 'message' => 'action not allowed');
        if ($this->session->userdata('user_type') != 'admin'){
            echo json_encode($response);
            return false;
        }
        $status = $this->input->post('status');
        $this->db->where('id', 1);
        if ($this->db->update('default_setting', array('otp_verification' => $status))){
            $response['success'] = true;
            $response['message'] = 'OTP Verification Status Changed';
        }
        echo json_encode($response);
        return true;
    }
}
