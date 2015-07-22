<?php
function to_currency($number, $decimals = 2)
{
	$CI =& get_instance();
	$currency_symbol = $CI->config->item('currency_symbol') ? $CI->config->item('currency_symbol') : '$';
	if($number >= 0)
	{
		$ret = $currency_symbol.number_format($number, $decimals, '.', ',');
    }
    else
    {
    	$ret = '&#8209;'.$currency_symbol.number_format(abs($number), $decimals, '.', ',');
    }

	return preg_replace('/(?<=\d{2})0+$/', '', $ret);
}

function round_to_nearest_05($amount)
{
	return round($amount * 2, 1) / 2;
}

function to_currency_no_money($number, $decimals = 2)
{
	$ret = number_format($number, $decimals, '.', '');
	return preg_replace('/(?<=\d{2})0+$/', '', $ret);
}

function to_quantity($val, $show_not_set = TRUE)
{
	if ($val !== NULL)
	{
		return $val == (int)$val ? (int)$val : rtrim($val, '0');		
	}
	
	if ($show_not_set)
	{
		return lang('common_not_set');
	}
	
	return '';
	
}

// reils number 

function to_riel_currency($number, $decimals = 2)
{
    $CI =& get_instance();
    $currency_symbol = '៛';
    if($number >= 0)
    {
            $ret = number_format($number, $decimals, '.', ',').$currency_symbol;
    }
    else
    {
    	$ret = '&#8209;'.number_format(abs($number), $decimals, '.', ',').$currency_symbol;
    }

	return preg_replace('/(?<=\d{2})0+$/', '', $ret);
}
?>