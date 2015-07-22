<?php
require_once ("interfaces/icredit_card_processor.php");
class MercuryProcessor implements iCreditCardProcessor
{
	private $sales_controller;
	
	function __construct($sales_controller)
	{
		$this->sales_controller = $sales_controller;
	}
	
	public function start_cc_processing()
	{
		$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/hcws/hcservice.asmx?WSDL': 'https://hc.mercurypay.com/hcws/hcservice.asmx?WSDL';
		$cc_amount = to_currency_no_money($this->sales_controller->sale_lib->get_payment_amount(lang('sales_credit')));
		$tax_amount = to_currency_no_money(($this->sales_controller->sale_lib->get_total() - $this->sales_controller->sale_lib->get_subtotal()) * ($cc_amount / $this->sales_controller->sale_lib->get_total()));
		$customer_id = $this->sales_controller->sale_lib->get_customer();
		$customer_name = '';
		if ($customer_id != -1)
		{
			$customer_info=$this->sales_controller->Customer->get_info($customer_id);
			$customer_name = $customer_info->first_name.' '.$customer_info->last_name;
		}
		
		if(!$this->sales_controller->sale_lib->get_use_saved_cc_info())
		{
			$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->sales_controller->Employee->get_logged_in_employee_info()->person_id), 0, 16);

			$parameters = array(
				'request' => array(
					'MerchantID' => $this->sales_controller->Location->get_info_for_key('merchant_id'),
					'Password' => $this->sales_controller->Location->get_info_for_key('merchant_password'),
					'TranType' => $cc_amount > 0 ? 'Sale' : 'Return',
					'TotalAmount' => abs($cc_amount),
					'PartialAuth' => 'On',
					'Frequency' => 'OneTime',
					'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->sales_controller->Employee->get_logged_in_employee_info()->person_id,
					'Invoice' => $invoice_number,
					'Memo' => 'PHP POS '.APPLICATION_VERSION,
					'TaxAmount' => abs($tax_amount),
					'CardHolderName' => substr(preg_replace("/[^A-Za-z ]/", '', $customer_name),0,30),
					'ForceManualTablet' => 'Off',
					'ProcessCompleteUrl' => site_url('sales/finish_cc_processing'),
					'ReturnUrl' => site_url('sales/cancel_cc_processing'),
					'LaneID' => $this->sales_controller->Employee->get_logged_in_employee_current_register_id() ? $this->sales_controller->Employee->get_logged_in_employee_current_register_id()  : 0
				)
			);

			if (isset($customer_info) && $customer_info->zip && $this->_is_valid_zip($customer_info->zip))
			{
				$customer_info->zip = str_replace('-','',$customer_info->zip);
				$parameters['request']['AVSZip'] = $customer_info->zip;
			}

			$client = new SoapClient($service_url,array('trace' => TRUE));
			$result = $client->InitializePayment($parameters);
			$response_code = $result->InitializePaymentResult->ResponseCode;

			if ($response_code == 0)
			{
				$payment_id = $result->InitializePaymentResult->PaymentID;
				$hosted_checkout_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/CheckoutPOS.aspx' : 'https://hc.mercurypay.com/CheckoutPOS.aspx';
				$this->sales_controller->load->view('sales/mercury_hosted_checkout', array('payment_id' => $payment_id, 'hosted_checkout_url' =>$hosted_checkout_url ));
			}
			else
			{
				$this->sales_controller->_reload(array('error' => lang('sales_credit_card_processing_is_down')), false);
			}			
		}
		elseif($customer_info->cc_token) //We have saved credit card information, process it
		{
			$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/tws/transactionservice.asmx?WSDL': 'https://hc.mercurypay.com/tws/transactionservice.asmx?WSDL';
			$client = new SoapClient($service_url,array('trace' => TRUE));
			$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->sales_controller->Employee->get_logged_in_employee_info()->person_id), 0, 16);
			
			$parameters = array(
				'request' => array(
					'Token' => $customer_info->cc_token,
					'MerchantID' => $this->sales_controller->Location->get_info_for_key('merchant_id'),
					'PurchaseAmount' => $cc_amount,
					'PartialAuth' => FALSE,
					'Frequency' => 'OneTime',
					'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->sales_controller->Employee->get_logged_in_employee_info()->person_id,
					'Invoice' => $invoice_number,
					'Memo' => 'PHP POS '.APPLICATION_VERSION,
					'TaxAmount' => $tax_amount,
					'CardHolderName' => substr(preg_replace("/[^A-Za-z ]/", '', $customer_name),0,30),
				),
				'password' => $this->sales_controller->Location->get_info_for_key('merchant_password'),
			);

			if (isset($customer_info) && $customer_info->zip && $this->_is_valid_zip($customer_info->zip))
			{
				$customer_info->zip = str_replace('-','',$customer_info->zip);
				$parameters['request']['Zip'] = $customer_info->zip;
			}
			$result = $client->CreditSaleToken($parameters);
			
			$status = $result->CreditSaleTokenResult->Status;

			
			if ($status == 'Approved')	
			{
				$token =  $result->CreditSaleTokenResult->Token;
				$ref_no =  $result->CreditSaleTokenResult->RefNo;
				$auth_code = $result->CreditSaleTokenResult->AuthCode;
				$masked_account = $customer_info->cc_preview;
				$card_issuer = $customer_info->card_issuer;
				$acq_ref_data = $result->CreditSaleTokenResult->AcqRefData;
				$process_data =  $result->CreditSaleTokenResult->ProcessData;
				
				$person_info = array('person_id' => $this->sales_controller->sale_lib->get_customer());
				$customer_info = array('cc_token' => $token);
				$this->sales_controller->Customer->save($person_info,$customer_info,$this->sales_controller->sale_lib->get_customer());
				$this->sales_controller->session->set_userdata('ref_no', $ref_no);
				$this->sales_controller->session->set_userdata('auth_code', $auth_code);
				$this->sales_controller->session->set_userdata('masked_account', $masked_account);
				$this->sales_controller->session->set_userdata('card_issuer', $card_issuer);
				
				//If the sale payments cover the total, redirect to complete (receipt)
				if ($this->sales_controller->_payments_cover_total())
				{
					redirect(site_url('sales/complete'));
				}
				else //Change payment type to Partial Credit Card and show sales interface
				{
					$credit_card_amount = to_currency_no_money($this->sales_controller->sale_lib->get_payment_amount(lang('sales_credit')));

					$partial_transaction = array(
						'AuthCode' => $auth_code,
						'Frequency' => 'OneTime',
						'Memo' => 'PHP POS '.APPLICATION_VERSION,
						'Invoice' => $invoice_number,
						'MerchantID' => $this->sales_controller->Location->get_info_for_key('merchant_id'),
						'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->sales_controller->Employee->get_logged_in_employee_info()->person_id,
						'PurchaseAmount' => $credit_card_amount,
						'RefNo' => $ref_no,
						'Token' => $token,
						'AcqRefData' =>$acq_ref_data,
						'ProcessData' => $process_data,
					);
										
					$this->sales_controller->sale_lib->delete_payment($this->sales_controller->sale_lib->get_payment_ids(lang('sales_credit')));
					$this->sales_controller->sale_lib->add_payment(lang('sales_partial_credit'), $credit_card_amount, FALSE, $masked_account, $card_issuer);
					$this->sales_controller->sale_lib->add_partial_transaction($partial_transaction);
					$this->sales_controller->_reload(array('warning' => lang('sales_credit_card_partially_charged_please_complete_sale_with_another_payment_method')), false);			
				}
			}
			else
			{
				//If we have failed, remove cc token and cc preview
				$person_info = array('person_id' => $this->sales_controller->sale_lib->get_customer());
				$customer_info = array('cc_token' => NULL, 'cc_preview' => NULL, 'card_issuer' => NULL);
				$this->sales_controller->Customer->save($person_info,$customer_info,$this->sales_controller->sale_lib->get_customer());
				
				//Clear cc token for using saved cc info
				$this->sales_controller->sale_lib->clear_use_saved_cc_info();
				$this->sales_controller->_reload(array('error' => lang('sales_charging_card_failed_please_try_again')), false);
			}

		}
	}
	
	public function finish_cc_processing()
	{
		$return_code = $this->sales_controller->input->get("ReturnCode");
		//TODO
		//Check return code 0
		//Check return code 101: Decline
		
		//Only make verify payment call with the above return codes
		
		$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/hcws/hcservice.asmx?WSDL': 'https://hc.mercurypay.com/hcws/hcservice.asmx?WSDL';
		$parameters = array(
			'request' => array(
				'MerchantID' => $this->sales_controller->Location->get_info_for_key('merchant_id'),
				'PaymentID' => $this->sales_controller->input->get('PaymentID'),
				'Password' => $this->sales_controller->Location->get_info_for_key('merchant_password'),
			)
		);

		$client = new SoapClient($service_url,array('trace' => TRUE));
		$result = $client->VerifyPayment($parameters);
		$response_code = $result->VerifyPaymentResult->ResponseCode;
		$status = $result->VerifyPaymentResult->Status;
		$total_amount = $result->VerifyPaymentResult->Amount;
		$auth_amount = $result->VerifyPaymentResult->AuthAmount;
		
		$auth_code = $result->VerifyPaymentResult->AuthCode;
		$acq_ref_data = $result->VerifyPaymentResult->AcqRefData;
		$ref_no =  $result->VerifyPaymentResult->RefNo;
		$token =  $result->VerifyPaymentResult->Token;
		$masked_account = $result->VerifyPaymentResult->MaskedAccount;
		$process_data =  $result->VerifyPaymentResult->ProcessData;
		$card_issuer = $result->VerifyPaymentResult->CardType;
		
		if ($response_code == 0 && $status == 'Approved')
		{
			$result = $client->AcknowledgePayment($parameters);
			$response_code = $result->AcknowledgePaymentResult;
			
			$this->sales_controller->session->set_userdata('ref_no', $ref_no);
			$this->sales_controller->session->set_userdata('auth_code', $auth_code);
			
			if ($response_code == 0 && $auth_amount == $total_amount)
			{
				$this->sales_controller->session->set_userdata('masked_account', $masked_account);
				$this->sales_controller->session->set_userdata('card_issuer', $card_issuer);
				
				$info=$this->sales_controller->Customer->get_info($this->sales_controller->sale_lib->get_customer());
				
				//We want to save/update card:
				//1. User decides to save credit card info
				//2. We already have a saved credit and need to update
				if (($this->sales_controller->sale_lib->get_save_credit_card_info() or ($info->cc_token && $info->cc_preview)) && $this->sales_controller->sale_lib->get_customer() != -1)
				{
					$person_info = array('person_id' => $this->sales_controller->sale_lib->get_customer());
					$customer_info = array('cc_token' => $token, 'cc_preview' => $masked_account, 'card_issuer' => $card_issuer);
					$this->sales_controller->Customer->save($person_info,$customer_info,$this->sales_controller->sale_lib->get_customer());
				}
								
				//If the sale payments cover the total, redirect to complete (receipt)
				if ($this->sales_controller->_payments_cover_total())
				{
					redirect(site_url('sales/complete'));
				}
				else //Change payment type to Partial Credit Card and show sales interface
				{
					$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->sales_controller->Employee->get_logged_in_employee_info()->person_id), 0, 16);
					
					$credit_card_amount = to_currency_no_money($this->sales_controller->sale_lib->get_payment_amount(lang('sales_credit')));
					
					$partial_transaction = array(
						'AuthCode' => $auth_code,
						'Frequency' => 'OneTime',
						'Memo' => 'PHP POS '.APPLICATION_VERSION,
						'Invoice' => $invoice_number,
						'MerchantID' => $this->sales_controller->Location->get_info_for_key('merchant_id'),
						'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->sales_controller->Employee->get_logged_in_employee_info()->person_id,
						'PurchaseAmount' => $credit_card_amount,
						'RefNo' => $ref_no,
						'Token' => $token,
						'AcqRefData' =>$acq_ref_data,
						'ProcessData' => $process_data,
					);
															
					$this->sales_controller->sale_lib->delete_payment($this->sales_controller->sale_lib->get_payment_ids(lang('sales_credit')));
					$this->sales_controller->sale_lib->add_payment(lang('sales_partial_credit'), $credit_card_amount, FALSE, $masked_account, $card_issuer);
					$this->sales_controller->sale_lib->add_partial_transaction($partial_transaction);
					$this->sales_controller->_reload(array('warning' => lang('sales_credit_card_partially_charged_please_complete_sale_with_another_payment_method')), false);			
				}
			}
			elseif($response_code == 0 && $auth_amount < $total_amount)
			{
				$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->sales_controller->Employee->get_logged_in_employee_info()->person_id), 0, 16);
				
				$partial_transaction = array(
					'AuthCode' => $auth_code,
					'Frequency' => 'OneTime',
					'Memo' => 'PHP POS '.APPLICATION_VERSION,
					'Invoice' => $invoice_number,
					'MerchantID' => $this->sales_controller->Location->get_info_for_key('merchant_id'),
					'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->sales_controller->Employee->get_logged_in_employee_info()->person_id,
					'PurchaseAmount' => $auth_amount,
					'RefNo' => $ref_no,
					'Token' => $token,
					'AcqRefData' =>$acq_ref_data,
					'ProcessData' => $process_data,
				);
				
				$this->sales_controller->sale_lib->delete_payment($this->sales_controller->sale_lib->get_payment_ids(lang('sales_credit')));
				$this->sales_controller->sale_lib->add_payment(lang('sales_partial_credit'), $auth_amount, FALSE, $masked_account, $card_issuer);
				$this->sales_controller->sale_lib->add_partial_transaction($partial_transaction);
				$this->sales_controller->_reload(array('warning' => lang('sales_credit_card_partially_charged_please_complete_sale_with_another_payment_method')), false);
			}
			else
			{
				$this->sales_controller->_reload(array('error' => lang('sales_acknowledge_payment_failed_please_contact_support')), false);
			}
		}
		else
		{
			$client->AcknowledgePayment($parameters);
			$this->sales_controller->_reload(array('error' => $result->VerifyPaymentResult->StatusMessage.': '.$result->VerifyPaymentResult->DisplayMessage), false);
		}
	}
	public function cancel_cc_processing()
	{
		$this->sales_controller->sale_lib->delete_payment($this->sales_controller->sale_lib->get_payment_ids(lang('sales_credit')));
		$this->sales_controller->_reload(array('error' => lang('sales_cc_processing_cancelled')), false);
	}
	
	public function void_partial_transactions()
	{
		$void_success = true;
		
		if ($partial_transactions = $this->sales_controller->sale_lib->get_partial_transactions())
		{
			$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/tws/transactionservice.asmx?WSDL': 'https://hc.mercurypay.com/tws/transactionservice.asmx?WSDL';
			
			foreach($partial_transactions as $partial_transaction)
			{
				$parameters = array(
					'request' => $partial_transaction,
					'password' => $this->sales_controller->Location->get_info_for_key('merchant_password'),
				);
				
				$client = new SoapClient($service_url,array('trace' => TRUE));
				$result = $client->CreditReversalToken($parameters);
				
				$status = $result->CreditReversalTokenResult->Status;
				if ($status != 'Approved')
				{
					unset($parameters['AcqRefData']);
					unset($parameters['ProcessData']);
					$result = $client->CreditVoidSaleToken($parameters);
					$status = $result->CreditVoidSaleTokenResult->Status;
					
					if ($status != 'Approved')
					{
						$void_success = false;
					}
				}
			}
		}
		
		return $void_success;
	}
	
	function _is_valid_zip($zip)
	{
		if (strlen($zip) == 5 || strlen($zip) == 9)
		{
			return is_numeric($zip);
		}
		elseif(strlen($zip) == 10)
		{
			$parts = explode('-', $zip);
			return (count($parts) == 2 && is_numeric($parts[0]) && is_numeric($parts[1]));
		}
		return FALSE;
	}
	
}