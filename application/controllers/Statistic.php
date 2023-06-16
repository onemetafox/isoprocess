
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Statistic extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
	}
    public function all_company_stat(){
    	$admin_id = $this->session->userdata('admin_id');
    	$consultant_id = $this->session->userdata('consultant_id');
    	if($admin_id){
	        $this->db->where('admin_id', $admin_id);
					$this->db->where('show_chart', 1);
        	$company = $this->db->get('company')->result();
    	}
    	if($consultant_id){
    		$this->db->where('consultant_id', $consultant_id);
				$this->db->where('show_chart', 1);
        	$company = $this->db->get('company')->result();
    	}

		$start_date = $this->input->post('start');
    	$end_date = $this->input->post('end');
    	$type = $this->input->post('type');
    	if($type == "1"){
    		$employee_cnd = 'spa';
    	}elseif ($type == "2") {
    		$employee_cnd = 'sme';
    	}
    	$open_status = 1;
    	$close_status = 0;

        $return['labels'] = array();
        $return['total'] = array();
        $return['open'] = array();
        $return['close'] = array();
        $return['past'] = array();
        if(isset($company) && !empty($company)) {
            foreach($company as $val) {
            	$this->db->where('cdate >', $start_date)->where('cdate <',$end_date);
            	$this->db->where('company_id', $val->id);
                $total_for_spas = $this->db->count_all_results('corrective_action');

                $this->db->where('cdate >', $start_date)->where('cdate <',$end_date);
                $this->db->where('company_id', $val->id);
                $this->db->where($employee_cnd.' >', '0');
                $this->db->where('status', $open_status);
                $open_for_spas = $this->db->count_all_results('corrective_action');

                $this->db->where('cdate >', $start_date)->where('cdate <',$end_date);
                $this->db->where('company_id', $val->id);
                $this->db->where($employee_cnd.' >', '0');
                $this->db->where('status', $close_status);
                $close_for_spas = $this->db->count_all_results('corrective_action');

                $this->db->where('cdate >', $start_date)->where('cdate <',$end_date);
				$this->db->where('company_id', $val->id);
                $this->db->where($employee_cnd.' >', '0');
                $this->db->where('date(corrective_action.when) <',date('Y-m-d'));
                $past_for_spas = $this->db->count_all_results('corrective_action');

                $return['labels'][] = $val->name;
                $return['total'][] = $total_for_spas;
                $return['open'][] = $open_for_spas;
                $return['close'][] = $close_for_spas;
                $return['past'][] = $past_for_spas;

                $item = array('label'=>$val->name,'total'=>$total_for_spas,'open'=>$open_for_spas,'close'=>$close_for_spas,'past'=>$past_for_spas);
                $return['real_data'][] = $item;
            }
        }
        echo json_encode($return);
    }
    public function employee_stat(){
    	$admin_id = $this->session->userdata('admin_id');
    	$consultant_id = $this->session->userdata('consultant_id');
		$start_date = $this->input->post('start');
    	$end_date = $this->input->post('end');
    	$company_id = $this->input->post('company_id');
    	$type = $this->input->post('type');
        $owner_filter = $this->input->post('owner_filter');
    	if($type == "1"){
    		$process_owner = 1;
    	}elseif($type == "2"){
    		$process_owner = 2;
    	}
    	$open_status = 1;
    	$close_status = 0;

        $return['labels'] = array();
        $return['total'] = array();
        $return['open'] = array();
        $return['close'] = array();
        $return['past'] = array();

        $this->db->where('role_id', $process_owner);
        $this->db->where('company_id', $company_id);
        if(!empty($owner_filter))
            $this->db->where('employee_id', $owner_filter);
        $spas = $this->db->get('employees')->result();
        if(isset($spas) && !empty($spas)) {
            foreach($spas as $spa) {
            	$this->db->where('corrective_action.cdate >', $start_date)->where('corrective_action.cdate <',$end_date);
                if($type == 1)
                    $this->db->where('spa', $spa->employee_id);
                else
                    $this->db->where('sme', $spa->employee_id);
                $this->db->join('fssc_requirements fr','corrective_action.fssc_id = fr.f_id');
                $this->db->join('standard s','fr.standard = s.s_id');
                $this->db->where('s.active',1);
                $total_for_spas = $this->db->count_all_results('corrective_action');

                if($type == 1)
                    $this->db->where('spa', $spa->employee_id);
                else
                    $this->db->where('sme', $spa->employee_id);
                $this->db->where('status', $open_status);
                $this->db->join('fssc_requirements fr','corrective_action.fssc_id = fr.f_id');
                $this->db->join('standard s','fr.standard = s.s_id');
                $this->db->where('s.active',1);
                $this->db->where('corrective_action.cdate >', $start_date)->where('corrective_action.cdate <',$end_date);

                $open_for_spas = $this->db->count_all_results('corrective_action');
                
                if($type == 1)
                    $this->db->where('spa', $spa->employee_id);
                else
                    $this->db->where('sme', $spa->employee_id);

                $this->db->where('status', $close_status);
                $this->db->where('corrective_action.cdate >', $start_date)->where('corrective_action.cdate <',$end_date);
                $this->db->join('fssc_requirements fr','corrective_action.fssc_id = fr.f_id');
                $this->db->join('standard s','fr.standard = s.s_id');
                $this->db->where('s.active',1);

                $close_for_spas = $this->db->count_all_results('corrective_action');

                if($type == 1)
                    $this->db->where('spa', $spa->employee_id);
                else
                    $this->db->where('sme', $spa->employee_id);

                $this->db->where('date(corrective_action.when) <',date('Y-m-d'));
                $this->db->where('corrective_action.cdate >', $start_date)->where('corrective_action.cdate <',$end_date);
                $this->db->join('fssc_requirements fr','corrective_action.fssc_id = fr.f_id');
                $this->db->join('standard s','fr.standard = s.s_id');
                $this->db->where('s.active',1);

                $past_for_spas = $this->db->count_all_results('corrective_action');

                $return['labels'][] = $spa->employee_name;
                $return['total'][] = $total_for_spas;
                $return['open'][] = $open_for_spas;
                $return['close'][] = $close_for_spas;
                $return['past'][] = $past_for_spas;
            }
        }

        if(empty($owner_filter))
        {
            $this->db->where('role_id', $process_owner);
            $this->db->where('company_id', $company_id);
            $return['process_owners'] = $this->db->get('employees')->result_array();
        }
        else
        {
            $return['process_owners'] = array();
        }
        echo json_encode($return);
    }
    public function employee_one_stat(){
    	$employee_id = $this->session->userdata('employee_id');
		$company_id = $this->session->userdata('company_id');

		$start_date = $this->input->post('start');
    	$end_date = $this->input->post('end');
    	$open_status = 1;
    	$close_status = 0;

        $return['labels'] = array();
        $return['total'] = array();
        $return['open'] = array();
        $return['close'] = array();
        $return['past'] = array();

    	$this->db->where('corrective_action.cdate >', $start_date)->where('corrective_action.cdate <',$end_date);
        $this->db->group_start();
        $this->db->where('spa', $employee_id);
        $this->db->or_where('sme', $employee_id);
        $this->db->group_end();
        $this->db->join('fssc_requirements fr','corrective_action.fssc_id = fr.f_id');
        $this->db->join('standard s','fr.standard = s.s_id');
        $this->db->where('s.active',1);
        $total_for_spas = $this->db->count_all_results('corrective_action');

        
        $this->db->group_start();
        $this->db->where('spa', $employee_id);
        $this->db->or_where('sme', $employee_id);
        $this->db->group_end();
        $this->db->where('status', $open_status);
        $this->db->where('corrective_action.cdate >', $start_date)->where('corrective_action.cdate <',$end_date);
        $this->db->join('fssc_requirements fr','corrective_action.fssc_id = fr.f_id');
        $this->db->join('standard s','fr.standard = s.s_id');
        $this->db->where('s.active',1);
        $open_for_spas = $this->db->count_all_results('corrective_action');

        $this->db->group_start();
        $this->db->where('spa', $employee_id);
        $this->db->or_where('sme', $employee_id);
        $this->db->group_end();

        $this->db->where('status', $close_status);
        $this->db->where('corrective_action.cdate >', $start_date)->where('corrective_action.cdate <',$end_date);
        $this->db->join('fssc_requirements fr','corrective_action.fssc_id = fr.f_id');
        $this->db->join('standard s','fr.standard = s.s_id');
        $this->db->where('s.active',1);
        $close_for_spas = $this->db->count_all_results('corrective_action');

        $this->db->group_start();
        $this->db->where('spa', $employee_id);
        $this->db->or_where('sme', $employee_id);
        $this->db->group_end();

        $this->db->where('date(corrective_action.when) <',date('Y-m-d'));
        $this->db->where('corrective_action.cdate >', $start_date)->where('corrective_action.cdate <',$end_date);
        $this->db->join('fssc_requirements fr','corrective_action.fssc_id = fr.f_id');
        $this->db->join('standard s','fr.standard = s.s_id');
        $this->db->where('s.active',1);
        $past_for_spas = $this->db->count_all_results('corrective_action');


        $return['statistic'][] = $total_for_spas;
        $return['statistic'][] = $open_for_spas;
        $return['statistic'][] = $close_for_spas;
        $return['statistic'][] = $past_for_spas;
        echo json_encode($return);
    }

    public function each_company_stat(){
        $admin_id = $this->session->userdata('admin_id');
        $consultant_id = $this->session->userdata('consultant_id');
        $start_date = $this->input->post('start');
        $end_date = $this->input->post('end');
        $company_id = $this->input->post('company_id');
        $type = $this->input->post('type');
        if($type == "1"){
            $employee_cnd = 'spa';
        }elseif ($type == "2") {
            $employee_cnd = 'sme';
        }
        $open_status = 1;
        $close_status = 0;
        $this->db->where('cdate >', $start_date)->where('cdate <',$end_date);
        $this->db->where('company_id', $company_id);
        $total_for_spas = $this->db->count_all_results('corrective_action');

        $this->db->where('cdate >', $start_date)->where('cdate <',$end_date);
        $this->db->where('company_id', $company_id);
        $this->db->where($employee_cnd.' >', '0');
        $this->db->where('status', $open_status);
        $open_for_spas = $this->db->count_all_results('corrective_action');

        $this->db->where('cdate >', $start_date)->where('cdate <',$end_date);
        $this->db->where('company_id', $company_id);
        $this->db->where($employee_cnd.' >', '0');
        $this->db->where('status', $close_status);
        $close_for_spas = $this->db->count_all_results('corrective_action');

        $this->db->where('cdate >', $start_date)->where('cdate <',$end_date);
        $this->db->where('company_id', $company_id);
        $this->db->where($employee_cnd.' >', '0');
        $this->db->where('date(corrective_action.when) <',date('Y-m-d'));
        $past_for_spas = $this->db->count_all_results('corrective_action');

        $return['statistic'][] = $total_for_spas;
        $return['statistic'][] = $open_for_spas;
        $return['statistic'][] = $close_for_spas;
        $return['statistic'][] = $past_for_spas;
        echo json_encode($return);
    }
}
