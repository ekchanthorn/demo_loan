<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
	<h1><i class="fa fa-upload"></i> <?php echo lang('sales_choose_register');?></h1>
</div>

<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
	<div class="row">
		<div class="col-md-12">
			<div class="widget-box">

<br>
<br>

					<div class="row">
					<?php
				foreach($this->Register->get_all()->result() as $register) 
				{ ?>
					<div class="col-md-3 site-stats">

					
						<h4><?php echo anchor('sales/choose_register/'.$register->register_id, '<i class="fa fa-inbox"></i> '.$register->name); ?></h4>
						
						
					
					</div>
				<?php } ?>
						
					</div>




			<div class="row">
				
			</div>

				
			
			</div>
		</div>
	</div>
<?php $this->load->view('partial/footer.php'); ?>
<script type='text/javascript'>

</script>