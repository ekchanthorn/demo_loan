<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
	<h1><i class="fa fa-upload"></i> <?php echo lang('sales_opening_amount');?></h1>
</div>

<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
	<div class="row">
		<div class="col-md-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-align-justify"></i>									
					</span>
					<h5><?php echo lang('sales_opening_amount_desc'); ?></h5>
				</div>
				<div class="widget-content">
						<div class="col-md-6">
							<div class="table-responsive">
								<table class="table table-bordered text-center">
								<tr>
									<th style="text-align: center;"><?php echo lang('sales_denomination');?></th>
									<th style="text-align: center;"><?php echo lang('reports_count');?></th>
								</tr>
								<tr>
									<td>100's</td>
									<td>
										<?php echo form_input(array(
										'name'=>'100s',
										'id'=>'100s'
										)
									);?>
									</td>
								</tr>
								
								<tr>
									<td>50's</td>
									<td>
										<?php echo form_input(array(
										'name'=>'50s',
										'id'=>'50s'
										)
									);?>
									</td>
								</tr>	
								
								
								<tr>
									<td>20's</td>
									<td>
										<?php echo form_input(array(
										'name'=>'20s',
										'id'=>'20s'
										)
									);?>
									</td>
								</tr>	
								
								
								<tr>
									<td>10's</td>
									<td>
										<?php echo form_input(array(
										'name'=>'10s',
										'id'=>'10s'
										)
									);?>
									</td>
								</tr>	
								
								
								<tr>
									<td>5's</td>
									<td>
										<?php echo form_input(array(
										'name'=>'5s',
										'id'=>'5s'
										)
									);?>
									</td>
								</tr>	
								
								<tr>
									<td>1's</td>
									<td>
										<?php echo form_input(array(
										'name'=>'1s',
										'id'=>'1s'
										)
									);?>
									</td>
								</tr>	

								<tr>
									<td><?php echo lang('sales_half_dollars');?></td>
									<td>
										<?php echo form_input(array(
										'name'=>'half_dollars',
										'id'=>'half_dollars'
										)
									);?>
									</td>
								</tr>	
								<tr>
									<td><?php echo lang('sales_quarters');?></td>
									<td>
										<?php echo form_input(array(
										'name'=>'quarters',
										'id'=>'quarters'
										)
									);?>
									</td>
								</tr>	
								
								<tr>
									<td><?php echo lang('sales_dimes');?></td>
									<td>
										<?php echo form_input(array(
										'name'=>'dimes',
										'id'=>'dimes'
										)
									);?>
									</td>
								</tr>	
								
								<tr>
									<td><?php echo lang('sales_nickels');?></td>
									<td>
										<?php echo form_input(array(
										'name'=>'nickels',
										'id'=>'nickels'
										)
									);?>
									</td>
								</tr>	
								
								<tr>
									<td><?php echo lang('sales_pennies');?></td>
									<td>
										<?php echo form_input(array(
										'name'=>'pennies',
										'id'=>'pennies'
										)
									);?>
									</td>
								</tr>
							</table>
							</div>
						</div>
				


<div class="col-md-6">
	<?php
				$reg_info = $this->Register->get_info($this->Employee->get_logged_in_employee_current_register_id());
				$reg_name = $reg_info->name . '&nbsp;&nbsp;&nbsp;<span class="btn btn-primary">'.lang('sales_change_register').'</span>';
				?>

					<?php echo form_open('sales', array('id'=>'opening_amount_form')); ?>

							
							<div class="control-group controll-croups1">
								 <?php echo form_label(lang('sales_opening_amount').':', 'opening_amount',array('class'=>'control-label ss')); ?>
                    <?php echo form_input(array(
                        'name'=>'opening_amount',
                        'id'=>'opening_amount',
                        'value'=>'')
                    );?>
                    
								</div>
								<div class="form-actions form-actions1">
								<?php echo form_submit(array(
							'name'=>'submit',
							'id'=>'submit',
							'value'=>lang('common_submit'),
							'class'=>'btn btn-primary ')
						);
						?>
								</div>
								<div style="clear:both;"></div>
								
								<div style="text-align: center;">
									<h1><?php echo lang('common_or'); ?></h1>					
											<?php echo lang('locations_register_name');?>: <?php echo anchor('sales/clear_register', $reg_name);?>
									<br /><br />
								</div>
							</div>


				
				
                    <div style="clear:both;"></div>

                    
                </div>
                
				
				</div>
                </div>
				
<?php
echo form_close();
?>
</div>
<?php $this->load->view('partial/footer.php'); ?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$("#opening_amount").focus();
	
	var submitting = false;

	$('#opening_amount_form').validate({
		rules:
		{
			opening_amount: {
				required: true,
				number: true
			}
   		},
		errorClass: "text-danger",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
		},
		messages: {
	   		closing_amount: {
				required: <?php echo json_encode(lang('sales_amount_required')); ?>,
				number: <?php echo json_encode(lang('sales_amount_number')); ?>
			}
   		}
	});
	
	function calculate_total()
	{
		var total = 0;
		
		total+= 100 * $("#100s").val();
		total+= 50 * $("#50s").val();
		total+= 20 * $("#20s").val();
		total+= 10 * $("#10s").val();
		total+= 5 * $("#5s").val();
		total+= 1 * $("#1s").val();
		total+= .5 * $("#half_dollars").val();
		total+= .25 * $("#quarters").val();
		total+= .1 * $("#dimes").val();
		total+= .05 * $("#nickels").val();
		total+= .01 * $("#pennies").val();
		
		$("#opening_amount").val(parseFloat(Math.round(total * 100) / 100).toFixed(2));
	}
	
	$("#100s, #50s, #20s, #10s, #5s, #1s, #half_dollars,#quarters, #dimes,#nickels,#pennies").change(calculate_total);
	$("#100s, #50s, #20s, #10s, #5s, #1s, #half_dollars,#quarters, #dimes,#nickels,#pennies").keyup(calculate_total);

});
</script>