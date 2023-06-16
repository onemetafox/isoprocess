
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Company extends CI_Controller
{
    public function employees()
    {
        $company_id  = $this->session->userdata('company_id');
        $com_status  = $this->session->userdata('com_status');
        $data['cc1'] = 'active';
        $data['c2']  = 'act1';
        if ($company_id && $com_status != '0') {
            $data['title'] = "Employee List";
            $plan_id       = @$this->db->query("SELECT * FROM `upgrad_plan` WHERE `company_id`='$company_id' And `status`='1'")->row()->plan_id;
            $rowdata1      = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `company_id`='$company_id'")->row()->emps;
            if ($plan_id) {
                $rowdata               = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
                $data['total_account'] = $rowdata1;
                $data['limit']         = $rowdata;
                $data['reached']       = (($rowdata1 * 100) / $rowdata);
            }
            $data['role'] = $this->db->get('role')->result();
            $this->db->where('company_id', $company_id);
            $data['employees'] = $this->db->get('employees')->result();
            $this->load->view('Company/employees', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function invoice_list()
    {
        $company_id = $this->session->userdata('company_id');
        $com_status = $this->session->userdata('com_status');
        if ($company_id && $com_status != '0') {
            $data['title'] = "Invoice";
            $this->load->view('Company/invoice_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function role_list()
    {
        $data['cc1'] = 'active';
        $data['c3']  = 'act1';
        $company_id  = $this->session->userdata('company_id');
        $com_status  = $this->session->userdata('com_status');
        if ($company_id && $com_status != '0') {
            $data['title'] = "Role Management";
            $data['role']  = $this->db->get('role')->result();
            $this->load->view('Company/role_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_role()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $role_name   = $this->input->post('role_name');
            $description = $this->input->post('description');
            $data        = array(
                'role_name' => $role_name,
                'description' => $description,
                'company_id' => $company_id
            );
            $done        = $this->db->insert('role', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'success');
                redirect('Company/role_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/role_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_role($id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('role_id', $id);
            $done = $this->db->delete('role');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/role_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/role_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function findrole()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $id = $this->input->post('id');
            $this->db->where('role_id', $id);
            $done = $this->db->get('role')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }
    public function edit_role()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $role_id     = $this->input->post('role_id');
            $role_name   = $this->input->post('role_name');
            $description = $this->input->post('description');
            $data        = array(
                'role_name' => $role_name,
                'description' => $description
            );
            $this->db->where('role_id', $role_id);
            $done = $this->db->update('role', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Company/role_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/role_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function add_employee()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $role_id        = $this->input->post('role_id');
            $password       = $this->input->post('password');
            $employee_name  = $this->input->post('employee_name');
            $username       = $this->input->post('username');
            $employee_email = $this->input->post('employee_email');
            $created_at     = date('Y-m-d');
            $data           = array(
                'role_id' => $role_id,
                'company_id' => $company_id,
                'employee_name' => $employee_name,
                'username' => $username,
                'employee_email' => $employee_email,
                'password' => $password,
                'created_at' => $created_at,
                'status' => 1
            );
            $plan_id        = @$this->db->query("SELECT * FROM `upgrad_plan` WHERE `company_id`='$company_id' And `status`='1'")->row()->plan_id;
            if ($plan_id) {
                $rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->no_of_user;
            }
            $rowdata1 = @$this->db->query("SELECT COUNT(employee_id) as emps FROM `employees` WHERE `company_id`='$company_id'")->row()->emps;
            if ($rowdata == $rowdata1 || $rowdata1 > $rowdata) {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/employees');
            } else {
                $done = $this->db->insert('employees', $data);
                if ($done) {
                    $this->session->set_flashdata('message', 'success');
                    redirect('Company/employees');
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Company/employees');
                }
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_employee($id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('employee_id', $id);
            $done = $this->db->delete('employees');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/employees');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/employees');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function finduser()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $id = $this->input->post('id');
            $this->db->where('employee_id', $id);
            $done = $this->db->get('employees')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }
    public function edit_employee()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $role_id        = $this->input->post('role_id');
            $password       = $this->input->post('password');
            $employee_name  = $this->input->post('employee_name');
            $username       = $this->input->post('username');
            $employee_email = $this->input->post('employee_email');
            $employee_id    = $this->input->post('employee_id');
            $data           = array(
                'role_id' => $role_id,
                'company_id' => $company_id,
                'employee_name' => $employee_name,
                'username' => $username,
                'employee_email' => $employee_email,
                'password' => $password
            );
            $this->db->where('employee_id', $employee_id);
            $done = $this->db->update('employees', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Company/employees');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/employees');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function main_info()
    {
        $data['cc1'] = 'active';
        $data['c4']  = 'active';
        $data['c41'] = 'act1';
        $company_id  = $this->session->userdata('company_id');
        $employee_id = $this->session->userdata('employee_id');
        if ($company_id) {
            if ($employee_id) {
                $this->db->where('employee_id', $employee_id);
                $data['profile'] = $this->db->get('employees')->row();
            } else {
                $this->db->where('company_id', $company_id);
                $data['profile'] = $this->db->get('company')->row();
            }
            $data['title'] = "Edit Profile";
            $this->load->view('Company/main_info', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function update_main_info()
    {
        $company_id   = $this->session->userdata('company_id');
        $employee_id  = $this->session->userdata('employee_id');
        $username     = $this->input->post('username');
        $password     = $this->input->post('password');
        $company_name = $this->input->post('company_name');
        $address      = $this->input->post('address');
        $city         = $this->input->post('city');
        $state        = $this->input->post('state');
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
            $picture = @$this->db->query("SELECT * FROM company WHERE company_id='$company_id'")->row()->logo;
        }
        $up = array(
            'state' => $state,
            'company_name' => $company_name,
            'address' => $address,
            'city' => $city,
            'username' => $username,
            'password' => $password,
            'logo' => $picture
        );
        if ($company_id) {
            $this->db->where('company_id', $company_id);
            $done = $this->db->update('company', $up);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Company/main_info');
            } else {
                redirect('Company/main_info');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function payment_list()
    {
        $data['cc1'] = 'active';
        $data['c5']  = 'act1';
        $company_id  = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Invoice";
            $this->db->where('company_id', $company_id);
            $data['payment'] = $this->db->get('upgrad_plan')->result();
            $this->load->view('Company/payment_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function audit_verification_form()
    {
        $company_id1 = $this->session->userdata('company_id');
        if ($company_id1) {
            $data['title'] = "AUDIT ACTIONS FORM";
            $this->db->where('company_id', $company_id1);
            $data['trigger'] = $this->db->get('trigger')->result();
            $this->db->where('company_id', $company_id1);
            $data['cases']     = $this->db->get('cases')->result();
            $data['customers'] = $this->db->get('customers')->result();
            $this->db->where('company_id', $company_id1);
            $data['employees'] = $this->db->get('employees')->result();
            $this->load->view('Company/audit_verification_form', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_audit_verification_form()
    {
        $company_id     = $this->session->userdata('company_id');
        $auditor        = $this->input->post('auditor');
        $spa            = $this->input->post('spa');
        $process_type   = $this->input->post('process_type');
        $system_audited = $this->input->post('system_audited');
        $create_at      = date('Y-m-d');
        $audit_area     = $this->input->post('audit_area');
        $audit_criteria = $this->input->post('audit_criteria');
        $description    = $this->input->post('description');
        if ($company_id) {
            $data     = array(
                'company_id' => $company_id,
                'created_at' => $create_at,
                'auditor' => $auditor,
                'spa' => $spa,
                'process_type' => $process_type,
                'system_audited' => $system_audited
            );
            $done     = $this->db->insert('audit_verification_form', $data);
            $audit_id = $this->db->insert_id();
            if ($done) {
                $cnt = count($audit_area);
                for ($i = 0; $i < $cnt; $i++) {
                    $n                = 'is_verify_' . $i;
                    $m                = 'is_nonconformity_' . $i;
                    $is_verify        = $this->input->post($n);
                    $is_nonconformity = $this->input->post($m);
                    $array            = array(
                        'audit_id' => $audit_id,
                        'audit_area' => $audit_area[$i],
                        'audit_criteria' => $audit_criteria[$i],
                        'is_verify' => $is_verify,
                        'is_nonconformity' => $is_nonconformity,
                        'created_at' => $create_at,
                        'description' => $description[$i]
                    );
                    $success          = $this->db->insert('audit_verification_form_data', $array);
                }
                if ($success) {
                    $this->session->set_flashdata('message', 'save');
                    redirect('Company/audit_verification_form');
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Company/audit_verification_form');
                }
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/audit_verification_form');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function audit_verification_form_list()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Previous Audits";
            $this->db->where('company_id', $company_id);
            $data['audit_verification_form'] = $this->db->get('audit_verification_form')->result();
            $this->load->view('Company/audit_verification_form_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function edit_audit_verification_form($id = null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Audits";
            $this->db->where('company_id', $company_id);
            $data['employees'] = $this->db->get('employees')->result();
            $this->db->where('audit_id', $id);
            $this->db->where('company_id', $company_id);
            $data['audit_form'] = $this->db->get('audit_verification_form')->row();
            $this->db->where('audit_id', $id);
            $data['audit_form_data'] = $this->db->get('audit_verification_form_data')->result();
            $this->load->view('Company/edit_audit_verification_form', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function update_audit_verification_form()
    {
        $company_id     = $this->session->userdata('company_id');
        $updateid       = $this->input->post('updateid');
        $audit_id       = $this->input->post('updateid');
        $auditor        = $this->input->post('auditor');
        $spa            = $this->input->post('spa');
        $process_type   = $this->input->post('process_type');
        $system_audited = $this->input->post('system_audited');
        $create_at      = date('Y-m-d');
        $audit_area     = $this->input->post('audit_area');
        $audit_criteria = $this->input->post('audit_criteria');
        $description    = $this->input->post('description');
        if ($company_id) {
            $data = array(
                'company_id' => $company_id,
                'created_at' => $create_at,
                'auditor' => $auditor,
                'spa' => $spa,
                'process_type' => $process_type,
                'system_audited' => $system_audited
            );
            $this->db->where('audit_id', $audit_id);
            $done = $this->db->update('audit_verification_form', $data);
            $this->db->where('audit_id', $audit_id);
            $this->db->delete('audit_verification_form_data');
            if ($done) {
                $cnt = count($audit_area);
                for ($i = 0; $i < $cnt; $i++) {
                    $n                = 'is_verify_' . $i;
                    $m                = 'is_nonconformity_' . $i;
                    $is_verify        = $this->input->post($n);
                    $is_nonconformity = $this->input->post($m);
                    $array            = array(
                        'audit_id' => $audit_id,
                        'audit_area' => $audit_area[$i],
                        'audit_criteria' => $audit_criteria[$i],
                        'is_verify' => $is_verify,
                        'is_nonconformity' => $is_nonconformity,
                        'created_at' => $create_at,
                        'description' => $description[$i]
                    );
                    $success          = $this->db->insert('audit_verification_form_data', $array);
                }
                if ($success) {
                    $this->session->set_flashdata('message', 'save');
                    redirect('Company/edit_audit_verification_form/' . $audit_id);
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Company/edit_audit_verification_form/' . $audit_id);
                }
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/edit_audit_verification_form/' . $audit_id);
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_audit_verification_form($audit_id = null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('audit_id', $audit_id);
            $done = $this->db->delete('audit_verification_form');
            $this->db->where('audit_id', $audit_id);
            $this->db->delete('audit_verification_form_data');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/audit_verification_form_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/audit_verification_form_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_audit_verification_form_data($audit_id = null, $id = null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('id', $id);
            $done = $this->db->delete('audit_verification_form_data');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/edit_audit_verification_form/' . $audit_id);
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/edit_audit_verification_form/' . $audit_id);
            }
        } else {
            redirect('Welcome');
        }
    }
    public function add_processform()
    {
        $company_id  = $this->session->userdata('company_id');
        $data['cc1'] = 'active';
        $data['c1']  = 'act1';
        if ($company_id) {
            $data['title'] = "ADD PROCESS";
            $this->db->where('company_id', $company_id);
            $data['trigger'] = $this->db->get('trigger')->result();
            $this->db->where('company_id', $company_id);
            $data['cases']     = $this->db->get('cases')->result();
            $data['customers'] = $this->db->get('customers')->result();
            $this->db->where('company_id', $company_id);
            $data['employees'] = $this->db->get('employees')->result();
            $this->load->view('Company/add_processform', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function processlist()
    {
        $data['cc1'] = 'active';
        $data['c1']  = 'act1';
        $company_id  = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Data Table Process-list";
            $plan_id       = @$this->db->query("SELECT * FROM `upgrad_plan` WHERE `company_id`='$company_id' And `status`='1'")->row()->plan_id;
            $rowdata1      = @$this->db->query("SELECT COUNT(id) as tot_pro FROM `process_list` WHERE `company_id`='$company_id'")->row()->tot_pro;
            if ($plan_id) {
                $rowdata               = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->total_process;
                $data['total_account'] = $rowdata1;
                $data['limit']         = $rowdata;
                $data['reached']       = (($rowdata1 * 100) / $rowdata);
            }
            $this->db->where('company_id', $company_id);
            $data['standalone_data'] = $this->db->get('process_list')->result();
            $this->load->view('Company/standaloneform_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function findcust()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $done = $this->db->get('customers')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }
    public function findresponsible()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $id = $this->input->post('id');
            $this->db->where('employee_id', $id);
            $role_id = $this->db->get('employees')->row()->role_id;
            if ($role_id) {
                $this->db->where('role_id', $role_id);
                $role_id = $this->db->get('role')->row();
            }
            echo json_encode($role_id);
        } else {
            redirect('Welcome');
        }
    }
    public function add_stand_formdata()
    {
        $company_id        = $this->session->userdata('company_id');
        $unique_id         = time();
        $auditor_id        = $this->input->post('auditor_id');
        $process_name        = $this->input->post('process_name');
        $frequency         = $this->input->post('frequency');
        $Type_ins          = $this->input->post('Type_ins');
        $resolution_date   = $this->input->post('resolution_date');
        $responsible_party = $this->input->post('responsible_party');
        $create_at         = date('Y-m-d');
        $company_id        = $this->session->userdata('company_id');
        $date              = date('Y-m-d');
        if ($company_id) {
            $data    = array(
                'company_id' => $company_id,
                'create_at' => $create_at,
                'resolution_date' => $resolution_date,
                'responsible_party' => $responsible_party,
                'Type_ins' => $Type_ins,
                'frequency' => $frequency,
                'process_name' => $process_name,
                'auditor_id' => $auditor_id,
                'unique_id' => $unique_id,
                'updated_at' => $date
            );
            $plan_id = @$this->db->query("SELECT * FROM `upgrad_plan` WHERE `company_id`='$company_id' And `status`='1'")->row()->plan_id;
            if ($plan_id) {
                $rowdata = @$this->db->query("SELECT * FROM `plan` WHERE `plan_id`='$plan_id'")->row()->total_process;
            }
            $rowdata1 = @$this->db->query("SELECT COUNT(id) as tot_pro FROM `process_list` WHERE `company_id`='$company_id'")->row()->tot_pro;
            if ($rowdata == $rowdata1 || $rowdata1 > $rowdata) {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/add_processform');
            } else {
                $done = $this->db->insert('process_list', $data);
                if ($done) {
                    $this->session->set_flashdata('message', 'success');
                    redirect('Company/add_processform');
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Company/add_processform');
                }
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_standaloneform($id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('id', $id);
            $done = $this->db->delete('process_list');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/processlist');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/processlist');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function process_assignment($id = '')
    {
        $company_id  = $this->session->userdata('company_id');
        $data['cc1'] = 'active';
        $data['c1']  = 'act1';
        if ($company_id) {
            $data['title'] = " Process Assignment";
            $this->db->where('company_id', $company_id);
            $data['trigger'] = $this->db->get('trigger')->result();
            $this->db->where('company_id', $company_id);
            $data['cases']     = $this->db->get('cases')->result();
            $data['customers'] = $this->db->get('customers')->result();
            $this->db->order_by('process_id', 'DESC');
            $this->db->where('process_id', $id);
            $data['standalone_data'] = $this->db->get('process_items')->result();
            $data['process_id']      = $id;
            $this->db->where('company_id', $company_id);
            $data['employees'] = $this->db->get('employees')->result();
            $this->db->where('company_id', $company_id);
            $data['process_list'] = $this->db->get('process_list')->result();
            $this->load->view('Company/process_assignment', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_process_assignment()
    {
        $company_id     = $this->session->userdata('company_id');
        $audit_area     = $this->input->post('audit_area');
        $audit_criteria = $this->input->post('audit_criteria');
        $create_at      = date('Y-m-d');
        $process_id     = $this->input->post('process_id');
        if ($process_id != '') {
            $this->db->where('id', $process_id);
            $responsible_party = $this->db->get('process_list')->row()->responsible_party;
        } else {
            $responsible_party = '0';
        }
        if ($company_id) {
            $data = array(
                'audit_area' => $audit_area,
                'audit_criteria' => $audit_criteria,
                'responsible_party' => $responsible_party,
                'company_id' => $company_id,
                'process_id' => $process_id,
                'create_date' => $create_at
            );
            $done = $this->db->insert('process_items', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'save');
                redirect('Company/process_assignment/' . $process_id);
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/process_assignment/' . $process_id);
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_process_assignment($process_id = '', $id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('id', $id);
            $done = $this->db->delete('process_items');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/process_assignment/' . $process_id);
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/process_assignment/' . $process_id);
            }
        } else {
            redirect('Welcome');
        }
    }
    public function audit($id = null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Audits";
            $this->db->where('company_id', $company_id);
            $data['employees'] = $this->db->get('employees')->result();
            $this->db->where('process_id', $id);
            $data['process_items'] = $this->db->get('process_items')->result();
            $this->db->where('id', $id);
            $data['process_list'] = $this->db->get('process_list')->row();
            $this->load->view('Company/audit', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function update_process_assignment()
    {
        $data['cc1']       = 'active';
        $data['c6']        = 'act1';
        $company_id        = $this->session->userdata('company_id');
        $updateid          = $this->input->post('updateid');
        $process_id        = $this->input->post('updateid');
        $process_type      = $this->input->post('process_type');
        $system_audited    = $this->input->post('system_audited');
        $create_at         = date('Y-m-d');
        $audit_area        = $this->input->post('audit_area');
        $audit_criteria    = $this->input->post('audit_criteria');
        $description       = $this->input->post('description');
        $responsible_party = $this->input->post('auditor');
        if ($company_id) {
            $this->db->where('process_id', $process_id);
            $this->db->where('flag', 1);
            $this->db->delete('verification_log_process_items');
            $process = array(
                'system_audited' => $system_audited,
                'updated_at' => $create_at
            );
            $this->db->where('id', $process_id);
            $done = $this->db->update('process_list', $process);
            if ($done) {
                $cnt = count($audit_area);
                for ($i = 0; $i < $cnt; $i++) {
                    $n                = 'is_verify_' . $i;
                    $m                = 'is_nonconformity_' . $i;
                    $description      = 'description' . $i;
                    $is_verify        = $this->input->post($n);
                    $is_nonconformity = $this->input->post($m);
                    $description_text = $this->input->post($description);
                    $array            = array(
                        'process_id' => $process_id,
                        'audit_area' => $audit_area[$i],
                        'audit_criteria' => $audit_criteria[$i],
                        'is_verify' => $is_verify,
                        'is_nonconformity' => $is_nonconformity,
                        'responsible_party' => $responsible_party,
                        'company_id' => $company_id,
                        'updated_at' => $create_at,
                        'description' => $description_text,
                        'flag' => '1'
                    );
                    $success          = $this->db->insert('verification_log_process_items', $array);
                }
                if ($success) {
                    $this->session->set_flashdata('message', 'save');
                    redirect('Company/send_assignment_mail/' . $process_id);
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Company/send_assignment_mail/' . $process_id);
                }
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/send_assignment_mail/' . $process_id);
            }
        } else {
            redirect('Welcome');
        }
    }
    public function performance()
    {
        $data['aa1']             = 'active';
        $data['a1']              = 'act1';
        $company_id              = $this->session->userdata('company_id');
        $data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
        $data['comp_email']      = $this->db->query("SELECT * FROM `company` WHERE `company_id`='$company_id'")->row()->email;
        $data['employees_email'] = $this->db->query("SELECT * FROM `employees` WHERE `company_id`='$company_id' &&  `employee_email`!=''")->result();
        if ($company_id) {
            $data['title'] = "VERIFICATIONS/AUDITS";
            $this->db->where('company_id', $company_id);
            $list = $this->db->get('process_list')->result();
            for ($i = 0; $i < sizeof($list); $i++) {
                $item      = $list[$i];
                $last_date = $item->updated_at;
                $freq_day  = 0;
                if ($item->frequency == 'Weekly') {
                    $freq_day = 7;
                }
                if ($item->frequency == 'Annual') {
                    $freq_day = 365;
                }
                if ($item->frequency == 'Monthly') {
                    $freq_day = 31;
                }
                if ($item->frequency == 'Triannual') {
                    $freq_day = 122;
                }
                if ($item->frequency == 'Bi-Annual') {
                    $freq_day = 182;
                }
                if ($item->frequency == 'Bi-Monthly') {
                    $freq_day = 15;
                }
                if ($item->frequency == 'Quarterly') {
                    $freq_day = 91;
                }
                $today           = date('Y-m-d', time());
                $next_audit_date = date('Y-m-d', strtotime($last_date . ' + ' . ($freq_day - 7) . ' days'));
                if ($next_audit_date < $today) {
                    $item->alert_flag = 1;
                }
                $list[$i] = $item;
            }
            $data['standalone_data'] = $list;
            $this->load->view('Company/performance', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function report()
    {
        $data['aa1']             = 'active';
        $data['a2']              = 'act1';
        $company_id              = $this->session->userdata('company_id');
        $data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
        $data['comp_email']      = $this->db->query("SELECT * FROM `company` WHERE `company_id`='$company_id'")->row()->email;
        $data['employees_email'] = $this->db->query("SELECT * FROM `employees` WHERE `company_id`='$company_id' &&  `employee_email`!=''")->result();
        if ($company_id) {
            $data['title'] = "VERIFICATIONS/AUDITS REPORT";
            $this->db->where('company_id', $company_id);
            $data['standalone_data'] = $this->db->get('process_list')->result();
            $this->load->view('Company/report', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function verification_log()
    {
        $data['aa1']             = 'active';
        $data['a3']              = 'act1';
        $company_id              = $this->session->userdata('company_id');
        $data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
        $data['comp_email']      = $this->db->query("SELECT * FROM `company` WHERE `company_id`='$company_id'")->row()->email;
        $data['employees_email'] = $this->db->query("SELECT * FROM `employees` WHERE `company_id`='$company_id' &&  `employee_email`!=''")->result();
        if ($company_id) {
            $data['title'] = "Verification Log";
            $this->db->order_by('process_id', 'desc');
            $this->db->where('company_id', $company_id);
            $this->db->where('flag', '0');
            $data['standalone_data'] = $this->db->get('verification_log_process_items')->result();
            $this->load->view('Company/verification_log', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function corrective_action_form($id = '')
    {
        $company_id  = $this->session->userdata('company_id');
        $data['bb1'] = 'active';
        $data['b1']  = 'act1';
        if ($company_id) {
            $data['title'] = "CORRECTIVE ACTIONS FORM";
            $this->db->where('company_id', $company_id);
            $data['trigger'] = $this->db->get('trigger')->result();
            $this->db->where('company_id', $company_id);
            $data['cases']     = $this->db->get('cases')->result();
            $data['customers'] = $this->db->get('customers')->result();
            $this->db->where('company_id', $company_id);
            $data['employees'] = $this->db->get('employees')->result();
            if ($id != '') {
                $this->db->where('id', $id);
                $data['process_items'] = $this->db->get('verification_log_process_items')->row();
                $this->load->view('Company/corrective_action_form_two', $data);
            } else {
                $this->load->view('Company/corrective_action_form', $data);
            }
        } else {
            redirect('Welcome');
        }
    }
    public function add_corrective_action_data()
    {
        $company_id                 = $this->session->userdata('company_id');
        $form_status                = $this->input->post('submit');
        $area                       = $this->input->post('area');
        $process                    = $this->input->post('process');
        $comment                    = $this->input->post('comment');
        $process_id                 = $this->input->post('process_id');
        $item_id                    = $this->input->post('item_id');
        $unique_id                  = time();
        $auditor_id                 = $this->input->post('auditor_id');
        $trigger_id                 = $this->input->post('trigger_id');
        $occur_date                 = $this->input->post('occur_date');
        $audit_criteria             = $this->input->post('audit_criteria');
        $customer_requirment        = $this->input->post('customer_requirment');
        $product                    = $this->input->post('product');
        $standard                   = $this->input->post('standard');
        $regulatory_requirement     = $this->input->post('regulatory_requirement');
        $shift                      = $this->input->post('shift');
        $policy                     = $this->input->post('policy');
        $mashine_clause             = $this->input->post('mashine_clause');
        $comp_id                    = $this->input->post('company_id');
        $company_name               = $this->input->post('company_name');
        $company_address            = $this->input->post('company_address');
        $city                       = $this->input->post('city');
        $state                      = $this->input->post('state');
        $prob_desc                  = $this->input->post('prob_desc');
        $correction                 = $this->input->post('correction');
        $root_cause                 = $this->input->post('root_cause');
        $business_impact            = $this->input->post('business_impact');
        $policy                     = $this->input->post('policy');
        $action_plan                = $this->input->post('action_plan');
        $corrective_action          = $this->input->post('corrective_action');
        $verification_effectiveness = $this->input->post('verification_effectiveness');
        $type                       = $this->input->post('type');
        $responsible_party          = $this->input->post('responsible_party');
        $role                       = $this->input->post('role');
        $by_when_date               = $this->input->post('by_when_date');
        $create_at                  = date('Y-m-d');
        $date                       = date('Y-m-d');
        $data12                     = array(
            'company_id' => $company_id,
            'create_at' => $create_at,
            'by_when_date' => $by_when_date,
            'policy' => $policy,
            'role' => $role,
            'responsible_party' => $responsible_party,
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
            'comp_id' => $comp_id,
            'mashine_clause' => $mashine_clause,
            'shift' => $shift,
            'regulatory_requirement' => $regulatory_requirement,
            'standard' => $standard,
            'product' => $product,
            'customer_requirment' => $customer_requirment,
            'audit_criteria' => $audit_criteria,
            'occur_date' => $occur_date,
            'trigger_id' => $trigger_id,
            'auditor_id' => $auditor_id,
            'unique_id' => $unique_id,
            'area' => $area,
            'process' => $process,
            'comment' => $comment,
            'process_id' => $process_id,
            'item_id' => $item_id,
            'corrective_action' => $corrective_action,
            'verification_effectiveness' => $verification_effectiveness
        );
        if ($company_id) {
            $data = array(
                'company_id' => $company_id,
                'create_at' => $create_at,
                'by_when_date' => $by_when_date,
                'role' => $role,
                'responsible_party' => $responsible_party,
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
                'comp_id' => $comp_id,
                'mashine_clause' => $mashine_clause,
                'policy' => $policy,
                'shift' => $shift,
                'regulatory_requirement' => $regulatory_requirement,
                'standard' => $standard,
                'product' => $product,
                'customer_requirment' => $customer_requirment,
                'audit_criteria' => $audit_criteria,
                'occur_date' => $occur_date,
                'trigger_id' => $trigger_id,
                'auditor_id' => $auditor_id,
                'unique_id' => $unique_id,
                'corrective_action' => $corrective_action,
                'verification_effectiveness' => $verification_effectiveness
            );
            if ($item_id != '') {
                $done    = $this->db->insert('corrective_action_data', $data12);
                $last_id = $this->db->insert_id();
            } else {
                $done    = $this->db->insert('corrective_action_data', $data);
                $last_id = $this->db->insert_id();
            }
            if ($done) {
                $this->session->set_flashdata('message', 'submit');
                redirect('Company/car_action_notification/' . $last_id);
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/car_action_notification/' . $last_id);
            }
        } else {
            redirect('Welcome');
        }
    }
    public function trigger_list()
    {
        $data['cc1'] = 'active';
        $data['c4']  = 'active';
        $data['c42'] = 'act1';
        $company_id  = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Trigger";
            $this->db->where('company_id', $company_id);
            $data['trigger'] = $this->db->get('trigger')->result();
            $this->load->view('Company/trigger_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_trigger()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $trigger_name = $this->input->post('trigger_name');
            $data         = array(
                'trigger_name' => $trigger_name,
                'company_id' => $company_id
            );
            $done         = $this->db->insert('trigger', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'success');
                redirect('Company/trigger_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/trigger_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_trigger($id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('trigger_id', $id);
            $done = $this->db->delete('trigger');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/trigger_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/trigger_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function findtrigger()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $id = $this->input->post('id');
            $this->db->where('trigger_id', $id);
            $done = $this->db->get('trigger')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }
    public function edit_trigger()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $trigger_id   = $this->input->post('trigger_id');
            $trigger_name = $this->input->post('trigger_name');
            $data         = array(
                'trigger_name' => $trigger_name
            );
            $this->db->where('trigger_id', $trigger_id);
            $done = $this->db->update('trigger', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Company/trigger_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/trigger_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function cases_list()
    {
        $data['cc1'] = 'active';
        $data['c4']  = 'active';
        $data['c43'] = 'act1';
        $company_id  = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Case Type";
            $this->db->where('company_id', $company_id);
            $data['case'] = $this->db->get('cases')->result();
            $this->load->view('Company/cases_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_case()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $case_name = $this->input->post('case_name');
            $data      = array(
                'case_name' => $case_name,
                'company_id' => $company_id
            );
            $done      = $this->db->insert('cases', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'success');
                redirect('Company/cases_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/cases_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_case($id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('case_id', $id);
            $done = $this->db->delete('cases');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/cases_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/cases_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function findcase()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
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
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $case_id   = $this->input->post('case_id');
            $case_name = $this->input->post('case_name');
            $data      = array(
                'case_name' => $case_name
            );
            $this->db->where('case_id', $case_id);
            $done = $this->db->update('cases', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Company/cases_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/cases_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function cust_list()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title']     = "Company Information";
            $data['customers'] = $this->db->get('customers')->result();
            $this->load->view('Company/cust_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_customer()
    {
        $company_id = $this->session->userdata('company_id');
        $date       = date('Y-m-d');
        if ($company_id) {
            $name    = $this->input->post('name');
            $address = $this->input->post('address');
            $city    = $this->input->post('city');
            $state   = $this->input->post('state');
            $data    = array(
                'name' => $name,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'date' => $date,
                'status' => '1',
                'company_id' => $company_id
            );
            $done    = $this->db->insert('customers', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'success');
                redirect('Company/cust_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/cust_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function findcust11()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $done = $this->db->get('customers')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }
    public function edit_cust()
    {
        $company_id = $this->session->userdata('company_id');
        $date       = date('Y-m-d');
        if ($company_id) {
            $name    = $this->input->post('name');
            $address = $this->input->post('address');
            $city    = $this->input->post('city');
            $state   = $this->input->post('state');
            $cust_id = $this->input->post('cust_id');
            $data    = array(
                'name' => $name,
                'address' => $address,
                'city' => $city,
                'state' => $state
            );
            $this->db->where('id', $cust_id);
            $done = $this->db->update('customers', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Company/cust_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/cust_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_cust($id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('id', $id);
            $done = $this->db->delete('customers');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/cust_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/cust_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function send_assignment_mail($id = 0)
    {
        $company_id              = $this->session->userdata('company_id');
        $data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
        $data['comp_email']      = $this->db->query("SELECT * FROM `company` WHERE `company_id`='$company_id'")->row()->email;
        $data['employees_email'] = $this->db->query("SELECT * FROM `employees` WHERE `company_id`='$company_id' &&  `employee_email`!=''")->result();
        $this->db->where('company_id', $company_id);
        $data['company_name'] = $this->db->get('company')->row()->company_name;
        $data['process_id']   = $id;
        if ($company_id) {
            $data['title'] = "CheckList  EMail";
            $this->db->where('id', $id);
            $data['email_data'] = $this->db->get('process_list')->row();
            $this->db->where('flag', '1');
            $this->db->where('process_id', $id);
            $data['email_list_data'] = $this->db->get('verification_log_process_items')->result();
            $this->load->view('Company/send_assignment_mail', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function car_action_notification($id = Null)
    {
        $data['bb1'] = 'active';
        $company_id  = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "CAR ACTION NOTIFICATION";
            $this->db->where('id', $id);
            $data['standalone'] = $this->db->get('corrective_action_data')->row();
            $this->load->view('Company/car_action_notification', $data);
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
        $company_id   = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Standalone Form Detail";
            $this->db->where('id', $id);
            $data['standalone'] = $this->db->get('corrective_action_data')->row();
            $this->load->view('Company/corrective_action_form_detail', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function resolution_list_rp()
    {
        $company_id  = $this->session->userdata('company_id');
        $data['bb1'] = 'active';
        $data['b2']  = 'act1';
        if ($company_id) {
            $data['title']           = "CORRECTIVE ACTIONS Resolution";
            $data['no']              = "owner";
            $data['standalone_data'] = $this->db->query("SELECT *,COUNT(id) as open FROM `corrective_action_data` WHERE del_flag=0 and `process_status`!='Close'  AND `company_id`='$company_id' ")->result();
            print_r($data['standalone_data']);
        } else {
            redirect('Welcome');
        }
    }
    public function corrective_action_report()
    {
        $data['bb1']             = 'active';
        $data['b3']              = 'act1';
        $company_id              = $this->session->userdata('company_id');
        $data['admin_emails']    = $this->db->query("SELECT * FROM `admin`")->row()->email;
        $data['comp_email']      = $this->db->query("SELECT * FROM `company` WHERE `company_id`='$company_id'")->row()->email;
        $data['employees_email'] = $this->db->query("SELECT * FROM `employees` WHERE `company_id`='$company_id' &&  `employee_email`!=''")->result();
        $data['title']           = "CORRECTIVE ACTIONS Report";
        if ($company_id) {
            $data['no'] = "owner";
            $emp_list   = $this->db->query("SELECT * FROM `employees` WHERE `company_id`='$company_id'")->result();
            for ($i = 0; $i < sizeof($emp_list); $i++) {
                $item            = $emp_list[$i];
                $cnt             = $this->db->query("SELECT COUNT(id) as count FROM `corrective_action_data` WHERE del_flag=0 and `process_status`!='Close'  AND `responsible_party`='$item->employee_id'")->row()->count;
                $item->open_cnt  = $cnt;
                $cnt             = $this->db->query("SELECT COUNT(id) as count FROM `corrective_action_data` WHERE del_flag=0 and `process_status`='Close'  AND `responsible_party`='$item->employee_id'")->row()->count;
                $item->close_cnt = $cnt;
                $emp_list[$i]    = $item;
            }
            $data['standalone_data'] = $emp_list;
            $this->load->view('Company/corrective_action_report', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function resolution_list()
    {
        $data['bb1']   = 'active';
        $data['b4']    = 'act1';
        $company_id    = $this->session->userdata('company_id');
        $data['title'] = "Corrective Action Resolution Log";
        if ($company_id) {
            $this->db->order_by('id', 'desc');
            $this->db->where('company_id', $company_id);
            $this->db->where('process_status!=', 'Close');
            $data['standalone_data'] = $this->db->get('corrective_action_data')->result();
            $this->load->view('Company/resolution_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function resolution($id = '')
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('company_id', $company_id);
            $data['cases']     = $this->db->get('cases')->result();
            $data['customers'] = $this->db->get('customers')->result();
            $this->db->where('company_id', $company_id);
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
            $data['regulatory_requirement_list'] = $this->db->get('regulatory_requirement')->result();
            $this->db->where('company_id', $company_id);
            $data['shift_list'] = $this->db->get('shift')->result();
            $this->db->where('company_id', $company_id);
            $data['policy_list'] = $this->db->get('policy')->result();
            $this->db->where('company_id', $company_id);
            $data['mashine_list'] = $this->db->get('mashine')->result();
            $this->db->where('company_id', $company_id);
            $data['mashine_list'] = $this->db->get('mashine')->result();
            $this->db->where('company_id', $company_id);
            $data['criteria_list'] = $this->db->get('audit_criteria_list')->result();
            $this->load->view('Company/resolution', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function resolved_list($type = '')
    {
        $data['bb1'] = 'active';
        if ($type == 'CORRECTION') {
            $data['b51']   = 'act1';
            $data['title'] = "Verification Resolution History";
        } else {
            $data['b5']    = 'act1';
            $data['title'] = "Internal Audit And Other Resolution History";
        }
        $data['type'] = $type;
        $company_id   = $this->session->userdata('company_id');
        if ($company_id) {
            $trigger_id = $this->db->query("SELECT * FROM `trigger` WHERE `company_id`='$company_id' ")->row()->trigger_id;
            $data['no'] = "auditor";
            $this->db->where('type', $type);
            $this->db->where('company_id', $company_id);
            $this->db->where('process_status', 'Close');
            $query = "select * from corrective_action_data  where del_flag=0 and company_id=" . $company_id;
            $query .= " and process_status='Close'";
            if ($type == 'CORRECTION') {
                $query .= " and (type = 'CORRECTION' or trigger_id=" . $trigger_id . ")";
            } else {
                $query .= " and (type = 'CORRECTIVE' and trigger_id !=" . $trigger_id . ")";
            }
            $data['standalone_data'] = $this->db->query($query)->result();
            $this->load->view('Company/resolved_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function update_resolution()
    {
        $company_id                 = $this->session->userdata('company_id');
        $trigger_id                 = $this->input->post('trigger_id');
        $customer_requirment        = $this->input->post('customer_requirment');
        $product                    = $this->input->post('product');
        $regulatory_requirement     = $this->input->post('regulatory_requirement');
        $policy                     = $this->input->post('policy');
        $shift                      = $this->input->post('shift');
        $standard                   = $this->input->post('standard');
        $mashine_clause             = $this->input->post('mashine_clause');
        $occur_date                 = $this->input->post('occur_date');
        $cust_id                    = $this->input->post('comp_id');
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
                'standard' => $standard,
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
                'action_taken' => $action_taken
            );
            $this->db->where('id', $form_id);
            $done = $this->db->update('corrective_action_data', $data);
            if ($done) {
                if ($verification_question_flag == '2') {
                    $this->session->set_flashdata('message', 'submit');
                    redirect('Company/car_verification_form/' . $form_id);
                } else {
                    redirect('Company/resolution_list');
                }
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/car_verification_form/' . $form_id);
            }
        } else {
            redirect('Welcome');
        }
    }
    public function car_verification_form($id)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Verification Form";
            $this->db->where('id', $id);
            $data['standalone_data'] = $this->db->get('corrective_action_data')->row();
            $this->load->view('Company/car_verification_form', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function get_audit_area()
    {
        $process = $this->input->post('process');
        $this->db->where('inspection', $process);
        $process_list = $this->db->get('process_list')->result();
        if (sizeof($process_list) == 0) {
            echo '<option value=""></option>';
        } else {
            $this->db->flush_cache();
            $this->db->where('process_id', $process_list[0]->id);
            $process_item_list = $this->db->get('process_items')->result();
            echo '<option value=""></option>';
            if (sizeof($process_item_list) > 0) {
                foreach ($process_item_list as $item) {
                    echo "<option value='" . $item->audit_area . "'>" . $item->audit_area . "</option>";
                }
            }
        }
    }
    public function add_mashine()
    {
        $company_id = $this->session->userdata('company_id');
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
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $mashine = $this->db->get('mashine')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($mashine as $mashines) {
            echo "<option value='" . $mashines->name . "'>" . $mashines->name . "</option>";
        }
    }
    public function all_mashine_table()
    {
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $mashine = $this->db->get('mashine')->result();
        foreach ($mashine as $mashines) {
            echo "<tr><td>" . $mashines->name . "</td><td><a onclick='deletemashine(" . $mashines->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_mashine()
    {
        $company_id = $this->session->userdata('company_id');
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('mashine');
    }
    public function add_regulatory_requirement()
    {
        $company_id = $this->session->userdata('company_id');
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
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('regulatory_requirement')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_regulatory_requirement_table()
    {
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('regulatory_requirement')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deleteregulatory_requirement(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_regulatory_requirement()
    {
        $company_id = $this->session->userdata('company_id');
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('regulatory_requirement');
    }
    public function add_customer_requirment()
    {
        $company_id = $this->session->userdata('company_id');
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
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('customer_requirment')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_customer_requirment_table()
    {
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('customer_requirment')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deletecustomer_requirment(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_customer_requirment()
    {
        $company_id = $this->session->userdata('company_id');
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('customer_requirment');
    }
    public function add_standard()
    {
        $company_id = $this->session->userdata('company_id');
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
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('standard')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_standard_table()
    {
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('standard')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deletestandard(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_standard()
    {
        $company_id = $this->session->userdata('company_id');
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('standard');
    }
    public function add_policy()
    {
        $company_id = $this->session->userdata('company_id');
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
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('policy')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_policy_table()
    {
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('policy')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deletepolicy(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_policy()
    {
        $company_id = $this->session->userdata('company_id');
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('policy');
    }
    public function add_criteria()
    {
        $company_id = $this->session->userdata('company_id');
        $name       = $this->input->post('name');
        $data       = array(
            'name' => $name,
            'company_id' => $company_id
        );
        $done       = $this->db->insert('audit_criteria_list', $data);
        if ($done) {
            $this->db->where('company_id', $company_id);
            $audit_criteria_list = $this->db->get('audit_criteria_list')->result();
            echo '<option value="">Select AUDIT CRITERIA</option>';
            foreach ($audit_criteria_list as $audit_criteria_lists) {
                echo "<option value='" . $audit_criteria_lists->name . "'>" . $audit_criteria_lists->name . "</option>";
            }
        } else {
        }
    }
    public function all_criteria()
    {
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $audit_criteria_list = $this->db->get('audit_criteria_list')->result();
        echo '<option value="">Select AUDIT CRITERIA</option>';
        foreach ($audit_criteria_list as $audit_criteria_lists) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $audit_criteria_lists->name . "</option>";
        }
    }
    public function all_criteria_table()
    {
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $audit_criteria_list = $this->db->get('audit_criteria_list')->result();
        foreach ($audit_criteria_list as $audit_criteria_lists) {
            echo "<tr><td>" . $audit_criteria_lists->name . "</td><td><a onclick='deletecriteria(" . $audit_criteria_lists->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_criteria()
    {
        $company_id = $this->session->userdata('company_id');
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('audit_criteria_list');
    }
    public function add_product()
    {
        $company_id = $this->session->userdata('company_id');
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
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('product')->result();
        echo '<option value="Not Applicable">Not Applicable</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_product_table()
    {
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('product')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deleteproduct(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_product()
    {
        $company_id = $this->session->userdata('company_id');
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('product');
    }
    public function add_shift()
    {
        $company_id = $this->session->userdata('company_id');
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
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('shift')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<option value='" . $regulatory_requirements->name . "'>" . $regulatory_requirements->name . "</option>";
        }
    }
    public function all_shift_table()
    {
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('shift')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->name . "</td><td><a onclick='deleteshift(" . $regulatory_requirements->id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_shift()
    {
        $company_id = $this->session->userdata('company_id');
        $id         = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('shift');
    }
    public function add_triggers()
    {
        $company_id = $this->session->userdata('company_id');
        $name       = $this->input->post('name');
        $data       = array(
            'trigger_name' => $name,
            'company_id' => $company_id
        );
        $done       = $this->db->insert('trigger', $data);
        if ($done) {
            $this->db->where('company_id', $company_id);
            $regulatory_requirement = $this->db->get('trigger')->result();
            echo '<option value="">Select TRIGGER</option>';
            foreach ($regulatory_requirement as $regulatory_requirements) {
                echo "<option value='" . $regulatory_requirements->trigger_name . "'>" . $regulatory_requirements->trigger_name . "</option>";
            }
        } else {
        }
    }
    public function all_trigger()
    {
        $type       = $this->input->post('type');
        $company_id = $this->session->userdata('company_id');
        if ($company_id == null) {
            $company_id = $this->session->userdata('company_id1');
        }
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('trigger')->result();
        echo '<option value="">Select TRIGGER</option>';
        foreach ($regulatory_requirement as $regulatory_requirements) {
            if (isset($type)) {
                $pos = stripos($regulatory_requirements->trigger_name, 'Verification');
                if ($pos === false) {
                    echo "<option value='" . $regulatory_requirements->trigger_id . "'>" . $regulatory_requirements->trigger_name . "</option>";
                } else {
                    echo "<option selected value='" . $regulatory_requirements->trigger_id . "'>" . $regulatory_requirements->trigger_name . "</option>";
                }
            } else {
                echo "<option value='" . $regulatory_requirements->trigger_id . "'>" . $regulatory_requirements->trigger_name . "</option>";
            }
        }
    }
    public function all_trigger_table()
    {
        $company_id = $this->session->userdata('company_id');
        $this->db->where('company_id', $company_id);
        $regulatory_requirement = $this->db->get('trigger')->result();
        foreach ($regulatory_requirement as $regulatory_requirements) {
            echo "<tr><td>" . $regulatory_requirements->trigger_name . "</td><td><a onclick='deletetriggers(" . $regulatory_requirements->trigger_id . ");';><i class='icon-trash'></i></a></td><tr>";
        }
    }
    public function delete_triggers()
    {
        $company_id = $this->session->userdata('company_id');
        $id         = $this->input->post('id');
        $this->db->where('trigger_id', $id);
        $this->db->delete('trigger');
    }
    function verification_log_update()
    {
        $data       = array(
            'flag' => '0'
        );
        $process_id = $this->input->post('process_id');
        $this->db->where('process_id', $process_id);
        $this->db->update('verification_log_process_items', $data);
    }





    public function spa_list()
    {
        $data['aa1'] = 'active';
        $data['a1'] = 'act1';
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "SPA LIST";
            $data['spas'] = $this->db->get('spa_list')->result();
            $this->load->view('Company/spa_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_spa()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $spa_name = $this->input->post('spa_name');
            $created_at = date('Y-m-d');

            $data = array(
                'company_id' => $company_id,
                'spa_name' => $spa_name,
                'created_at' => $created_at
            );
            $done = $this->db->insert('spa_list', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'success');
                redirect('Company/spa_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/spa_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function findspa()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $id = $this->input->post('id');
            $this->db->where('spa_id', $id);
            $done = $this->db->get('spa_list')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }
    public function edit_spa()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $spa_id = $this->input->post('spa_id1');
            $spa_name = $this->input->post('spa_name');

            $data = array(
                'company_id' => $company_id,
                'spa_name' => $spa_name,
            );

            $this->db->where('spa_id', $spa_id);
            $done = $this->db->update('spa_list', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Company/spa_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/spa_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_spa($id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('spa_id', $id);
            $done = $this->db->delete('spa_list');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/spa_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/spa_list');
            }
        } else {
            redirect('Welcome');
        }
    }

    public function sme_list()
    {
        $data['aa1'] = 'active';
        $data['a2'] = 'act1';
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "SME LIST";
            $data['smes'] = $this->db->get('sme_list')->result();
            $this->load->view('Company/sme_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_sme()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $sme_name = $this->input->post('sme_name');
            $created_at = date('Y-m-d');

            $data = array(
                'company_id' => $company_id,
                'sme_name' => $sme_name,
                'created_at' => $created_at
            );
            $done = $this->db->insert('sme_list', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'success');
                redirect('Company/sme_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/sme_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function findsme()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $id = $this->input->post('id');
            $this->db->where('sme_id', $id);
            $done = $this->db->get('sme_list')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }
    public function edit_sme()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $sme_id = $this->input->post('sme_id1');
            $sme_name = $this->input->post('sme_name');

            $data = array(
                'company_id' => $company_id,
                'sme_name' => $sme_name,
            );

            $this->db->where('sme_id', $sme_id);
            $done = $this->db->update('sme_list', $data);
            if ($done) {
                $this->session->set_flashdata('message', 'update_success');
                redirect('Company/sme_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/sme_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_sme($id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('sme_id', $id);
            $done = $this->db->delete('sme_list');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/sme_list');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/sme_list');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function fssc_requirements()
    {
        $data['dd1'] = 'active';

        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "FSSC Requirements";
            $this->db->where('company_id', $company_id);
            $data['fssc_requirements'] = $this->db->get('fssc_requirements')->result();
            foreach ($data['fssc_requirements'] as &$value) {
                $this->db->where('clause_number',$value->clause);
                $this->db->order_by('when','desc');
                $log = $this->db->get('corrective_actions_log')->row();
                if(!empty($log))
                {
                    $value->spa = $log->spa;
                    $value->action = $log->action;
                    $value->sme = $log->sme;
                    $value->when = $log->when;
                    $value->status = $log->status;
                    $value->closed = $log->closed;
                }
            }
            $this->load->view('Company/fssc_requirements_list', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_fssc_requirement()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('clause', 'CLAUSE', 'required|is_unique[fssc_requirements.clause]');
            $this->form_validation->set_rules('description', 'DESCRIPTION', 'required');
            $this->form_validation->set_rules('internal', 'IDENTIFIED GAPS FROM INTERNAL AUDIT', 'required');
            $this->form_validation->set_rules('correct', 'IMPLEMENTATION TASKS TO CORRECT GAPS', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', $this->form_validation->error_string());
                redirect('Company/fssc_requirements');
            }
            else
            {
                $fssc_requirement = $this->input->post();
                $fssc_requirement['company_id'] = $company_id;
                $fssc_requirement['cdate'] = date('Y-m-d H:i:s');
                $done = $this->db->insert('fssc_requirements', $fssc_requirement);
                if ($done) {
                    $this->session->set_flashdata('message', 'success');
                    redirect('Company/fssc_requirements');
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Company/fssc_requirements');
                }
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_fssc_requirement($id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('id', $id);
            $done = $this->db->delete('fssc_requirements');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/fssc_requirements');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/fssc_requirements');
            }
        } else {
            redirect('Welcome');
        }
    }

    public function find_fssc_requirement()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $id = $this->input->post('id');
            if($id == null)
            {
                $id = $this->input->post('clause');
                $this->db->where('clause', $id);
            }
            else
                $this->db->where('id', $id);
            $done = $this->db->get('fssc_requirements')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }
    public function edit_fssc_requirement()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $done = $this->db->get('fssc_requirements')->row();

            if ($done->clause != $this->input->post('clause')){
                $this->form_validation->set_rules('clause', 'CLAUSE', 'required|is_unique[fssc_requirements.clause]');
            }
            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('description', 'DESCRIPTION', 'required');
            $this->form_validation->set_rules('internal', 'IDENTIFIED GAPS FROM INTERNAL AUDIT', 'required');
            $this->form_validation->set_rules('correct', 'IMPLEMENTATION TASKS TO CORRECT GAPS', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', $this->form_validation->error_string());
                redirect('Company/fssc_requirements');
            }
            else
            {

                $this->db->where('id', $this->input->post('id'));
                $done = $this->db->update('fssc_requirements', $this->input->post());
                if ($done) {
                    $this->session->set_flashdata('message', 'update_success');
                    redirect('Company/fssc_requirements');
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Company/fssc_requirements');
                }
            }
        } else {
            redirect('Welcome');
        }
    }
    public function corrective_actions_log()
    {
        $data['ee1'] = 'active';

        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Corrective Actions Log";
            $this->db->where('company_id', $company_id);
            $spa_name = $this->input->get('spa_name');
            if($spa_name != Null) {
                $this->db->where('spa', $spa_name);
            }
            $sme_name = $this->input->get('sme_name');
            if($sme_name != Null) {
                $this->db->where('sme', $sme_name);
            }
            $data['logs'] = $this->db->get('corrective_actions_log')->result();
            $this->db->where('company_id', $company_id);
            $data['spas'] = $this->db->get('spa_list')->result();
            $this->db->where('company_id', $company_id);
            $data['smes'] = $this->db->get('sme_list')->result();
            $this->load->view('Company/corrective_actions_log', $data);
        } else {
            redirect('Welcome');
        }
    }
    public function add_corrective_actions_log()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('clause_number', 'CLAUSE_NUMBER', 'required');
            $this->form_validation->set_rules('clause', 'CLAUSE', 'required');
            $this->form_validation->set_rules('gaps', 'GAPS', 'required');
            $this->form_validation->set_rules('correct', 'TASKS', 'required');
            $this->form_validation->set_rules('spa', 'SPA', 'required');
            $this->form_validation->set_rules('action', 'CORRECTIVE ACTION', 'required');
            $this->form_validation->set_rules('sme', 'RESPONSIVE PERSON', 'required');
            $this->form_validation->set_rules('action', 'CORRECTIVE ACTION', 'required');
            $this->form_validation->set_rules('when', 'BY When Date', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', $this->form_validation->error_string());
                redirect('Company/corrective_actions_log');
            }
            else
            {
                $log = $this->input->post();
                $log['company_id'] = $company_id;
                $log['cdate'] = date('Y-m-d H:i:s');
                $done = $this->db->insert('corrective_actions_log', $log);
                if ($done) {
                    $this->session->set_flashdata('message', 'success');
                    redirect('Company/corrective_actions_log');
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Company/corrective_actions_log');
                }
            }
        } else {
            redirect('Welcome');
        }
    }
    public function find_corrective_actions_log()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $done = $this->db->get('corrective_actions_log')->row();
            echo json_encode($done);
        } else {
            redirect('Welcome');
        }
    }
    public function edit_corrective_actions_log()
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('id', 'CLAUSE_NUMBER', 'required');
            $this->form_validation->set_rules('clause_number', 'CLAUSE_NUMBER', 'required');
            $this->form_validation->set_rules('clause', 'CLAUSE', 'required');
            $this->form_validation->set_rules('gaps', 'GAPS', 'required');
            $this->form_validation->set_rules('correct', 'TASKS', 'required');
            $this->form_validation->set_rules('spa', 'SPA', 'required');
            $this->form_validation->set_rules('action', 'CORRECTIVE ACTION', 'required');
            $this->form_validation->set_rules('sme', 'RESPONSIVE PERSON', 'required');
            $this->form_validation->set_rules('action', 'CORRECTIVE ACTION', 'required');
            $this->form_validation->set_rules('when', 'BY When Date', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', $this->form_validation->error_string());
                redirect('Company/corrective_actions_log');
            }
            else
            {
                $log = $this->input->post();
                $this->db->where('id',$this->input->post('id'));
                $done = $this->db->update('corrective_actions_log', $log);
                if ($done) {
                    $this->session->set_flashdata('message', 'update_success');
                    redirect('Company/corrective_actions_log');
                } else {
                    $this->session->set_flashdata('message', 'failed');
                    redirect('Company/corrective_actions_log');
                }
            }
        } else {
            redirect('Welcome');
        }
    }
    public function delete_corrective_actions_log($id = Null)
    {
        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $this->db->where('id', $id);
            $done = $this->db->delete('corrective_actions_log');
            if ($done) {
                $this->session->set_flashdata('message', 'success_del');
                redirect('Company/corrective_actions_log');
            } else {
                $this->session->set_flashdata('message', 'failed');
                redirect('Company/corrective_actions_log');
            }
        } else {
            redirect('Welcome');
        }
    }
    public function mainmenu()
    {
        $data['bb1'] = 'active';
        $open_status = 1;
        $closed_status = 0;

        $company_id = $this->session->userdata('company_id');
        if ($company_id) {
            $data['title'] = "Main Menu";
            $data['total_gaps'] = $this->db->count_all_results('corrective_actions_log');

            $this->db->where('status', $open_status);
            $data['total_open_actions'] = $this->db->count_all_results('corrective_actions_log');

            $this->db->where('status', $closed_status);
            $data['total_closed_actions'] = $this->db->count_all_results('corrective_actions_log');

            $spas = $this->db->get('spa_list')->result();
            $data['spas'] = array();
            if(isset($spas) && !empty($spas)) {
                foreach($spas as $spa) {
                    $this->db->where('spa', $spa->spa_name);
                    $total_for_spas = $this->db->count_all_results('corrective_actions_log');

                    $this->db->where('spa', $spa->spa_name);
                    $this->db->where('status', $open_status);
                    $open_for_spas = $this->db->count_all_results('corrective_actions_log');

                    $this->db->where('spa', $spa->spa_name);
                    $this->db->where('date(corrective_actions_log.when) <',date('Y-m-d'));
                    $past_for_spas = $this->db->count_all_results('corrective_actions_log');

                    array_push($data['spas'], array(
                        'spa_name' => $spa->spa_name,
                        'total' => $total_for_spas,
                        'open' => $open_for_spas,
                        'past' => $past_for_spas
                    ));
                }
            }

            $smes = $this->db->get('sme_list')->result();
            $data['smes'] = array();
            if(isset($smes) && !empty($smes)) {
                foreach($smes as $sme) {
                    $this->db->where('sme', $sme->sme_name);
                    $total_for_smes = $this->db->count_all_results('corrective_actions_log');

                    $this->db->where('sme', $sme->sme_name);
                    $this->db->where('status', $open_status);
                    $open_for_smes = $this->db->count_all_results('corrective_actions_log');

                    $this->db->where('sme', $sme->sme_name);
                    $this->db->where('date(corrective_actions_log.when) <',date('Y-m-d'));
                    $past_for_smes = $this->db->count_all_results('corrective_actions_log');

                    array_push($data['smes'], array(
                        'sme_name' => $sme->sme_name,
                        'total' => $total_for_smes,
                        'open' => $open_for_smes,
                        'past' => $past_for_smes
                    ));
                }
            }
            $this->load->view('Company/mainmenu', $data);
        } else {
            redirect('Welcome');
        }
    }
}
