<?php
/*
This interface is implemented by any credit card processor in the system
*/

interface iCreditCardProcessor
{
	public function start_cc_processing();
	public function finish_cc_processing();
	public function cancel_cc_processing();
}
?>