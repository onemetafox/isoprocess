<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/BaseController.php';
class Consultant extends BaseController //CI_Controller
{
	public function __construct(){
		parent::__construct();

		$this->load->library('session');
	}

    public function corrective_message()
    {
        $data['dd1'] = 'active';
        $data['d1'] = 'act1';
        $consultant_id = $this->session->userdata('consultant_id');
        $com_status = $this->session->userdata('com_status');
        if ($consultant_id && $com_status != '0') {
            $data['title'] = "Corrective Action Inbox";

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
                                )
                        ORDER BY corrective.id DESC";

            $data['corrective_message'] = $this->db->query($sql)->result();
            $this->load->view('consultant/corrective_message', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function send_corrective_message() {
        $consultant_id  = $this->session->userdata('consultant_id');
        $com_status  = $this->session->userdata('com_status');
        $message = $this->input->post('message');
        $corrective_id = $this->input->post('corrective_id');
        if ($consultant_id && $com_status != '0') {
            $created_at = date('Y-m-d h:i:s');

            $data = array(
                'company_id' => $consultant_id,
                'sender_id' => $consultant_id,
                'sender_role' => 'Consultant',
                'message' => $message,
                'date_time' => $created_at,
                'corrective_id' => $corrective_id
            );
            $done = $this->db->insert('corrective_message', $data);
            print_r($this->db->last_query());
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }

    public function show_corrective_message($id = '')
    {
        $data['dd1'] = 'active';
        $data['d1'] = 'act1';
        $consultant_id = $this->session->userdata('consultant_id');
        $com_status = $this->session->userdata('com_status');
        if ($consultant_id && $com_status != '0') {
            $this->db->where('corrective_id', $id);
            $this->db->where('company_id', $consultant_id);
            $this->db->order_by('date_time', 'asc');
            $data['message'] = $this->db->get('corrective_message')->result();
            $data['title']   = "Messages";
            $this->db->where('id', $id);
            $data['standalone_data'] = $this->db->get('corrective_action_data')->row();
            $data['corrective_id'] = $id;
            $this->load->view('consultant/show_corrective_message', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function individual_message()
    {
        $data['dd1'] = 'active';
        $data['d2'] = 'act1';
        $consultant_id = $this->session->userdata('consultant_id');
        $com_status = $this->session->userdata('com_status');
        if ($consultant_id && $com_status != '0') {
            $data['title'] = "Individual Inbox";
            $this->db->order_by('date_time', 'desc');
            $this->db->where('company_id', $consultant_id);
            $data['individual_message'] = $this->db->get('individual_message')->result();
            $this->db->where('consultant_id', $consultant_id);
            $data['employees'] = $this->db->get('employees')->result();
            $this->load->view('consultant/individual_message', $data);
        } else {
            redirect('Welcome');
        }
    }

    function mails_to_indi()
    {
        $consultant_id = $this->session->userdata('consultant_id');
//        $email      = $this->input->post('email');
        $title      = $this->input->post('title');
        $message    = $this->input->post('message');
        $to_user    = $this->input->post('to_user');
//        $from_role  = 'consultant';
        $date_time  = date('Y-m-d');
//        if ($to_user == 'owner') {
//            $ml    = $this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row();
//            $mails = $ml->email;
//        } else {
//            $ml    = $this->db->query("select * from `employees` where `employee_id`='$to_user'")->row();
//            $mails = $ml->employee_email;
//        }
        $mszdata = array(
            'company_id' => $consultant_id,
            'message' => $message,
            'from_user' => $consultant_id,
            'to_user' => $to_user,
            'from_role' => 'consultant',
            'to_role' => 'employee',
            'title' => $title,
            'date_time' => $date_time
        );
        $done    = $this->db->insert('individual_message', $mszdata);
        $data_id = $this->db->insert_id();
        if ($done) {
            $mszdata1 = array(
                'company_id' => $consultant_id,
                'message' => $message,
                'from_user' => $consultant_id,
                'to_user' => $to_user,
                'from_role' => 'consultant',
                'to_role' => 'employee',
                'title' => $title,
                'data_id' => $data_id,
                'date_time' => $date_time
            );
            $this->db->insert('individual_message_data', $mszdata1);
        }
//        $this->load->model('sendmail');
//        $this->sendmail->emails($mails, $message);
        redirect('consultant/individual_message');
    }

    public function show_individual_message($id = '')
    {
        $data['dd1'] = 'active';
        $data['d2'] = 'act1';
        $consultant_id = $this->session->userdata('consultant_id');
        $com_status = $this->session->userdata('com_status');
        if ($consultant_id && $com_status != '0') {
            $this->db->where('data_id', $id);
            $data['message'] = $this->db->get('individual_message_data')->result();
            $data['title']   = "Messages";
            $this->db->where('id', $id);
            $data['title_msz'] = $this->db->get('individual_message')->row();
            $this->load->view('consultant/show_individual_message', $data);
        } else {
            redirect('Welcome');
        }
    }

    function mails_to_indi_data()
    {
        $consultant_id = $this->session->userdata('consultant_id');
//        $email      = $this->input->post('email');
//        $title      = $this->input->post('title');
        $message    = $this->input->post('message');
        $to_user    = $this->input->post('to_user');
        $data_id    = $this->input->post('data_id');
//        $from_role  = 'company';
        $date_time  = date('Y-m-d');
//        if ($to_user == '0') {
//            $ml    = $this->db->query("select * from `consultant` where `consultant_id`='$consultant_id'")->row();
//            $mails = $ml->email;
//        } else {
//            $ml    = $this->db->query("select * from `employees` where `employee_id`='$to_user'")->row();
//            $mails = $ml->employee_email;
//        }
        $mszdata1 = array(
            'company_id' => $consultant_id,
            'message' => $message,
            'from_user' => $consultant_id,
            'to_user' => $to_user,
            'from_role' => 'consultant',
            'to_role' => 'employee',
            'data_id' => $data_id,
            'date_time' => $date_time
        );
        $this->db->insert('individual_message_data', $mszdata1);
//        $this->load->model('sendmail');
//        $this->sendmail->emails($mails, $message);
        redirect('Consultant/show_individual_message/' . $data_id);
    }

    public function findcustomer()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $done = $this->db->get('customers')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }

    public function audits_finish($pa_id = Null) {
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $data = array(
                'submited_date' => date('Y-m-d'),
                'status' => 2,
                'submited' => 1
            );
            $this->db->where('log_id', $pa_id);
            $done = $this->db->update('audit_log_list', $data);
            if($done) {
                redirect('Consultant/audits');
            } else {
                redirect('Consultant/audit_schedule/' . $pa_id);
            }
        } else {
            redirect('Welcome');
        }
    }

    function process_message() {
        $data['dd1'] = 'active';
        $data['d3'] = 'act1';
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $data['title'] = "Process Inbox";
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
                            )
                        ORDER BY sp.id DESC";
            $data['process_message'] = $this->db->query($sql)->result();
            $this->load->view('consultant/process_message', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function send_process_message() {
        $consultant_id  = $this->session->userdata('consultant_id');
        $com_status  = $this->session->userdata('com_status');
        $message = $this->input->post('message');
        $process_id = $this->input->post('process_id');
        if ($consultant_id && $com_status != '0') {
            $created_at = date('Y-m-d h:i:s');

            $data = array(
                'company_id' => $consultant_id,
                'sender_id' => $consultant_id,
                'sender_role' => 'Consultant',
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

    public function show_process_message($id = '')
    {
        $data['dd1'] = 'active';
        $data['d3'] = 'act1';
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $this->db->where('process_id', $id);
            $this->db->where('company_id', $consultant_id);
            $this->db->order_by('date_time', 'asc');
            $data['message'] = $this->db->get('process_message')->result();
            $data['title']   = "Messages";

            $this->db->where('select_process.id', $id);
            $this->db->join('process_list', 'process_list.process_id=select_process.process_id', 'left');
            $data['process_data'] = $this->db->get('select_process')->row();
            $this->load->view('consultant/show_process_message', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function invoice_list()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $com_status = $this->session->userdata('com_status');
        if ($consultant_id && $com_status != '0') {
            $data['title'] = "Invoice";
            $this->load->view('consultant/invoice_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function main_info()
    {
        $data['cc1'] = 'active';
        $data['c3']  = 'act1';
//        $data['c41'] = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        $employee_id = $this->session->userdata('employee_id');
        if ($consultant_id) {
            if ($employee_id) {
                $this->db->where('employee_id', $employee_id);
                $data['profile'] = $this->db->get('employees')->row();
            } else {
                $this->db->where('consultant_id', $consultant_id);
                $data['profile'] = $this->db->get('consultant')->row();
            }
            $data['title'] = "Edit Profile";
            $this->load->view('consultant/main_info', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function update_main_info()
    {
        $consultant_id   = $this->session->userdata('consultant_id');
        $employee_id  = $this->session->userdata('employee_id');
        $username     = $this->input->post('username');
        $consultant_name = $this->input->post('consultant_name');
        $address      = $this->input->post('address');
        $city         = $this->input->post('city');
        $state        = $this->input->post('state');
        /*=-=- check user mobile number valid start =-=-*/
        $this->load->library('phone_RK');
        $phone        = $this->input->post('phone');
        $phone_response = $this->phone_rk->checkPhoneNumber($phone);
        if (!$phone_response['success']){
            $this->session->set_flashdata('phone_response', $phone_response);
            redirect('consultant/main_info');
            return;
        }
        /*=-=- check user mobile number valid end =-=-*/
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
            $picture = @$this->db->query("SELECT * FROM consultant WHERE consultant_id='$consultant_id'")->row()->logo;
        }
        $up = array(
            'state'             => $state,
            'consultant_name'   => $consultant_name,
            'address'           => $address,
            'city'              => $city,
            'username'          => $username,
            'phone'             => $phone,
            'logo'              => $picture
        );
        if ($consultant_id) {
            $this->db->where('consultant_id', $consultant_id);
            $done = $this->db->update('consultant', $up);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Consultant/main_info');
            } else {
                redirect('Consultant/main_info');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function update_main_info_password(){
        $param  = $this->input->post();
        $this->load->model('Companymodel');
        $user   = $this->session->userdata('consultant_id');
        $user   = $this->Companymodel->get_company($user);
        if ($user){
            if (!verifyHashedPassword($param['old_password'], $user->password)){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'Old Password did\'nt matched'));
                redirect('Consultant/main_info');
            }
            if (empty(trim($param['password'])) && empty(trim($param['repassword']))){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'New Password Cannot be Empty'));
                redirect('Consultant/main_info');
            }
            if ($param['password'] != $param['repassword']){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'New Password didn\'t matched with confirm password'));
                redirect('Consultant/main_info');
            }

            // Validate password strength
            $uppercase = preg_match('@[A-Z]@', $param['password']);
            $lowercase = preg_match('@[a-z]@', $param['password']);
            $number    = preg_match('@[0-9]@', $param['password']);
            $specialChars = preg_match('@[^\w]@', $param['password']);
            if(strlen($param['password']) < 8){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'Password should be at least 8 characters in length'));
                redirect('Consultant/main_info');
            }else if(!$uppercase ){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'Password should include at least one upper case letter'));
                redirect('Consultant/main_info');
            }else if(!$lowercase){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'Password should include at least one lower case letter'));
                redirect('Consultant/main_info');
            }else if(!$number){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'Password should include at least one number'));
                redirect('Consultant/main_info');
            }else if(!$specialChars){
                $this->session->set_flashdata('password', array('success' => false, 'message' => 'Password should include at least one one special character'));
                redirect('Consultant/main_info');
            }
            
            $password   = getHashedPassword($param['password']);
            $this->db->where('consultant_id', $user->consultant_id);
            $result     = $this->db->update('consultant', array('password' => $password));
            if ($result){
                $this->session->set_flashdata('password', array('success' => true, 'message' => 'Password Updated Successfully'));
            }else{
                $this->session->set_flashdata('password', array('success' => true, 'message' => 'Password Not Updated'));
            }

            redirect('Consultant/main_info');
        }else{
            redirect('Welcome');
        }
    }

	public function invoice()
	{
        $data['cc1'] = 'active';
        $data['c5']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');

        $start_date = $this->input->post('filter_start');
        $end_date = $this->input->post('filter_end');
        $date = date('Y-m-d');
        if($start_date == NULL){
            $start_date = date('Y-m-d', strtotime($date . ' - 29 days'));
        }
        if($end_date == NULL){
            $end_date = date('Y-m-d');
        }
        if($consultant_id){
            $data['title'] = "Invoice";
            $filter['from'] = $start_date;
            $filter['to'] = $end_date;
            $filter['admin_id'] = $consultant_id;
            $data['invoices'] = $this->invoice->getAll($filter, 'create_date', 'DESC');

            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;

            $this->db->select('SUM(amount) as amount');
            $this->db->where('admin_id',$consultant_id);
            $this->db->where('create_date >=', $start_date)->where('create_date <=',$end_date);
            $data['total_amount'] = $this->db->get('invoice')->row()->amount;
            $this->db->select('SUM(amount) as amount');
            $this->db->where('admin_id',$consultant_id);
            $this->db->where('status','pending');
            $this->db->where('create_date >=', $start_date)->where('create_date <=',$end_date);
            $data['total_open_amount'] = $this->db->get('invoice')->row()->amount;
            $this->db->select('SUM(amount) as amount');
            $this->db->where('admin_id',$consultant_id);
            $this->db->where('status','paid');
            $this->db->where('create_date >=', $start_date)->where('create_date <=',$end_date);
            $data['total_paid_amount'] = $this->db->get('invoice')->row()->amount;

            $this->load->view('consultant/invoice/invoice_list',$data);
        }else{
            redirect('Welcome');
        }
	}

    public function invoice_view($id = NULL){
        $data['cc1'] = 'active';
        $data['c5']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        if($consultant_id){
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
            $admin = $this->db->query("select consultant.*,company.name name,company.phone from consultant left join company on consultant.consultant_id=company.consultant_id 
                    where consultant.consultant_id='$consultant_id'")->row();
            //$admin = $this->db->select('*')->where('consultant_id',$consultant_id)->get('consultant')->row();
            $data['customer_admin'] = $admin;
            $this->load->view('consultant/invoice/invoice_view',$data);
        }else{
            redirect('Welcome');
        }
    }

    public function invoice_pdf() {
        //$out_html = $this->input->post('pdf_val');
        $id = $this->input->post('view_invoice_id');
        //$superadmin_id = $this->session->userdata('superadmin_id');
        $consultant_id = $this->session->userdata('consultant_id');
        if(/*$superadmin_id ||*/ $consultant_id) {
            $super = $this->db->select('username,email,company_name,address,city,phone,fax,logo')->get('admin')->row();
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
            $customer_admin = $this->db->query
                ("select consultant.*,company.name name,company.phone from consultant left join company on consultant.consultant_id=company.consultant_id where consultant.consultant_id='$invoice->admin_id'")->row();

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

    public function process_audit_list()
    {
        $user_type = 1;
        $data['cc1'] = 'active';
        $data['c2']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $data['title'] = "Process Audit Manage";

            $plan_id = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
            $rowdata1 = @$this->db->query("SELECT COUNT(pa_id) as tot_pro FROM `audit_list` WHERE `company_id`='$consultant_id'")->row()->tot_pro;
            if ($plan_id) {
                $rowdata               = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
                $data['total_account'] = $rowdata1;
                $data['limit']         = $rowdata;
                $data['reached']       = (($rowdata1 * 100) / $rowdata);
            }

            $this->db->join("type_of_audit","audit_list.audit_type = type_of_audit.type_id","left");
            $this->db->join("employees","audit_list.lead_auditor = employees.employee_id","left");
            $this->db->join("frequency","audit_list.frequency = frequency.frequency_id","left");
            $this->db->join("trigger","audit_list.trigger = trigger.trigger_id","left");
            $this->db->where('audit_list.company_id', $consultant_id);
            $this->db->order_by('audit_list.created_at', 'desc');
            $this->db->order_by('audit_list.pa_id', 'desc');
            $data['audits'] = $this->db->get('audit_list')->result();

            $this->db->where('company_id', $consultant_id);
            $data['audit_types'] = $this->db->get('type_of_audit')->result();

            $this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
            $this->db->where('employees.consultant_id', $consultant_id);
            $this->db->where('permision.type_id', $user_type);
            $data['lead_audits'] = $this->db->get('employees')->result();
            $this->db->where('company_id', $consultant_id);
            $data['frequences'] = $this->db->get('frequency')->result();
            $this->db->where('company_id', $consultant_id);
            $data['triggers'] = $this->db->get('trigger')->result();
            $this->load->view('consultant/process_audit_list', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function edit_audit()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $audit_type = $this->input->post('audit_type');
            $lead_audit = $this->input->post('lead_audit');
            $frequency = $this->input->post('frequency');
            $trigger = $this->input->post('trigger');
            $pa_id = $this->input->post('pa_id');
            $data = array(
                'audit_type' => $audit_type,
                'lead_auditor' => $lead_audit,
                'frequency' => $frequency,
                'trigger' => $trigger
            );
            $this->db->where('pa_id', $pa_id);
            $done = $this->db->update('audit_list', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Consultant/process_audit_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Consultant/process_audit_list');
            }
        } else {
            redirect('Welcome');
        }
    }

    public function delete_audit($id = Null)
    {
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $this->db->where('audit_id', $id);
            $this->db->delete('audit_brief');
            $this->db->where('audit_id', $id);
            $this->db->delete('audit_plan');
            $this->db->where('audit_id', $id);
            $this->db->delete('select_process');

            $this->db->where('pa_id', $id);
            $done = $this->db->delete('audit_list');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Consultant/process_audit_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Consultant/process_audit_list');
            }
        } else {
            redirect('Welcome');
        }
    }

    public function findaudit()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $id = $this->input->post('id');
            $this->db->where('pa_id', $id);
            $done = $this->db->get('audit_list')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }

    public function add_audit_form()
    {
        $user_type = 1;
        $data['cc1'] = 'active';
        $data['c2']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $data['title'] = "Add Process Audit";

            $this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
            $this->db->where('employees.consultant_id', $consultant_id);
            $this->db->where('permision.type_id', $user_type);
            $data['lead_audits'] = $this->db->get('employees')->result();

            $this->load->view('consultant/add_audit_form', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function add_audit_type()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $value = $this->input->post('type_of_audit');
        $data = array(
            'type_of_audit' => $value,
            'company_id' => $consultant_id
        );
        $done = $this->db->insert('type_of_audit', $data);
        if ($done) {
            $this->db->where('company_id', $consultant_id);
            $audit_type = $this->db->get('type_of_audit')->result();
            foreach ($audit_type as $audit_types) {
                echo "<option value='" . $audit_types->type_id . "'>" . $audit_types->type_of_audit . "</option>";
            }
        } else {
        }
    }

    public function all_audittype()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $consultant_id);
        $audittype = $this->db->get('type_of_audit')->result();
        foreach ($audittype as $types) {
            echo "<option value='" . $types->type_id . "'>" . $types->type_of_audit . "</option>";
        }
    }

    public function all_audittype_table()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $consultant_id);
        $audittype = $this->db->get('type_of_audit')->result();
        $index = 1;
        foreach ($audittype as $types) {
            echo "<tr><td>" . $index . "</td><td>" . $types->type_of_audit . "</td><td><a onclick='deleteaudittype(" . $types->type_id . ");';><i class='icon-trash'></i></a></td><tr>";
            $index ++;
        }
    }

    public function delete_audittypet()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $id = $this->input->post('id');
        $pa_id = @$this->db->query("SELECT * FROM `audit_list` WHERE `audit_type`='$id'")->row()->pa_id;
        if ($pa_id != null) {
            echo json_encode('failure');
        } else {
            $this->db->where('type_id', $id);
            $this->db->delete('type_of_audit');
            echo json_encode('success');
        }
    }

    public function add_trigger()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $value = $this->input->post('trigger');
        $data = array(
            'trigger_name' => $value,
            'company_id' => $consultant_id
        );
        $done = $this->db->insert('trigger', $data);
        if ($done) {
            $this->db->where('company_id', $consultant_id);
            $triggers = $this->db->get('trigger')->result();
            foreach ($triggers as $trigger) {
                echo "<option value='" . $trigger->trigger_name . "'>" . $trigger->trigger_name . "</option>";
            }
        } else {
        }
    }

    public function all_trigger()
    {
        $checklist_id = $this->input->post('name');
        $consultant_id = $this->session->userdata('consultant_id');
        $sql = "SELECT
                        c.trigger
                    FROM
                        checklist AS a
                    LEFT JOIN select_process AS e ON a.process_id = e.id
                    LEFT JOIN process_list AS b ON e.process_id = b.process_id
                    LEFT JOIN audit_log_list AS d ON e.audit_id = d.log_id
                    LEFT JOIN audit_list AS c ON d.audit_id = c.pa_id
                    WHERE
                        a.id = " . $checklist_id;
        $checklist = $this->db->query($sql)->result();
        $this->db->where('company_id', $consultant_id);
        $triggers = $this->db->get('trigger')->result();
        foreach ($triggers as $trigger) {
            if (count($checklist) > 0 && $checklist[0]->trigger == $trigger->trigger_id){
                echo "<option value='" . $trigger->trigger_id . "' selected>" . $trigger->trigger_name . "</option>";
            }else{
                echo "<option value='" . $trigger->trigger_id . "'>" . $trigger->trigger_name . "</option>";
            }
        }
    }

    public function all_trigger_table()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $consultant_id);
        $triggers = $this->db->get('trigger')->result();
        $index = 1;
        foreach ($triggers as $trigger) {
            echo "<tr><td>" . $index . "</td><td>" . $trigger->trigger_name . "</td><td><a onclick='deletetrigger(" . $trigger->trigger_id . ");';><i class='icon-trash'></i></a></td><tr>";
            $index ++;
        }
    }

    public function delete_trigger()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $id = $this->input->post('id');
        $pa_id = @$this->db->query("SELECT * FROM `audit_list` WHERE `trigger`='$id'")->row()->pa_id;
        if ($pa_id != null) {
            echo json_encode('failure');
        } else {
            $this->db->where('trigger_id', $id);
            $this->db->delete('trigger');
            echo json_encode('success');
        }
    }

    public function add_frequency()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $name = $this->input->post('frequency');
        $days = $this->input->post('days');
        $data = array(
            'frequency_name' => $name,
            'days' => $days,
            'company_id' => $consultant_id
        );
        $done = $this->db->insert('frequency', $data);
        if ($done) {
            $this->db->where('company_id', $consultant_id);
            $frequencys = $this->db->get('frequency')->result();
            foreach ($frequencys as $frequency) {
                echo "<option value='" . $frequency->frequency_id . "'>" . $frequency->frequency_name . "</option>";
            }
        } else {
        }
    }

    public function all_frequency()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $consultant_id);
        $frequencys = $this->db->get('frequency')->result();
        foreach ($frequencys as $frequency) {
            echo "<option value='" . $frequency->frequency_id . "'>" . $frequency->frequency_name . "</option>";
        }
    }

    public function all_frequency_table()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $consultant_id);
        $frequencys = $this->db->get('frequency')->result();
        $index = 1;
        foreach ($frequencys as $frequency) {
            echo "<tr><td>" . $index . "</td><td>" . $frequency->frequency_name . "</td><td>" . $frequency->days . "</td><td><a onclick='deletefrequency(" . $frequency->frequency_id . ");';><i class='icon-trash'></i></a></td><tr>";
            $index ++;
        }
    }

    public function delete_frequency()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $id = $this->input->post('id');
        $pa_id = @$this->db->query("SELECT * FROM `audit_list` WHERE `frequency`='$id'")->row()->pa_id;
        if ($pa_id != null) {
            echo json_encode('failure');
        } else {
            $this->db->where('frequency_id', $id);
            $this->db->delete('frequency');
            echo json_encode('success');
        }
    }

    public function add_audit_action()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $audit_type = $this->input->post('audit_type');
            $lead_auditor= $this->input->post('lead_auditor');
            $frequency = $this->input->post('frequency');
            $type = $this->input->post('type');
            $trigger = $this->input->post('trigger');
            $created_at = date('Y-m-d');

            //--------------------get admin & lead_audotor info------------------------
            $company_name = $this->db->where('consultant_id', $consultant_id)->get('consultant')->row()->consultant_name;
            $lead_auditor_info = $this->db->where('employee_id', $lead_auditor)->get('employees')->row();
            //-------------------------------------------------------------------------

            $data = array(
                'audit_type' => $audit_type,
                'lead_auditor' => $lead_auditor,
                'frequency' => $frequency,
                'trigger' => $trigger,
                'type' => $type,
                'company_id' => $consultant_id,
                'created_at' => $created_at
            );
            $plan_id = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
            if ($plan_id) {
                $rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
            }
            $rowdata1 = @$this->db->query("SELECT COUNT(pa_id) as ids FROM `audit_list` WHERE `company_id`='$consultant_id'")->row()->ids;
            if ($rowdata == $rowdata1 || $rowdata1 > $rowdata) {
                $this->session->set_flashdata('message', 'overflow');
                redirect('Consultant/process_audit_list');
            } else {
                $done = $this->db->insert('audit_list', $data);
                if ($done) {

                    //---------------------------------------send email-------------------------------------------
                    $email_temp = $this->getEmailTemp('When Audit is scheduled by Admin to Lead Auditor');
                    $email_temp['message'] = str_replace("{Lead Auditor NAME}", $lead_auditor_info->employee_name, $email_temp['message']);
                    $email_temp['message'] = str_replace("{Company Name}", $company_name, $email_temp['message']);
                    $email_temp['message'] = str_replace("{COURSE_NAME}", 'phpstack-971964-3536769.cloudwaysapps.com', $email_temp['message']);
                    $email_temp['message'] = str_replace("{Audit Here}", 'Audit Here', $email_temp['message']);
                    $email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
                    $this->sendemail($lead_auditor_info->employee_email, 'Audit is scheduled by Admin', $email_temp['message'], $email_temp['subject'], 1);
                    //--------------------------------------------------------------------------------------------

                    $this->session->set_flashdata('message', 'success');
                    redirect('Consultant/process_audit_list');
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Consultant/process_audit_list');
                }
            }
        } else {
            redirect('Welcome');
        }
    }

    public function audits()
    {
        $data['aa1'] = 'active';
        $data['a1']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        $filter = $this->input->post();
        $leadauditor_sel = $this->input->post('leadauditor_sel');
        $company_start = $this->input->post('company_start');
        $company_end = $this->input->post('company_end');
        if ($consultant_id) {
            $data['title'] = "Audit";

            $sql_sub = '';
            if (!empty($company_start) && !empty($company_end)) {
                $sql_sub = " and audit.created_at > '".$company_start."'
                        and audit.created_at < '".$company_end."'";
            }else{
                $filter['company_start'] = date("Y-m-d");
                $filter['company_end'] = date("Y-m-d");
            }
                        
            if (!isset($leadauditor_sel) || $leadauditor_sel == -1) {
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
                            audit.company_id = " . $consultant_id . $sql_sub .
                            " ORDER BY audit.created_at DESC, pa_id DESC ";
            } else {
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
                        and audit.lead_auditor=".$leadauditor_sel.$sql_sub."
                        ORDER BY audit.created_at DESC, pa_id DESC ";
            }

            $data['audits'] = $this->db->query($sql)->result();
            $leadauditors   = $this->db->query("SELECT * FROM `employees`,permision,user_type WHERE employees.employee_id = permision.employee_id &&
                permision.type_id = user_type.utype_id && user_type.utype_id = 1 &&
                `consultant_id`='$consultant_id'")->result();
            $data['leadauditors'] = $leadauditors;
            $data['filter'] = $filter;

            $this->load->view('consultant/audits', $data);
        } else {
            redirect('Welcome');
        }
    }
/////////////Audit Delete for admin//////////
     public function del_audit(){
        $employee_id = $this->session->userdata('employee_id');
        $consultant_id = $this->session->userdata('consultant_id');
        $process_id    = $this->input->post('process_id_del');
        if ($consultant_id) {
            $this->db->where('pa_id', $process_id);
            $done = $this->db->delete('audit_list');
            if ($done) {
                    $this->session->set_flashdata('message', 'success_del');
                    redirect('Consultant/audits/');
             } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Consultant/audits/');
             }
        } else {
            redirect('Welcome');
        }
    }
///////////////////////End//////////////////    
    // public function audit_brief($log_id = Null)
    public function audit_brief($audit_id = Null)
    {
        $data['aa1'] = 'active';
        $data['a1']  = 'act1';
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $data['title']  = 'Audit Brief';

            // $audit_log_list = $this->db->query("SELECT * FROM `audit_log_list` WHERE `log_id`='$log_id'")->row();

            // if($log_id == null || $audit_log_list == null) {
            //     redirect('Consultant/audits');
            // } else {

               /* $data['log_id'] = $log_id;
                $this->db->where('audit_id', $log_id);
                $data['audit_brief_array'] = $this->db->get('audit_brief')->row();
                if($data['audit_brief_array'] != null) {
                    $data['is_brief'] = TRUE;
                }
                else {
                    $data['is_brief'] = FALSE;
                }
                $this->load->view('consultant/audit_brief', $data);*/
                $data['audit_id'] = $audit_id;
				$data['audit_brief_array'] = $this->audit_brief->selectOne(array('audit_id'=>$audit_id));
                if($data['audit_brief_array'] != null) {
                    $data['is_brief'] = TRUE;
                }
                else {
                    $data['is_brief'] = FALSE;
                    // while($data['audit_brief_array'] == null && $log_id > 1){
                    //     $log_id -= 1;
                    //     $this->db->where('audit_id', $log_id);
                    //     $data['audit_brief_array'] = $this->db->get('audit_brief')->row();
                    //     if($data['audit_brief_array'] != null){
                    //         $audit_id = $data['audit_brief_array']->audit_id;
                    //         $sql = "SELECT type.company_id from type_of_audit type, audit_list audit, audit_log_list log
                    //         WHERE type.type_id = audit.audit_type and log.audit_id = audit.pa_id and log.log_id = '$audit_id'";
                    //       //  $company_id = $this->db->query($sql)->row()->company_id;
                    //         $company_id = $this->db->query($sql)->row('company_id');
                    //         if($company_id == $consultant_id){
                    //             $data['log_id'] = $log_id;
                    //             $data['is_brief'] = TRUE;
                    //         }
                    //         else
                    //             $data['audit_brief_array'] = null;
                    //     }
                    // }
                }
                $this->load->view('consultant/audit_brief', $data);
            // }


        } else {
            redirect('Welcome');
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
                    'open_when' => date('m/d/Y', strtotime( $audit_plan_array->open_when )),
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
            $this->load->view('consultant/audit_plan', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function employees()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $data['cc1'] = 'active';
        $data['c1']  = 'act1';
        $search_name = $this->input->post('search_name');
        $usertype_sel = $this->input->post('usertype_sel');
        if ($consultant_id) {
            $data['title'] = "Employee List";
            $plan_id = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
            $rowdata1 = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `consultant_id`='$consultant_id'")->row()->emps;
            if ($plan_id) {
                $rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
                $data['total_account'] = $rowdata1;
                $data['limit']         = $rowdata;
                $data['reached']       = (($rowdata1 * 100) / $rowdata);
            }
            $where = "";
            $data['search_name'] = "";
            if ($search_name != Null && $search_name != '') {
                $data['search_name'] = $search_name;
                $where .= " AND e.employee_name like '%" . $search_name . "%'";
            }
            if($usertype_sel != Null && $usertype_sel != '0') {
                $where .= " AND p.type_id = " . $usertype_sel;
            }
            $sql = "SELECT
                            *, GROUP_CONCAT(t.utype_name) type_name
                        FROM
                            employees e
                        LEFT JOIN permision p ON e.employee_id = p.employee_id
                        LEFT JOIN user_type t ON p.type_id = t.utype_id
                        WHERE
                            e.consultant_id = " . $consultant_id . $where . "
                        GROUP BY
                            e.employee_id";
            //SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

            $data['employees'] = $this->db->query($sql)->result();
            $this->load->view('consultant/employees', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function add_meeting_employee(){
        $consultant_id = $this->session->userdata('consultant_id');
        $confirm = TRUE;
        if ($consultant_id) {
            $employee_name  = $this->input->post('add_name');
            $employee_email = $this->input->post('add_email');
            $role_name = $this->input->post('add_role');
            $username = $this->input->post('add_username');
            $password = getHashedPassword($this->input->post('add_password'));
            $lead_auditor = $this->input->post('lead_auditor');
            $auditor = $this->input->post('auditor');
            $process_owner = $this->input->post('process_owner');
            $auditee = $this->input->post('auditee');
            $created_at = date('Y-m-d');
            $data = array(
                'consultant_id' => $consultant_id,
                'employee_name' => $employee_name,
                'username' => $username,
                'employee_email' => $employee_email,
                'role' => $role_name,
                'password' => $password,
                'created_at' => $created_at,
                'status' => 1
            );

            $employee_list = $this->db->query("SELECT * FROM `employees` WHERE `username`='$username'")->row();
            if($employee_list == null) {
                $plan_id = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
                if ($plan_id) {
                    $rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
                }
                $rowdata1 = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `consultant_id`='$consultant_id'")->row()->emps;
                if ($rowdata == $rowdata1 || $rowdata1 > $rowdata) {
                    echo json_encode('failed');
                } else {
                    $done = $this->db->insert('employees', $data);
                    if ($done) {
                        $this->db->order_by('employee_id', 'asc');
                        $employee_id = $this->db->get('employees')->last_row()->employee_id;
                        if($lead_auditor != "" && $lead_auditor != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $lead_auditor
                            );
                            $confirm = $this->db->insert('permision', $tmp);
                        }
                        if($auditor != "" && $auditor != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $auditor
                            );
                            $confirm = $confirm & $this->db->insert('permision', $tmp);
                        }
                        if($process_owner != "" && $process_owner != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $process_owner
                            );
                            $confirm = $confirm & $this->db->insert('permision', $tmp);
                        }
                        if($auditee != "" && $auditee != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $auditee
                            );
                            $confirm = $confirm & $this->db->insert('permision', $tmp);
                        }
                        echo json_encode('success');
                    } else {
                        echo json_encode('failed');
                    }
                }
            } else {
                echo json_encode('live_err');
            }
        } else {
            echo json_encode('failed');
        }
    }

    public function add_employee()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $confirm = TRUE;
        if ($consultant_id) {
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
            $lead_auditor = $this->input->post('lead_auditor');
            $auditor = $this->input->post('auditor');
            $process_owner = $this->input->post('process_owner');
            $auditee = $this->input->post('auditee');
            $created_at = date('Y-m-d');
            $data = array(
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

            $employee_list = $this->db->query("SELECT * FROM `employees` WHERE `username`='$username'")->row();
            if($employee_list == null) {
                $plan_id = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
                if ($plan_id) {
                    $rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
                }
                $rowdata1 = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `consultant_id`='$consultant_id'")->row()->emps;
                if ($rowdata == $rowdata1 || $rowdata1 > $rowdata) {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Consultant/employees');
                } else {
                    $done = $this->db->insert('employees', $data);
                    if ($done) {
                        $this->db->order_by('employee_id', 'asc');
                        $employee_id = $this->db->get('employees')->last_row()->employee_id;
                        if($lead_auditor != "" && $lead_auditor != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $lead_auditor
                            );
                            $confirm = $this->db->insert('permision', $tmp);
                        }
                        if($auditor != "" && $auditor != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $auditor
                            );
                            $confirm = $confirm & $this->db->insert('permision', $tmp);
                        }
                        if($process_owner != "" && $process_owner != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $process_owner
                            );
                            $confirm = $confirm & $this->db->insert('permision', $tmp);
                        }
                        if($auditee != "" && $auditee != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $auditee
                            );
                            $confirm = $confirm & $this->db->insert('permision', $tmp);
                        }
                        $this->session->set_flashdata('message', 'success');
                        redirect('Consultant/employees');
                    } else {
                        $this->session->set_flashdata('message', 'error');
                        redirect('Consultant/employees');
                    }
                }
            } else {
                $this->session->set_flashdata('message', 'live_err');
                redirect('Consultant/employees');
            }
        } else {
            redirect('Welcome');
        }
    }

    public function delete_employee($id = Null)
    {
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $this->db->where('employee_id', $id);
            $done = $this->db->delete('employees');
            if ($done) {
                $this->db->where('employee_id', $id);
                $confirm = $this->db->delete('permision');
                if($confirm) {
                    $this->session->set_flashdata('message', 'success_del');
                    redirect('consultant/employees');
                } else {
                    $this->session->set_flashdata('message', 'error');
                    redirect('consultant/employees');
                }
            } else {
                $this->session->set_flashdata('message', 'error');
                redirect('consultant/employees');
            }
        } else {
            redirect('Welcome');
        }
    }

    public function finduser()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
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

    public function edit_employee()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $confirm = TRUE;
        if ($consultant_id) {
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
            $lead_auditor = $this->input->post('edit_lead_auditor');
            $auditor = $this->input->post('edit_auditor');
            $process_owner = $this->input->post('edit_process_owner');
            $auditee = $this->input->post('edit_auditee');
            $old_username = $this->input->post('old_username');

            /** check password validate in admin employee update  */
            if (!empty(trim($this->input->post('edit_password')))){
                $uppercase = preg_match('@[A-Z]@', $this->input->post('edit_password'));
                $lowercase = preg_match('@[a-z]@', $this->input->post('edit_password'));
                $number    = preg_match('@[0-9]@', $this->input->post('edit_password'));
                $specialChars = preg_match('@[^\w]@', $this->input->post('edit_password'));
                if(strlen($this->input->post('edit_password')) < 8){
                    $this->session->set_flashdata('message', "pwd_error");
                    redirect('Consultant/employees');
                }else if(!$uppercase ){
                    $this->session->set_flashdata('message', "pwd_error");
                    redirect('Consultant/employees');
                }else if(!$lowercase){
                    $this->session->set_flashdata('message', "pwd_error");
                    redirect('Consultant/employees');
                }else if(!$number){
                    $this->session->set_flashdata('message', "pwd_error");
                    redirect('Consultant/employees');
                }else if(!$specialChars){
                    $this->session->set_flashdata('message',"pwd_error");
                    redirect('Consultant/employees');
                }
                $password = getHashedPassword($this->input->post('edit_password'));
            
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
            }else{
                $data = array(
                    'consultant_id' => $consultant_id,
                    'employee_name' => $employee_name,
                    'username' => $username,
                    'employee_email' => $employee_email,
                    'employee_phone' => $phone,
                    'role' => $role_name,
                    'status' => 1
                );
            }
             
            $employee_list = $this->db->query("SELECT * FROM `employees` WHERE `username`='$username'")->row();
            if($employee_list == null) {
                $this->db->where("employee_id", $employee_id);
                $done = $this->db->update('employees', $data);
                if ($done) {
                    $this->db->where("employee_id", $employee_id);
                    $this->db->delete("permision");

                    if ($lead_auditor != "" && $lead_auditor != null) {
                        $tmp = array(
                            'employee_id' => $employee_id,
                            'type_id' => $lead_auditor
                        );
                        $confirm = $this->db->insert('permision', $tmp);
                    }
                    if ($auditor != "" && $auditor != null) {
                        $tmp = array(
                            'employee_id' => $employee_id,
                            'type_id' => $auditor
                        );
                        $confirm = $confirm & $this->db->insert('permision', $tmp);
                    }
                    if ($process_owner != "" && $process_owner != null) {
                        $tmp = array(
                            'employee_id' => $employee_id,
                            'type_id' => $process_owner
                        );
                        $confirm = $confirm & $this->db->insert('permision', $tmp);
                    }
                    if ($auditee != "" && $auditee != null) {
                        $tmp = array(
                            'employee_id' => $employee_id,
                            'type_id' => $auditee
                        );
                        $confirm = $confirm & $this->db->insert('permision', $tmp);
                    }
                    $this->session->set_flashdata('message', 'update_success');
                    redirect('Consultant/employees');
                } else {
                    $this->session->set_flashdata('message', 'error');
                    redirect('Consultant/employees');
                }
            } else {
                if($old_username == $username) {
                    $this->db->where("employee_id", $employee_id);
                    $done = $this->db->update('employees', $data);
                    if ($done) {
                        $this->db->where("employee_id", $employee_id);
                        $this->db->delete("permision");

                        if ($lead_auditor != "" && $lead_auditor != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $lead_auditor
                            );
                            $confirm = $this->db->insert('permision', $tmp);
                        }
                        if ($auditor != "" && $auditor != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $auditor
                            );
                            $confirm = $confirm & $this->db->insert('permision', $tmp);
                        }
                        if ($process_owner != "" && $process_owner != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $process_owner
                            );
                            $confirm = $confirm & $this->db->insert('permision', $tmp);
                        }
                        if ($auditee != "" && $auditee != null) {
                            $tmp = array(
                                'employee_id' => $employee_id,
                                'type_id' => $auditee
                            );
                            $confirm = $confirm & $this->db->insert('permision', $tmp);
                        }
                        $this->session->set_flashdata('message', 'update_success');
                        redirect('Consultant/employees');
                    } else {
                        $this->session->set_flashdata('message', 'error');
                        redirect('Consultant/employees');
                    }
                } else {
                    $this->session->set_flashdata('message', 'live_err');
                    redirect('Consultant/employees');
                }
            }
        } else {
            redirect('Welcome');
        }
    }

    public function all_auditors($log_id = Null)
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $auditor = 2;
        $search_name = $this->input->post('name');

        $this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
        $this->db->where('employees.consultant_id', $consultant_id);
        $this->db->where('permision.type_id', $auditor);
        if($search_name != '1') {
            $this->db->like('employees.employee_name', $search_name);
        }
        $auditors = $this->db->get('employees')->result();

        $this->db->where('audit_id', $log_id);
        $audit_brief_array = $this->db->get('audit_brief')->row();
        $index = 1;
        foreach ($auditors as $auditor) {
            $flag = FALSE;
            echo '<tr>';
            echo '<td>' . $index . '</td>';
            if($audit_brief_array != null) {
                $arrays = explode(",", $audit_brief_array->audit_team);
                for($i = 1; $i < count($arrays); $i++) {
                    if($auditor->employee_id == $arrays[$i]) {
                        $flag = TRUE;
                        echo '<td><input class="styled auditor_checker" type="checkbox" checked value="' . $auditor->employee_id . '"></td>';
                        break;
                    }
                }
                if(!$flag)  {
                    echo '<td><input class="styled auditor_checker" type="checkbox" value="' . $auditor->employee_id . '"></td>';
                }
            } else {
                echo '<td><input class="styled auditor_checker" type="checkbox" value="' . $auditor->employee_id . '"></td>';
            }
            echo '<td>' . $auditor->employee_name . '</td>';
            echo '<td>' . $auditor->employee_email . '</td>';
            echo '<td>' . $auditor->role . '</td>';
            echo '<td>' . $auditor->username . '</td>';
            echo '<td width="50">' . $auditor->password . '</td>';
            echo '<td>';
            echo '<ul class="icons-list">';
            echo '<li class="text-primary-600" onclick="edit_auditor(' . $auditor->employee_id . ')"><a><i class="icon-pencil7"></i></a></li>';
            echo '<li class="text-danger-600"><a id="' . $auditor->employee_id . '" class="delete_auditor" ><i class="icon-trash"></i></a></li>';
            echo '</ul>';
            echo '</td>';
            echo '</tr>';
            $index ++;
        }
    }

    public function all_owners($log_id = Null)
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $process_owner = 3;
        $search_name = $this->input->post('name');

        $this->db->join("permision", "employees.employee_id = permision.employee_id", "left");
        $this->db->where('employees.consultant_id', $consultant_id);
        $this->db->where('permision.type_id', $process_owner);
        if($search_name != '1') {
            $this->db->like('employees.employee_name', $search_name);
        }
        $owners = $this->db->get('employees')->result();

        $this->db->where('audit_id', $log_id);
        $audit_brief_array = $this->db->get('audit_brief')->row();
        $index = 1;
        foreach ($owners as $owner) {
            $flag = FALSE;
            echo '<tr>';
            echo '<td>' . $index . '</td>';
            if($audit_brief_array != null) {
                $arrays = explode(",", $audit_brief_array->process_owners);
                for($i = 1; $i < count($arrays); $i++) {
                    if($owner->employee_id == $arrays[$i]) {
                        $flag = TRUE;
                        echo '<td><input class="styled auditor_checker" type="checkbox" checked value="' . $owner->employee_id . '"></td>';
                        break;
                    }
                }
                if(!$flag)  {
                    echo '<td><input class="styled auditor_checker" type="checkbox" value="' . $owner->employee_id . '"></td>';
                }
            } else {
                echo '<td><input class="styled auditor_checker" type="checkbox" value="' . $owner->employee_id . '"></td>';
            }
            echo '<td>' . $owner->employee_name . '</td>';
            echo '<td>' . $owner->employee_email . '</td>';
            echo '<td>' . $owner->role . '</td>';
            echo '<td>' . $owner->username . '</td>';
            echo '<td>' . $owner->password . '</td>';
            echo '<td>';
            echo '<ul class="icons-list">';
            echo '<li class="text-primary-600" onclick="edit_owner(' . $owner->employee_id . ')"><a><i class="icon-pencil7"></i></a></li>';
            echo '<li class="text-danger-600"><a id="' . $owner->employee_id . '" class="delete_owner" ><i class="icon-trash"></i></a></li>';
            echo '</ul>';
            echo '</td>';
            echo '</tr>';
            $index ++;
        }
    }

    public function add_auditor()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $employee_name  = $this->input->post('name');
        $employee_email = $this->input->post('email');
        $role_name = $this->input->post('role');
        $username = $this->input->post('username');
        $password = getHashedPassword($this->input->post('password'));
        $auditor = $this->input->post('auditor');
        $created_at = date('Y-m-d');
        
        $data = array(
            'consultant_id' => $consultant_id,
            'employee_name' => $employee_name,
            'username' => $username,
            'employee_email' => $employee_email,
            'role' => $role_name,
            'password' => $password,
            'created_at' => $created_at,
            'status' => 1
        );
        $employee_list = $this->db->query("SELECT * FROM `employees` WHERE `username`='$username'")->row();
        if($employee_list == null) {
            $plan_id = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
            if ($plan_id) {
                $rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
            }
            $rowdata1 = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `consultant_id`='$consultant_id'")->row()->emps;
            if ($rowdata1 < $rowdata) {
                $done = $this->db->insert('employees', $data);
                if ($done) {
                    $this->db->order_by('employee_id', 'asc');
                    $employee_id = $this->db->get('employees')->last_row()->employee_id;
                    if($auditor != "" && $auditor != null) {
                        $tmp = array(
                            'employee_id' => $employee_id,
                            'type_id' => $auditor
                        );
                        $this->db->insert('permision', $tmp);
                    }
                }
                echo json_encode('success');
            } else {
                echo json_encode('failed');
            }
        } else {
            echo json_encode('live_err');
        }
    }

    public function edit_employee_any()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $employee_name  = $this->input->post('name');
        $employee_email = $this->input->post('email');
        $role_name = $this->input->post('role');
        $username = $this->input->post('username');
        $password = getHashedPassword($this->input->post('password'));
        $employee_id    = $this->input->post('id');
        $oldpassword = $this->db->query("SELECT password FROM employees WHERE employee_id = '$employee_id'")->row()->password;
        if($oldpassword == $this->input->post('password'))
            $password = $oldpassword;
        $data = array(
            'consultant_id' => $consultant_id,
            'employee_name' => $employee_name,
            'username' => $username,
            'employee_email' => $employee_email,
            'role' => $role_name,
            'password' => $password,
            'status' => 1
        );
        if (empty(trim($this->input->post('password')))){
            unset($data['password']);
        }
        $employee_list = $this->db->query("SELECT * FROM `employees` WHERE `username`='$username'")->row();
        if($employee_list == null) {
            $this->db->where("employee_id", $employee_id);
            $this->db->update('employees', $data);
            echo json_encode('success');
        } else {
            echo json_encode('live_err');
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

    public function add_owner()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $employee_name  = $this->input->post('name');
        $employee_email = $this->input->post('email');
        $role_name = $this->input->post('role');
        $username = $this->input->post('username');
        $password = getHashedPassword($this->input->post('password'));
        $process_owner = $this->input->post('process_owner');
        $created_at = date('Y-m-d');
        $data = array(
            'consultant_id' => $consultant_id,
            'employee_name' => $employee_name,
            'username' => $username,
            'employee_email' => $employee_email,
            'role' => $role_name,
            'password' => $password,
            'created_at' => $created_at,
            'status' => 1
        );
        $employee_list = $this->db->query("SELECT * FROM `employees` WHERE `username`='$username'")->row();
        if($employee_list == null) {
            $plan_id = @$this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->plan_id;
            if ($plan_id) {
                $rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
            }
            $rowdata1 = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `consultant_id`='$consultant_id'")->row()->emps;
            if ($rowdata1 < $rowdata) {
                $done = $this->db->insert('employees', $data);
                if ($done) {
                    $this->db->order_by('employee_id', 'asc');
                    $employee_id = $this->db->get('employees')->last_row()->employee_id;
                    if($process_owner != "" && $process_owner != null) {
                        $tmp = array(
                            'employee_id' => $employee_id,
                            'type_id' => $process_owner
                        );
                        $this->db->insert('permision', $tmp);
                    }
                }
                echo json_encode('success');
            } else {
                echo json_encode('failed');
            }
        } else {
            echo json_encode('live_err');
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
        $consultant_id = $this->session->userdata('consultant_id');
        $open_who_list = $this->input->post("open_who_list");
        $open_when = $this->input->post("open_when");
        $open_where = $this->input->post("open_where");
        $open_cover = $this->input->post("open_cover");
        $schedule = $this->input->post("schedule");
        $close_who_list = $this->input->post("close_who_list");
        $close_when = $this->input->post("close_when");
        $close_where = $this->input->post("close_where");
        if ($consultant_id) {
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
            $this->load->view('consultant/select_process', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function add_process($pa_id = Null)
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $process_name = $this->input->post('process_name');
        $description = $this->input->post('description');
        if($consultant_id) {
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
                    redirect('Consultant/select_process/' . $pa_id);
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Consultant/select_process/' . $pa_id);
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
        $consultant_id = $this->session->userdata('consultant_id');
        $process_list = $this->input->post("process_list");
        if ($consultant_id) {
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
                            if($process->status != 1) {
                                $this->db->update('select_process', array('checked' => 1, 'status' => 2));
                            } else {
                                $this->db->update('select_process', array('checked' => 1));
                            }
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
            // $this->db->where('permision.type_id', $auditee);
            
            $data['smes'] = $this->db->get('employees')->result();
            
            $this->db->where('log_id', $pa_id);
            $data['audit_log'] = $this->db->get('audit_log_list')->row();

            $data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
            $data['comp_email']      = $this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$consultant_id'")->row()->email;

            $this->db->select("s.auditor, e.employee_email")->from("select_process s");
            $this->db->join("employees e", "e.employee_id = s.auditor", "left");
            $this->db->where("s.audit_id", $pa_id);
            $this->db->group_by("s.auditor");
            $data["auditors_email"] = $this->db->get()->result();
            // $data['smes'] = $this->employee->getSMES($consultant_id);
            $this->load->view('consultant/audit_schedule', $data);
        } else {
            redirect('Welcome');
        }
    }

    public  function assign_process($pa_id = Null) {
        $consultant_id = $this->session->userdata('consultant_id');
        $assign_process_id = $this->input->post("assign_process_id");
        $auditor = $this->input->post("auditor");
        $owner = $this->input->post("process_owner");
        $auditee_array = $this->input->post("auditee");
        $map_type = $this->input->post("map_type");
        $startTime = $this->input->post('startTimeInput');
        $endTime = $this->input->post('endTimeInput');

        $auditee = "";
        foreach($auditee_array as $row){
            $auditee .= $row . ", ";
        }
        $auditee = substr($auditee, 0, -2);

        if ($consultant_id) {
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
            $auditor_info = $this->db->where('employee_id', $auditor)->get('employees')->row();
            $process_owner_info = $this->db->where('employee_id', $owner)->get('employees')->row();
            $process_name = $this->db->where('process_id', $assign_process_id)->get('process_list')->row()->process_name;
            //$dates = (int)abs((float)(date('Y-m-d', strtotime($endTime)) - date('Y-m-d',strtotime($startTime))) / (60 * 60 * 24));
            $dates = (int)abs((float)(strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24));
            //-----------------------------------------------------------------------------------------------

            //-------------------------------------------------send email-----------------------------------------------------
            $email_temp = $this->getEmailTemp('When Audit is scheduled by Admin to Auditor');
            $email_temp['message'] = str_replace("{Auditor NAME}", $auditor_info->employee_name, $email_temp['message']);
            $email_temp['message'] = str_replace("{Company Name}", $company_name, $email_temp['message']);
            $email_temp['message'] = str_replace("{COURSE_NAME}", 'phpstack-971964-3536769.cloudwaysapps.com', $email_temp['message']);
            $email_temp['message'] = str_replace("{Audit Here}", 'Audit Here', $email_temp['message']);
            $email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
            $this->sendemail($auditor_info->employee_email, 'Audit is scheduled by Admin', $email_temp['message'], $email_temp['subject'], 1);

            $email_temp = $this->getEmailTemp('When Audit is scheduled by Admin to Process Owner');
            $email_temp['message'] = str_replace("{Process Owner NAME}", $process_owner_info->employee_name, $email_temp['message']);
            $email_temp['message'] = str_replace("{dates}", $dates, $email_temp['message']);
            $email_temp['message'] = str_replace("{Process Name}", $process_name, $email_temp['message']);
            $email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
            $this->sendemail($process_owner_info->employee_email, 'Audit is scheduled by Admin', $email_temp['message'], $email_temp['subject'], 3);

            $email_temp = $this->getEmailTemp('When Audit is scheduled by Admin Lead Auditor to Process Owner');
            $email_temp['message'] = str_replace("{Process Owner NAME}", $process_owner_info->employee_name, $email_temp['message']);
            $email_temp['message'] = str_replace("{LOGO}", "<img src='cid:logo'>", $email_temp['message']);
            $this->sendemail($process_owner_info->employee_email, 'Audit is scheduled by Admin', $email_temp['message'], $email_temp['subject'], 3);
            //------------------------------------------------------------------------------------------------------------------

            redirect('Consultant/audit_schedule/' . $pa_id);
        } else {
            redirect('Welcome');
        }
    }

    public  function edit_process($pa_id = Null) {
        $consultant_id = $this->session->userdata('consultant_id');
        $edit_process_id = $this->input->post("edit_process_id");
        $auditor = $this->input->post("edit_auditor");
        $owner = $this->input->post("edit_owner");
        $auditee_array = $this->input->post("edit_auditee");
        $map_type = $this->input->post("edit_map_type");
        $startTime = $this->input->post('edit_startTimeInput');
        $endTime = $this->input->post('edit_endTimeInput');

        $auditee = "";
        foreach($auditee_array as $row){
            $auditee .= $row . ", ";
        }
        $auditee = substr($auditee, 0, -2);

        if ($consultant_id) {
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
            redirect('Consultant/audit_schedule/' . $pa_id);
        } else {
            redirect('Welcome');
        }
    }

    public function find_process()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $id = $this->input->post('id');
            $pa_id = $this->input->post('pa_id');
            $this->db->where('process_id', $id);
            $this->db->where('audit_id', $pa_id);
            $done = $this->db->get('select_process')->row();
            // $sql = "SELECT *, GROUP_CONCAT(t.utype_name) type_name
            //         FROM employees e
            //         LEFT JOIN permision p ON e.employee_id = p.employee_id
            //         LEFT JOIN user_type t ON p.type_id = t.utype_id
            //         WHERE e.consultant_id = " . $consultant_id . $where . "
            //         GROUP BYe.employee_id";
            // //SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

            // $data['employees'] = $this->db->query($sql)->result();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }
    public function open_audit()
    {
        $data['aa1'] = 'active';
        $data['a2']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        $leadauditor_sel = $this->input->post('leadauditor_sel');
        $company_start = $this->input->post('company_start');
        $company_end = $this->input->post('company_end');

        if ($consultant_id) {
            $data['title'] = "Open Audit";
            $sql_sub = '';
            if (!empty($company_start) && !empty($company_end)) 
                $sql_sub = " and audit.created_at > '".$company_start."'
                        and audit.created_at < '".$company_end."'";
            if (!isset($leadauditor_sel) || $leadauditor_sel == -1) {
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
                            audit.company_id = " . $consultant_id . $sql_sub. "
                        ORDER BY audit.created_at DESC, g.log_id DESC ";
            } else {
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
                            audit.company_id = " . $consultant_id . "
                            and audit.lead_auditor=".$leadauditor_sel.$sql_sub."
                        ORDER BY audit.created_at DESC, g.log_id DESC ";
            }
            $data['open_audits'] = $this->db->query($sql)->result();
            $leadauditors   = $this->db->query("SELECT * FROM `employees`,permision,user_type WHERE employees.employee_id = permision.employee_id &&
                permision.type_id = user_type.utype_id && user_type.utype_id = 1 &&
                `consultant_id`='$consultant_id'")->result();
            $data['leadauditors'] = $leadauditors;
            $this->load->view('consultant/open_audit', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function close_audit()
    {
        $data['aa1'] = 'active';
        $data['a3']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        $leadauditor_sel = $this->input->post('leadauditor_sel');
        $company_start = $this->input->post('company_start');
        $company_end = $this->input->post('company_end');
        if ($consultant_id) {
            $data['title'] = "Close Audit";
            $sql_sub = '';
            if (!empty($company_start) && !empty($company_end)) 
                $sql_sub = " and audit.created_at > '".$company_start."'
                        and audit.created_at < '".$company_end."'";

            if (!isset($leadauditor_sel) || $leadauditor_sel == -1) {
                $sql = "SELECT a.* ,count(a.pa_id) count
                    FROM (
                    SELECT
                        audit.*, type.type_of_audit,
                        e.employee_name,
                        f.frequency_name, t.trigger_name,
                        g.*
                    FROM
                        audit_log_list g
                    LEFT JOIN audit_list audit ON g.audit_id = audit.pa_id
                    LEFT JOIN type_of_audit type ON audit.audit_type = type.type_id
                    LEFT JOIN employees e ON audit.lead_auditor = e.employee_id
                    LEFT JOIN frequency f ON audit.frequency = f.frequency_id
                    LEFT JOIN `trigger` t ON audit.`trigger` = t.trigger_id
                    WHERE
                        g. STATUS = 1 and 
                        audit.company_id = " . $consultant_id . $sql_sub. "
                    ORDER BY
                        audit.created_at DESC,
                        pa_id DESC) a
                     GROUP BY a.pa_id";
            } else {
                $sql = "SELECT a.* ,count(a.pa_id) count
                    FROM (
                    SELECT
                        audit.*, type.type_of_audit,
                        e.employee_name,
                        f.frequency_name, t.trigger_name,
                        g.*
                    FROM
                        audit_log_list g
                    LEFT JOIN audit_list audit ON g.audit_id = audit.pa_id
                    LEFT JOIN type_of_audit type ON audit.audit_type = type.type_id
                    LEFT JOIN employees e ON audit.lead_auditor = e.employee_id
                    LEFT JOIN frequency f ON audit.frequency = f.frequency_id
                    LEFT JOIN `trigger` t ON audit.`trigger` = t.trigger_id
                    WHERE
                        g. STATUS = 1 and audit.lead_auditor=".$leadauditor_sel."
                        and audit.company_id = " . $consultant_id . $sql_sub. "
                    ORDER BY
                        audit.created_at DESC,
                        pa_id DESC) a
                     GROUP BY a.pa_id";
            }
            
            $data['close_audits'] = $this->db->query($sql)->result();
            $leadauditors   = $this->db->query("SELECT * FROM `employees`,permision,user_type WHERE employees.employee_id = permision.employee_id &&
                permision.type_id = user_type.utype_id && user_type.utype_id = 1 &&
                `consultant_id`='$consultant_id'")->result();
            $data['leadauditors'] = $leadauditors;

            $data['type'] = 4;
            $this->load->view('consultant/close_audit', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function close_auditstring()
    {
        $data['aa1'] = 'active';
        $data['a3']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        $start_date = $this->input->post('start');
        $end_date = $this->input->post('end');
        if ($consultant_id) {
            $data['title'] = "Close Audit";

            $sql = "SELECT a.* ,count(a.pa_id) count
                FROM (
                SELECT
                    audit.*, type.type_of_audit,
                    e.employee_name,
                    f.frequency_name, t.trigger_name,
                    g.*
                FROM
                    audit_log_list g
                LEFT JOIN audit_list audit ON g.audit_id = audit.pa_id
                LEFT JOIN type_of_audit type ON audit.audit_type = type.type_id
                LEFT JOIN employees e ON audit.lead_auditor = e.employee_id
                LEFT JOIN frequency f ON audit.frequency = f.frequency_id
                LEFT JOIN `trigger` t ON audit.`trigger` = t.trigger_id
                WHERE
                    g. STATUS = 1 and g.closed_date >= '".$start_date."' and g.closed_date <= '".$end_date."'
                AND audit.company_id = " . $consultant_id . "
                ORDER BY
                    audit.created_at DESC,
                    pa_id DESC) a
                 GROUP BY a.pa_id";
            $data['close_audits'] = $this->db->query($sql)->result();
            $item = array();
            $return = array();
            foreach ($data['close_audits'] as $key => $audit) {
                $return['employee_name'][] = $audit->employee_name;
                $return['count'][] = $audit->count;
                $item = array('employee_name'=>$audit->employee_name,'count'=>$audit->count);
                $return['real_data'][] = $item;
            }
            echo json_encode($return);
        } else {
            redirect('Welcome');
        }
    }
    public function delete_audit_plan($id = null)
    {
        $data['aa1'] = 'active';
        $data['a2']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        if ($consultant_id) {

            $sql = "DELETE FROM audit_log_list
                        WHERE log_id = '$id'";
            $this->db->query($sql);
            $this->open_audit();
        } else {
            redirect('Welcome');
        }
    }
    public function edit_audit_plan($id = null)
    {
        $data['aa1'] = 'active';
        $data['a2']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $data['title'] = "Edit Audit Plan";

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
                            sp.audit_id = " . $id . " AND sp.status != 0
                        ORDER BY process.process_id DESC ";
            $data['process'] = $this->db->query($sql)->result();
            $data['audit_id'] = $id;
            $this->load->view('consultant/edit_audit_plan', $data);
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
            $this->load->view('consultant/view_audit_plan', $data);
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

            $this->db->where('audit_id',$id);
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
            redirect('Consultant/open_audit');
        } else {
            redirect('Welcome');
        }
    }
    public function close_audit_plan($id)
    {
        $data['aa1'] = 'active';
        $data['a2']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        $up = array(
            'status' => '1',
            'closed_date' => date('Y-m-d')
        );
        if ($consultant_id) {
            $this->db->where('log_id', $id);
            $done = $this->db->update('audit_log_list', $up);
            if ($done) {
                $this->session->set_flashdata('message', 'close_success');
                redirect('Consultant/open_audit');
            } else {
                redirect('Consultant/open_audit');
            }
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
        $consultant_id  = $this->session->userdata('consultant_id');
        if ($consultant_id) {
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
            $this->db->select("*,checklist.id as checklist_id");
            $data['process_id'] = $id;
            $this->load->view('consultant/edit_checklist_process', $data);
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
        $consultant_id  = $this->session->userdata('consultant_id');
        if ($consultant_id) {
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
            $this->load->view('consultant/edit_checklist_mind', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function view_checklist_mind($id = null)
    {
        $data['aa1'] = 'active';
        $data['a3']  = 'act1';
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
            $this->load->view('consultant/view_checklist_mind', $data);
        } else {
            redirect('Welcome');
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
            $this->load->view('consultant/edit_checklist_mind', $data);
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
            $this->load->view('consultant/view_checklist_mind', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function view_checklist_process($id = null)
    {
        $data['aa1'] = 'active';
        $data['a3']  = 'act1';
        $consultant_id  = $this->session->userdata('consultant_id');
        if ($consultant_id) {
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
            $this->load->view('consultant/view_checklist_process', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function create_checklist()
    {
        $data['aa1'] = 'active';
        $data['a2']  = 'act1';
        $process_id = $this->input->post('process_id');
        $clause_id = $this->input->post('clause_id');
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
                $data['checklist_id'] = '0';
                $this->db->where('consultant_id',$consultant_id);
                $data['audit_criteria'] = $this->db->get('audit_criteria')->result();
                $this->load->view('consultant/create_checklist', $data);

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
                redirect('consultant/edit_audit_plan/'.$audit[0]->audit_id);
            }else{
                redirect('consultant/open_audit');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function edit_checklist()
    {
        $data['aa1'] = 'active';
        $data['a2']  = 'act1';
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
                $this->db->where('id', $checklist_id);
                $checklist = $this->db->get('checklist')->result();
                $data['checklist_id'] = $checklist_id;
                $data['checklist'] = $checklist;
                $this->load->view('consultant/edit_checklist', $data);

            }else{
                redirect('Welcome');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function add_criteria()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'criteria_name' => $name,
            'consultant_id' => $consultant_id
        );
        $done       = $this->db->insert('audit_criteria', $data);
        if ($done) {
            $this->db->where('consultant_id', $consultant_id);
            $audit_criteria_list = $this->db->get('audit_criteria')->result();
            echo '<option value="N/A">N/A</option>';
            echo '<option value="TBD">TBD</option>';
            foreach ($audit_criteria_list as $audit_criteria_lists) {
                echo "<option value='" . $audit_criteria_lists->criteria_name . "'>" . $audit_criteria_lists->criteria_name . "</option>";
            }
        } else {
        }
    }
    public function add_criteria2()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'criteria_name2' => $name,
            'consultant_id' => $consultant_id
        );
        $done       = $this->db->insert('audit_criteria2', $data);
        if ($done) {
            $this->db->where('consultant_id', $consultant_id);
            $audit_criteria_list = $this->db->get('audit_criteria2')->result();
            echo '<option value="N/A">N/A</option>';
            echo '<option value="TBD">TBD</option>';
            foreach ($audit_criteria_list as $audit_criteria_lists) {
                echo "<option value='" . $audit_criteria_lists->criteria_name2 . "'>" . $audit_criteria_lists->criteria_name2 . "</option>";
            }
        }
    }
    public function add_criteria3()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'criteria_name3' => $name,
            'consultant_id' => $consultant_id
        );
        $done       = $this->db->insert('audit_criteria3', $data);
        if ($done) {
            $this->db->where('consultant_id', $consultant_id);
            $audit_criteria_list = $this->db->get('audit_criteria3')->result();
            echo '<option value="N/A">N/A</option>';
            echo '<option value="TBD">TBD</option>';
            foreach ($audit_criteria_list as $audit_criteria_lists) {
                echo "<option value='" . $audit_criteria_lists->criteria_name3 . "'>" . $audit_criteria_lists->criteria_name3 . "</option>";
            }
        }
    }
    public function add_criteria4()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'criteria_name4' => $name,
            'consultant_id' => $consultant_id
        );
        $done       = $this->db->insert('audit_criteria4', $data);
        if ($done) {
            $this->db->where('consultant_id', $consultant_id);
            $audit_criteria_list = $this->db->get('audit_criteria4')->result();
            echo '<option value="N/A">N/A</option>';
            echo '<option value="TBD">TBD</option>';
            foreach ($audit_criteria_list as $audit_criteria_lists) {
                echo "<option value='" . $audit_criteria_lists->criteria_name4 . "'>" . $audit_criteria_lists->criteria_name4 . "</option>";
            }
        }
    }
    public function all_criteria()
    {
        $checklist_id = $this->input->post('name');
        $consultant_id = $this->session->userdata('consultant_id');
        $this->db->where('consultant_id', $consultant_id);
        $audit_criteria_list = $this->db->get('audit_criteria')->result();
        $this->db->where('id', $checklist_id);
        $checklist = $this->db->get('checklist')->result();
        echo '<option value="N/A">N/A</option>';
        if (count($checklist) > 0 && $checklist[0]->criteria_id == 'TBD') {
            echo '<option value="TBD" selected>TBD</option>';
        } else {
            echo '<option value="TBD">TBD</option>';
        }
        foreach ($audit_criteria_list as $audit_criteria_lists) {
            if (count($checklist) > 0 && $checklist[0]->criteria_id == $audit_criteria_lists->criteria_name){
                echo "<option value='" . $audit_criteria_lists->criteria_name . "' selected>" . $audit_criteria_lists->criteria_name . "</option>";
            }else{
                echo "<option value='" . $audit_criteria_lists->criteria_name . "'>" . $audit_criteria_lists->criteria_name . "</option>";
            }
        }
    }
    public function all_criteria2()
    {
        $checklist_id = $this->input->post('name');
        $consultant_id = $this->session->userdata('consultant_id');
        $this->db->where('consultant_id', $consultant_id);
        $audit_criteria_list = $this->db->get('audit_criteria2')->result();
        $this->db->where('id', $checklist_id);
        $checklist = $this->db->get('checklist')->result();
        echo '<option value="N/A">N/A</option>';
        if (count($checklist) > 0 && $checklist[0]->criteria_id2 == 'TBD') {
            echo '<option value="TBD" selected>TBD</option>';
        } else {
            echo '<option value="TBD">TBD</option>';
        }
        foreach ($audit_criteria_list as $audit_criteria_lists) {
            if (count($checklist) > 0 && $checklist[0]->criteria_id2 == $audit_criteria_lists->criteria_name2){
                echo "<option value='" . $audit_criteria_lists->criteria_name2 . "' selected>" . $audit_criteria_lists->criteria_name2 . "</option>";
            }else{
                echo "<option value='" . $audit_criteria_lists->criteria_name2 . "'>" . $audit_criteria_lists->criteria_name2 . "</option>";
            }
        }
    }
    public function all_criteria3()
    {
        $checklist_id = $this->input->post('name');
        $consultant_id = $this->session->userdata('consultant_id');
        $this->db->where('consultant_id', $consultant_id);
        $audit_criteria_list = $this->db->get('audit_criteria3')->result();
        $this->db->where('id', $checklist_id);
        $checklist = $this->db->get('checklist')->result();
        echo '<option value="N/A">N/A</option>';
        if (count($checklist) > 0 && $checklist[0]->criteria_id3 == 'TBD') {
            echo '<option value="TBD" selected>TBD</option>';
        } else {
            echo '<option value="TBD">TBD</option>';
        }
        foreach ($audit_criteria_list as $audit_criteria_lists) {
            if (count($checklist) > 0 && $checklist[0]->criteria_id3 == $audit_criteria_lists->criteria_name3){
                echo "<option value='" . $audit_criteria_lists->criteria_name3 . "' selected>" . $audit_criteria_lists->criteria_name3 . "</option>";
            }else{
                echo "<option value='" . $audit_criteria_lists->criteria_name3 . "'>" . $audit_criteria_lists->criteria_name3 . "</option>";
            }
        }
    }
    public function all_criteria4()
    {
        $checklist_id = $this->input->post('name');
        $consultant_id = $this->session->userdata('consultant_id');
        $this->db->where('consultant_id', $consultant_id);
        $audit_criteria_list = $this->db->get('audit_criteria4')->result();
        $this->db->where('id', $checklist_id);
        $checklist = $this->db->get('checklist')->result();
        echo '<option value="N/A">N/A</option>';
        if (count($checklist) > 0 && $checklist[0]->criteria_id4 == 'TBD') {
            echo '<option value="TBD" selected>TBD</option>';
        } else {
            echo '<option value="TBD">TBD</option>';
        }
        foreach ($audit_criteria_list as $audit_criteria_lists) {
            if (count($checklist) > 0 && $checklist[0]->criteria_id4 == $audit_criteria_lists->criteria_name4){
                echo "<option value='" . $audit_criteria_lists->criteria_name4 . "' selected>" . $audit_criteria_lists->criteria_name4 . "</option>";
            }else{
                echo "<option value='" . $audit_criteria_lists->criteria_name4 . "'>" . $audit_criteria_lists->criteria_name4 . "</option>";
            }
        }
    }
    public function all_criteria_table()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $this->db->where('consultant_id', $consultant_id);
        $audit_criteria_list = $this->db->get('audit_criteria')->result();
        foreach ($audit_criteria_list as $audit_criteria_lists) {
            echo "<tr><td>" . $audit_criteria_lists->criteria_name . "</td><td><a onclick='deletecriteria(" . $audit_criteria_lists->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function all_criteria_table2()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $this->db->where('consultant_id', $consultant_id);
        $audit_criteria_list = $this->db->get('audit_criteria2')->result();
        foreach ($audit_criteria_list as $audit_criteria_lists) {
            echo "<tr><td>" . $audit_criteria_lists->criteria_name2 . "</td><td><a onclick='deletecriteria2(" . $audit_criteria_lists->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function all_criteria_table3()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $this->db->where('consultant_id', $consultant_id);
        $audit_criteria_list = $this->db->get('audit_criteria3')->result();
        foreach ($audit_criteria_list as $audit_criteria_lists) {
            echo "<tr><td>" . $audit_criteria_lists->criteria_name3 . "</td><td><a onclick='deletecriteria3(" . $audit_criteria_lists->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function all_criteria_table4()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $this->db->where('consultant_id', $consultant_id);
        $audit_criteria_list = $this->db->get('audit_criteria4')->result();
        foreach ($audit_criteria_list as $audit_criteria_lists) {
            echo "<tr><td>" . $audit_criteria_lists->criteria_name4 . "</td><td><a onclick='deletecriteria4(" . $audit_criteria_lists->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_criteria()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('audit_criteria');
    }
    public function delete_criteria2()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('audit_criteria2');
    }
    public function delete_criteria3()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('audit_criteria3');
    }
    public function delete_criteria4()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('audit_criteria4');
    }
    public function save_checklist(){
        $consultant_id = $this->session->userdata('consultant_id');
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
            'evidence' => $evidence,
            'note' => $notes,
            'status' => $status,
            'effectiveness' => $effectiveness
        );
        $done = $this->db->insert('checklist', $data);
        if ($clause_id < 0){
            redirect('Consultant/edit_checklist_process/'.$process_id);
        }else{
            $data['aa1'] = 'active';
            $data['a2']  = 'act1';
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
            $this->load->view('consultant/edit_checklist_mind', $data);
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
            'evidence' => $evidence,
            'note' => $notes,
            'status' => $status,
            'effectiveness' => $effectiveness
        );
        $this->db->where('id', $checklist_id);
        $done = $this->db->update('checklist', $data);
        if ($clause_id < 0){
            redirect('Consultant/edit_checklist_process/'.$process_id);
        }else{
            redirect('Consultant/edit_checklist_mind/'.$process_id);
        }
    }
    public function corrective_action_form($id = '')
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $data['bb1'] = 'active';
        $data['b1']  = 'act1';
        if ($consultant_id) {
            $data['title'] = "CORRECTIVE ACTIONS FORM";
            if ($id != '') {
                $data['checklist_id'] = $id;
                $sql = "SELECT
                    a.criteria_id,
                    a.note,
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
                $data['clause_list'] = json_encode($this->db->get('clause')->result());
                $this->load->view('consultant/corrective_action_form_view', $data);
            } else {
                $this->db->where('company_id', $consultant_id);
                $data['audit_type'] = $this->db->get('type_of_audit')->result();
//                $this->db->where('company_id', $consultant_id);
//                $data['process'] = $this->db->get('process_list')->result();
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

                $this->load->view('consultant/corrective_action_form', $data);

            }
        } else {
            redirect('Welcome');
        }
    }
    public function add_grade_nonconform()
    {
        $company_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'name' => $name,
            'company_id' => $company_id
        );
        $done       = $this->db->insert('grade_nonconform', $data);
        if ($done) {
            $this->db->where('company_id', $company_id);
            $grade_nonconform = $this->db->get('grade_nonconform')->result();
            echo '<option value="Major">Major</option>';
            echo '<option value="Minor">Minor</option>';
            echo '<option value="Critical">Critical</option>';
            foreach ($grade_nonconform as $grade_nonconforms) {
                echo "<option value='" . $grade_nonconforms->name . "'>" . $grade_nonconforms->name . "</option>";
            }
        } else {
        }
    }
    public function create_grade()
    {
        $grade       = $this->input->post('grade');
        $id       = $this->input->post('id');
        $data       = array(
            'grade_nonconform' => $grade
        );
        $this->db->where('id', $id);
        $done       = $this->db->update('checklist', $data);
    }
    public function all_grade_nonconform()
    {
        $checklist_id = $this->input->post('name');
        $company_id = $this->session->userdata('consultant_id');
        echo '<option value="NA" selected>Select Grade of Non-Conformity</option>';
        $this->db->where('id', $checklist_id);
        $checklist = $this->db->get('checklist')->result();
        if ($checklist_id != '0'){
            if ($checklist[0]->grade_nonconform == 'Major'){
                echo '<option value="Major">Major</option>';
                echo '<option value="Minor">Minor</option>';
                echo '<option value="Critical">Critical</option>';
            }else if ($checklist[0]->grade_nonconform == 'Minor'){
                echo '<option value="Major">Major</option>';
                echo '<option value="Minor">Minor</option>';
                echo '<option value="Critical">Critical</option>';
            }else if ($checklist[0]->grade_nonconform == 'Critical'){
                echo '<option value="Major">Major</option>';
                echo '<option value="Minor">Minor</option>';
                echo '<option value="Critical">Critical</option>';
            }else{
                echo '<option value="Major">Major</option>';
                echo '<option value="Minor">Minor</option>';
                echo '<option value="Critical">Critical</option>';
            }
        }
        $this->db->where('company_id', $company_id);
        $grade_nonconform = $this->db->get('grade_nonconform')->result();
        foreach ($grade_nonconform as $grade_nonconforms) {
            echo "<option value='" . $grade_nonconforms->name . "'>" . $grade_nonconforms->name . "</option>";
        }
    }
    public function all_grade_nonconform_table()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $grade_nonconform = $this->db->get('grade_nonconform')->result();
        echo "<tr><td>Major</td><td></td><tr>";
        echo "<tr><td>Minor</td><td></td><tr>";
        echo "<tr><td>Critical</td><td></td><tr>";
        foreach ($grade_nonconform as $grade_nonconforms) {
            echo "<tr><td>" . $grade_nonconforms->name . "</td><td><a onclick='deletegrade_nonconforms(" . $grade_nonconforms->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_grade_nonconform()
    {
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('grade_nonconform');
    }
    public function add_customer_requirment()
    {
        $company_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'name' => $name,
            'company_id' => $company_id
        );
        $done       = $this->db->insert('customer_requirment', $data);
        if ($done) {
            $this->db->where('company_id', $company_id);
            $regulatory_requirement = $this->db->get('customer_requirment')->result();
            echo '<option value="Not Applicable">Not Applicable</option>';
            foreach ($regulatory_requirement as $regulatory_requirements) {
                echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
            }
        } else {
        }
    }
    public function all_customer_requirment()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('customer_requirment')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_customer_requirment_table()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('customer_requirment')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deletecustomer_requirment(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_customer_requirment()
    {
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('customer_requirment');
    }
    public function add_product()
    {
        $company_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'name' => $name,
            'company_id' => $company_id
        );
        $done       = $this->db->insert('product', $data);
        if ($done) {
            $this->db->where('company_id', $company_id);
            $regulatory_requirement = $this->db->get('product')->result();
            echo '<option value="Not Applicable">Not Applicable</option>';
            foreach ($regulatory_requirement as $regulatory_requirements) {
                echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
            }
        } else {
        }
    }
    public function all_product()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('product')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_product_table()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('product')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deleteproduct(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_product()
    {
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('product');
    }
    public function add_standard()
    {
        $company_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'name' => $name,
            'company_id' => $company_id
        );
        $done       = $this->db->insert('standard', $data);
        if ($done) {
            $this->db->where('company_id', $company_id);
            $regulatory_requirement = $this->db->get('standard')->result();
            echo '<option value="Not Applicable">Not Applicable</option>';
            foreach ($regulatory_requirement as $regulatory_requirements) {
                echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
            }
        } else {
        }
    }
    public function all_standard()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('standard')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_standard_table()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('standard')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deletestandard(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_standard()
    {
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('standard');
    }
    public function add_process_step()
    {
        $company_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'name' => $name,
            'company_id' => $company_id
        );
        $done       = $this->db->insert('process_step', $data);
        if ($done) {
            $this->db->where('company_id', $company_id);
            $regulatory_requirement = $this->db->get('process_step')->result();
            echo '<option value="Not Applicable">Not Applicable</option>';
            foreach ($regulatory_requirement as $regulatory_requirements) {
                echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
            }
        } else {
        }
    }
    public function all_process_step()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('process_step')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_process_step_table()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('process_step')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deletestandard(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_process_step()
    {
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('process_step');
    }
    public function add_clause()
    {
        $company_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $manage_id       = $this->input->post('manage_id');
        $this->db->where('company_id', $company_id);
        $this->db->where('id', $manage_id);
        $parent = $this->db->get('clause')->row();
        if (empty($parent)){
            $data       = array(
                'name' => $name,
                'company_id' => $company_id,
                'parent_id' => '0',
                'symbol' => ''
            );
            $done = $this->db->insert('clause', $data);
        }else{
            $data       = array(
                'name' => $name,
                'company_id' => $company_id,
                'parent_id' => $manage_id,
                'symbol' => $parent->symbol."".$parent->id."."
            );
            $done = $this->db->insert('clause', $data);
        }
        if ($done) {
            $this->db->where('company_id',$company_id);
            echo json_encode($this->db->get('clause')->result());
        } else {
        }
    }
    public function all_clause()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('clause')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_clause_table()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('clause')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deleteclause(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function edit_clause()
    {
        $company_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $manage_id       = $this->input->post('manage_id');
        $this->db->where('company_id', $company_id);
        $this->db->where('id', $manage_id);
        $parent = $this->db->get('clause')->row();
        if (!empty($parent)){
            $data       = array(
                'name' => $name
            );
            $this->db->where('id',$manage_id);
            $done = $this->db->update('clause', $data);
        }else{
        }
        if ($done) {
            $this->db->where('company_id',$company_id);
            echo json_encode($this->db->get('clause')->result());
        } else {
        }
    }
    public function delete_clause()
    {
        $id         = $this->input->post('manage_id');
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('id', $id);
        $parent = $this->db->get('clause')->row();
        if (!empty($parent)){
            $this->db->where('symbol like "'.$parent->symbol.''.$id.'.%"');
            $this->db->delete('clause');
        }else{
        }
        $this->db->where('id', $id);
        $done = $this->db->delete('clause');
        if ($done) {
            $this->db->where('company_id',$company_id);
            echo json_encode($this->db->get('clause')->result());
        } else {
        }
    }
    public function add_regulatory_requirement()
    {
        $company_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'name' => $name,
            'company_id' => $company_id
        );
        $done       = $this->db->insert('regulatory_requirement', $data);
        if ($done) {
            $this->db->where('company_id', $company_id);
            $regulatory_requirement = $this->db->get('regulatory_requirement')->result();
            echo '<option value="Not Applicable">Not Applicable</option>';
            foreach ($regulatory_requirement as $regulatory_requirements) {
                echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
            }
        } else {
        }
    }
    public function all_regulatory_requirement()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('regulatory_requirement')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_regulatory_requirement_table()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('regulatory_requirement')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deleteregulatory_requirement(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_regulatory_requirement()
    {
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('regulatory_requirement');
    }
    public function add_shift()
    {
        $company_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'name' => $name,
            'company_id' => $company_id
        );
        $done       = $this->db->insert('shift', $data);
        if ($done) {
            $this->db->where('company_id', $company_id);
            $regulatory_requirement = $this->db->get('shift')->result();
            echo '<option value="Not Applicable">Not Applicable</option>';
            foreach ($regulatory_requirement as $regulatory_requirements) {
                echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
            }
        } else {
        }
    }
    public function all_shift()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('shift')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_shift_table()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('shift')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deleteshift(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_shift()
    {
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('shift');
    }
    public function add_policy()
    {
        $company_id = $this->session->userdata('consultant_id');
        $name       = $this->input->post('name');
        $data       = array(
            'name' => $name,
            'company_id' => $company_id
        );
        $done       = $this->db->insert('policy', $data);
        if ($done) {
            $this->db->where('company_id', $company_id);
            $regulatory_requirement = $this->db->get('policy')->result();
            echo '<option value="Not Applicable">Not Applicable</option>';
            foreach ($regulatory_requirement as $regulatory_requirements) {
                echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
            }
        } else {
        }
    }
    public function all_policy()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('policy')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_policy_table()
    {
        $company_id = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('policy')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deletepolicy(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_policy()
    {
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('policy');
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
        $trigger_id                 = $this->input->post('trigger_id');
        $grade_nonconform               = $this->input->post('grade_nonconform');
        $occur_date                 = $this->input->post('occur_date');
        $audit_criteria             = $this->input->post('audit_criteria');
        $audit_criteria2             = $this->input->post('audit_criteria2');
        $audit_criteria3             = $this->input->post('audit_criteria3');
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
        $prob_desc                  = $this->input->post('prob_desc');
        $correction                 = $this->input->post('correction');
        $business_impact            = $this->input->post('business_impact');
        $root_cause                 = $this->input->post('root_cause');
        $action_plan                = $this->input->post('action_plan');
        $corrective_action          = $this->input->post('corrective_action');
        $verification_effectiveness = $this->input->post('verification_effectiveness');
        $type                       = $this->input->post('type');
        $by_when_date               = $this->input->post('by_when_date');
        $responsible_party          = $this->input->post('responsible_party');
        $role                       = $this->input->post('role');
        $checklist_id                    = $this->input->post('checklist_id');
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
            'corrective_action' => $corrective_action,
            'verification_effectiveness' => $verification_effectiveness,
            'root_doc' => $root_doc,
            'corrective_plan_doc' => $corrective_plan_doc,
            'corrective_doc' => $corrective_doc,
            'verification_doc' => $verification_doc,
            'clause' => $clause,
            'clause1' => $clause1,
            'clause2' => $clause2,
            'grade_nonconform' => $grade_nonconform
        );
        if ($company_id) {
            $data = array(
                'company_id' => $company_id,
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
                'grade_nonconform' => $grade_nonconform
            );
            if ($checklist_id != '') {
                $this->db->where('checklist_id', $checklist_id);
                $corrective_temp = $this->db->get('corrective_action_data')->result();
                if (count($corrective_temp) > 0){
                    $this->db->where('id', $corrective_temp[0]->id);
                    $this->db->update('corrective_action_data', $data12);
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
                $this->session->set_flashdata('message', 'submit');
                redirect('consultant/car_action_notification/' . $last_id);
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('consultant/car_action_notification/' . $last_id);
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
            $this->load->view('consultant/car_action_notification', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function resolution_list()
    {
        $data['bb1']   = 'active';
        $data['b3']    = 'act1';
        $company_id    = $this->session->userdata('consultant_id');
        $data['title'] = "Corrective Action Resolution Log";
        if ($company_id) {
            $this->db->order_by('by_when_date', 'DESC');
            $this->db->where('company_id', $company_id);
            $this->db->where('process_status!=', 'Close');
            $this->db->where('type != ', 'OFI');
            $data['standalone_data'] = $this->db->get('corrective_action_data')->result();
            $this->load->view('consultant/resolution_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function resolution_list_ofi()
    {
        $data['bb1']   = 'active';
        $data['b6']    = 'act1';
        $company_id    = $this->session->userdata('consultant_id');
        $data['title'] = "Corrective Action Resolution Log";
        if ($company_id) {
            $this->db->order_by('by_when_date', 'DESC');
            $this->db->where('company_id', $company_id);
            $this->db->where('process_status!=', 'Close');
            $this->db->where('type', 'OFI');
            $data['standalone_data'] = $this->db->get('corrective_action_data')->result();
            $this->load->view('consultant/resolution_list_ofi', $data);
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

            $data['corrective_id'] = $id;

            $this->load->view('consultant/resolution', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function update_resolution()
    {
        $company_id                 = $this->session->userdata('consultant_id');
        $trigger_id                 = $this->input->post('trigger_id');
        $customer_requirment        = $this->input->post('customer_requirment');
        $product                    = $this->input->post('product');
        $regulatory_requirement     = $this->input->post('regulatory_requirement');
        $policy                     = $this->input->post('policy');
        $shift                      = $this->input->post('shift');
        $process_step                   = $this->input->post('process_step');
        $standard                   = $this->input->post('standard');
        $standard1                   = $this->input->post('standard1');
        $standard2                   = $this->input->post('standard2');
        $mashine_clause             = $this->input->post('mashine_clause');
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
        $process_status             = $this->input->post('process_status');
        $type                       = $this->input->post('type');
        $closed_date                = date('Y-m-d');
        $corrective_action          = $this->input->post('corrective_action');
        $verification_effectiveness = $this->input->post('verification_effectiveness');
        $verification_question_flag = $this->input->post('verification_question_flag');
        $audit_criteria             = $this->input->post('audit_criteria');
        $verification_flag          = $this->input->post('verification_flag');
        $action_taken               = $this->input->post('action_taken');
        $action_taken               = intval($action_taken) + 1;
        $form_id                    = $this->input->post('form_id');
        $clause                     = $this->input->post('clause');
        $clause1                     = $this->input->post('clause1');
        $clause2                     = $this->input->post('clause2');
        $grade_nonconform                     = $this->input->post('grade_nonconform');

        if (!empty($_FILES['root_doc']['name'])) {
            $config['upload_path'] = 'uploads/Doc/';
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
        if (!empty($_FILES['corrective_plan_doc']['name'])) {
            $config['upload_path'] = 'uploads/Doc/';
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
                if ($verification_question_flag == '2') {
                    $this->session->set_flashdata('message', 'submit');
                    redirect('consultant/car_verification_form/' . $form_id);
                } else {
                    redirect('consultant/resolution_list');
                }
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('consultant/car_verification_form/' . $form_id);
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
            $this->load->view('consultant/car_verification_form', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_root_cause()
    {
        $company_id = $this->session->userdata('consultant_id');
        $user_type = $this->session->userdata('user_type');
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
            $this->db->where('id', $id)->update('corrective_action_data', array('root_cause'=>$conclusion));
            $this->db->where('corrective_action_data_id', $id);
            $root_cause_data = $this->db->get('root_cause')->row();

            if ($root_cause_data){
                $this->db->where('corrective_action_data_id', $id);
                $this->db->update('root_cause', $data);
            }else{
                $this->db->insert('root_cause', $data);
            }
            if($user_type == 'admin')
                redirect('consultant/resolution/' . $id);
            else
                redirect('employee/resolution/' . $id);
        } else {
            redirect('Welcome');
        }
    }

    public function byprocess()
    {
        $data['ee1']             = 'active';
        $data['e1']              = 'act1';
        $company_id              = $this->session->userdata('consultant_id');
        $data['title']           = "BY PROCESS";
        $data['comp_email']      = $this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$company_id'")->row()->email;
        $data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
        $processowner_sel = $this->input->post('processowner_sel');

        $company_start = $this->input->post('company_start');
        $company_end = $this->input->post('company_end');

        if ($company_id) {
            $sql_sub = '';
            if (!empty($company_start) && !empty($company_end)) 
                $sql_sub = " and corrective_action_data.create_at > '".$company_start."'
                        and corrective_action_data.create_at < '".$company_end."'";

            if (!isset($processowner_sel) || $processowner_sel == -1) {
                $process_list   = $this->db->query("SELECT * FROM `process_list` where company_id = '$company_id'")->result();
            } else {
                $this->db->select('process_list.*');
                $this->db->join('select_process','select_process.process_id=process_list.process_id');
                $this->db->join('employees','employees.employee_id=select_process.process_owner');
                $this->db->where('employees.employee_id',$processowner_sel);
                $this->db->where('employees.company_id',$company_id);
                $process_list = $this->db->get('process_list')->result();
            }
            
            for ($i = 0; $i < sizeof($process_list); $i++) {
                $item            = $process_list[$i];

                // noncomformity
                $nonComformity   = $this->db->query("SELECT COUNT(id) as count FROM `checklist` WHERE `process_id`=$item->process_id && `status`='Non-Conformity Table'")->row()->count;
                $item->noncomformity = $nonComformity;
                
                $trigger = $this->db->query("SELECT * FROM `trigger` WHERE `company_id`=$company_id")->result_array();
                if (count($trigger) > 0) {
                    $trigger_id = $trigger[0]['trigger_id'];
                    // process owner
                    $this->db->select('employees.*');
                    $this->db->join('select_process','select_process.process_id=process_list.process_id');
                    $this->db->join('employees','employees.employee_id=select_process.process_owner');
                    $this->db->where('process_list.process_id',$item->process_id);
                    $this->db->where('employees.consultant_id',$company_id);
                    $processowner = $this->db->get('process_list')->row();
                    if (isset($processowner) && $processowner != NULL) {
                        $item->processowner = $processowner->employee_name;
                    } else {
                        $item->processowner = '';
                    }
                    // Corrections
                    $query = "select count(*) count from corrective_action_data  where del_flag=0 and company_id=" . $company_id;
                    $query .= " and process_status='Close'";
                    //$query .= " and (type = 'CORRECTION' or trigger_id=" . $trigger_id . ")";
                    $query .= " and (type = 'OFI' or trigger_id=" . $trigger_id . ")";
                    $query .= " and process=$item->process_id ".$sql_sub;

                    $ofi = $this->db->query($query)->row()->count;
//                    $item->correction = $correction;
                    $item->ofi = $ofi;

                    // Corrective 
                    $query = "select count(*) count from corrective_action_data  where del_flag=0 and company_id=" . $company_id;
                    $query .= " and process_status='Close'";
                    $query .= " and (type = 'CORRECTIVE' or trigger_id=" . $trigger_id . ")";
                    $query .= " and process=$item->process_id ".$sql_sub;
                    $corrective = $this->db->query($query)->row()->count;
                    $item->corrective = $corrective;
                }

                $process_list[$i]    = $item;
            }
            $data['byprocess_data'] = $process_list;

            //process_owner
            $emp_list   = $this->db->query("SELECT employees.* FROM `employees`,permision,user_type WHERE employees.employee_id = permision.employee_id &&
                permision.type_id = user_type.utype_id && user_type.utype_id = 3 &&
                `consultant_id`='$company_id'")->result();
            $data['processowners'] = $emp_list;
            $this->load->view('consultant/byprocess', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function byprocessowner()
    {
        $data['ee1']             = 'active';
        $data['e2']              = 'act1';
        $company_id              = $this->session->userdata('consultant_id');
        $data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
        $data['comp_email']      = $this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$company_id'")->row()->email;
        $data['title']           = "BY PROCESS OWNER";
        $processowner_sel = $this->input->post('processowner_sel');
        $company_start = $this->input->post('company_start');
        $company_end = $this->input->post('company_end');
        if ($company_id) {
            $data['no'] = "owner"; 
             $sql_sub = '';
            if (!empty($company_start) && !empty($company_end)) 
                $sql_sub = " and corrective_action_data.create_at > '".$company_start."'
                        and corrective_action_data.create_at < '".$company_end."'";
            $emp_list = [];
            if (!isset($processowner_sel) || $processowner_sel == -1) {
                $emp_list   = $this->db->query("SELECT * FROM `employees`,permision,user_type WHERE employees.employee_id = permision.employee_id &&
                    permision.type_id = user_type.utype_id && user_type.utype_id = 3 &&
                    `consultant_id`='$company_id'")->result();
            } else {
                $emp_list   = $this->db->query("SELECT * FROM `employees`,permision,user_type WHERE employees.employee_id = permision.employee_id &&
                    permision.type_id = user_type.utype_id && user_type.utype_id = 3 &&
                    `consultant_id`='$company_id' && employees.employee_id='$processowner_sel'")->result();
            }
 
            for ($i = 0; $i < sizeof($emp_list); $i++) {
                $item            = $emp_list[$i];
                // noncomformity
                $nonComformity   = $this->db->query("SELECT COUNT(*) as count FROM `checklist`,`select_process` WHERE 
                    `checklist`.`process_id`=`select_process`.`process_id` && `select_process`.`process_owner`=$item->employee_id &&
                    `checklist`.`status`='Non-Conformity Table'")->row()->count;
                $item->noncomformity = $nonComformity;
               
                $trigger = $this->db->query("SELECT * FROM `trigger` WHERE `company_id`=$company_id")->result_array();
                if (count($trigger) > 0) {
                    // Corrections
                    $trigger_id = $trigger[0]['trigger_id'];
                    $query = "select count(*) count from corrective_action_data where del_flag=0 ";
                    $query .= " and process_status='Close'";
//                    $query .= " and (type = 'CORRECTION' or trigger_id=" . $trigger_id . ")";
                    $query .= " and (type = 'OFI' or trigger_id=" . $trigger_id . ")";
                    $query .= " and process_owner=$item->employee_id ".$sql_sub;
                    $ofi = $this->db->query($query)->row()->count;
//                    $item->correction = $correction;
                    $item->ofi = $ofi;

                    // Corrective 
                    $query = "select count(*) count from corrective_action_data  where del_flag=0 ";
                    $query .= " and process_status='Close'";
                    $query .= " and (type = 'CORRECTIVE' or trigger_id=" . $trigger_id . ")";
                    $query .= " and process_owner=$item->employee_id ".$sql_sub;
                    $corrective = $this->db->query($query)->row()->count;
                    $item->corrective = $corrective;
                }

                $emp_list[$i]    = $item;
            }
            $data['byprocessowner_data'] = $emp_list;
            $this->load->view('consultant/byprocessowner', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function byauditee()
    {
        $data['ee1']             = 'active';
        $data['e3']              = 'act1';
        $company_id              = $this->session->userdata('consultant_id');
        $data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
        $data['comp_email']      = $this->db->query("SELECT * FROM `consultant` WHERE `consultant_id`='$company_id'")->row()->email;
        $data['title']           = "BY AUDITOR";
        $company_start = $this->input->post('company_start');
        $company_end = $this->input->post('company_end');
        if ($company_id) {
            $data['no'] = "owner";             
            $sql_sub = '';
            if (!empty($company_start) && !empty($company_end)) 
                $sql_sub = " and corrective_action_data.create_at > '".$company_start."'
                        and corrective_action_data.create_at < '".$company_end."'";
            $audit_list   = $this->db->query("SELECT * FROM `employees`,permision,user_type WHERE
                employees.employee_id = permision.employee_id and permision.type_id = user_type.utype_id and user_type.utype_id = 4
                and `consultant_id`='$company_id'")->result();

            for ($i = 0; $i < sizeof($audit_list); $i++) {
                $item            = $audit_list[$i];
                // noncomformity
                $nonComformity   = $this->db->query("SELECT COUNT(*) as count FROM `checklist`,`select_process` WHERE 
                    `checklist`.`process_id`=`select_process`.`process_id` && `select_process`.`process_owner`=$item->employee_id &&
                    `checklist`.`status`='Non-Conformity Table'")->row()->count;
                $item->noncomformity = $nonComformity;
                
                $trigger = $this->db->query("SELECT * FROM `trigger` WHERE `company_id`=$company_id")->result_array();
                if (count($trigger) > 0) {
                    // Corrections
                    $trigger_id = $trigger[0]['trigger_id'];
                    $query = "select count(*) count from corrective_action_data where del_flag=0 ";
                    $query .= " and process_status='Close'";
//                    $query .= " and (type = 'CORRECTION' or trigger_id=" . $trigger_id . ")";
                    $query .= " and (type = 'OFI' or trigger_id=" . $trigger_id . ")";
                    $query .= " and auditor_id=$item->employee_id ".$sql_sub;
                    $ofi = $this->db->query($query)->row()->count;
//                    $item->correction = $correction;
                    $item->ofi = $ofi;

                    // Corrective 
                    $query = "select count(*) count from corrective_action_data  where del_flag=0 ";
                    $query .= " and process_status='Close'";
                    $query .= " and (type = 'CORRECTIVE' or trigger_id=" . $trigger_id . ")";
                    $query .= " and auditor_id=$item->employee_id ".$sql_sub;
                    $corrective = $this->db->query($query)->row()->count;
                    $item->corrective = $corrective;
                }

                $audit_list[$i]    = $item;
            }
            $data['byauditee_data'] = $audit_list;
            $this->load->view('consultant/byauditee', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function byprocessstring() {
        $company_id = $this->session->userdata('consultant_id');
        $processownerid = $this->input->post('processownerid');
        $start_date = $this->input->post('start');
        $end_date = $this->input->post('end');
        //$return ='';
        if (isset($company_id)) {
            if (!isset($processownerid) || $processownerid == -1) {
                $process_list   = $this->db->query("SELECT * FROM `process_list`")->result();
            } else {
                $this->db->select('process_list.*');
                $this->db->join('select_process','select_process.process_id=process_list.process_id');
                $this->db->join('employees','employees.employee_id=select_process.process_owner');
                $this->db->where('employees.employee_id',$processownerid);
                $process_list = $this->db->get('process_list')->result();
            }

            for ($i = 0; $i < sizeof($process_list); $i++) {
                $item            = $process_list[$i];

                // noncomformity
                $nonComformity   = $this->db->query("SELECT COUNT(id) as count FROM `checklist` WHERE `process_id`=$item->process_id and `status`='Non-Conformity Table'")->row()->count;
                $return['noncomformity'][] = $nonComformity;
                
                $trigger = $this->db->query("SELECT * FROM `trigger` WHERE `company_id`=$company_id")->result_array();
                if (count($trigger) > 0) {
                    // Corrections
                    $trigger_id = $trigger[0]['trigger_id'];
                    $query = "select count(*) count from corrective_action_data  where del_flag=0 and company_id=" . $company_id;
                    $query .= " and process_status='Close'";
                    $query .= " and (type = 'OFI' or trigger_id=" . $trigger_id . ")";
                    $query .= " and process=$item->process_id ";
                    $query .= " and create_at > '$start_date' and create_at < '$end_date' ";
                    
                    $correction = $this->db->query($query)->row()->count;
                    $return['correction'][] = $correction;

                    // Corrective 
                    $query = "select count(*) count from corrective_action_data  where del_flag=0 and company_id=" . $company_id;
                    $query .= " and process_status='Close'";
                    $query .= " and (type = 'CORRECTIVE' or trigger_id=" . $trigger_id . ")";
                    $query .= " and process=$item->process_id ";
                    $query .= " and create_at > '$start_date' and create_at < '$end_date' ";
                    $corrective = $this->db->query($query)->row()->count;
                    $return['corrective'][] = $corrective;
                }

                $return['process_name'][] = $item->process_name;
                $item = array('process_name'=>$item->process_name,'noncomformity'=>$nonComformity,'correction'=>$correction,
                    'corrective'=>$corrective);
                $return['real_data'][] = $item;
            }
        }
        echo json_encode($return);
    }

    public function byprocessownerstring()
    {
        $company_id = $this->session->userdata('consultant_id');
        $processownerid = $this->input->post('processownerid');
        $start_date = $this->input->post('start');
        $end_date = $this->input->post('end');
        //$return ='';
        if ($company_id) {
            $data['no'] = "owner"; 

            if (!isset($processownerid) || $processownerid == -1) {
                $emp_list   = $this->db->query("SELECT * FROM `employees`,permision,user_type WHERE employees.employee_id = permision.employee_id and
                    permision.type_id = user_type.utype_id and user_type.utype_id = 3 and
                    `consultant_id`='$company_id'")->result();
            } else {
                $emp_list   = $this->db->query("SELECT * FROM `employees`,permision,user_type WHERE employees.employee_id = permision.employee_id and
                    permision.type_id = user_type.utype_id and user_type.utype_id = 3 and
                    `consultant_id`='$company_id' and employees.employee_id='$processownerid'")->result();
            }
            for ($i = 0; $i < sizeof($emp_list); $i++) {
                $item            = $emp_list[$i];
                // noncomformity
                $nonComformity   = $this->db->query("SELECT COUNT(*) as count FROM `checklist`,`select_process` WHERE 
                    `checklist`.`process_id`=`select_process`.`process_id` and `select_process`.`process_owner`=$item->employee_id and
                    `checklist`.`status`='Non-Conformity Table'")->row()->count;
                $return['noncomformity'][] = $nonComformity;

                $trigger = $this->db->query("SELECT * FROM `trigger` WHERE `company_id`=$company_id")->result_array();
                if (count($trigger) > 0) {
                    // Corrections
                    $trigger_id = $trigger[0]['trigger_id'];
                    $query = "select count(*) count from corrective_action_data where del_flag=0 ";
                    $query .= " and process_status='Close'";
                    $query .= " and (type = 'OFI' or trigger_id=" . $trigger_id . ")";
                    $query .= " and process_owner=$item->employee_id ";
                    $query .= " and create_at > '$start_date' and create_at < '$end_date' ";
                    $correction = $this->db->query($query)->row()->count;
                    $return['correction'][] = $correction;

                    // Corrective 
                    $query = "select count(*) count from corrective_action_data  where del_flag=0 ";
                    $query .= " and process_status='Close'";
                    $query .= " and (type = 'CORRECTIVE' or trigger_id=" . $trigger_id . ")";
                    $query .= " and process_owner=$item->employee_id ";
                    $query .= " and create_at > '$start_date' and create_at < '$end_date' ";
                    $corrective = $this->db->query($query)->row()->count;
                    $return['corrective'][] = $corrective;
                }
 
                $return['process_name'][] = $item->employee_name;
                $item = array('process_name'=>$item->employee_name,'noncomformity'=>$nonComformity,'correction'=>$correction,
                    'corrective'=>$corrective);
                $return['real_data'][] = $item;
                $emp_list[$i]    = $item;
            }
        }

        echo json_encode($return);
    }

    public function byauditeestring()
    {
        $company_id = $this->session->userdata('consultant_id');
        $start_date = $this->input->post('start');
        $end_date = $this->input->post('end');
//        $return ='';
        if ($company_id) {
            $data['no'] = "owner";
            $audit_list   = $this->db->query("SELECT * FROM `employees`,permision,user_type WHERE
                employees.employee_id = permision.employee_id and permision.type_id = user_type.utype_id and user_type.utype_id = 4
                and `consultant_id`='$company_id'")->result();

            for ($i = 0; $i < sizeof($audit_list); $i++) {
                $item            = $audit_list[$i];
                // noncomformity
                $nonComformity   = $this->db->query("SELECT COUNT(*) as count FROM `checklist`,`select_process` WHERE 
                    `checklist`.`process_id`=`select_process`.`process_id` and `select_process`.`process_owner`=$item->employee_id and
                    `checklist`.`status`='Non-Conformity Table'")->row()->count;
                $return['noncomformity'][] = $nonComformity;
                
                $trigger = $this->db->query("SELECT * FROM `trigger` WHERE `company_id`=$company_id")->result_array();
                if (count($trigger) > 0) {
                    // Corrections
                    $trigger_id = $trigger[0]['trigger_id'];
                    $query = "select count(*) count from corrective_action_data where del_flag=0 ";
                    $query .= " and process_status='Close'";
                    $query .= " and (type = 'OFI' or trigger_id=" . $trigger_id . ")";
                    $query .= " and auditor_id=$item->employee_id ";
                    $query .= " and create_at > '$start_date' and create_at < '$end_date' ";
                    $correction = $this->db->query($query)->row()->count;
                    $return['correction'][] = $correction;

                    // Corrective 
                    $query = "select count(*) count from corrective_action_data  where del_flag=0 ";
                    $query .= " and process_status='Close'";
                    $query .= " and (type = 'CORRECTIVE' or trigger_id=" . $trigger_id . ")";
                    $query .= " and auditor_id=$item->employee_id ";
                    $query .= " and create_at > '$start_date' and create_at < '$end_date' ";
                    $corrective = $this->db->query($query)->row()->count;
                    $return['corrective'][] = $corrective;
                }

                $audit_list[$i]    = $item;
                $return['process_name'][] = $item->employee_name;
                $item = array('process_name'=>$item->employee_name,'noncomformity'=>$nonComformity,'correction'=>$correction,
                    'corrective'=>$corrective);
                $return['real_data'][] = $item;
            }
        } 
        echo json_encode($return);
    }

    public function statistic($id = 0) {
        $consultant_id = $this->session->userdata('consultant_id');
        $data['type'] = $id; 
        $data['view_string'] = '';
        if ($id == 1)
            $data['title'] = "BY PROCESS";
        else if ($id == 2) 
            $data['title'] = "BY PROCESS OWNER";
        else if ($id == 3)
            $data['title'] = "BY AUDITEE";
        else if ($id == 4)
            $data['title'] = "Close Audit";

        $emp_list   = $this->db->query("SELECT employees.* FROM `employees`,permision,user_type WHERE employees.employee_id = permision.employee_id and
            permision.type_id = user_type.utype_id and user_type.utype_id = 3 and
            `consultant_id`='$consultant_id'")->result();
        $data['processowners'] = $emp_list;
        if ($id == 4)
            $this->load->view('consultant/statistic_closeaudit',$data);
        else
            $this->load->view('consultant/statistic',$data);
    }

    public function resolved_list($type = '')
    {
        $data['bb1'] = 'active';
        if ($type == 'OFI') {
            $data['b4']   = 'act1';
            $data['title'] = "Opportunity for improvement Resolution History";
        } else {
            $data['b5']    = 'act1';
            $data['title'] = "Corrective Action Resolution History";
        }
        $data['type'] = $type;
        $company_id   = $this->session->userdata('consultant_id');
        if ($company_id) {
            $trigger = $this->db->query("SELECT * FROM `trigger` WHERE `company_id`='$company_id' ")->row();
            $trigger_id = 0;
            if (!empty($trigger)) {
                $trigger_id = $trigger->trigger_id;
                $data['no'] = "auditor";
                $this->db->where('type', $type);
                $this->db->where('company_id', $company_id);
                $this->db->where('process_status', 'Close');
                $query = "select * from corrective_action_data  where del_flag=0 and company_id=" . $company_id;
                $query .= " and process_status='Close'";
//                if ($type == 'CORRECTION') {
//                    $query .= " and (type = 'CORRECTION' or trigger_id=" . $trigger_id . ")";
//                } else {
//                    $query .= " and (type = 'CORRECTIVE' and trigger_id !=" . $trigger_id . ")";
//                }
                if($type != "OFI"){
                    $query .= " and ((type = 'CORRECTION' or trigger_id=" . $trigger_id . ") or (type = 'CORRECTIVE' and trigger_id !=" . $trigger_id . "))";
                }
                else
                    $query .= " and type = 'OFI' ";
                $data['standalone_data'] = $this->db->query($query)->result();
                $data['tyep'] = $type;
                $this->load->view('consultant/resolved_list', $data);
            }
            
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
            $this->db->where('id', $id);
            $data['standalone'] = $this->db->get('corrective_action_data')->row();
            $data['id'] = $id;
            $this->load->view('consultant/corrective_action_form_detail', $data);
        } else {
            redirect('Welcome');
        }
    }

    public function confirm_assign() {
        $id = $this->input->post('id');
        $audit_list = @$this->db->query("SELECT * FROM `audit_list` WHERE `lead_auditor`='$id'")->row();
        $confirm = ($audit_list != null) ? FALSE : TRUE;
        $process_list = @$this->db->query("SELECT * FROM `select_process` WHERE `auditor`='$id' OR `process_owner`='$id' OR `sme`='$id'")->row();
        $confirm = $confirm & (($process_list != null) ? FALSE : TRUE);
        $corrective_action = @$this->db->query("SELECT * FROM `corrective_action_data` WHERE `auditor_id`='$id' OR `process_owner`='$id'")->row();
        $confirm = $confirm & (($corrective_action != null) ? FALSE : TRUE);

        echo json_encode($confirm);
    }

    public function get_download_temp_checklist($id = ""){
        $consultant_id = $this->session->userdata('consultant_id');
        $audit_id = $this->db->where('id', $id)->get('select_process')->row()->audit_id;
        if(isset($_GET['img_name']))
            $img_name = $this->input->GET('img_name');
        else
            $img_name = "";


        $consultant_name = $this->db->where('consultant_id', $consultant_id)->get('consultant')->row()->consultant_name;
        $data['consultant_name'] = $consultant_name;

        $this->db->select("*,DATE_FORMAT(audit_log_list.submited_date,'%M %e, %Y') as starttime_type,DATE_FORMAT(audit_log_list.closed_date,'%M %e, %Y') as endtime_type ");
        $this->db->join("type_of_audit","audit_list.audit_type = type_of_audit.type_id","left");
        $this->db->join("employees","audit_list.lead_auditor = employees.employee_id","left");
        $this->db->join("frequency","audit_list.frequency = frequency.frequency_id","left");
        $this->db->join("trigger","audit_list.trigger = trigger.trigger_id","left");
        $this->db->join("audit_log_list","audit_list.pa_id = audit_log_list.audit_id","left");
        $this->db->where('audit_log_list.log_id', $audit_id);
        $this->db->order_by('audit_list.created_at', 'desc');
        $this->db->order_by('audit_list.pa_id', 'desc');
        $data['audits'] = $this->db->get('audit_list')->row();

        $sql = "SELECT
                        sp.*, p.*
                    FROM
                        audit_log_list log
                    LEFT JOIN select_process sp ON log.log_id = sp.audit_id
                    LEFT JOIN process_list p ON sp.process_id = p.process_id
                    WHERE
                        sp.id = $id AND sp.checked = 1";
        $data["process_check_list"] = $this->db->query($sql)->result();
        $data['img_name'] = $img_name;

        $this->load->view('consultant/download_pdf_checklist', $data);
    }

    public function get_download_temp($id = ""){
        $consultant_id = $this->session->userdata('consultant_id');

        $consultant_name = $this->db->where('consultant_id', $consultant_id)->get('consultant')->row()->consultant_name;
        $data['consultant_name'] = $consultant_name;

        $this->db->select("*,DATE_FORMAT(audit_log_list.submited_date,'%M %e, %Y') as starttime_type,DATE_FORMAT(audit_log_list.closed_date,'%M %e, %Y') as endtime_type ");
        $this->db->join("type_of_audit","audit_list.audit_type = type_of_audit.type_id","left");
        $this->db->join("employees","audit_list.lead_auditor = employees.employee_id","left");
        $this->db->join("frequency","audit_list.frequency = frequency.frequency_id","left");
        $this->db->join("trigger","audit_list.trigger = trigger.trigger_id","left");
        $this->db->join("audit_log_list","audit_list.pa_id = audit_log_list.audit_id","left");
        $this->db->where('audit_log_list.log_id', $id);
        $this->db->order_by('audit_list.created_at', 'desc');
        $this->db->order_by('audit_list.pa_id', 'desc');
        $data['audits'] = $this->db->get('audit_list')->row();
        $this->db->where('audit_id', $id);
        $data['audit_brief'] = $this->db->get('audit_brief')->row();
        $this->db->where('employees.consultant_id', $consultant_id);
        $auditors = $this->db->get('employees')->result();
        $sql = "SELECT
                    c.process_name,
                    a.auditor,
                    (SELECT employee_name FROM employees WHERE a.auditor = employees.employee_id) AS auditor_name,
                (SELECT employee_name FROM employees WHERE a.process_owner = employees.employee_id) AS process_owner_name,
                (SELECT employee_name FROM employees WHERE a.sme = employees.employee_id) AS auditee_name,
                DATE_FORMAT(a.start_time,'%M %e, %Y %l:%i %p') as starttime_type,
                DATE_FORMAT(a.end_time,'%M %e, %Y %l:%i %p') as endtime_type
                FROM
                    select_process AS a
                LEFT JOIN process_list AS c ON a.process_id = c.process_id
                WHERE
                    a.audit_id = ".$id." AND a.status != 0
                        ORDER BY starttime_type DESC";

        $data['process_list'] = $this->db->query($sql)->result();
        $this->db->where('audit_id', $id);
        $audit_brief_array = $this->db->get('audit_brief')->row();
        $this->db->select("*,DATE_FORMAT(open_when,'%W, %M %e, %Y') as opentime, DATE_FORMAT(close_when,'%W, %M %e, %Y') as closetime ");
        $this->db->where('audit_id', $id);
        $audit_plan_array = $this->db->get('audit_plan')->row();
        $data['audit_plan'] = $audit_plan_array;
        $audit_team_temp = '';
        $process_owners_temp = '';
        $open_employees_temp = '';
        $close_employees_temp = '';
        $index = 0;
        $index1 = 0;
        $index2 = 0;
        $index3 = 0;
        foreach ($auditors as $auditor) {
            $index++;
            if($audit_brief_array != null) {
                $arrays = explode(",", $audit_brief_array->audit_team);
                for($i = 1; $i < count($arrays); $i++) {
                    if($auditor->employee_id == $arrays[$i]) {
                        $index1++;
                        if ($index1 == count($arrays)-1){
                            $audit_team_temp .= $auditor->employee_name;
                        }else{
                            $audit_team_temp .= $auditor->employee_name.", ";
                        }
                        break;
                    }
                }
                $arrays2 = explode(",", $audit_brief_array->process_owners);
                for($i = 1; $i < count($arrays2); $i++) {
                    if($auditor->employee_id == $arrays2[$i]) {
                        if ($index == count($auditors)){
                            $process_owners_temp .= $auditor->employee_name;
                        }else{
                            $process_owners_temp .= $auditor->employee_name."-".$auditor->role."<br>";
                        }
                        break;
                    }
                }
            }
            if($audit_plan_array != null) {
                $arrays3 = explode(",", $audit_plan_array->open_employees);
                for ($i = 1; $i < count($arrays3); $i++) {
                    if ($auditor->employee_id == $arrays3[$i]) {
                        $index2++;
                        if ($index2 == count($arrays3)-1) {
                            $open_employees_temp .= $auditor->employee_name;
                        } else {
                            $open_employees_temp .= $auditor->employee_name .", ";
                        }
                        break;
                    }
                }
            }
            if($audit_plan_array != null) {
                $arrays4 = explode(",", $audit_plan_array->close_employees);
                for ($i = 1; $i < count($arrays4); $i++) {
                    if ($auditor->employee_id == $arrays4[$i]) {
                        $index3++;
                        if ($index3 == count($arrays4)-1) {
                            $close_employees_temp .= $auditor->employee_name;
                        } else {
                            $close_employees_temp .= $auditor->employee_name .", ";
                        }
                        break;
                    }
                }
            }
        }
        $this->db->where('consultant_id', $consultant_id);
        $data['consultant'] = $this->db->get('consultant')->row();
        $sql = "SELECT
            a.log_id,b.id,d.process_name,c.status,count(*) as cnt
        FROM
            audit_log_list AS a
        LEFT JOIN select_process AS b ON a.log_id = b.audit_id
        LEFT JOIN checklist as c on b.id = c.process_id
        LEFT JOIN process_list AS d ON b.process_id = d.process_id
        where b.checked = 1 and a.log_id = ".$id."
        and c.status = 'Non-Conformity Table'
        group by process_name";
        $data['process_non_list'] = $this->db->query($sql)->result();
        $sql = "SELECT
            a.log_id,b.id,d.process_name,c.status,count(*) as cnt
        FROM
            audit_log_list AS a
        LEFT JOIN select_process AS b ON a.log_id = b.audit_id
        LEFT JOIN checklist as c on b.id = c.process_id
        LEFT JOIN process_list AS d ON b.process_id = d.process_id
        where b.checked = 1 and a.log_id = ".$id."
        and c.status = 'Conformity Table'
        group by process_name";
        $data['process_conf_list'] = $this->db->query($sql)->result();
        $sql = "SELECT
            a.log_id,b.id,d.process_name,c.status,count(*) as cnt
        FROM
            audit_log_list AS a
        LEFT JOIN select_process AS b ON a.log_id = b.audit_id
        LEFT JOIN checklist as c on b.id = c.process_id
        LEFT JOIN process_list AS d ON b.process_id = d.process_id
        where b.checked = 1 and a.log_id = ".$id."
        and c.status = 'Opportunity for Improvement'
        group by process_name";
        $data['process_opp_list'] = $this->db->query($sql)->result();

        $sql = "SELECT
                        sp.*, p.*
                    FROM
                        audit_log_list log
                    LEFT JOIN select_process sp ON log.log_id = sp.audit_id
                    LEFT JOIN process_list p ON sp.process_id = p.process_id
                    WHERE
                        log.log_id = $id AND sp.checked = 1";
        $data["process_check_list"] = $this->db->query($sql)->result();

        $data['audit_team'] = $audit_team_temp;
        $data['process_owners'] = $process_owners_temp;
        $data['open_employees'] = $open_employees_temp;
        $data['close_employees'] = $close_employees_temp;
        $this->load->view('consultant/download_pdf', $data);
    }

    public function save_signature_monitoring()
    {
        if (isset($_REQUEST["id"]) && isset($_REQUEST["sign"]))
        {
            // Need to decode before saving since the data we received is already base64 encoded
            $unencodedData=base64_decode(substr($_REQUEST["sign"], strpos($_REQUEST["sign"], ",")+1));
            $imageName =  time() ."_sign.png";
            if(!file_exists("uploads/sign/" )){
                mkdir("uploads/sign/", 0777, TRUE);
            }
            $filepath = "uploads/sign/" . $imageName;

            $fp = fopen("$filepath", 'wb' );
            fwrite( $fp, $unencodedData);
            fclose( $fp );

            echo $imageName;
        } else {
            echo "fail";
        }
    }

    public function download_pdf_checklist()
    {
        ini_set('max_execution_time', 0);
        $this->load->library("Pdf");
        $content = $this->input->post('download_text');
        $id = $this->input->post('download_id');
        $audit_id = $this->db->where('id', $id)->get('select_process')->row()->audit_id;
        $audit_list_info = $this->db->query("SELECT * FROM audit_list as a LEFT JOIN audit_log_list as b on a.pa_id = b.audit_id WHERE b.log_id='$audit_id'")->row();
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->SetAuthor($contract['parta']);
//        $pdf->SetTitle($contract['contract_title']);

        // set margins
        $pdf->SetMargins(22.5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //$pdf->SetPrintFooter(FALSE);
        $pdf->SetPrintHeader(FALSE);

        $pdf->AddPage();

        $html = <<< EOD
        $content
        EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, TRUE, '', TRUE);
//        $pdf->writeHTML($html, true, false, true, false, '');

        if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/file/')){
            mkdir($_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/file/', 0777, TRUE);
        }
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/file/'.$id.'.pdf', 'F');

        $this->load->library("WatermarkPDF");
        global $fullPathToFile, $fullPathToImage, $footer_text, $footer_align, $header_text, $header_align;
        $fullPathToFile = $_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/file/'.$id.'.pdf';
        if (!empty($audit_list_info->logo_filename) && ($audit_list_info->logo_filename != 'No file selected')) {
            $fullPathToImage = $_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/logo/'.$audit_list_info->logo_filename;
        }
        $pdf->setHeaderData('',0,'',$audit_list_info->header_text,array(0,0,0), array(255,255,255) );
        $header_text = $audit_list_info->header_text;
        switch ($audit_list_info->header_align) {
            case 'left':
                $header_align = "L";
                break;
            case 'center':
                $header_align = "C";
                break;
            case 'right':
                $header_align = "R";
                break;
            case 'hide':
            default:
                $header_align = "C";
                break;
        }
        $footer_text = $audit_list_info->footer_text;
        switch ($audit_list_info->footer_align) {
            case 'left':
                $footer_align = 'L';
                break;
            case 'center':
                $footer_align = 'C';
                break;
            case 'right':
                $footer_align = 'R';
                break;
            case 'hide':
            default:
                $footer_align = 'C';
                break;
        }
        $watermarkPDF = new WatermarkPDF();
        $watermarkPDF->AddPage();

        if($watermarkPDF->numPages>1) {
            for($i=2;$i<=$watermarkPDF->numPages;$i++) {
                $watermarkPDF->_tplIdx = $watermarkPDF->importPage($i);
                $watermarkPDF->AddPage();
            }
        }

        $watermarkPDF->Output('Audit Report_'.$id.'.pdf', 'D');

    }

    public function download_pdf()
    {
        ini_set('max_execution_time', 0);
        $this->load->library("Pdf");
        $html = $this->input->post('download_text');
        $id = $this->input->post('download_id');
        $audit_list_info = $this->db->query("SELECT * FROM audit_list as a LEFT JOIN audit_log_list as b on a.pa_id = b.audit_id WHERE b.log_id='$id'")->row();
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->SetAuthor($contract['parta']);
//        $pdf->SetTitle($contract['contract_title']);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //$pdf->SetPrintFooter(FALSE);
        $pdf->SetPrintHeader(FALSE);

        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, TRUE, '', TRUE);

        if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/file/')){
            mkdir($_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/file/', 0777, TRUE);
        }
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/file/'.$id.'.pdf', 'F');

        $this->load->library("WatermarkPDF");
        global $fullPathToFile, $fullPathToImage, $footer_text, $footer_align, $header_text, $header_align;
        $fullPathToFile = $_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/file/'.$id.'.pdf';
        if (!empty($audit_list_info->logo_filename) && ($audit_list_info->logo_filename != 'No file selected')) {
            $fullPathToImage = $_SERVER['DOCUMENT_ROOT'].'/PAT/uploads/logo/'.$audit_list_info->logo_filename;
        }
        $pdf->setHeaderData('',0,'',$audit_list_info->header_text,array(0,0,0), array(255,255,255) );
        $header_text = $audit_list_info->header_text;
        switch ($audit_list_info->header_align) {
            case 'left':
                $header_align = "L";
                break;
            case 'center':
                $header_align = "C";
                break;
            case 'right':
                $header_align = "R";
                break;
            case 'hide':
            default:
                $header_align = "H";
                break;
        }
        $footer_text = $audit_list_info->footer_text;
        switch ($audit_list_info->footer_align) {
            case 'left':
                $footer_align = 'L';
                break;
            case 'center':
                $footer_align = 'C';
                break;
            case 'right':
                $footer_align = 'R';
                break;
            case 'hide':
            default:
                $footer_align = 'H';
                break;
        }
        $watermarkPDF = new WatermarkPDF();
        $watermarkPDF->AddPage();

        if($watermarkPDF->numPages>1) {
            for($i=2;$i<=$watermarkPDF->numPages;$i++) {
                $watermarkPDF->_tplIdx = $watermarkPDF->importPage($i);
                $watermarkPDF->AddPage();
            }
        }

        $watermarkPDF->Output('Audit Report_'.$id.'.pdf', 'D');

    }

    public function setWatermark()
    {
        $id = $this->input->post('contract_id');
        $header_text = $this->input->post('header_text');
        $header_align = $this->input->post('header_align');
        $footer_text = $this->input->post('footer_text');
        $footer_align = $this->input->post('footer_align');
//        $logo_filename = $this->input->post('logo_filename');
        if ($id) {
            $data = array(
                'header_text' => $header_text,
                'header_align' => $header_align,
                'footer_text' => $footer_text,
                'footer_align' => $footer_align
//                'logo_filename' => $logo_filename
            );
            $this->db->where('log_id', $id);
            $done = $this->db->update('audit_log_list', $data);
            echo json_encode($done);
        }
    }

    public function upload_logo()
    {
        $id = $this->input->post('id');
        if ($id && isset($_FILES['logo'])) {
            if (is_uploaded_file($_FILES['logo']['tmp_name'])
                && in_array(substr($_FILES['logo']['name'], -4), array('.gif','.jpg','.png'))) {
                $logoData = file_get_contents($_FILES['logo']['tmp_name']);
                $logoName = "Audit_" . $id . ".png";

                $this->db->where('log_id', $id);
//                $this->db->update('audit_list', array('logo_filename' => $_FILES['logo']['name']));
                $this->db->update('audit_log_list', array('logo_filename' => $logoName));

                if(!file_exists("./uploads/logo/" )){
                    mkdir("./uploads/logo/", 0777, TRUE);
                }
                $filepath = "./uploads/logo/" . $logoName;

                $fp = fopen("$filepath", 'wb' );
                fwrite( $fp, $logoData);
                fclose( $fp );

                echo json_encode('SUCCESS');
            } else {
                echo json_encode('FAILED');
            }
        } else {
            echo json_encode('FAILED');
        }
    }

    public function get_process_for_type()
    {
        $consultant_id  = $this->session->userdata('consultant_id');
        $this->db->where('company_id', $consultant_id);
        $type_of_audit = $this->input->post('type_of_audit');
        if($type_of_audit == null) {
            $this->db->where('company_id', $consultant_id);
            $type_of_audit = $this->db->get('type_of_audit')->first_row()->type_id;
        }
        $this->db->where('type_of_audit', $type_of_audit);
        $process_list = $this->db->get('process_list')->result();
        foreach ($process_list as $process) {
            echo "<option value='" . $process->process_id . "'>" . $process->process_name . "</option>";
        }
    }
    // public function add_audit_log($pa_id = null) {
    //     $audit_list = $this->db->query("SELECT * FROM `audit_list` WHERE `pa_id`='$pa_id'")->row();
    //     if($pa_id == null || $audit_list == null) {
    //         redirect('Consultant/audits');
    //     } else {
    //         $data = array(
    //             'audit_id' => $pa_id
    //         );
    //         $this->db->insert('audit_log_list', $data);
    //         $log_id = $this->db->insert_id();
    //         // redirect('Consultant/audit_brief/'. $log_id);
    //         redirect('Consultant/audit_brief/'. $plan_id);
    //     }
    // }
    public function get_root_cause(){
        $data = array();
        $id = $this->input->post('id');
        $this->db->where('corrective_action_data_id', $id);
        $root_cause = $this->db->get('root_cause')->row();
        if (!empty($root_cause)){
            echo json_encode($root_cause);
        }
    }

    /****
        
        Function for saving 2FA
        Security Question & Answer.
        
    ****/

    public function update_security_question()
    {
        $consultant_id   = $this->session->userdata('consultant_id');
        //$employee_id  = $this->session->userdata('employee_id');
        $up = array(
                'is2FAEnabled' => 1,
                'security_question' => $this->input->post('question'),
                'security_answer' => $this->input->post('answer')
        );
        if ($consultant_id) {
            $this->db->where('consultant_id', $consultant_id);
            $done = $this->db->update('consultant', $up);
            if ($done) {
                $this->session->set_flashdata('message', 'update_security_success');
                redirect('Consultant/main_info');
            } else {
                redirect('Consultant/main_info');
            }
        } else {
            redirect('Welcome');
        }
    }

    /*************For last login details***********************/
    public function login_history()
    {
        $data['dd4'] = 'active';
        $data['d4'] = 'act1';
        $consultant_id   = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $data['title'] = "Login History";
            $sql = "SELECT * FROM login_history WHERE user_id = " . $consultant_id . " ORDER BY date_time DESC";
            $rowdata1 = @$this->db->query("SELECT COUNT(user_id) as emps FROM `login_history` WHERE `user_id`='$consultant_id' AND status = 2")->row()->emps;
            $data['count_notification'] = $rowdata1;
            $data['login_history'] = $this->db->query($sql)->result();
            $this->load->view('consultant/login_history', $data);
        } else {
            redirect('Welcome');
        }
    }
    /************************End*******************************/
    /*********************update notification***********************************/
   public function update_notification()
    {
        $employee_id   = $this->session->userdata('employee_id');
        $consultant_id = $this->session->userdata('consultant_id');
        //$Notification_id    = $this->input->post('Notification_id');
        $Notification_id = $_POST['Notification_id'];
        $up = array(
            'status' => '3',
        );
        if ($consultant_id) {
            $this->db->where('id', $Notification_id);
            $done = $this->db->update('login_history', $up);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('consultant/login_history');
            } 
        } else {
            redirect('Welcome');
        }
    }

    /************************END********************************/
    /**********************Delete schedule by ID**********************/
 public function delete_schedule_id(){
        $employee_id = $this->session->userdata('employee_id');
        $consultant_id = $this->session->userdata('consultant_id');
        $process_id    = $this->input->post('assign_schedule_id');
        $pro_id        = $_GET['id'];
        if ($consultant_id) {
            $this->db->where('id', $process_id);
            $done = $this->db->delete('select_process');
            if ($done) {
                
                    $this->session->set_flashdata('message', 'success_del');
                    redirect('consultant/audit_schedule/'.$pro_id);
                
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('consultant/audit_schedule/'.$pro_id);
            }
        } else {
            redirect('Welcome');
        }
    }
/*******************************END********************************/
        /*************Edit process ***********************/
    public function edit_single_process()
    {
        $pa_id = $_POST['pa_id'];
        $employee_id = $this->session->userdata('employee_id');
        $consultant_id = $this->session->userdata('consultant_id');
        if ($consultant_id) {
            $sql = "SELECT * FROM process_list WHERE process_id ='$pa_id'";
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
        if ($consultant_id) {
            $this->db->where('process_id', $process_id);
            $done = $this->db->update('process_list', $up);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                //$this->load->view('employee/select_process', $done);
                redirect('Consultant/select_process/'.$pro_id);
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
        if ($consultant_id) {
            $this->db->where('process_id', $process_id);
            $done = $this->db->delete('process_list');
            if ($done) {
                    $this->session->set_flashdata('message', 'success_del');
                    redirect('Consultant/select_process/'.$pro_id);
               
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Consultant/select_process/'.$pro_id);
            }
        } else {
            redirect('Welcome');
        }
    }
/***************************END**********************************/
/************Get all users base on Suspicious login**************/
  public function ViewAllUsers()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $data['dd4'] = 'active';
        $data['d1']  = 'act1';
        if ($consultant_id) {
            $data['title'] = "All User List";
            $last_login =  "SELECT DISTINCT login_history.user_id,employees.employee_name,employees.username,employees.employee_email,employees.role,employees.status FROM login_history
            JOIN employees ON login_history.user_id = employees.employee_id";
            $data['getAllUser_1'] = $this->db->query($last_login)->result();
           //  $data['getAllUser'] = $this->db->query($sql)->result();
            $this->load->view('consultant/ViewAllUsers', $data);
        } else {
            redirect('Welcome');
        }
    }
/***************************************************************/
/************Get users history base on Suspicious login**************/
  public function ViewUsersHistory()
    {
        $consultant_id = $this->session->userdata('consultant_id');
        $data['dd4'] = 'active';
        $data['d1']  = 'act1';
        $user_id = $_GET["user_id"];
        if ($consultant_id) {
            $data['title'] = "View User Login History";
            $last_login =  "SELECT * FROM login_history WHERE user_id = ".$user_id." AND status >= 2";
            $data['GetUserHistory'] = $this->db->query($last_login)->result();
            $this->load->view('consultant/ViewUsersHistory', $data);
        } else {
            redirect('Welcome');
        }
    }
/***************************END************************************/
/*******************disable login for user ************************/
public function update_status($id) {
        $upArr = array('status' => $_GET['is_active']);
        $this->db->where('employee_id', $id);
        $done = $this->db->update('employees', $upArr);

        if($done) {
            $this->session->set_flashdata('message', 'update_success');
            redirect('Consultant/ViewAllUsers');
        }
    }
/***************************END************************************/
}
