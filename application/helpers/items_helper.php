<?php
function get_items_barcode_data($item_ids)
{
	$CI =& get_instance();	
	$result = array();

	$item_ids = explode('~', $item_ids);
	foreach ($item_ids as $item_id)
	{
		$item_info = $CI->Item->get_info($item_id);
		$item_location_info = $CI->Item_location->get_info($item_id);
		
		$today =  strtotime(date('Y-m-d'));
		$is_item_location_promo = ($item_location_info->start_date !== NULL && $item_location_info->end_date !== NULL) && (strtotime($item_location_info->start_date) <= $today && strtotime($item_location_info->end_date) >= $today);
		$is_item_promo = ($item_info->start_date !== NULL && $item_info->end_date !== NULL) && (strtotime($item_info->start_date) <= $today && strtotime($item_info->end_date) >= $today);
		
		$regular_item_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
		
		
		if ($is_item_location_promo)
		{
			$item_price = $item_location_info->promo_price;
		}
		elseif ($is_item_promo)
		{
			$item_price = $item_info->promo_price;
		}
		else
		{
			$item_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
		}		
		
		if($CI->config->item('barcode_price_include_tax'))
		{
			if($item_info->tax_included)
			{
				$result[] = array('name' => ($is_item_location_promo || $is_item_promo ? '<span style="text-decoration: line-through;">'.to_currency($regular_item_price).'</span> ' : ' ').to_currency($item_price).': '.$item_info->name, 'id'=> number_pad($item_id, 10));
			}
			else
			{				
				$result[] = array('name' => ($is_item_location_promo || $is_item_promo ? '<span style="text-decoration: line-through;">'.to_currency(get_price_for_item_including_taxes($item_id,$regular_item_price)).'</span> ' : ' ').to_currency(get_price_for_item_including_taxes($item_id,$item_price)).': '.$item_info->name, 'id'=> number_pad($item_id, 10));
				
	  	 	}
	  }
	  else
	  {
		if ($item_info->tax_included)
		{
		    $result[] = array('name' =>($is_item_location_promo || $is_item_promo ? '<span style="text-decoration: line-through;">'.to_currency(get_price_for_item_excluding_taxes($item_id, $regular_item_price)).'</span> ' : ' ').to_currency(get_price_for_item_excluding_taxes($item_id, $item_price)).': '.$item_info->name, 'id'=> number_pad($item_id, 10));
		}
		else
		{
			$result[] = array('name' => ($is_item_location_promo || $is_item_promo ? '<span style="text-decoration: line-through;">'.to_currency($regular_item_price).'</span> ' : ' ').to_currency($item_price).': '.$item_info->name, 'id'=> number_pad($item_id, 10));
	  	}
	  }
	}
	return $result;
}

function get_price_for_item_excluding_taxes($item_id_or_line, $item_price_including_tax, $sale_id = FALSE)
{
	$return = FALSE;
	$CI =& get_instance();
	
	if ($sale_id !== FALSE)
	{
		$tax_info = $CI->Sale->get_sale_items_taxes($sale_id, $item_id_or_line);
	}	
	else
	{
		$tax_info = $CI->Item_taxes_finder->get_info($item_id_or_line);
	}
	
	if (count($tax_info) == 2 && $tax_info[1]['cumulative'] == 1)
	{
		$return = $item_price_including_tax/(1+($tax_info[0]['percent'] /100) + ($tax_info[1]['percent'] /100) + (($tax_info[0]['percent'] /100) * (($tax_info[1]['percent'] /100))));
	}
	else //0 or more taxes NOT cumulative
	{
		$total_tax_percent = 0;
		
		foreach($tax_info as $tax)
		{
			$total_tax_percent+=$tax['percent'];
		}
		
		$return = $item_price_including_tax/(1+($total_tax_percent /100));
	}
	
	if ($return !== FALSE)
	{
		return to_currency_no_money($return, 10);
	}
	
	return FALSE;
}

function get_price_for_item_including_taxes($item_id_or_line, $item_price_excluding_tax, $sale_id = FALSE)
{
	$return = FALSE;
	$CI =& get_instance();
	if ($sale_id !== FALSE)
	{
		$tax_info = $CI->Sale->get_sale_items_taxes($sale_id,$item_id_or_line);
	}	
	else
	{
		$tax_info = $CI->Item_taxes_finder->get_info($item_id_or_line);
	}
	
	if (count($tax_info) == 2 && $tax_info[1]['cumulative'] == 1)
	{
		$first_tax = ($item_price_excluding_tax*($tax_info[0]['percent']/100));
		$second_tax = ($item_price_excluding_tax + $first_tax) *($tax_info[1]['percent']/100);
		$return = $item_price_excluding_tax + $first_tax + $second_tax;
	}	
	else //0 or more taxes NOT cumulative
	{
		$total_tax_percent = 0;
		
		foreach($tax_info as $tax)
		{
			$total_tax_percent+=$tax['percent'];
		}
		
		$return = $item_price_excluding_tax*(1+($total_tax_percent /100));
	}

	
	if ($return !== FALSE)
	{
		return to_currency_no_money($return, 10);
	}
	
	return FALSE;
}

function get_commission_for_item($item_id, $price, $quantity,$discount)
{
	$CI =& get_instance();
	$CI->load->library('sale_lib');

	$employee_id=$CI->sale_lib->get_sold_by_employee_id();
	$sales_person_info = $CI->Employee->get_info($employee_id);
	$employee_id=$CI->Employee->get_logged_in_employee_info()->person_id;
	$logged_in_employee_info = $CI->Employee->get_info($employee_id);
	
	$item_info = $CI->Item->get_info($item_id);
	
	if ($item_info->commission_fixed > 0)
	{
		return $quantity*$item_info->commission_fixed;
	}
	elseif($item_info->commission_percent > 0)
	{
		return to_currency_no_money(($price*$quantity-$price*$quantity*$discount/100)*($item_info->commission_percent/100));
	}
	elseif($CI->config->item('select_sales_person_during_sale'))
	{
		if($sales_person_info->commission_percent > 0)
		{
			return to_currency_no_money(($price*$quantity-$price*$quantity*$discount/100)*((float)($sales_person_info->commission_percent)/100));
		}
		return to_currency_no_money(($price*$quantity-$price*$quantity*$discount/100)*((float)($CI->config->item('commission_default_rate'))/100));
	}
	elseif($logged_in_employee_info->commission_percent > 0)
	{
		return to_currency_no_money(($price*$quantity-$price*$quantity*$discount/100)*((float)($logged_in_employee_info->commission_percent)/100));
	}
	else
	{
		return to_currency_no_money(($price*$quantity-$price*$quantity*$discount/100)*((float)($CI->config->item('commission_default_rate'))/100));
	}
}
?>