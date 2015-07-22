<?php
require_once("report.php");
class Detailed_inventory extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_date'), 'align' => 'left'), array('data'=>lang('reports_item_name'), 'align' => 'left'), array('data'=>lang('items_category'), 'align'=>'left'), array('data'=>lang('reports_item_number'), 'align' => 'left'), array('data'=>lang('items_product_id'), 'align' => 'left'),array('data'=>lang('items_in_out_qty'), 'align' => 'left'),array('data'=>lang('items_inventory_comments'), 'align' => 'left'));
	}
	
	public function getData()
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		
		$this->db->from('inventory');
		$this->db->join('items', 'items.item_id = inventory.trans_items');
		$this->db->where('trans_date BETWEEN '.$this->db->escape($this->params['start_date']).' and '.$this->db->escape($this->params['end_date']));
		$this->db->where('items.deleted', 0);
		$this->db->where('trans_inventory !=', 0);
		$this->db->where('inventory.location_id', $current_location);
		$this->db->order_by('trans_date');
		
		//Hide POS XXX and RECV XXX
		if ($this->params['show_manual_adjustments_only'])
		{
			$sale_prefix = $this->config->item('sale_prefix');
			$recv_prefix = 'RECV';
			
			$this->db->not_like('trans_comment', $sale_prefix, 'after');
			$this->db->not_like('trans_comment', $recv_prefix, 'after');
			
		}

		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}
		
		return $this->db->get()->result_array();
	}
	
	function getTotalRows()
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		$this->db->from('inventory');
		$this->db->where('trans_date BETWEEN "'.$this->params['start_date'].'" and "'.$this->params['end_date'].'"');
		$this->db->where('inventory.location_id', $current_location);
		$this->db->where('trans_inventory !=', 0);
		
		//Hide POS XXX and RECV XXX
		if ($this->params['show_manual_adjustments_only'])
		{
			$sale_prefix = $this->config->item('sale_prefix');
			$recv_prefix = 'RECV';
			
			$this->db->not_like('trans_comment', $sale_prefix, 'after');
			$this->db->not_like('trans_comment', $recv_prefix, 'after');
			
		}
		
		return $this->db->count_all_results();
	}
	
	public function getSummaryData()
	{
		return array();
	}
}
?>