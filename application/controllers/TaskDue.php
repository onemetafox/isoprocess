<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/BaseController.php';
class TaskDue extends BaseController//CI_Controller
{
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
	}

	public function sendNotification() {

		$sql = "SELECT    audit.pa_id, (DATEDIFF(audit.created_at,DATE(NOW())) + f.days) as status_days,
					DATE_ADD(audit.created_at,INTERVAL f.days DAY) due_date,
					c.consultant_name admin_name
					FROM audit_list audit
					LEFT JOIN type_of_audit type ON audit.audit_type = type.type_id
					LEFT JOIN employees e ON audit.lead_auditor = e.employee_id
					LEFT JOIN frequency f ON audit.frequency = f.frequency_id
					LEFT JOIN consultant c ON audit.company_id = c.consultant_id
					ORDER BY audit.created_at DESC, pa_id DESC limit 4";


		$Tit_due_in = "Task is Due in";
		$email_temp_due_in_audit = $this->getEmailTemp('Task is Due in to Auditor');
		$email_temp_due_in_process_owner = $this->getEmailTemp('Task is Due in to Process Owner');

		$Tit_due_today = "Task is Due today";
		$email_temp_due_today_audit = $this->getEmailTemp('Task is Due today to Auditor');
		$email_temp_due_today_process_owner = $this->getEmailTemp('Task is Due today to Process Owner');

		$Tit_due_on = "Task is Past Due";
		$email_temp_past_due_audit = $this->getEmailTemp('Task is Past Due to Auditor');
		$email_temp_pPast_due_process_owner = $this->getEmailTemp('Task is Past Due to Process Owner');
		$datas = $this->db->query($sql)->result();

		foreach($datas as $data) {
			$pa_id = $data->pa_id;
			$dates = $data->status_days;
			$due_date = $data->due_date;
			$admin_name = $data->admin_name;

			$email_audit = $email_temp_due_in_audit;
			$email_proces_owner = $email_temp_due_in_process_owner;
			$tit = $Tit_due_in;

			if($dates > 7)
				continue;

			if($dates == 0){
				$email_audit = $email_temp_due_today_audit;
				$email_proces_owner = $email_temp_due_today_process_owner;
				$tit = $Tit_due_today;
			}
			if($dates < 0){
				$email_audit = $email_temp_past_due_audit;
				$email_proces_owner = $email_temp_pPast_due_process_owner;
				$tit = $Tit_due_on;
			}

			$dates = abs($dates);
			if($dates == 1)
				$dates = $dates." day";
			else
				$dates = $dates." days";

			$query = "SELECT 	a.log_id , b.auditor, b.process_owner, e1.employee_name auditor_name, e1.employee_email auditor_email,
								e2.employee_name process_owner_name, e2.employee_email process_owner_email, p.process_name
								from audit_log_list a
								LEFT JOIN select_process b ON a.log_id = b.audit_id
								LEFT JOIN employees e1 ON b.auditor = e1.employee_id
								LEFT JOIN employees e2 ON b.process_owner = e2.employee_id
								LEFT JOIN process_list p ON b.process_id = p.process_id
								where a.audit_id = $pa_id and b.auditor is not null GROUP BY b.auditor, b.process_owner";

			$results = $this->db->query($query)->result();
			foreach($results as $res){
				$auditor_name = $res->auditor_name;
				$auditor_email = $res->auditor_email;
				$process_owner_name = $res->process_owner_name;
				$process_owner_email = $res->process_owner_email;
				$process_name = $res->process_name;

				$email_audit['message'] = str_replace("{Auditor NAME}", $auditor_name, $email_audit['message']);
				$email_audit['message'] = str_replace("{Admin NAME}", $admin_name, $email_audit['message']);
				$email_audit['message'] = str_replace("{Process Name}", $process_name, $email_audit['message']);
				$email_audit['message'] = str_replace("{Company Name}", $admin_name, $email_audit['message']);
				$email_audit['message'] = str_replace("{Date}", $due_date, $email_audit['message']);
				$email_audit['message'] = str_replace("{number of Days}", $dates, $email_audit['message']);
				$email_audit['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_audit['message']);
				$this->sendemail($auditor_email, $tit, $email_audit['message'], $email_audit['subject']);

				$email_proces_owner['message'] = str_replace("{Process Owner NAME}", $process_owner_name, $email_proces_owner['message']);
				$email_proces_owner['message'] = str_replace("{Admin NAME}", $admin_name, $email_proces_owner['message']);
				$email_proces_owner['message'] = str_replace("{Process Name}", $process_name, $email_proces_owner['message']);
				$email_proces_owner['message'] = str_replace("{Company Name}", $admin_name, $email_proces_owner['message']);
				$email_proces_owner['message'] = str_replace("{Date}", $due_date, $email_proces_owner['message']);
				$email_proces_owner['message'] = str_replace("{number of Days}", $dates, $email_proces_owner['message']);
				$email_proces_owner['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_proces_owner['message']);
				$this->sendemail($process_owner_email, $tit, $email_proces_owner['message'], $email_proces_owner['subject']);
			}

		}
	}
}
