<?php $this->load->view("partial/header"); ?>
<div id="content-header">
	<h1 > <i class="fa fa-bar-chart"> </i><?php echo lang('reports_reports'); ?> - <?php echo $title ?>	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>

<?php if(isset($pagination) && $pagination) {  ?>
	<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top" >
		<?php echo $pagination;?>
	</div>
<?php }  ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="widget-box">
				<div class="widget-content" style="padding:10px;">
					
					
					<?php $counter = 0;?>
					<?php foreach($report_data as $data) {?>
						<div id="statement_header">
							<div id="company_name"><?php echo $this->config->item('company'); ?></div>
							<?php if($this->config->item('company_logo')) {?>
							<div id="company_logo"><?php echo img(array('src' => $this->Appconfig->get_logo_image())); ?></div>
							<?php } ?>
							<div id="company_address"><?php echo nl2br($this->Location->get_info_for_key('address')); ?></div>
							<div id="company_phone"><?php echo $this->Location->get_info_for_key('phone'); ?></div>
							<?php if($this->config->item('website')) { ?>
								<div id="website"><?php echo $this->config->item('website'); ?></div>
							<?php } ?>
						</div>
						
						<?php
							
						if ($data['customer_info']->company_name)
						{
							$customer_title = $data['customer_info']->company_name;
						}
						else
						{
							$customer_title = $data['customer_info']->first_name .' '. $data['customer_info']->last_name;		
						}
						?>
						<div style="text-align: right;"><i><?php echo $subtitle;?></i></div>
						
						<div class="store_account_address">
						<span><strong><?php echo $customer_title.' '.($data['customer_info']->account_number ? $data['customer_info']->account_number : '') ;?></strong></span><br />
							<?php if($data['customer_info']->address_1) { ?>
									<span><?php echo $data['customer_info']->address_1 . ' '.$data['customer_info']->address_2; ?></span><br />
									<span><?php echo $data['customer_info']->city . ', '.$data['customer_info']->state . ' '.$data['customer_info']->zip; ?></span>
							<?php } ?>
						</div>

						<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover data-table tablesorter" id="sortable_table">
							<thead>
								<tr>
									<th><?php echo lang('reports_id');?></th>
									<th><?php echo lang('reports_date');?></th>
									<th><?php echo lang('reports_sale_id');?></th>
									<th><?php echo lang('reports_debit');?></th>
									<th><?php echo lang('reports_credit');?></th>
									<th><?php echo lang('reports_balance');?></th>
									<?php if (!$hide_items) { ?>
										<th><?php echo lang('reports_items');?></th>
									<?php } ?>
									<th><?php echo lang('sales_comment');?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$amount_due = false;
								foreach($data['store_account_transactions'] as $transaction) 
								{ 
								?>
								<tr>
									<td><?php echo $transaction['sno'];?></td>
									<td><?php echo date(get_date_format(). ' '.get_time_format(), strtotime($transaction[$date_column]));?></td>
									<td><?php echo $transaction['sale_id'] ? anchor('sales/receipt/'.$transaction['sale_id'], $this->config->item('sale_prefix').' '.$transaction['sale_id'], array('target' => '_blank')) : '-';?></td>
									<td><?php echo $transaction['transaction_amount'] > 0 ? to_currency($transaction['transaction_amount']) : to_currency(0); ?></td>
									<td><?php echo $transaction['transaction_amount'] < 0 ? to_currency($transaction['transaction_amount'] * -1) : to_currency(0); ?></td>
									<td><?php echo to_currency($transaction['balance']);?></td>
									<?php if (!$hide_items) { ?>
										<td><?php echo $transaction['items'];?></td>
									<?php } ?>
									<td><?php echo $transaction['comment'];?></td>
								</tr>
								<?php 
								$amount_due = $transaction['balance'];
								} ?>
							</tbody>
						</table>
					</div>					
						
						
						<div class="row">
							<div class="col-md-12" style="margin-left: -40px;">					
								<ul class="stat-boxes">
									<li class="popover-visits">
										<div class="left peity_bar_good"><h5><?php echo lang('sales_amount_due'); ?></h5></div>
										<div class="right">
											<strong><?php echo to_currency($amount_due); ?></strong>
										</div>
									</li>				
								</ul>
							</div>	
						</div>
						<?php if ($counter != count($report_data) - 1) {?>
								<div class="page-break" style="page-break-before: always;"></div>
						<?php } ?>
					<?php $counter++;?>
					<?php } ?>
					
				</div>
			</div>
		</div>
	</div>
</div>

<?php if(isset($pagination) && $pagination) {  ?>
	<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top" >
		<?php echo $pagination;?>
	</div>
<?php }  ?>

<?php $this->load->view("partial/footer"); ?>