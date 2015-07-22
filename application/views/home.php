<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
    <h1><i class="icon fa fa-dashboard"></i> <?php echo lang('common_dashboard'); ?></h1>
</div>
<div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>

</div>
<div class="clear"></div>
<div class="text-center">					
    <h3><?php echo lang('common_welcome_message'); ?></h3>
<!--<ul class="quick-actions">
        <?php foreach ($allowed_modules->result() as $module) { ?>
            <li <?php echo $module->module_id == $this->uri->segment(1) ? 'class="active"' : ''; ?>  > 
                <a class="right" href="<?php echo site_url("$module->module_id"); ?>">	<i class="text-info fa fa-<?php echo $module->icon; ?> left fa "></i> <?php echo lang("module_" . $module->module_id) ?></a>
            </li>
        <?php } ?>
    </ul>-->

    <?php if (!$this->config->item('hide_dashboard_statistics')) { ?>

        <div class="row">
            <div class="widget-box">
                <div class="widget-title"><span class="icon"><i class="fa fa-signal"></i></span><h5><?php echo lang('statistics') ?></h5></div>
                <div class="widget-content">
                    <div class="row">
                        <div class="col-md-4">
                            <ul class="site-stats">
                                <li class="bg-green"> <a href="<?php echo site_url('loans'); ?>"><h1> <i class="pull-left fa-3x fa fa-dollar"></i></h1><br/> <h2> <?php echo $total_loans; ?></h2><h4><?php echo lang('common_total') . " " . lang('module_loans'); ?></h4></a></li>
                                <li class="bg-blue">  <a href="<?php echo site_url('pawns'); ?>"><h1> <i class="pull-left fa-3x fa fa-tasks"></i></h1><br/> <h2> <?php echo $total_giftcards; ?></h2><h4><?php echo lang('common_total') . " " .lang('module_pawns'); ?> </h4></a></li>
                                <!--<li><a href="<?php echo site_url('item_kits'); ?>"><h3> <i class="fa fa-inbox"></i>  <?php echo lang('module_item_kits'); ?>  :  <strong><?php echo $total_item_kits; ?></strong></h3></a></li>-->
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul class="site-stats">
                                <li class="bg-yellow">  <a href="<?php echo site_url('customers'); ?>"><h1> <i class="pull-left fa-3x fa fa-group"></i></h1><br/> <h2><?php echo $total_customers; ?></h2> <h4> <?php echo lang('common_total') . " " . lang('module_customers'); ?> </h4></a></li>
                                <li class="bg-purple"> <a href="<?php echo site_url('employees'); ?>"> <h1> <i class="pull-left fa-3x fa fa-user"></i></h1><br/> <h2><?php echo $total_employees; ?></h2> <h4><?php echo lang('common_total') . " " . lang('module_employees'); ?>  </h4> </a></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul class="site-stats">
                                <li class="bg-red"><a href="<?php echo site_url('items'); ?>"><h1> <i class="pull-left fa-3x fa fa-inbox"></i></h1><br/> <h2><?php echo $total_items; ?> </h2> <h4><?php echo lang('common_total') . " " . lang('module_items'); ?> </h4></a> </li>
                                <li class="bg-skype"> <a href="<?php echo site_url('sales'); ?>"><h1> <i class="pull-left fa-3x fa fa-shopping-cart"></i></h1><br/> <h2><?php echo $total_sales; ?></h2> <h4><?php echo lang('common_total') . " " . lang('module_sales'); ?> </h4></a></li>
                                
                            </ul>
                        </div>
                        <div class="col-md-12"></div>
                       <!-- this is for have to pay for loans -->
                        <div class="thumbnail col-md-6">
                          <h4 class="text-info"><?php echo lang('loans_have_to_pay'); ?></h4>
                            <table class="table table-bordered table-hover table-responsive">
                                 <tr>
                                     <td><?php echo lang('reports_no');?></td>
                                     <td><?php echo lang('common_fullname');?></td>
                                     <td><?php echo lang('loans_print_date');?></td>
                                     <td><?php echo lang('loans_late');?></td>
                                     <td><?php echo lang('loans_view');?></td>
                                 </tr>
                                 <?php $i = 1; ?>
                                 <?php if($loan_pay_today->num_rows()<1){
                                     echo "<tr><td colspan='5'><span class='text-center text-warning' >".lang('common_no_persons_to_display')."</span></tr>";
                                 }else { ?>
                                 <?php foreach ($loan_pay_today->result() as $loan_now){?>
                                 <tr>
                                     <td><?php echo $i;?></td>
                                     <td><?php echo $loan_now->first_name.' '.$loan_now->last_name;?></td>
                                     <td><?php echo date('d-m-Y',  strtotime($loan_now->pay_date));?></td>
                                     <td>
                                         <?php
                                            echo date_distant($loan_now->pay_date,date('Y-m-d'));
                                         ?>
                                     </td>
                                     <td><a href="<?php echo base_url().'loans/view_schedule/'.$loan_now->loan_id;?>"><i data-toggle="tooltip" data-placement="top" title="<?php echo lang('loans_view');?>" class="fa fa-expand"></i></a></td>
                                 </tr>
                                 <?php $i++; } } ?>
                                
                            </table>
                            <?php if($count_row_loan>10){?>
                          <a href="<?php echo base_url().'home/loan';?>" class="btn btn-default form-control"><?php echo lang('common_view_all');?> >>></a>
                            <?php }?>
                        </div>
                       <!--end loan and start pawn-->
                        <div class="thumbnail col-md-6">
                            <h4 class="text-info"><?php echo lang('pawns_have_to_pay'); ?></h4>
                            <table class="table table-bordered table-hover table-responsive">
                                 <tr>
                                     <td><?php echo lang('reports_no');?></td>
                                     <td><?php echo lang('common_fullname');?></td>
                                     <td><?php echo lang('pawns_print_date');?></td>
                                     <td><?php echo lang('pawns_late');?></td>
                                     <td><?php echo lang('pawns_view');?></td>
                                 </tr>
                                 <?php $i = 1; ?>
                                 <?php if($pawn_pay_today->num_rows()<1){
                                     echo "<tr><td colspan='5'><span class='text-center text-warning' >".lang('common_no_persons_to_display')."</span></tr>";
                                 }else { ?>
                                 <?php foreach ($pawn_pay_today->result() as $pawn_row){?>
                                 <tr>
                                     <td><?php echo $i;?></td>
                                     <td><?php echo $pawn_row->first_name.' '.$pawn_row->last_name;?></td>
                                     <td><?php echo date('d-m-Y',  strtotime($pawn_row->pay_date));?></td>
                                     <td>
                                         <?php
                                            echo date_distant($pawn_row->pay_date,date('Y-m-d'));
                                         ?>
                                     </td>
                                     <td><a href="<?php echo base_url().'pawns/view_schedule/'.$pawn_row->pawn_id;?>"><i data-toggle="tooltip" data-placement="top" title="<?php echo lang('pawns_view');?>" class="fa fa-expand"></i></a></td>
                                 </tr>
                                 <?php $i++; } } ?>
                            </table>
                            <?php if($count_row_pawn>10){?>
                                      <a href="<?php echo base_url().'home/pawn';?>" class="btn btn-default form-control"><?php echo lang('common_view_all');?> >>></a>
                            <?php }?>
                        </div>
                        
                    </div>
                </div>

            </div>		
        </div>
    <?php } ?>
</div>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<?php $this->load->view("partial/footer"); ?>