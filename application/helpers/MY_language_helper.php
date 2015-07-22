<?php

function is_rtl_lang()
{
	$CI =& get_instance();
	return ($CI->Employee->is_logged_in() && $CI->Employee->get_logged_in_employee_info()->language  =='arabic') 
			|| ($CI->Employee->is_logged_in() && !$CI->Employee->get_logged_in_employee_info()->language && $CI->config->item('language') == 'arabic') 
			|| !$CI->Employee->is_logged_in() && $CI->config->item('language') == 'arabic';
}