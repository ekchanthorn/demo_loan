<?php
require_once("report.php");
class Detailed_payments extends Report
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
		$return['summary'][] = array('data'=>lang('reports_sale_date'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_payment_date'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_payment_type'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('sales_payment_amount'), 'align'=> 'left');
				

		$return['details'] = array();
		$return['details'][] = array('data'=>lang('reports_payment_date'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_payment_type'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('sales_payment_amount'), 'align'=> 'left');
		
		return $return;
	}
	
	
	public function getData()
	{
		$data = array();
		$data['summary'] = array();
		$data['details'] = array();
		$data['details']['sale_ids'] = array();
		
		$logged_in_location_id = $this->Employee->get_logged_in_employee_current_location_id();
		$sales_totals = array();
		
		$this->db->select('sale_id, SUM(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->db->where('deleted', 0);
		$this->db->group_by('sale_id');
		foreach($this->db->get()->result_array() as $sale_total_row)
		{
			$sales_totals[$sale_total_row['sale_id']] = to_currency_no_money($sale_total_row['total'],2);
		}
		$this->db->select('sales.sale_time, sales_payments.sale_id, sales_payments.payment_type, payment_amount, payment_date, payment_id', false);
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
		$this->db->where('payment_date BETWEEN '. $this->db->escape($this->params['start_date']). ' and '. $this->db->escape($this->params['end_date']).' and location_id = '.$this->db->escape($logged_in_location_id));
		
		if ($this->config->item('hide_store_account_payments_in_reports'))
		{
			$this->db->where('store_account_payment',0);
		}
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('payment_amount > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('payment_amount < 0');
		}
		
		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);
		$this->db->order_by('sale_id, payment_type');
				
		$sales_payments = $this->db->get()->result_array();
		
		$payments_by_sale = array();
		foreach($sales_payments as $row)
		{
        	$payments_by_sale[$row['sale_id']][] = $row;
		}
		
		$payment_data = $this->Sale->get_payment_data_grouped_by_sale($payments_by_sale,$sales_totals);
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$payment_data = array_slice($payment_data, $this->params['offset'], $this->report_limit, TRUE);
		}
		
		$data['summary'] = $payment_data;
		$sale_ids = array();
		
		foreach(array_keys($data['summary']) as $sale_id)
		{
			$sale_ids[] = $sale_id;
		}
		
		
		$sales_totals = array();
		
		$this->db->select('sale_id, SUM(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->db->where('deleted', 0);
		$this->db->group_by('sale_id');
		foreach($this->db->get()->result_array() as $sale_total_row)
		{
			$sales_totals[$sale_total_row['sale_id']] = $sale_total_row['total'];
		}
		
		$this->db->select('sales.sale_time, sales_payments.sale_id, sales_payments.payment_type, payment_amount, payment_date, payment_id', false);
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');

		if (!empty($sale_ids))
		{
			$this->db->where_in('sales.sale_id', $sale_ids);
		}
		else
		{
			$this->db->where('1', '2', FALSE);		
		}
		
		$this->db->order_by('sale_id, payment_type');
		
		$sales_payments = $this->db->get()->result_array();
		
		$payments_by_sale = array();
		foreach($sales_payments as $row)
		{
        	$payments_by_sale[$row['sale_id']][] = $row;
		}
		
		$payment_data = $this->Sale->get_payment_data_grouped_by_sale($payments_by_sale,$sales_totals);
		foreach($payment_data as $sale_id => $payments_row)
		{
			foreach($payments_row as $payment_type => $sale_payment_row)
			{
				$data['details'][$sale_id.'|'.$payment_type][] = $sale_payment_row;
				$data['details']['sale_ids'][$sale_id][] = $sale_payment_row;
			}
		}
		return $data;
	}
	
	public function getTotalRows()
	{
		$logged_in_location_id = $this->Employee->get_logged_in_employee_current_location_id();
		
		$this->db->select("COUNT(payment_date) as payment_row_count", false);
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
		$this->db->where('payment_date BETWEEN '. $this->db->escape($this->params['start_date']). ' and '. $this->db->escape($this->params['end_date']).' and location_id = '.$this->db->escape($logged_in_location_id));
		if ($this->config->item('hide_store_account_payments_in_reports'))
		{
			$this->db->where('store_account_payment',0);
		}
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('payment_amount > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('payment_amount < 0');
		}
		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);

		$ret = $this->db->get()->row_array();
		return $ret['payment_row_count'];
	}
	public function getSummaryData()
	{
		$logged_in_location_id = $this->Employee->get_logged_in_employee_current_location_id();
		$sales_totals = array();
		
		$this->db->select('sale_id, SUM(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->db->where('deleted', 0);
		$this->db->group_by('sale_id');
		foreach($this->db->get()->result_array() as $sale_total_row)
		{
			$sales_totals[$sale_total_row['sale_id']] = to_currency_no_money($sale_total_row['total'], 2);
		}
		$this->db->select('sales_payments.sale_id, sales_payments.payment_type, payment_amount, payment_id', false);
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
		$this->db->where('payment_date BETWEEN '. $this->db->escape($this->params['start_date']). ' and '. $this->db->escape($this->params['end_date']).' and location_id = '.$this->db->escape($logged_in_location_id));
		
		if ($this->config->item('hide_store_account_payments_in_reports'))
		{
			$this->db->where('store_account_payment',0);
		}
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('payment_amount > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('payment_amount < 0');
		}
		
		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);
		$this->db->order_by('sale_id, payment_type');
				
		$sales_payments = $this->db->get()->result_array();
		
		$payments_by_sale = array();
		foreach($sales_payments as $row)
		{
        	$payments_by_sale[$row['sale_id']][] = $row;
		}
		
		$payment_data = $this->Sale->get_payment_data($payments_by_sale,$sales_totals);		
		
		$return = array('total' => 0);
		foreach($payment_data as $payment)
		{
			$return['total']+=$payment['payment_amount'];
		}
				
		return $return;
	}
	
	function get_sale_ids_for_payments()
	{
		$sale_ids = array();
		$logged_in_location_id = $this->Employee->get_logged_in_employee_current_location_id();
		
		$this->db->select('sales_payments.sale_id');
		$this->db->distinct();
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
		$this->db->where('payment_date BETWEEN '. $this->db->escape($this->params['start_date']). ' and '. $this->db->escape($this->params['end_date']).' and location_id = '.$this->db->escape($logged_in_location_id));
	
		foreach($this->db->get()->result_array() as $sale_row)
		{
			 $sale_ids[] = $sale_row['sale_id'];
		}
		
		return $sale_ids;
	}
}
?>