<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/BaseController.php';
class Superadmin extends BaseController
{
	public function __construct(){
		parent::__construct();

		$this->load->library('session');
	}
	public function plan_list()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		if ($superadmin_id) {
			$data['title'] = "Plans";
			$data['plans'] = $this->db->get('plan')->result();
			$this->load->view('Superadmin/plan_list', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function findplan()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		if ($superadmin_id) {
			$id = $this->input->post('id');
			$this->db->where('id', $id);
			$done = $this->db->get('plan')->row();
			echo json_encode($done);
		} else {
			redirect('Welcome');
		}
	}
	public function findcomp()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		if ($superadmin_id) {
			$id = $this->input->post('id');
			$this->db->where('id', $id);
			$done = $this->db->get('admin')->row();
			$done->expired = date('m/d/Y', strtotime($done->expired));
			echo json_encode($done);
		} else {
			redirect('Welcome');
		}
	}

	public function edit_plan()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		if ($superadmin_id) {

			$plan_id      = $this->input->post('plan_id1');
			$price_type     = $this->input->post('price_type');
			$cs_limit   = $this->input->post('cs_limit');
			$cp_limit   = $this->input->post('cp_limit');
			$preq_limit   = $this->input->post('preq_limit');
			$price = $this->input->post('price');

			$created_at   = date('Y-m-d');

			if($price_type == 1){
				$data = array(
				'cs_limit' => $cs_limit,
				'cp_limit' => $cp_limit,
				'preq_limit' => $preq_limit
				);
			}elseif($price_type == 2){
				$data = array(
				'price' => $price
				);
			}else{
				$data = array(
				'cs_limit' => $cs_limit,
				'cp_limit' => $cp_limit,
				'preq_limit' => $preq_limit,
				'price' => $price
				);
			}
			$this->db->where('id', $plan_id);
			$done = $this->db->update('plan', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Superadmin/plan_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Superadmin/plan_list');
			}
		} else {
			redirect('Welcome');
		}
	}
	public function admin_list()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		if ($superadmin_id) {
			$data['title']   = "Admin Mangement";
			//$this->db->where('plan_id !=','');
			$this->db->where('is_admin','1');
			$data['admin'] = $this->db->get('admin')->result();
			$data['plan'] = $this->db->get('plan')->result();
			$this->load->view('Superadmin/admin_list', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function delete_admin($id = Null)
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		if ($superadmin_id) {

            $this->db->where('admin_id', $id);
            $this->db->delete('payment');

            $this->db->where('admin_id', $id);
            $this->db->delete('purchase_plan');

			$this->db->where('admin_id', $id);
			$this->db->delete('company');

			$this->db->where('id', $id);
			$this->db->delete('admin');

			//relative data delete
			$delete_consultant = $this->db->where('admin_id', $id)->get('consultant')->result_array();
			$this->db->where('admin_id', $id);
            $this->db->delete('consultant');

			// while(list($key,$val) = each($delete_consultant)){
            while(TRUE){
            	$key = key($delete_consultant);
                if($key === null)
                    break;
                $val = current($delete_consultant);
	            $this->db->where('consultant', $val['consultant_id']);
		        $this->db->delete('inbox');

		        $this->db->where('company_id', $val['consultant_id']);
		        $this->db->delete('clause');

		        $this->db->where('company_id', $val['consultant_id']);
		        $delete_standard = $this->db->get('standard')->result_array();
		        $this->db->where('company_id', $val['consultant_id']);
		        $this->db->delete('standard');
		        $delete_stantdard_ids = array();
		        
		        while (TRUE) {
                    $key = key($delete_standard);
                    if($key === null)
                        break;
                    $val = current($delete_standard);
                    $delete_stantdard_ids[] = $val['s_id'];
                    next($delete_standard);
                }
		        // while(list($key, $val) = each($delete_standard)){
		        //     $delete_stantdard_ids[] = $val['s_id'];
		        // }
		        if(!empty($delete_stantdard_ids)){
		            $this->db->where_in('standard_id',$delete_stantdard_ids);
		            $this->db->delete('standard_add1');
		            $this->db->where_in('standard_id',$delete_stantdard_ids);
		            $this->db->delete('standard_add2');
		            $this->db->where_in('standard_id',$delete_stantdard_ids);
		            $this->db->delete('standard_add3');
		        }

		        $this->db->where('consultant_id',$val['consultant_id']);
		        $delete_company = $this->db->get('company')->result_array();
		        $this->db->where('consultant_id',$val['consultant_id']);
		        $this->db->delete('company');

		        $delete_company_ids = array();

		        while (TRUE) {
                    $key = key($delete_company);
                    if($key === null)
                        break;
                    $val = current($delete_company);
                    $delete_company_ids[] = $val['id'];
                    next($delete_company);
                }
		        // while(list($key, $val) = each($delete_company)){
		        //     $delete_company_ids[] = $val['id'];
		        // }
		        if(!empty($delete_company_ids)){
		            $this->db->where_in('company_id',$delete_company_ids);
		            $this->db->delete('fssc_requirements');
		            $this->db->where_in('company_id',$delete_company_ids);
		            $this->db->delete('corrective_action');
		            $this->db->where_in('company_id',$delete_company_ids);
		            $this->db->delete('employees');

		        }
		        next($delete_consultant);
            }

			$this->session->set_flashdata('message', 'success_del');
			redirect('Superadmin/admin_list');
		} else {
			redirect('Welcome');
		}
	}

	public function payment_list()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		if ($superadmin_id) {
			$data['title']   = "Payment History";
			$data['payment'] = $this->db->get('payment')->result();
			$this->load->view('Superadmin/payment_list', $data);
		} else {
			redirect('Welcome');
		}
	}


	public function edit_profile()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		if ($superadmin_id) {

			$this->db->where('id', $superadmin_id);
			$data['profile'] = $this->db->get('admin')->row();
			$data['title']   = "Edit Profile";
			$this->load->view('Superadmin/edit_profile', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function update_profile()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		$username = $this->input->post('username');
		$email    = $this->input->post('email');
        /*=-=- check user mobile number valid start =-=-*/
        $this->load->library('phone_RK');
        $phone        = $this->input->post('phone');
        $phone_response = $this->phone_rk->checkPhoneNumber($phone);
        if (!$phone_response['success']){
            $this->session->set_flashdata('phone_response', $phone_response);
            redirect('Superadmin/edit_profile');
            return;
        }
        /*=-=- check user mobile number valid end =-=-*/
		$company_name = $this->input->post('company_name');
		$phone    = $this->input->post('phone');
		$fax    = $this->input->post('fax');
		$address    = $this->input->post('address');
		$city    = $this->input->post('city');
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
            $picture = @$this->db->query("SELECT * FROM admin WHERE id='$admin_id'")->row()->logo;
        }
		$up       = array(
			'username' => $username,
			'email' => $email,
			'company_name' => $company_name,
			'phone' => $phone,
			'fax' => $fax,
			'address' => $address,
			'city' => $city,
			'logo' => $picture
		);

		if ($superadmin_id) {
			$this->db->where('id', $superadmin_id);
			$done = $this->db->update('admin', $up);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Superadmin/edit_profile');
			} else {
				redirect('Superadmin/edit_profile');
			}

		} else {
			redirect('Welcome');
		}
	}

    public function update_password() {
        $param  = $this->input->post();
        $this->load->model('Adminmodel');
        $user   = $this->session->userdata();
        $user   = $this->Adminmodel->get_admin($user['superadmin_id']);

        if ($user){
            if (!verifyHashedPassword($param['old_password'], $user->password)){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'Old Password did\'nt matched'));
                redirect('Superadmin/edit_profile');
            }
            if (empty(trim($param['password'])) && empty(trim($param['repassword']))){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'New Password Cannot be Empty'));
                redirect('Superadmin/edit_profile');
            }
            if ($param['password'] != $param['repassword']){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'New Password didn\'t matched with confirm password'));
                redirect('Superadmin/edit_profile');
            }
            $password   = getHashedPassword($param['password']);
            $result     = $this->Adminmodel->update_admin(array('password' => $password), $user->id);
            if ($result){
                $this->session->set_flashdata('password', array('success' => true, 'message' => 'Password Updated Successfully'));
            }else{
                $this->session->set_flashdata('password', array('success' => true, 'message' => 'Password Not Updated'));
            }

            redirect('Superadmin/edit_profile');
        }else{
            redirect('Welcome');
        }
    }

	public function default_logo()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		if ($superadmin_id) {
			$this->db->where('id', '1');
			$data['logo']  = $this->db->get('default_setting')->row();
			$data['title'] = "Default Logo";
			$this->load->view('Superadmin/default_logo', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function update_default()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');

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

		if ($superadmin_id) {
			$this->db->where('id', '1');
			$done = $this->db->update('default_setting', $up);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Superadmin/default_logo');
			} else {
				redirect('Superadmin/default_logo');
			}

		} else {
			redirect('Welcome');
		}
	}

	public function edit_admin()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		$date = date('Y-m-d');
		if ($superadmin_id) {
			$admin_name = $this->input->post('admin_name');
			$username     = $this->input->post('username');
			$email        = $this->input->post('email');
            /*=-=- check user mobile number valid start =-=-*/
            $this->load->library('phone_RK');
            $phone        = $this->input->post('phone');
            $phone_response = $this->phone_rk->checkPhoneNumber($phone);
            if (!$phone_response['success']){
                $this->session->set_flashdata('phone_response', $phone_response);
                redirect('Superadmin/admin_list');
                return;
            }
            /*=-=- check user mobile number valid end =-=-*/
			$password     = getHashedPassword($this->input->post('password'));
			$admin_id = $this->input->post('admin_id');
			$plan_id     = $this->input->post('plan');
			if($plan_id > 0){
				$plan_row = $this->db->where('id',$plan_id)->get('plan')->row();
				if($plan_row->price_type == 1){
					$plan_type = 'trial';
					$expired = date('Y-m-d', strtotime($date . ' + 14 days'));
				}else{
					$plan_type = 'real';
					//$plan_type = 'pending';
					//$expired = NULL;
					if($plan_row->term_type == 0){
						$expired = date('Y-m-d', strtotime($date . ' + 30 days'));
					}
					if($plan_row->term_type == 1){
						$expired = date('Y-m-d', strtotime($date . ' + 365 days'));
					}
				}
			}elseif($plan_id == 0){
				$plan_id = NULL;
			}
			//$expired = date('Y-m-d', strtotime($this->input->post('expired_date')));
			$data = array(
				'admin_name' => $admin_name,
				'username' => $username,
				'email' => $email,
                'phone' => $phone,
				'password' => $password,
				'plan_id' => $plan_id,
				'plan_type' => $plan_type,
				'expired' => $expired
			);
            //check main otp verification status
            if ($this->settings->otp_verification){
                $data['otp_status'] = $this->input->post('otp_status');
            }

            if (empty(trim($this->input->post('password')))){
                unset($data['password']);
            }
			$this->db->where('id', $admin_id);
			$done = $this->db->update('admin', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Superadmin/admin_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Superadmin/admin_list');
			}
		} else {
			redirect('Welcome');
		}
	}


	public function add_admin()
	{
		$superadmin_id = $this->session->userdata('superadmin_id');
		$date = date('Y-m-d');
		if ($superadmin_id) {
			$admin_name = $this->input->post('admin_name');
			$username     = $this->input->post('username');
			$email        = $this->input->post('email');
            /*=-=- check user mobile number valid start =-=-*/
            $this->load->library('phone_RK');
            $phone        = $this->input->post('phone');
            $phone_response = $this->phone_rk->checkPhoneNumber($phone);
            if (!$phone_response['success']){
                $this->session->set_flashdata('phone_response', $phone_response);
                redirect('Superadmin/admin_list');
                return;
            }
            /*=-=- check user mobile number valid end =-=-*/
			$password     = getHashedPassword($this->input->post('password'));
			$plan_id     = $this->input->post('plan');

			//---------------------send email to user(admin)-----------------------
			$email_temp = $this->getEmailTemp('Super Admin assign subscription');
			$email_temp['message'] = str_replace("{USERNAME}", $username, $email_temp['message']);
			$email_temp['message'] = str_replace("{COURSE_NAME}", 'isogapauditsoftware.com', $email_temp['message']);
			$email_temp['message'] = str_replace("{firstname1}", 'firstname1', $email_temp['message']);
			$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
			$this->sendemail($email, 'Super Admin assigned a subscription', $email_temp['message'], $email_temp['subject']);
            //---------------------------------------------- send sms ----------------------------------------------
            if (!empty($phone) && $this->settings->otp_verification){
                $phone = formatMobileNumber($phone, true);
                /*=-=- check user mobile number valid start =-=-*/
                $phone_response = $this->phone_rk->checkPhoneNumber($phone);
                if ($phone_response['success']){
                    $message = "Hi {$username}".PHP_EOL;
                    $message.= "Congratulations you have been assigned to ".APP_NAME." Quality Circle's Process Risk(Strategic and Operational) Based Implementation Software. The software is the first of its kind globally. It is a cloud based automated tool which enables users to monitor established controls so data can be harvested for useful analytics and evaluation by top management to drive continual improvement.";
                    $this->twill_rk->sendMsq($phone,$message);
                }
            }

			//---------------------------------------------------------------------

			if($plan_id > 0){
				$plan_row = $this->db->where('id',$plan_id)->get('plan')->row();
				if($plan_row->price_type == 1){
					$plan_type = 'trial';
					$expired = date('Y-m-d', strtotime($date . ' + 14 days'));
				}else{
					$plan_type = 'real';
					//$plan_type = 'pending';
					//$expired = NULL;
					if($plan_row->term_type == 0){
						$expired = date('Y-m-d', strtotime($date . ' + 30 days'));
					}
					if($plan_row->term_type == 1){
						$expired = date('Y-m-d', strtotime($date . ' + 365 days'));
					}
				}
			}elseif($plan_id == 0){
				$plan_id = NULL;
			}
			//$expired = date('Y-m-d', strtotime($this->input->post('expired_date')));
			$data = array(
				'admin_name' => $admin_name,
				'username' => $username,
				'email' => $email,
				'password' => $password,
                'phone' => $phone,
				'status' => '1',
				'plan_id' => $plan_id,
				'plan_type' => $plan_type,
				'expired' => $expired
			);
            //check main otp verification status
            if ($this->settings->otp_verification){
                $data['otp_status'] = $this->input->post('otp_status');
            }
			$done = $this->db->insert('admin', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Superadmin/admin_list');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Superadmin/admin_list');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function invoice(){
		$superadmin_id = $this->session->userdata('superadmin_id');
		$start_date = $this->input->post('filter_start');
		$end_date = $this->input->post('filter_end');
		$date = date('Y-m-d');
		if($start_date == NULL){
			$start_date = date('Y-m-d', strtotime($date . ' - 29 days'));
		}
		if($end_date == NULL){
			$end_date = date('Y-m-d');
		}
		if($superadmin_id){
			$data['title'] = 'Invoice';
			$this->db->order_by('create_date','DESC');
			$data['invoices'] = $this->db->where('create_date >=', $start_date)->where('create_date <=',$end_date)->get('invoice')->result();
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
			$this->load->view('Superadmin/invoice/invoice_list',$data);
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_add(){
		$superadmin_id = $this->session->userdata('superadmin_id');
		if($superadmin_id){
			$data['title'] = 'Create Invoice';
			$data['super'] = $this->db->select('username,email,company_name,address,city,phone,fax,logo')->where('id',$superadmin_id)->where('is_admin',0)->get('admin')->row();
			$admins = $this->db->select('id,admin_name,company_name,address,city,phone,fax,logo')->where('is_admin',1)->get('admin')->result_array();
			$admin_array = array();
			
			while (TRUE) {
                $key = key($admins);
                if($key === null)
                    break;
                $val = current($admins);
                $admin_array[$val['id']] = $val;
                next($admins);
            }
			// while (list($key,$val) = each($admins)) {
			// 	$admin_array[$val['id']] = $val;
			// }
			$data['admins'] = $admin_array;
			$this->load->view('Superadmin/invoice/invoice_add',$data);
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_add_action(){
		$superadmin_id = $this->session->userdata('superadmin_id');
		if($superadmin_id){
			$invoice_data=[
				'admin_id' => $this->input->post('admin_id'),
				'invoice_num' => $this->input->post('invoice_num'),
				'comment' => $this->input->post('comment'),
				'tax_rate' => $this->input->post('tax_rate'),
				'amount' => $this->input->post('total_amount'),
				'create_date' => $this->input->post('create_date'),
				'due_date' => $this->input->post('create_date'),
				'footer_comment' => $this->input->post('footer_comment'),
				'status' => 'pending'
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
				while(ture){
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
				redirect('superadmin/invoice');
			}else{
				redirect('superadmin/invoice');
			}
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_edit($id = NULL){
		$superadmin_id = $this->session->userdata('superadmin_id');
		if($superadmin_id){
			$data['title'] = 'Edit Invoice';
			$data['super'] = $this->db->select('username,email,company_name,address,city,phone,fax,logo')->where('id',$superadmin_id)->where('is_admin',0)->get('admin')->row();
			$invoice_data = $this->db->where('id',$id)->get('invoice')->row();
			$data['invoice'] = $invoice_data;
			$invoice_items = $this->db->where('invoice_id',$id)->get('invoice_item')->result();
			$data['items'] = $invoice_items;
			$admin = $this->db->select('id,admin_name,company_name,address,city,phone,fax,logo')->where('id',$invoice_data->admin_id)->where('is_admin',1)->get('admin')->row();
			$data['customer_admin'] = $admin;

			$this->load->view('Superadmin/invoice/invoice_edit',$data);
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_edit_action(){
		$superadmin_id = $this->session->userdata('superadmin_id');
		if($superadmin_id){
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
				while(TRUE){
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
				redirect('superadmin/invoice');
			}else{
				redirect('superadmin/invoice');
			}
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_pay($id = NULL){
		$superadmin_id = $this->session->userdata('superadmin_id');
		if($superadmin_id){
			$this->db->where('id',$id);
			$this->db->update('invoice',array('status'=>'paid'));
			redirect('superadmin/invoice');
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_delete($id = NULL){
		$superadmin_id = $this->session->userdata('superadmin_id');
		if($superadmin_id){
			$this->db->where('id',$id);
			$this->db->delete('invoice');
			$this->db->where('invoice_id',$id);
			$this->db->delete('invoice_item');
			redirect('superadmin/invoice');
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_view($id = NULL){
		$superadmin_id = $this->session->userdata('superadmin_id');
		if($superadmin_id){
			$data['title'] = 'View Invoice';
			$data['super'] = $this->db->select('username,email,company_name,address,city,phone,fax,logo')->where('is_admin',0)->get('admin')->row();
			$invoice_data = $this->db->where('id',$id)->get('invoice')->row();
			$data['invoice'] = $invoice_data;
			$invoice_items = $this->db->where('invoice_id',$id)->get('invoice_item')->result();
			$data['items'] = $invoice_items;
			$subtotal = 0;
			$taxable = 0;
			$taxdue = 0;
			// while(list($key,$val) = each($invoice_items)){
			while(TRUE){
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
			$this->load->view('Superadmin/invoice/invoice_view',$data);
		}else{
			redirect('Welcome');
		}
	}
	public function invoice_pdf(){
		//$out_html = $this->input->post('pdf_val');
		$id = $this->input->post('view_invoice_id');
		$superadmin_id = $this->session->userdata('superadmin_id');
		$admin_id = $this->session->userdata('admin_id');
		if($superadmin_id || $admin_id){
			$super = $this->db->select('username,email,company_name,address,city,phone,fax,logo')->where('is_admin',0)->get('admin')->row();
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
			$customer_admin = $this->db->select('id,admin_name,company_name,address,city,phone,fax,logo')->where('id',$invoice->admin_id)->where('is_admin',1)->get('admin')->row();
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
									$out_html .= '<p style="margin:1px;padding-left:10px;!important">'.$customer_admin->admin_name.'</p>';
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

		    $this->load->library('Html2pdf');
		    $this->html2pdf->folder($_SERVER['DOCUMENT_ROOT'].'/OBS/uploads/doc/');
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
			while(TRUE){
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
			$this->load->view('Superadmin/invoice/invoice_view_pdf',$data);
	}

	public function update_status($id) {
		$upArr = array('is_active' => $_GET['is_active']);
		$this->db->where('id', $id);
		$done = $this->db->update('admin', $upArr);

		if($done) {
			$this->session->set_flashdata('message', 'update_success');
			redirect('Superadmin/admin_list');
		}
	}

    public function update_otp_setting(){
        $response = array('success' => false, 'message' => 'action not allowed');
        if ($this->session->userdata('user_type') != 'superadmin'){
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

	public function insert_email_template () {
		$insArr = array(
						'subject' => 'Forgot Password Recovery',
						'message' => '<!DOCTYPE html>
<html>
<head>
  <title>table</title> 
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Montserrat, sans-serif;
    }
  </style>  
</head>
<body>

<div style="padding: 0px">

<div style="width: 100%;float: left;">
  <table style="border: none;width: 100%;max-width: 500px;margin: auto;">
    <tr>
      <td><img src="https://isogapauditsoftware.com/assets/images/forgot-pass.png" style="width: 100%;"></td>
    </tr>
    <tr>
      <td><p style="margin: 50px 0 20px 0;text-align: center;padding: 0 0 0 0;font-size: 35px;font-weight: bold;color: #532280;">Got Yourself Locked Out?</p></td>
    </tr>
    <tr>
      <td><p style="margin: 0 0 0 0;text-align: center;padding: 0 0 0 0;font-size: 17px;">No worries - we"ll help you retrieve your keys(AKA account password), and get you back on the road again in no time.</p></td>
    </tr>
    <tr>
      <td><a href="{forgot_pass_link}" style="background: #532280;text-decoration: none;color: #fff;font-weight: bold;font-size: 17px;padding: 10px 20px;border-radius: 10px;width: 100%;display: block;text-align: center;max-width: 200px;margin: auto;margin-top: 15px;margin-bottom: 15px;text-transform: uppercase;">Change Password</a></td>
    </tr>
    <tr>
      <td><p style="margin: 0 0 0 0;text-align: center;padding: 0 0 60px 0;font-size: 17px;">Button not working ? Copy and paste this URL into your browser instead : {forgot_pass_link} </p></td>
    </tr>
  </table>
</div>

<div class="clearfix"></div>

</div>
</body>
</html>
',
'action' => 'forgot password recovery'
					   );

	$done = $this->db->insert('settings_email_template', $insArr);

	}
}
