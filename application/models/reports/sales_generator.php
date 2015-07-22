<?php
require_once("report.php");
class Sales_generator extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		$return = array();
		
		$return['summary'] = array();
		$return['summary'][] = array('data'=>lang('reports_sale_id'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_date'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_register'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_items_purchased'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_sold_by'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_sold_to'), 'align'=> 'left');		
		$return['summary'][] = array('data'=>lang('reports_subtotal'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('reports_total'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('reports_tax'), 'align'=> 'right');
				
		if($this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
		{
			$return['summary'][] = array('data'=>lang('reports_profit'), 'align'=> 'right');
		}
		$return['summary'][] = array('data'=>lang('reports_payment_type'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('reports_comments'), 'align'=> 'right');
		
		$return['details'] = array();
		$return['details'][] = array('data'=>lang('reports_item_number'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('items_product_id'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_name'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_category'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('items_size'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_serial_number'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_description'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_quantity_purchased'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_subtotal'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('reports_total'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('reports_tax'), 'align'=> 'right');
		if($this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
		{
			$return['details'][] = array('data'=>lang('reports_profit'), 'align'=> 'right');			
		}
		$return['details'][] = array('data'=>lang('reports_discount'), 'align'=> 'right');
		
		return $return;
	}
	
	public function getData()
	{
		$data = array();
		$data['summary'] = array();
		$data['details'] = array();
		
		if ($this->params['matched_items_only'])
		{
			$this->db->select('sale_id, sale_time, register_name, sale_date, sum(quantity_purchased) as items_purchased, CONCAT(sold_by_employee.first_name," ",sold_by_employee.last_name) as sold_by_employee, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, payment_type, comment', false);
			$this->db->from('sales_items_temp');
			$this->db->join('people as employee', 'sales_items_temp.employee_id = employee.person_id');
			$this->db->join('people as sold_by_employee', 'sales_items_temp.sold_by_employee_id = sold_by_employee.person_id', 'left');
			$this->db->join('people as customer', 'sales_items_temp.customer_id = customer.person_id', 'left');			
			$this->_searchSalesQueryParams();
			$this->db->where('sales_items_temp.deleted', 0);
			$this->db->group_by('sale_id');
			$this->db->order_by('sale_time');
			
			//If we are exporting NOT exporting to excel make sure to use offset and limit
			if (isset($this->params['export_excel']) && !$this->params['export_excel'])
			{
				$this->db->limit($this->report_limit);
				$this->db->offset($this->params['offset']);
			}
					
			foreach($this->db->get()->result_array() as $sale_summary_row)
			{
				$data['summary'][$sale_summary_row['sale_id']] = $sale_summary_row; 
			}
		
			$sale_ids = array();
		
			foreach($data['summary'] as $sale_row)
			{
				$sale_ids[] = $sale_row['sale_id'];
			}
			
			$this->db->select('sale_id, items.item_id, item_kits.item_kit_id, sale_time, sale_date, item_number, items.product_id as item_product_id,item_kits.product_id as item_kit_product_id, item_kit_number, items.name as item_name, item_kits.name as item_kit_name, sales_items_temp.category, quantity_purchased, quantity_purchased as items_purchased, serialnumber, sales_items_temp.description, subtotal,total, tax, profit, discount_percent, items.size', false);
			$this->db->from('sales_items_temp');
			$this->db->join('items', 'sales_items_temp.item_id = items.item_id', 'left');
			$this->db->join('item_kits', 'sales_items_temp.item_kit_id = item_kits.item_kit_id', 'left');		
			$this->_searchSalesQueryParams();		
			if (!empty($sale_ids))
			{
				$this->db->where_in('sale_id', $sale_ids);
			}
			else
			{
				$this->db->where('1', '2', FALSE);		
			}			
		
			foreach($this->db->get()->result_array() as $sale_item_row)
			{
				$data['details'][$sale_item_row['sale_id']][] = $sale_item_row;
			}
												
			return $data;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIds();
			$this->db->select('sale_id, sale_time, sale_date, register_name, sum(quantity_purchased) as items_purchased, CONCAT(sold_by_employee.first_name," ",sold_by_employee.last_name) as sold_by_employee, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, payment_type, comment', false);
			$this->db->from('sales_items_temp');
			$this->db->join('people as employee', 'sales_items_temp.employee_id = employee.person_id');
			$this->db->join('people as sold_by_employee', 'sales_items_temp.sold_by_employee_id = sold_by_employee.person_id', 'left');
			$this->db->join('people as customer', 'sales_items_temp.customer_id = customer.person_id', 'left');
			$this->db->where('sales_items_temp.deleted', 0);
			if (!empty($sale_ids))
			{
				$this->db->where_in('sale_id', $sale_ids);
			}
			else
			{
				$this->db->where('sale_id', -1);
			}
			$this->db->group_by('sale_id');
			$this->db->order_by('sale_time');
			
			//If we are exporting NOT exporting to excel make sure to use offset and limit
			if (isset($this->params['export_excel']) && !$this->params['export_excel'])
			{
				$this->db->limit($this->report_limit);
				$this->db->offset($this->params['offset']);
			}			
			
			foreach($this->db->get()->result_array() as $sale_summary_row)
			{
				$data['summary'][$sale_summary_row['sale_id']] = $sale_summary_row; 
			}
		
			$sale_ids = array();
		
			foreach($data['summary'] as $sale_row)
			{
				$sale_ids[] = $sale_row['sale_id'];
			}
			
			$this->db->select('sale_id, items.item_id, item_kits.item_kit_id, sale_time, sale_date, item_number, items.product_id as item_product_id,item_kits.product_id as item_kit_product_id, item_kit_number, items.name as item_name, item_kits.name as item_kit_name, sales_items_temp.category, quantity_purchased, serialnumber, sales_items_temp.description, subtotal,total, tax, profit, discount_percent, items.size', false);
			$this->db->from('sales_items_temp');
			$this->db->join('items', 'sales_items_temp.item_id = items.item_id', 'left');
			$this->db->join('item_kits', 'sales_items_temp.item_kit_id = item_kits.item_kit_id', 'left');		
		
			if (!empty($sale_ids))
			{
				$this->db->where_in('sale_id', $sale_ids);
			}
			else
			{
				$this->db->where('1', '2', FALSE);		
			}
		
			foreach($this->db->get()->result_array() as $sale_item_row)
			{
				$data['details'][$sale_item_row['sale_id']][] = $sale_item_row;
			}
		
			return $data;
		}		
	}
	
	function getTotalRows()
	{		
		$sale_ids = $this->_getMatchingSaleIds();
		return count($sale_ids);
	}
	
	public function getSummaryData()
	{
		if ($this->params['matched_items_only'])
		{
			$this->db->select('sales_items_temp.sale_id, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit,sum(quantity_purchased) as items_purchased', false);
			$this->db->from('sales_items_temp');
			$this->db->where('sales_items_temp.deleted', 0);
			$this->_searchSalesQueryParams();
			$this->db->group_by('sale_id');
			if ($this->config->item('hide_store_account_payments_from_report_totals'))
			{
				$this->db->where('store_account_payment', 0);
			}
			
			$result = $this->db->get()->result_array();
			
			$return = array('subtotal' => 0, 'total' => 0,'tax' => 0, 'profit' => 0);
			foreach($result as $row)
			{
				$return['subtotal']+=to_currency_no_money($row['subtotal'],2);
				$return['total']+=to_currency_no_money($row['total'],2);
				$return['tax']+=to_currency_no_money($row['tax'],2);
				$return['profit']+=to_currency_no_money($row['profit'],2);
			}
			
			if(!$this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
			{
				unset($return['profit']);
			}
			
			return $return;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIds();
			$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit', false);
			$this->db->from('sales_items_temp');
			$this->db->group_by('sale_id');
			if ($this->config->item('hide_store_account_payments_from_report_totals'))
			{
				$this->db->where('store_account_payment', 0);
			}
			
			if (!empty($sale_ids))
			{
				$this->db->where_in('sale_id', $sale_ids);
			}
			else
			{
				$this->db->where('sale_id', -1);
			}
			
			$return = array('subtotal' => 0, 'total' => 0,'tax' => 0, 'profit' => 0);
			$result = $this->db->get()->result_array();
			foreach($result as $row)
			{
				$return['subtotal']+=to_currency_no_money($row['subtotal'],2);
				$return['total']+=to_currency_no_money($row['total'],2);
				$return['tax']+=to_currency_no_money($row['tax'],2);
				$return['profit']+=to_currency_no_money($row['profit'],2);
			}
			
			if(!$this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
			{
				unset($return['profit']);
			}
			
			return $return;
		}
	}
	
	private function _getMatchingSaleIds()
	{
		$this->db->select('sale_id, sum(quantity_purchased) as items_purchased, sum(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->_searchSalesQueryParams();
		$this->db->where('sales_items_temp.deleted', 0);
		$this->db->group_by('sale_id');
		$this->db->order_by('sale_time');		
		$sales_matches = $this->db->get()->result_array();
		$sale_ids = array();
		foreach($sales_matches as $sale_match)
		{
			$sale_ids[] = $sale_match['sale_id'];
		}
		
		return $sale_ids;
	}
	
	private function _searchSalesQueryParams()
	{
		$matchType = 'where';
		if ($this->params['matchType'] == 'matchType_Or') 
		{
			$matchType = 'or_where';			
		}
		
		if ($this->params['values'][0]['f'] != 0) 
		{
			foreach ($this->params['values'] as $w => $d) 
			{
				$ops = $this->params['ops'][$d['o']]; // Condition Operator
				if (count($d['i']) > 1) 
				{
					if ($d['o'] == 1) { $ops = $this->params['ops'][5]; }
					if ($d['o'] == 2) { $ops = $this->params['ops'][6]; }
				}

				if  ($d['f'] == 6 && $d['o'] == 10) 
				{ 
					// Sale Type
					$this->db->or_having('items_purchased > 0');
				} 
				elseif ($d['f'] == 6 && $d['o'] == 11) 
				{ 
					// Returns
					$this->db->or_having('items_purchased < 0');
				} 
				elseif ($d['f'] == 7) 
				{ 
					for($k = 0;$k<count($d['i']);$k++)
					{
						$d['i'][$k] = $this->db->escape_str($d['i'][$k]);
					}
					
					// Sale Amount
					if ($this->params['matchType'] == 'matchType_All')
					{
						$this->db->having('ROUND(total,2) '.str_replace("xx", join(", ", $d['i']), $ops));				
					}
					elseif($this->params['matchType'] == 'matchType_Or')
					{
						$this->db->or_having('ROUND(total,2) '.str_replace("xx", join(", ", $d['i']), $ops));				
					}
				}
				elseif($d['f'] == 11)
				{
					//Payment type
					$payment_field = $this->db->dbprefix($this->params['tables'][$d['f']]);
				
					$payment_like = '(';
					
					foreach($d['i'] as $payment_type)
					{
						$payment_type = $this->db->escape_like_str($payment_type);
						
						$payment_like.= $payment_field." LIKE '%".$payment_type."%' OR ";						
					}
				 	$payment_like = rtrim($payment_like, ' OR ');
					
					$payment_like.= ')';
					$this->db->{$matchType}($payment_like, null, false);
				}
				else 
				{
					for($k = 0;$k<count($d['i']);$k++)
					{
						$d['i'][$k] = $this->db->escape_str($d['i'][$k]);
					}
					
					$this->db->{$matchType}($this->params['tables'][$d['f']].' '.str_replace("xx", join("', '", $d['i']), $ops));
				}
			}
		}
	}
}
?>