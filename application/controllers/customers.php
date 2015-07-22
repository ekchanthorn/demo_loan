<?php
require_once ("person_controller.php");
class Customers extends Person_controller
{
	function __construct()
	{
		parent::__construct('customers');
	}
	
	
	function index($offset=0)
	{
		$params = $this->session->userdata('customers_search_data') ? $this->session->userdata('customers_search_data') : array('offset' => 0, 'order_col' => 'last_name', 'order_dir' => 'asc', 'search' => FALSE);
		if ($offset!=$params['offset'])
		{
		   //redirect('customers/index/'.$params['offset']);
                    $this->clear_state();
		}
		$this->check_action_permission('search');
		$config['base_url'] = site_url('customers/sorting');
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$data['controller_name']=strtolower(get_class());
		$data['per_page'] = $config['per_page'];
		$data['search'] = $params['search'] ? $params['search'] : "";
		if ($data['search'])
		{
			$config['total_rows'] = $this->Customer->search_count_all($data['search']);
			$table_data = $this->Customer->search($data['search'],$data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		else
		{
			$config['total_rows'] = $this->Customer->count_all();
			$table_data = $this->Customer->get_all($data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['order_col'] = $params['order_col'];
		$data['order_dir'] = $params['order_dir'];
		
		$data['manage_table']=get_people_manage_table($table_data,$this);
		$data['total_rows'] = $config['total_rows'];
		$this->load->view('people/manage',$data);
	}

	function sorting()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search') ? $this->input->post('search') : "";
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$customers_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("customers_search_data",$customers_search_data);
		
		if ($search)
		{
			$config['total_rows'] = $this->Customer->search_count_all($search);
			$table_data = $this->Customer->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		else
		{
			$config['total_rows'] = $this->Customer->count_all();
			$table_data = $this->Customer->get_all($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		$config['base_url'] = site_url('customers/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_people_manage_table_data_rows($table_data,$this);
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

		$customers_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("customers_search_data",$customers_search_data);
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Customer->search($search,$per_page,$offset, $order_col ,$order_dir);
		$config['base_url'] = site_url('customers/search');
		$config['total_rows'] = $this->Customer->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $this->Customer->search_count_all($search);
		$data['manage_table']=get_people_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Customer->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	/*
	Loads the customer edit form
	*/
	function view($customer_id=-1,$redirect_code=0)
	{
		$this->check_action_permission('add_update');
		
		$tiers = array();
		$tiers_result = $this->Tier->get_all()->result_array();
		
		if (count($tiers_result) > 0)
		{
			$tiers[0] = lang('items_none');
			foreach($tiers_result as $tier)
			{
				$tiers[$tier['id']]=$tier['name'];
			}	
		}
		
		$data['controller_name']=strtolower(get_class());
		$data['tiers']=$tiers;
		$data['person_info']=$this->Customer->get_info($customer_id);
		$data['redirect_code']=$redirect_code;
		$this->load->view("customers/form",$data);
	}

	function view_modal($customer_id)
	{
		$data['customer_info'] = $this->Customer->get_info($customer_id);;
		$this->load->view("customers/customer_modal", $data);
	}
	
	function account_number_exists()
	{
		if($this->Customer->account_number_exists($this->input->post('account_number')))
		echo 'false';
		else
		echo 'true';
		
	}

	function clear_state()
	{
		$this->session->unset_userdata('customers_search_data');
		redirect('customers');
	}
	/*
	Inserts/updates a customer
	*/
	function save($customer_id=-1)
	{
		$this->check_action_permission('add_update');
		$person_data = array(
		'first_name'=>$this->input->post('first_name'),
		'last_name'=>$this->input->post('last_name'),
		'email'=>$this->input->post('email'),
		'phone_number'=>$this->input->post('phone_number'),
		'address_1'=>$this->input->post('address_1'),
		'address_2'=>$this->input->post('address_2')?$this->input->post('address_2'):'',
		'city'=>$this->input->post('city')?$this->input->post('city'):'',
		'state'=>$this->input->post('state')?$this->input->post('state'):'',
		'zip'=>$this->input->post('zip')?$this->input->post('zip'):'',
		'country'=>$this->input->post('country')?$this->input->post('country'):'',
		'comments'=>$this->input->post('comments'),
		'gender'=>$this->input->post('gender'),
		'dob'=>$this->input->post('dob'),
		'identity_no'=>$this->input->post('identity_no'),
		'age'=>$this->input->post('age')
		);
		$customer_data=array(
			'company_name' => $this->input->post('company_name')? $this->input->post('company_name'):'',
			'tier_id' => $this->input->post('tier_id') ? $this->input->post('tier_id') : NULL,
			'account_number'=>$this->input->post('account_number')=='' ? null:$this->input->post('account_number'),
			'taxable'=>$this->input->post('taxable')=='' ? 0:1,
		);
		
		if ($this->input->post('balance')!== FALSE && is_numeric($this->input->post('balance')))
		{
			$customer_data['balance'] = $this->input->post('balance');
		}

		if ($this->input->post('credit_limit')!== FALSE && is_numeric($this->input->post('credit_limit')))
		{
			$customer_data['credit_limit'] = $this->input->post('credit_limit');
		}
		else
		{
			$customer_data['credit_limit'] = NULL;
		}		
		$redirect_code=$this->input->post('redirect_code');
		if ($this->input->post('delete_cc_info'))
		{
			$customer_data['cc_token'] = NULL;
			$customer_data['cc_preview'] = NULL;
			$customer_data['card_issuer'] = NULL;			
		}
		
		if($this->Customer->save($person_data,$customer_data,$customer_id))
		{
			if ($this->Location->get_info_for_key('mailchimp_api_key'))
			{
				$this->Person->update_mailchimp_subscriptions($this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('mailing_lists'));
			}
	

			$success_message = '';
			
			//New customer
			if($customer_id==-1)
			{
				$success_message = lang('customers_successful_adding').' '.$person_data['first_name'].' '.$person_data['last_name'];
				echo json_encode(array('success'=>true,'message'=> $success_message,'person_id'=>$customer_data['person_id'],'redirect_code'=>$redirect_code));
			}
			else //previous customer
			{
				$success_message = lang('customers_successful_updating').' '.$person_data['first_name'].' '.$person_data['last_name'];
				$this->session->set_flashdata('manage_success_message', $success_message);
				echo json_encode(array('success'=>true,'message'=>$success_message,'person_id'=>$customer_id,'redirect_code'=>$redirect_code));
			}
						
				//Delete Image
				if($this->input->post('del_image') && $customer_id != -1)
				{
					$customer_info = $this->Customer->get_info($customer_id);
				    if($customer_info->image_id != null)
				    {
						$this->Person->update_image(NULL,$customer_id);
						$this->Appfile->delete($customer_info->image_id);
				    }
				}

				//Save Image File
				if(!empty($_FILES["image_id"]) && $_FILES["image_id"]["error"] == UPLOAD_ERR_OK)
				{			    

				    $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
					$extension = strtolower(pathinfo($_FILES["image_id"]["name"], PATHINFO_EXTENSION));
				    if (in_array($extension, $allowed_extensions))
				    {
					    $config['image_library'] = 'gd2';
					    $config['source_image']	= $_FILES["image_id"]["tmp_name"];
					    $config['create_thumb'] = FALSE;
					    $config['maintain_ratio'] = TRUE;
					    $config['width']	 = 400;
					    $config['height']	= 300;
					    $this->load->library('image_lib', $config); 
					    $this->image_lib->resize();
					    $image_file_id = $this->Appfile->save($_FILES["image_id"]["name"], file_get_contents($_FILES["image_id"]["tmp_name"]));
				    }
					
					if($customer_id==-1)
					{
		    			$this->Person->update_image($image_file_id,$customer_data['person_id']);
					}
					else
					{
						$this->Person->update_image($image_file_id,$customer_id);
	    			
					}
				}
		}
		else//failure
		{	
			echo json_encode(array('success'=>false,'message'=>lang('customers_error_adding_updating').' '.
			$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>-1));
		}
	}
	
	/*
	This deletes customers from the customers table
	*/
	function delete()
	{
		$this->check_action_permission('delete');
		$customers_to_delete=$this->input->post('ids');
		
		if($this->Customer->delete_list($customers_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('customers_successful_deleted').' '.
			count($customers_to_delete).' '.lang('customers_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('customers_cannot_be_deleted')));
		}
	}
	
	function _excel_get_header_row()
	{
		return array(lang('common_first_name'),lang('common_last_name'),lang('common_gender'),lang('common_dob'),lang('common_identity_no'),lang('common_age'), lang('common_email'),lang('common_phone_number'),lang('common_address'),lang('common_comments'));
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
		$this->load->view("customers/excel_import", null);
	}
	
	function check_duplicate()
	{
		echo json_encode(array('duplicate'=>$this->Customer->check_duplicate($this->input->post('term'))));
	}
	/* added for excel expert */
	function excel_export() {
		$data = $this->Customer->get_all($this->Customer->count_all())->result_object();
		$this->load->helper('report');
		$rows = array();
		
		$header_row = $this->_excel_get_header_row();
		$header_row[] = lang('customers_customer_id');
		$rows[] = $header_row;
		
		foreach ($data as $r) {
			$row = array(
				$r->first_name,
				$r->last_name,
				$r->gender,
				$r->dob,
				$r->identity_no,
				$r->age,
				$r->email,
				$r->phone_number,
				$r->address_1,
//				$r->address_2,
//				$r->city,
//				$r->state,
//				$r->zip,
//				$r->country,
				$r->comments,
//				$r->account_number,
//				$r->taxable ? 'y' : 'n',
//				$r->company_name,
				$r->person_id
			);
			$rows[] = $row;
		}
		
		$content = array_to_spreadsheet($rows);
		force_download('customers_export.'.($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
		exit;
	}

	function do_excel_import()
	{
		if (is_on_demo_host())
		{
			$msg = lang('items_excel_import_disabled_on_demo');
			echo json_encode( array('success'=>false,'message'=>$msg) );
			return;
		}
		
		set_time_limit(0);
		$this->check_action_permission('add_update');
		$this->db->trans_start();
				
		$msg = 'do_excel_import';
		$failCodes = array();
		if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK)
		{
			$msg = lang('items_excel_import_failed');
			echo json_encode( array('success'=>false,'message'=>$msg) );
			return;
		}
		else
		{
			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{
				$objPHPExcel = file_to_obj_php_excel($_FILES['file_path']['tmp_name']);
				$sheet = $objPHPExcel->getActiveSheet();
				$num_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				
				//Loop through rows, skip header row
				for($k = 2;$k<=$num_rows; $k++)
				{					
					$first_name = $sheet->getCellByColumnAndRow(0, $k)->getValue();
					if (!$first_name)
					{
						$first_name = '';
					}
					
					$last_name = $sheet->getCellByColumnAndRow(1, $k)->getValue();
					if (!$last_name)
					{
						$last_name = '';
					}
                                        
                                        $gender = $sheet->getCellByColumnAndRow(2, $k)->getValue();
					if (!$gender)
					{
						$gender = '';
					}
                                        
                                        $dob = $sheet->getCellByColumnAndRow(3, $k)->getValue();
					if (!$dob)
					{
						$dob = '';
					}
                                        
                                        $idt = $sheet->getCellByColumnAndRow(4, $k)->getValue();
					if (!$idt)
					{
						$idt = '';
					}
                                        
                                        $age = $sheet->getCellByColumnAndRow(5, $k)->getValue();
					if (!$age)
					{
						$age = '';
					}

					$email = $sheet->getCellByColumnAndRow(6, $k)->getValue();
					if (!$email)
					{
						$email = '';
					}

					$phone_number = $sheet->getCellByColumnAndRow(7, $k)->getValue();
					if (!$phone_number)
					{
						$phone_number = '';
					}

					$address_1 = $sheet->getCellByColumnAndRow(8, $k)->getValue();
					if (!$address_1)
					{
						$address_1 = '';
					}

//					$address_2 = $sheet->getCellByColumnAndRow(5, $k)->getValue();
//					if (!$address_2)
//					{
//						$address_2 = '';
//					}
//
//					$city = $sheet->getCellByColumnAndRow(6, $k)->getValue();
//					if (!$city)
//					{
//						$city = '';
//					}
//
//					$state = $sheet->getCellByColumnAndRow(7, $k)->getValue();
//					if (!$state)
//					{
//						$state = '';
//					}
//
//					$zip = $sheet->getCellByColumnAndRow(8, $k)->getValue();
//					if (!$zip)
//					{
//						$zip = '';
//					}

//					$country = $sheet->getCellByColumnAndRow(9, $k)->getValue();
//					if (!$country)
//					{
//						$country = '';
//					}

					$comments = $sheet->getCellByColumnAndRow(9, $k)->getValue();
					if (!$comments)
					{
						$comments = '';
					}

//					$account_number = $sheet->getCellByColumnAndRow(11, $k)->getValue();
//					if (!$account_number)
//					{
//						$account_number = NULL;
//					}
//
//					$taxable = $sheet->getCellByColumnAndRow(12, $k)->getValue();
//					
//					$company_name = $sheet->getCellByColumnAndRow(13, $k)->getValue();
//					if (!$company_name)
//					{
//						$company_name = '';
//					}
					
					$person_id = $sheet->getCellByColumnAndRow(10, $k)->getValue();
					
					
					$person_data = array(
					'first_name'=>$first_name,
					'last_name'=>$last_name,
					'age'=>$age,
					'gender'=>$gender,
					'dob'=>$dob,
					'identity_no'=>$idt,
					'email'=>$email,
					'phone_number'=>$phone_number,
					'address_1'=>$address_1,
//					'address_2'=>$address_2,
//					'city'=>$city,
//					'state'=>$state,
//					'zip'=>$zip,
//					'country'=>$country,
					'comments'=>$comments
					);
					
					$customer_data=array(
					'account_number'=>'',
					'taxable'=> 0,
					'company_name' => '',
					);
					
					if(!$this->Customer->save($person_data,$customer_data, $person_id ? $person_id : FALSE))
					{	
						echo json_encode( array('success'=>false,'message'=>lang('customers_duplicate_account_id')));
						return;
					}
				}
			}
			else 
			{
				echo json_encode( array('success'=>false,'message'=>lang('common_upload_file_not_supported_format')));
				return;
			}
		}
		$this->db->trans_complete();
		echo json_encode(array('success'=>true,'message'=>lang('customers_import_successfull')));
	}
		
	function cleanup()
	{
		$this->Customer->cleanup();
		echo json_encode(array('success'=>true,'message'=>lang('customers_cleanup_sucessful')));
	}
		
	function pay_now($customer_id)
	{
		$this->load->library('sale_lib');
    	$this->sale_lib->clear_all();
		$this->sale_lib->set_customer($customer_id);
		$this->sale_lib->set_mode('store_account_payment');
		$store_account_payment_item_id = $this->Item->create_or_update_store_account_item();
		$this->sale_lib->add_item($store_account_payment_item_id,1);
		redirect('sales');
	}
}
?>