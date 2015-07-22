<?php
class Receiving extends CI_Model
{
	public function get_info($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id',$receiving_id);
		return $this->db->get();
	}

	function exists($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id',$receiving_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
	
	function update($receiving_data, $receiving_id)
	{
		$this->db->where('receiving_id', $receiving_id);
		$success = $this->db->update('receivings',$receiving_data);
		
		return $success;
	}


	function save ($items,$supplier_id,$employee_id,$comment,$payment_type,$receiving_id=false, $suspended = 0, $mode,$location_id=-1)
	{
		if(count($items)==0)
			return -1;

		$receivings_data = array(
		'receiving_time' => date('Y-m-d H:i:s'),
		'supplier_id'=> $supplier_id > 0 ? $supplier_id : null,
		'employee_id'=>$employee_id,
		'payment_type'=>$payment_type,
		'comment'=>$comment,
		'suspended' => $suspended,
		'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
		'transfer_to_location_id' => $location_id > 0 ? $location_id : NULL,
		'deleted' => 0,
		'deleted_by' => NULL,
		);
			
		$this->db->query("SET autocommit=0");	
		//Lock tables invovled in sale transaction so we don't have deadlock
		$this->db->query('LOCK TABLES '.$this->db->dbprefix('customers').' WRITE, '.$this->db->dbprefix('receivings').' WRITE, 
		'.$this->db->dbprefix('store_accounts').' WRITE, '.$this->db->dbprefix('receivings_items').' WRITE, 
		'.$this->db->dbprefix('giftcards').' WRITE, '.$this->db->dbprefix('location_items').' WRITE, 
		'.$this->db->dbprefix('inventory').' WRITE, 
		'.$this->db->dbprefix('people').' READ,'.$this->db->dbprefix('items').' WRITE
		,'.$this->db->dbprefix('employees_locations').' READ,'.$this->db->dbprefix('locations').' READ, '.$this->db->dbprefix('items_tier_prices').' READ
		, '.$this->db->dbprefix('location_items_tier_prices').' READ, '.$this->db->dbprefix('items_taxes').' READ, '.$this->db->dbprefix('item_kits').' READ
		, '.$this->db->dbprefix('location_item_kits').' READ, '.$this->db->dbprefix('item_kit_items').' READ, '.$this->db->dbprefix('employees').' READ , '.$this->db->dbprefix('item_kits_tier_prices').' READ
		, '.$this->db->dbprefix('location_item_kits_tier_prices').' READ, '.$this->db->dbprefix('suppliers').' READ, '.$this->db->dbprefix('location_items_taxes').' READ
		, '.$this->db->dbprefix('location_item_kits_taxes'). ' READ, '.$this->db->dbprefix('item_kits_taxes'). ' READ');

		if ($receiving_id)
		{
			//Delete previoulsy receving so we can overwrite data
			if (!$this->delete($receiving_id, true, false))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;				
			}
			
			$this->db->where('receiving_id', $receiving_id);
			if (!$this->db->update('receivings', $receivings_data))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
		}
		else
		{
			if (!$this->db->insert('receivings',$receivings_data))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
			$receiving_id = $this->db->insert_id();
		}

		foreach($items as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);
			$cur_item_location_info = $this->Item_location->get_info($item['item_id']);
			$cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;

			$receivings_items_data = array
			(
				'receiving_id'=>$receiving_id,
				'item_id'=>$item['item_id'],
				'line'=>$item['line'],
				'description'=>$item['description'],
				'serialnumber'=>$item['serialnumber'],
				'quantity_purchased'=>$item['quantity'],
				'discount_percent'=>$item['discount'],
				'item_cost_price' => $cost_price,
				'item_unit_price'=>$item['price']
			);

			if (!$this->db->insert('receivings_items',$receivings_items_data))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
			
			if ($suspended == 0)
			{
				if ($this->config->item('calculate_average_cost_price_from_receivings'))
				{
					$this->calculate_and_update_average_cost_price_for_item($item['item_id'], $receivings_items_data);
				}
			}
			
			//Update stock quantity IF not a service item
			if ($suspended == 0 && !$cur_item_info->is_service)
			{
				//If we have a null quanity set it to 0, otherwise use the value
				$cur_item_location_info->quantity = $cur_item_location_info->quantity !== NULL ? $cur_item_location_info->quantity : 0;

				if (!$this->Item_location->save_quantity($cur_item_location_info->quantity + $item['quantity'], $item['item_id']))
				{
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}
				
				$qty_recv = $item['quantity'];
				$recv_remarks ='RECV '.$receiving_id;
				$inv_data = array
				(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$item['item_id'],
					'trans_user'=>$employee_id,
					'trans_comment'=>$recv_remarks,
					'trans_inventory'=>$qty_recv,
					'location_id'=>$this->Employee->get_logged_in_employee_current_location_id()
				);
				if (!$this->Inventory->insert($inv_data))
				{
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}
			}

			if($suspended  == 0 && $mode=='transfer' && $location_id && $cur_item_location_info->quantity !== NULL && !$cur_item_info->is_service)
			{				
				if (!$this->Item_location->save_quantity($this->Item_location->get_location_quantity($item['item_id'],$location_id) + ($item['quantity'] * -1),$item['item_id'],$location_id))
				{
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}
				
				
				//Change values from $inv_data above and insert
				$inv_data['trans_inventory']=$qty_recv * -1;
				$inv_data['location_id']=$location_id;
				if (!$this->Inventory->insert($inv_data))
				{
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}			
			}		
		}
		
		$this->db->query("COMMIT");			
		$this->db->query('UNLOCK TABLES');	

		return $receiving_id;
	}
	
	function delete($receiving_id, $all_data = false, $update_quantity = true)
	{
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		
		$this->db->select('receivings.location_id, item_id, quantity_purchased, transfer_to_location_id');
		$this->db->from('receivings_items');
		$this->db->join('receivings', 'receivings.receiving_id = receivings_items.receiving_id');
		$this->db->where('receivings.receiving_id', $receiving_id);
		
		foreach($this->db->get()->result_array() as $receiving_item_row)
		{
			$receiving_location_id = $receiving_item_row['location_id'];
			$cur_item_info = $this->Item->get_info($receiving_item_row['item_id']);	
			$cur_item_location_info = $this->Item_location->get_info($receiving_item_row['item_id']);
			
			if ($update_quantity)
			{
				$this->Item_location->save_quantity($cur_item_location_info->quantity - $receiving_item_row['quantity_purchased'],$receiving_item_row['item_id']);
				
				$sale_remarks ='RECV '.$receiving_id;
				$inv_data = array
					(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$receiving_item_row['item_id'],
					'trans_user'=>$employee_id,
					'trans_comment'=>$sale_remarks,
					'trans_inventory'=>$receiving_item_row['quantity_purchased'] * -1,
					'location_id'=>$receiving_location_id
					);
					$this->Inventory->insert($inv_data);
					
					if ($receiving_item_row['transfer_to_location_id'])
					{
						$cur_item_location_transfer_info = $this->Item_location->get_info($receiving_item_row['item_id'], $receiving_item_row['transfer_to_location_id']);
						
						$this->Item_location->save_quantity($cur_item_location_transfer_info->quantity + $receiving_item_row['quantity_purchased'],$receiving_item_row['item_id'], $receiving_item_row['transfer_to_location_id']);
				
						$sale_remarks ='RECV '.$receiving_id;
						$inv_data = array
							(
							'trans_date'=>date('Y-m-d H:i:s'),
							'trans_items'=>$receiving_item_row['item_id'],
							'trans_user'=>$employee_id,
							'trans_comment'=>$sale_remarks,
							'trans_inventory'=>$receiving_item_row['quantity_purchased'] * 1,
							'location_id'=>$receiving_item_row['transfer_to_location_id']
							);
							$this->Inventory->insert($inv_data);
					}
			 }
		}
		
		if ($all_data)
		{
			$this->db->delete('receivings_items', array('receiving_id' => $receiving_id));
		}
		
		$this->db->where('receiving_id', $receiving_id);
		return $this->db->update('receivings', array('deleted' => 1,'deleted_by'=>$employee_id));
	}
	
	/* This function is not visible accessible easily from php pos. 
	If we ever make it visible we should make sure quantities are added back
	*/ 
	function undelete($receiving_id)
	{
		$this->db->where('receiving_id', $receiving_id);
		return $this->db->update('receivings', array('deleted' => 0,'deleted_by'=>NULL));
	}

	function get_receiving_items($receiving_id)
	{
		$this->db->from('receivings_items');
		$this->db->where('receiving_id',$receiving_id);
		return $this->db->get();
	}

	function get_supplier($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id',$receiving_id);
		return $this->Supplier->get_info($this->db->get()->row()->supplier_id);
	}
	
	//We create a temp table that allows us to do easy report/receiving queries
	public function create_receivings_items_temp_table($params)
	{
		set_time_limit(0);
		
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();

		$where = '';
		
		if (isset($params['start_date']) && isset($params['end_date']))
		{
			$where = 'WHERE receiving_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"'.' and '.$this->db->dbprefix('receivings').'.location_id='.$this->db->escape($location_id);
		}
		else
		{
			//If we don't pass in a date range, we don't need data from the temp table
			$where = 'WHERE location_id='.$this->db->escape($location_id);
		}
		
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('receivings_items_temp')."
		(SELECT ".$this->db->dbprefix('receivings').".deleted as deleted,".$this->db->dbprefix('receivings').".deleted_by as deleted_by, date(receiving_time) as receiving_date, ".$this->db->dbprefix('receivings_items').".receiving_id, comment,payment_type, employee_id, 
		".$this->db->dbprefix('items').".item_id, ".$this->db->dbprefix('receivings').".supplier_id, quantity_purchased, item_cost_price, item_unit_price,category,
		discount_percent, (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		".$this->db->dbprefix('receivings_items').".line as line, serialnumber, ".$this->db->dbprefix('receivings_items').".description as description,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as total,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) - (item_cost_price*quantity_purchased) as profit
		FROM ".$this->db->dbprefix('receivings_items')."
		INNER JOIN ".$this->db->dbprefix('receivings')." ON  ".$this->db->dbprefix('receivings_items').'.receiving_id='.$this->db->dbprefix('receivings').'.receiving_id'."
		INNER JOIN ".$this->db->dbprefix('items')." ON  ".$this->db->dbprefix('receivings_items').'.item_id='.$this->db->dbprefix('items').'.item_id'."
		$where
		GROUP BY receiving_id, item_id, line)");
	}
	
	function calculate_and_update_average_cost_price_for_item($item_id,$current_receivings_items_data)
	{
		//Dont calculate averages unless we receive quanitity > 0
		if ($current_receivings_items_data['quantity_purchased'] > 0)
		{
			
			$cost_price_avg = false;
			$averaging_method = $this->config->item('averaging_method');
		
			$cur_item_info = $this->Item->get_info($item_id);
			$cur_item_location_info = $this->Item_location->get_info($item_id);
		
			if ($averaging_method == 'moving_average')
			{
				$current_cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;			
				$current_quantity = $cur_item_location_info->quantity;
				$current_inventory_value = $current_cost_price * $current_quantity;
			
				$received_cost_price = $current_receivings_items_data['item_unit_price'] * (1 - ($current_receivings_items_data['discount_percent']/100));
				$received_quantity = $current_receivings_items_data['quantity_purchased'];
				$new_inventory_value = $received_cost_price * $received_quantity;
			
				$cost_price_avg = ($current_inventory_value + $new_inventory_value) / ($current_quantity + $received_quantity);
			
			}
			elseif ($averaging_method == 'historical_average')
			{
				if ($cur_item_location_info && $cur_item_location_info->cost_price)
				{
					$location_id = $this->Employee->get_logged_in_employee_current_location_id();
					$result = $this->db->query("SELECT ROUND((SUM(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)) / SUM(quantity_purchased),10) as cost_price_average 
					FROM ".$this->db->dbprefix('receivings_items').' '.
					'JOIN '.$this->db->dbprefix('receivings').' ON '.$this->db->dbprefix('receivings').'.receiving_id = '.$this->db->dbprefix('receivings_items').'.receiving_id '.
					'WHERE quantity_purchased > 0 and item_id='.$this->db->escape($item_id).' and location_id = '.$this->db->escape($location_id))->result();
				}
				else
				{
					$result = $this->db->query("SELECT ROUND((SUM(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)) / SUM(quantity_purchased),10) as cost_price_average 
					FROM ".$this->db->dbprefix('receivings_items'). '
					WHERE quantity_purchased > 0 and item_id='.$this->db->escape($item_id))->result();				
				}
			
				$cost_price_avg = $result[0]->cost_price_average;
			}
		
			if ($cost_price_avg !== FALSE)
			{
				$cost_price_avg = to_currency_no_money($cost_price_avg, 10);
				//If we have a location cost price, update that value
				if ($cur_item_location_info && $cur_item_location_info->cost_price)
				{
					$item_location_data = array('cost_price' => $cost_price_avg);
					$this->Item_location->save($item_location_data,$item_id);
				}
				else
				{
					//Update cost price
					$item_data = array('cost_price'=>$cost_price_avg);
					$this->Item->save($item_data,$item_id);
				}
			}
		}
	}

	function calculate_cost_price_preview($item_id,$price, $additional_quantity, $discount_percent)
	{
		if ($additional_quantity > 0)
		{
			$cost_price_avg = false;
			$averaging_method = $this->config->item('averaging_method');
		
			$cur_item_info = $this->Item->get_info($item_id);
			$cur_item_location_info = $this->Item_location->get_info($item_id);
			
			if ($averaging_method == 'moving_average')
			{
				$current_cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;			
				$current_quantity = $cur_item_location_info->quantity;
				$current_inventory_value = $current_cost_price * $current_quantity;
			
				$received_cost_price = $price * (1 - ($discount_percent/100));
				$received_quantity = $additional_quantity;
				$new_inventory_value = $received_cost_price * $received_quantity;
			
				$cost_price_avg = ($current_inventory_value + $new_inventory_value) / ($current_quantity + $received_quantity);
			
			}
			elseif ($averaging_method == 'historical_average')
			{
				if ($cur_item_location_info && $cur_item_location_info->cost_price)
				{
					$location_id = $this->Employee->get_logged_in_employee_current_location_id();
					$result = $this->db->query("SELECT ROUND((SUM(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)),10) as cost_price_sum,  SUM(quantity_purchased) as cost_price_quantity_sum
					FROM ".$this->db->dbprefix('receivings_items').' '.
					'JOIN '.$this->db->dbprefix('receivings').' ON '.$this->db->dbprefix('receivings').'.receiving_id = '.$this->db->dbprefix('receivings_items').'.receiving_id '.
					'WHERE quantity_purchased > 0 and item_id='.$this->db->escape($item_id).' and location_id = '.$this->db->escape($location_id))->result();
				}
				else
				{
					$result = $this->db->query("SELECT ROUND((SUM(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)),10) as cost_price_sum,  SUM(quantity_purchased) as cost_price_quantity_sum
					FROM ".$this->db->dbprefix('receivings_items'). '
					WHERE quantity_purchased > 0 and item_id='.$this->db->escape($item_id))->result();				
				}
				
				$cost_price_sum = $result[0]->cost_price_sum + ($price*$additional_quantity-$price*$additional_quantity*$discount_percent/100);
				$cost_price_quantity_sum = $result[0]->cost_price_quantity_sum + $additional_quantity;
				
				$cost_price_avg = $cost_price_sum/$cost_price_quantity_sum;
			}
		
			return to_currency($cost_price_avg,10);
		}
	
		return FALSE;
	}
	
	function get_all_suspended()
	{		
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();		
		
		$this->db->from('receivings');
		$this->db->join('suppliers', 'receivings.supplier_id = suppliers.person_id', 'left');
		$this->db->join('people', 'suppliers.person_id = people.person_id', 'left');
		$this->db->where('receivings.deleted', 0);
		$this->db->where('receivings.suspended', 1);
		$this->db->where('location_id', $location_id);
		$this->db->order_by('receiving_id');
		$receivings = $this->db->get()->result_array();

		for($k=0;$k<count($receivings);$k++)
		{
			$item_names = array();
			$this->db->select('name');
			$this->db->from('items');
			$this->db->join('receivings_items', 'receivings_items.item_id = items.item_id');
			$this->db->where('receiving_id', $receivings[$k]['receiving_id']);
		
			foreach($this->db->get()->result_array() as $row)
			{
				$item_names[] = $row['name'];
			}
			
			$receivings[$k]['items'] = implode(', ', $item_names);
		}
		
		return $receivings;
	}
	
	function get_suspended_receivings_for_item($item_id)
	{
		$this->db->from('receivings');
		$this->db->join('receivings_items', 'receivings.receiving_id = receivings_items.receiving_id');
		$this->db->where('receivings.suspended', '1');
		$this->db->where('receivings_items.item_id', $item_id);
		
		return $this->db->get()->result_array();
	}
}
?>
