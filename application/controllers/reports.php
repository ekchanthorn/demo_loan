<?php
require_once ("secure_area.php");
class Reports extends Secure_area 
{	
	function __construct()
	{
		parent::__construct('reports');
		$this->load->helper('report');
		$this->has_profit_permission = $this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id);
		$this->has_cost_price_permission = $this->Employee->has_module_action_permission('reports','show_cost_price',$this->Employee->get_logged_in_employee_info()->person_id);
	}
		
	//Initial report listing screen
	function index()
	{
		$this->load->view("reports/listing",array());	
	}

	// Sales Generator Reports 
	function sales_generator() {	
		$this->check_action_permission('view_sales_generator');
		
		if ($this->input->get('act') == 'autocomplete') { // Must return a json string
			if ($this->input->get('w') != '') { // From where should we return data
				if ($this->input->get('term') != '') { // What exactly are we searchin
					
					//allow parallel searchs to improve performance.
					session_write_close();
					
					switch($this->input->get('w')) {
						case 'customers': 
							$t = $this->Customer->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
							$tmp = array();
							foreach ($t as $k=>$v) { $tmp[$k] = array('id'=>$v->person_id, 'name'=>$v->last_name.", ".$v->first_name." - ".$v->email); }
							die(json_encode($tmp));
						break;
						case 'employees':
						case 'salesPerson':
							$t = $this->Employee->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
							$tmp = array();
							foreach ($t as $k=>$v) { $tmp[$k] = array('id'=>$v->person_id, 'name'=>$v->last_name.", ".$v->first_name." - ".$v->email); }
							die(json_encode($tmp));
						break;
						case 'itemsCategory':
							$t = $this->Item->get_category_suggestions($this->input->get('term'));
							$tmp = array();
							foreach ($t as $k=>$v) { $tmp[$k] = array('id'=>$v['label'], 'name'=>$v['label']); }
							die(json_encode($tmp));
						break;
						case 'suppliers':
							$t = $this->Supplier->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
							$tmp = array();
							foreach ($t as $k=>$v) { $tmp[$k] = array('id'=>$v->person_id, 'name'=>$v->last_name.", ".$v->first_name." - ".$v->company_name." - ".$v->email); }
							die(json_encode($tmp));
						break;
						case 'itemsKitName':
							$t = $this->Item_kit->search($this->input->get('term'), 100, 0, 'name', 'asc')->result_object();
							$tmp = array();
							foreach ($t as $k=>$v) { $tmp[$k] = array('id'=>$v->item_kit_id, 'name'=>$v->name." / #".$v->item_kit_number); }
							die(json_encode($tmp));
						break;
						case 'itemsName':
							$t = $this->Item->search($this->input->get('term'), FALSE, 100, 0, 'name', 'asc')->result_object();
							$tmp = array();
							foreach ($t as $k => $v) { $tmp[$k] = array('id'=>$v->item_id, 'name'=>$v->name); }
							die(json_encode($tmp));
						break;
						case 'paymentType':
							$t = array(lang('sales_cash'),lang('sales_check'), lang('sales_giftcard'),lang('sales_debit'),lang('sales_credit'));
							
							if($this->config->item('customers_store_accounts')) 
							{
								$t[] =lang('sales_store_account');
							}
							
							foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
							{
								$t[] = $additional_payment_type;
							}

							$tmp = array();
							foreach ($t as $k => $v) { $tmp[$k] = array('id'=>$v, 'name'=>$v); }
							die(json_encode($tmp));
						break;		
					}
				} else {
					die;	
				}
			} else {
				die(json_encode(array('value' => 'No such data found!')));
			}
		}		
		
		$data = $this->_get_common_report_data();
		$data["title"] = lang('reports_sales_generator');
		$data["subtitle"] = lang('reports_sales_report_generator');
		
		$setValues = array('report_type' => '', 'sreport_date_range_simple' => '', 
										'start_month' => date("m"), 'start_day' => date('d'), 'start_year' => date("Y"),
										'end_month' => date("m"), 'end_day' => date('d'), 'end_year' => date("Y"),
										'matchType' => '',
										'matched_items_only' => FALSE
										);
		foreach ($setValues as $k => $v) { 
			if (empty($v) && !isset($data[$k])) { 
				$data[$k] = ''; 		
			} else {
				$data[$k] = $v;
			}
		}		
		if ($this->input->get('generate_report')) { // Generate Custom Raport
			$data['report_type'] = $this->input->get('report_type');
			$data['sreport_date_range_simple'] = $this->input->get('report_date_range_simple');
			
			
			$data['start_month'] = $this->input->get('start_month');
			$data['start_day'] = $this->input->get('start_day');
			$data['start_year'] = $this->input->get('start_year');
			$data['end_month'] = $this->input->get('end_month');
			$data['end_day'] = $this->input->get('end_day');
			$data['end_year'] = $this->input->get('end_year');		
			if ($data['report_type'] == 'simple') {
				$q = explode("/", $data['sreport_date_range_simple']);
				list($data['start_year'], $data['start_month'], $data['start_day']) = explode("-", $q[0]);
				list($data['end_year'], $data['end_month'], $data['end_day']) = explode("-", $q[1]);
			}
			$data['matchType'] = $this->input->get('matchType');
			$data['matched_items_only'] = $this->input->get('matched_items_only') ? TRUE : FALSE;

			$data['field'] = $this->input->get('field');
			$data['condition'] = $this->input->get('condition');
			$data['value'] = $this->input->get('value');
			
			$data['prepopulate'] = array();
			
			$field = $this->input->get('field');
			$condition = $this->input->get('condition');
			$value = $this->input->get('value');
			
			$tmpData = array();
			foreach ($field as $a => $b) {
				$uData = explode(",",$value[$a]);
				$tmp = $tmpID = array();
				switch ($b) {
					case '1': // Customer
						$t = $this->Customer->get_multiple_info($uData)->result_object();
						foreach ($t as $k=>$v) { $tmpID[] = $v->person_id; $tmp[$k] = array('id'=>$v->person_id, 'name'=>$v->last_name.", ".$v->first_name." - ".$v->email); }
					break;
					case '2': // Item Serial Number
						$tmpID[] = $value[$a];
					break;
					case '3': // Employees
						$t = $this->Employee->get_multiple_info($uData)->result_object();
						foreach ($t as $k=>$v) { $tmpID[] = $v->person_id;  $tmp[$k] = array('id'=>$v->person_id, 'name'=>$v->last_name.", ".$v->first_name." - ".$v->email); }
					break;
					case '4': // Items Category
						foreach ($uData as $k=>$v) { $tmpID[] = $v;  $tmp[$k] = array('id'=>$v, 'name'=>$v); }
					break;
					case '5': // Suppliers 
						$t = $this->Supplier->get_multiple_info($uData)->result_object();
						foreach ($t as $k=>$v) { $tmpID[] = $v->person_id;  $tmp[$k] = array('id'=>$v->person_id, 'name'=>$v->last_name.", ".$v->first_name." - ".$v->company_name." - ".$v->email); }
					break;
					case  '6': // Sale Type
						$tmpID[] = $condition[$a];
					break;
					case '7': // Sale Amount
						$tmpID[] = $value[$a];
					break;
					case '8': // Item Kits
						$t = $this->Item_kit->get_multiple_info($uData)->result_object();
						foreach ($t as $k=>$v) { $tmpID[] = $v->item_kit_id;  $tmp[$k] = array('id'=>$v->item_kit_id, 'name'=>$v->name." / #".$v->item_kit_number); }
					break;
					case '9': // Items Name
						$t = $this->Item->get_multiple_info($uData)->result_object();
						foreach ($t as $k => $v) { $tmpID[] = $v->item_id;  $tmp[$k] = array('id'=>$v->item_id, 'name'=>$v->name); }
					break;				
					case '10': // SaleID
						if(strpos(strtolower($value[$a]), strtolower($this->config->item('sale_prefix'))) !== FALSE)
						{							
							$value[$a] =(int)substr(strtolower($value[$a]), strpos(strtolower($value[$a]),$this->config->item('sale_prefix').' ') + strlen(strtolower($this->config->item('sale_prefix')).' '));	
						}
						$tmpID[] = $value[$a];
					break;
					case '11': // Payment type
						foreach ($uData as $k=>$v) { $tmpID[] = $v;  $tmp[$k] = array('id'=>$v, 'name'=>$v); }
					break;
					
					case '12': // Sale Item Description
						$tmpID[] = $value[$a];
					break;
					case '13': // Employees
						$t = $this->Employee->get_multiple_info($uData)->result_object();
						foreach ($t as $k=>$v) { $tmpID[] = $v->person_id;  $tmp[$k] = array('id'=>$v->person_id, 'name'=>$v->last_name.", ".$v->first_name." - ".$v->email); }
					break;
							
					
				}
				$data['prepopulate']['field'][$a][$b] = $tmp;			

				// Data for sql
				$tmpData[] = array('f' => $b, 'o' => $condition[$a], 'i' => $tmpID);
			}
			
			$params['matchType'] = $data['matchType'];
			$params['matched_items_only'] = $data['matched_items_only'];
			$params['ops'] = array(
												1 => " = 'xx'", 
												2 => " != 'xx'", 
												5 => " IN ('xx')", 
												6 => " NOT IN ('xx')", 
												7 => " > xx", 
												8 => " < xx", 
												9 => " = xx",
												10 => '', // Sales
												11 => '', // Returns
												);

			$params['tables'] = array(
								1 => 'sales_items_temp.customer_id', // Customers
								2 => 'sales_items_temp.serialnumber', // Item Sale Serial number
								3 => 'sales_items_temp.employee_id', // Employees
								4 => 'sales_items_temp.category', // Item Category
								5 => 'sales_items_temp.supplier_id', // Suppliers
								6 => '', // Sale Type
								7 => '', // Sale Amount
								8 => 'sales_items_temp.item_kit_id', // Item Kit Name
								9 => 'sales_items_temp.item_id', // Item Name
								10 => 'sales_items_temp.sale_id', // Sale ID
								11 => 'sales_items_temp.payment_type', // Payment Type
								12 => 'sales_items_temp.description', // Item Sale Serial number
								13 => 'sales_items_temp.sold_by_employee_id', // Item Sale Serial number
							);			
			$params['values'] = $tmpData;
			$params['offset'] = $this->input->get('per_page')  ? $this->input->get('per_page') : 0;
			$params['export_excel'] = $this->input->get('export_excel');
			
			$this->load->model('reports/Sales_generator');
			$model = $this->Sales_generator;
			$model->setParams($params);			

			// Sales Interval Reports
			$interval = array(
									'start_date' => $data['start_year'].'-'.$data['start_month'].'-'.$data['start_day'], 
									'end_date' => $data['end_year'].'-'.$data['end_month'].'-'.$data['end_day']. ' 23:59:59'									
							);
			$this->Sale->create_sales_items_temp_table($interval);
			$config = array();
			
			//Remove per_page from url so we don't have it duplicated
			$config['base_url'] = preg_replace('/&per_page=[0-9]*/','',current_url());
			$config['total_rows'] = $model->getTotalRows();
			$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			
			$tabular_data = array();
			$report_data = $model->getData();
			
			$summary_data = array();
			$details_data = array();
			
			foreach($report_data['summary'] as $key=>$row)
			{
				$summary_data_row = array();
			
				$summary_data_row[] = array('data'=>anchor('sales/receipt/'.$row['sale_id'], '<i class="fa fa-print fa fa-2x"></i>', array('target' => '_blank')).' '.anchor('sales/edit/'.$row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')).' '.anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=>'left');
				$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['sale_time'])), 'align'=>'left');
				$summary_data_row[] = array('data'=>$row['register_name'], 'align'=>'left');
				$summary_data_row[] = array('data'=>to_quantity($row['items_purchased']), 'align'=>'left');
				$summary_data_row[] = array('data'=>$row['employee_name'].($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/'. $row['sold_by_employee']: ''), 'align'=>'left');
				$summary_data_row[] = array('data'=>$row['customer_name'], 'align'=>'left');
				$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=>'right');
				$summary_data_row[] = array('data'=>to_currency($row['total']), 'align'=>'right');
				$summary_data_row[] = array('data'=>to_currency($row['tax']), 'align'=>'right');
				
				if($this->has_profit_permission)
				{
					$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
				}
				$summary_data_row[] = array('data'=>$row['payment_type'], 'align'=>'right');
				$summary_data_row[] = array('data'=>$row['comment'], 'align'=>'right');
			
				
				$summary_data[$key] = $summary_data_row;
				
				foreach($report_data['details'][$key] as $drow)
				{
					$details_data_row = array();
				
					$details_data_row[] = array('data'=>isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align'=>'left');
					$details_data_row[] = array('data'=>isset($drow['item_product_id']) ? $drow['item_product_id'] : $drow['item_kit_product_id'], 'align'=>'left');
					$details_data_row[] = array('data'=>isset($drow['item_name']) ? anchor('items/view/'.$drow['item_id'],$drow['item_name']) : anchor('item_kits/view/'.$drow['item_kit_id'],$drow['item_kit_name']), 'align'=>'left');
					$details_data_row[] = array('data'=>$drow['category'], 'align'=>'left');
					$details_data_row[] = array('data'=>$drow['size'], 'align'=>'left');
					$details_data_row[] = array('data'=>$drow['serialnumber'], 'align'=>'left');
					$details_data_row[] = array('data'=>$drow['description'], 'align'=>'left');
					$details_data_row[] = array('data'=>to_quantity($drow['quantity_purchased']), 'align'=>'left');
					$details_data_row[] = array('data'=>to_currency($drow['subtotal']), 'align'=>'right');
					$details_data_row[] = array('data'=>to_currency($drow['total']), 'align'=>'right');
					$details_data_row[] = array('data'=>to_currency($drow['tax']), 'align'=>'right');
				
					if($this->has_profit_permission)
					{
						$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');					
					}
				
					$details_data_row[] = array('data'=>$drow['discount_percent'].'%', 'align'=>'left');
					$details_data[$key][] = $details_data_row;
					
				}
			}
			
			$reportdata = array(
				"title" => lang('reports_sales_generator'),
				"subtitle" => lang('reports_sales_report_generator')." - ".date(get_date_format(), strtotime($interval['start_date'])) .'-'.date(get_date_format(), strtotime($interval['end_date']))." - ".$config['total_rows'].' '.lang('reports_sales_report_generator_results_found'),
				"headers" => $model->getDataColumns(),
				"summary_data" => $summary_data,
				"details_data" => $details_data,
				"overall_summary_data" => $model->getSummaryData(),
				'pagination' => $this->pagination->create_links(),
				'export_excel' =>$this->input->get('export_excel'),
			);
			
			// Fetch & Output Data 
			
			if (!$this->input->get('export_excel'))
			{
				$data['results'] = $this->load->view("reports/sales_generator_tabular_details", $reportdata, true);	
			}
		}	
		
		if (!$this->input->get('export_excel'))
		{
			$this->load->view("reports/sales_generator",$data);
		}
		else //Excel export use regular tabular_details
		{
			$this->load->view("reports/tabular_details",$reportdata);
		}
	}	
	
	function _get_common_report_data($time=false)
	{
		$data = array();
		$data['report_date_range_simple'] = get_simple_date_ranges($time);
		$data['months'] = get_months();
		$data['days'] = get_days();
		$data['years'] = get_years();
		$data['hours'] = get_hours($this->config->item('time_format'));
		$data['minutes'] = get_minutes();
		$data['selected_month']=date('m');
		$data['selected_day']=date('d');
		$data['selected_year']=date('Y');	
	
		return $data;
	}
	
	//Input for reports that require only a date range and an export to excel. (see routes.php to see that all summary reports route here)
	function date_input_excel_export()
	{
		$data = $this->_get_common_report_data(TRUE);
		
		$this->load->view("reports/date_input_excel_export",$data);	
	}
	
	function suspended_date_input_excel_export()
	{
		$data = $this->_get_common_report_data(TRUE);
		
		$this->load->view("reports/suspended_date_input_excel_export",$data);	
	}
	
	function employees_date_input_excel_export()
	{
		$data = $this->_get_common_report_data(TRUE);
		
		$this->load->view("reports/employees_date_input_excel_export",$data);	
		
	}
	
	/** added for register log */
	function date_input_excel_export_register_log()
	{
		$data = $this->_get_common_report_data();
		$this->load->view("reports/date_input_excel_register_log.php",$data);	
	}
	
	/** also added for register log */
	
	function detailed_register_log($start_date, $end_date, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_register_log');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);
		
		$this->load->model('reports/Detailed_register_log');
		$model = $this->Detailed_register_log;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'offset' => $offset, 'export_excel' => $export_excel));
		
		$config = array();
		$config['base_url'] = site_url("reports/detailed_register_log/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 6;
		$this->pagination->initialize($config);
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();
		
		$summary_data = array();
		$details_data = array();
				
		foreach($report_data as $row)
		{
			if($row['shift_end']=='0000-00-00 00:00:00')
			{
				$shift_end=lang('reports_register_log_open');
				$delete=anchor('reports/delete_register_log/'.$row['register_log_id'].'/'.$start_date.'/'. $end_date, lang('common_delete'), 
				"onclick='return confirm(".json_encode(lang('reports_confirm_register_log_delete')).")'");
			}
			else
			{
				$shift_end=date(get_date_format(), strtotime($row['shift_end'])) .' '.date(get_time_format(), strtotime($row['shift_end']));
				$delete=anchor('reports/delete_register_log/'.$row['register_log_id'].'/'.$start_date.'/'. $end_date, lang('common_delete'), 
				"onclick='return confirm(".json_encode(lang('reports_confirm_register_log_delete')).")'");
			}
			$summary_data[] = array(
				array('data'=>$delete, 'align'=>'left'), 
				array('data'=>$row['register_name'], 'align'=>'left'), 
				array('data'=>$row['open_first_name'] . ' ' . $row['open_last_name'], 'align'=>'left'), 
				array('data'=>$row['close_first_name'] . ' ' . $row['close_last_name'], 'align'=>'left'), 
				array('data'=>date(get_date_format(), strtotime($row['shift_start'])) .' '.date(get_time_format(), strtotime($row['shift_start'])), 'align'=>'left'), 
				array('data'=>$shift_end, 'align'=>'left'), 
				array('data'=>to_currency($row['open_amount']), 'align'=>'right'), 
				array('data'=>to_currency($row['close_amount']), 'align'=>'right'), 
				array('data'=>to_currency($row['cash_sales_amount']), 'align'=>'right'),
				array('data'=>to_currency($row['difference']), 'align'=>'right')
			);			
		}

		$data = array(
			"title" =>lang('reports_register_log_title'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $summary_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular", $data);
	}

	function delete_register_log($register_log_id,$start_date,$end_date)
	{
		$this->load->model('reports/Detailed_register_log');
		if($this->Detailed_register_log->delete_register_log($register_log_id))
		{
			redirect('reports/detailed_register_log/'.$start_date.'/'.$end_date);
		}
		
		
	}

	//Summary sales report
	function summary_sales($start_date, $end_date, $sale_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_sales');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
	
		$config = array();
		$config['base_url'] = site_url("reports/summary_sales/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);
	
		$tabular_data = array();
		$report_data = $model->getData();

		foreach($report_data as $row)
		{
			$data_row = array();
			
			$data_row[] = array('data'=>date(get_date_format(), strtotime($row['sale_date'])), 'align'=>'left');
			$data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=>'right');
			$data_row[] = array('data'=>to_currency($row['total']), 'align'=>'right');
			$data_row[] = array('data'=>to_currency($row['tax']), 'align'=> 'right');
			
			if($this->has_profit_permission)
			{
				$data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			$tabular_data[] = $data_row;
		}
		$data = array(
			"title" => lang('reports_sales_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links()
		);

		$this->load->view("reports/tabular",$data);
	}

	//Summary categories report
	function summary_categories($start_date, $end_date, $sale_type, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_categories');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_categories');
		$model = $this->Summary_categories;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'export_excel'=>$export_excel, 'offset' => $offset));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$config = array();
		$config['base_url'] = site_url("reports/summary_categories/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;

		$this->pagination->initialize($config);
		
		$tabular_data = array();
		$report_data = $model->getData();

		foreach($report_data as $row)
		{
			$data_row = array();
			
			$data_row[] = array('data'=>$row['category'], 'align' => 'left');
			$data_row[] = array('data'=>to_currency($row['subtotal']), 'align' => 'right');
			$data_row[] =  array('data'=>to_currency($row['total']), 'align' => 'right');
			$data_row[] = array('data'=>to_currency($row['tax']), 'align' => 'right');
			if($this->has_profit_permission)
			{
				$data_row[] = array('data'=>to_currency($row['profit']), 'align' => 'right');
			}
			$data_row[] = array('data'=>floatval($row['item_sold']), 'align' => 'center');
			$tabular_data[] = $data_row;				
		}

		$data = array(
			"title" => lang('reports_categories_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}

	//Summary customers report
	function summary_customers($start_date, $end_date, $sale_type, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_customers');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_customers');
		$model = $this->Summary_customers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$config = array();
		$config['base_url'] = site_url("reports/summary_customers/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;		
		$this->pagination->initialize($config);

		$tabular_data = array();
		$report_data = $model->getData();
		$no_customer = $model->getNoCustomerData();
		$report_data = array_merge($no_customer,$report_data);
		
		foreach($report_data as $row)
		{
			$data_row = array();
			
			$data_row[] = array('data'=>$row['customer'], 'align' => 'left');
			$data_row[] = array('data'=>to_currency($row['subtotal']), 'align' => 'right');
			$data_row[] =  array('data'=>to_currency($row['total']), 'align' => 'right');
			$data_row[] = array('data'=>to_currency($row['tax']), 'align' => 'right');
			if($this->has_profit_permission)
			{
				$data_row[] = array('data'=>to_currency($row['profit']), 'align' => 'right');
			}
			
			$tabular_data[] = $data_row;				
		}

		$data = array(
			"title" => lang('reports_customers_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}

	//Summary suppliers report
	function summary_suppliers($start_date, $end_date, $sale_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_suppliers');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);
		
		$this->load->model('reports/Summary_suppliers');
		$model = $this->Summary_suppliers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset'=>$offset, 'export_excel' => $export_excel));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/summary_suppliers/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;		
		$this->pagination->initialize($config);
		
		$tabular_data = array();
		$report_data = $model->getData();

		foreach($report_data as $row)
		{
			$data_row = array();
			
			$data_row[] = array('data'=>$row['supplier'], 'align' => 'left');
			$data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=>'right');
			$data_row[] = array('data'=>to_currency($row['total']), 'align'=>'right');
			$data_row[] = array('data'=>to_currency($row['tax']), 'align'=> 'right');
			
			if($this->has_profit_permission)
			{
				$data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			$tabular_data[] = $data_row;			
		}

		$data = array(
			"title" => lang('reports_suppliers_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}

	//Summary items report
	function summary_items($start_date, $end_date, $sale_type, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_items');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_items');
		$model = $this->Summary_items;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$config = array();
		$config['base_url'] = site_url("reports/summary_items/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;

		$this->pagination->initialize($config);

		$tabular_data = array();
		$report_data = $model->getData();

		foreach($report_data as $row)
		{
			$data_row = array();
			$data_row[] = array('data'=>$row['name'], 'align' => 'left');
			$data_row[] = array('data'=>$row['item_number'], 'align' => 'left');
			$data_row[] = array('data'=>$row['product_id'], 'align' => 'left');
			$data_row[] = array('data'=>$row['category'], 'align' => 'left');
			$data_row[] = array('data'=>to_quantity($row['quantity']), 'align' => 'left');
			$data_row[] = array('data'=>to_quantity($row['quantity_purchased']), 'align' => 'left');
			$data_row[] = array('data'=>to_currency($row['subtotal']), 'align' => 'right');
			$data_row[] = array('data'=>to_currency($row['total']), 'align' => 'right');
			$data_row[] = array('data'=>to_currency($row['tax']), 'align' => 'right');
			if($this->has_profit_permission)
			{
				$data_row[] = array('data'=>to_currency($row['profit']), 'align' => 'right');
			}
			$tabular_data[] = $data_row;
		
		}

		$data = array(
			"title" => lang('reports_items_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links()
		);

		$this->load->view("reports/tabular",$data);
	}

	//Summary item kits report
	function summary_item_kits($start_date, $end_date, $sale_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_item_kits');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_item_kits');
		$model = $this->Summary_item_kits;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'export_excel' =>$export_excel, 'offset' => $offset));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/summary_item_kits/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;		
		$this->pagination->initialize($config);

		$tabular_data = array();
		$report_data = $model->getData();

		foreach($report_data as $row)
		{
			$data_row = array();
		
			$data_row[] = array('data'=>$row['name'], 'align' => 'left');
			$data_row[] = array('data'=>to_quantity($row['quantity_purchased']), 'align' => 'left');
			$data_row[] =  array('data'=>to_currency($row['subtotal']), 'align' => 'right');
			$data_row[] = array('data'=>to_currency($row['total']), 'align' => 'right');
			$data_row[] = array('data'=>to_currency($row['tax']), 'align' => 'right');
			if($this->has_profit_permission)
			{			
				$data_row[] = array('data'=>to_currency($row['profit']), 'align' => 'right');
			}
			$tabular_data[] = $data_row;
		}

		$data = array(
			"title" => lang('reports_item_kits_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}

	//Summary employees report
	function summary_employees($start_date, $end_date, $sale_type, $employee_type, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_employees');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_employees');
		$model = $this->Summary_employees;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'employee_type' =>$employee_type, 'export_excel' => $export_excel, 'offset' => $offset));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/summary_employees/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$employee_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 8;		
		$this->pagination->initialize($config);

		$tabular_data = array();
		$report_data = $model->getData();

		foreach($report_data as $row)
		{
			$data_row = array();
			
			$data_row[] = array('data'=>$row['employee'], 'align' => 'left');
			$data_row[] = array('data'=>to_currency($row['subtotal']), 'align' => 'right');
			$data_row[] =  array('data'=>to_currency($row['total']), 'align' => 'right');
			$data_row[] = array('data'=>to_currency($row['tax']), 'align' => 'right');
			if($this->has_profit_permission)
			{
				$data_row[] = array('data'=>to_currency($row['profit']), 'align' => 'right');
			}
			
			$tabular_data[] = $data_row;			
		}

		$data = array(
			"title" => lang('reports_employees_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}

	//Summary taxes report
	function summary_taxes($start_date, $end_date, $sale_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_taxes');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_taxes');
		$model = $this->Summary_taxes;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel'=>$export_excel));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$config = array();
		$config['base_url'] = site_url("reports/summary_taxes/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;		
		$this->pagination->initialize($config);

		$tabular_data = array();
		$report_data = $model->getData();

		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['name'], 'align'=>'left'),array('data'=>to_currency($row['subtotal']), 'align'=>'left'),array('data'=>to_currency($row['tax']), 'align'=>'left'), array('data'=>to_currency($row['total']), 'align'=>'left'));
		}

		$data = array(
			"title" => lang('reports_taxes_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links()
		);

		$this->load->view("reports/tabular",$data);
	}

	//Summary discounts report
	function summary_discounts($start_date, $end_date, $sale_type, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_discounts');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_discounts');
		$model = $this->Summary_discounts;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'export_excel' => $export_excel, 'offset' => $offset));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$config = array();
		$config['base_url'] = site_url("reports/summary_discounts/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;		
		$this->pagination->initialize($config);

		$tabular_data = array();
		$report_data = $model->getData();

		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['discount_percent'], 'align'=>'left'),array('data'=>$row['count'], 'align'=>'left'));
		}

		$data = array(
			"title" => lang('reports_discounts_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links()
		);

		$this->load->view("reports/tabular",$data);
	}

	function summary_payments($start_date, $end_date, $sale_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_payments');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_payments');
		$model = $this->Summary_payments;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset'=> $offset, 'export_excel' => $export_excel));
		$sale_ids = $model->get_sale_ids_for_payments();
		$this->Sale->create_sales_items_temp_table(array('sale_ids' => $sale_ids, 'sale_type' => $sale_type));

		$config = array();
		$config['base_url'] = site_url("reports/summary_payments/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;

		$this->pagination->initialize($config);

		$tabular_data = array();
		$report_data = $model->getData();

		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['payment_type'], 'align'=>'left'),array('data'=>to_currency($row['payment_amount']), 'align'=>'right'));
		}

		$data = array(
			"title" => lang('reports_payments_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}

	//Input for reports that require only a date range. (see routes.php to see that all graphical summary reports route here)
	function date_input()
	{
		$data = $this->_get_common_report_data();
		$this->load->view("reports/date_input",$data);
	}

	function employees_date_input()
	{
		$data = $this->_get_common_report_data();
		$this->load->view("reports/employees_date_input",$data);
	}

	//Graphical summary sales report
	function graphical_summary_sales($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_sales');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_sales_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_sales_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_sales_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[strtotime($row['sale_date'])]= $row['total'];
		}

		$data = array(
			"title" => lang('reports_sales_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/line",$data);

	}

	//Graphical summary items report
	function graphical_summary_items($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_items');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_items');
		$model = $this->Summary_items;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_items_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_items_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_items_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_items');
		$model = $this->Summary_items;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['name']] = $row['total'];
		}

		$data = array(
			"title" => lang('reports_items_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/pie",$data);
	}

	//Graphical summary item kits report
	function graphical_summary_item_kits($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_item_kits');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_item_kits');
		$model = $this->Summary_item_kits;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_item_kits_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_item_kits_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_item_kits_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_item_kits');
		$model = $this->Summary_item_kits;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['name']] = $row['total'];
		}

		$data = array(
			"title" => lang('reports_item_kits_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/pie",$data);
	}

	//Graphical summary customers report
	function graphical_summary_categories($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_categories');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_categories');
		$model = $this->Summary_categories;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_categories_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_categories_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_categories_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_categories');
		$model = $this->Summary_categories;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['category']] = $row['total'];
		}

		$data = array(
			"title" => lang('reports_categories_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/pie",$data);
	}

	function graphical_summary_suppliers($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_suppliers');
		
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_suppliers');
		$model = $this->Summary_suppliers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_suppliers_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_suppliers_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_suppliers_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_suppliers');
		$model = $this->Summary_suppliers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['supplier']] = $row['total'];
		}

		$data = array(
			"title" => lang('reports_suppliers_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/pie",$data);
	}

	function graphical_summary_employees($start_date, $end_date, $sale_type, $employee_type)
	{
		$this->check_action_permission('view_employees');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_employees');
		$model = $this->Summary_employees;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'employee_type' => $employee_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_employees_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_employees_graph/$start_date/$end_date/$sale_type/$employee_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_employees_graph($start_date, $end_date, $sale_type, $employee_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_employees');
		$model = $this->Summary_employees;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'employee_type' => $employee_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['employee']] = $row['total'];
		}

		$data = array(
			"title" => lang('reports_employees_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/bar",$data);
	}

	function graphical_summary_taxes($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_taxes');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_taxes');
		$model = $this->Summary_taxes;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_taxes_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_taxes_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_taxes_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_taxes');
		$model = $this->Summary_taxes;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['name']] = $row['tax'];
		}

		$data = array(
			"title" => lang('reports_taxes_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/bar",$data);
	}

	//Graphical summary customers report
	function graphical_summary_customers($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_customers');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_customers');
		$model = $this->Summary_customers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_customers_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_customers_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_customers_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		$this->load->model('reports/Summary_customers');
		$model = $this->Summary_customers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['customer']] = $row['total'];
		}

		$data = array(
			"title" => lang('reports_customers_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/pie",$data);
	}

	//Graphical summary discounts report
	function graphical_summary_discounts($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_discounts');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_discounts');
		$model = $this->Summary_discounts;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_discounts_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_discounts_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_discounts_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_discounts');
		$model = $this->Summary_discounts;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['discount_percent']] = $row['count'];
		}

		$data = array(
			"title" => lang('reports_discounts_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/bar",$data);
	}

	function graphical_summary_payments($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_payments');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_payments');
		$model = $this->Summary_payments;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$sale_ids = $model->get_sale_ids_for_payments();
		$this->Sale->create_sales_items_temp_table(array('sale_ids' => $sale_ids, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_payments_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_payments_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_payments_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_payments');
		$model = $this->Summary_payments;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$sale_ids = $model->get_sale_ids_for_payments();
		$this->Sale->create_sales_items_temp_table(array('sale_ids' => $sale_ids, 'sale_type' => $sale_type));
		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['payment_type']] = $row['payment_amount'];
		}

		$data = array(
			"title" => lang('reports_payments_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/bar",$data);
	}
	function specific_customer_input()
	{
		$data = $this->_get_common_report_data(TRUE);
		$data['specific_input_name'] = lang('reports_customer');
		$data['search_suggestion_url'] = site_url('reports/customer_search');
		$this->load->view("reports/specific_input",$data);
	}
	
	function specific_customer_store_account_input()
	{
		$data = $this->_get_common_report_data(TRUE);
		$data['specific_input_name'] = lang('reports_customer');
		$data['search_suggestion_url'] = site_url('reports/customer_search');
		$this->load->view("reports/specific_input",$data);
	}

	function specific_customer($start_date, $end_date, $customer_id, $sale_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_customers');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Specific_customer');
		$model = $this->Specific_customer;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'customer_id' =>$customer_id, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel'=>$export_excel));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'customer_id' =>$customer_id, 'sale_type' => $sale_type));
		
		$config = array();
		$config['base_url'] = site_url("reports/specific_customer/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$customer_id/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 8;
		$this->pagination->initialize($config);
		
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();


		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data_row = array();
		
			$summary_data_row[] = array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=> 'left');
			$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['sale_time'])), 'align'=> 'left');
			$summary_data_row[] = array('data'=>$row['register_name'], 'align'=> 'left');
			$summary_data_row[] = array('data'=>to_quantity($row['items_purchased']), 'align'=> 'left');
			$summary_data_row[] = array('data'=>$row['employee_name'].($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/'. $row['sold_by_employee']: ''), 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>to_currency($row['total']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>to_currency($row['tax']), 'align'=> 'right');
			if($this->has_profit_permission)
			{
				$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
		
			$summary_data_row[] = array('data'=>$row['payment_type'], 'align'=>'right');
			$summary_data_row[] = array('data'=>$row['comment'], 'align'=>'right');
			$summary_data[$key] = $summary_data_row;
			

			foreach($report_data['details'][$key] as $drow)
			{
				$details_data_row = array();
				$details_data_row[] = array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['category'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['serialnumber'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['description'], 'align'=>'left');
				$details_data_row[] = array('data'=>to_quantity($drow['quantity_purchased']), 'align'=>'left');
				
				$details_data_row[] = array('data'=>to_currency($drow['subtotal']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['total']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['tax']), 'align'=>'right');
				
				if($this->has_profit_permission)
				{
					$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');					
				}
				$details_data_row[] = array('data'=>$drow['discount_percent'].'%', 'align'=> 'left');
				
				$details_data[$key][] = $details_data_row;
			}
		}

		$customer_info = $this->Customer->get_info($customer_id);
		$data = array(
			"title" => $customer_info->first_name .' '. $customer_info->last_name.' '.lang('reports_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}
	
	function specific_customer_store_account($start_date, $end_date, $customer_id, $sale_type, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_store_account');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Specific_customer_store_account');
		$model = $this->Specific_customer_store_account;		
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'customer_id' =>$customer_id, 'sale_type' => $sale_type, 'offset'=> $offset, 'export_excel' => $export_excel));
		$config = array();
		$config['base_url'] = site_url("reports/specific_customer_store_account/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$customer_id/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 8;
		$this->pagination->initialize($config);

		

		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$tabular_data = array();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['sno'], 'align'=> 'left'),
									array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['date'])), 'align'=> 'left'),
									array('data'=>$row['sale_id'] ? anchor('sales/receipt/'.$row['sale_id'], $this->config->item('sale_prefix').' '.$row['sale_id'], array('target' => '_blank')) : '-', 'align'=> 'center'),
									array('data'=> $row['transaction_amount'] > 0 ? to_currency($row['transaction_amount']) : to_currency(0), 'align'=> 'right'),
									array('data'=>$row['transaction_amount'] < 0 ? to_currency($row['transaction_amount'] * -1)  : to_currency(0), 'align'=> 'right'),
									array('data'=>to_currency($row['balance']), 'align'=> 'right'),
									array('data'=>$row['items'], 'align'=> 'left'),
									array('data'=>$row['comment'], 'align'=> 'left'));
									
		}

		$customer_info = $this->Customer->get_info($customer_id);
		
		if ($customer_info->company_name)
		{
			$customer_title = $customer_info->company_name.' ('.$customer_info->first_name .' '. $customer_info->last_name.')';
		}
		else
		{
			$customer_title = $customer_info->first_name .' '. $customer_info->last_name;		
		}
		$data = array(
			"title" => lang('reports_detailed_store_account_report').$customer_title,
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $headers,
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);

	}
	
	function store_account_statements_input()
	{
		$data = $this->_get_common_report_data();
		
		$data['search_suggestion_url'] = site_url('reports/customer_search');		
		$this->load->view('reports/store_account_statements_input', $data);

	}
	
	function store_account_statements($customer_id = -1, $start_date, $end_date, $hide_items = 0, $pull_payments_by = 'payment_date', $offset=0)
	{
		$this->check_action_permission('view_store_account');
		$this->load->model('reports/Store_account_statements');
		$model = $this->Store_account_statements;
		$model->setParams(array('customer_id' =>$customer_id,'offset' => $offset, 'start_date' => $start_date, 'end_date'=>$end_date, 'pull_payments_by' => $pull_payments_by));
		$config = array();
		$config['base_url'] = site_url("reports/store_account_statements/$customer_id/$start_date/$end_date/$hide_items/$pull_payments_by");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 8;
		$this->pagination->initialize($config);
		
		$report_data = $model->getData();
		
		$customer_info = $this->Customer->get_info($customer_id);
		$data = array(
			"title" => lang('reports_store_account_statements'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			'report_data' => $report_data,
			'hide_items' => $hide_items,
			"pagination" => $this->pagination->create_links(),
			'date_column' => $pull_payments_by == 'payment_date' ? 'date' : 'sale_time',
		);
		
		$this->load->view("reports/store_account_statements",$data);
		
	}

	function specific_employee_input()
	{
		$data = $this->_get_common_report_data(TRUE);
		$data['specific_input_name'] = lang('reports_employee');

		$employees = array();
		foreach($this->Employee->get_all()->result() as $employee)
		{
			$employees[$employee->person_id] = $employee->first_name .' '.$employee->last_name;
		}
		$data['specific_input_data'] = $employees;
		$this->load->view("reports/specific_employee_input",$data);
	}

	function specific_employee($start_date, $end_date, $employee_id, $sale_type, $employee_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_employees');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Specific_employee');
		$model = $this->Specific_employee;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'employee_id' =>$employee_id, 'sale_type' => $sale_type, 'employee_type' => $employee_type, 'offset' => $offset, 'export_excel'=> $export_excel));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'employee_id' =>$employee_id, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/specific_employee/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$employee_id/$sale_type/$employee_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 9;
		$this->pagination->initialize($config);
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();

		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data_row = array();
		
			$summary_data_row[] = array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=> 'left');
			$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['sale_time'])), 'align'=> 'left');
			$summary_data_row[] = array('data'=>$row['register_name'], 'align'=> 'left');
			$summary_data_row[] = array('data'=>to_quantity($row['items_purchased']), 'align'=> 'left');
			$summary_data_row[] = array('data'=>$row['customer_name'], 'align'=> 'left');
			$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>to_currency($row['total']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>to_currency($row['tax']), 'align'=> 'right');
			if($this->has_profit_permission)
			{
				$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
		
			$summary_data_row[] = array('data'=>$row['payment_type'], 'align'=>'right');
			$summary_data_row[] = array('data'=>$row['comment'], 'align'=>'right');
			$summary_data[$key] = $summary_data_row;
			

			foreach($report_data['details'][$key] as $drow)
			{
				$details_data_row = array();
				$details_data_row[] = array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['category'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['serialnumber'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['description'], 'align'=>'left');
				$details_data_row[] = array('data'=>to_quantity($drow['quantity_purchased']), 'align'=>'left');
				
				$details_data_row[] = array('data'=>to_currency($drow['subtotal']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['total']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['tax']), 'align'=>'right');
				
				if($this->has_profit_permission)
				{
					$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');					
				}
				$details_data_row[] = array('data'=>$drow['discount_percent'].'%', 'align'=> 'left');
				
				$details_data[$key][] = $details_data_row;
			}
		}
		$employee_info = $this->Employee->get_info($employee_id);
		$data = array(
			"title" => $employee_info->first_name .' '. $employee_info->last_name.' '.lang('reports_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}

	function detailed_sales($start_date, $end_date, $sale_type, $export_excel=0, $offset = 0)
	{				
		$this->check_action_permission('view_sales');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Detailed_sales');
		$model = $this->Detailed_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));
		
		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/detailed_sales/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);

		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();

		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data_row = array();

			$link = site_url('reports/specific_customer/'.$start_date.'/'.$end_date.'/'.$row['customer_id'].'/all/0');
			
			$summary_data_row[] = array('data'=>anchor('sales/receipt/'.$row['sale_id'], '<i class="fa fa-print fa fa-2x"></i>', array('target' => '_blank', 'class'=>'hidden-print')).'<span class="visible-print">'.$row['sale_id'].'</span>'.anchor('sales/edit/'.$row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')).' '.anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank','class'=>'hidden-print')), 'align'=>'left');
			$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['sale_time'])), 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['register_name'], 'align'=>'left');
			$summary_data_row[] = array('data'=>to_quantity($row['items_purchased']), 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['employee_name'].($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/'. $row['sold_by_employee']: ''), 'align'=>'left');
			$summary_data_row[] = array('data'=>'<a href="'.$link.'" target="_blank">'.$row['customer_name'].'</a>', 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=>'right');
			$summary_data_row[] = array('data'=>to_currency($row['total']), 'align'=>'right');
			$summary_data_row[] = array('data'=>to_currency($row['tax']), 'align'=>'right');
			
			if($this->has_profit_permission)
			{
				$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			
			$summary_data_row[] = array('data'=>$row['payment_type'], 'align'=>'right');
			$summary_data_row[] = array('data'=>$row['comment'], 'align'=>'right');
			$summary_data[$key] = $summary_data_row;
			
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data_row = array();
				
				$details_data_row[] = array('data'=>isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align'=>'left');
				$details_data_row[] = array('data'=>isset($drow['item_product_id']) ? $drow['item_product_id'] : $drow['item_kit_product_id'], 'align'=>'left');
				$details_data_row[] = array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['category'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['size'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['serialnumber'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['description'], 'align'=>'left');
				$details_data_row[] = array('data'=>to_quantity($drow['quantity_purchased']), 'align'=>'left');
				$details_data_row[] = array('data'=>to_currency($drow['subtotal']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['total']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['tax']), 'align'=>'right');
				
				if($this->has_profit_permission)
				{
					$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');					
				}
				
				$details_data_row[] = array('data'=>$drow['discount_percent'].'%', 'align'=>'left');
				$details_data[$key][] = $details_data_row;
			}
		}

		$data = array(
			"title" =>lang('reports_detailed_sales_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}
  	
	function detailed_payments($start_date, $end_date, $sale_type, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_payments');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);
		
		$this->load->model('reports/Detailed_payments');
		$model = $this->Detailed_payments;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset'=> $offset, 'export_excel' => $export_excel));
		$sale_ids = $model->get_sale_ids_for_payments();
		$this->Sale->create_sales_items_temp_table(array('sale_ids' => $sale_ids, 'sale_type' => $sale_type));
		
		$config = array();
		$config['base_url'] = site_url("reports/detailed_payments/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);

		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();

		
		foreach($report_data['summary'] as $sale_id=>$row)
		{			
			foreach($row as $payment_type => $payment_data_row)
			{
				$summary_data_row = array();
				$summary_data_row[] = array('data'=>anchor('sales/receipt/'.$payment_data_row['sale_id'], '<i class="fa fa-print fa fa-2x"></i>', array('target' => '_blank', 'class'=>'hidden-print')).'<span class="visible-print">'.$payment_data_row['sale_id'].'</span>'.anchor('sales/edit/'.$payment_data_row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')).' '.anchor('sales/edit/'.$payment_data_row['sale_id'], lang('common_edit').' '.$payment_data_row['sale_id'], array('target' => '_blank','class'=>'hidden-print')), 'align'=>'left');
				$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($payment_data_row['sale_time'])), 'align'=>'left');
				$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($payment_data_row['payment_date'])), 'align'=>'left');
				$summary_data_row[] = array('data'=>$payment_data_row['payment_type'], 'align'=>'left');
				$summary_data_row[] = array('data'=>to_currency($payment_data_row['payment_amount']), 'align'=>'right');

				$summary_data[$sale_id.'|'.$payment_type] = $summary_data_row;
			}
		}

		$temp_details_data = array();
		
		foreach($report_data['details']['sale_ids'] as $sale_id => $drows)
		{
			$payment_types = array();
			foreach ($drows as $drow)
			{
				$payment_types[$drow['payment_type']] = TRUE;
			}
			
			foreach(array_keys($payment_types) as $payment_type)
			{
				foreach ($drows as $drow)
				{
					$details_data_row = array();

					$details_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($drow['payment_date'])), 'align'=>'left');
					$details_data_row[] = array('data'=>$drow['payment_type'], 'align'=>'left');
					$details_data_row[] = array('data'=>to_currency($drow['payment_amount']), 'align'=>'right');

					$details_data[$sale_id.'|'.$payment_type][] = $details_data_row;
				}
			}
		}
	
		$data = array(
			"title" =>lang('reports_detailed_payments_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}
	
	function detailed_suspended_sales($start_date, $end_date, $sale_type, $export_excel=0, $offset = 0)
	{				
		$this->check_action_permission('view_suspended_sales');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Detailed_suspended_sales');
		$model = $this->Detailed_suspended_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel, 'force_suspended' => true));
		
		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'force_suspended' => true));
		$config = array();
		$config['base_url'] = site_url("reports/detailed_suspended_sales/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);

		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();

		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data_row = array();

			$link = site_url('reports/specific_customer/'.$start_date.'/'.$end_date.'/'.$row['customer_id'].'/all/0');
			
			$summary_data_row[] = array('data'=>anchor('sales/receipt/'.$row['sale_id'], '<i class="fa fa-print fa fa-2x"></i>', array('target' => '_blank', 'class'=>'hidden-print')).'<span class="visible-print">'.$row['sale_id'].'</span>'.anchor('sales/edit/'.$row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')).' '.anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank','class'=>'hidden-print')), 'align'=>'left');
			$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['sale_time'])), 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['register_name'], 'align'=>'left');
			$summary_data_row[] = array('data'=>to_quantity($row['items_purchased']), 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['employee_name'].($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/'. $row['sold_by_employee']: ''), 'align'=>'left');
			$summary_data_row[] = array('data'=>'<a href="'.$link.'" target="_blank">'.$row['customer_name'].'</a>', 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=>'right');
			$summary_data_row[] = array('data'=>to_currency($row['total']), 'align'=>'right');
			$summary_data_row[] = array('data'=>to_currency($row['tax']), 'align'=>'right');
			
			if($this->has_profit_permission)
			{
				$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			
			$summary_data_row[] = array('data'=>$row['payment_type'], 'align'=>'right');
			$summary_data_row[] = array('data'=>$row['comment'], 'align'=>'right');
			$summary_data_row[] = array('data'=> $row['suspended']== 1  ? lang('sales_layaway') : lang('sales_estimate'), 'align'=>'right');
			
			$summary_data[$key] = $summary_data_row;
			
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data_row = array();
				
				$details_data_row[] = array('data'=>isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align'=>'left');
				$details_data_row[] = array('data'=>isset($drow['item_product_id']) ? $drow['item_product_id'] : $drow['item_kit_product_id'], 'align'=>'left');
				$details_data_row[] = array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['category'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['size'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['serialnumber'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['description'], 'align'=>'left');
				$details_data_row[] = array('data'=>to_quantity($drow['quantity_purchased']), 'align'=>'left');
				$details_data_row[] = array('data'=>to_currency($drow['subtotal']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['total']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['tax']), 'align'=>'right');
				
				if($this->has_profit_permission)
				{
					$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');					
				}
				
				$details_data_row[] = array('data'=>$drow['discount_percent'].'%', 'align'=>'left');
				$details_data[$key][] = $details_data_row;
			}
		}

		$data = array(
			"title" =>lang('reports_detailed_suspended_sales_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}
   
	function specific_supplier_input()
	{
		$data = $this->_get_common_report_data(TRUE);
		$data['specific_input_name'] = lang('reports_supplier');
		$data['search_suggestion_url'] = site_url('reports/supplier_search');
		$this->load->view("reports/specific_input",$data);
	}

	function specific_supplier($start_date, $end_date, $supplier_id, $sale_type, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_suppliers');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Specific_supplier');
		$model = $this->Specific_supplier;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'supplier_id' =>$supplier_id, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'supplier_id' =>$supplier_id, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/specific_supplier/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$supplier_id/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 8;
		$this->pagination->initialize($config);
		
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();

		foreach($report_data['summary'] as $key=>$row)
		{			
			$summary_data_row = array();
		
			$summary_data_row[] = array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=> 'left');
			$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['sale_time'])), 'align'=> 'left');
			$summary_data_row[] = array('data'=>$row['register_name'], 'align'=> 'left');
			$summary_data_row[] = array('data'=>to_quantity($row['items_purchased']), 'align'=> 'left');
			$summary_data_row[] = array('data'=>$row['customer_name'], 'align'=> 'left');
			$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>to_currency($row['total']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>to_currency($row['tax']), 'align'=> 'right');
			if($this->has_profit_permission)
			{
				$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
		
			$summary_data_row[] = array('data'=>$row['payment_type'], 'align'=>'right');
			$summary_data_row[] = array('data'=>$row['comment'], 'align'=>'right');
			$summary_data[$key] = $summary_data_row;
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data_row = array();
				$details_data_row[] =  array('data'=>isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align'=>'left');
				$details_data_row[] = array('data'=>isset($drow['item_product_id']) ? $drow['item_product_id'] : $drow['item_kit_product_id'], 'align'=>'left');
				$details_data_row[] = array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=> 'left');
				$details_data_row[] = array('data'=>$drow['category'], 'align'=> 'left');
				$details_data_row[] = array('data'=>$drow['serialnumber'], 'align'=> 'left');
				$details_data_row[] = array('data'=>$drow['description'], 'align'=> 'left');
				$details_data_row[] = array('data'=>to_quantity($drow['quantity_purchased']), 'align'=> 'left');
				$details_data_row[] = array('data'=>to_currency($drow['subtotal']), 'align'=> 'right');
				$details_data_row[] = array('data'=>to_currency($drow['total']), 'align'=> 'right');
				$details_data_row[] = array('data'=>to_currency($drow['tax']), 'align'=> 'right');
				
				if($this->has_profit_permission)
				{
					$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');					
				}
				$details_data_row[] = array('data'=>$drow['discount_percent'].'%', 'align'=> 'left');
				
				$details_data[$key][] = $details_data_row;
			}	
		}

		$supplier_info = $this->Supplier->get_info($supplier_id);
		$data = array(
					"title" => $supplier_info->first_name .' '. $supplier_info->last_name.' '.lang('reports_report'),
					"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
					"headers" => $model->getDataColumns(),
					"summary_data" => $summary_data,
					"details_data" => $details_data,
					"overall_summary_data" => $model->getSummaryData(),
					"export_excel" => $export_excel,
					"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}



	function deleted_sales($start_date, $end_date, $sale_type, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_deleted_sales');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);
	
		$this->load->model('reports/Deleted_sales');
		$model = $this->Deleted_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/deleted_sales/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();

		foreach($report_data['summary'] as $key=>$row)
		{
			
			$summary_data_row = array();

			$summary_data_row[] = array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=>'left');
			$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['sale_time'])), 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['register_name'], 'align'=>'left');
			$summary_data_row[] = array('data'=>to_quantity($row['items_purchased']), 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['deleted_by'], 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['employee_name'].($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/'. $row['sold_by_employee']: ''), 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['customer_name'], 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=>'right');
			$summary_data_row[] = array('data'=>to_currency($row['total']), 'align'=>'right');
			$summary_data_row[] = array('data'=>to_currency($row['tax']), 'align'=>'right');
			if($this->has_profit_permission)
			{
				$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			$summary_data_row[] = array('data'=>$row['payment_type'], 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['comment'], 'align'=>'left');
			
			
			$summary_data[$key] = $summary_data_row;
			
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data_row = array();
				$details_data_row[] = array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['category'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['serialnumber'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['description'], 'align'=>'left');
				$details_data_row[] = array('data'=>to_quantity($drow['quantity_purchased']), 'align'=>'left');
				$details_data_row[] = array('data'=>to_currency($drow['subtotal']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['total']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['tax']), 'align'=>'right');
				if($this->has_profit_permission)
				{
					$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');
				}
				
				$details_data_row[] = array('data'=>$drow['discount_percent'].'%', 'align'=>'left');
				
				$details_data[$key][] = $details_data_row;
			}
		}

		$data = array(
			"title" =>lang('reports_deleted_sales_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			'pagination' => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}

	function detailed_receivings($start_date, $end_date, $sale_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_receivings');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Detailed_receivings');
		$model = $this->Detailed_receivings;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));

		$this->Receiving->create_receivings_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/detailed_receivings/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);
		

		$headers = $model->getDataColumns();
		$report_data = $model->getData();
		
		$summary_data = array();
		$details_data = array();

		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[$key] = array(array('data'=>anchor('receivings/edit/'.$row['receiving_id'], 'RECV '.$row['receiving_id'], array('target' => '_blank')), 'align'=> 'left'), array('data'=>date(get_date_format(), strtotime($row['receiving_date'])), 'align'=> 'left'), array('data'=>to_quantity($row['items_purchased']), 'align'=> 'left'), array('data'=>$row['employee_name'], 'align'=> 'left'), array('data'=>$row['supplier_name'], 'align'=> 'left'), array('data'=>to_currency($row['total'],10), 'align'=> 'right'), array('data'=>$row['payment_type'], 'align'=> 'left'), array('data'=>$row['comment'], 'align'=> 'left'));

			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array(array('data'=>$drow['name'], 'align'=> 'left'),array('data'=>$drow['product_id'], 'align'=> 'left'), array('data'=>$drow['category'], 'align'=> 'left'), array('data'=>$drow['size'], 'align'=> 'left'), array('data'=>to_quantity($drow['quantity_purchased']), 'align'=> 'left'), array('data'=>to_currency($drow['total'],10), 'align'=> 'right'), array('data'=>$drow['discount_percent'].'%', 'align'=> 'left'));
			}
		}

		$data = array(
			"title" =>lang('reports_detailed_receivings_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}

	function excel_export()
	{
		$this->load->view("reports/excel_export",array());
	}

    function inventory_input()
	{
		$data = $this->_get_common_report_data(TRUE);
		$data['specific_input_name'] = lang('reports_supplier');

		$suppliers = array();
		
		$suppliers[-1] = lang('common_all');
		foreach($this->Supplier->get_all()->result() as $supplier)
		{
			$suppliers[$supplier->person_id] = $supplier->company_name. ' ('.$supplier->first_name .' '.$supplier->last_name.')';
		}
		$data['specific_input_data'] = $suppliers;
		$this->load->view("reports/inventory_input",$data);
	}

	function inventory_low($supplier = -1, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_inventory_reports');
		$this->load->model('reports/Inventory_low');
		$model = $this->Inventory_low;
		$model->setParams(array('supplier'=>$supplier, 'export_excel' => $export_excel, 'offset'=>$offset));
		
		$config = array();
		$config['base_url'] = site_url("reports/inventory_low/$supplier/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 5;
		$this->pagination->initialize($config);
		
		$tabular_data = array();
		$report_data = $model->getData();
		foreach($report_data as $row)
		{
			$data_row = array();
			
			$data_row[] = array('data'=>$row['name'], 'align' => 'left');
			$data_row[] = array('data'=>$row['category'], 'align'=> 'left');
			$data_row[] = array('data'=>$row['company_name'], 'align'=> 'left');
			$data_row[] = array('data'=>$row['item_number'], 'align'=> 'left');
			$data_row[] = array('data'=>$row['product_id'], 'align'=> 'left');
			$data_row[] = array('data'=>$row['description'], 'align'=> 'left');
			if($this->has_cost_price_permission)
			{
				$data_row[] = array('data'=>to_currency($row['cost_price']), 'align'=> 'right');
			}
			$data_row[] = array('data'=>to_currency($row['unit_price']), 'align'=> 'right');
			$data_row[] = array('data'=>to_quantity($row['quantity']), 'align'=> 'left');
			$data_row[] = array('data'=>to_quantity($row['reorder_level']), 'align'=> 'left');
			
			$tabular_data[] = $data_row;				
			
		}

		$data = array(
			"title" => lang('reports_low_inventory_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}

	function inventory_summary($supplier = -1, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_inventory_reports');
		$this->load->model('reports/Inventory_summary');
		$model = $this->Inventory_summary;
		$model->setParams(array('supplier'=>$supplier, 'export_excel' => $export_excel, 'offset'=>$offset));
		
		$config = array();
		$config['base_url'] = site_url("reports/inventory_summary/$supplier/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 5;
		$this->pagination->initialize($config);
		
		
		$tabular_data = array();
		$report_data = $model->getData();
		foreach($report_data as $row)
		{
			$data_row = array();
			
			$data_row[] = array('data'=>$row['name'], 'align' => 'left');
			$data_row[] = array('data'=>$row['category'], 'align'=> 'left');
			$data_row[] = array('data'=>$row['company_name'], 'align'=> 'left');
			$data_row[] = array('data'=>$row['item_number'], 'align'=> 'left');
			$data_row[] = array('data'=>$row['product_id'], 'align'=> 'left');
			$data_row[] = array('data'=>$row['description'], 'align'=> 'left');
			if($this->has_cost_price_permission)
			{
				$data_row[] = array('data'=>to_currency($row['cost_price']), 'align'=> 'right');
			}
			$data_row[] = array('data'=>to_currency($row['unit_price']), 'align'=> 'right');
			$data_row[] = array('data'=>to_quantity($row['quantity']), 'align'=> 'left');
			$data_row[] = array('data'=>to_quantity($row['reorder_level']), 'align'=> 'left');
			
			$tabular_data[] = $data_row;				
			
		}

		$data = array(
			"title" => lang('reports_inventory_summary_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}

	function summary_giftcards($export_excel = 0, $offset = 0)
	{
		$this->check_action_permission('view_giftcards');
		$this->load->model('reports/Summary_giftcards');
		$model = $this->Summary_giftcards;
		$model->setParams(array('export_excel' => $export_excel, 'offset' => $offset));
		$config = array();
		$config['base_url'] = site_url("reports/summary_giftcards/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config);
		
		$tabular_data = array();
		$report_data = $model->getData();
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['giftcard_number'], 'align'=> 'left'),array('data'=>to_currency($row['value']), 'align'=> 'left'), array('data'=>$row['customer_name'], 'align'=> 'left'));
		}

		$data = array(
			"title" => lang('reports_giftcard_summary_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}
		
	function summary_store_accounts($export_excel = 0, $offset=0)
	{
		$this->check_action_permission('view_store_account');
		$this->load->model('reports/Summary_store_accounts');
		$model = $this->Summary_store_accounts;
		$model->setParams(array('export_excel' => $export_excel, 'offset' => $offset));
		
		$config = array();
		$config['base_url'] = site_url("reports/summary_store_accounts/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config);
		
		$tabular_data = array();
		$report_data = $model->getData();
		$sno=1;
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$sno, 'align'=> 'left'),array('data'=>$row['customer'], 'align'=> 'left'), array('data'=>to_currency($row['balance']), 'align'=> 'right'), array('data'=>anchor("customers/pay_now/".$row['person_id'],lang('customers_pay'),array('title'=>lang('customers_update'))), 'align'=> 'right'));
			$sno++;
		}

		$data = array(
			"title" => lang('reports_store_account_summary_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			'pagination' => $this->pagination->create_links()
		);

		$this->load->view("reports/tabular",$data);
	}

	function detailed_giftcards_input()
	{
		$data['specific_input_name'] = lang('reports_customer');
		$data['search_suggestion_url'] = site_url('reports/customer_search');
		$this->load->view("reports/detailed_giftcards_input",$data);
	}

	function detailed_giftcards($customer_id, $export_excel = 0, $offset=0)
	{
		$this->check_action_permission('view_giftcards');
		$this->load->model('reports/Detailed_giftcards');
		$model = $this->Detailed_giftcards;
		$model->setParams(array('customer_id' =>$customer_id, 'offset' => $offset, 'export_excel' => $export_excel));

		$this->Sale->create_sales_items_temp_table(array('customer_id' =>$customer_id));
		
		$config = array();
		$config['base_url'] = site_url("reports/detailed_giftcards/$customer_id/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 5;
		$this->pagination->initialize($config);
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();

		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data_row = array();
			
			$summary_data_row[] = array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=> 'left');
			$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['sale_time'])), 'align'=> 'left');
			$summary_data_row[] = array('data'=>$row['register_name'], 'align'=>'left');
			$summary_data_row[] = array('data'=>to_quantity($row['items_purchased']), 'align'=> 'left');
			$summary_data_row[] = array('data'=>$row['customer_name'], 'align'=> 'left');
			$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>to_currency($row['total']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>to_currency($row['tax']), 'align'=> 'right');
			
			if($this->has_profit_permission)
			{
				$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			
			$summary_data_row[] = array('data'=>$row['payment_type'], 'align'=>'right');
			$summary_data_row[] = array('data'=>$row['comment'], 'align'=>'right');
			$summary_data[$key] = $summary_data_row;
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data_row = array();
				
				$details_data_row[] = array('data'=>isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align'=>'left');
				$details_data_row[] = array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['category'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['serialnumber'], 'align'=>'left');;
				$details_data_row[] = array('data'=>$drow['description'], 'align'=>'left');
				$details_data_row[] = array('data'=>to_quantity($drow['quantity_purchased']), 'align'=>'left');
				$details_data_row[] = array('data'=>to_currency($drow['subtotal']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['total']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['tax']), 'align'=>'right');
				
				if($this->has_profit_permission)
				{
					$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');					
				}
				
				$details_data_row[] = array('data'=>$drow['discount_percent'].'%', 'align'=>'left');
				$details_data[$key][] = $details_data_row;
			}
		}
		$customer_info = $this->Customer->get_info($customer_id);
		$data = array(
			"title" => $customer_info->first_name .' '. $customer_info->last_name.' '.lang('giftcards_giftcard'). ' '.lang('reports_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}
	
	function date_input_profit_and_loss()
	{
		$data = $this->_get_common_report_data();
		$this->load->view("reports/date_input_profit_and_loss",$data);	
	}
	
	function detailed_profit_and_loss($start_date, $end_date)
	{
		$this->check_action_permission('view_profit_and_loss');
		$this->load->model('reports/Detailed_profit_and_loss');
		$model = $this->Detailed_profit_and_loss;
		$end_date=date('Y-m-d 23:59:59', strtotime($end_date));

		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date));
		$this->Receiving->create_receivings_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date));
	
		$data = array(
			"title" =>lang('reports_detailed_profit_and_loss'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"details_data" => $model->getData(),
			"overall_summary_data" => $model->getSummaryData(),
		);

		$this->load->view("reports/profit_and_loss_details",$data);
	}
	
	function summary_profit_and_loss($start_date, $end_date)
	{
		$this->check_action_permission('view_profit_and_loss');
		$this->load->model('reports/Summary_profit_and_loss');
		$model = $this->Summary_profit_and_loss;
		$end_date=date('Y-m-d 23:59:59', strtotime($end_date));
		
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date));
		$this->Receiving->create_receivings_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date));
	
		$data = array(
			"title" =>lang('reports_detailed_profit_and_loss'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"details_data" => $model->getData(),
			"overall_summary_data" => $model->getSummaryData(),
		);
		
		$this->load->view("reports/profit_and_loss_summary",$data);
	}
	
	function detailed_inventory_input()
	{
		$data = $this->_get_common_report_data(TRUE);
		$this->load->view("reports/detailed_inventory_input",$data);	
	}
	
	function detailed_inventory($start_date, $end_date, $show_manual_adjustments_only, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_inventory_reports');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);
		$this->load->model('reports/Detailed_inventory');
		$model = $this->Detailed_inventory;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date,'show_manual_adjustments_only' => $show_manual_adjustments_only, 'offset' => $offset, 'export_excel' => $export_excel));
		
		$config = array();
		$config['base_url'] = site_url("reports/detailed_inventory/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$show_manual_adjustments_only/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);
		
		$tabular_data = array();
		$report_data = $model->getData();
		foreach($report_data as $row)
		{
			$row['trans_comment'] = preg_replace('/'.$this->config->item('sale_prefix').' ([0-9]+)/', anchor('sales/receipt/$1', $row['trans_comment']), $row['trans_comment']);
			
			$tabular_data[] = array(array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['trans_date'])), 'align'=>'left'),
			array('data'=>$row['name'], 'align'=>'left'),
			array('data'=>$row['category'], 'align'=>'left'),
			array('data'=>$row['item_number'], 'align'=>'left'),
			array('data'=>$row['product_id'], 'align'=>'left'),
			array('data'=>to_quantity($row['trans_inventory']), 'align'=>'left'),
			array('data'=>$row['trans_comment'], 'align'=>'left'),
		);
		}

		$data = array(
			"title" => lang('reports_detailed_inventory_report'),
			"subtitle" => lang('reports_detailed_inventory_report')." - ".date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date))." - ".$config['total_rows'].' '.lang('reports_sales_report_generator_results_found'),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}
	
	//Summary employees report
	function summary_commissions($start_date, $end_date, $sale_type, $employee_type, $export_excel=0, $offset = 0)
	{
		$this->check_action_permission('view_commissions');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_commissions');
		$model = $this->Summary_commissions;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'employee_type' =>$employee_type, 'export_excel' => $export_excel, 'offset' => $offset));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/summary_commissions/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$employee_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 8;		
		$this->pagination->initialize($config);

		$tabular_data = array();
		$report_data = $model->getData();

		foreach($report_data as $row)
		{
			$data_row = array();
			
			$data_row[] = array('data'=>$row['employee'], 'align' => 'left');
			$data_row[] = array('data'=>to_currency($row['subtotal']), 'align' => 'right');
			$data_row[] =  array('data'=>to_currency($row['total']), 'align' => 'right');
			$data_row[] = array('data'=>to_currency($row['tax']), 'align' => 'right');
			if($this->has_profit_permission)
			{
				$data_row[] = array('data'=>to_currency($row['profit']), 'align' => 'right');
			}
			$data_row[] = array('data'=>to_currency($row['commission']), 'align' => 'right');			
			$tabular_data[] = $data_row;			
		}

		$data = array(
			"title" => lang('reports_comissions_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular",$data);
	}
	
	function graphical_summary_commissions($start_date, $end_date, $sale_type, $employee_type)
	{
		$this->check_action_permission('view_commissions');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_commissions');
		$model = $this->Summary_commissions;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'employee_type' => $employee_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_comissions_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_commissions_graph/$start_date/$end_date/$sale_type/$employee_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}

	//The actual graph data
	function graphical_summary_commissions_graph($start_date, $end_date, $sale_type, $employee_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_commissions');
		$model = $this->Summary_commissions;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'employee_type' => $employee_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['employee']] = $row['commission'];
		}

		$data = array(
			"title" => lang('reports_comissions_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/bar",$data);
	}
	
	function detailed_commissions($start_date, $end_date, $employee_id, $sale_type, $employee_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_employees');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Detailed_commissions');
		$model = $this->Detailed_commissions;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'employee_id' =>$employee_id, 'sale_type' => $sale_type, 'employee_type' => $employee_type, 'offset' => $offset, 'export_excel'=> $export_excel));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'employee_id' =>$employee_id, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/detailed_commissions/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$employee_id/$sale_type/$employee_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 9;
		$this->pagination->initialize($config);
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();

		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data_row = array();
		
			$summary_data_row[] = array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=> 'left');
			$summary_data_row[] = array('data'=>date(get_date_format().'-'.get_time_format(), strtotime($row['sale_time'])), 'align'=> 'left');
			$summary_data_row[] = array('data'=>to_quantity($row['items_purchased']), 'align'=> 'left');
			$summary_data_row[] = array('data'=>$row['customer_name'], 'align'=> 'left');
			$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>to_currency($row['total']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>to_currency($row['tax']), 'align'=> 'right');
			if($this->has_profit_permission)
			{
				$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			$summary_data_row[] = array('data'=>to_currency($row['commission']), 'align'=> 'right');
			$summary_data_row[] = array('data'=>$row['payment_type'], 'align'=>'right');
			$summary_data_row[] = array('data'=>$row['comment'], 'align'=>'right');
			$summary_data[$key] = $summary_data_row;
			

			foreach($report_data['details'][$key] as $drow)
			{
				$details_data_row = array();
				$details_data_row[] = array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['category'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['serialnumber'], 'align'=>'left');
				$details_data_row[] = array('data'=>$drow['description'], 'align'=>'left');
				$details_data_row[] = array('data'=>to_quantity($drow['quantity_purchased']), 'align'=>'left');
				
				$details_data_row[] = array('data'=>to_currency($drow['subtotal']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['total']), 'align'=>'right');
				$details_data_row[] = array('data'=>to_currency($drow['tax']), 'align'=>'right');
				
				if($this->has_profit_permission)
				{
					$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');					
				}
				$details_data_row[] = array('data'=>to_currency($drow['commission']), 'align'=>'right');					
				
				$details_data_row[] = array('data'=>$drow['discount_percent'].'%', 'align'=> 'left');
				
				$details_data[$key][] = $details_data_row;
			}
		}
		$employee_info = $this->Employee->get_info($employee_id);
		$data = array(
			"title" => $employee_info->first_name .' '. $employee_info->last_name.' '.lang('reports_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}
	
	function customer_search()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Customer->get_customer_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	function supplier_search()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Supplier->get_suppliers_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
        
        //sumary loan report
	function summary_loans($start_date, $end_date, $sale_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_loans');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_loans');
		$model = $this->Summary_loans;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));

		$this->Loan->create_loans_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
	
		$config = array();
		$config['base_url'] = site_url("reports/summary_loans/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);
	
		$tabular_data = array();
		$report_data = $model->getData();
                $i=1;
		foreach($report_data as $row)
		{
			$data_row = array();
			
                        $data_row[] = array('data'=>$i, 'align'=> 'right');
			$data_row[] = array('data'=>date(get_date_format(), strtotime($row['borrow_date'])), 'align'=>'left');
			$data_row[] = array('data'=>to_currency($row['amount']), 'align'=>'right');
			$data_row[] = array('data'=>to_currency($row['paid_princ']), 'align'=>'right');
			$data_row[] = array('data'=>to_currency($row['amount_due']), 'align'=>'right');
			$data_row[] = array('data'=>to_currency($row['deposit']), 'align'=> 'right');
			$data_row[] = array('data'=>to_currency($row['rate']), 'align'=> 'right');
			$data_row[] = array('data'=>to_currency($row['paid_rate']), 'align'=> 'right');
			$data_row[] = array('data'=>$row['late'].' '. lang('common_day'), 'align'=> 'right');
			$data_row[] = array('data'=>to_currency($row['pay_fine']), 'align'=> 'right');
			
			if($this->has_profit_permission)
			{
				$data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			$tabular_data[] = $data_row;
                        $i++;
		}
		$data = array(
			"title" => lang('reports_loans_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .' '.lang('common_to').' '.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links()
		);

		$this->load->view("reports/tabular",$data);
	}
        
        //report of loan detail 
       function detailed_loans($start_date, $end_date, $sale_type, $export_excel=0, $offset = 0){				
		$this->check_action_permission('view_loans');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Detailed_loans');
		$model = $this->Detailed_loans;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));
		
		$this->Loan->create_loans_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/detailed_loans/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);

		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();
                $i=1;
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data_row = array();

			$summary_data_row[] = array('data'=>$i, 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['borrower'], 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['amount']), 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['paid_princ']), 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['amount_due']), 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['deposit']), 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=>'right');
			$summary_data_row[] = array('data'=>to_currency($row['rate']), 'align'=>'right');
			$summary_data_row[] = array('data'=>to_currency($row['paid_rate']), 'align'=>'right');
			
			if($this->has_profit_permission)
			{
				$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			
			$summary_data[$key] = $summary_data_row;
			
			$j = 1;
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data_row = array();
				
				$details_data_row[] = array('data'=>$j,'align'=>'left');
				$details_data_row[] = array('data'=>date(get_date_format(), strtotime($drow['borrow_date'])), 'align'=>'left');
				$details_data_row[] = array('data'=>to_currency($drow['amount']), 'align'=>'right');
                               	$details_data_row[]  = array('data'=>to_currency($drow['paid_princ']), 'align'=>'right');
                                $details_data_row[]  = array('data'=>to_currency($drow['amount_due']), 'align'=>'right');
                                $details_data_row[]  = array('data'=>to_currency($drow['deposit']), 'align'=> 'right');
                                $details_data_row[]  = array('data'=>to_currency($drow['rate']), 'align'=> 'right');
                                $details_data_row[]  = array('data'=>to_currency($drow['paid_rate']), 'align'=> 'right');
                                $details_data_row[]  = array('data'=>$drow['late'].' '. lang('common_day'), 'align'=> 'right');
                                $details_data_row[]  = array('data'=>to_currency($drow['pay_fine']), 'align'=> 'right');
				
				if($this->has_profit_permission)
				{
					$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');					
				}
                                
				$details_data[$key][] = $details_data_row;
                                $j++;
			}
                        $i++;
		}

		$data = array(
			"title" =>lang('reports_detailed_loans_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}
        
        //Graphical summary loans report
	function graphical_summary_loans($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_loans');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_loans');
		$model = $this->Summary_loans;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Loan->create_loans_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_loans_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_loans_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
        
        //The actual graph data
	function graphical_summary_loans_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_loans');
		$model = $this->Summary_loans;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Loan->create_loans_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[strtotime($row['borrow_date'])]= $row['amount'];
		}

		$data = array(
			"title" => lang('reports_loans_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/line",$data);

	}
        
        //sumary pawn report
	function summary_pawns($start_date, $end_date, $sale_type, $export_excel=0, $offset=0)
	{
		$this->check_action_permission('view_pawns');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Summary_pawns');
		$model = $this->Summary_pawns;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));

		$this->Pawn->create_pawns_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
	
		$config = array();
		$config['base_url'] = site_url("reports/summary_pawns/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);
	
		$tabular_data = array();
		$report_data = $model->getData();
                $i=1;
		foreach($report_data as $row)
		{
			$data_row = array();
			
                        $data_row[] = array('data'=>$i, 'align'=> 'right');
			$data_row[] = array('data'=>date(get_date_format(), strtotime($row['start_date'])), 'align'=>'left');
			$data_row[] = array('data'=>to_currency($row['amount']), 'align'=>'right');
			$data_row[] = array('data'=>to_currency($row['paid_princ']), 'align'=>'right');
			$data_row[] = array('data'=>to_currency($row['amount_due']), 'align'=>'right');
			//$data_row[] = array('data'=>to_currency($row['deposit']), 'align'=> 'right');
			$data_row[] = array('data'=>to_currency($row['rate']), 'align'=> 'right');
			$data_row[] = array('data'=>to_currency($row['paid_rate']), 'align'=> 'right');
			$data_row[] = array('data'=>$row['late'].' '. lang('common_day'), 'align'=> 'right');
			$data_row[] = array('data'=>to_currency($row['pay_fine']), 'align'=> 'right');
			
			if($this->has_profit_permission)
			{
				$data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			$tabular_data[] = $data_row;
                        $i++;
		}
		$data = array(
			"title" => lang('reports_pawns_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .' '.lang('common_to').' '.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links()
		);

		$this->load->view("reports/tabular",$data);
	}
        
         //report of pawn detail 
       function detailed_pawns($start_date, $end_date, $sale_type, $export_excel=0, $offset = 0){				
		$this->check_action_permission('view_pawns');
		$start_date=rawurldecode($start_date);
		$end_date=rawurldecode($end_date);

		$this->load->model('reports/Detailed_pawns');
		$model = $this->Detailed_pawns;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel));
		
		$this->Pawn->create_pawns_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$config = array();
		$config['base_url'] = site_url("reports/detailed_pawns/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$sale_type/$export_excel");
		$config['total_rows'] = $model->getTotalRows();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);

		$headers = $model->getDataColumns();
		$report_data = $model->getData();

		$summary_data = array();
		$details_data = array();
                $i=1;
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data_row = array();

			$summary_data_row[] = array('data'=>$i, 'align'=>'left');
			$summary_data_row[] = array('data'=>$row['borrower'], 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['amount']), 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['paid_princ']), 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['amount_due']), 'align'=>'left');
			//$summary_data_row[] = array('data'=>to_currency($row['deposit']), 'align'=>'left');
			$summary_data_row[] = array('data'=>to_currency($row['subtotal']), 'align'=>'right');
			$summary_data_row[] = array('data'=>to_currency($row['rate']), 'align'=>'right');
			$summary_data_row[] = array('data'=>to_currency($row['paid_rate']), 'align'=>'right');
			
			if($this->has_profit_permission)
			{
				$summary_data_row[] = array('data'=>to_currency($row['profit']), 'align'=>'right');
			}
			
			$summary_data[$key] = $summary_data_row;
			
			$j = 1;
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data_row = array();
				
				$details_data_row[] = array('data'=>$j,'align'=>'left');
				$details_data_row[] = array('data'=>date(get_date_format(), strtotime($drow['borrow_date'])), 'align'=>'left');
				$details_data_row[] = array('data'=>to_currency($drow['amount']), 'align'=>'right');
                               	$details_data_row[]  = array('data'=>to_currency($drow['paid_princ']), 'align'=>'right');
                                $details_data_row[]  = array('data'=>to_currency($drow['amount_due']), 'align'=>'right');
                                //$details_data_row[]  = array('data'=>to_currency($drow['deposit']), 'align'=> 'right');
                                $details_data_row[]  = array('data'=>to_currency($drow['rate']), 'align'=> 'right');
                                $details_data_row[]  = array('data'=>to_currency($drow['paid_rate']), 'align'=> 'right');
                                $details_data_row[]  = array('data'=>$drow['late'].' '. lang('common_day'), 'align'=> 'right');
                                $details_data_row[]  = array('data'=>to_currency($drow['pay_fine']), 'align'=> 'right');
				
				if($this->has_profit_permission)
				{
					$details_data_row[] = array('data'=>to_currency($drow['profit']), 'align'=>'right');					
				}
                                
				$details_data[$key][] = $details_data_row;
                                $j++;
			}
                        $i++;
		}

		$data = array(
			"title" =>lang('reports_detailed_pawns_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel,
			"pagination" => $this->pagination->create_links(),
		);

		$this->load->view("reports/tabular_details",$data);
	}
        
        //Graphical summary pawns report
	function graphical_summary_pawns($start_date, $end_date, $sale_type)
	{
		$this->check_action_permission('view_pawns');
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_pawns');
		$model = $this->Summary_pawns;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Pawn->create_pawns_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_pawns_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_pawns_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
        
        //The actual graph data
	function graphical_summary_pawns_graph($start_date, $end_date, $sale_type)
	{
		$start_date=rawurldecode($start_date);
		$end_date=date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
		
		$this->load->model('reports/Summary_pawns');
		$model = $this->Summary_pawns;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Pawn->create_pawns_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();

		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[strtotime($row['start_date'])]= $row['amount'];
		}

		$data = array(
			"title" => lang('reports_pawns_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/line",$data);

	}
}
?>