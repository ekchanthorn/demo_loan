<?php
require_once ("person_controller.php");
class Pawns extends Person_controller
{
	function __construct()
	{
		parent::__construct('pawns');
	}
	
	
	function index($offset=0)
	{
            $params = $this->session->userdata('pawns_search_data') ? $this->session->userdata('pawns_search_data') : array('offset' => 0, 'order_col' => 'pawn_id', 'order_dir' => 'desc', 'search' => FALSE);
            if ($offset!=$params['offset'])
            {
               //redirect('pawns/index/'.$params['offset']);
                $this->clear_state();
            }
            $this->check_action_permission('search');
            $config['base_url'] = site_url('pawns/sorting');
            $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
            $data['controller_name']=strtolower(get_class());
            $data['per_page'] = $config['per_page'];
            $data['search'] = $params['search'] ? $params['search'] : "";
            if ($data['search'])
            {
                    $config['total_rows'] = $this->Pawn->search_count_all($data['search']);
                    $table_data = $this->Pawn->search($data['search'],$data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
            }
            else
            {
                    $config['total_rows'] = $this->Pawn->count_all();
                    $table_data = $this->Pawn->get_all($data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
            }
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $data['order_col'] = $params['order_col'];
            $data['order_dir'] = $params['order_dir'];

            $data['manage_table']=get_pawns_manage_table($table_data,$this);
            $data['total_rows'] = $config['total_rows'];
            $this->load->view('people/manage',$data);
	}

	function sorting()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search') ? $this->input->post('search') : "";
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'pawn_id';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'desc';

		$pawns_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("pawns_search_data",$pawns_search_data);
		
		if ($search)
		{
			$config['total_rows'] = $this->Pawn->search_count_all($search);
			$table_data = $this->Pawn->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'pawn_id' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'desc');
		}
		else
		{
			$config['total_rows'] = $this->Pawn->count_all();
			$table_data = $this->Pawn->get_all($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'pawn_id' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'desc');
		}
		$config['base_url'] = site_url('pawns/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_pawns_manage_table_data_rows($table_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));	
		
	}
	
	/*
	Returns customer table data rows. This will be called with AJAX.
	*/
	function search()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$pawns_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("pawns_search_data",$pawns_search_data);
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Pawn->search($search,$per_page,$offset, $order_col ,$order_dir);
		$config['base_url'] = site_url('pawns/search');
		$config['total_rows'] = $this->Pawn->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $this->Pawn->search_count_all($search);
		$data['manage_table']=get_pawns_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Pawn->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	/*
	Loads the customer edit form
	*/
	function view($pawn_id=-1,$redirect_code=0)
	{
		$this->check_action_permission('add_update');
		
		$data['all_people'] = $this->Pawn->get_all_people();
                $data['rate_setting']= $this->Pawn->get_rate_setting();
		$data['controller_name']=strtolower(get_class());
		$data['pawn_info']=$this->Pawn->get_info($pawn_id);
		$data['redirect_code']=$redirect_code;
		$this->load->view("pawns/form",$data);
	}

	function view_modal($pawn_id)
	{
		$data['customer_info'] = $this->Pawn->get_info($pawn_id);;
		$this->load->view("pawns/customer_modal", $data);
	}
	
	function account_number_exists()
	{
		if($this->Pawn->account_number_exists($this->input->post('account_number')))
		echo 'false';
		else
		echo 'true';
		
	}

	function clear_state() {
		$this->session->unset_userdata('pawns_search_data');
		redirect('pawns');
	}
	/*
	Inserts/updates a customer
	*/
	function save($pawn_id=-1) {
           $this->check_action_permission('add_update');
           $data_pawn = array(
            'person_id'=> $this->input->post('person_id'),
            'amount'=>$this->input->post('amount'),
            'pay_type'=>$this->input->post('pay_type'),
            'is_loan'=>$this->input->post('is_loan')?$this->input->post('is_loan'):0,
            'rate'=>$this->input->post('rate'),
            'duration'=>$this->input->post('duration'),
            'start_date'=>date('Y-m-d', strtotime($this->input->post('start_date'))),
            'end_date'=>date('Y-m-d', strtotime($this->input->post('end_date'))),
            'currency'=>$this->input->post('currency'),
            'product_name'=>$this->input->post('product_name'),
            'comments'=>$this->input->post('comments'));
           //insert to database
            if($pawn_id==-1)
            {
               $data_save = $this->Pawn->save($data_pawn);
               if($data_save){
                    mkdir('pawn_attachment/'.$data_save.'/', 0777, true);
                    $success_message = lang('pawns_successful_adding').' '.to_currency($data_pawn['amount']);
                    echo json_encode(array('success'=>true,'message'=> $success_message,'pawn_id'=>$data_save,'redirect_code'=>1));
               }
            }else{
                $pawn_info = $this->Pawn->get_info($pawn_id);
                if($data_pawn['amount']==$pawn_info->amount && $data_pawn['pay_type']==$pawn_info->pay_type && $data_pawn['is_loan']==$pawn_info->is_loan && $data_pawn['rate']==$pawn_info->rate && $data_pawn['duration']==$pawn_info->duration && $data_pawn['start_date']==date('Y-m-d',strtotime($pawn_info->start_date)) && $data_pawn['end_date']==date('Y-m-d',strtotime($pawn_info->end_date))){
                    $this->Pawn->update_pawn($pawn_id,$data_pawn);  
                    $success_message = lang('pawns_successful_updating').' '.to_currency($data_pawn['amount']);
                    $this->session->set_flashdata('manage_success_message', $success_message);
                    echo json_encode(array('success'=>true,'message'=>$success_message,'pawn_id'=>$pawn_id,'redirect_code'=>2));
                }else{
                    $this->Pawn->update_pawn_and_schedule($pawn_id,$data_pawn);  
                    $success_message = lang('pawns_successful_updating').' '.to_currency($data_pawn['amount']);
                    $this->session->set_flashdata('manage_success_message', $success_message);
                    echo json_encode(array('success'=>true,'message'=>$success_message,'pawn_id'=>$pawn_id,'redirect_code'=>2));
                }
            }
            
            if (isset($_FILES['upload']['name'])) {
                $count = count($_FILES['upload']['name']);
                $uploads = $_FILES['upload'];
                for ($i = 0; $i <$count; $i++) {
                    if ($uploads['error'][$i] == 0) {
                        if($pawn_id==-1){
                            move_uploaded_file($uploads['tmp_name'][$i],'pawn_attachment/'.$data_save.'/' . $uploads['name'][$i]);
                        }else{
                             move_uploaded_file($uploads['tmp_name'][$i],'pawn_attachment/'.$pawn_id.'/' . $uploads['name'][$i]);
                        }
                        $data['file_name']=$uploads['name'][$i];
                        if($pawn_id==-1){
                            $data['pawn_id']=$data_save;
                        }else{
                            $data['pawn_id']=$pawn_id;
                        }
                        $this->Pawn->insert_attament($data);
                    }
                }
            }
        }
	
	/*
	This deletes customers from the customers table
	*/
	function delete()
	{
		$this->check_action_permission('delete');
		$pawn_id_to_delete=$this->input->post('ids')?$this->input->post('ids'):$this->uri->segment(3);
		
		if($this->Pawn->delete_list($pawn_id_to_delete))
		{
			$this->uri->segment(3)?redirect('pawns'):'';
                        echo json_encode(array('success'=>true,'message'=>lang('pawns_successful_deleted').' '.
			count($pawn_id_to_delete).' '.lang('pawns_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('pawns_cannot_be_deleted')));
		}
	}
	
	function _excel_get_header_row()
	{
		return array(lang('common_order_no'),lang('common_first_name'),lang('common_last_name'),lang('pawns_amount'),lang('pawns_rate'),lang('pawns_duration'), lang('pawns_from'),lang('pawns_to'),lang('pawns_currency'),lang('pawns_product_name'),lang('common_comments'));
	}
		
	function excel()
	{
		$this->load->helper('report');
		$header_row = $this->_excel_get_header_row();
		
		$content = array_to_spreadsheet(array($header_row));
		force_download('import_customers.'.($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
	}
	
	function excel_import()
	{
		$this->check_action_permission('add_update');
		$this->load->view("pawns/excel_import", null);
	}
	
	function check_duplicate() {
		echo json_encode(array('duplicate'=>$this->Pawn->check_duplicate($this->input->post('term'))));
	}
	/* added for excel expert */
	function excel_export() {
		$data = $this->Pawn->get_all($this->Pawn->count_all())->result_object();
		$this->load->helper('report');
		$rows = array();
		
		$header_row = $this->_excel_get_header_row();
		$header_row[] = lang('pawns_id');
		$rows[] = $header_row;
		$i=1;
		foreach ($data as $r) {
			$row = array(
				$i,
				$r->first_name,
				$r->last_name,
				$r->amount,
				$r->rate,
				$r->duration,
				$r->start_date,
				$r->end_date,
				$r->currency,
				$r->product_name,
				$r->comments,
				$r->pawn_id
			);
			$rows[] = $row;
                        $i++;
		}
		
		$content = array_to_spreadsheet($rows);
		force_download('pawns_export.'.($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
		exit;
	}

		
	function cleanup() {
		$this->Pawn->cleanup();
		echo json_encode(array('success'=>true,'message'=>lang('customers_cleanup_sucessful')));
	}
		
	function pay_now($pawn_id) {
		$this->load->library('sale_lib');
                $this->sale_lib->clear_all();
		$this->sale_lib->set_customer($pawn_id);
		$this->sale_lib->set_mode('store_account_payment');
		$store_account_payment_item_id = $this->Item->create_or_update_store_account_item();
		$this->sale_lib->add_item($store_account_payment_item_id,1);
		redirect('sales');
	}
        
        //function for view setting of pawn
        function setting(){
            $data['query']= $this->Pawn->get_rate_setting();    
            $this->load->view('pawns/setting',$data);
        }
        
        //function update pawn
        function update_rate(){
        $data['rate_one']=$this->input->post('condition_one');
        $data['rate_two']=$this->input->post('condition_two');
        $this->form_validation->set_rules('condition_one', 'This', 'numeric|greater_than[0]|less_than[100]');
        $this->form_validation->set_rules('condition_two', 'This', 'numeric|greater_than[0]|less_than[100]');
        if ($this->form_validation->run() == FALSE)
        {
            $data['query']= $this->Pawn->get_rate_setting();      
            $this->load->view('pawns/setting',$data);
        }else{  
            $this->db->where('id',1);
            $this->db->update('laon_setting',$data);
            $data['query']= $this->Pawn->get_rate_setting(); 
            $data['success'] =lang('pawns_success_save'); 
            $this->load->view('pawns/setting',$data);
            
        }
        
    }
    
    //view schedule
    function view_schedule($pawn_id){
        $data['schedule']=$this->Pawn->get_schedule($pawn_id);
        $data['pawn_info']=$this->Pawn->get_info($pawn_id);
        $data['location']=$this->Location->get_info(1);
        if($this->Pawn->get_pay_all_before_the_end($pawn_id)->num_rows()==1){
            $data['notify'] = lang('pawns_already_pay_before_date').'<br/>'.lang('pawns_print_amount').' '.to_currency($this->Pawn->get_pay_all_before_the_end($pawn_id)->row()->pay_total).'<br/> '.lang('pawns_print_date').' '.date('D, d-M-Y',  strtotime($this->Pawn->get_pay_all_before_the_end($pawn_id)->row()->pay_date));
        }else if($this->Pawn->get_pay_all_at_the_end($pawn_id)->num_rows()==$data['pawn_info']->duration){
            $data['notify'] = lang('pawns_already_pay_on_date');
        }else{
            $data['notify'] =='';
        }
        $data['attachment'] = $this->Pawn->get_attachment($pawn_id);
        $this->load->view('pawns/schedule',$data);
    }
    //view attachment 
    function add_attachment($pawn_id){
        $data['attachment'] = $this->Pawn->get_attachment($pawn_id);
        $this->load->view('pawns/attachment',$data);
        if (isset($_FILES['upload']['name'])) {
                $count = count($_FILES['upload']['name']);
                $uploads = $_FILES['upload'];
                for ($i = 0; $i <$count; $i++) {
                    if ($uploads['error'][$i] == 0) {
                        move_uploaded_file($uploads['tmp_name'][$i],'pawn_attachment/'.$pawn_id.'/' . $uploads['name'][$i]);
                        $data_file['file_name']=$uploads['name'][$i];
                        $data_file['pawn_id']=$pawn_id;
                        $this->Pawn->insert_attament($data_file);
                    }
                }
                redirect('pawns/add_attachment/'.$pawn_id);
        }
    }
    //add attachment
    function delete_attachment(){
        $id = $this->input->post('ids');
        $this->db->where('id',$id);
        $this->db->update('product_pawn_attachment',array('deleted'=>1));
    }
    
    function add_comment(){
        $id = $this->input->post('id');
        $comment = $this->input->post('comment');
        $this->Pawn->add_comment($id, $comment);
    }
    
    //for paid principle 
    function pay_for_princ(){
        $id = $this->input->post('id');
        $pawn_id= $this->input->post('pawn_id');
        $data['paid_princ']= $this->input->post('value');
        $this->Pawn->add_prince($id, $data);
        $this->re_paid_prince_schedule($id, $pawn_id, $data['paid_princ']);
    }
    //regenerate schedule for borrow more 
    function re_paid_prince_schedule($id, $pawn_id, $paid_prince){
        $result = $this->Pawn->get_id_biger_than($id,$pawn_id);
        $this_id_info = $this->Pawn->get_this_info_for_current_id($id,$pawn_id);
        $query=$this->Pawn->count_data_biger_than($id,$pawn_id);
        $row= $query->row_of_next_id;
        $next_id = $result->id;
        //$next_balance = $result->pay_principle;
        //$amount =$this->Pawn->get_info($pawn_id)->amount;
        //$next_rate = $result->pay_interest;
        $rate = $this->Pawn->get_info($pawn_id)->rate;
        $duration = $this->Pawn->get_info($pawn_id)->duration;
        //calculate pay_principle when we paid the prince back
        $amount_after_calc = $this_id_info->pay_principle-$paid_prince;
        for($i=0; $i<$row;$i++){
            $row_num = $this->Pawn->get_detail_as_id($next_id)->key;
            $data['pay_principle'] = $amount_after_calc;
             if($duration==$row_num){
                $data['pay_rate'] = 0;
                $data['pay_total'] = $amount_after_calc;
             }else{
                $data['pay_rate']= (($rate/100)*($amount_after_calc));
                $data['pay_total']= ($amount_after_calc)+(($rate/100)*($amount_after_calc)); 
             }
            $this->Pawn->update_re_sechule($next_id,$data);
            ++$next_id;
        }
    }
    
    //for add more borrow in amount 
    function add_borrow_more(){
        $id = $this->input->post('id');
        $pawn_id= $this->input->post('pawn_id');
        $data['borrow_more']= $this->input->post('value');
        $this->Pawn->add_prince($id, $data);
        $this->re_borrow_more_schedule($id, $pawn_id, $data['borrow_more']);
    }
    //regenerate schedule for paid prince 
    function re_borrow_more_schedule($id, $pawn_id, $borrow_more){
        $result = $this->Pawn->get_id_biger_than($id,$pawn_id);
        $this_id_info = $this->Pawn->get_this_info_for_current_id($id,$pawn_id);
        $query=$this->Pawn->count_data_biger_than($id,$pawn_id);
        $row= $query->row_of_next_id;
        $next_id = $result->id;
        //$next_balance = $result->pay_principle;
        //$amount =$this->Pawn->get_info($pawn_id)->amount;
        //$next_rate = $result->pay_interest;
        $rate = $this->Pawn->get_info($pawn_id)->rate;
        $duration = $this->Pawn->get_info($pawn_id)->duration;
        //calculate pay_principle when we paid the prince back
        $amount_after_calc = $this_id_info->pay_principle+$borrow_more;
        for($i=0; $i<$row;$i++){
            $row_num = $this->Pawn->get_detail_as_id($next_id)->key;
            if($duration==$row_num){
                $data['pay_total']= $amount_after_calc;
                $data['pay_rate']= 0;
            }else{
                $data['pay_rate']= (($rate/100)*($amount_after_calc));
                $data['pay_total']= ($amount_after_calc)+(($rate/100)*($amount_after_calc));
            }
            $data['pay_principle'] = $amount_after_calc;
            $this->Pawn->update_re_sechule($next_id,$data);
            ++$next_id;
        }
    }
    
    
    //add late 
    function add_late(){
        $id = $this->input->post('id');
        $num_day = $this->input->post('num_day');
        if($num_day<=3){
            $pay_fine =$num_day;
        }else{
            $pay_fine = $num_day*2;
        }
        $this->Pawn->add_late($id, $num_day, $pay_fine);
    }
    //allow to fine for late
    function allow_fine(){
        $id= $this->input->post('id');
        $check= $this->input->post('check_fine');
        $this->Pawn->allow_fine($id,$check);
    }
    //change status of pawn
    function status(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $pawn_id = $this->input->post('pawn_id');
        $is_loan = $this->Pawn->get_info($pawn_id)->is_loan;
        $paid_prince = $this->Pawn->get_info($pawn_id)->amount/$this->Pawn->get_info($pawn_id)->duration;
        $not_yet_paid = $this->Pawn->get_sum_principle_new($pawn_id)->paid_princ;
        $key = $this->Pawn->get_detail_as_id($id)->key;
        //update paid
        if($status==1){
            $data['paid_rate']= $this->Pawn->get_detail_as_id($id)->pay_rate;
            $data['status']= $status;
            $this->Pawn->update_paid($id,$data);
        //update not pay    
        }else if($status==0){
            if($is_loan==1 && $key ==1){
               $data['paid_rate'] =0; 
               $data['paid_princ'] =0; 
            }else if($is_loan==1 && $key !=1){
               $data['paid_rate'] =0; 
               $data['paid_princ'] =$paid_prince;  
            }
            $data['status']= 0;
            $this->Pawn->update_paid($id,$data);
             if($is_loan==1 && $key ==1){
               $data['paid_princ'] =$paid_prince;    
             }
            $this->Pawn->update_not_pay($id,$pawn_id,$data); 
        //update paid all
        }else if($status==2){
            if($is_loan==1){
               $data['paid_princ'] = $not_yet_paid;  
            }else{
               $data['paid_princ'] = $this->Pawn->get_detail_as_id($id)->pay_principle;
            }
            $data['paid_rate'] = $this->Pawn->get_detail_as_id($id)->pay_rate;
            $data['status']= $status;
            $this->Pawn->update_paid($id,$data);
            $this->Pawn->update_pay_all($id,$pawn_id);
        }
    }
        
        
}
?>