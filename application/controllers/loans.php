<?php
require_once ("person_controller.php");
class Loans extends Person_controller
{
	function __construct()
	{
		parent::__construct('loans');
	}
	
	
	function index($offset=0)
	{
            $params = $this->session->userdata('loans_search_data') ? $this->session->userdata('loans_search_data') : array('offset' => 0, 'order_col' => 'loan_id', 'order_dir' => 'desc', 'search' => FALSE);
            if ($offset!=$params['offset'])
            {
               //redirect('loans/index/'.$params['offset']);
                $this->clear_state();
            }
            $this->check_action_permission('search');
            $config['base_url'] = site_url('loans/sorting');
            $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
            $data['controller_name']=strtolower(get_class());
            $data['per_page'] = $config['per_page'];
            $data['search'] = $params['search'] ? $params['search'] : "";
            if ($data['search'])
            {
                    $config['total_rows'] = $this->Loan->search_count_all($data['search']);
                    $table_data = $this->Loan->search($data['search'],$data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
            }
            else
            {
                    $config['total_rows'] = $this->Loan->count_all();
                    $table_data = $this->Loan->get_all($data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
            }
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $data['order_col'] = $params['order_col'];
            $data['order_dir'] = $params['order_dir'];

            $data['manage_table']=get_loans_manage_table($table_data,$this);
            $data['total_rows'] = $config['total_rows'];
            $this->load->view('people/manage',$data);
	}

	function sorting()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search') ? $this->input->post('search') : "";
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'loan_id';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'desc';

		$loans_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("loans_search_data",$loans_search_data);
		
		if ($search)
		{
			$config['total_rows'] = $this->Loan->search_count_all($search);
			$table_data = $this->Loan->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'loan_id' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'desc');
		}
		else
		{
			$config['total_rows'] = $this->Loan->count_all();
			$table_data = $this->Loan->get_all($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'loan_id' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'desc');
		}
		$config['base_url'] = site_url('loans/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_loans_manage_table_data_rows($table_data,$this);
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

		$loans_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("loans_search_data",$loans_search_data);
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Loan->search($search,$per_page,$offset, $order_col ,$order_dir);
		$config['base_url'] = site_url('loans/search');
		$config['total_rows'] = $this->Loan->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $this->Loan->search_count_all($search);
		$data['manage_table']=get_loans_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Loan->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	/*
	Loads the customer edit form
	*/
	function view($loan_id=-1,$redirect_code=0)
	{
		$this->check_action_permission('add_update');
		
		$data['all_people'] = $this->Loan->get_all_people();
                $data['rate_setting']= $this->Loan->get_rate_setting();
		$data['controller_name']=strtolower(get_class());
		$data['loan_info']=$this->Loan->get_info($loan_id);
		$data['redirect_code']=$redirect_code;
		$this->load->view("loans/form",$data);
	}

	function view_modal($loan_id)
	{
		$data['customer_info'] = $this->Loan->get_info($loan_id);;
		$this->load->view("loans/customer_modal", $data);
	}
	
	function account_number_exists()
	{
		if($this->Loan->account_number_exists($this->input->post('account_number')))
		echo 'false';
		else
		echo 'true';
		
	}

	function clear_state()
	{
		$this->session->unset_userdata('loans_search_data');
		redirect('loans');
	}
	/*
	Inserts/updates a customer
	*/
	function save($loan_id=-1)
	{
           $this->check_action_permission('add_update');
           $data_loan = array('person_id'=> $this->input->post('person_id'),
            'amount'=>$this->input->post('amount'),
            'deposit'=>$this->input->post('deposit')?$this->input->post('deposit'):0,
            'rate'=>$this->input->post('rate'),
            'duration'=>$this->input->post('duration'),
            'borrow_date'=> date('Y-m-d', strtotime($this->input->post('borrow_date'))),
            'start_date'=>date('Y-m-d', strtotime($this->input->post('start_date'))),
            'end_date'=>date('Y-m-d', strtotime($this->input->post('end_date'))),
            'currency'=>$this->input->post('currency'),
            'product_name'=>$this->input->post('product_name'),
            'comments'=>$this->input->post('comments'));
           //insert to database
            if($loan_id==-1)
            {
               $data_save = $this->Loan->save($data_loan);
               if($data_save){
                    mkdir('borrow_attachment/'.$data_save.'/', 0777, true);
                    $success_message = lang('loans_successful_adding').' '.to_currency($data_loan['amount']);
                    echo json_encode(array('success'=>true,'message'=> $success_message,'loan_id'=>$data_save,'redirect_code'=>1));
               }
            }else{
                $loan_info = $this->Loan->get_info($loan_id);
                if($data_loan['amount']==$loan_info->amount && $data_loan['deposit']==$loan_info->deposit && $data_loan['rate']==$loan_info->rate && $data_loan['duration']==$loan_info->duration && $data_loan['borrow_date']==date('Y-m-d',strtotime($loan_info->borrow_date)) && $data_loan['start_date']== date('Y-m-d',strtotime($loan_info->start_date)) ){
                    $this->Loan->update_loan($loan_id,$data_loan);  
                    $success_message = lang('loans_successful_updating').' '.to_currency($data_loan['amount']);
                    $this->session->set_flashdata('manage_success_message', $success_message);
                    echo json_encode(array('success'=>true,'message'=>$success_message,'loan_id'=>$loan_id,'redirect_code'=>2));
                }else{
                    $this->Loan->update_loan_and_schedule($loan_id,$data_loan);  
                    $success_message = lang('loans_successful_updating').' '.to_currency($data_loan['amount']);
                    $this->session->set_flashdata('manage_success_message', $success_message);
                    echo json_encode(array('success'=>true,'message'=>$success_message,'loan_id'=>$loan_id,'redirect_code'=>2));
                }
            }
            
            if (isset($_FILES['upload']['name'])) {
                $count = count($_FILES['upload']['name']);
                $uploads = $_FILES['upload'];
                for ($i = 0; $i <$count; $i++) {
                    if ($uploads['error'][$i] == 0) {
                        if($loan_id==-1){
                            move_uploaded_file($uploads['tmp_name'][$i],'borrow_attachment/'.$data_save.'/' . $uploads['name'][$i]);
                        }else{
                             move_uploaded_file($uploads['tmp_name'][$i],'borrow_attachment/'.$loan_id.'/' . $uploads['name'][$i]);
                        }
                        $data['file_name']=$uploads['name'][$i];
                        if($loan_id==-1){
                            $data['loan_id']=$data_save;
                        }else{
                            $data['loan_id']=$loan_id;
                        }
                        $this->Loan->insert_attament($data);
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
		$loan_id_to_delete=$this->input->post('ids')?$this->input->post('ids'):$this->uri->segment(3);
		
		if($this->Loan->delete_list($loan_id_to_delete))
		{
			$this->uri->segment(3)?redirect('loans'):'';
                        echo json_encode(array('success'=>true,'message'=>lang('loans_successful_deleted').' '.
			count($loan_id_to_delete).' '.lang('loans_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('loans_cannot_be_deleted')));
		}
	}
	
	function _excel_get_header_row()
	{
		return array(lang('common_order_no'),lang('common_first_name'),lang('common_last_name'),lang('loans_amount'),lang('loans_deposit'),lang('loans_rate'),lang('loans_duration'), lang('loans_borrow_date'), lang('loans_from'),lang('loans_to'),lang('loans_currency'),lang('loans_product_name'),lang('common_comments'));
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
		$this->load->view("loans/excel_import", null);
	}
	
	function check_duplicate()
	{
		echo json_encode(array('duplicate'=>$this->Loan->check_duplicate($this->input->post('term'))));
	}
	/* added for excel expert */
	function excel_export() {
		$data = $this->Loan->get_all($this->Loan->count_all())->result_object();
		$this->load->helper('report');
		$rows = array();
		
		$header_row = $this->_excel_get_header_row();
		$header_row[] = lang('loans_id');
		$rows[] = $header_row;
		$i=1;
		foreach ($data as $r) {
			$row = array(
				$i,
				$r->first_name,
				$r->last_name,
				$r->amount,
				$r->deposit,
				$r->rate,
				$r->duration,
				$r->borrow_date,
				$r->start_date,
				$r->end_date,
				$r->currency,
				$r->product_name,
				$r->comments,
				$r->loan_id
			);
			$rows[] = $row;
                        $i++;
		}
		
		$content = array_to_spreadsheet($rows);
		force_download('loans_export.'.($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
		exit;
	}

		
	function cleanup()
	{
		$this->Loan->cleanup();
		echo json_encode(array('success'=>true,'message'=>lang('customers_cleanup_sucessful')));
	}
		
	function pay_now($loan_id)
	{
		$this->load->library('sale_lib');
    	$this->sale_lib->clear_all();
		$this->sale_lib->set_customer($loan_id);
		$this->sale_lib->set_mode('store_account_payment');
		$store_account_payment_item_id = $this->Item->create_or_update_store_account_item();
		$this->sale_lib->add_item($store_account_payment_item_id,1);
		redirect('sales');
	}
        
        //function for view setting of loan
        function setting(){
            $data['query']= $this->Loan->get_rate_setting();    
            $this->load->view('loans/setting',$data);
        }
        
        //function update loan
        function update_rate(){
        $data['rate_one']=$this->input->post('condition_one');
        $data['rate_two']=$this->input->post('condition_two');
        $this->form_validation->set_rules('condition_one', 'This', 'numeric|greater_than[0]|less_than[100]');
        $this->form_validation->set_rules('condition_two', 'This', 'numeric|greater_than[0]|less_than[100]');
        if ($this->form_validation->run() == FALSE)
        {
            $data['query']= $this->Loan->get_rate_setting();      
            $this->load->view('loans/setting',$data);
        }else{  
            $this->db->where('id',1);
            $this->db->update('laon_setting',$data);
            $data['query']= $this->Loan->get_rate_setting(); 
            $data['success'] =lang('loans_success_save'); 
            $this->load->view('loans/setting',$data);
            
        }
        
    }
    
    //view schedule
    function view_schedule($loan_id){
        $data['schedule']=$this->Loan->get_schedule($loan_id);
        $data['loan_info']=$this->Loan->get_info($loan_id);
        $data['location']=$this->Location->get_info(1);
        if($this->Loan->get_pay_all_before_the_end($loan_id)->num_rows()==1){
            $data['notify'] = lang('loans_already_pay_before_date').'<br/>'.lang('loans_print_amount').' '.to_currency($this->Loan->get_pay_all_before_the_end($loan_id)->row()->pay_total).'<br/> '.lang('loans_print_date').' '.date('D, d-M-Y',  strtotime($this->Loan->get_pay_all_before_the_end($loan_id)->row()->pay_date));
        }else if($this->Loan->get_pay_all_at_the_end($loan_id)->num_rows()==$data['loan_info']->duration){
            $data['notify'] = lang('loans_already_pay_on_date');
        }else{
            $data['notify'] =='';
        }
        $data['attachment'] = $this->Loan->get_attachment($loan_id);
        $this->load->view('loans/schedule',$data);
    }
    //view attachment 
    function add_attachment($loan_id){
        $data['attachment'] = $this->Loan->get_attachment($loan_id);
        $this->load->view('loans/attachment',$data);
        if (isset($_FILES['upload']['name'])) {
                $count = count($_FILES['upload']['name']);
                $uploads = $_FILES['upload'];
                for ($i = 0; $i <$count; $i++) {
                    if ($uploads['error'][$i] == 0) {
                        move_uploaded_file($uploads['tmp_name'][$i],'borrow_attachment/'.$loan_id.'/' . $uploads['name'][$i]);
                        $data_file['file_name']=$uploads['name'][$i];
                        $data_file['loan_id']=$loan_id;
                        $this->Loan->insert_attament($data_file);
                    }
                }
                redirect('loans/add_attachment/'.$loan_id);
        }
    }
    //add attachment
    function delete_attachment(){
        $id = $this->input->post('ids');
        $this->db->where('id',$id);
        $this->db->update('product_loan_attachment',array('deleted'=>1));
    }
    
    function add_comment(){
        $id = $this->input->post('id');
        $comment = $this->input->post('comment');
        $this->Loan->add_comment($id, $comment);
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
        $this->Loan->add_late($id, $num_day, $pay_fine);
    }
    //change status of loan
    function status(){
        $id = $this->input->post('id');
        $set_pay = $this->input->post('set_pay');
        $status = $this->input->post('status');
        $row = $this->input->post('order');
        $loan_id = $this->input->post('loan_id');
        //update paid
        if($status==1){
            $data['paid_princ']= $this->Loan->get_detail_as_id($id)->pay_principle;
            $data['paid_rate']= $this->Loan->get_detail_as_id($id)->pay_rate;
            $data['status']= $status;
            $data['pay_left']= $set_pay-($this->Loan->get_detail_as_id($id)->pay_rate+$this->Loan->get_detail_as_id($id)->pay_principle);
            $data['pay_total']= $this->Loan->get_detail_as_id($id)->pay_rate+$this->Loan->get_detail_as_id($id)->pay_principle;
            $this->Loan->update_paid($id,$data);
        //update not pay    
        }else if($status==0){
            $data['paid_princ']= 0;
            $data['paid_rate']= 0;
            $data['status']= 0;
            $data['pay_left']= 0;
            $data['pay_total']= $this->Loan->get_detail_as_id($id)->pay_rate+$this->Loan->get_detail_as_id($id)->pay_principle;
            $this->Loan->update_paid($id,$data);
            $this->Loan->update_not_pay($id,$loan_id); 
        //update paid all
        }else if($status==2){
            $half = ceil(($this->Loan->get_info($loan_id)->duration)/2);
            if($row <= $half){
               //get sum paid rate in haft 
                $some_rate_in_half = $this->Loan->get_sum_interest($loan_id, $half);
                //get all paid total that has been paid
                $some_paid_total = $this->Loan->get_sum_interest_paid($loan_id, $half)->sum_paid_total;
                //get all amount 
                $amount = $this->Loan->get_info($loan_id)->amount;
                
                $data['paid_princ']= $this->Loan->get_detail_as_id($id)->pay_principle;
                $data['paid_rate']= $this->Loan->get_detail_as_id($id)->pay_rate;
                $data['status']= $status;
                $data['pay_left']= 0;
                $data['pay_total']= ($amount+$some_rate_in_half)-$some_paid_total;
                $this->Loan->update_paid($id,$data);
            }else{
               if($this->Loan->get_detail_as_id($id)->late>15){
                   $not_yet_paid = $this->Loan->get_sum_principle_new($loan_id)->pay_principle;
                   $rate = $this->Loan->get_detail_as_id($id)->pay_rate;
                   
                   $data['paid_princ']= $not_yet_paid;
                   $data['paid_rate']= $rate;
                   $data['status']= $status;
                   $data['pay_left']= 0;
                   $data['pay_total']= $not_yet_paid+($rate*2);
                   $this->Loan->update_paid($id,$data);
               }else{
                   //sum not yet paid principle
                   $not_yet_paid = $this->Loan->get_sum_principle_new($loan_id)->pay_principle;
                   $rate = $this->Loan->get_detail_as_id($id)->pay_rate;
                   
                   $data['paid_princ']= $not_yet_paid;
                   $data['paid_rate']= $rate;
                   $data['status']= $status;
                   $data['pay_left']= 0;
                   $data['pay_total']= $not_yet_paid+$rate;
                   $this->Loan->update_paid($id,$data);
               }
            }
            
            $this->Loan->update_pay_all($id,$loan_id);
        }
    }
    
   //function for API
    function get_loans_api(){
        $data = $this->Loan->get_all_api()->result();
        echo json_encode(array('all_loan'=>$data));
    }
        
        
}
?>