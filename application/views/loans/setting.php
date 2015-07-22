<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
    <h1 > <i class="fa fa-lock"></i>  <?php echo lang('loans_setting');?>
     </h1>
</div>

<div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
<div class="row" id="form">
    <div class="col-md-12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fa fa-align-justify"></i>									
                </span>
                <h5><?php echo lang("loans_setting_basic_information"); ?></h5>
            </div>
            <div class="widget-content ">
                <?php echo form_open('loans/update_rate', array('id' => '', 'class' => 'form-horizontal')); ?>
                <div class="row">
                    <?php 
                        foreach($query->result() as $row){
                            
                        }
                    ?>
                    <div class="col-md-12">
                        <h4><?php echo lang("loans_setting_info");?></h4>
                        <div class="form-group">
                             <?php echo form_label(lang('loans_if_ammount_small_than') . ': ', 'condition_one', array('class' => 'col-sm-4 col-md-5 col-lg-4 control-label ' . ($controller_name == 'employees' ? 'required' : 'not_required'))); ?>
                              <input type="text" value="<?php if(set_value('condition_one')){echo set_value('condition_one');}else{echo $row->rate_one;}?>" name="condition_one" id="condition_one"/>%  <span class="col-sm-5 text-danger pull-right"><?php echo form_error('condition_one'); ?></span>
                        </div>
                        <div class="form-group">
                             <?php echo form_label(lang('loans_if_ammount_getter_than') . ': ', 'condition_two', array('class' => 'col-sm-4 col-md-5 col-lg-4 control-label ' . ($controller_name == 'employees' ? 'required' : 'not_required'))); ?>
                              <input type="text" value="<?php if(set_value('condition_two')){echo set_value('condition_two');}else{echo $row->rate_two;}?>" name="condition_two" id="condition_two"/>%  <span class="col-sm-5 text-danger pull-right"><?php echo form_error('condition_two'); ?></span>
                        </div>
                        <div class="col-md-3"></div><center class="col-md-5 alert-success text-success"><?php echo $success; ?></center>
                    </div>

                    <div class="form-actions">
                        <input id="update_loan" type="submit" class="btn btn-info" name="submit" id="submit" value="<?php echo lang('common_submit');?>" />
                        <a href="<?php echo base_url().'loans';?>" class="btn btn-danger"><?php echo lang('common_cancel'); ?></a>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
    </div>
</div>
<script src="<?php echo base_url();?>js/bootstrap-filestyle.min.js" type="text/javascript"></script>
<?php $this->load->view("partial/footer"); ?>