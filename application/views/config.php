<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
    <h1><i class="fa fa-cogs"></i> <?php echo lang('module_' . $controller_name); ?></h1>
</div>

<div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>

<div class="pull-right">
    <div class="row">
        <div class="col-md-12 ">					
        </div>
    </div>
</div>
<ul class="list-inline pull-right">
    <li> <?php echo anchor('config/backup', lang('config_backup_database'), array('class' => 'btn btn-primary text-white pull-right dbBackup')); ?> </li>
    <li> <?php echo anchor('config/optimize', lang('config_optimize_database'), array('class' => 'btn btn-primary text-white pull-right dbOptimize')); ?> </li>
    <li><i id="spin" class="fa fa-spinner fa fa-spin fa fa-3x  hidden"></i> &nbsp;</li>
</ul>

<div class="">
    <div class="row">
        <?php echo lang('config_looking_for_location_settings') . ' ' . anchor($this->Location->count_all() > 1 ? 'locations' : 'locations/view/1', lang('module_locations') . ' ' . lang('config_module')); ?>

        <?php echo form_open_multipart('config/save/', array('id' => 'config_form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
        <!-- Company Information -->
        <div class="col-md-12">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon">
                        <i class="fa fa-align-justify"></i>									
                    </span>
                    <h5><?php echo lang("config_company_info"); ?></h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="form-group">	
                        <?php echo form_label(lang('config_company_logo') . ':', 'company_logo', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_upload(array(
                                'name' => 'company_logo',
                                'id' => 'company_logo',
                                'value' => $this->config->item('company_logo')));
                            ?>		
                        </div>	
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_delete_logo') . ':', 'delete_logo', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php echo form_checkbox('delete_logo', '1'); ?>
                        </div>	
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_company') . ':', 'company', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_input(array(
                                'class' => 'form-control form-inps',
                                'name' => 'company',
                                'id' => 'company',
                                'value' => $this->config->item('company')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_website') . ':', 'website', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_input(array(
                                'class' => 'form-control form-inps',
                                'name' => 'website',
                                'id' => 'website',
                                'value' => $this->config->item('website')));
                            ?>
                        </div>
                    </div>
                </div>
            </div>	
        </div>
        <!-- Taxes & Currency -->
        <div class="col-md-12">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon">
                        <i class="fa fa-align-justify"></i>									
                    </span>
                    <h5><?php echo lang("config_tax_currency_info"); ?></h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="form-group">	
                            <?php echo form_label(lang('common_prices_include_tax') . ':', 'prices_include_tax', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'prices_include_tax',
    'id' => 'prices_include_tax',
    'value' => 'prices_include_tax',
    'checked' => $this->config->item('prices_include_tax')));
?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_default_tax_rate_1') . ':', 'default_tax_1_rate', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-4 col-md-4 col-lg-5">
<?php
echo form_input(array(
    'class' => 'form-control form-inps',
    'name' => 'default_tax_1_name',
    'placeholder' => lang('common_tax_name'),
    'id' => 'default_tax_1_name',
    'size' => '10',
    'value' => $this->config->item('default_tax_1_name') !== FALSE ? $this->config->item('default_tax_1_name') : lang('items_sales_tax_1')));
?>
                        </div>

                        <div class="col-sm-4 col-md-4 col-lg-5">
<?php
echo form_input(array(
    'class' => 'form-control form-inps-tax',
    'placeholder' => lang('items_tax_percent'),
    'name' => 'default_tax_1_rate',
    'id' => 'default_tax_1_rate',
    'size' => '4',
    'value' => $this->config->item('default_tax_1_rate')));
?>
                            <div class="tax-percent-icon">%</div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_default_tax_rate_2') . ':', 'default_tax_1_rate', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-4 col-md-4 col-lg-5">
                            <?php
                            echo form_input(array(
                                'class' => 'form-control form-inps',
                                'name' => 'default_tax_2_name',
                                'placeholder' => lang('common_tax_name'),
                                'id' => 'default_tax_2_name',
                                'size' => '10',
                                'value' => $this->config->item('default_tax_2_name') !== FALSE ? $this->config->item('default_tax_2_name') : lang('items_sales_tax_2')));
                            ?>
                        </div>

                        <div class="col-sm-4 col-md-4 col-lg-5">
<?php
echo form_input(array(
    'class' => 'form-control form-inps-tax',
    'name' => 'default_tax_2_rate',
    'placeholder' => lang('items_tax_percent'),
    'id' => 'default_tax_2_rate',
    'size' => '4',
    'value' => $this->config->item('default_tax_2_rate')));
?>
                            <div class="tax-percent-icon">%</div>
                            <div class="clear"></div>
                                    <?php echo form_checkbox('default_tax_2_cumulative', '1', $this->config->item('default_tax_2_cumulative') ? true : false, 'class="cumulative_checkbox"'); ?>
                            <span class="cumulative_label">
                                    <?php echo lang('common_cumulative'); ?>
                            </span>
                        </div>

                        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 col-lg-9 col-lg-offset-3" style="display: <?php echo $this->config->item('default_tax_3_rate') ? 'none' : 'block'; ?>">
                            <a href="javascript:void(0);" class="show_more_taxes"><?php echo lang('common_show_more'); ?> &raquo;</a>
                        </div>

                        <div class="more_taxes_container" style="display: <?php echo $this->config->item('default_tax_3_rate') ? 'block' : 'none'; ?>">
                            <div class="form-group">	
                                    <?php echo form_label(lang('config_default_tax_rate_3') . ':', 'default_tax_3_rate', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                                <div class="col-sm-4 col-md-4 col-lg-5">
                                    <?php
                                    echo form_input(array(
                                        'class' => 'form-control form-inps',
                                        'name' => 'default_tax_3_name',
                                        'placeholder' => lang('common_tax_name'),
                                        'id' => 'default_tax_3_name',
                                        'size' => '10',
                                        'value' => $this->config->item('default_tax_3_name') !== FALSE ? $this->config->item('default_tax_3_name') : lang('items_sales_tax_3')));
                                    ?>
                                </div>

                                <div class="col-sm-4 col-md-4 col-lg-5">
                                    <?php
                                    echo form_input(array(
                                        'class' => 'form-control form-inps-tax',
                                        'placeholder' => lang('items_tax_percent'),
                                        'name' => 'default_tax_3_rate',
                                        'id' => 'default_tax_3_rate',
                                        'size' => '4',
                                        'value' => $this->config->item('default_tax_3_rate')));
                                    ?>
                                    <div class="tax-percent-icon">%</div>
                                    <div class="clear"></div>
                                </div>
                            </div>


                            <div class="form-group">	
<?php echo form_label(lang('config_default_tax_rate_4') . ':', 'default_tax_4_rate', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                                <div class="col-sm-4 col-md-4 col-lg-5">
<?php
echo form_input(array(
    'class' => 'form-control form-inps',
    'placeholder' => lang('common_tax_name'),
    'name' => 'default_tax_4_name',
    'id' => 'default_tax_4_name',
    'size' => '10',
    'value' => $this->config->item('default_tax_4_name') !== FALSE ? $this->config->item('default_tax_4_name') : lang('items_sales_tax_4')));
?>
                                </div>

                                <div class="col-sm-4 col-md-4 col-lg-5">
                                    <?php
                                    echo form_input(array(
                                        'class' => 'form-control form-inps-tax',
                                        'placeholder' => lang('items_tax_percent'),
                                        'name' => 'default_tax_4_rate',
                                        'id' => 'default_tax_4_rate',
                                        'size' => '4',
                                        'value' => $this->config->item('default_tax_4_rate')));
                                    ?>
                                    <div class="tax-percent-icon">%</div>
                                    <div class="clear"></div>
                                </div>
                            </div>

                            <div class="form-group">	
<?php echo form_label(lang('config_default_tax_rate_5') . ':', 'default_tax_5_rate', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                                <div class="col-sm-4 col-md-4 col-lg-5">
                        <?php
                        echo form_input(array(
                            'class' => 'form-control form-inps',
                            'placeholder' => lang('common_tax_name'),
                            'name' => 'default_tax_5_name',
                            'id' => 'default_tax_5_name',
                            'size' => '10',
                            'value' => $this->config->item('default_tax_5_name') !== FALSE ? $this->config->item('default_tax_5_name') : lang('items_sales_tax_5')));
                        ?>
                                </div>

                                <div class="col-sm-4 col-md-4 col-lg-5">
                            <?php
                            echo form_input(array(
                                'class' => 'form-control form-inps-tax',
                                'placeholder' => lang('items_tax_percent'),
                                'name' => 'default_tax_5_rate',
                                'id' => 'default_tax_5_rate',
                                'size' => '4',
                                'value' => $this->config->item('default_tax_5_rate')));
                            ?>
                                    <div class="tax-percent-icon">%</div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">	
<?php echo form_label(lang('config_barcode_price_include_tax') . ':', 'barcode_price_include_tax', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'barcode_price_include_tax',
    'id' => 'barcode_price_include_tax',
    'value' => 'barcode_price_include_tax',
    'checked' => $this->config->item('barcode_price_include_tax')));
?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_currency_symbol') . ':', 'currency_symbol', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_input(array(
    'class' => 'form-control form-inps',
    'name' => 'currency_symbol',
    'id' => 'currency_symbol',
    'value' => $this->config->item('currency_symbol')));
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sales & Receipt -->
        <div class="col-md-12">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon">
                        <i class="fa fa-align-justify"></i>									
                    </span>
                    <h5><?php echo lang("config_sales_receipt_info"); ?></h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="form-group">	
                        <?php echo form_label(lang('config_prefix') . ':', 'sale_prefix', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_input(array(
                                'class' => 'form-control form-inps',
                                'name' => 'sale_prefix',
                                'id' => 'sale_prefix',
                                'value' => $this->config->item('sale_prefix')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_id_to_show_on_sale_interface') . ':', 'language', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_dropdown('id_to_show_on_sale_interface', array(
                                'number' => lang('items_item_number'),
                                'product_id' => lang('items_product_id'),
                                'id' => lang('items_item_id')
                                    ), $this->config->item('id_to_show_on_sale_interface'))
                            ?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_print_after_sale') . ':', 'print_after_sale', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'print_after_sale',
                                'id' => 'print_after_sale',
                                'value' => 'print_after_sale',
                                'checked' => $this->config->item('print_after_sale')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_print_after_receiving') . ':', 'print_after_receiving', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'print_after_receiving',
    'id' => 'print_after_receiving',
    'value' => 'print_after_receiving',
    'checked' => $this->config->item('print_after_receiving')));
?>
                        </div>
                    </div>
                    <div class="form-group">	
<?php echo form_label(lang('config_auto_focus_on_item_after_sale_and_receiving') . ':', 'auto_focus_on_item_after_sale_and_receiving', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_checkbox(array(
                            'name' => 'auto_focus_on_item_after_sale_and_receiving',
                            'id' => 'auto_focus_on_item_after_sale_and_receiving',
                            'value' => 'auto_focus_on_item_after_sale_and_receiving',
                            'checked' => $this->config->item('auto_focus_on_item_after_sale_and_receiving')));
                        ?>
                        </div>
                    </div>
                    <div class="form-group">	
                        <?php echo form_label(lang('config_hide_signature') . ':', 'hide_signature', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'hide_signature',
                                'id' => 'hide_signature',
                                'value' => 'hide_signature',
                                'checked' => $this->config->item('hide_signature')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_hide_customer_recent_sales') . ':', 'hide_customer_recent_sales', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'hide_customer_recent_sales',
                                'id' => 'hide_customer_recent_sales',
                                'value' => 'hide_customer_recent_sales',
                                'checked' => $this->config->item('hide_customer_recent_sales')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('disable_confirmation_sale') . ':', 'disable_confirmation_sale', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'disable_confirmation_sale',
                                'id' => 'disable_confirmation_sale',
                                'value' => 'disable_confirmation_sale',
                                'checked' => $this->config->item('disable_confirmation_sale')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">	
<?php echo form_label(lang('config_round_cash_on_sales') . ':', 'round_cash_on_sales', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'round_cash_on_sales',
    'id' => 'round_cash_on_sales',
    'value' => 'round_cash_on_sales',
    'checked' => $this->config->item('round_cash_on_sales')));
?>
                        </div>
                    </div>
                    <div class="form-group">	
<?php echo form_label(lang('config_automatically_email_receipt') . ':', 'automatically_email_receipt', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_checkbox(array(
                            'name' => 'automatically_email_receipt',
                            'id' => 'automatically_email_receipt',
                            'value' => 'automatically_email_receipt',
                            'checked' => $this->config->item('automatically_email_receipt')));
                        ?>
                        </div>
                    </div>
                    <div class="form-group">	
                        <?php echo form_label(lang('config_automatically_show_comments_on_receipt') . ':', 'automatically_show_comments_on_receipt', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'automatically_show_comments_on_receipt',
                                'id' => 'automatically_show_comments_on_receipt',
                                'value' => 'automatically_show_comments_on_receipt',
                                'checked' => $this->config->item('automatically_show_comments_on_receipt')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">	
                        <?php echo form_label(lang('config_automatically_calculate_average_cost_price_from_receivings') . ':', 'calculate_average_cost_price_from_receivings', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'calculate_average_cost_price_from_receivings',
                                'id' => 'calculate_average_cost_price_from_receivings',
                                'value' => '1',
                                'checked' => $this->config->item('calculate_average_cost_price_from_receivings')));
                            ?>
                        </div>
                    </div>

                    <div id="average_cost_price_from_receivings_methods">
                        <div class="form-group">	
                            <?php echo form_label($this->lang->line('config_averaging_method') . ':', 'averaging_method', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
                            <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php echo form_dropdown('averaging_method', array('moving_average' => lang('config_moving_average'), 'historical_average' => lang('config_historical_average')), $this->config->item('averaging_method'), 'class="span2"'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">	
                        <?php echo form_label(lang('config_track_cash') . ':', 'track_cash', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'track_cash',
                                'id' => 'track_cash',
                                'value' => '1',
                                'checked' => $this->config->item('track_cash')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_disable_giftcard_detection') . ':', 'disable_giftcard_detection', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'disable_giftcard_detection',
                                'id' => 'disable_giftcard_detection',
                                'value' => '1',
                                'checked' => $this->config->item('disable_giftcard_detection')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_always_show_item_grid') . ':', 'always_show_item_grid', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'always_show_item_grid',
                                'id' => 'always_show_item_grid',
                                'value' => '1',
                                'checked' => $this->config->item('always_show_item_grid')));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">	
<?php echo form_label(lang('config_hide_barcode_on_sales_and_recv_receipt') . ':', 'hide_barcode_on_sales_and_recv_receipt', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_checkbox(array(
                            'name' => 'hide_barcode_on_sales_and_recv_receipt',
                            'id' => 'hide_barcode_on_sales_and_recv_receipt',
                            'value' => '1',
                            'checked' => $this->config->item('hide_barcode_on_sales_and_recv_receipt')));
                        ?>
                        </div>
                    </div>

                    <div class="form-group">	
<?php echo form_label(lang('config_round_tier_prices_to_2_decimals') . ':', 'round_tier_prices_to_2_decimals', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_checkbox(array(
                            'name' => 'round_tier_prices_to_2_decimals',
                            'id' => 'round_tier_prices_to_2_decimals',
                            'value' => '1',
                            'checked' => $this->config->item('round_tier_prices_to_2_decimals')));
                        ?>
                        </div>
                    </div>

                    <div class="form-group">	
                        <?php echo form_label(lang('config_group_all_taxes_on_receipt') . ':', 'group_all_taxes_on_receipt', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'group_all_taxes_on_receipt',
                                'id' => 'group_all_taxes_on_receipt',
                                'value' => '1',
                                'checked' => $this->config->item('group_all_taxes_on_receipt')));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">	
                            <?php echo form_label(lang('config_require_customer_for_sale') . ':', 'require_customer_for_sale', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'require_customer_for_sale',
    'id' => 'require_customer_for_sale',
    'value' => '1',
    'checked' => $this->config->item('require_customer_for_sale')));
?>
                        </div>
                    </div>


                    <div class="form-group">	
<?php echo form_label(lang('config_payment_types') . ':', 'additional_payment_types', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php echo lang('sales_cash'); ?>, 
<?php echo lang('sales_check'); ?>, 
<?php echo lang('sales_giftcard'); ?>, 
<?php echo lang('sales_debit'); ?>, 
<?php echo lang('sales_credit'); ?>,
<?php
echo form_input(array(
    'class' => 'form-control form-inps',
    'name' => 'additional_payment_types',
    'id' => 'additional_payment_types',
    'size' => 40,
    'value' => $this->config->item('additional_payment_types')));
?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_default_payment_type') . ':', 'default_payment_type', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php echo form_dropdown('default_payment_type', $payment_options, $this->config->item('default_payment_type'), 'class="span2"'); ?>
                        </div>
                    </div>

                    <div class="form-group">	
                        <?php echo form_label(lang('config_receipt_text_size') . ':', 'receipt_text_size', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php echo form_dropdown('receipt_text_size', $receipt_text_size_options, $this->config->item('receipt_text_size'), 'class="span2"'); ?>
                        </div>
                    </div>

                    <div class="form-group">	
<?php echo form_label(lang('config_select_sales_person_during_sale') . ':', 'select_sales_person_during_sale', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'select_sales_person_during_sale',
    'id' => 'select_sales_person_during_sale',
    'value' => '1',
    'checked' => $this->config->item('select_sales_person_during_sale')));
?>
                        </div>
                    </div>

                    <div class="form-group">	
<?php echo form_label(lang('config_default_sales_person') . ':', 'default_sales_person', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php echo form_dropdown('default_sales_person', array('logged_in_employee' => lang('employees_logged_in_employee'), 'not_set' => lang('common_not_set')), $this->config->item('default_sales_person'), 'class="span2"'); ?>
                        </div>
                    </div>

                    <div class="form-group">	
                            <?php echo form_label(lang('config_commission_default_rate') . ' (' . lang('common_commission_help') . '):', 'commission_default_rate', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_input(array(
    'name' => 'commission_default_rate',
    'id' => 'commission_default_rate',
    'value' => $this->config->item('commission_default_rate')));
?>%
                        </div>
                    </div>

                    <div class="form-group">	
<?php echo form_label(lang('config_disable_sale_notifications') . ':', 'disable_sale_notifications', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_checkbox(array(
                            'name' => 'disable_sale_notifications',
                            'id' => 'disable_sale_notifications',
                            'value' => '1',
                            'checked' => $this->config->item('disable_sale_notifications')));
                        ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Suspended Sales/Layaways -->
        <div class="col-md-12">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon">
                        <i class="fa fa-align-justify"></i>									
                    </span>
                    <h5><?php echo lang("config_suspended_sales_layaways_info"); ?></h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="form-group">	
<?php echo form_label(lang('sales_hide_layaways_sales_in_reports') . ':', 'hide_layaways_sales_in_reports', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'hide_layaways_sales_in_reports',
    'id' => 'hide_layaways_sales_in_reports',
    'value' => '1',
    'checked' => $this->config->item('hide_layaways_sales_in_reports')));
?>
                        </div>
                    </div>
                    <div class="form-group">	
<?php echo form_label(lang('config_hide_store_account_payments_in_reports') . ':', 'hide_store_account_payments_in_reports', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_checkbox(array(
                            'name' => 'hide_store_account_payments_in_reports',
                            'id' => 'hide_store_account_payments_in_reports',
                            'value' => '1',
                            'checked' => $this->config->item('hide_store_account_payments_in_reports')));
                        ?>
                        </div>
                    </div>

                    <div class="form-group">	
                            <?php echo form_label(lang('config_hide_store_account_payments_from_report_totals') . ':', 'hide_store_account_payments_from_report_totals', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'hide_store_account_payments_from_report_totals',
                                'id' => 'hide_store_account_payments_from_report_totals',
                                'value' => '1',
                                'checked' => $this->config->item('hide_store_account_payments_from_report_totals')));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">	
                            <?php echo form_label(lang('config_change_sale_date_when_suspending') . ':', 'change_sale_date_when_suspending', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'change_sale_date_when_suspending',
    'id' => 'change_sale_date_when_suspending',
    'value' => '1',
    'checked' => $this->config->item('change_sale_date_when_suspending')));
?>
                        </div>
                    </div>
                    <div class="form-group">	
<?php echo form_label(lang('config_change_sale_date_when_completing_suspended_sale') . ':', 'change_sale_date_when_completing_suspended_sale', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'change_sale_date_when_completing_suspended_sale',
    'id' => 'change_sale_date_when_completing_suspended_sale',
    'value' => '1',
    'checked' => $this->config->item('change_sale_date_when_completing_suspended_sale')));
?>
                        </div>
                    </div>
                    <div class="form-group">	
<?php echo form_label(lang('config_show_receipt_after_suspending_sale') . ':', 'show_receipt_after_suspending_sale', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'show_receipt_after_suspending_sale',
    'id' => 'show_receipt_after_suspending_sale',
    'value' => '1',
    'checked' => $this->config->item('show_receipt_after_suspending_sale')));
?>
                        </div>
                    </div>
                    <div class="form-group">	
                            <?php echo form_label(lang('config_disable_subtraction_of_giftcard_amount_from_sales') . ':', 'disable_subtraction_of_giftcard_amount_from_sales', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <?php
                            echo form_checkbox(array(
                                'name' => 'disable_subtraction_of_giftcard_amount_from_sales',
                                'id' => 'disable_subtraction_of_giftcard_amount_from_sales',
                                'value' => '1',
                                'checked' => $this->config->item('disable_subtraction_of_giftcard_amount_from_sales')));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Application Settings -->
        <div class="col-md-12">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon">
                        <i class="fa fa-align-justify"></i>									
                    </span>
                    <h5><?php echo lang("config_application_settings_info"); ?></h5>

                </div>
                <div class="widget-content nopadding">



                    <div class="form-group">	
<?php echo form_label(lang('config_language') . ':', 'language', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_dropdown('language', array(
                            'khmer' => 'ភាសាខ្មែរ',
                            'english' => 'English',
                            'indonesia' => 'Indonesia',
                            'spanish' => 'Spanish',
                            'french' => 'French',
                            'italian' => 'Italian',
                            'dutch' => 'Dutch',
                            'portugues' => 'Portugues',
                            'arabic' => 'Arabic',
                                ), $this->Appconfig->get_raw_language_value());
                        ?>
                        </div>
                    </div>

                    <div class="form-group">	
                            <?php echo form_label(lang('config_date_format') . ':', 'date_format', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_dropdown('date_format', array(
    'middle_endian' => '12/30/2000',
    'little_endian' => '30-12-2000',
    'big_endian' => '2000-12-30'), $this->config->item('date_format'));
?>
                        </div>
                    </div>

                    <div class="form-group">	
<?php echo form_label(lang('config_time_format') . ':', 'time_format', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_dropdown('time_format', array(
    '12_hour' => '1:00 PM',
    '24_hour' => '13:00'
        ), $this->config->item('time_format'));
?>
                        </div>
                    </div>


                    <div class="form-group">	
                                        <?php echo form_label(lang('config_customers_store_accounts') . ':', 'customers_store_accounts', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'customers_store_accounts',
    'id' => 'customers_store_accounts',
    'value' => 'customers_store_accounts',
    'checked' => $this->config->item('customers_store_accounts')));
?>
                        </div>
                    </div>


                    <div class="form-group">	
<?php echo form_label(lang('config_number_of_items_per_page') . ':', 'number_of_items_per_page', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
        <?php
        echo form_dropdown('number_of_items_per_page', array(
            '20' => '20',
            '50' => '50',
            '100' => '100',
            '200' => '200',
            '500' => '500'
                ), $this->config->item('number_of_items_per_page') ? $this->config->item('number_of_items_per_page') : '20');
        ?>
                        </div>
                    </div>							

                    <div class="form-group">	
<?php echo form_label(lang('config_hide_dashboard_statistics') . ':', 'hide_dashboard_statistics', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'hide_dashboard_statistics',
    'id' => 'hide_dashboard_statistics',
    'value' => '1',
    'checked' => $this->config->item('hide_dashboard_statistics')));
?>
                        </div>
                    </div>


                    <div class="form-group">	
<?php echo form_label(lang('config_legacy_detailed_report_export') . ':', 'legacy_detailed_report_export', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_checkbox(array(
    'name' => 'legacy_detailed_report_export',
    'id' => 'legacy_detailed_report_export',
    'value' => '1',
    'checked' => $this->config->item('legacy_detailed_report_export')));
?>
                        </div>
                    </div>


                    <div class="form-group">	
<?php echo form_label(lang('common_return_policy') . ':', 'return_policy', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label required')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php
echo form_textarea(array(
    'name' => 'return_policy',
    'id' => 'return_policy',
    'class' => 'form-textarea',
    'rows' => '4',
    'cols' => '30',
    'value' => $this->config->item('return_policy')));
?>
                        </div>
                    </div>

                    <div class="form-group">	
<?php echo form_label(lang('config_spreadsheet_format') . ':', 'spreadsheet_format', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
<?php echo form_dropdown('spreadsheet_format', array(lang('config_csv') => lang('config_csv'), lang('config_xlsx') => lang('config_xlsx')), $this->config->item('spreadsheet_format'), 'class="span2"'); ?>
                        </div>
                    </div>


                    <div class="form-group">	
<?php echo form_label(lang('config_price_tiers') . ':', 'tiers', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                        <div class="col-sm-9 col-md-9 col-lg-10">
                            <table id="price_tiers">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('items_tier_name'); ?></th>
                                        <th><?php echo lang('common_delete'); ?></th>
                                    </tr>
                                </thead>

                                <tbody>
<?php foreach ($tiers->result() as $tier) { ?>
                                        <tr><td><input type="text" name="tiers_to_edit[<?php echo $tier->id; ?>]" value="<?php echo H($tier->name); ?>" /></td><td>
    <?php if ($this->Employee->has_module_action_permission('items', 'delete', $this->Employee->get_logged_in_employee_info()->person_id) || $this->Employee->has_module_action_permission('item_kits', 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>				
                                                    <a class="delete_tier" href="javascript:void(0);" data-tier-id='<?php echo $tier->id; ?>'><?php echo lang('common_delete'); ?></a>
    <?php } else { ?>
                                                    &nbsp;
    <?php } ?>
                                            </td><tr>
<?php } ?>
                                </tbody>
                            </table>

                            <a href="javascript:void(0);" id="add_tier"><?php echo lang('config_add_tier'); ?></a>
                        </div>
                    </div>
                    <div class="form-actions">
<?php
echo form_submit(array(
    'name' => 'submitf',
    'id' => 'submitf',
    'value' => lang('common_submit'),
    'class' => 'submit_button btn btn-primary float_right'));
?>
                    </div>				
                </div>
            </div>
        </div>
<?php echo form_close(); ?>	
    </div>
</div>
</div>
<script type='text/javascript'>
//validation and submit handling
    $(document).ready(function()
    {
        $(".delete_tier").click(function()
        {
            $("#config_form").append('<input type="hidden" name="tiers_to_delete[]" value="' + $(this).data('tier-id') + '" />');
            $(this).parent().parent().remove();
        });

        $("#add_tier").click(function()
        {
            $("#price_tiers tbody").append('<tr><td><input type="text" class="tiers_to_add" name="tiers_to_add[]" value="" /></td><td>&nbsp;</td></tr>');
        });

        $(".dbOptimize").click(function(event)
        {
            event.preventDefault();
            $('#spin').removeClass('hidden');

            $.getJSON($(this).attr('href'), function(response)
            {
                $('#spin').addClass('hidden');
                alert(response.message);
            });

        });
        var submitting = false;
        $('#config_form').validate({
            submitHandler: function(form)
            {
                if (submitting)
                    return;
                submitting = true;
                $(form).ajaxSubmit({
                    success: function(response)
                    {
                        //Don't let the tiers be double submitted, so we change the name
                        $(".tiers_to_add").attr('name', 'tiers_added[]');
                        if (response.success)
                        {
                            gritter(<?php echo json_encode(lang('common_success')); ?>, response.message, 'gritter-item-success', false, false);
                        }
                        else
                        {
                            gritter(<?php echo json_encode(lang('common_error')); ?>, response.message, 'gritter-item-error', false, false);
                        }
                        submitting = false;
                    },
                    dataType: 'json'
                });

            },
            errorClass: "text-danger",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('has-error').addClass('has-success');
            },
            rules:
                    {
                        company: "required",
                        sale_prefix: "required",
                        return_policy:
                                {
                                    required: true
                                }
                    },
            messages:
                    {
                        company: <?php echo json_encode(lang('config_company_required')); ?>,
                        sale_prefix: <?php echo json_encode(lang('config_sale_prefix_required')); ?>,
                        return_policy:
                                {
                                    required:<?php echo json_encode(lang('config_return_policy_required')); ?>
                                },
                    }
        });

    });

    $("#calculate_average_cost_price_from_receivings").change(check_calculate_average_cost_price_from_receivings).ready(check_calculate_average_cost_price_from_receivings);

    function check_calculate_average_cost_price_from_receivings()
    {
        if ($("#calculate_average_cost_price_from_receivings").prop('checked'))
        {
            $("#average_cost_price_from_receivings_methods").show();
        }
        else
        {
            $("#average_cost_price_from_receivings_methods").hide();
        }
    }

</script>
<?php $this->load->view("partial/footer"); ?>


