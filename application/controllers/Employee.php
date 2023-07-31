<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/BaseController.php';
class Employee extends BaseController//CI_Controller
{

	public function __construct(){
		parent::__construct();

		$this->load->library('session');
	}

	public function edit_profile()
	{
		$employee_id = $this->session->userdata('employee_id');
		if ($employee_id) {

			$this->db->where('employee_id', $employee_id);
			$data['profile'] = $this->db->get('employees')->row();
			$data['title']   = "Edit Profile";
			$this->load->view('employee/edit_profile', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function update_profile()
	{
		$employee_id = $this->session->userdata('employee_id');
		$employee_email = $this->input->post('employee_email');
		$username = $this->input->post('username');
		$password = getHashedPassword($this->input->post('password'));
        /*=-=- check user mobile number valid start =-=-*/
        $this->load->library('phone_RK');
        $phone          = formatMobileNumber($this->input->post('employee_phone'));;
        $phone_response = $this->phone_rk->checkPhoneNumber($phone);
        if (!$phone_response['success']){
            $this->session->set_flashdata('phone_response', $phone_response);
            redirect($_SERVER['HTTP_REFERER']);
            return;
        }
        /*=-=- check user mobile number valid end =-=-*/
		$up = array(
			'username' => $username,
			'password' => $password,
			'employee_email' => $employee_email,
            'employee_phone' => $phone
		);
        if (empty(trim($this->input->post('password')))){
            unset($up['password']);
        }
		if ($employee_id) {
			$this->db->where('employee_id', $employee_id);
			$done = $this->db->update('employees', $up);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('employee/edit_profile');
			} else {
				redirect('employee/edit_profile');
			}

		} else {
			redirect('Welcome');
		}
	}

	public function corrective_message()
	{
		$data['dd1'] = 'active';
		$data['d1'] = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$user_type = $this->session->userdata('user_type');
		if ($employee_id) {
			$data['title'] = "Corrective Action Inbox";

			if($user_type == 'Lead Auditor') {
				$where = "";
			} else {
				$where = " AND (corrective.auditor_id = " . $employee_id . " OR corrective.process_owner = " . $employee_id . ")";
			}

			$sql = "SELECT corrective.create_at,
                                corrective.id,
                                corrective.audit_criteria,
                                corrective.mashine_clause,
                                corrective.prob_desc,
		                        t.trigger_name,
                                corrective.auditor_id,
                                (SELECT employee_name FROM employees WHERE corrective.auditor_id = employees.employee_id) AS auditee_name,
                                (SELECT employee_name FROM employees WHERE corrective.process_owner = employees.employee_id) AS process_owner_name
                        FROM
                                corrective_action_data AS corrective
                        LEFT JOIN `trigger` t ON corrective.trigger_id = t.trigger_id
                        WHERE
                                corrective.id IN (
                                        SELECT corrective_id FROM corrective_message WHERE company_id = " . $consultant_id . "
                                )" . $where . "
                        ORDER BY corrective.id DESC";

			$data['corrective_message'] = $this->db->query($sql)->result();
			$this->load->view('employee/corrective_message', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function show_corrective_message($id = '')
	{
		$data['dd1'] = 'active';
		$data['d1'] = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$this->db->where('corrective_id', $id);
			$this->db->where('company_id', $consultant_id);
			$this->db->order_by('date_time', 'asc');
			$data['message'] = $this->db->get('corrective_message')->result();
			$data['title']   = "Messages";
			$this->db->where('id', $id);
			$data['standalone_data'] = $this->db->get('corrective_action_data')->row();
			$data['corrective_id'] = $id;
			$this->load->view('employee/show_corrective_message', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function send_corrective_message() {
		$message = $this->input->post('message');
		$corrective_id = $this->input->post('corrective_id');
		$employee_id = $this->session->userdata('employee_id');
		$user_name = $this->session->userdata('username');
		$consultant_id = $this->session->userdata('consultant_id');
		$user_type = $this->session->userdata('user_type');
		$process_id	= $this->input->post('process_id');
		$auditor_id = $this->input->post('auditor_id');
		$process_owner_id = $this->input->post('process_owner_id');
		if ($employee_id) {
			$created_at = date('Y-m-d h:i:s');

			$data = array(
				'company_id' => $consultant_id,
				'sender_id' => $employee_id,
				'sender_role' => $user_type,
				'message' => $message,
				'date_time' => $created_at,
				'corrective_id' => $corrective_id
			);

			//--------------------get lead auditor & auditor & process owner info--------------------
			$audit_id = $this->db->where('log_id', $process_id)->get('audit_log_list')->row()->audit_id;
			$lead_auditor_id = $this->db->where('pa_id', $audit_id)->get('audit_list')->row()->lead_auditor;
			$lead_auditor_info = $this->db->where('employee_id', $lead_auditor_id)->get('employees')->row();
			$auditor_info = $this->db->where('employee_id', $auditor_id)->get('employees')->row();
			$process_owner_info = $this->db->where('employee_id', $process_owner_id)->get('employees')->row();
			//---------------------------------------------------------------------------------------
			//-------------------------------------------send message----------------------------------
			$email_process_owner = $this->getEmailTemp('Send Message');
			$email_lead_auditor = $email_process_owner;
			$email_auditor = $email_process_owner;

			if($employee_id != $lead_auditor_id){
				$email_lead_auditor['message'] = str_replace("{Recipient}", $lead_auditor_info->employee_name, $email_lead_auditor['message']);
				$email_lead_auditor['message'] = str_replace("{CONTENT}", $message, $email_lead_auditor['message']);
				$email_lead_auditor['message'] = str_replace("{Sender}", $user_name, $email_lead_auditor['message']);
				$email_lead_auditor['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_lead_auditor['message']);
				$this->sendemail($lead_auditor_info->employee_email, 'Corrective Action Resoultion', $email_lead_auditor['message'], $email_lead_auditor['subject']);
                //---------------------------------------------- send sms ----------------------------------------------
                if (!empty($lead_auditor_info->employee_phone) && $this->settings->otp_verification){
                    $phone = formatMobileNumber($lead_auditor_info->employee_phone, true);
                    /*=-=- check user mobile number valid start =-=-*/
                    $phone_response = $this->phone_rk->checkPhoneNumber($phone);
                    if ($phone_response['success']){
                        $message = "Hi {$lead_auditor_info->employee_name}".PHP_EOL;
                        $message.= "{$message}".PHP_EOL;
                        $message.= "{$user_name}";
                        $this->twill_rk->sendMsq($phone,$message);
                    }
                }
			}

			if($employee_id != $process_owner_id){
				$process_owner_info['message'] = str_replace("{Recipient}", $process_owner_info->employee_name, $process_owner_info['message']);
				$email_sme['message'] = str_replace("{CONTENT}", $message, $process_owner_info['message']);
				$email_sme['message'] = str_replace("{Sender}", $user_name, $process_owner_info['message']);
				$email_sme['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $process_owner_info['message']);
				$this->sendemail($process_owner_info->employee_email, 'Corrective Action Resoultion', $process_owner_info['message'], $process_owner_info['subject']);
                //---------------------------------------------- send sms ----------------------------------------------
                if (!empty($process_owner_info->employee_phone) && $this->settings->otp_verification){
                    $phone = formatMobileNumber($process_owner_info->employee_phone, true);
                    /*=-=- check user mobile number valid start =-=-*/
                    $phone_response = $this->phone_rk->checkPhoneNumber($phone);
                    if ($phone_response['success']){
                        $message = "Hi {$process_owner_info->employee_name}".PHP_EOL;
                        $message.= "{$message}".PHP_EOL;
                        $message.= "{$user_name}";
                        $this->twill_rk->sendMsq($phone,$message);
                    }
                }
			}

			if($employee_id != $auditor_id && $auditor_id != '0' && $auditor_id != '1' && $auditor_id != '-1'){
				$email_auditor['message'] = str_replace("{Recipient}", $auditor_info->employee_name, $email_auditor['message']);
				$email_auditor['message'] = str_replace("{CONTENT}", $message, $email_auditor['message']);
				$email_auditor['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_auditor['message']);
				$email_auditor['message'] = str_replace("{Sender}", $user_name, $email_auditor['message']);
				$this->sendemail($auditor_info->employee_email, 'Corrective Action Resoultion', $email_auditor['message'], $email_auditor['subject']);
                //---------------------------------------------- send sms ----------------------------------------------
                if (!empty($auditor_info->employee_phone) && $this->settings->otp_verification){
                    $phone = formatMobileNumber($auditor_info->employee_phone, true);
                    /*=-=- check user mobile number valid start =-=-*/
                    $phone_response = $this->phone_rk->checkPhoneNumber($phone);
                    if ($phone_response['success']){
                        $message = "Hi {$auditor_info->employee_name}".PHP_EOL;
                        $message.= "{$message}".PHP_EOL;
                        $message.= "{$user_name}";
                        $this->twill_rk->sendMsq($phone,$message);
                    }
                }
			}

			//-----------------------------------------------------------------------------------------

			$done = $this->db->insert('corrective_message', $data);
			echo json_encode($done);
		} else {
			redirect('Welcome');
		}
	}

	public function process_manage()
	{
		$data['cc1'] = 'active';
		$data['c1']  = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$audit_type_sel = $this->input->post('audit_type_sel');
		if ($employee_id) {
			$data['title']  = 'Process Manage';

			$this->db->where('company_id', $consultant_id);
			$data['type_of_audits'] = $this->db->get('type_of_audit')->result();

			if($audit_type_sel == null) {
				$this->db->where('company_id', $consultant_id);
				$audit_type_sel = $this->db->get('type_of_audit')->first_row()->type_id;
				$this->db->where('type_of_audit', $audit_type_sel);
			} else {
				$this->db->where('type_of_audit', $audit_type_sel);
			}
			$this->db->where('company_id', $consultant_id);
			$data['processes'] = $this->db->get('process_list')->result();

			$data['audit_type_sel'] = $audit_type_sel;
			$this->load->view('employee/process_manage', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function add_process_mng()
	{
		$process_name = $this->input->post('process_name');
		$description = $this->input->post('description');
		$type_sel_id = $this->input->post('type_sel_id');
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data = array(
				'process_name' => $process_name,
				'description' => $description,
				'type_of_audit' => $type_sel_id,
				'company_id' => $consultant_id
			);
			$done = $this->db->insert('process_list', $data);
			if ($done) {
				if ($done) {
					$this->session->set_flashdata('message', 'success');
					redirect('Employee/process_manage');
				} else {
					$this->session->set_flashdata('message', 'failed');
					redirect('Employee/process_manage');
				}
			}
		} else {
			redirect('Welcome');
		}
	}
	public function findAuditee(){
		$id = $this->input->post("id");
		$data = $this->checklist->getOne($id);
		echo json_encode($data);
	}

	public function findprocess()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$id = $this->input->post('id');
			$pa_id = $this->input->post('pa_id');
			$this->db->where('process_id', $id);
			$this->db->where('audit_id', $pa_id);
			$done = $this->db->get('select_process')->row();
			echo json_encode($done);
		} else {
			redirect('Welcome');
		}
	}

	public function edit_process_list()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$process_id = $this->input->post('process_id');
			$process_name = $this->input->post('edit_process_name');
			$description  = $this->input->post('edit_description');
			$data = array(
				'process_name' => $process_name,
				'description' => $description
			);
			$this->db->where('process_id', $process_id);
			$done = $this->db->update('process_list', $data);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Employee/process_manage');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Employee/process_manage');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function delete_process($id = Null)
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$this->db->where('process_id', $id);
			$done = $this->db->delete('process_list');
			if ($done) {
				$this->session->set_flashdata('message', 'success_del');
				redirect('Employee/process_manage');
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Employee/process_manage');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function team_auditors()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$data['cc1'] = 'active';
		$data['c2']  = 'act1';
		if ($employee_id) {
			$data['title'] = "Team Auditors";
			$plan_id = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
			$rowdata1 = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `consultant_id`='$consultant_id'")->row()->emps;
			if ($plan_id) {
				$rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
				$data['total_account'] = $rowdata1;
				$data['limit']         = $rowdata;
				$data['reached']       = (($rowdata1 * 100) / $rowdata);
			}

			$sql = "SELECT
                            *, GROUP_CONCAT(t.utype_name) type_name
                        FROM
                            employees e
                        LEFT JOIN permision p ON e.employee_id = p.employee_id
                        LEFT JOIN user_type t ON p.type_id = t.utype_id
                        WHERE
                            e.consultant_id = " . $consultant_id . " AND (p.type_id = 2 OR p.type_id = 3)
                        GROUP BY
                            e.employee_id";

			$data['employees'] = $this->db->query($sql)->result();
			$this->load->view('employee/team_auditors', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function add_auditors()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$employee_name  = $this->input->post('add_name');
			$employee_email = $this->input->post('add_email');
            /*=-=- check user mobile number valid start =-=-*/
            $this->load->library('phone_RK');
            $phone          = formatMobileNumber($this->input->post('add_phone'));;
            $phone_response = $this->phone_rk->checkPhoneNumber($phone);
            if (!$phone_response['success']){
                $this->session->set_flashdata('phone_response', $phone_response);
                redirect($_SERVER['HTTP_REFERER']);
                return;
            }
            /*=-=- check user mobile number valid end =-=-*/
			$role_name = $this->input->post('add_role');
			$username = $this->input->post('add_username');
			$password = getHashedPassword($this->input->post('add_password'));
			$auditor = $this->input->post('auditor');
			$process_owner = $this->input->post('process_owner');
			$created_at = date('Y-m-d');
			$data = array(
				'consultant_id' => $consultant_id,
				'employee_name' => $employee_name,
                'employee_phone' => $phone,
				'username' => $username,
				'employee_email' => $employee_email,
				'role' => $role_name,
				'password' => $password,
				'created_at' => $created_at,
				'status' => 1
			);
            if (empty(trim($this->input->post('add_password')))){
                unset($data['password']);
            }
			$employee_list = $this->db->query("SELECT * FROM `employees` WHERE `username`='$username'")->row();
			if($employee_list == null) {
				$plan_id = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
				if ($plan_id) {
					$rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
				}
				$rowdata1 = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `consultant_id`='$consultant_id'")->row()->emps;
				if ($rowdata == $rowdata1 || $rowdata1 > $rowdata) {
					$this->session->set_flashdata('message', 'failed');
					redirect('Employee/team_auditors');
				} else {
					$done = $this->db->insert('employees', $data);
					if ($done) {
						$this->db->order_by('employee_id', 'asc');
						$employee_id = $this->db->get('employees')->last_row()->employee_id;
						if($auditor != "" && $auditor != null) {
							$tmp = array(
								'employee_id' => $employee_id,
								'type_id' => $auditor
							);
							$confirm = $this->db->insert('permision', $tmp);
						}
						if($process_owner != "" && $process_owner != null) {
							$tmp = array(
								'employee_id' => $employee_id,
								'type_id' => $process_owner
							);
							$confirm = $confirm & $this->db->insert('permision', $tmp);
						}
						$this->session->set_flashdata('message', 'success');
						redirect('Employee/team_auditors');
					} else {
						$this->session->set_flashdata('message', 'error');
						redirect('Employee/team_auditors');
					}
				}
			} else {
				$this->session->set_flashdata('message', 'live_err');
				redirect('Employee/team_auditors');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function finduser()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$id = $this->input->post('id');

			$sql = "SELECT
                            *, GROUP_CONCAT(p.type_id) type_ids
                        FROM
                            employees e
                        LEFT JOIN permision p ON e.employee_id = p.employee_id
                        WHERE
                            e.consultant_id = " . $consultant_id . " AND e.employee_id = " . $id . "
                        GROUP BY
                            e.employee_id";
			$done = $this->db->query($sql)->row();
			echo json_encode($done);
		} else {
			redirect('Welcome');
		}
	}

	public function edit_auditors()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$employee_id = $this->input->post("employee_id");
			$employee_name  = $this->input->post('edit_name');
			$employee_email = $this->input->post('edit_email');
            /*=-=- check user mobile number valid start =-=-*/
            $this->load->library('phone_RK');
            $phone          = formatMobileNumber($this->input->post('edit_phone'));;
            $phone_response = $this->phone_rk->checkPhoneNumber($phone);
            if (!$phone_response['success']){
                $this->session->set_flashdata('phone_response', $phone_response);
                redirect($_SERVER['HTTP_REFERER']);
                return;
            }
            /*=-=- check user mobile number valid end =-=-*/
			$role_name = $this->input->post('edit_role');
			$username = $this->input->post('edit_username');
			$password = getHashedPassword($this->input->post('edit_password'));
			$auditor = $this->input->post('edit_auditor');
			$process_owner = $this->input->post('edit_process_owner');
			$old_username = $this->input->post('old_username');
			$data = array(
				'consultant_id' => $consultant_id,
				'employee_name' => $employee_name,
				'username' => $username,
				'employee_email' => $employee_email,
                'employee_phone' => $phone,
				'role' => $role_name,
				'password' => $password,
				'status' => 1
			);
            if (empty(trim($this->input->post('edit_password')))){
                unset($data['password']);
            }
			$employee_list = $this->db->query("SELECT * FROM `employees` WHERE `username`='$username'")->row();
			if($employee_list == null) {
				$this->db->where("employee_id", $employee_id);
				$done = $this->db->update('employees', $data);
				if ($done) {
					$this->db->where("employee_id", $employee_id);
					$this->db->delete("permision");

					if($auditor != "" && $auditor != null) {
						$tmp = array(
							'employee_id' => $employee_id,
							'type_id' => $auditor
						);
						$confirm = $this->db->insert('permision', $tmp);
					}
					if($process_owner != "" && $process_owner != null) {
						$tmp = array(
							'employee_id' => $employee_id,
							'type_id' => $process_owner
						);
						$confirm = $confirm & $this->db->insert('permision', $tmp);
					}
					$this->session->set_flashdata('message', 'update_success');
					redirect('Employee/team_auditors');
				} else {
					$this->session->set_flashdata('message', 'error');
					redirect('Employee/team_auditors');
				}
			} else {
				if($old_username == $username) {
					$this->db->where("employee_id", $employee_id);
					$done = $this->db->update('employees', $data);
					if ($done) {
						$this->db->where("employee_id", $employee_id);
						$this->db->delete("permision");

						if($auditor != "" && $auditor != null) {
							$tmp = array(
								'employee_id' => $employee_id,
								'type_id' => $auditor
							);
							$confirm = $this->db->insert('permision', $tmp);
						}
						if($process_owner != "" && $process_owner != null) {
							$tmp = array(
								'employee_id' => $employee_id,
								'type_id' => $process_owner
							);
							$confirm = $confirm & $this->db->insert('permision', $tmp);
						}
						$this->session->set_flashdata('message', 'update_success');
						redirect('Employee/team_auditors');
					} else {
						$this->session->set_flashdata('message', 'error');
						redirect('Employee/team_auditors');
					}
				} else {
					$this->session->set_flashdata('message', 'live_err');
					redirect('Employee/team_auditors');
				}
			}
		} else {
			redirect('Welcome');
		}
	}

	public function delete_auditors($id = Null)
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$this->db->where('employee_id', $id);
			$done = $this->db->delete('employees');
			if ($done) {
				$this->db->where('employee_id', $id);
				$confirm = $this->db->delete('permision');
				if($confirm) {
					$this->session->set_flashdata('message', 'success_del');
					redirect('Employee/team_auditors');
				} else {
					$this->session->set_flashdata('message', 'error');
					redirect('Employee/team_auditors');
				}
			} else {
				$this->session->set_flashdata('message', 'error');
				redirect('Employee/team_auditors');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function audits()
	{
		$data['aa1'] = 'active';
		$data['a1']  = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title'] = "Audit";

			$sql = "SELECT
                            audit.*, type.type_of_audit, e.employee_name, f.*, t.trigger_name,
                            (DATEDIFF(audit.created_at,DATE(NOW())) + f.days) as status_days
                        FROM
                            audit_list audit
                        LEFT JOIN type_of_audit type ON audit.audit_type = type.type_id
                        LEFT JOIN employees e ON audit.lead_auditor = e.employee_id
                        LEFT JOIN frequency f ON audit.frequency = f.frequency_id
                        LEFT JOIN `trigger` t ON audit.`trigger` = t.trigger_id
                        WHERE
                            audit.company_id = " . $consultant_id . " AND audit.lead_auditor = " . $employee_id . "
                        ORDER BY audit.created_at DESC, pa_id DESC ";
			$data['audits'] = $this->db->query($sql)->result();
			$this->load->view('employee/audits', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function audit_brief($audit_id = Null)
	{
		$data['aa1'] = 'active';
		$data['a1']  = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title']  = 'Audit Brief';

			// $audit_log_list = $this->db->query("SELECT * FROM `audit_log_list` WHERE `log_id`='$log_id'")->row();
			// if($log_id == null || $audit_log_list == null) {
			// 	redirect('Consultant/audits');
			// } else {
				$data['audit_id'] = $audit_id;
				$data['audit_brief_array'] = $this->audit_brief->selectOne(array('audit_id'=>$audit_id));
				if($data['audit_brief_array'] != null) {
					$data['is_brief'] = TRUE;
				}
				else {
					$data['is_brief'] = FALSE;
					// while($data['audit_brief_array'] == null && $log_id > 1){
					// 	$log_id -= 1;
					// 	$this->db->where('audit_id', $log_id);
					// 	$data['audit_brief_array'] = $this->db->get('audit_brief')->row();
					// 	if($data['audit_brief_array'] != null){
					// 		$audit_id = $data['audit_brief_array']->audit_id;
					// 		$sql = "SELECT type.company_id from type_of_audit type, audit_list audit, audit_log_list log
					// 		WHERE type.type_id = audit.audit_type and log.audit_id = audit.pa_id and log.log_id = '$audit_id'";
					// 		$company_id = $this->db->query($sql)->row('company_id');
					// 		if($company_id == $consultant_id){
					// 			$data['log_id'] = $log_id;
					// 			$data['is_brief'] = TRUE;
					// 		}
					// 		else
					// 			$data['audit_brief_array'] = null;
					// 	}
					// }
				}
				$this->load->view('employee/audit_brief', $data);
			// }
		} else {
			redirect('Welcome');
		}
	}

	public function delete_employee_any($id = Null)
	{
		$consultant_id = $this->session->userdata('consultant_id');
		$this->db->where('employee_id', $id);
		$done = $this->db->delete('employees');
		if ($done) {
			$this->db->where('employee_id', $id);
			$confirm = $this->db->delete('permision');
		}
	}

	public function all_open_employees(){
		$consultant_id = $this->session->userdata('consultant_id');
		$is_plan = $this->input->post("is_plan");
		$open_employees = $this->input->post("open_employees");
		$this->db->where('consultant_id', $consultant_id);
		$employees = $this->db->get('employees')->result();
		$count = 1;
		foreach ($employees as $employee) {
            echo "<tr>";
            echo "<td>" . $count . "</td>";
            $flag = FALSE;
            if($is_plan) {
                $check_list = explode(",", $open_employees);
                for($i = 0; $i < count($check_list); $i++) {
                    if($employee->employee_id == $check_list[$i]) {
                        $flag = TRUE;
                        echo '<td><input class="styled open_checker" type="checkbox" checked value="' . $employee->employee_id . '"></td>';
                        break;
                    }
                }
                if(!$flag)  {
                    echo '<td><input class="styled open_checker" type="checkbox" value="' . $employee->employee_id . '"></td>';
                }
            } else {
                echo '<td><input class="styled open_checker" type="checkbox" value="' . $employee->employee_id . '"></td>';
            }
            echo "<td>" . $employee->employee_name . "</td>";
            echo "<td>" . $employee->employee_email . "</td>";
            echo '<td style="min-width: 140px;">' . $employee->role . "</td>";
            echo "</tr>";
            $count++;
        }
	}

	public function all_close_employees(){
		$consultant_id = $this->session->userdata('consultant_id');
		$is_plan = $this->input->post("is_plan");
		$close_employees = $this->input->post("close_employees");
		$this->db->where('consultant_id', $consultant_id);
		$employees = $this->db->get('employees')->result();
		$count = 1;
		foreach ($employees as $employee) {
            echo "<tr>";
            echo "<td>" . $count . "</td>";
            $flag = FALSE;
            if($is_plan) {
                $check_list = explode(",", $close_employees);
                for($i = 0; $i < count($check_list); $i++) {
                    if($employee->employee_id == $check_list[$i]) {
                        $flag = TRUE;
                        echo '<td><input class="styled open_checker" type="checkbox" checked value="' . $employee->employee_id . '"></td>';
                        break;
                    }
                }
                if(!$flag)  {
                    echo '<td><input class="styled open_checker" type="checkbox" value="' . $employee->employee_id . '"></td>';
                }
            } else {
                echo '<td><input class="styled open_checker" type="checkbox" value="' . $employee->employee_id . '"></td>';
            }
            echo "<td>" . $employee->employee_name . "</td>";
            echo "<td>" . $employee->employee_email . "</td>";
            echo '<td style="min-width: 140px;">' . $employee->role . "</td>";
            echo "</tr>";
            $count++;
        }
	}

	public function audit_plan($pa_id = Null)
	{
		$data['aa1'] = 'active';
        $data['a1']  = 'act1';
        $consultant_id = $this->session->userdata('consultant_id');
        $params = $this->input->post();
        
        $start_date = date("Y-m-d", strtotime($this->input->post("start_date")));
        $end_date = date("Y-m-d", strtotime($this->input->post("end_date")));

        $date_schedule = $start_date . " - " . $end_date;

        $params["start_date"] = $start_date;
        $params["end_date"] = $end_date;
        $params["date_schedule"] = $date_schedule;
        $params["audit_id"] = $pa_id;
        unset($params["auditor__view_length"]);
        unset($params["owner__view_length"]);
        $summary = $this->input->post("summary");
        if ($consultant_id) {
            $data['title']  = 'Audit Plan';
            $data['pa_id'] = $pa_id;

            if($date_schedule != null && $date_schedule != "") {

                $result = $this->audit_brief->selectOne(array("audit_id"=>$pa_id));
                if($result == null) {
                    $brief_id = $this->audit_brief->save($params);
                    $this->audit_log->insert(array('log_id'=> $pa_id, "audit_id"=>$pa_id,'brief_id' => $brief_id));

                } else {
                    $this->audit_brief->updateWithFilter($params, array('audit_id'=> $pa_id));
                    $brief_id = $result->brief_id;
                    $this->audit_log->update(array('log_id'=> $pa_id, "audit_id"=>$pa_id, 'brief_id' => $brief_id));
                }
            }

			$data['employees'] = $this->employee->getAll(array('consultant_id'=> $consultant_id));

			$this->db->where('audit_id', $pa_id);
			$audit_plan_array = $this->db->get('audit_plan')->row();

			if($audit_plan_array == null) {
				$data['is_plan'] = FALSE;
				$data['audit_plan_array'] = array(
					'close_when' => ''
				);
			} else {
				$open_employees = $audit_plan_array->open_employees;
				$open_who = "";
				$open_who_array = explode(",", $open_employees);
				for($i = 1; $i < count($open_who_array); $i++) {
					$this->db->where('employee_id', $open_who_array[$i]);
					$employee_name = $this->db->get('employees')->row('employee_name');
					if($i == 1) {
						$open_who .= $employee_name;
					} else {
						$open_who .= '; ' . $employee_name;
					}
				}
				$close_employees = $audit_plan_array->close_employees;
				$close_who = "";
				if($close_employees != null) {
					$open_who_array = explode(",", $close_employees);
					for($i = 1; $i < count($open_who_array); $i++) {
						$this->db->where('employee_id', $open_who_array[$i]);
						$employee_name = $this->db->get('employees')->row('employee_name');
						if($i == 1) {
							$close_who .= $employee_name;
						} else {
							$close_who .= '; ' . $employee_name;
						}
					}
				} else {
					$close_who = "N/A";
				}

				$colse_when = ($audit_plan_array->close_when == null) ? 'TBD' : date('m/d/Y', strtotime( $audit_plan_array->close_when ));

				$data['audit_plan_array'] = array(
					'audit_id' => $audit_plan_array->audit_id,
					'open_employees' => $audit_plan_array->open_employees,
					'open_when' => $audit_plan_array->open_when,
					'open_where' => $audit_plan_array->open_where,
					'open_cover' => $audit_plan_array->open_cover,
					'schedule' => $audit_plan_array->schedule,
					'close_employees' => ($close_who != "N/A") ? $audit_plan_array->close_employees : '',
					'close_when' => $colse_when,
					'close_where' => $audit_plan_array->close_where,
					'open_who' => $open_who,
					'close_who' => $close_who
				);
				$data['is_plan'] = TRUE;
			}

			$this->db->where('audit_id', $pa_id);
			$audit_plan_data = $this->db->get('audit_plan')->result();
			if($audit_plan_data != null) {
				$this->db->where('audit_id', $pa_id);
				$this->db->where('checked', 1);
                $audit_process_data = $this->db->get('select_process')->result();
				if($audit_process_data != null) {
					$data['is_schedule'] = TRUE;
				} else {
					$data['is_schedule'] = FALSE;
				}
			} else {
				$data['is_schedule'] = FALSE;
			}
			$this->load->view('employee/audit_plan', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function checked_list() {
		$consultant_id = $this->session->userdata('consultant_id');
		$check_list = $this->input->post('check_list');
		$employee_list = "";

		$arrays = explode(",", $check_list);
		for($i = 1; $i < count($arrays); $i++) {
			$this->db->where('employee_id', $arrays[$i]);
			$employee_name = $this->db->get('employees')->row()->employee_name;
			if($i == 1) {
				$employee_list .= $employee_name;
			} else {
				$employee_list .= '; ' . $employee_name;
			}
		}
		echo $employee_list;
	}

	public function select_process($pa_id = Null)
	{
		$data['aa1'] = 'active';
		$data['a1']  = 'act1';
		$open_who_list = $this->input->post("open_who_list");
		$open_when = $this->input->post("open_when");
		$open_where = $this->input->post("open_where");
		$open_cover = $this->input->post("open_cover");
		$schedule = $this->input->post("schedule");
		$close_who_list = $this->input->post("close_who_list");
		$close_when = $this->input->post("close_when");
		$close_where = $this->input->post("close_where");
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title']  = 'Select Process';
			$data['pa_id'] = $pa_id;

			if($open_when != null) {
				$audit_plan_array = array(
					'audit_id' => $pa_id,
					'open_employees' => $open_who_list,
					'open_when' => date('Y-m-d', strtotime( $open_when )),
					'open_where' => $open_where,
					'open_cover' => $open_cover,
					'schedule' => $schedule,
					'close_where' => $close_where
				);
				$audit_plan_array['close_employees'] = ($close_who_list != 'N/A') ? $close_who_list : null;
				$audit_plan_array['close_when'] = ($close_when != 'TBD') ? date('Y-m-d', strtotime( $close_when )) : null;

				$this->db->where('audit_id', $pa_id);
				$result = $this->db->get('audit_plan')->row();
				if($result == null) {
					$this->db->insert('audit_plan', $audit_plan_array);
					$plan_id = $this->db->insert_id();

					$this->db->where('log_id', $pa_id);
					$this->db->update('audit_log_list', array('plan_id' => $plan_id));
				} else {
					$this->db->where('audit_id', $pa_id);
					$this->db->update('audit_plan', $audit_plan_array);
				}
			}

            $this->db->where('audit_id', $pa_id);
            $this->db->where('checked', 1);
            $process_list = $this->db->get('select_process')->result();
            $process_checked = "";
            if($process_list != null) {
                for($i = 0; $i < count($process_list) ; $i++) {
                    if($i == 0) {
                        $process_checked .= $process_list[$i]->process_id;
                    } else {
                        $process_checked .= ',' . $process_list[$i]->process_id;
                    }
                    $data['process_list'] = $process_checked;
                    $data['is_process'] = TRUE;
                }
            } else {
                $data['is_process'] = FALSE;
            }

            // $sql = "SELECT p.* FROM
			// 				(
			// 					SELECT * FROM
			// 						audit_log_list WHERE log_id = " . $pa_id . "
			// 				) a
			// 			LEFT JOIN audit_list al ON al.pa_id = a.audit_id
			// 			LEFT JOIN process_list p ON al.audit_type = p.type_of_audit
			// 			WHERE p.company_id = " . $consultant_id;
            // $data['processes'] = $this->db->query($sql)->result();
			$data['processes'] = $this->process->getAll(array("company_id"=>$consultant_id));
            $this->load->view('employee/select_process', $data);
        } else {
            redirect('Welcome');
        }
    }

	public function add_process($pa_id = Null)
	{
		$process_name = $this->input->post('process_name');
		$description = $this->input->post('description');
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$this->db->where('audit_log_list.log_id', $pa_id);
			$this->db->join('audit_list', 'audit_list.pa_id=audit_log_list.audit_id', 'left');
			$type_of_audit = $this->db->get('audit_log_list')->row()->audit_type;

			$data = array(
				'process_name' => $process_name,
				'description' => $description,
				'type_of_audit' => $type_of_audit,
				'company_id' => $consultant_id
			);
			$done = $this->db->insert('process_list', $data);
			if ($done) {
				if ($done) {
					$this->session->set_flashdata('message', 'success');
					redirect('Employee/select_process/' . $pa_id);
				} else {
					$this->session->set_flashdata('message', 'failed');
					redirect('Employee/select_process/' . $pa_id);
				}
			} else {
			}
		} else {
			redirect('Welcome');
		}
	}

	public function audit_schedule($pa_id = Null)
	{
		$data['aa1'] = 'active';
		$data['a1']  = 'act1';
		$auditor = 2;
		$process_owner = 3;
		$auditee = 4;
		$process_list = $this->input->post("process_list");
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title']  = 'Process Schedule';
			$data['pa_id'] = $pa_id;

			if($process_list != null) {
				$this->db->where('audit_id', $pa_id);
				$process_items = $this->db->get('select_process')->result();
				if($process_items == null) {
					$check_list = explode(",", $process_list);
					for($i = 1; $i < count($check_list); $i ++) {
						$check_array = array (
							'audit_id' => $pa_id,
							'process_id' => $check_list[$i],
							'checked' => 1,
							'status' => 2
						);
						$this->db->insert('select_process', $check_array);
					}
				} else {
					$this->db->where('audit_id', $pa_id);
					$sp_list = $this->db->get('select_process')->result();
					foreach($sp_list as $sp) {
						$this->db->where('id', $sp->id);
						if($sp->status == 1) {
							$this->db->update('select_process', array('checked' => 0));
						} else {
							$this->db->update('select_process', array('checked' => 0, 'status' => 0));
						}
					}
					$check_list = explode(',', $process_list);
					for($i = 1; $i < count($check_list); $i ++) {
						$check_array = array (
							'audit_id' => $pa_id,
							'process_id' => $check_list[$i],
							'checked' => 1,
							'status' => 2
						);
//                        $this->db->insert('select_process', $check_array);
						$this->db->where('audit_id', $pa_id);
						$this->db->where('process_id', $check_list[$i]);
						$process = $this->db->get('select_process')->row();
						if($process == null) {
							$this->db->insert('select_process', $check_array);
						} else {
							$this->db->where('audit_id', $pa_id);
							$this->db->where('process_id', $check_list[$i]);
							$this->db->update('select_process', array('checked' => 1, 'status' => 2));
						}
					}
				}
			}

			$sql = "SELECT process.*, pl.process_name, pl.description, (
                            SELECT
                                employee_name
                            FROM
                                employees
                            WHERE
                                process.auditor = employees.employee_id
                        ) AS auditor_name,
                        (
                        SELECT
                                employee_name
                            FROM
                                employees
                            WHERE
                                process.process_owner = employees.employee_id
                        ) AS process_owner_name,
                        (
                        SELECT
                                employee_name
                            FROM
                                employees
                            WHERE
                                process.sme = employees.employee_id
                        ) AS sme_name,
                        DATE_FORMAT(process.start_time,'%M %e, %Y %l:%i %p') as starttime_type,
                        DATE_FORMAT(process.end_time,'%M %e, %Y %l:%i %p') as endtime_type
                        FROM
                            select_process AS process
                        LEFT JOIN
                            process_list pl ON process.process_id = pl.process_id
                        WHERE
                            process.audit_id = " . $pa_id . " AND process.checked = 1
						ORDER BY process.process_id DESC ";
			$data['processes'] = $this->db->query($sql)->result();

			$this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
			$this->db->where('employees.consultant_id', $consultant_id);
			$this->db->where('permision.type_id', $auditor);
			$data['auditors'] = $this->db->get('employees')->result();

			$this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
			$this->db->where('employees.consultant_id', $consultant_id);
			$this->db->where('permision.type_id', $process_owner);
			$data['owners'] = $this->db->get('employees')->result();

			$this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
			$this->db->where('employees.consultant_id', $consultant_id);
			$this->db->where('permision.type_id', $auditee);
			$data['smes'] = $this->db->get('employees')->result();

			$this->db->where('log_id', $pa_id);
			$data['audit_log'] = $this->db->get('audit_log_list')->row();

			$this->load->view('employee/audit_schedule', $data);
		} else {
			redirect('Welcome');
		}
	}

	public  function assign_process($pa_id = Null) {
		$assign_process_id = $this->input->post("assign_process_id");
		$auditor = $this->input->post("auditor");
		$owner = $this->input->post("process_owner");
		$auditee_array = $this->input->post("auditee");
		$map_type = $this->input->post("map_type");
		$startTime = $this->input->post('startTimeInput');
		$endTime = $this->input->post('endTimeInput');
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');

		$auditee = "";
		foreach($auditee_array as $row){
			$auditee .= $row . ", ";
		}
		$auditee = substr($auditee, 0, -2);

		if ($employee_id) {
			$array = array(
				'auditor' => $auditor,
				'process_owner' => $owner,
				'sme' => $auditee,
				'map_type' => $map_type,
				'start_time' => $startTime,
				'end_time' => $endTime,
				'status' => 2,
				'checked' => 1
			);
			$this->db->where('audit_id', $pa_id);
			$this->db->where('process_id', $assign_process_id);
			$this->db->update('select_process', $array);

			//---------------------------------------------get info------------------------------------------
			$company_name = $this->db->where('consultant_id', $consultant_id)->get('consultant')->row()->consultant_name;
			$sql = "SELECT e.* FROM audit_log_list a, audit_list b, employees e
                    where a.audit_id = b.pa_id and a.log_id = '$pa_id' and b.lead_auditor = e.employee_id";
			$lead_auditor_info = $this->db->query($sql)->row();
			$audit_id = $this->db->where('log_id', $pa_id)->get('audit_log_list')->row()->audit_id;
			$audit_type_id = $this->db->where('pa_id', $audit_id)->get('audit_list')->row()->audit_type;
			$audit_here = $this->db->where('type_id', $audit_type_id)->get('type_of_audit')->row()->type_of_audit;
			$auditor_info = $this->db->where('employee_id', $auditor)->get('employees')->row();
			$process_owner_info = $this->db->where('employee_id', $owner)->get('employees')->row();
			$process_name = $this->db->where('process_id', $assign_process_id)->get('process_list')->row()->process_name;
			$dates = abs((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24));
			//-----------------------------------------------------------------------------------------------

			//-------------------------------------------------send email-----------------------------------------------------
			$email_temp = $this->getEmailTemp('When Audit is scheduled by Admin to Auditor');
			$email_temp['message'] = str_replace("{Auditor NAME}", $auditor_info->employee_name, $email_temp['message']);
			$email_temp['message'] = str_replace("{Company Name}", $company_name, $email_temp['message']);
			$email_temp['message'] = str_replace("{Audit Here}", $audit_here, $email_temp['message']);
			$email_temp['message'] = str_replace("{COURSE_NAME}", 'phpstack-971964-3536769.cloudwaysapps.com', $email_temp['message']);
			$email_temp['message'] = str_replace("{Audit Here}", 'Audit Here', $email_temp['message']);
			$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
			$this->sendemail($auditor_info->employee_email, 'Audit is scheduled by Admin', $email_temp['message'], $email_temp['subject'], 1);
            //---------------------------------------------- send sms ----------------------------------------------
            if (!empty($auditor_info->employee_phone) && $this->settings->otp_verification){
                $phone = formatMobileNumber($auditor_info->employee_phone, true);
                /*=-=- check user mobile number valid start =-=-*/
                $phone_response = $this->phone_rk->checkPhoneNumber($phone);
                if ($phone_response['success']){
                    $message = "Hi {$auditor_info->employee_name}".PHP_EOL;
                    $message.= "Congratulations you have been assigned to be an Auditor for {$audit_here} by your Administrator from {$company_name} on ".APP_NAME." Quality Circle's Process and Risk Based Software.";
                    $this->twill_rk->sendMsq($phone,$message);
                }
            }

			$email_temp = $this->getEmailTemp('When Audit is scheduled by Admin to Process Owner');
			$email_temp['message'] = str_replace("{Process Owner NAME}", $process_owner_info->employee_name, $email_temp['message']);
//			$email_temp['message'] = str_replace("{dates}", $dates." days", $email_temp['message']);
			$email_temp['message'] = str_replace("{dates}", $startTime." and ".$endTime, $email_temp['message']);
			$email_temp['message'] = str_replace("{Process Name}", $process_name, $email_temp['message']);
			$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
			$this->sendemail($process_owner_info->employee_email, 'Audit is scheduled by Admin', $email_temp['message'], $email_temp['subject'], 3);
            //---------------------------------------------- send sms ----------------------------------------------
            if (!empty($process_owner_info->employee_phone) && $this->settings->otp_verification){
                $phone = formatMobileNumber($process_owner_info->employee_phone, true);
                /*=-=- check user mobile number valid start =-=-*/
                $phone_response = $this->phone_rk->checkPhoneNumber($phone);
                if ($phone_response['success']){
                    $message = "Hi {$process_owner_info->employee_name}".PHP_EOL;
                    $message.= "Audits are scheduled between {$startTime} for your {$process_name}. The Lead auditor will send you the Audit Plan. Please have your team ready and extend kind courtesies to the audit team. They will require to look at you Process Documents (Procedures, Guidelines, Work Plans and Record). They will also be observing and doing interviews with you and your team. The objective of the audit is to make sure you are conforming with the STANDARD, YOUR OWN SETS OF REQUIREMENT, CUSTOMER REQUIREMENTS AND ANY APPLICABLE REQULATORY REQUIREMENTS.".PHP_EOL;
                    $message.= "Admin";
                    $this->twill_rk->sendMsq($phone,$message);
                }
            }
			$email_temp = $this->getEmailTemp('When Audit is scheduled by Admin Lead Auditor to Process Owner');
			$email_temp['message'] = str_replace("{Process Owner NAME}", $process_owner_info->employee_name, $email_temp['message']);
			$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
			$this->sendemail($process_owner_info->employee_email, 'Audit is scheduled by Admin', $email_temp['message'], $email_temp['subject'], 3);
            //---------------------------------------------- send sms ----------------------------------------------
            if (!empty($process_owner_info->employee_phone) && $this->settings->otp_verification){
                $phone = formatMobileNumber($process_owner_info->employee_phone, true);
                /*=-=- check user mobile number valid start =-=-*/
                $phone_response = $this->phone_rk->checkPhoneNumber($phone);
                if ($phone_response['success']){
                    $message = "Hi {$process_owner_info->employee_name}".PHP_EOL;
                    $message.= "The audit plan is attached. Please go through the schedules and note the date and time of your audits. Admin has already communicated the purpose of the audit to you.".PHP_EOL;
                    $message.= "Lead Auditor";
                    $this->twill_rk->sendMsq($phone,$message);
                }
            }
			//------------------------------------------------------------------------------------------------------------------

			redirect('Employee/audit_schedule/' . $pa_id);
		} else {
			redirect('Welcome');
		}
	}

	public  function edit_process($pa_id = Null) {
		$edit_process_id = $this->input->post("edit_process_id");
		$auditor = $this->input->post("edit_auditor");
		$owner = $this->input->post("edit_owner");
		$auditee_array = $this->input->post("edit_auditee");
		$map_type = $this->input->post("edit_map_type");
		$startTime = $this->input->post('edit_startTimeInput');
		$endTime = $this->input->post('edit_endTimeInput');
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');

		$auditee = "";
		foreach($auditee_array as $row){
			$auditee .= $row . ", ";
		}
		$auditee = substr($auditee, 0, -2);

		if ($employee_id) {
			$array = array(
				'auditor' => $auditor,
				'process_owner' => $owner,
				'sme' => $auditee,
				'map_type' => $map_type,
				'start_time' => $startTime,
				'end_time' => $endTime,
				'status' => 2,
				'checked' => 1
			);
			$this->db->where('audit_id', $pa_id);
			$this->db->where('process_id', $edit_process_id);
			$this->db->update('select_process', $array);
			redirect('Employee/audit_schedule/' . $pa_id);
		} else {
			redirect('Welcome');
		}
	}

	public function audits_finish($pa_id = Null) {
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data = array(
				'submited_date' => date('Y-m-d'),
				'status' => 2,
				'submited' => 1
			);
			$this->db->where('log_id', $pa_id);
			$done = $this->db->update('audit_log_list', $data);
			if($done) {
				redirect('Employee/audits');
			} else {
				redirect('Employee/audit_schedule/' . $pa_id);
			}
		} else {
			redirect('Welcome');
		}
	}

	function process_message() {
		$data['dd1'] = 'active';
		$data['d3'] = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$user_type = $this->session->userdata('user_type');
		if ($employee_id) {
			$data['title'] = "Process Inbox";
			if($user_type == 'Lead Auditor') {
				$where = " AND audit.lead_auditor = " . $employee_id;
			} else {
				$where = " AND (sp.auditor = " . $employee_id . " OR sp.process_owner = " . $employee_id . " OR sp.sme = " . $employee_id . ")";
			}

			$sql = "SELECT process.process_name, process.description, sp.id, type.type_of_audit, f.frequency_name, t.trigger_name,
                            (SELECT employee_name FROM employees WHERE audit.lead_auditor = employees.employee_id) AS lead_auditor_name
                        FROM
                            select_process AS sp
                        LEFT JOIN process_list process ON process.process_id = sp.process_id
                        LEFT JOIN audit_log_list log ON log.log_id = sp.audit_id
                        LEFT JOIN audit_list audit ON audit.pa_id = log.audit_id
                        LEFT JOIN type_of_audit type ON type.type_id = audit.audit_type
                        LEFT JOIN frequency f ON f.frequency_id = audit.frequency
                        LEFT JOIN `trigger` t ON t.trigger_id = audit.`trigger`
                        WHERE
                            sp.id IN (
                                SELECT process_id FROM process_message WHERE company_id = " . $consultant_id . "
                            )" . $where . "
                        ORDER BY sp.id DESC";
			$data['process_message'] = $this->db->query($sql)->result();
			$this->load->view('employee/process_message', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function show_process_message($id = '')
	{
		$data['dd1'] = 'active';
		$data['d3'] = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$this->db->where('process_id', $id);
			$this->db->where('company_id', $consultant_id);
			$this->db->order_by('date_time', 'asc');
			$data['message'] = $this->db->get('process_message')->result();
			$data['title']   = "Messages";
			$this->db->where('select_process.id', $id);
			$this->db->join('process_list', 'process_list.process_id=select_process.process_id', 'left');
			$data['process_data'] = $this->db->get('select_process')->row();
			$this->load->view('employee/show_process_message', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function send_process_message() {
		$message = $this->input->post('message');
		$process_id = $this->input->post('process_id');
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$user_type = $this->session->userdata('user_type');
		if ($employee_id) {
			$created_at = date('Y-m-d h:i:s');

			$data = array(
				'company_id' => $consultant_id,
				'sender_id' => $employee_id,
				'sender_role' => $user_type,
				'message' => $message,
				'date_time' => $created_at,
				'process_id' => $process_id
			);
			$done = $this->db->insert('process_message', $data);
			echo json_encode($done);
		} else {
			redirect('Welcome');
		}
	}

	public function individual_message()
	{
		$data['dd1'] = 'active';
		$data['d2'] = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title'] = "Individual Inbox";
			$sql = "SELECT
							*
						FROM
							individual_message
						WHERE
							company_id = " . $consultant_id . "
						AND (from_user = " . $employee_id . " OR to_user = " . $employee_id . ")
						ORDER BY
							date_time DESC";
			$data['individual_message'] = $this->db->query($sql)->result();
			$this->db->where('consultant_id', $consultant_id);
			$this->db->where('employee_id !=', $employee_id);
			$data['employees'] = $this->db->get('employees')->result();
			$this->load->view('employee/individual_message', $data);
		} else {
			redirect('Welcome');
		}
	}

	/*************For last login details***********************/
	public function login_history()
	{
		$data['dd4'] = 'active';
		$data['d4'] = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title'] = "Login History";
			$sql = "SELECT * FROM login_history WHERE user_id = " . $employee_id . " ORDER BY date_time DESC";
			$rowdata1 = @$this->db->query("SELECT COUNT(user_id) as emps FROM `login_history` WHERE `user_id`='$employee_id' AND status = 2")->row()->emps;
			$data['count_notification'] = $rowdata1;
			$data['login_history'] = $this->db->query($sql)->result();
			$this->load->view('employee/login_history', $data);
		} else {
			redirect('Welcome');
		}
	}
	/************************End*******************************/
	/********************************************************/
   public function update_notification()
	{
		$employee_id   = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		//$Notification_id    = $this->input->post('Notification_id');
		$Notification_id = $_POST['Notification_id'];
		$up = array(
			'status' => '3',
		);
		if ($employee_id) {
			$this->db->where('id', $Notification_id);
			$done = $this->db->update('login_history', $up);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				redirect('Employee/login_history');
			} 
		} else {
			redirect('Welcome');
		}
	}

	/************************END********************************/
		/*************Edit process ***********************/
	public function edit_single_process()
	{
		$pa_id = $_POST['pa_id'];
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$sql = "SELECT * FROM process_list WHERE process_id = " . $pa_id . "	
						";
			$data['edit_single_pro'] = $this->db->query($sql)->result();
			//$this->load->view('employee/select_process', $data);
			echo json_encode($data);
		} else {
			redirect('Welcome');
		}
	}
	/************************End*******************************/
	/********************************************************/
   public function update_process()
	{
		$employee_id   = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$process_id    = $this->input->post('process_id');
		$process_name  = $this->input->post('process_name_1');
		$description   = $this->input->post('description_1');
		$pro_id        = $_GET['id'];
		$up = array(
			'process_name' => $process_name,
			'description' => $description,
		);
		if ($employee_id) {
			$this->db->where('process_id', $process_id);
			$done = $this->db->update('process_list', $up);
			if ($done) {
				$this->session->set_flashdata('message', 'update_success');
				//$this->load->view('employee/select_process', $done);
				redirect('Employee/select_process/'.$pro_id);
			} /*else {
				redirect('Employee/open_audit');
			}*/
		} else {
			redirect('Welcome');
		}
	}

	/************************END********************************/
	/**********************Delete Process***********************/
	 public function del_process(){
	    $employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$process_id    = $this->input->post('process_id_del');
		$pro_id        = $_GET['id'];
		if ($employee_id) {
			$this->db->where('process_id', $process_id);
			$done = $this->db->delete('process_list');
			if ($done) {
				$this->db->where('employee_id', $id);
				$confirm = $this->db->delete('permision');
				if($confirm) {
					$this->session->set_flashdata('message', 'success_del');
					redirect('Employee/select_process/'.$pro_id);
				} else {
					$this->session->set_flashdata('message', 'failed');
					redirect('Employee/select_process/'.$pro_id);
				}
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Employee/select_process/'.$pro_id);
			}
		} else {
			redirect('Welcome');
		}
	}
	/***********************END*******************************/
/**********************Delete schedule by ID**********************/
 public function delete_schedule_id(){
	    $employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$process_id    = $this->input->post('assign_schedule_id');
		$pro_id        = $_GET['id'];
		if ($employee_id) {
			$this->db->where('id', $process_id);
			$done = $this->db->delete('select_process');
			if ($done) {
				
					$this->session->set_flashdata('message', 'success_del');
					redirect('Employee/audit_schedule/'.$pro_id);
				
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('Employee/audit_schedule/'.$pro_id);
			}
		} else {
			redirect('Welcome');
		}
	}
/*******************************END********************************/
	function mails_to_indi()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id  = $this->session->userdata('consultant_id');
		$title       = $this->input->post('title');
		$message     = $this->input->post('message');
		$to_user     = $this->input->post('to_user');
		$date_time   = date('Y-m-d');
		if ($to_user == 'owner') {
//			$ml      = $this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row();
//			$mails   = $ml->email;
			$to_role = 'consultant';
		} else {
//			$ml      = $this->db->query("select * from `employees` where `employee_id`='$to_user'")->row();
//			$mails   = $ml->employee_email;
			$to_role = 'employee';
		}
		$mszdata = array(
			'company_id' => $consultant_id,
			'message' => $message,
			'from_user' => $employee_id,
			'to_user' => $to_user,
			'from_role' => 'employee',
			'to_role' => $to_role,
			'title' => $title,
			'date_time' => $date_time
		);
		$done    = $this->db->insert('individual_message', $mszdata);
		$data_id = $this->db->insert_id();
		if ($done) {
			$mszdata1 = array(
				'company_id' => $consultant_id,
				'message' => $message,
				'from_user' => $employee_id,
				'to_user' => $to_user,
				'from_role' => 'employee',
				'to_role' => $to_role,
				'title' => $title,
				'data_id' => $data_id,
				'date_time' => $date_time
			);
			$this->db->insert('individual_message_data', $mszdata1);
		}
//		$this->load->model('sendmail');
//		$this->sendmail->emails($mails, $message);
		redirect('Employee/individual_message');
	}

	public function show_individual_message($id = '')
	{
		$data['dd1'] = 'active';
		$data['d2'] = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id  = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$this->db->where('data_id', $id);
			$data['message'] = $this->db->get('individual_message_data')->result();
			$data['title']   = "Messages";
			$this->db->where('id', $id);
			$data['title_msz'] = $this->db->get('individual_message')->row();
			$this->load->view('employee/show_individual_message', $data);
		} else {
			redirect('Welcome');
		}
	}

	function mails_to_indi_data()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id  = $this->session->userdata('consultant_id');
		$email       = $this->input->post('email');
		$title       = $this->input->post('title');
		$message     = $this->input->post('message');
		$to_user     = $this->input->post('to_user');
		$data_id     = $this->input->post('data_id');
		$from_role   = 'Employee';
		$date_time   = date('Y-m-d');
		if ($to_user == '0') {
			$ml      = $this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row();
			$mails   = $ml->email;
			$to_role = 'consultant';
		} else {
			$ml      = $this->db->query("select * from `employees` where `employee_id`='$to_user'")->row();
			$mails   = $ml->employee_email;
			$to_role = 'employee';
		}
		$mszdata1 = array(
			'company_id' => $consultant_id,
			'message' => $message,
			'from_user' => $employee_id,
			'to_user' => $to_user,
			'from_role' => $from_role,
			'to_role' => $to_role,
			'data_id' => $data_id,
			'date_time' => $date_time
		);
		$this->db->insert('individual_message_data', $mszdata1);
//		$this->load->model('sendmail');
//		$this->sendmail->emails($mails, $message);
		redirect('Employee/show_individual_message/' . $data_id);
	}

	public function audit_report()
	{
		$data['aa1'] = 'active';
		$data['a1']  = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title'] = "Audit Report";

			$sql = "SELECT
                            audit.*, type.type_of_audit, e.employee_name, f.*, t.trigger_name,
                            (DATEDIFF(audit.created_at,DATE(NOW())) + f.days) as status_days
                        FROM
                            audit_list audit
                        LEFT JOIN type_of_audit type ON audit.audit_type = type.type_id
                        LEFT JOIN employees e ON audit.lead_auditor = e.employee_id
                        LEFT JOIN frequency f ON audit.frequency = f.frequency_id
                        LEFT JOIN `trigger` t ON audit.`trigger` = t.trigger_id
                        WHERE
                            audit.company_id = " . $consultant_id . "
                        ORDER BY audit.created_at DESC, pa_id DESC ";
			$data['audits'] = $this->db->query($sql)->result();
			$this->load->view('employee/audit_report', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function auditees()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$data['cc1'] = 'active';
		$data['c2']  = 'act1';
		if ($employee_id) {
			$data['title'] = "Auditee List";
			$plan_id = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
			$rowdata1 = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `consultant_id`='$consultant_id'")->row()->emps;
			if ($plan_id) {
				$rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
				$data['total_account'] = $rowdata1;
				$data['limit']         = $rowdata;
				$data['reached']       = (($rowdata1 * 100) / $rowdata);
			}

			$sql = "SELECT
                            *, GROUP_CONCAT(t.utype_name) type_name
                        FROM
                            employees e
                        LEFT JOIN permision p ON e.employee_id = p.employee_id
                        LEFT JOIN user_type t ON p.type_id = t.utype_id
                        WHERE
                            e.consultant_id = " . $consultant_id . " AND p.type_id = 4
                        GROUP BY
                            e.employee_id";

			$data['employees'] = $this->db->query($sql)->result();
			$this->load->view('employee/auditees', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function add_auditee()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$employee_name  = $this->input->post('add_name');
			$employee_email = $this->input->post('add_email');
            /*=-=- check user mobile number valid start =-=-*/
            $this->load->library('phone_RK');
            $phone          = formatMobileNumber($this->input->post('add_phone'));;
            $phone_response = $this->phone_rk->checkPhoneNumber($phone);
            if (!$phone_response['success']){
				
                $this->session->set_flashdata('phone_response', $phone_response);
                redirect($_SERVER['HTTP_REFERER']);
                return;
            }
            /*=-=- check user mobile number valid end =-=-*/
			$role_name = $this->input->post('add_role');
			$username = $this->input->post('add_username');
			$created_at     = date('Y-m-d');

			if (!empty(trim($this->input->post('add_password')))){
                $uppercase = preg_match('@[A-Z]@', $this->input->post('add_password'));
                $lowercase = preg_match('@[a-z]@', $this->input->post('add_password'));
                $number    = preg_match('@[0-9]@', $this->input->post('add_password'));
                $specialChars = preg_match('@[^\w]@', $this->input->post('add_password'));
                if(strlen($this->input->post('add_password')) < 8){
                    $this->session->set_flashdata('message',"pwd_error");
                    redirect('Employee/auditees');
                }else if(!$uppercase ){
                    $this->session->set_flashdata('message',"pwd_error");
                    redirect('Employee/auditees');
                }else if(!$lowercase){
                    $this->session->set_flashdata('message',"pwd_error");
                    redirect('Employee/auditees');
                }else if(!$number){
                    $this->session->set_flashdata('message',"pwd_error");
                    redirect('Employee/auditees');
                }else if(!$specialChars){
                    $this->session->set_flashdata('message',"pwd_error");
                    redirect('Employee/auditees');
                }
				$password = getHashedPassword($this->input->post('add_password'));
                $data           = array(
					'consultant_id' => $consultant_id,
					'employee_name' => $employee_name,
					'username' => $username,
					'employee_email' => $employee_email,
					'employee_phone' => $phone,
					'role' => $role_name,
					'password' => $password,
					'created_at' => $created_at,
					'status' => 1
				);
            }else{
                $data           = array(
					'consultant_id' => $consultant_id,
					'employee_name' => $employee_name,
					'username' => $username,
					'employee_email' => $employee_email,
					'employee_phone' => $phone,
					'role' => $role_name,
					'created_at' => $created_at,
					'status' => 1
				);
            }
			
            
			$employee_list = $this->db->query("SELECT * FROM `employees` WHERE `username`='$username'")->row();
			if($employee_list == null) {
				$plan_id        = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
				if ($plan_id) {
					$rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
				}
				$rowdata1 = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `consultant_id`='$consultant_id'")->row()->emps;
				if ($rowdata == $rowdata1 || $rowdata1 > $rowdata) {
					$this->session->set_flashdata('message', 'failed');
					redirect('Employee/auditees');
				} else {
					$done = $this->db->insert('employees', $data);
					if ($done) {
						$employee_id = $this->db->get('employees')->last_row()->employee_id;
						$tmp = array(
							'employee_id' => $employee_id,
							'type_id' => 4
						);
						$this->db->insert('permision', $tmp);

						$this->session->set_flashdata('message', 'success');
						redirect('Employee/auditees');
					} else {
						$this->session->set_flashdata('message', 'failed');
						redirect('Employee/auditees');
					}
				}
			} else {
				$this->session->set_flashdata('message', 'live_err');
				redirect('Employee/auditees');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function edit_auditee()
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$employee_name  = $this->input->post('edit_name');
			$employee_email = $this->input->post('edit_email');
            /*=-=- check user mobile number valid start =-=-*/
            $this->load->library('phone_RK');
            $phone          = formatMobileNumber($this->input->post('edit_phone'));;
            $phone_response = $this->phone_rk->checkPhoneNumber($phone);
            if (!$phone_response['success']){
                $this->session->set_flashdata('phone_response', $phone_response);
                redirect($_SERVER['HTTP_REFERER']);
                return;
            }
            /*=-=- check user mobile number valid end =-=-*/
			$role_name = $this->input->post('edit_role');
			$username = $this->input->post('edit_username');
			$password = getHashedPassword($this->input->post('edit_password'));
			$id = $this->input->post('employee_id');
			$old_username = $this->input->post('old_username');
			$data           = array(
				'consultant_id' => $consultant_id,
				'employee_name' => $employee_name,
				'username' => $username,
				'role' => $role_name,
				'employee_email' => $employee_email,
                'employee_phone' => $phone,
				'password' => $password
			);
            if (empty(trim($this->input->post('edit_password')))){
                unset($data['password']);
            }
			$employee_list = $this->db->query("SELECT * FROM `employees` WHERE `username`='$username'")->row();
			if($employee_list == null) {
				$this->db->where('employee_id', $id);
				$done = $this->db->update('employees', $data);
				if ($done) {
					$this->session->set_flashdata('message', 'update_success');
					redirect('Employee/auditees');
				} else {
					$this->session->set_flashdata('message', 'failed');
					redirect('Employee/auditees');
				}
			} else {
				if($old_username == $username) {
					$this->db->where('employee_id', $id);
					$done = $this->db->update('employees', $data);
					if ($done) {
						$this->session->set_flashdata('message', 'update_success');
						redirect('Employee/auditees');
					} else {
						$this->session->set_flashdata('message', 'failed');
						redirect('Employee/auditees');
					}
				} else {
					$this->session->set_flashdata('message', 'live_err');
					redirect('Employee/auditees');
				}
			}
		} else {
			redirect('Welcome');
		}
	}

	public function delete_auditee($id = Null)
	{
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$this->db->where('employee_id', $id);
			$done = $this->db->delete('employees');
			if ($done) {
				$this->db->where('employee_id', $id);
				$confirm = $this->db->delete('permision');
				if($confirm) {
					$this->session->set_flashdata('message', 'success_del');
					redirect('Employee/auditees');
				} else {
					$this->session->set_flashdata('message', 'error');
					redirect('Employee/auditees');
				}
			} else {
				$this->session->set_flashdata('message', 'error');
				redirect('Employee/auditees');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function open_audit()
	{
		$data['aa1'] = 'active';
		$data['a2']  = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$user_type = $this->session->userdata('user_type');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title'] = "Open Audit";

			$sql = "SELECT
                            audit.*, type.type_of_audit, e.employee_name, f.*, t.trigger_name,g.*,
                            (DATEDIFF(audit.created_at,DATE(NOW())) + f.days) as status
                        FROM
                            audit_list audit
                        LEFT JOIN type_of_audit type ON audit.audit_type = type.type_id
                        LEFT JOIN employees e ON audit.lead_auditor = e.employee_id
                        LEFT JOIN frequency f ON audit.frequency = f.frequency_id
                        LEFT JOIN audit_log_list g ON audit.pa_id = g.audit_id
                        LEFT JOIN `trigger` t ON audit.`trigger` = t.trigger_id
                        WHERE
                            g.status = 2 and
                            audit.company_id = " . $consultant_id;
			if ($user_type == "Lead Auditor"){
				$sql .= " and audit.lead_auditor = ".$employee_id;
			}else if ($user_type == "Auditor"){
				$sql1 = "select * from select_process as a where auditor = ".$employee_id." group by audit_id";
				$audit_temp = $this->db->query($sql1)->result();
				if (count($audit_temp) > 0){
					$sql .= " and (";
				}else{
					$sql .= " and ( 1= 0";
				}
				$index = 0;
				foreach ($audit_temp as $item) {
					$index++;
					if ($index == count($audit_temp)){
						$sql .= "g.log_id = ".$item->audit_id;
					}else{
						$sql .= "g.log_id = ".$item->audit_id." OR ";
					}
				}
				$sql .= ")";
			}else if ($user_type == "Process Owner"){
				$sql1 = "select * from select_process as a where process_owner = ".$employee_id." group by audit_id";
				$audit_temp = $this->db->query($sql1)->result();
				if (count($audit_temp) > 0){
					$sql .= " and (";
				}else{
					$sql .= " and ( 1= 0";
				}
				$index = 0;
				foreach ($audit_temp as $item) {
					$index++;
					if ($index == count($audit_temp)){
						$sql .= "g.log_id = ".$item->audit_id;
					}else{
						$sql .= "g.log_id = ".$item->audit_id." OR ";
					}
				}
				$sql .= ")";
			}
			else if ($user_type == "Auditee"){
				$sql1 = "select * from select_process as a where sme = ".$employee_id." group by audit_id";
				$audit_temp = $this->db->query($sql1)->result();
				if (count($audit_temp) > 0){
					$sql .= " and (";
				}else{
					$sql .= " and ( 1= 0";
				}
				$index = 0;
				foreach ($audit_temp as $item) {
					$index++;
					if ($index == count($audit_temp)){
						$sql .= "g.log_id = ".$item->audit_id;
					}else{
						$sql .= "g.log_id = ".$item->audit_id." OR ";
					}
				}
				$sql .= ")";
			}
            $sql .= " ORDER BY audit.created_at DESC, pa_id DESC ";
			$data['open_audits'] = $this->db->query($sql)->result();
			$this->load->view('employee/open_audit', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function edit_audit_plan($id = null)
	{
		$data['aa1'] = 'active';
		$data['a2']  = 'act1';
		$auditee = 4;
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$user_type = $this->session->userdata('user_type');
		if ($employee_id) {
			$data['title'] = "Edit Audit Plan";

			$process_id = $this->input->post('process_id');
			$sme_list = $this->input->post('sme_list');
			if($process_id != null) {
				$array = array(
					'sme' => $sme_list
				);
				$this->db->where('id', $process_id);
				$done = $this->db->update('select_process', $array);
			}

			$where = "";
			if($user_type != 'Lead Auditor') {
				if($user_type == 'Auditor') {
					$where = " AND sp.auditor = " . $employee_id;
				} else if ($user_type == 'Process Owner') {
					$where = " AND sp.process_owner = " . $employee_id;
				} else if ($user_type == 'Auditee') {
					$where = " AND sp.sme = " . $employee_id;
				}
			}

			$sql = "SELECT *,sp.id as sp_id, (
                        SELECT
                            employee_name
                        FROM
                            employees
                        WHERE
                            sp.auditor = employees.employee_id
                    ) AS auditor_name,
                    (
                        SELECT
                            employee_name
                        FROM
                            employees
                        WHERE
                            sp.process_owner = employees.employee_id
                    ) AS process_owner_name,
                    (
                        SELECT
                            employee_name
                        FROM
                            employees
                        WHERE
                            sp.sme = employees.employee_id
                    ) AS sme_name,
                    DATE_FORMAT(sp.start_time,'%M %e, %Y %l:%i %p') as starttime_type,
                    DATE_FORMAT(sp.end_time,'%M %e, %Y %l:%i %p') as endtime_type,
                    (100 * ((DATEDIFF(sp.end_time,sp.start_time) + DATEDIFF(sp.end_time,DATE(NOW())))/DATEDIFF(sp.end_time,sp.start_time))) as efficiency,
                    DATEDIFF(sp.end_time,DATE(NOW())) as past_due
                      FROM
                    process_list AS process
                    left join select_process AS sp on process.process_id = sp.process_id
                        WHERE
                            sp.audit_id = " . $id . " AND sp.status != 0" . $where . "
                        ORDER BY process.process_id DESC ";
			$data['process'] = $this->db->query($sql)->result();
			$data['audit_id'] = $id;

			$this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
			$this->db->where('employees.consultant_id', $consultant_id);
			$this->db->where('permision.type_id', $auditee);
			$data['smes'] = $this->db->get('employees')->result();
			$this->load->view('employee/edit_audit_plan', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function close_audit_plan($id)
	{
		$data['aa1'] = 'active';
		$data['a2']  = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		$up = array(
			'status' => '1',
			'closed_date' => date('Y-m-d')
		);
		if ($employee_id) {
			$this->db->where('log_id', $id);
			$done = $this->db->update('audit_log_list', $up);
			if ($done) {
				$this->session->set_flashdata('message', 'close_success');
				redirect('Employee/open_audit');
			} else {
				redirect('Employee/open_audit');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function edit_checklist_mind($id = null)
	{
		$data['aa1'] = 'active';
		$data['a2']  = 'act1';
		$data1 = array(
			'map_type' => '1'
		);
		$this->db->where('id', $id);
		$done = $this->db->update('select_process', $data1);
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title'] = "CheckList";
			$this->db->where('company_id',$consultant_id);
			$first_clause = $this->db->get('clause')->result();

			$this->db->select("*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id > 0 ');
			$this->db->where('process_id',$id);
			if (count($first_clause) != 0){
				$this->db->where('clause_id',$first_clause[0]->id);
			}else{
				$this->db->where('clause_id','0');
			}
			$data['checklist'] = $this->db->get('checklist')->result();
			$this->db->where('company_id',$consultant_id);
			$data['clause_list'] = json_encode($this->db->get('clause')->result());
			$data['process_id'] = $id;
			$data['clause_id'] = '0';
			$this->load->view('employee/edit_checklist_mind', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function edit_checklist_process($id = null)
	{
		$data['aa1'] = 'active';
		$data['a2']  = 'act1';
		$data1 = array(
			'map_type' => '2'
		);
		$this->db->where('id', $id);
		$done = $this->db->update('select_process', $data1);
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title'] = "CheckList";

			$this->db->select("`checklist`.*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-1');
			$this->db->where('process_id',$id);
			$data['input_step'] = $this->db->get('checklist')->result();
			$this->db->select("`checklist`.*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-2');
			$this->db->where('process_id',$id);
			$data['activity'] = $this->db->get('checklist')->result();
			$this->db->select("`checklist`.*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-3');
			$this->db->where('process_id',$id);
			$data['output'] = $this->db->get('checklist')->result();
			$this->db->select("`checklist`.*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-4');
			$this->db->where('process_id',$id);
			$data['control'] = $this->db->get('checklist')->result();
			$this->db->select("`checklist`.*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-5');
			$this->db->where('process_id',$id);
			$data['resource'] = $this->db->get('checklist')->result();
			$this->db->select("`checklist`.*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-6');
			$this->db->where('process_id',$id);
			$data['effectiveness'] = $this->db->get('checklist')->result();
			$this->db->select("`checklist`.*,checklist.id as checklist_id");
			$data['process_id'] = $id;

			$this->load->view('employee/edit_checklist_process', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function view_checklist_process($id = null)
	{
		$data['aa1'] = 'active';
		$data['a3']  = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title'] = "CheckList";

			$this->db->select("*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-1');
			$this->db->where('process_id',$id);
			$data['input_step'] = $this->db->get('checklist')->result();
			$this->db->select("*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-2');
			$this->db->where('process_id',$id);
			$data['activity'] = $this->db->get('checklist')->result();
			$this->db->select("*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-3');
			$this->db->where('process_id',$id);
			$data['output'] = $this->db->get('checklist')->result();
			$this->db->select("*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-4');
			$this->db->where('process_id',$id);
			$data['control'] = $this->db->get('checklist')->result();
			$this->db->select("*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-5');
			$this->db->where('process_id',$id);
			$data['resource'] = $this->db->get('checklist')->result();
			$this->db->select("*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id','-6');
			$this->db->where('process_id',$id);
			$data['effectiveness'] = $this->db->get('checklist')->result();
			$data['process_id'] = $id;
			$this->load->view('employee/view_checklist_process', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function create_checklist()
	{
		$data['aa1'] = 'active';
		$data['a2']  = 'act1';
		$auditee = 4;
		$process_id = $this->input->post('process_id');
		$clause_id = $this->input->post('clause_id');
		$employee_id = $this->session->userdata('employee_id');
		$consultant_id = $this->session->userdata('consultant_id');
		if ($employee_id) {
			$data['title'] = "CheckList";
			$sql = "SELECT *,sp.id as sp_id, (
                        SELECT
                            employee_name
                        FROM
                            employees
                        WHERE
                            sp.auditor = employees.employee_id
                    ) AS auditor_name,
                    (
                        SELECT
                            employee_name
                        FROM
                            employees
                        WHERE
                            sp.process_owner = employees.employee_id
                    ) AS process_owner_name
                      FROM
                    process_list AS process
                    left join select_process as sp on process.process_id = sp.process_id
                        WHERE
                            sp.id = " . $process_id . "
                        ORDER BY process.process_id DESC ";
			$process = $this->db->query($sql)->result();
			/*echo "<pre>";
			print_r($process);
			echo "</pre>";
			die("dsffdsf");*/
			if (count($process) > 0){
				$data['process_name'] = $process[0]->process_name;
				$data['start_time'] = $process[0]->start_time;
				$data['end_time'] = $process[0]->end_time;
				$data['auditor'] = $process[0]->auditor_name;
				$data['process_owner'] = $process[0]->process_owner_name;
				$data['process_id'] = $process_id;
				$data['clause_id'] = $clause_id;
				$data['checklist_id'] = '0';
				$data['process__id'] = $process[0]->process_id;
				$data['audit_id'] = $process[0]->audit_id;
				$this->db->where('consultant_id',$consultant_id);
				$data['audit_criteria'] = $this->db->get('audit_criteria')->result();

				$this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
				$this->db->where('employees.consultant_id', $consultant_id);
				// $this->db->where('permision.type_id', $auditee);
				$data['smes'] = $this->db->get('employees')->result();
				
				// $data['smes'] = $this->employee->getSMES($consultant_id);
				$this->load->view('employee/create_checklist', $data);

			}else{
				redirect('Welcome');
			}
		} else {
			redirect('Welcome');
		}
	}
	public function clone_audit($id = null)
	{
		$consultant_id  = $this->session->userdata('consultant_id');
		if ($consultant_id) {
			$this->db->where('log_id',$id);
			$clone_audit = $this->db->get('audit_log_list')->result();
			$data = array(
					'audit_id' => $clone_audit[0]->audit_id,
					'brief_id' => $clone_audit[0]->brief_id,
					'plan_id' => $clone_audit[0]->plan_id,
					'status' => $clone_audit[0]->status,
					'submited_date' => $clone_audit[0]->submited_date,
					'closed_date' => $clone_audit[0]->closed_date,
					'submited' => $clone_audit[0]->submited,
					'header_text' => $clone_audit[0]->header_text,
					'header_align' => $clone_audit[0]->header_align,
					'footer_text' => $clone_audit[0]->footer_text,
					'footer_align' => $clone_audit[0]->footer_align,
					'logo_filename' => $clone_audit[0]->logo_filename
			);
			$this->db->insert('audit_log_list', $data);
			$clone_audit_id = $this->db->insert_id();

			$this->db->where('audit_id',$id);
			$clone_audit_plan = $this->db->get('audit_plan')->row();
			unset($clone_audit_plan->plan_id);
			$clone_audit_plan->audit_id = $clone_audit_id;
			$this->db->insert('audit_plan', $clone_audit_plan);
			$clone_plan_id = $this->db->insert_id();

			$this->db->where('log_id', $clone_audit_id);
			$this->db->update('audit_log_list', array('plan_id' => $clone_plan_id));

			$clone_audit_brief = $this->db->get('audit_brief')->row();
			unset($clone_audit_brief->brief_id);
			$clone_audit_brief->audit_id = $clone_audit_id;
			$clone_audit_brief->refer_num = time();
			$this->db->insert('audit_brief', $clone_audit_brief);
			$clone_brief_id = $this->db->insert_id();

			$this->db->where('log_id', $clone_audit_id);
			$this->db->update('audit_log_list', array('brief_id' => $clone_brief_id));

			$this->db->where('audit_id',$id);
			$clone_processes = $this->db->get('select_process')->result();
			foreach ($clone_processes as $clone_process) {
				$process_id = $clone_process->id;
				unset($clone_process->id);
				$clone_process->audit_id = $clone_audit_id;
				$this->db->insert('select_process', $clone_process);
				$clone_process_id = $this->db->insert_id();
				$this->db->where('process_id',$process_id);
				$clone_checklists = $this->db->get('checklist')->result();
				foreach ($clone_checklists as $clone_checklist) {
					unset($clone_checklist->id);
					$clone_checklist->process_id = $clone_process_id;
					$this->db->insert('checklist', $clone_checklist);
				}
			}
			redirect('employee/open_audit');
		} else {
			redirect('Welcome');
		}
	}
	public function update_checklist(){
		$process_id = $this->input->post('process_id');
		$checklist_id = $this->input->post('checklist_id');
		$clause_id = $this->input->post('clause_id');
		$process_type = $this->input->post('process_type');
		$expected_answer = $this->input->post('expected_answer');
		$evidence = $this->input->post('evidence');
		$questions = $this->input->post('questions');
		$audit_trail = $this->input->post('audit_trail');
		$audit_criteria = $this->input->post('audit_criteria');
		$audit_criteria2 = $this->input->post('audit_criteria2');
		$audit_criteria3 = $this->input->post('audit_criteria3');
		$audit_criteria4 = $this->input->post('audit_criteria4');
		$notes = $this->input->post('notes');
		$effectiveness = $this->input->post('effectiveness');
		if ($expected_answer == '2'){
			$status = "Conformity Table";
		}else if ($expected_answer == '1'){
			$status = "Non-Conformity Table";
		}else if ($expected_answer == '0'){
			if ($audit_trail == '2'){
				$status = "Conformity Table";
			}else if ($audit_trail == '1'){
				$status = "Non-Conformity Table";
			}else if($audit_trail == '0'){
				$status = "Opportunity For Improvement";
			}
		}

		///////////////////////////update auditee////////////////////////////
		$edit_process_id = $this->input->post("edit_process_id");
		$pa_id = $this->input->post("audit_id");
		$auditee_array = $this->input->post("edit_auditee");

		$auditee = "";
		foreach($auditee_array as $row){
		    $auditee .= $row . ", ";
		}
		$auditee = substr($auditee, 0, -2);
		$array = array(
			'sme' => $auditee,
		);
	    $this->db->where('audit_id', $pa_id);
	    $this->db->where('process_id', $edit_process_id);
	    $this->db->update('select_process', $array);
	    //die("dfsdf");
		///////////////////////////////////////////////////////////////////
		$data = array(
				'process_id' => $process_id,
				'process_step' => $process_type,
				'questions' => $questions,
				'criteria_id' => $audit_criteria,
				'criteria_id2' => $audit_criteria2,
				'criteria_id3' => $audit_criteria3,
				'criteria_id4' => $audit_criteria4,
				'answer' => $expected_answer,
				'audit_trail' => $audit_trail,
				'evidence' => json_encode($evidence),
				'note' => $notes,
				'status' => $status,
				'effectiveness' => $effectiveness,
				'auditees' => $auditee
		);
		$this->db->where('id', $checklist_id);
		$done = $this->db->update('checklist', $data);
		if ($clause_id < 0){
			redirect('employee/edit_checklist_process/'.$process_id);
		}else{
			redirect('employee/edit_checklist_mind/'.$process_id);
		}
	}
	public function get_checklist_mind($id = null)
	{
		$data['aa1'] = 'active';
		$data['a2']  = 'act1';
		$process_id = $this->input->post('process_id');
		$clause_id = $this->input->post('clause_id');
		$consultant_id  = $this->session->userdata('consultant_id');
		if ($consultant_id) {
			$data['title'] = "CheckList";

			$this->db->select("*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id',$clause_id);
			$this->db->where('process_id',$process_id);
			$data['checklist'] = $this->db->get('checklist')->result();
			$this->db->where('company_id',$consultant_id);
			$data['clause_list'] = json_encode($this->db->get('clause')->result());
			$data['process_id'] = $process_id;
			$data['clause_id'] = $clause_id;
			$this->load->view('employee/edit_checklist_mind', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function get_checklist_mind_view($id = null)
	{
		$data['aa1'] = 'active';
		$data['a3']  = 'act1';
		$process_id = $this->input->post('process_id');
		$clause_id = $this->input->post('clause_id');
		$consultant_id  = $this->session->userdata('consultant_id');
		if ($consultant_id) {
			$data['title'] = "CheckList";

			$this->db->select("*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id',$clause_id);
			$this->db->where('process_id',$process_id);
			$data['checklist'] = $this->db->get('checklist')->result();
			$this->db->where('company_id',$consultant_id);
			$data['clause_list'] = json_encode($this->db->get('clause')->result());
			$data['process_id'] = $process_id;
			$data['clause_id'] = $clause_id;
			$this->load->view('employee/view_checklist_mind', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function save_checklist(){
		$process_id = $this->input->post('process_id');
		$clause_id = $this->input->post('clause_id');
		$process_type = $this->input->post('process_type');
		$expected_answer = $this->input->post('expected_answer');
		$evidence = $this->input->post('evidence');
		$questions = $this->input->post('questions');
		$audit_trail = $this->input->post('audit_trail');
		$audit_criteria = $this->input->post('audit_criteria');
		$audit_criteria2 = $this->input->post('audit_criteria2');
		$audit_criteria3 = $this->input->post('audit_criteria3');
		$audit_criteria4 = $this->input->post('audit_criteria4');
		$notes = $this->input->post('notes');
		$effectiveness = $this->input->post('effectiveness');


		///////////////////////////update auditee////////////////////////////
		$edit_process_id = $this->input->post("edit_process_id");
		$pa_id = $this->input->post("audit_id");
		$auditee_array = $this->input->post("edit_auditee");

		$auditee = "";
		foreach($auditee_array as $row){
		    $auditee .= $row . ", ";
		}
		$auditee = substr($auditee, 0, -2);
		    $array = array(
		        'sme' => $auditee,
		    );
	    $this->db->where('audit_id', $pa_id);
	    $this->db->where('process_id', $edit_process_id);
	    $this->db->update('select_process', $array);
		///////////////////////////////////////////////////////////////////

		if ($expected_answer == '2'){
			$status = "Conformity Table";
		}else if ($expected_answer == '1'){
			$status = "Non-Conformity Table";
		}else if ($expected_answer == '0'){
			if ($audit_trail == '2'){
				$status = "Conformity Table";
			}else if ($audit_trail == '1'){
				$status = "Non-Conformity Table";
			}else if($audit_trail == '0'){
				$status = "Opportunity For Improvement";
			}
		}
		$data = array(
			'process_id' => $process_id,
			'clause_id' => $clause_id,
			'process_step' => $process_type,
			'questions' => $questions,
			'criteria_id' => $audit_criteria,
			'criteria_id2' => $audit_criteria2,
			'criteria_id3' => $audit_criteria3,
			'criteria_id4' => $audit_criteria4,
			'answer' => $expected_answer,
			'audit_trail' => $audit_trail,
			'evidence' => json_encode($evidence),
			'note' => $notes,
			'status' => $status,
			'effectiveness' => $effectiveness,
			'auditees' => $auditee
		);
		$done = $this->db->insert('checklist', $data);

		if ($clause_id < 0){
			redirect('employee/edit_checklist_process/'.$process_id);
		}else{
			redirect('employee/edit_checklist_mind/'.$process_id);
		}
	}

	public function delete_checklist()
	{
		$checklist_id = $this->input->post('checklist_id');
		$consultant_id  = $this->session->userdata('consultant_id');
		if ($consultant_id) {
			$data = $this->db->query("delete from checklist where id = '$checklist_id'");
			echo json_encode($data);
		} else {
			redirect('Welcome');
		}
	}

	public function edit_checklist()
	{
		$data['aa1'] = 'active';
		$data['a2']  = 'act1';
		$auditee = 4;
		$process_id = $this->input->post('process_id');
		$clause_id = $this->input->post('clause_id');
		$checklist_id = $this->input->post('checklist_id');
		$consultant_id  = $this->session->userdata('consultant_id');
		if ($consultant_id) {
			$data['title'] = "CheckList";
			$sql = "SELECT *,sp.id as sp_id, (
                        SELECT
                            employee_name
                        FROM
                            employees
                        WHERE
                            sp.auditor = employees.employee_id
                    ) AS auditor_name,
                    (
                        SELECT
                            employee_name
                        FROM
                            employees
                        WHERE
                            sp.process_owner = employees.employee_id
                    ) AS process_owner_name
                      FROM
                    process_list AS process
                    left join select_process as sp on process.process_id = sp.process_id
                        WHERE
                            sp.id = " . $process_id . "
                        ORDER BY process.process_id DESC ";
			$process = $this->db->query($sql)->result();
			if (count($process) > 0){
				$data['process_name'] = $process[0]->process_name;
				$data['start_time'] = $process[0]->start_time;
				$data['end_time'] = $process[0]->end_time;
				$data['auditor'] = $process[0]->auditor_name;
				$data['process_owner'] = $process[0]->process_owner_name;
				$data['process_id'] = $process_id;
				$data['clause_id'] = $clause_id;
				$data['process__id'] = $process[0]->process_id;
                $data['audit_id'] = $process[0]->audit_id;
				$this->db->where('id', $checklist_id);
				$checklist = $this->db->get('checklist')->result();
				$data['checklist_id'] = $checklist_id;
				$data['checklist'] = $checklist;
				$this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
				$this->db->where('employees.consultant_id', $consultant_id);
				// $this->db->where('permision.type_id', $auditee);
				$data['smes'] = $this->db->get('employees')->result();
				// print_r($this->db->last_query());
				// die();
				$this->load->view('employee/edit_checklist', $data);


			}else{
				redirect('Welcome');
			}
		} else {
			redirect('Welcome');
		}
	}
	public function submit_checklist()
	{
		$data['aa1'] = 'active';
		$data['a2']  = 'act1';
		$process_id = $this->input->post('process_id');
		$consultant_id  = $this->session->userdata('consultant_id');
		if ($consultant_id) {
			$data['title'] = "CheckList";
			$data1 = array(
					'status' => '1'
			);
			$this->db->where('id', $process_id);
			$done = $this->db->update('select_process', $data1);
			$this->db->where('id', $process_id);
			$audit = $this->db->get('select_process')->result();
			if (count($audit) > 0){

				//----------------------------------get info ----------------------------------
				$audit_info = $this->db->where('id', $process_id)->get('select_process')->row();
				if(isset($auditor_info)){
					$process_name = $this->db->where('process_id', $audit_info->process_id)->get('process_list')->row()->process_name;
					$pa_id = $this->db->where('log_id', $audit_info->audit_id)->get('audit_log_list')->row()->audit_id;
					$lead_auditor_id = $this->db->where('pa_id', $pa_id)->get('audit_list')->row()->lead_auditor;
					$lead_auditor_info = $this->db->where('employee_id', $lead_auditor_id)->get('employees')->row();
					$auditor_info = $this->db->where('employee_id', $audit_info->auditor)->get('employees')->row();
					$process_owner_info = $this->db->where('employee_id', $audit_info->process_owner)->get('employees')->row();
					$nonconformity_count = $this->db->query("SELECT COUNT(*) count FROM checklist WHERE process_id = '$process_id' && status = 'Non-Conformity Table'")->row()->count;
					$Opportunities_count = $this->db->query("SELECT COUNT(*) count FROM checklist WHERE process_id = '$process_id' && status = 'Opportunity for Improvement'")->row()->count;
					//-----------------------------------------------------------------------------
					//----------------------------------send email----------------------------------
					$email_temp = $this->getEmailTemp('Completion sent to Lead Auditor');
					$email_temp['message'] = str_replace("{Lead Auditor NAME}", $lead_auditor_info->employee_name, $email_temp['message']);
					$email_temp['message'] = str_replace("{Process Name}", $process_name, $email_temp['message']);
					$email_temp['message'] = str_replace("{Auditor Name}", $auditor_info->employee_name, $email_temp['message']);
					if($nonconformity_count == 0)
						$email_temp['message'] = str_replace("{nonconformity}", "out Non-conformity", $email_temp['message']);
					else
						$email_temp['message'] = str_replace("{nonconformity}", " nonconformity", $email_temp['message']);
					$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
					$this->sendemail($lead_auditor_info->employee_email, 'Completion sent to Lead Auditor', $email_temp['message'], $email_temp['subject'], 2);

					$email_temp = $this->getEmailTemp('Completion sent to Auditor');
					$email_temp['message'] = str_replace("{Process Name}", $process_name, $email_temp['message']);
					$email_temp['message'] = str_replace("{Auditor Name}", $auditor_info->employee_name, $email_temp['message']);
					if($nonconformity_count == 0)
						$email_temp['message'] = str_replace("{nonconformity}", "out Non-conformity", $email_temp['message']);
					else
						$email_temp['message'] = str_replace("{nonconformity}", " nonconformity", $email_temp['message']);
					$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
					$this->sendemail($auditor_info->employee_email, 'Completion sent to Auditor', $email_temp['message'], $email_temp['subject'],2 );

					$tit = 'Completion sent to Process Owner without Nonconformity';
					if($nonconformity_count == 0 && $Opportunities_count == 0){
						$email_temp = $this->getEmailTemp('Completion sent to Process Owner without Nonconformity');
						$email_temp['message'] = str_replace("{Process Name}", $process_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Process Owner Name}", $process_owner_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Auditor Name}", $auditor_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{number of nonconformities}", $nonconformity_count." of nonconformities", $email_temp['message']);
						$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
						$this->sendemail($process_owner_info->employee_email, $tit, $email_temp['message'], $email_temp['subject'], 2);
					}
				}

//				else{
//					$email_temp = $this->getEmailTemp('Completion sent to Process Owner with Nonconformity');
//					$tit = 'Completion sent to Process Owner with Nonconformity';
//				}

				//------------------------------------------------------------------------------

				redirect('employee/edit_audit_plan/'.$audit[0]->audit_id);
			}else{
				redirect('employee/open_audit');
			}
		} else {
			redirect('Welcome');
		}
	}

	public function corrective_action_form($id = '')
	{
		$consultant_id  = $this->session->userdata('consultant_id');
		$user_type = $this->session->userdata('user_type');
		$data['bb1'] = 'active';
		$data['b1']  = 'act1';
		if ($consultant_id) {
			$data['title'] = "CORRECTIVE ACTIONS FORM";
			if ($id != '') {
				$data['checklist_id'] = $id;
				$sql = "SELECT
					a.criteria_id,
					a.note,
					a.status,
					e.process_id,
					d.type_id,
					e.sme,
					c.trigger,
					e.process_owner,
					b.description,
					b.process_id as process_name
				FROM
					checklist AS a
				LEFT JOIN select_process AS e ON a.process_id = e.id
				LEFT JOIN process_list AS b ON e.process_id = b.process_id
				LEFT JOIN audit_log_list AS f ON e.audit_id =f.log_id
				LEFT JOIN audit_list AS c ON f.audit_id = c.pa_id
				LEFT JOIN type_of_audit AS d ON c.audit_type = d.type_id
				WHERE
					a.id = " . $id;
				$data['selected_item'] = $this->db->query($sql)->row();
				$this->db->where('company_id', $consultant_id);
				$data['audit_type'] = $this->db->get('type_of_audit')->result();
				$this->db->where('company_id', $consultant_id);
				$this->db->where('type_of_audit', $data['selected_item']->type_id);
				$data['process'] = $this->db->get('process_list')->result();
				$this->db->join("permision","permision.employee_id = employees.employee_id","left");
				$this->db->where('employees.consultant_id', $consultant_id);
				$this->db->where('permision.type_id', '3');
				$data['process_owners'] = $this->db->get('employees')->result();
				$this->db->join("permision","permision.employee_id = employees.employee_id","left");
				$this->db->where('employees.consultant_id', $consultant_id);
				$this->db->where('permision.type_id', '4');
				$data['auditees'] = $this->db->get('employees')->result();
				$this->db->where('company_id', $consultant_id);
				$data['triggers'] = $this->db->get('trigger')->result();
				$this->db->where('company_id', $consultant_id);
				$data['clauses'] = $this->db->get('clause')->result();
				$this->db->where('company_id',$consultant_id);
				$data['user_type'] = $user_type;
				$data['clause_list'] = json_encode($this->db->get('clause')->result());

				$this->db->where('id', $id)->update('checklist', array('load_status'=> 1));

				$this->load->view('employee/corrective_action_form_two', $data);
			} else {
				$this->db->where('company_id', $consultant_id);
				$data['audit_type'] = $this->db->get('type_of_audit')->result();
				$this->db->where('company_id', $consultant_id);
				$data['process'] = $this->db->get('process_list')->result();
				$this->db->join("permision","permision.employee_id = employees.employee_id","left");
				$this->db->where('employees.consultant_id', $consultant_id);
				$this->db->where('permision.type_id', '3');
				$data['process_owners'] = $this->db->get('employees')->result();
				$this->db->join("permision","permision.employee_id = employees.employee_id","left");
				$this->db->where('employees.consultant_id', $consultant_id);
				$this->db->where('permision.type_id', '4');
				$data['auditees'] = $this->db->get('employees')->result();
				$this->db->where('company_id', $consultant_id);
				$data['triggers'] = $this->db->get('trigger')->result();
				$this->db->where('company_id', $consultant_id);
				$data['clauses'] = $this->db->get('clause')->result();
				$this->db->where('company_id',$consultant_id);
				$data['clause_list'] = json_encode($this->db->get('clause')->result());
				$data['user_type'] = $user_type;

				$this->load->view('employee/corrective_action_form', $data);

			}
		} else {
			redirect('Welcome');
		}
	}

	public function corrective_action_form_view($id = '')
	{
		$consultant_id  = $this->session->userdata('consultant_id');
		$user_type = $this->session->userdata('user_type');
		$data['bb1'] = 'active';
		$data['b1']  = 'act1';
		if ($consultant_id) {
			$data['title'] = "CORRECTIVE ACTIONS FORM";
			if ($id != '') {
				$data['checklist_id'] = $id;
				$sql = "SELECT
	a.criteria_id,
	a.note,
	a.status,
	e.process_id,
	d.type_id,
	e.sme,
	c.trigger,
	e.process_owner,
	b.description,
	b.process_id as process_name
FROM
	checklist AS a
LEFT JOIN select_process AS e ON a.process_id = e.id
LEFT JOIN process_list AS b ON e.process_id = b.process_id
LEFT JOIN audit_log_list AS f ON e.audit_id =f.log_id
LEFT JOIN audit_list AS c ON f.audit_id = c.pa_id
LEFT JOIN type_of_audit AS d ON c.audit_type = d.type_id
WHERE
	a.id = " . $id;
				$data['selected_item'] = $this->db->query($sql)->row();
				$this->db->where('company_id', $consultant_id);
				$data['audit_type'] = $this->db->get('type_of_audit')->result();
				$this->db->where('company_id', $consultant_id);
				$this->db->where('type_of_audit', $data['selected_item']->type_id);
				$data['process'] = $this->db->get('process_list')->result();
				$this->db->join("permision","permision.employee_id = employees.employee_id","left");
				$this->db->where('employees.consultant_id', $consultant_id);
				$this->db->where('permision.type_id', '3');
				$data['process_owners'] = $this->db->get('employees')->result();
				$this->db->join("permision","permision.employee_id = employees.employee_id","left");
				$this->db->where('employees.consultant_id', $consultant_id);
				$this->db->where('permision.type_id', '4');
				$data['auditees'] = $this->db->get('employees')->result();
				$this->db->where('company_id', $consultant_id);
				$data['triggers'] = $this->db->get('trigger')->result();
				$this->db->where('company_id', $consultant_id);
				$data['clauses'] = $this->db->get('clause')->result();
				$this->db->where('company_id',$consultant_id);
				$data['user_type'] = $user_type;
				$data['clause_list'] = json_encode($this->db->get('clause')->result());

				$this->db->where('id', $id)->update('checklist', array('load_status'=> 1));

				$this->load->view('employee/corrective_action_form_view', $data);
			} else {
				$this->db->where('company_id', $consultant_id);
				$data['audit_type'] = $this->db->get('type_of_audit')->result();
				$this->db->where('company_id', $consultant_id);
				$data['process'] = $this->db->get('process_list')->result();
				$this->db->join("permision","permision.employee_id = employees.employee_id","left");
				$this->db->where('employees.consultant_id', $consultant_id);
				$this->db->where('permision.type_id', '3');
				$data['process_owners'] = $this->db->get('employees')->result();
				$this->db->join("permision","permision.employee_id = employees.employee_id","left");
				$this->db->where('employees.consultant_id', $consultant_id);
				$this->db->where('permision.type_id', '4');
				$data['auditees'] = $this->db->get('employees')->result();
				$this->db->where('company_id', $consultant_id);
				$data['triggers'] = $this->db->get('trigger')->result();
				$this->db->where('company_id', $consultant_id);
				$data['clauses'] = $this->db->get('clause')->result();
				$this->db->where('company_id',$consultant_id);
				$data['clause_list'] = json_encode($this->db->get('clause')->result());
				$data['user_type'] = $user_type;

				$this->load->view('employee/corrective_action_form', $data);

			}
		} else {
			redirect('Welcome');
		}
	}
	public function view_checklist_mind($id = null)
	{
		$data['aa1'] = 'active';
		$data['a2']  = 'act1';
		$consultant_id  = $this->session->userdata('consultant_id');
		if ($consultant_id) {
			$data['title'] = "CheckList";

			$this->db->select("*,checklist.id as checklist_id,corrective_action_data.id as corrective_id");
			$this->db->join('audit_criteria', 'checklist.criteria_id = audit_criteria.id', 'left');
			$this->db->join('corrective_action_data', 'checklist.id = corrective_action_data.checklist_id', 'left');
			$this->db->where('clause_id > 0 ');
			$this->db->where('process_id',$id);
			$data['checklist'] = $this->db->get('checklist')->result();
			$this->db->where('company_id',$consultant_id);
			$data['clause_list'] = json_encode($this->db->get('clause')->result());
			$data['process_id'] = $id;
			$data['clause_id'] = '0';
			$this->load->view('employee/view_checklist_mind', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function add_mashine()
	{
		$company_id = $this->session->userdata('consultant_id');
		$name       = $this->input->post('name');
		$data       = array(
				'name' => $name,
				'company_id' => $company_id
		);
		$done       = $this->db->insert('mashine', $data);
		if ($done) {
			$this->db->where('company_id', $company_id);
			$mashine = $this->db->get('mashine')->result();
			echo '<option value="Not Applicable">Not Applicable</option>';
			foreach ($mashine as $mashines) {
				echo "<option value='" . $mashines->name . "'>" . $mashines->name . "</option>";
			}
		} else {
		}
	}
	public function all_mashine()
	{
		$company_id = $this->session->userdata('consultant_id');
		$this->db->where('company_id', $company_id);
		$mashine = $this->db->get('mashine')->result();
		echo '<option value="Not Applicable">Not Applicable</option>';
		foreach ($mashine as $mashines) {
			echo "<option value='" . $mashines->name . "'>" . $mashines->name . "</option>";
		}
	}
	public function all_mashine_table()
	{
		$company_id = $this->session->userdata('consultant_id');
		$this->db->where('company_id', $company_id);
		$mashine = $this->db->get('mashine')->result();
		foreach ($mashine as $mashines) {
			echo "<tr><td>" . $mashines->name . "</td><td><a onclick='deletemashine(" . $mashines->id . ");';><i class='icon-trash'></i></a></td><tr>";
		}
	}
	public function delete_mashine()
	{
		$id         = $this->input->post('id');
		$this->db->where('id', $id);
		$this->db->delete('mashine');
	}
	public function findcomp()
	{
		$company_id  = $this->session->userdata('consultant_id');
		$done        = $this->db->query("SELECT * FROM consultant WHERE consultant_id='$company_id'")->row();
		echo json_encode($done);
	}
	public function findcust()
	{
		$company = $this->input->post('company');
		$done    = $this->db->query("SELECT * FROM consultant WHERE consultant_name LIKE '%$company%'")->result();
		foreach ($done as $dones) {
			echo '<option value="' . $dones->consultant_name . '">' . $dones->consultant_name . '</option>';
		}
	}
	public function findresponsible()
	{
		$company_id  = $this->session->userdata('consultant_id');
		if ($company_id) {
			$id = $this->input->post('id');
			$this->db->where('employee_id', $id);
			$role_id = $this->db->get('employees')->row()->role;
			echo $role_id;
		} else {
			redirect('Welcome');
		}
	}
	public function add_corrective_action_data()
	{
		$company_id                 = $this->session->userdata('consultant_id');
		$audit_type                       = $this->input->post('audit_type');
		$process                    = $this->input->post('process');
		$auditor_id                 = $this->input->post('auditor_id');
		$auditor_real_id			= $this->session->userdata('employee_id');
		$trigger_id                 = $this->input->post('trigger_id');
		$grade_nonconform               = $this->input->post('grade_nonconform');
		$occur_date                 = $this->input->post('occur_date');
		$audit_criteria             = $this->input->post('audit_criteria');
		$audit_criteria2             = $this->input->post('audit_criteria2');
		$audit_criteria3             = $this->input->post('audit_criteria3');
		$audit_criteria4             = $this->input->post('audit_criteria4');
		$audit_criteria4             = $this->input->post('audit_criteria4');
		$customer_requirment        = $this->input->post('customer_requirment');
		$product                    = $this->input->post('product');
		$process_step                   = $this->input->post('process_step');
		$standard                   = $this->input->post('standard');
		$standard1                   = $this->input->post('standard1');
		$standard2                   = $this->input->post('standard2');
		$clause               = $this->input->post('clause');
		$clause1               = $this->input->post('clause1');
		$clause2               = $this->input->post('clause2');
		$regulatory_requirement     = $this->input->post('regulatory_requirement');
		$shift                      = $this->input->post('shift');
		$policy                     = $this->input->post('policy');
		$mashine_clause             = $this->input->post('mashine_clause');
		$company_name               = $this->input->post('company_name');
		$company_address            = $this->input->post('company_address');
		$city                       = $this->input->post('city');
		$state                      = $this->input->post('state');
		$ofi_desc                  = $this->input->post('ofi_desc');
		$ofi 						= $this->input->post('ofi');
		$prob_desc                  = $this->input->post('prob_desc');
		$correction                 = $this->input->post('correction');
		$business_impact            = $this->input->post('business_impact');
		$root_cause                 = $this->input->post('root_cause');
		$action_plan                = $this->input->post('action_plan');
		$corrective_action          = $this->input->post('corrective_action');
		$opp_action			          = $this->input->post('opp_action');
		$verification_effectiveness = $this->input->post('verification_effectiveness');
		$type                       = $this->input->post('type');
		$by_when_date               = $this->input->post('by_when_date');
		$responsible_party          = $this->input->post('responsible_party');
		$role                       = $this->input->post('role');
		$checklist_id               = $this->input->post('checklist_id');
		$unique_id                  = time();
		$create_at                  = date('Y-m-d');
		if (!empty($_FILES['root_doc']['name'])) {
			$config['upload_path']   = 'uploads/Doc/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
			$config['file_name']     = time() . $_FILES['root_doc']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('root_doc')) {

				$uploadData = $this->upload->data();
				$root_doc   = $uploadData['file_name'];
			} else {
				$root_doc = '';
			}
		} else {
			$root_doc = '';
		}
		if (!empty($_FILES['corrective_plan_doc']['name'])) {
			$config['upload_path']   = 'uploads/Doc/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
			$config['file_name']     = time() . $_FILES['corrective_plan_doc']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('corrective_plan_doc')) {
				$uploadData          = $this->upload->data();
				$corrective_plan_doc = $uploadData['file_name'];
			} else {
				$corrective_plan_doc = '';
			}
		} else {
			$corrective_plan_doc = '';
		}
		if (!empty($_FILES['corrective_doc']['name'])) {
			$config['upload_path']   = 'uploads/Doc/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
			$config['file_name']     = time() . $_FILES['corrective_doc']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('corrective_doc')) {
				$uploadData = $this->upload->data();
				$corrective_doc = $uploadData['file_name'];
			} else {
				$corrective_doc = '';
			}
		} else {
			$corrective_doc = '';
		}
		if (!empty($_FILES['verification_doc']['name'])) {
			$config['upload_path']   = 'uploads/Doc/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
			$config['file_name']     = time() . $_FILES['verification_doc']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('verification_doc')) {
				$uploadData       = $this->upload->data();
				$verification_doc = $uploadData['file_name'];
			} else {
				$verification_doc = '';
			}
		} else {
			$verification_doc = '';
		}
		$data12 = array(
				'company_id' => $company_id,
				'auditor_real_id' => $auditor_real_id,
				'audit_type' => $audit_type,
				'create_at' => $create_at,
				'by_when_date' => $by_when_date,
				'policy' => $policy,
				'role' => $role,
				'process_owner' => $responsible_party,
				'type' => $type,
				'action_plan' => $action_plan,
				'business_impact' => $business_impact,
				'root_cause' => $root_cause,
				'correction' => $correction,
				'prob_desc' => $prob_desc,
				'state' => $state,
				'city' => $city,
				'company_address' => $company_address,
				'company_name' => $company_name,
				'mashine_clause' => $mashine_clause,
				'shift' => $shift,
				'regulatory_requirement' => $regulatory_requirement,
				'process_step' => $process_step,
				'standard' => $standard,
				'standard1' => $standard1,
				'standard2' => $standard2,
				'product' => $product,
				'customer_requirment' => $customer_requirment,
				'audit_criteria' => $audit_criteria,
				'audit_criteria2' => $audit_criteria2,
				'audit_criteria3' => $audit_criteria3,
				'audit_criteria4' => $audit_criteria4,
				'occur_date' => $occur_date,
				'trigger_id' => $trigger_id,
				'auditor_id' => $auditor_id,
				'unique_id' => $unique_id,
				'process' => $process,
				'checklist_id' => $checklist_id,
				'opp_action' => $opp_action,
				'corrective_action' => $corrective_action,
				'verification_effectiveness' => $verification_effectiveness,
				'root_doc' => $root_doc,
				'corrective_plan_doc' => $corrective_plan_doc,
				'corrective_doc' => $corrective_doc,
				'verification_doc' => $verification_doc,
				'clause' => $clause,
				'clause1' => $clause1,
				'clause2' => $clause2,
				'grade_nonconform' => $grade_nonconform,
				'ofi_desc' => $ofi_desc,
				'ofi' => $ofi
		);
		if ($company_id) {
			$data = array(
					'company_id' => $company_id,
					'auditor_real_id' => $auditor_real_id,
					'audit_type' => $audit_type,
					'process' => $process,
					'create_at' => $create_at,
					'by_when_date' => $by_when_date,
					'role' => $role,
					'process_owner' => $responsible_party,
					'type' => $type,
					'action_plan' => $action_plan,
					'business_impact' => $business_impact,
					'root_cause' => $root_cause,
					'correction' => $correction,
					'prob_desc' => $prob_desc,
					'state' => $state,
					'city' => $city,
					'company_address' => $company_address,
					'company_name' => $company_name,
					'mashine_clause' => $mashine_clause,
					'policy' => $policy,
					'shift' => $shift,
					'regulatory_requirement' => $regulatory_requirement,
					'process_step' => $process_step,
					'standard' => $standard,
					'standard1' => $standard1,
					'standard2' => $standard2,
					'product' => $product,
					'customer_requirment' => $customer_requirment,
					'audit_criteria' => $audit_criteria,
					'audit_criteria2' => $audit_criteria2,
					'audit_criteria3' => $audit_criteria3,
					'audit_criteria4' => $audit_criteria4,
					'occur_date' => $occur_date,
					'trigger_id' => $trigger_id,
					'auditor_id' => $auditor_id,
					'unique_id' => $unique_id,
					'corrective_action' => $corrective_action,
					'verification_effectiveness' => $verification_effectiveness,
					'root_doc' => $root_doc,
					'corrective_plan_doc' => $corrective_plan_doc,
					'corrective_doc' => $corrective_doc,
					'verification_doc' => $verification_doc,
					'clause' => $clause,
					'clause1' => $clause1,
					'clause2' => $clause2,
					'ofi_desc' => $ofi_desc,
					'opp_action' => $opp_action,
					'grade_nonconform' => $grade_nonconform,
					'ofi' => $ofi
			);
			if ($checklist_id != '') {
				$this->db->where('checklist_id', $checklist_id);
				$corrective_temp = $this->db->get('corrective_action_data')->result();
				if (count($corrective_temp) > 0){
					$this->db->where('id', $corrective_temp[0]->id);
					$done = $this->db->update('corrective_action_data', $data12);
					$last_id = $corrective_temp[0]->id;
				}else{
					$done    = $this->db->insert('corrective_action_data', $data12);
					$last_id = $this->db->insert_id();
				}
			} else {
				$done    = $this->db->insert('corrective_action_data', $data);
				$last_id = $this->db->insert_id();
			}
			if ($done) {

				//---------------------------get info---------------------------------------
				$checklist_id = $this->db->where('id', $last_id)->get('corrective_action_data')->row()->checklist_id;
				if($checklist_id != null && $checklist_id != 0){
					$process_id = $this->db->where('id', $checklist_id)->get('checklist')->row()->process_id;
					$status = $this->db->where('id', $checklist_id)->get('checklist')->row()->status;
					$process_info = $this->db->where('id', $process_id)->get('select_process')->row();
					$auditor_info = $this->db->where('employee_id', $process_info->auditor)->get('employees')->row();
					$process_owner_info = $this->db->where('employee_id', $process_info->process_owner)->get('employees')->row();
					$process_name = $this->db->where('process_id', $process_info->process_id)->get('process_list')->row()->process_name;
					//--------------------------------------------------------------------------

					//-----------------------------send email------------------------------------
					$tit = 'Completion sent to Process Owner with Nonconformity';
					// if($status == "Comformity Table")
					// 	$email_temp = $this->getEmailTemp('Completion sent to Process Owner with Nonconformity');
					// if($status == "Opportunity for Improvement"){
					// 	$email_temp = $this->getEmailTemp('Completion sent to Process Owner with Opportunities');
					// 	$tit = 'Completion sent to Process Owner with Opportunities';
					// }
					// $email_temp['message'] = str_replace("{Process Name}", $process_name, $email_temp['message']);
					// $email_temp['message'] = str_replace("{Process Owner Name}", $process_owner_info->employee_name, $email_temp['message']);
					// $email_temp['message'] = str_replace("{Auditor Name}", $auditor_info->employee_name, $email_temp['message']);
					// $email_temp['message'] = str_replace("{the number for the nonconformities}", $unique_id, $email_temp['message']);
					// $email_temp['message'] = str_replace("{Opportunities For Improvement}", $unique_id, $email_temp['message']);
					// $email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
					// $this->sendemail($process_owner_info->employee_email, $tit, $email_temp['message'], $email_temp['subject'], 2);
					//---------------------------------------------------------------------------
				}

				$this->session->set_flashdata('message', 'submit');
				redirect('employee/car_action_notification/' . $last_id);
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('employee/car_action_notification/' . $last_id);
			}
		} else {
			redirect('Welcome');
		}
	}
	public function car_action_notification($id = Null)
	{
		$company_id1             = $this->session->userdata('consultant_id');
		$data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
		$data['comp_email']      = $this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$company_id1'")->row()->email;
		$data['employees_email'] = $this->db->query("SELECT * FROM `employees` WHERE `consultant_id`='$company_id1' &&  `employee_email`!=''")->result();


		$data['bb1'] = 'active';
		$company_id  = $this->session->userdata('consultant_id');
		if ($company_id) {
			$data['title'] = "CAR ACTION NOTIFICATION";
			$this->db->where('id', $id);
			$data['standalone'] = $this->db->get('corrective_action_data')->row();
			$this->load->view('employee/car_action_notification', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function resolution_list()
	{
		$data['bb1']   = 'active';
		$data['b3']    = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$user_type = $this->session->userdata('user_type');
		$company_id    = $this->session->userdata('consultant_id');
		$data['title'] = "Corrective Action Resolution Log";
		if ($company_id) {
			$this->db->order_by('by_when_date', 'DESC');
			$this->db->where('company_id', $company_id);
			$this->db->where('process_status!=', 'Close');
			$this->db->where('type != ', 'OFI');
			if ($user_type == "Process Owner"){
				$this->db->where('process_owner', $employee_id);
			}
			else if ($user_type == "Auditee"){
				$this->db->where('auditor_id', $employee_id);
			}
			$data['standalone_data'] = $this->db->get('corrective_action_data')->result();
			if ($user_type == "Auditor"){
				$sql = "	Select a.*
						FROM
							corrective_action_data AS a
						LEFT JOIN checklist AS b ON a.checklist_id = b.id
						LEFT JOIN select_process as c on b.process_id = c.id where a.company_id = ".$company_id."
						and process_status != 'Close' and type != 'OFI' and c.auditor = ".$employee_id;
				$data['standalone_data'] = $this->db->query($sql)->result();
			}
			$this->load->view('employee/resolution_list', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function resolution_list_ofi()
	{
		$data['bb1']   = 'active';
		$data['b6']    = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$user_type = $this->session->userdata('user_type');
		$company_id    = $this->session->userdata('consultant_id');
		$data['title'] = "Corrective Action Resolution Log";
		if ($company_id) {
			$this->db->order_by('by_when_date', 'DESC');
			$this->db->where('company_id', $company_id);
			$this->db->where('process_status!=', 'Close');
			$this->db->where('type', 'OFI');
			if ($user_type == "Process Owner"){
				$this->db->where('process_owner', $employee_id);
			}
			else if ($user_type == "Auditee"){
				$this->db->where('auditor_id', $employee_id);
			}
			$data['standalone_data'] = $this->db->get('corrective_action_data')->result();
			if ($user_type == "Auditor"){
				$sql = "	Select a.*
					FROM
						corrective_action_data AS a
					LEFT JOIN checklist AS b ON a.checklist_id = b.id
					LEFT JOIN select_process as c on b.process_id = c.id where a.company_id = ".$company_id."
					and process_status != 'Close' and type = 'OFI' and c.auditor = ".$employee_id;
				$data['standalone_data'] = $this->db->query($sql)->result();
			}
			$this->load->view('employee/resolution_list_ofi', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function resolution($id = '')
	{
		$data['bb1'] = 'active';
		$data['b3']  = 'act1';
		$company_id = $this->session->userdata('consultant_id');
		if ($company_id) {
			$this->db->where('consultant_id', $company_id);
			$data['employees'] = $this->db->get('employees')->result();
			$data['title']     = "Resolution";
			$this->db->where('id', $id);
			$data['standalone_data'] = $this->db->get('corrective_action_data')->row();
			$this->db->where('company_id', $company_id);
			$data['trigger_list'] = $this->db->get('trigger')->result();
			$this->db->where('company_id', $company_id);
			$data['customer_requirment_list'] = $this->db->get('customer_requirment')->result();
			$this->db->where('company_id', $company_id);
			$data['product_list'] = $this->db->get('product')->result();
			$this->db->where('company_id', $company_id);
			$data['standard_list'] = $this->db->get('standard')->result();
			$this->db->where('company_id', $company_id);
			$data['process_step_list'] = $this->db->get('process_step')->result();

			$this->db->where('company_id', $company_id);
			$data['clause_list'] = $this->db->get('clause')->result();

			$this->db->where('company_id', $company_id);
			$data['regulatory_requirement_list'] = $this->db->get('regulatory_requirement')->result();
			$this->db->where('company_id', $company_id);
			$data['shift_list'] = $this->db->get('shift')->result();
			$this->db->where('company_id', $company_id);
			$data['policy_list'] = $this->db->get('policy')->result();
			$this->db->where('company_id', $company_id);
			$data['mashine_list'] = $this->db->get('mashine')->result();
			$this->db->where('company_id', $company_id);
			$data['mashine_list'] = $this->db->get('mashine')->result();
			$this->db->where('consultant_id', $company_id);
			$data['criteria_list'] = $this->db->get('audit_criteria')->result();
			$this->db->where('company_id', $company_id);
			$data['grade_nonconform'] = $this->db->get('grade_nonconform')->result();

			$data['user_type']  = $this->session->userdata('user_type');

			$data['corrective_id'] = $id;

			// echo "<pre>";
			// 	print_r($data);
			// exit;
			$this->load->view('employee/resolution', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function resolution_ofi($id = '')
	{
		$data['bb1'] = 'active';
		$data['b6']  = 'act1';
		$company_id = $this->session->userdata('consultant_id');
		if ($company_id) {
			$this->db->where('consultant_id', $company_id);
			$data['employees'] = $this->db->get('employees')->result();
			$data['title']     = "Resolution";
			$this->db->where('id', $id);
			$data['standalone_data'] = $this->db->get('corrective_action_data')->row();
			$this->db->where('company_id', $company_id);
			$data['trigger_list'] = $this->db->get('trigger')->result();
			$this->db->where('company_id', $company_id);
			$data['customer_requirment_list'] = $this->db->get('customer_requirment')->result();
			$this->db->where('company_id', $company_id);
			$data['product_list'] = $this->db->get('product')->result();
			$this->db->where('company_id', $company_id);
			$data['standard_list'] = $this->db->get('standard')->result();
			$this->db->where('company_id', $company_id);
			$data['process_step_list'] = $this->db->get('process_step')->result();

			$this->db->where('company_id', $company_id);
			$data['clause_list'] = $this->db->get('clause')->result();

			$this->db->where('company_id', $company_id);
			$data['regulatory_requirement_list'] = $this->db->get('regulatory_requirement')->result();
			$this->db->where('company_id', $company_id);
			$data['shift_list'] = $this->db->get('shift')->result();
			$this->db->where('company_id', $company_id);
			$data['policy_list'] = $this->db->get('policy')->result();
			$this->db->where('company_id', $company_id);
			$data['mashine_list'] = $this->db->get('mashine')->result();
			$this->db->where('company_id', $company_id);
			$data['mashine_list'] = $this->db->get('mashine')->result();
			$this->db->where('consultant_id', $company_id);
			$data['criteria_list'] = $this->db->get('audit_criteria')->result();
			$this->db->where('company_id', $company_id);
			$data['grade_nonconform'] = $this->db->get('grade_nonconform')->result();

			$data['user_type']  = $this->session->userdata('user_type');

			$data['corrective_id'] = $id;
			$this->load->view('employee/resolution_ofi', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function update_resolution()
	{
		$company_id                 = $this->session->userdata('consultant_id');
		$user_id 					= $this->session->userdata('employee_id');
		$auditor_id 				= $this->input->post('auditor_id');
		$process_owner_id			= $this->input->post('process_owner_id');
		$nonConformity_id			= $this->input->post('nonConformity_id');
		$process_id					= $this->input->post('process_id');

		$trigger_id                 = $this->input->post('trigger_id');
		if($trigger_id == null)
			$trigger_id = $this->input->post('trigger_id_value');
		$ofi_dsc                 = $this->input->post('ofi_desc');
		$customer_requirment        = $this->input->post('customer_requirment');
		if($customer_requirment == null)
			$customer_requirment = $this->input->post('customer_requirement_value');
		$product                    = $this->input->post('product');
		if($product == null)
			$product = $this->input->post('product_value');
		$regulatory_requirement     = $this->input->post('regulatory_requirement');
		if($regulatory_requirement == null)
			$regulatory_requirement = $this->input->post('regulatory_requirement_value');
		$policy                     = $this->input->post('policy');
		if($policy == null)
			$policy = $this->input->post('policy_value');
		$shift                      = $this->input->post('shift');
		if($shift == null)
			$shift = $this->input->post('shift_value');
		$process_step                   = $this->input->post('process_step');
		if($process_step == null)
			$process_step = $this->input->post('process_step_value');
		$standard                   = $this->input->post('standard');
		if($standard == null)
			$standard = $this->input->post('standard_value');
		$standard1                   = $this->input->post('standard1');
		if($standard1 == null)
			$standard1 = $this->input->post('standard1_value');
		$standard2                   = $this->input->post('standard2');
		if($standard2 == null)
			$standard2 = $this->input->post('standard2_value');
		$mashine_clause             = $this->input->post('mashine_clause');
		if($mashine_clause == null)
			$mashine_clause = $this->input->post('mashine_clause_value');
		$occur_date                 = $this->input->post('occur_date');
		$company_name               = $this->input->post('company_name');
		$company_address            = $this->input->post('company_address');
		$city                       = $this->input->post('city');
		$state                      = $this->input->post('state');
		$prob_desc                  = $this->input->post('prob_desc');
		$correction                 = $this->input->post('correction');
		$root_cause                 = $this->input->post('root_cause');
		$business_impact            = $this->input->post('business_impact');
		$action_plan                = $this->input->post('action_plan');
		$by_when_date               = $this->input->post('by_when_date');
		$process_status             = $this->input->post('process_status_val');
		$type                       = $this->input->post('type');
		$closed_date                = date('Y-m-d');
		$corrective_action          = $this->input->post('corrective_action');
		$verification_effectiveness = $this->input->post('verification_effectiveness');
		$verification_question_flag = $this->input->post('verification_question_flag');
		$audit_criteria             = $this->input->post('audit_criteria');
		if($audit_criteria == null)
			$audit_criteria = $this->input->post('audit_criteria_value');
		$verification_flag          = $this->input->post('verification_flag');
		$action_taken               = $this->input->post('action_taken');
		$action_taken               = intval($action_taken) + 1;
		$form_id                    = $this->input->post('form_id');
		$clause                     = $this->input->post('clause');
		if($clause == null)
			$clause = $this->input->post('clause_value');
		$clause1                     = $this->input->post('clause1');
		if($clause1 == null)
			$clause1 = $this->input->post('clause1_value');
		$clause2                     = $this->input->post('clause2');
		if($clause2 == null)
			$clause2 = $this->input->post('clause2_value');
		$grade_nonconform                     = $this->input->post('grade_nonconform');
		if($grade_nonconform == null)
			$grade_nonconform = $this->input->post('grade_nonconform_value');

		//-----------------------get leader auditor & auditor & process owner info--------------------------------
		$admin_name = $this->db->where('consultant_id', $company_id)->get('consultant')->row()->consultant_name;
		$admin_email = $this->db->where('consultant_id', $company_id)->get('consultant')->row()->email;
		$audit_id = 0;
		$lead_auditor_id = 0;
		if(isset($this->db->where('log_id', $process_id)->get('audit_log_list')->row()->audit_id))
			$audit_id = $this->db->where('log_id', $process_id)->get('audit_log_list')->row()->audit_id;
		if(isset($process_id) && $audit_id != 0){
			if(isset($this->db->where('pa_id', $audit_id)->get('audit_list')->row()->lead_auditor))
				$lead_auditor_id = $this->db->where('pa_id', $audit_id)->get('audit_list')->row()->lead_auditor;
			if($lead_auditor_id != 0){
				$audit_type_id = $this->db->where('pa_id', $audit_id)->get('audit_list')->row()->audit_type;
				$audit_here = $this->db->where('type_id', $audit_type_id)->get('type_of_audit')->row()->type_of_audit;
				$lead_auditor_info = $this->db->where('employee_id', $lead_auditor_id)->get('employees')->row();
				$auditor_info = $this->db->where('employee_id', $auditor_id)->get('employees')->row();
				$process_owner_info = $this->db->where('employee_id', $process_owner_id)->get('employees')->row();
			}
		}
		//--------------------------------------------------------------------------------------------------------

		if (!empty($_FILES['root_doc']['name'])) {
			$config['upload_path'] = 'uploads/Doc/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
			$config['file_name']   = time() . $_FILES['root_doc']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('root_doc')) {
				$uploadData = $this->upload->data();
				$root_doc   = $uploadData['file_name'];
			} else {
				$root_doc = '';
			}
		} else {
			$root_doc = '';
		}
		// echo "<pre>";
		// 	print_r($this->upload->display_errors());
		// exit;
		if (!empty($_FILES['corrective_plan_doc']['name'])) {
			$config['upload_path'] = 'uploads/Doc/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
			$config['file_name']   = time() . $_FILES['corrective_plan_doc']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('corrective_plan_doc')) {
				$uploadData          = $this->upload->data();
				$corrective_plan_doc = $uploadData['file_name'];
			} else {
				$corrective_plan_doc = '';
			}
		} else {
			$corrective_plan_doc = '';
		}
		if (!empty($_FILES['corrective_doc']['name'])) {
			$config['upload_path'] = 'uploads/Doc/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
			$config['file_name']   = time() . $_FILES['corrective_doc']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('corrective_doc')) {
				$corrective_doc = $this->upload->data();
				$corrective_doc = $uploadData['file_name'];
			} else {
				$corrective_doc = '';
			}
		} else {
			$corrective_doc = '';
		}
		if (!empty($_FILES['verification_doc']['name'])) {
			$config['upload_path'] = 'uploads/Doc/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
			$config['file_name']   = time() . $_FILES['verification_doc']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('verification_doc')) {
				$uploadData       = $this->upload->data();
				$verification_doc = $uploadData['file_name'];
			} else {
				$verification_doc = '';
			}
		} else {
			$verification_doc = '';
		}


		if ($company_id) {
			$data = array(
					'by_when_date' => $by_when_date,
					'type' => $type,
					'action_plan' => $action_plan,
					'business_impact' => $business_impact,
					'root_cause' => $root_cause,
					'correction' => $correction,
					'prob_desc' => $prob_desc,
					'ofi_desc' => $ofi_dsc,
					'state' => $state,
					'city' => $city,
					'company_name' => $company_name,
					'company_address' => $company_address,
					'mashine_clause' => $mashine_clause,
					'process_step' => $process_step,
					'standard' => $standard,
					'standard1' => $standard1,
					'standard2' => $standard2,
					'shift' => $shift,
					'regulatory_requirement' => $regulatory_requirement,
					'policy' => $policy,
					'product' => $product,
					'customer_requirment' => $customer_requirment,
					'audit_criteria' => $audit_criteria,
					'occur_date' => $occur_date,
					'trigger_id' => $trigger_id,
					'closed_date' => $closed_date,
					'process_status' => $process_status,
					'corrective_action' => $corrective_action,
					'verification_effectiveness' => $verification_effectiveness,
					'verification_flag' => $verification_flag,
					'action_taken' => $action_taken,
					'clause' => $clause,
					'clause1' => $clause1,
					'clause2' => $clause2,
					'grade_nonconform' => $grade_nonconform
			);

			if($root_doc != ''){
				$data['root_doc'] = $root_doc;
			}
			if($corrective_plan_doc != ''){
				$data['corrective_plan_doc'] = $corrective_plan_doc;
			}
			if($corrective_doc != ''){
				$data['corrective_doc'] = $corrective_doc;
			}
			if($verification_doc != ''){
				$data['verification_doc'] = $verification_doc;
			}


			$this->db->where('id', $form_id);
			$done = $this->db->update('corrective_action_data', $data);
			if ($done) {

				//-------------------------------send email-----------------------------------------
				if(isset($process_id) && $auditor_id != 0 && $lead_auditor_id != 0){
					if($user_id == $process_owner_id){
						$email_temp = $this->getEmailTemp('Process Owner Takes Correction and Corrective Action(to Process Owner)');
						$email_temp['message'] = str_replace("{Process Owner NAME}", $process_owner_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Admin NAME}", $admin_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Nonconformity #}", $nonConformity_id, $email_temp['message']);
						$email_temp['message'] = str_replace("{Nonconformity Name}", $prob_desc, $email_temp['message']);
						$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
						$this->sendemail($process_owner_info->employee_email, 'Process Owner Takes Correction and Corrective Action', $email_temp['message'], $email_temp['subject'], 3);

						$email_temp = $this->getEmailTemp('Process Owner Takes Correction and Corrective Action(to Auditor)');
						$email_temp['message'] = str_replace("{Auditor NAME}", $auditor_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Process Owner NAME} ", $process_owner_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Lead Auditor NAME}", $lead_auditor_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Nonconformity #}", $nonConformity_id, $email_temp['message']);
						$email_temp['message'] = str_replace("{Nonconformity Name}", $prob_desc, $email_temp['message']);
						$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
						$this->sendemail($auditor_info->employee_email, 'Process Owner Takes Correction and Corrective Action', $email_temp['message'], $email_temp['subject'], 3);

						$email_temp = $this->getEmailTemp('Process Owner Takes Correction and Corrective Action( to Lead Auditor)');
						$email_temp['message'] = str_replace("{Lead Auditor NAME}", $lead_auditor_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Process Owner NAME}", $process_owner_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Nonconformity #}", $nonConformity_id, $email_temp['message']);
						$email_temp['message'] = str_replace("{Nonconformity Name}", $prob_desc, $email_temp['message']);
						$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
						$this->sendemail($lead_auditor_info->employee_email, 'Process Owner Takes Correction and Corrective Action', $email_temp['message'], $email_temp['subject'], 3);
					}
					else if($user_id == $company_id){
						$email_temp = $this->getEmailTemp('When Lead Auditor Finishes the Audit');
						$email_temp['message'] = str_replace("{Lead Auditor NAME}", $lead_auditor_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Admin NAME}", $admin_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Audit Here}", $audit_here, $email_temp['message']);
						$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
						$this->sendemail($admin_email, 'Lead Auditor Finishes the Audit', $email_temp['message'], $email_temp['subject'], 2);

					}
					else{
						$tit = 'Verifier Verify a Nonconformity as effectively closed';
						if($verification_flag == 'Yes')
							$email_temp = $this->getEmailTemp('Verifier Verify a Nonconformity as effectively closed');
						else{
							$email_temp = $this->getEmailTemp('Verifier Verify a Nonconformity as not effectively closed');
							$tit = 'Verifier Verify a Nonconformity as not effectively closed';
						}
						$email_temp['message'] = str_replace("{Lead Auditor NAME}", $auditor_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{Process Owner NAME}", $process_owner_info->employee_name, $email_temp['message']);
						$email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
						$this->sendemail($process_owner_info->employee_email, $tit, $email_temp['message'], $email_temp['subject'], 2);

					}
				}

				//----------------------------------------------------------------------------------

				if ($verification_question_flag == '2') {
					$this->session->set_flashdata('message', 'submit');
					redirect('employee/car_verification_form/' . $form_id);
				} else {
					redirect('employee/resolution_list');
				}
			} else {
				$this->session->set_flashdata('message', 'failed');
				redirect('employee/car_verification_form/' . $form_id);
			}
		} else {
			redirect('Welcome');
		}
	}
	public function car_verification_form($id)
	{
		$company_id = $this->session->userdata('consultant_id');
		if ($company_id) {
			$data['title'] = "Verification Form";
			$this->db->where('id', $id);
			$data['standalone_data'] = $this->db->get('corrective_action_data')->row();
			$this->load->view('employee/car_verification_form', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function add_root_cause()
	{
		$company_id = $this->session->userdata('consultant_id');
		$id = $this->input->post('data_id');
		$why1 = $this->input->post('why1');
		$why2 = $this->input->post('why2');
		$why3 = $this->input->post('why3');
		$why4 = $this->input->post('why4');
		$why5 = $this->input->post('why5');
		$conclusion = $this->input->post('conclusion');
		if ($company_id) {
			$data = array(
					'why1' => $why1,
					'why2' => $why2,
					'why3' => $why3,
					'why4' => $why4,
					'why5' => $why5,
					'conclusion' => $conclusion,
					'corrective_action_data_id' => $id
			);
			$this->db->where('corrective_action_data_id', $id);
			$root_cause_data = $this->db->get('root_cause')->row();
			if (count($root_cause_data) > 0){
				$this->db->where('corrective_action_data_id', $id);
				$this->db->update('root_cause', $data);
			}else{
				$this->db->insert('root_cause', $data);
			}
			redirect('employee/resolution/' . $id);
		} else {
			redirect('Welcome');
		}
	}
	public function corrective_action_report()
	{
		$data['bb1']             = 'active';
		$data['b2']              = 'act1';
		$company_id              = $this->session->userdata('consultant_id');
		$data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
		$data['comp_email']      = $this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$company_id'")->row()->email;
		$data['employees_email'] = $this->db->query("SELECT * FROM `employees` WHERE `consultant_id`='$company_id' &&  `employee_email`!=''")->result();
		$data['title']           = "CORRECTIVE ACTIONS Report";
		if ($company_id) {
			$data['no'] = "owner";
			$user_type  = $this->session->userdata('user_type');
			$this->db->where('employees.consultant_id', $company_id);
			if($user_type == "Auditor") {
				$this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
				$this->db->where('permision.type_id', 2);
			} else if($user_type == "Process Owner") {
				$this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
				$this->db->where('permision.type_id', 3);
			} else if($user_type == "Auditee") {
				$this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
				$this->db->where('permision.type_id', 4);
			}
			$emp_list = $this->db->get('employees')->result();

//			$emp_list   = $this->db->query("SELECT * FROM `employees` WHERE `consultant_id`='$company_id'")->result();
			for ($i = 0; $i < sizeof($emp_list); $i++) {
				$item            = $emp_list[$i];
				$cnt             = $this->db->query("SELECT COUNT(id) as count FROM `corrective_action_data` WHERE del_flag=0 and `process_status`!='Close'  AND `process_owner`='$item->employee_id'")->row()->count;
				$item->open_cnt  = $cnt;
				$cnt             = $this->db->query("SELECT COUNT(id) as count FROM `corrective_action_data` WHERE del_flag=0 and `process_status`='Close'  AND `process_owner`='$item->employee_id'")->row()->count;
				$item->close_cnt = $cnt;
				$emp_list[$i]    = $item;
			}
			$data['standalone_data'] = $emp_list;
			$this->load->view('employee/corrective_action_report', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function resolved_list($type = '')
	{
		$data['bb1'] = 'active';
		if ($type == 'OFI') {
			$data['b4']   = 'act1';
			  $data['title'] = "Opportunity of Improvement Resolution History";
		} else {
			$data['b5']    = 'act1';
			$data['title'] = "Corrective Action Resolution History";
		}
		$data['type'] = $type;
		$employee_id = $this->session->userdata('employee_id');
		$user_type = $this->session->userdata('user_type');
		$company_id   = $this->session->userdata('consultant_id');
		if ($company_id) {
			$trigger_id = $this->db->query("SELECT * FROM `trigger` WHERE `company_id`='$company_id' ")->row()->trigger_id;
			$data['no'] = "auditor";
			$this->db->where('type', $type);
			$this->db->where('company_id', $company_id);
			$this->db->where('process_status', 'Close');
			$query = "select * from corrective_action_data  where del_flag=0 and company_id=" . $company_id;
			$query .= " and process_status='Close'";
			if ($user_type == "Process Owner"){
				$this->db->where('process_owner', $employee_id);
			}
			else if ($user_type == "Auditee"){
				$this->db->where('auditor_id', $employee_id);
			}
//			if ($type == 'CORRECTION') {
//				$query .= " and (type = 'CORRECTION' or trigger_id=" . $trigger_id . ")";
//			} else {
//				$query .= " and (type = 'CORRECTIVE' and trigger_id !=" . $trigger_id . ")";
//			}
			if($type != "OFI"){
				$query .= " and ((type = 'CORRECTION' or trigger_id=" . $trigger_id . ") or (type = 'CORRECTIVE' and trigger_id !=" . $trigger_id . "))";
			}
			else
				$query .= " and type = 'OFI' ";
			$data['standalone_data'] = $this->db->query($query)->result();
			if ($user_type == "Auditor"){
				$sql = "	Select a.*
					FROM
						corrective_action_data AS a
					LEFT JOIN checklist AS b ON a.checklist_id = b.id
					LEFT JOIN select_process as c on b.process_id = c.id where a.company_id = ".$company_id." and process_status='Close' and c.auditor = ".$employee_id;
//				if ($type == 'CORRECTION') {
//					$sql .= " and (type = 'CORRECTION' or trigger_id=" . $trigger_id . ")";
//				} else {
//					$sql .= " and (type = 'CORRECTIVE' and trigger_id !=" . $trigger_id . ")";
//				}
				if($type != "OFI"){
					$sql .= " and ((type = 'CORRECTION' or trigger_id=" . $trigger_id . ") or (type = 'CORRECTIVE' and trigger_id !=" . $trigger_id . "))";
				}
				else
					$sql .= " and type = 'OFI' ";
				$data['standalone_data'] = $this->db->query($sql)->result();
			}
			$this->load->view('employee/resolved_list', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function corrective_action_form_detail($id = Null)
	{
		$type = $_GET['type'];
		if (!isset($type)) {
			$type = 'CORRECTION';
		}
		$data['type'] = $type;
		$data['bb1']  = 'active';
		$company_id   = $this->session->userdata('consultant_id');
		if ($company_id) {
			$data['title'] = "Standalone Form Detail";
			$data['corrective_id'] = $id;
			$this->db->where('id', $id);
			$data['standalone'] = $this->db->get('corrective_action_data')->row();
			$this->load->view('employee/corrective_action_form_detail', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function close_audit()
	{
		$data['aa1'] = 'active';
		$data['a3']  = 'act1';
		$employee_id = $this->session->userdata('employee_id');
		$user_type = $this->session->userdata('user_type');
		$consultant_id  = $this->session->userdata('consultant_id');
		if ($consultant_id) {
			$data['title'] = "Close Audit";

			$sql = "SELECT
                            audit.*, type.type_of_audit, e.employee_name, f.*, t.trigger_name,g.*,
                            (DATEDIFF(audit.created_at,DATE(NOW())) + f.days) as status
                        FROM
                            audit_list audit
                        LEFT JOIN type_of_audit type ON audit.audit_type = type.type_id
                        LEFT JOIN employees e ON audit.lead_auditor = e.employee_id
                        LEFT JOIN frequency f ON audit.frequency = f.frequency_id
                        LEFT JOIN audit_log_list g ON audit.pa_id = g.audit_id
                        LEFT JOIN `trigger` t ON audit.`trigger` = t.trigger_id
                        WHERE
                            g.status = 1 and
                            audit.company_id = " . $consultant_id;
			if ($user_type == "Lead Auditor"){
				$sql .= " and audit.lead_auditor = ".$employee_id;
			}else if ($user_type == "Auditor"){
				$sql1 = "select * from select_process as a where auditor = ".$employee_id." group by audit_id";
				$audit_temp = $this->db->query($sql1)->result();
				if (count($audit_temp) > 0){
					$sql .= " and (";
				}else{
					$sql .= " and ( 1= 0";
				}
				$index = 0;
				foreach ($audit_temp as $item) {
					$index++;
					if ($index == count($audit_temp)){
						$sql .= "g.log_id = ".$item->audit_id;
					}else{
						$sql .= "g.log_id = ".$item->audit_id." OR ";
					}
				}
				$sql .= ")";
			}else if ($user_type == "Process Owner"){
				$sql1 = "select * from select_process as a where process_owner = ".$employee_id." group by audit_id";
				$audit_temp = $this->db->query($sql1)->result();
				if (count($audit_temp) > 0){
					$sql .= " and (";
				}else{
					$sql .= " and ( 1= 0";
				}
				$index = 0;
				foreach ($audit_temp as $item) {
					$index++;
					if ($index == count($audit_temp)){
						$sql .= "g.log_id = ".$item->audit_id;
					}else{
						$sql .= "g.log_id = ".$item->audit_id." OR ";
					}
				}
				$sql .= ")";
			}
			else if ($user_type == "Auditee"){
				$sql1 = "select * from select_process as a where sme = ".$employee_id." group by audit_id";
				$audit_temp = $this->db->query($sql1)->result();
				if (count($audit_temp) > 0){
					$sql .= " and (";
				}else{
					$sql .= " and ( 1= 0";
				}
				$index = 0;
				foreach ($audit_temp as $item) {
					$index++;
					if ($index == count($audit_temp)){
						$sql .= "g.log_id = ".$item->audit_id;
					}else{
						$sql .= "g.log_id = ".$item->audit_id." OR ";
					}
				}
				$sql .= ")";
			}
			$sql .= " ORDER BY audit.created_at DESC, pa_id DESC ";
			$data['close_audits'] = $this->db->query($sql)->result();
			$this->load->view('employee/close_audit', $data);
		} else {
			redirect('Welcome');
		}
	}
	public function view_audit_plan($id = null)
	{
		$data['aa1'] = 'active';
		$data['a3']  = 'act1';
		$consultant_id  = $this->session->userdata('consultant_id');
		if ($consultant_id) {
			$data['title'] = "View Audit Plan";

			$sql = "SELECT *,sp.id as sp_id, (
                        SELECT
                            employee_name
                        FROM
                            employees
                        WHERE
                            sp.auditor = employees.employee_id
                    ) AS auditor_name,
                    (
                        SELECT
                            employee_name
                        FROM
                            employees
                        WHERE
                            sp.process_owner = employees.employee_id
                    ) AS process_owner_name,
                    (
                        SELECT
                            employee_name
                        FROM
                            employees
                        WHERE
                            sp.sme = employees.employee_id
                    ) AS sme_name,
                    DATE_FORMAT(sp.start_time,'%M %e, %Y %l:%i %p') as starttime_type,
                    DATE_FORMAT(sp.end_time,'%M %e, %Y %l:%i %p') as endtime_type,
                    (100 * ((DATEDIFF(sp.end_time,sp.start_time) + DATEDIFF(sp.end_time,DATE(NOW())))/DATEDIFF(sp.end_time,sp.start_time))) as efficiency,
                    DATEDIFF(sp.end_time,DATE(NOW())) as past_due
                      FROM
                    process_list AS process
                    left join select_process as sp on process.process_id = sp.process_id
                        WHERE
                            sp.audit_id = " . $id . " AND sp.status != 0
                        ORDER BY process.process_id DESC ";
			$data['process'] = $this->db->query($sql)->result();
			$data['audit_id'] = $id;
			$this->load->view('employee/view_audit_plan', $data);
		} else {
			redirect('Welcome');
		}
	}

	public function add_audit_log($pa_id = null) {
		$audit_list = $this->db->query("SELECT * FROM `audit_list` WHERE `pa_id`='$pa_id'")->row();
		if($pa_id == null || $audit_list == null) {
			redirect('Employee/audits');
		} else {
			$data = array(
				'audit_id' => $pa_id
			);
			$this->db->insert('audit_log_list', $data);
			$log_id = $this->db->insert_id();
			redirect('Employee/audit_brief/'. $log_id);
		}
	}
}
