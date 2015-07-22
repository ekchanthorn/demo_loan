<?php $this->load->view("partial/header");
	$controller_name="items";
 ?>
<div id="content-header" class="hidden-print">
	<h1 > <i class="fa fa-list"> </i> <?php echo lang('receivings_list_of_suspended'); ?></h1>
</div>

<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
								<i class="fa fa-th"></i>
							</span>
						<h5><?php echo lang('receivings_suspended_search')?></h5>
					</div>
					<div class="widget-content">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover data-table" id="dTable">
				<thead>	<tr>
					<th><?php echo lang('receivings_id'); ?></th>
					<th><?php echo lang('sales_date'); ?></th>
					<th><?php echo lang('items_supplier'); ?></th>
					<th><?php echo lang('reports_items'); ?></th>
					<th><?php echo lang('sales_comments'); ?></th>
					<th><?php echo lang('sales_unsuspend'); ?></th>
					<th><?php echo lang('receivings_receipt'); ?></th>
					<th><?php echo lang('common_delete'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($suspended_receivings as $suspended_receiving)
				{
				?>
					<tr>
						<td><?php echo $suspended_receiving['receiving_id'];?></td>
						<td><?php echo date(get_date_format(). ' @ '.get_time_format(),strtotime($suspended_receiving['receiving_time']));?></td>
						<td>
							<?php
							if (isset($suspended_receiving['supplier_id']))
							{
								$supplier = $this->Supplier->get_info($suspended_receiving['supplier_id']);
								echo $supplier->company_name.' ('.$supplier->first_name. ' '. $supplier->last_name.')';
							}
							else
							{
							?>
								&nbsp;
							<?php
							}
							?>
						</td>
						<td><?php echo $suspended_receiving['items'];?></td>
						<td><?php echo $suspended_receiving['comment'];?></td>
						<td >
							<?php 
							echo form_open('receivings/unsuspend');
							echo form_hidden('suspended_receiving_id', $suspended_receiving['receiving_id']);
							?>
							<input type="submit" name="submit" value="<?php echo lang('sales_unsuspend'); ?>" id="submit_unsuspend" class="btn btn-primary">
							</form>
						</td>
						<td>
							<?php 
							echo form_open('receivings/receipt/'.$suspended_receiving['receiving_id'], array('method'=>'get', 'class' => 'form_receipt_suspended_recv'));
							?>
							<input type="submit" name="submit" value="<?php echo lang('sales_recp'); ?>" id="submit_receipt" class="btn btn-primary">
							</form>
						</td>
						<td>
							<?php
						 	echo form_open('receivings/delete_suspended_receiving', array('class' => 'form_delete_suspended_recv'));
							echo form_hidden('suspended_receiving_id', $suspended_receiving['receiving_id']);
							?>
							<input type="submit" name="submit" value="<?php echo lang('common_delete'); ?>" id="submit_delete" class="btn btn-danger">
							</form>
						</td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
			</div>
</div></div></div>


<script type="text/javascript">
$(".form_delete_suspended_recv").submit(function()
{
	if (!confirm(<?php echo json_encode(lang("receivings_delete_confirmation")); ?>))
	{
		return false;
	}
});
</script>