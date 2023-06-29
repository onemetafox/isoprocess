<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH. '/libraries/BaseController.php';
class Auth extends BaseController //CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
    }

    public function login()
    {
        //$this->session->set_flashdata('message', '');

        /*=-=-=- check if user already logged in start =-=-=-*/
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
        }
        /*=-=-=- check if user already logged in end =-=-=-*/
        if ($_POST){

            $this->load->model('Authmodel');
            $this->load->library('form_validation');
            $this->load->library('user_agent');

            if ($this->settings->otp_verification){
                $this->load->model('OTPVerification');
                $data['title']      = 'Login';
                $data['menu_title'] = 'Login';
                $data['otp_status'] = true;

                $user_OTP       = $this->input->post('code');
                $user_OTP       = is_array($user_OTP) ? $user_OTP:array();
                $_POST['code']  = $user_OTP = implode('',$user_OTP);

                $this->form_validation->set_rules('u', 'Username', 'required');
                $this->form_validation->set_rules('p', 'Password', 'required');
                $this->form_validation->set_rules('u_t', 'User Type', 'required', array('required' => 'Please Select %s.'));
                $this->form_validation->set_rules('code', 'OTP', 'required|exact_length[4]', array('required' => 'Invalid %s.'));



                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('login', $data);
                } else {
                    try{
                        $usertype           = _decode($this->input->post('u_t'));
                        $username           = _decode($this->input->post('u'));
                        $password           = _decode($this->input->post('p'));
                    }catch (Exception $e){
                        $this->session->set_flashdata('message', 'Na Kakka Halii Na');
                        redirect('Auth/verification');
                        return;
                    }
                    if (isset($usertype)) {
                        if ($usertype == 'admin') {
                            $data = $this->Authmodel->admin_login($username);
                            if ($data && verifyHashedPassword($password, $data->password)) {
                                $data1 = array(
                                    'admin_id' => $data->id,
                                    'user_type' => $usertype,
                                    'username' => $data->username,
                                    'is_password_updated' => $data->isPasswordUptd
                                );
                                /*=-=-=- check is OTP Valid Start =-=-=-*/
                                $verified = $this->OTPVerification->get_auth_OTP(array(
                                    'model_name'    => 'admin',
                                    'model_id'      => $data->id,
                                    'otp'           => $user_OTP,
                                ));
                                if (!$verified){
                                    $this->session->set_flashdata('message', 'Invalid OTP');
                                    redirect('Auth/verification');
                                }
                                /*=-=-=- check is OTP Valid end =-=-=-*/

                                /**====Get userlogin location=====**/
                                if ($this->agent->is_browser())
                                {
                                   $agent = $this->agent->browser().' '.$this->agent->version();
                                }
                                elseif ($this->agent->is_robot())
                                {
                                        $agent = $this->agent->robot();
                                }
                                elseif ($this->agent->is_mobile())
                                {
                                        $agent = $this->agent->mobile();
                                }
                                else
                                {
                                        $agent = 'Unidentified User Agent';
                                }

                                $login_device = $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)
                                $login_platform = $this->agent->browser();
                                                                    
                                $ip_address=$_SERVER['REMOTE_ADDR'];
                                $user_id = $data->id;
                                //json_decode
                                ////////////////////////////////////////////////////////
                                $getLastIp = $this->Authmodel->last_login_ip($user_id,$ip_address);
                                $LastIp = $getLastIp->IP_address;
                                $user_last_id = $getLastIp->id;
                               // $status = "1";
                                if($ip_address == $LastIp){
                                    $status = "1";
                                }
                                else{
                                    $status = "2";
                                }
                                $geopluginURL_1 = 'https://api.ipfind.com?ip='.$ip_address.'&auth=af445bd7-b3b1-47d7-a3bc-fc92288a144c';
                                $addrDetailsArr_1 = json_decode(file_get_contents($geopluginURL_1));
                                $country = $addrDetailsArr_1->country;
                                $city = $addrDetailsArr_1->city;
                                $login_area = $city.','.$country;
                                $currentdatetime = date('Y-m-d H:i:s');
                                 $Last_Login = array(
                                    'user_id' => $data->id,
                                    'login_area' => $login_area,
                                    'IP_address' => $ip_address,
                                    'login_platform' => $login_platform,
                                    'login_device' => $login_device,
                                    'date_time' => $currentdatetime,
                                    'status' => $status,
                                   
                                    
                                );
                                $done    = $this->db->insert('login_history', $Last_Login);
                                /**====Get userlogin location End=====**/
                                $this->session->set_userdata($data1);
                                redirect('Welcome/admindashboard');
                            } else {
                                $this->session->set_flashdata('message', 'Invalid credentials');
                                redirect($_SERVER['HTTP_REFERER']);
                                exit;
                            }
                        }
                        if (($usertype == 'Lead Auditor') || ($usertype == 'Auditor') || ($usertype == 'Process Owner') || ($usertype == 'Auditee')) {
                            $data = $this->Authmodel->employee_login($username, $usertype);
                            if ($data && verifyHashedPassword($password, $data->password)) {
                                $data1 = array(
                                    'employee_id' => $data->employee_id,
                                    'username' => $data->username,
                                    'consultant_id' => $data->consultant_id,
                                    'user_type' => $usertype,
                                    'com_status' => $data->status,
                                    'is_password_updated' => $data->isPasswordUptd,
                                );
                                /*=-=-=- check is OTP Valid Start =-=-=-*/
                                $verified = $this->OTPVerification->get_auth_OTP(array(
                                    'model_name'    => 'employees',
                                    'model_id'      => $data->employee_id,
                                    'otp'           => $user_OTP,
                                ));
                                if (!$verified){
                                    $this->session->set_flashdata('message', 'Invalid OTP');
                                    redirect('Auth/verification');
                                }
                                /*=-=-=- check is OTP Valid end =-=-=-*/
                                $this->session->set_userdata($data1);
                                /**====Get userlogin location=====**/
                                  if ($this->agent->is_browser())
                                    {
                                            $agent = $this->agent->browser().' '.$this->agent->version();
                                    }
                                    elseif ($this->agent->is_robot())
                                    {
                                            $agent = $this->agent->robot();
                                    }
                                    elseif ($this->agent->is_mobile())
                                    {
                                            $agent = $this->agent->mobile();
                                    }
                                    else
                                    {
                                            $agent = 'Unidentified User Agent';
                                    }


                                    $login_device = $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)
                                    $login_platform = $this->agent->browser();
                                    //$getloc = json_decode(file_get_contents("http://ipinfo.io/"));
                                    //$record = geoip_record_by_name($ip);
                                   $ip_address=$_SERVER['REMOTE_ADDR'];
                                    $user_id = $data->employee_id;
                                    //json_decode
                                    ////////////////////////////////////////////////////////
                                    $getLastIp = $this->Authmodel->last_login_ip($user_id,$ip_address);
                                    $LastIp = $getLastIp->IP_address;
                                    $user_last_id = $getLastIp->id;
                                   // $status = "1";
                                    if($ip_address == $LastIp){
                                        $status = "1";
                                    }
                                    else{
                                        $status = "2";
                                    }
                                   $geopluginURL_1 = 'https://api.ipfind.com?ip='.$ip_address.'&auth=af445bd7-b3b1-47d7-a3bc-fc92288a144c';
                                   $addrDetailsArr_1 = json_decode(file_get_contents($geopluginURL_1));
                                   $country = $addrDetailsArr_1->country;
                                   $city = $addrDetailsArr_1->city;
                                   $login_area = $city.','.$country;
                                   $currentdatetime = date('Y-m-d H:i:s');
                                     $Last_Login = array(
                                        'user_id' => $data->employee_id,
                                        'login_area' => $login_area,
                                        'IP_address' => $ip_address,
                                        'login_platform' => $login_platform,
                                        'login_device' => $login_device,
                                        'date_time' => $currentdatetime,
                                        'status' => $status,
                                        
                                    );
                                    $done    = $this->db->insert('login_history', $Last_Login);
                                    /**====Get userlogin location End=====**/
                                redirect('Welcome/employeedashboard');
                            } else {
                                $this->session->set_flashdata('message', 'Invalid credentials');
                                redirect('Welcome');
                            }
                        }

                        if ($usertype == 'Consultant') {
                            $data   = $this->Authmodel->consultant_login($username);
                            // $data1n=$this->Authmodel->consultant_ot_login($username,$password);
                            $data1n = '';
                            if ($data || $data1n) {

                                if ($data && verifyHashedPassword($password, $data->password)) {
                                    if($data->is_active == 0) {
                                        $this->session->set_flashdata('message', 'Please verifiy your email to access the system');
                                        redirect($_SERVER['HTTP_REFERER']);
                                        exit;
                                    }
                                    /*=-=-=- check is OTP Valid Start =-=-=-*/
                                    $verified = $this->OTPVerification->get_auth_OTP(array(
                                        'model_name'    => 'consultant',
                                        'model_id'      => $data->consultant_id,
                                        'otp'           => $user_OTP,
                                    ));
                                    if (!$verified){
                                        $this->session->set_flashdata('message', 'Invalid OTP');
                                        redirect('Auth/verification');
                                    }
                                    /*=-=-=- check is OTP Valid end =-=-=-*/
                                    $data1 = array(
                                        'consultant_id' => $data->consultant_id,
                                        'username' => $data->username,
                                        'com_status' => $data->status,
                                        'plan_type' => $data->plan_type,
                                        'is_password_updated' => $data->isPasswordUptd,
                                        'user_type' => $usertype
                                    );
                                } else {
                                    $data1 = array(
                                        'consultant_id' => $data1n->consultant_id,
                                        'username' => $data1n->username,
                                        'com_status' => $data1n->status,
                                        'employee_id' => $data1n->employee_id,
                                        'is_password_updated' => $data->isPasswordUptd,
                                        'user_type' => $usertype
                                    );
                                }

                                if($data->is2FAEnabled == 1) {
                                    $this->session->set_userdata('temp_user', $data);
                                    redirect('auth/securityAuth');
                                }

                                   /**====Get userlogin location=====**/
                                if ($this->agent->is_browser())
                                {
                                   $agent = $this->agent->browser().' '.$this->agent->version();
                                }
                                elseif ($this->agent->is_robot())
                                {
                                        $agent = $this->agent->robot();
                                }
                                elseif ($this->agent->is_mobile())
                                {
                                        $agent = $this->agent->mobile();
                                }
                                else
                                {
                                        $agent = 'Unidentified User Agent';
                                }

                                $login_device = $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)
                                $login_platform = $this->agent->browser();
                                                                    
                               $ip_address=$_SERVER['REMOTE_ADDR'];
                                $user_id = $data->consultant_id;
                                //json_decode
                                ////////////////////////////////////////////////////////
                                $getLastIp = $this->Authmodel->last_login_ip($user_id,$ip_address);
                                $LastIp = $getLastIp->IP_address;
                                $user_last_id = $getLastIp->id;
                               // $status = "1";
                                if($ip_address == $LastIp){
                                    $status = "1";
                                }
                                else{
                                    $status = "2";
                                }
                                $geopluginURL_1 = 'https://api.ipfind.com?ip='.$ip_address.'&auth=af445bd7-b3b1-47d7-a3bc-fc92288a144c';
                                $addrDetailsArr_1 = json_decode(file_get_contents($geopluginURL_1));
                                $country = $addrDetailsArr_1->country;
                                $city = $addrDetailsArr_1->city;
                                $login_area = $city.','.$country;
                                $currentdatetime = date('Y-m-d H:i:s');
                                 $Last_Login = array(
                                    'user_id' => $data->consultant_id,
                                    'login_area' => $login_area,
                                    'IP_address' => $ip_address,
                                    'login_platform' => $login_platform,
                                    'login_device' => $login_device,
                                    'date_time' => $currentdatetime,
                                    'status' => $status,
                                    
                                );
                                $done    = $this->db->insert('login_history', $Last_Login);
                                /**====Get userlogin location End=====**/
                                

                                $data1['menu_title'] = 'home';
                                $this->session->set_userdata($data1);
                                redirect('Welcome/consultantdashboard');
                            } else {
                                $this->session->set_flashdata('message', 'Invalid credentials');
                                redirect('Welcome');
                            }
                        }
                    } else {
                        redirect('Welcome');
                    }
                }
            }else{

                $data['title']      = 'Login';
                $data['menu_title'] = 'Login';
                $data['otp_status'] = false;
                $this->form_validation->set_rules('username', 'Username', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required');
                $this->form_validation->set_rules('usertype', 'User Type', 'required', array('required' => 'Please Select %s.'));

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('login', $data);
                } else {
                    $usertype = $this->input->post('usertype');
                    $username = $this->input->post('username');
                    $password = $this->input->post('password');
                    $data['menu_title'] = 'home';

                    if (isset($usertype)) {
                        if ($usertype == 'admin') {
                            $data = $this->Authmodel->admin_login($username);
                            if ($data && verifyHashedPassword($password, $data->password)) {
                                $data1 = array(
                                    'admin_id' => $data->id,
                                    'user_type' => $usertype,
                                    'username' => $data->username,
                                    'is_password_updated' => $data->isPasswordUptd
                                );

                                $this->session->set_userdata($data1);
                                /**====Get userlogin location=====**/
                                if ($this->agent->is_browser())
                                {
                                   $agent = $this->agent->browser().' '.$this->agent->version();
                                }
                                elseif ($this->agent->is_robot())
                                {
                                        $agent = $this->agent->robot();
                                }
                                elseif ($this->agent->is_mobile())
                                {
                                        $agent = $this->agent->mobile();
                                }
                                else
                                {
                                        $agent = 'Unidentified User Agent';
                                }

                                $login_device = $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)
                                $login_platform = $this->agent->browser();
                                                                    
                                $ip_address=$_SERVER['REMOTE_ADDR'];
                                $user_id = $data->id;
                                //json_decode
                                ////////////////////////////////////////////////////////
                                $getLastIp = $this->Authmodel->last_login_ip($user_id,$ip_address);
                                $LastIp = $getLastIp->IP_address;
                                $user_last_id = $getLastIp->id;
                               // $status = "1";
                                if($ip_address == $LastIp){
                                    $status = "1";
                                }
                                else{
                                    $status = "2";
                                }
                                $geopluginURL_1 = 'https://api.ipfind.com?ip='.$ip_address.'&auth=af445bd7-b3b1-47d7-a3bc-fc92288a144c';
                                $addrDetailsArr_1 = json_decode(file_get_contents($geopluginURL_1));
                                $country = $addrDetailsArr_1->country;
                                $city = $addrDetailsArr_1->city;
                                $login_area = $city.','.$country;
                                $currentdatetime = date('Y-m-d H:i:s');
                                 $Last_Login = array(
                                    'user_id' => $data->id,
                                    'login_area' => $login_area,
                                    'IP_address' => $ip_address,
                                    'login_platform' => $login_platform,
                                    'login_device' => $login_device,
                                    'date_time' => $currentdatetime,
                                    'status' => $status,
                                    
                                );
                                $done    = $this->db->insert('login_history', $Last_Login);
                                /**====Get userlogin location End=====**/
                                
                                redirect('Welcome/admindashboard');
                            } else {
                                $this->session->set_flashdata('message', 'Invalid credentials');
                                redirect($_SERVER['HTTP_REFERER']);
                                exit;
                            }
                        }
                        if (($usertype == 'Lead Auditor') || ($usertype == 'Auditor') || ($usertype == 'Process Owner') || ($usertype == 'Auditee')) {
                            $data = $this->Authmodel->employee_login($username, $usertype);
                            if ($data && verifyHashedPassword($password, $data->password)) {
                                $data1 = array(
                                    'employee_id' => $data->employee_id,
                                    'username' => $data->username,
                                    'consultant_id' => $data->consultant_id,
                                    'user_type' => $usertype,
                                    'com_status' => $data->status,
                                    'is_password_updated' => $data->isPasswordUptd,
                                );

                                $this->session->set_userdata($data1);
                                    if ($this->agent->is_browser())
                                    {
                                            $agent = $this->agent->browser().' '.$this->agent->version();
                                    }
                                    elseif ($this->agent->is_robot())
                                    {
                                            $agent = $this->agent->robot();
                                    }
                                    elseif ($this->agent->is_mobile())
                                    {
                                            $agent = $this->agent->mobile();
                                    }
                                    else
                                    {
                                            $agent = 'Unidentified User Agent';
                                    }


                                    $login_device = $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)
                                    $login_platform = $this->agent->browser();
                                    //$getloc = json_decode(file_get_contents("http://ipinfo.io/"));
                                    //$record = geoip_record_by_name($ip);
                                   $ip_address=$_SERVER['REMOTE_ADDR'];
                                    $user_id = $data->employee_id;
                                    //json_decode
                                    ////////////////////////////////////////////////////////
                                    $getLastIp = $this->Authmodel->last_login_ip($user_id,$ip_address);
                                    $LastIp = $getLastIp->IP_address;
                                    $user_last_id = $getLastIp->id;
                                   // $status = "1";
                                    if($ip_address == $LastIp){
                                        $status = "1";
                                    }
                                    else{
                                        $status = "2";
                                    }
                                   $geopluginURL_1 = 'https://api.ipfind.com?ip='.$ip_address.'&auth=af445bd7-b3b1-47d7-a3bc-fc92288a144c';
                                   $addrDetailsArr_1 = json_decode(file_get_contents($geopluginURL_1));
                                   $country = $addrDetailsArr_1->country;
                                   $city = $addrDetailsArr_1->city;
                                   /*Get user ip address details with geoplugin.net*/
                                   //$geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip_address;
                                   //$addrDetailsArr = unserialize(file_get_contents($geopluginURL));
                                   /*Get City name by return array*/
                                   // $city = $addrDetailsArr['geoplugin_city']; echo "<br>";
                                   /*Get Country name by return array*/
                                   //$country = $addrDetailsArr['geoplugin_countryName'];
                                   $login_area = $city.','.$country;
                                   $currentdatetime = date('Y-m-d H:i:s');
                                     $Last_Login = array(
                                        'user_id' => $data->employee_id,
                                        'login_area' => $login_area,
                                        'IP_address' => $ip_address,
                                        'login_platform' => $login_platform,
                                        'login_device' => $login_device,
                                        'date_time' => $currentdatetime,
                                        'status' => $status,
                                        
                                    );
                                    $done    = $this->db->insert('login_history', $Last_Login);
                                    redirect('Welcome/employeedashboard');
                            } else {
                                if($data->status == 0){
                                   $this->session->set_flashdata('message', 'Your account is disabled by admin');
                                   redirect('Welcome/login');
                                }else{
                                   $this->session->set_flashdata('message', 'Invalid credentials');
                                   redirect('Welcome/login');
                                }
                            }
                        }

                        if ($usertype == 'Consultant') {
                            $data   = $this->Authmodel->consultant_login($username);
                            // $data1n=$this->Authmodel->consultant_ot_login($username,$password);
                            $data1n = '';
                            if ($data || $data1n) {

                                if ($data && verifyHashedPassword($password, $data->password)) {
                                    if($data->is_active == 0) {
                                        $this->session->set_flashdata('message', 'Please verifiy your email to access the system');
                                        redirect($_SERVER['HTTP_REFERER']);
                                        exit;
                                    }

                                    $data1 = array(
                                        'consultant_id' => $data->consultant_id,
                                        'username' => $data->username,
                                        'com_status' => $data->status,
                                        'plan_type' => $data->plan_type,
                                        'is_password_updated' => $data->isPasswordUptd,
                                        'user_type' => $usertype
                                    );
                                } else {
                                    $data1 = array(
                                        'consultant_id' => $data1n->consultant_id,
                                        'username' => $data1n->username,
                                        'com_status' => $data1n->status,
                                        'employee_id' => $data1n->employee_id,
                                        'is_password_updated' => $data->isPasswordUptd,
                                        'user_type' => $usertype
                                    );
                                }

                                if($data->is2FAEnabled == 1) {
                                    $this->session->set_userdata('temp_user', $data);
                                    redirect('auth/securityAuth');
                                }
                                /**====Get userlogin location=====**/
                                if ($this->agent->is_browser())
                                {
                                   $agent = $this->agent->browser().' '.$this->agent->version();
                                }
                                elseif ($this->agent->is_robot())
                                {
                                        $agent = $this->agent->robot();
                                }
                                elseif ($this->agent->is_mobile())
                                {
                                        $agent = $this->agent->mobile();
                                }
                                else
                                {
                                        $agent = 'Unidentified User Agent';
                                }

                                $login_device = $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)
                                $login_platform = $this->agent->browser();
                                                                    
                               $ip_address=$_SERVER['REMOTE_ADDR'];
                                $user_id = $data->consultant_id;
                                //json_decode
                                ////////////////////////////////////////////////////////
                                $getLastIp = $this->Authmodel->last_login_ip($user_id,$ip_address);
                                $LastIp = $getLastIp->IP_address;
                                $user_last_id = $getLastIp->id;
                               // $status = "1";
                                if($ip_address == $LastIp){
                                    $status = "1";
                                }
                                else{
                                    $status = "2";
                                }
                                $geopluginURL_1 = 'https://api.ipfind.com?ip='.$ip_address.'&auth=af445bd7-b3b1-47d7-a3bc-fc92288a144c';
                                $addrDetailsArr_1 = json_decode(file_get_contents($geopluginURL_1));
                                $country = $addrDetailsArr_1->country;
                                $city = $addrDetailsArr_1->city;
                                $login_area = $city.','.$country;
                                $currentdatetime = date('Y-m-d H:i:s');
                                 $Last_Login = array(
                                    'user_id' => $data->consultant_id,
                                    'login_area' => $login_area,
                                    'IP_address' => $ip_address,
                                    'login_platform' => $login_platform,
                                    'login_device' => $login_device,
                                    'date_time' => $currentdatetime,
                                    'status' => $status,
                                    
                                );
                                $done    = $this->db->insert('login_history', $Last_Login);
                                /**====Get userlogin location End=====**/
                                $data1['menu_title'] = 'home';
                                $this->session->set_userdata($data1);
                                redirect('Welcome/consultantdashboard');
                            } else {
                                $this->session->set_flashdata('message', 'Invalid credentials');
                                redirect('Welcome');
                            }
                        }
                    } else {
                        redirect('Welcome');
                    }
                }
            }
        }else{
            redirect('Welcome/login');
        }
    }
    public function verification(){
        if (!$this->settings->otp_verification){
            redirect('Auth/login');
        }
        /*=-=-=- check if user already logged in start =-=-=-*/
        $admin=$this->session->userdata('admin_id');
        $employee=$this->session->userdata('employee_id');
        $company=$this->session->userdata('company_id');
        if ($admin || $employee || $company) {
            if(isset($admin)) {
                redirect('Welcome/admindashboard');
            }
            if (isset($employee)) {
                redirect('Welcome/employeedashboard');
            }
            if (isset($company)) {
                redirect('Welcome/companydashboard');
            }
        }
        /*=-=-=- check if user already logged in end =-=-=-*/
        $this->load->model('Authmodel');
        $this->load->model('OTPVerification');
        $this->load->library('form_validation');
        $this->load->library('phone_RK');
        $data['title']      = 'Login';
        $data['menu_title'] = 'Login';
        $data['otp_status'] = true;
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('verification_method', 'Verification Method', array('required','in_list[email,phone]'));
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login', $data);
        }else{
            try{
                $usertype           = _decode($this->input->post('usertype'));
                $username           = _decode($this->input->post('username'));
                $password           = _decode($this->input->post('password'));
            }catch (Exception $e){
                $this->session->set_flashdata('message', 'Na Kakka Halii Na');
                redirect('Auth/verification');
                return;
            }

            $via_method         = $this->input->post('verification_method');
            $random_otp         = rand(1000,9999);
            $method_value       = '';

            $siteData['title']      = 'Verification';
            $siteData['username']   = _encode($username);
            $siteData['user_type']  = _encode($usertype);
            $siteData['password']   = _encode($password);
            $siteData['via_method'] = $via_method;
            $siteData['menu_title'] = 'Verification';

            if ($usertype == 'admin') {
                $data = $this->Authmodel->admin_login($username);
                if ($data && !empty($data->$via_method)  && verifyHashedPassword($password, $data->password)) {
                    $data1 = array(
                        'model_name'    => 'admin',
                        'model_id'      => $data->id,
                        'method_value'  => $data->$via_method,
                        'otp'           => $random_otp,
                        'is_verified'   => 0
                    );
                    $method_value   = $data->$via_method;
                    $result = $this->OTPVerification->set_auth_OTP($data1);
                } else {
                    $this->session->set_flashdata('message', 'Invalid credentials');
                    redirect($_SERVER['HTTP_REFERER']);
                    exit;
                }
            }
            elseif (($usertype == 'Lead Auditor') || ($usertype == 'Auditor') || ($usertype == 'Process Owner') || ($usertype == 'Auditee')) {
                $data = $this->Authmodel->employee_login($username, $usertype);
                $field_name = 'employee_'.$via_method;
                if ($data && !empty($data->$field_name)  && verifyHashedPassword($password, $data->password)) {
                    $data1 = array(
                        'model_name'    => 'employees',
                        'model_id'      => $data->employee_id,
                        'method_value'  => $data->$field_name,
                        'otp'           => $random_otp,
                        'is_verified'   => 0
                    );
                    $method_value   = $data->$field_name;
                    $result = $this->OTPVerification->set_auth_OTP($data1);
                } else {
                    $this->session->set_flashdata('message', 'Invalid credentials');
                    redirect('Welcome/login');
                }
            }
            elseif ($usertype == 'Consultant') {
                $data   = $this->Authmodel->consultant_login($username);
                // $data1n=$this->Authmodel->consultant_ot_login($username,$password);
                $data1n = '';
                if ($data || $data1n) {

                    if ($data && !empty($data->$via_method) && verifyHashedPassword($password, $data->password)) {
                        if($data->is_active == 0) {
                            $this->session->set_flashdata('message', 'Please verifiy your email to access the system');
                            redirect($_SERVER['HTTP_REFERER']);
                            exit;
                        }
                        $data1 = array(
                            'model_name'    => 'consultant',
                            'model_id'      => $data->consultant_id,
                            'method_value'  => $data->$via_method,
                            'otp'           => $random_otp,
                            'is_verified'   => 0
                        );
                        $method_value   = $data->$via_method;
                        $result = $this->OTPVerification->set_auth_OTP($data1);
                    } else {
                        $data1 = array(
                            'consultant_id' => $data1n->consultant_id,
                            'username' => $data1n->username,
                            'com_status' => $data1n->status,
                            'employee_id' => $data1n->employee_id,
                            'is_password_updated' => $data->isPasswordUptd,
                            'user_type' => $usertype
                        );
                    }

                    if($data->is2FAEnabled == 1) {
                        $this->session->set_userdata('temp_user', $data);
                        redirect('auth/securityAuth');
                    }

                    $data1['menu_title'] = 'home';
                } else {
                    $this->session->set_flashdata('message', 'Invalid credentials');
                    redirect('Welcome/login');
                }
            }
            else{
                $this->session->set_flashdata('message', 'Invalid credentials');
                redirect('Welcome/login');
            }
            /*=-=- check if method value is empty start =-=-*/
            $method_value = trim($method_value);
            if (empty($method_value)){
                $this->session->set_flashdata('message', 'Please try the other Verification Method');
                redirect('Welcome/login');
            }
            /*=-=- check if method value is empty end =-=-*/
            if ($via_method == 'phone'){
                $method_value = formatMobileNumber($method_value, true);
                /*=-=- check user mobile number valid start =-=-*/
                $phone_response = $this->phone_rk->checkPhoneNumber($method_value);
                if (!$phone_response['success']){
                    $this->session->set_flashdata('message', 'Your Mobile Number Is Not Valid Please Contact Admin');
                    redirect('Welcome/login');
                }
                /*=-=- send msg to user start =-=-*/
                $response = $this->twill_rk->sendMsq($method_value, "Your ".APP_NAME." Login OTP is $random_otp");
                if (!$response['success']){
                    $this->session->set_flashdata('message', $response['message']);
                    redirect('Welcome/login');
                }
                $this->load->view('OTP_verification', $siteData);
            }elseif ($via_method == 'email'){

                //-------------------send email----------------------
                $email_temp = $this->getEmailTemp('OTP-Verification-Email');
                $email_temp['message'] = str_replace("{OTP}", $random_otp, $email_temp['message']);
                $email_temp['message'] = str_replace("{APP_NAME}", APP_NAME, $email_temp['message']);
                $this->sendemail($method_value, $method_value, $email_temp['message'], $email_temp['subject']);

                $this->load->view('OTP_verification', $siteData);
            }else{
                $this->session->set_flashdata('message', 'We Dont Have Your Email or mobile Please Contact Admin');
                redirect('Welcome/login');
            }
        }
    }
    public function verifyMethod(){
        if (!$this->settings->otp_verification && empty($this->input->post('v'))){
            redirect('Auth/login');
        }
        /*=-=-=- check if user already logged in start =-=-=-*/
        $admin=$this->session->userdata('admin_id');
        $employee=$this->session->userdata('employee_id');
        $company=$this->session->userdata('company_id');
        if ($admin || $employee || $company) {
            if(isset($admin)) {
                redirect('Welcome/admindashboard');
            }
            if (isset($employee)) {
                redirect('Welcome/employeedashboard');
            }
            if (isset($company)) {
                redirect('Welcome/companydashboard');
            }
        }
        /*=-=-=- check if user already logged in end =-=-=-*/
        $this->load->model('Authmodel');
        $this->load->library('form_validation');
        $this->load->library('phone_RK');
        $data['title']      = 'Login';
        $data['menu_title'] = 'Login';
        $data['otp_status'] = true;
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');


        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login', $data);
        }else{

            $usertype           = $this->input->post('usertype');
            $username           = $this->input->post('username');
            $password           = $this->input->post('password');
            $siteVar['title']       = 'Choose Method';
            $siteVar['menu_title']  = 'choosemethod';
            $siteVar['username']    = _encode($username);
            $siteVar['password']    =_encode($password);
            $siteVar['user_type']   = _encode($usertype);
            $siteVar['otp_status']  = array('email' => true, 'phone' => true);

            if (isset($usertype)) {
                if ($usertype == 'admin') {
                    $data = $this->Authmodel->admin_login($username);
                    if ($data && verifyHashedPassword($password, $data->password)) {
                        $this->load->view('verification_method', $siteVar);
                    } else {
                        $this->session->set_flashdata('message', 'Invalid credentials');
                        redirect($_SERVER['HTTP_REFERER']);
                        exit;
                    }
                }
                if (($usertype == 'Lead Auditor') || ($usertype == 'Auditor') || ($usertype == 'Process Owner') || ($usertype == 'Auditee')) {
                    $data = $this->Authmodel->employee_login($username, $usertype);
                    if ($data && verifyHashedPassword($password, $data->password)) {
                        $this->load->view('verification_method', $siteVar);
                    } else {
                        $this->session->set_flashdata('message', 'Invalid credentials');
                        redirect('Welcome/login');
                    }
                }

                if ($usertype == 'Consultant') {
                    $data   = $this->Authmodel->consultant_login($username);
                    // $data1n=$this->Authmodel->consultant_ot_login($username,$password);
                    if ($data && verifyHashedPassword($password, $data->password)) {

                        if($data->is_active == 0) {
                            $this->session->set_flashdata('message', 'Please verifiy your email to access the system');
                            redirect($_SERVER['HTTP_REFERER']);
                            exit;
                        }
                        if (!$data->otp_status){
                            $siteVar['otp_status']['phone'] = false;
                            /*$data1 = array(
                                'consultant_id' => $data->consultant_id,
                                'username' => $data->username,
                                'com_status' => $data->status,
                                'plan_type' => $data->plan_type,
                                'is_password_updated' => $data->isPasswordUptd,
                                'user_type' => $usertype
                            );
                            $this->session->set_userdata($data1);
                            redirect('Welcome/consultantdashboard');*/
                        }
                        $this->load->view('verification_method', $siteVar);
                    } else {
                        $this->session->set_flashdata('message', 'Invalid credentials');
                        redirect('Welcome/login');
                    }
                }
            } else {
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
                //---------------------------------------------- send sms ----------------------------------------------
                if (!empty($phone) && $this->settings->otp_verification){
                    $phone = formatMobileNumber($phone, true);
                    /*=-=- check user mobile number valid start =-=-*/
                    $phone_response = $this->phone_rk->checkPhoneNumber($phone);
                    if ($phone_response['success']){
                        $message = "Hi {$username}".PHP_EOL;
                        $message.= "Congratulations you have signed up to ".APP_NAME." Quality Circle’s Process and Risk Based Software. The software is the first of its kind globally. qIt is a cloud based automated tool which does all the work for you. No more paper checklist. Grab your tablet or smartphone, input your data and the SMART platform does the rest, Including generating your reports.";
                        $this->twill_rk->sendMsq($phone,$message);
                    }
                }

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
	public function load_plan($id){
        $data['plan'] = $this->plan->getOne($id);
        $data['menu_title'] = 'payment';
        $data['title'] = 'Payment';
        $this->load->view('Register/reg_payment',$data);

    }

    public function add_purchase($plan_id = null, $payment_type = Null){
        $this->load->model('Companymodel');
        $consultant_id = $this->session->userdata('consultant_id');

        $consultant = $this->consultant->getOne($consultant_id);
        $plan = $this->plan->getOne($plan_id);

        $email = $this->db->where('is_admin', 1)->get('admin')->row()->email;

        //-------------------- send email-------------------------
        $email_temp = $this->getEmailTemp('User Sign up to Super Admin');
        $email_temp['message'] = str_replace("{Admin Name}",$consultant->username. " from ". $consultant->consultant_name,$email_temp['message']);
        $email_temp['message'] = str_replace("{COURSE NAME}",'phpstack-971964-3536769.cloudwaysapps.com',$email_temp['message']);
        $email_temp['message'] = str_replace("{Plan}",$plan->plan_name,$email_temp['message']);
        // $this->sendemail($email,'User sign up for subscription',$email_temp['message'],$email_temp['subject'],2);
        //--------------------------------------------------------
        //---------------------------------------------- send sms ----------------------------------------------
        if (!empty($phone) && $this->settings->otp_verification){
            $phone = formatMobileNumber($phone, true);
            /*=-=- check user mobile number valid start =-=-*/
            $phone_response = $this->phone_rk->checkPhoneNumber($phone);
            if ($phone_response['success']){
                $message = "Hello Super Admin".PHP_EOL;
                $message.= "{$consultant->username} from {$consultant->consultant_name} has signed up for {$plan->plan_name} of ".APP_NAME.".";
                $this->twill_rk->sendMsq($phone,$message);
            }
        }

        if($plan_id == '0'){
            redirect('Auth/reg_pay_plans');
        }else{
            if($plan_id == '1'){
                $this->trial();
            }else{
                $date = date('Y-m-d');
                $term_type = $plan->term_type;
                if($term_type == 0){
                    $expired = date('Y-m-d', strtotime($date. ' + 30 days'));
                }
                if($term_type == 1){
                    $expired = date('Y-m-d', strtotime($date. ' + 365 days'));
                }
                //start This is a case of payed
                $data = array('status' => 1,'plan_type' => 'real','plan_id' => $plan_id,'expired' => $expired);
                
                $invoice = array(
                    'status' => 'paid',
                    'admin_id' => $consultant_id,
                    'amount' => $plan->total_amount,
                    'plan_id' => $plan_id,
                    'tax_rate' => 0,
                    'create_date' => date('Y-m-d'),
                    'due_date' => $expired,
                    'invoice_num' => 'INV-'. rand(),
                    'payment_type'=> $payment_type
                );
                $invoice_id = $this->invoice->save($invoice);

                $item_data = [
                    'description' => $plan->plan_name. ' Membership Payment','invoice_id' => $invoice_id,'amount' => $plan->total_amount
                ];
				
                $this->db->insert('invoice_item',$item_data);

                $result = $this->Companymodel->update_company($data,$consultant_id);
                if($result){
                    /*require_once('./config.php');
                     $data['stripe'] = $stripe;*/
                    // $session_data = array('com_status' => 1,'plan_type' => 'real','plan_id' => $plan_id);
                    // $this->session->set_userdata($session_data);

                    // redirect('Auth/payment',$data);
                    $data = array("msg" => "Paid Successfully", "status" => TRUE);
                    echo json_encode($data);
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

            $company = $this->Companymodel->get_company($consultant_id);
            $plan = $this->plan->getOne($company->plan_id);

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
                $email_tempF['message'] = str_replace("{METHOD}", $data['forget_method'], $email_tempF['message']);
                $email_tempF['message'] = str_replace("{forgot_pass_link}",$recovery_link,$email_tempF['message']);
                // $this->sendemail($email,'Email Verification',$email_tempU['message'],$email_tempU['subject']);
                $result = $this->sendemail($email, $email, $email_tempF['message'], "Forgot {$data['forget_method']} Recovery");
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
            $consultant = $this->Companymodel->get_company($consultant_id);
            $plan = $this->plan->getOne($plan_id);

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
        $data['setting'] = $this->setting->getOne(1);
        $this->load->view('terms',$data);
    }
	
    public function securityAuth(){
        $data['menu_title'] = 'security';
        $this->load->view('2FAPage',$data);
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
            $password = getHashedPassword($this->input->post('password'));

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
