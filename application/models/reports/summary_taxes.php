<?php
require_once("report.php");
class Summary_taxes extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_tax_percent'), 'align'=>'left'),array('data'=>lang('reports_subtotal'), 'align'=>'left'), array('data'=>lang('reports_tax'), 'align'=>'left'),array('data'=>lang('reports_total'), 'align'=>'left'));
	}
	
	public function getData()
	{
		$this->db->select('sale_id, item_id, item_kit_id, line');
		$this->db->from('sales_items_temp');
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		
		$taxes_data = array();
		foreach($this->db->get()->result_array() as $row)
		{
			if ($row['item_id'])
			{
				$this->getTaxesForItems($row['sale_id'], $row['item_id'], $row['line'], $taxes_data);
			}
			else
			{
				$this->getTaxesForItemKits($row['sale_id'], $row['item_kit_id'], $row['line'], $taxes_data);			
			}
		}
		
		$this->getNonTaxableTotalForItemsAndItemKits($taxes_data);
				
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$taxes_data = array_slice($taxes_data, $this->params['offset'], $this->report_limit);
		}
		
		
		return $taxes_data;
	}
	
	function getTotalRows()
	{
		$logged_in_location_id = $this->Employee->get_logged_in_employee_current_location_id();
		
		$this->db->select('COUNT(DISTINCT(CONCAT('.$this->db->dbprefix('sales_items_taxes').'.name,'.$this->db->dbprefix('sales_items_taxes').'.percent))) as tax_count', false);
		$this->db->from('sales_items_taxes');
		$this->db->join('sales', 'sales.sale_id=sales_items_taxes.sale_id');
		$this->db->where('sale_time BETWEEN '. $this->db->escape($this->params['start_date']). ' and '. $this->db->escape($this->params['end_date']).' and location_id = '.$this->db->escape($logged_in_location_id));
		
		if ($this->config->item('hide_store_account_payments_in_reports'))
		{
			$this->db->where('store_account_payment',0);
		}
				
		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);
		
		$ret = $this->db->get()->row_array();
		
		//add 1 for non taxable
		return $ret['tax_count'] + 1;
	}
	
	function getTaxesForItems($sale_id, $item_id, $line, &$taxes_data)
	{
		$query = $this->db->query("SELECT name, percent, cumulative, item_unit_price, item_cost_price, quantity_purchased, discount_percent FROM ".$this->db->dbprefix('sales_items_taxes').' 
		JOIN '.$this->db->dbprefix('sales_items'). ' USING(sale_id, item_id, line) WHERE '.
		$this->db->dbprefix('sales_items_taxes').'.sale_id = '.$sale_id.' and '.
		$this->db->dbprefix('sales_items_taxes').'.item_id = '.$item_id.' and '.
		$this->db->dbprefix('sales_items_taxes').'.line = '.$line. ' ORDER BY cumulative');
		
		$tax_result = $query->result_array();
		for($k=0;$k<count($tax_result);$k++)
		{
			$row = $tax_result[$k];
			if ($row['cumulative'])
			{
				$previous_tax = $tax;
				$subtotal = ($row['item_unit_price']*$row['quantity_purchased']-$row['item_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = ($subtotal + $tax) * ($row['percent'] / 100);
			}
			else
			{
				$subtotal = ($row['item_unit_price']*$row['quantity_purchased']-$row['item_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = $subtotal * ($row['percent'] / 100);
			}
			
			if (empty($taxes_data[$row['name'].$row['percent']]))
			{
				$taxes_data[$row['name'].$row['percent']] = array('name' => $row['name'].' ('.$row['percent'] . '%)', 'tax' => 0, 'subtotal' => 0, 'total' => 0);
			}
						
			$taxes_data[$row['name'].$row['percent']]['subtotal'] += ($subtotal);
			$taxes_data[$row['name'].$row['percent']]['tax'] += ($tax);
			$taxes_data[$row['name'].$row['percent']]['total'] += ($subtotal+ $tax);
			
		}
		
	}
	
	function getTaxesForItemKits($sale_id, $item_kit_id, $line, &$taxes_data)
	{
		$query = $this->db->query("SELECT name, percent, cumulative, item_kit_unit_price, item_kit_cost_price, quantity_purchased, discount_percent FROM ".$this->db->dbprefix('sales_item_kits_taxes').' 
		JOIN '.$this->db->dbprefix('sales_item_kits'). ' USING(sale_id, item_kit_id, line) WHERE '.
		$this->db->dbprefix('sales_item_kits_taxes').'.sale_id = '.$sale_id.' and '.
		$this->db->dbprefix('sales_item_kits_taxes').'.item_kit_id = '.$item_kit_id.' and '.
		$this->db->dbprefix('sales_item_kits_taxes').'.line = '.$line. ' ORDER BY cumulative');

		$tax_result = $query->result_array();
		for($k=0;$k<count($tax_result);$k++)
		{
			$row = $tax_result[$k];
			if ($row['cumulative'])
			{
				$previous_tax = $tax;
				$subtotal = ($row['item_kit_unit_price']*$row['quantity_purchased']-$row['item_kit_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = ($subtotal + $tax) * ($row['percent'] / 100);
			}
			else
			{
				$subtotal = ($row['item_kit_unit_price']*$row['quantity_purchased']-$row['item_kit_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = $subtotal * ($row['percent'] / 100);
			}
			
			if (empty($taxes_data[$row['name'].$row['percent']]))
			{
				$taxes_data[$row['name'].$row['percent']] = array('name' => $row['name'].' ('.$row['percent'] . '%)', 'tax' => 0, 'subtotal' => 0, 'total' => 0);				
			}
			
			
			$taxes_data[$row['name'].$row['percent']]['subtotal'] += ($subtotal);
			$taxes_data[$row['name'].$row['percent']]['tax'] += ($tax);
			$taxes_data[$row['name'].$row['percent']]['total'] += ($subtotal+ $tax);
		}
	}
	
	function getNonTaxableTotalForItemsAndItemKits(&$taxes_data)
	{
		$logged_in_location_id = $this->Employee->get_logged_in_employee_current_location_id();
		
	
		$non_taxable_items_and_item_kits_result = $this->db->query("SELECT item_unit_price as unit_price, quantity_purchased, discount_percent 
		FROM `".$this->db->dbprefix('sales_items')."` 
		LEFT OUTER JOIN `".$this->db->dbprefix('sales_items_taxes')."` USING (sale_id, item_id, line)
		INNER join `".$this->db->dbprefix('sales')."` USING (sale_id)
		WHERE ".$this->db->dbprefix('sales').".sale_time BETWEEN ".$this->db->escape($this->params['start_date'])." and ".$this->db->escape($this->params['end_date']).
		" and ".$this->db->dbprefix('sales').".location_id = ".$this->db->escape($logged_in_location_id)." and percent IS NULL 
		UNION ALL
		(
		SELECT item_kit_unit_price as unit_price, quantity_purchased, discount_percent 
		FROM `".$this->db->dbprefix('sales_item_kits')."` 
		LEFT OUTER JOIN `".$this->db->dbprefix('sales_item_kits_taxes')."` USING (sale_id, item_kit_id, line)
		INNER join `".$this->db->dbprefix('sales')."` USING (sale_id)
		WHERE ".$this->db->dbprefix('sales').".sale_time BETWEEN ".$this->db->escape($this->params['start_date'])." and ".$this->db->escape($this->params['end_date']).
		" and ".$this->db->dbprefix('sales').".location_id = ".$this->db->escape($logged_in_location_id)." and percent IS NULL)");
	
		$total = 0;
		foreach($non_taxable_items_and_item_kits_result->result_array() as $row)
		{
			$total += ($row['unit_price']*$row['quantity_purchased']-$row['unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);	
		}
		
		$total = $total;
		$taxes_data[lang('reports_non_taxable')] = array(
			'name' => lang('reports_non_taxable'),
			'subtotal' => $total,
			'total' => $total,
			'tax' => 0
		);
			
	}
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit', false);
		$this->db->from('sales_items_temp');
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		
		if ($this->config->item('hide_store_account_payments_from_report_totals'))
		{
			$this->db->where('store_account_payment', 0);
		}
		
		$this->db->group_by('sale_id');
		
		$return = array(
			'subtotal' => 0,
			'total' => 0,
			'tax' => 0,
			'profit' => 0,
		);
		
		foreach($this->db->get()->result_array() as $row)
		{
			$return['subtotal'] += to_currency_no_money($row['subtotal'],2);
			$return['total'] += to_currency_no_money($row['total'],2);
			$return['tax'] += to_currency_no_money($row['tax'],2);
			$return['profit'] += to_currency_no_money($row['profit'],2);
		}
		if(!$this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
		{
			unset($return['profit']);
		}
		return $return;
	}
}
?>