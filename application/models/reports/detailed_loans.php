<?php
require_once("report.php");
class Detailed_loans extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		$return = array();
		
		$return['summary'] = array();
		$return['summary'][] = array('data'=>lang('reports_no'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_customer'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('loans_amount'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('loans_pay_amount'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('loans_amount_due'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('loans_deposit'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('loans_rate'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('loans_pay_rate'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('loans_pay_rate'), 'align'=> 'right');
				
		if($this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
		{
			$return['summary'][] = array('data'=>lang('reports_profit'), 'align'=> 'right');
		}
                
		$return['details'] = array();
		$return['details'][] = array('data'=>lang('reports_no'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_date'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('loans_amount'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('loans_pay_amount'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('loans_amount_due'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('loans_deposit'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('loans_rate'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('loans_pay_rate'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('loans_number_of_day_late'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('loans_fine'), 'align'=> 'right');
		if($this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
		{
			$return['details'][] = array('data'=>lang('reports_profit'), 'align'=> 'right');			
		}
		
		return $return;
	}
	
	
	public function getData()
	{
		$data = array();
		$data['summary'] = array();
		$data['details'] = array();
				
		$this->db->select('borrower AS borrower, 
                borrow_date AS borrow_date, 
                person_id as person_id,
                sum(pay_principle) as amount, 
                sum(paid_princ) as paid_princ, 
                (sum(paid_princ)-sum(pay_principle)) as amount_due, 
                sum(deposit) as deposit,
                sum(pay_rate) as rate,
                sum(paid_rate) as paid_rate,
                sum(late) as late,
                sum(pay_fine) as pay_fine,
                sum(profit) as profit', false);
		$this->db->from('loans_temp');
                
		$this->db->where('deleted', 0);
		$this->db->group_by('person_id');
		$this->db->order_by('borrow_date','DESC');
		
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}		
		
		foreach($this->db->get()->result_array() as $loans_summary_row)
		{
			$data['summary'][$loans_summary_row['person_id']] = $loans_summary_row; 
		}
		
		$person_ids = array();
		
		foreach($data['summary'] as $loan_row)
		{
			$person_ids[] = $loan_row['person_id'];
		}
		
		$this->db->select('borrower AS borrower, borrow_date AS borrow_date, 
                sum(pay_principle) as amount, 
                person_id as person_id,
                sum(paid_princ) as paid_princ, 
                (sum(paid_princ)-sum(pay_principle)) as amount_due, 
                sum(deposit) as deposit,
                sum(pay_rate) as rate,
                sum(paid_rate) as paid_rate,
                sum(late) as late,
                sum(pay_fine) as pay_fine,
                sum(profit) as profit', false);
		$this->db->from('loans_temp');
		
                $this->db->group_by('loan_id');
		
                if (!empty($person_ids)) {
                    $this->db->where_in('person_id', $person_ids);
                } else {
                    $this->db->where('1', '2', FALSE);
                }
                
		foreach($this->db->get()->result_array() as $loans_detail_row)
		{
			$data['details'][$loans_detail_row['person_id']][] = $loans_detail_row;
		}
		
		return $data;
	}
	
	public function getTotalRows()
	{
		$this->db->select("COUNT(DISTINCT(person_id)) as person_id_count");
		$this->db->from('loans_temp');
		
		$this->db->where('loans_temp.deleted', 0);

		$ret = $this->db->get()->row_array();
		return $ret['person_id_count'];
	}
        
        
	public function getSummaryData()
	{
		$this->db->select('sum(pay_principle) as amount, 
                    sum(paid_princ) as paid_princ, 
                    (sum(paid_princ)-sum(pay_principle)) as amount_due,
                    sum(deposit) as deposit,
                    sum(pay_rate) as rate,
                    sum(paid_rate) as paid_rate,
                    sum(pay_fine) as pay_fine,
                    sum(profit) as profit', false);
		$this->db->from('loans_temp');
		$this->db->where('deleted', 0);
		$this->db->group_by('person_id');
		$return = array(
			'amount' => 0,
			'paid_princ' => 0,
			'amount_due' => 0,
			'deposit' => 0,
			'rate' => 0,
			'paid_rate' => 0,
			//'late' => 0,
			'pay_fine' => 0,
			'profit' => 0,
		);
		
		foreach($this->db->get()->result_array() as $row)
		{
			$return['amount'] += to_currency_no_money($row['amount'],2);
			$return['paid_princ'] += to_currency_no_money($row['paid_princ'],2);
			$return['amount_due'] += to_currency_no_money($row['amount_due'],2);
			$return['deposit'] += to_currency_no_money($row['deposit'],2);
			$return['rate'] += to_currency_no_money($row['rate'],2);
			$return['paid_rate'] += to_currency_no_money($row['paid_rate'],2);
			//$return['late'] += $row['late'];
			$return['pay_fine'] += to_currency_no_money($row['pay_fine'],2);
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