<?php $this->load->view("partial/header"); ?>
<div id="receipt_wrapper">
	<div id="receipt_header">
		<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<?php if($this->config->item('company_logo')) {?>
		<div id="company_logo"><?php echo img(array('src' => $this->Appconfig->get_logo_image())); ?></div>
		<?php } ?>
		<div id="company_address"><?php echo nl2br($this->Location->get_info_for_key('address')); ?></div>
		<div id="company_phone"><?php echo $this->Location->get_info_for_key('phone'); ?></div>
		<?php if($this->config->item('website')) { ?>
			<div id="website"><?php echo $this->config->item('website'); ?></div>
		<?php } ?>
		<div id="sale_receipt"><?php echo lang('sales_fulfillment_sheet'); ?></div>
		<div id="sale_time"><?php echo $transaction_time ?></div>
		<div class="pull-right"><button class="btn btn-primary text-white hidden-print" id="new_sale_button_1" onclick="window.location='<?php echo site_url('sales'); ?>'" > <?php echo lang('sales_new_sale'); ?> </button></div>
	</div>
	<div id="receipt_general_info">
		<?php if(isset($customer))
		{
		?>
			<div id="customer"><?php echo lang('customers_customer').": ".$customer; ?></div>
			<?php if(!empty($customer_address_1)){ ?><div><?php echo lang('common_address'); ?> : <?php echo $customer_address_1. ' '.$customer_address_2; ?></div><?php } ?>
			<?php if (!empty($customer_city)) { echo $customer_city.' '.$customer_state.', '.$customer_zip;} ?>
			<?php if (!empty($customer_country)) { echo '<div>'.$customer_country.'</div>';} ?>			
			<?php if(!empty($customer_phone)){ ?><div><?php echo lang('common_phone_number'); ?> : <?php echo $customer_phone; ?></div><?php } ?>
			<?php if(!empty($customer_email)){ ?><div><?php echo lang('common_email'); ?> : <?php echo $customer_email; ?></div><?php } ?>
		<?php
		}
		?>
		<div id="sale_id"><?php echo lang('sales_id').": ".$sale_id; ?></div>
		<div id="employee"><?php echo lang('employees_employee').": ".$employee; ?></div>
		<?php 
		if($this->Location->get_info_for_key('enable_credit_card_processing'))
		{
			echo '<div id="mercahnt_id">'.lang('config_merchant_id').': '.$this->Location->get_info_for_key('merchant_id').'</div>';
		}
		?>
		
	</div>
	<table id="receipt_items">
	<tr>
	<th style="width:<?php echo $discount_exists ? "33%" : "49%"; ?>;text-align:left;"><?php echo lang('items_item'); ?></th>
	<th style="width:20%;text-align:left;" ><?php echo lang('common_price'); ?></th>
	<th style="width:15%;text-align:left;"><?php echo lang('sales_quantity'); ?></th>
	<?php if($discount_exists) 
    {
	?>
	<th style="width:16%;text-align:left;"><?php echo lang('sales_discount'); ?></th>
	<?php
	}
	?>
	<th style="width:16%;text-align:right;"><?php echo lang('sales_total'); ?></th>
	</tr>
	<?php
	if (count($sales_items) > 0)
	{
		?>			
		<tr>
				<td colspan="<?php echo $discount_exists ? '5' : '4'; ?>">
					<h1><?php echo lang('module_items'); ?></h1>
				</td>
			</tr>
		<?php
		$current_category = FALSE;
		foreach($sales_items as $item)
		{
			if ($current_category != $item['category'])
			{
			?>
				<tr>
					<td colspan="<?php echo $discount_exists ? '5' : '4'; ?>">
						<h3><?php echo $item['category'];?></h3>
					</td>
				</tr>
			<?php
				$current_category = $item['category'];
			}
			?>
			<tr>
			<td style="text-align:left;"><?php echo $item['name']; ?><?php if ($item['size']){ ?> (<?php echo $item['size']; ?>)<?php } ?></td>
			<td style="text-align:left;"><?php echo to_currency($item['item_unit_price']); ?></td>
			<td style='text-align:left;'><?php echo to_quantity($item['quantity_purchased']); ?></td>
			<?php if($discount_exists) 
			{
			?>
			<td style='text-align:left;'><?php echo $item['discount_percent']; ?></td>
			<?php
			}
			?>
			<td style='text-align:right;'><?php echo to_currency($item['item_unit_price']*$item['quantity_purchased']-$item['item_unit_price']*$item['quantity_purchased']*$item['discount_percent']/100); ?></td>
			</tr>

		    <tr>
		    <td colspan="3" align="left"><?php echo $item['sales_items_description']; ?></td>
			<td colspan="1" ><?php echo isset($item['serialnumber']) ? $item['serialnumber'] : ''; ?></td>
		
			<?php if($discount_exists) {?>
			<td colspan="1"><?php echo '&nbsp;'; ?></td>
			<?php } ?>
		    </tr>

		<?php
		}
	}
		?>	


	<?php
	if (count($sales_item_kits) > 0)
	{
		?>			
		<tr>
				<td colspan="<?php echo $discount_exists ? '5' : '4'; ?>">
					<h1><?php echo lang('module_item_kits'); ?></h1>
				</td>
			</tr>
		<?php
		$current_category = FALSE;
		foreach($sales_item_kits as $item)
		{
			if ($current_category != $item['category'])
			{
			?>
				<tr>
					<td colspan="<?php echo $discount_exists ? '5' : '4'; ?>">
						<h3><?php echo $item['category'];?></h3>
					</td>
				</tr>
			<?php
				$current_category = $item['category'];
			}
			?>
			<tr>
			<td style="text-align:left;"><?php echo $item['name']; ?></td>
			<td style="text-align:left;"><?php echo to_currency($item['item_kit_unit_price']); ?></td>
			<td style='text-align:left;'><?php echo to_quantity($item['quantity_purchased']); ?></td>
			<?php if($discount_exists) 
			{
			?>
			<td style='text-align:left;'><?php echo $item['discount_percent']; ?></td>
			<?php
			}
			?>
			<td style='text-align:right;'><?php echo to_currency($item['item_kit_unit_price']*$item['quantity_purchased']-$item['item_kit_unit_price']*$item['quantity_purchased']*$item['discount_percent']/100); ?></td>
			</tr>

		    <tr>
		    <td colspan="3" align="left"><?php echo $item['description']; ?></td>
		
			<?php if($discount_exists) {?>
			<td colspan="1"><?php echo '&nbsp;'; ?></td>
			<?php } ?>
		    </tr>

		<?php
		}
	}
		?>

	<tr>
		<td colspan="<?php echo $discount_exists ? '5' : '4'; ?>" align="right">
		<?php if($show_comment_on_receipt==1)
			{
				echo $comment ;
			}
		?>
		</td>
	</tr>
	</table>

	<div id="sale_return_policy">
	<?php echo nl2br($this->config->item('return_policy')); ?>
   <br />   

	</div>
	<div id='barcode'>
	<?php echo "<img src='".site_url('barcode')."?barcode=$sale_id&text=$sale_id' />"; ?>
	</div>
	
	
	
<button class="btn btn-primary text-white hidden-print" id="print_button" onclick="print_fulfillment()" > <?php echo lang('sales_print'); ?> </button>
<br />
	
</div>
<?php $this->load->view("partial/footer"); ?>

<?php if ($this->config->item('print_after_sale'))
{
?>
<script type="text/javascript">
$(window).bind("load", function() {
	window.print();
});
</script>
<?php }  ?>

<script type="text/javascript">
function print_fulfillment()
 {
 	window.print();
 }
 </script>
