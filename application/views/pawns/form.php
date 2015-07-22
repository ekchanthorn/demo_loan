<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
    <h1 > <i class="fa fa-pencil"></i>  <?php
        if (!$pawn_info->person_id) {
            echo lang($controller_name . '_new');
        } else {
            echo lang($controller_name . '_update');
        }
        ?>	</h1>
</div>

<div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
<div class="row" id="form">
    <div class="col-md-12">
        <?php echo lang('common_fields_required_message'); ?>
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fa fa-align-justify"></i>									
                </span>
                <h5><?php echo lang("pawns_basic_information"); ?></h5>
            </div>
            <div class="widget-content ">
                <?php echo form_open_multipart('pawns/save/' . $pawn_info->pawn_id, array('id'    => 'pawn_form', 'class' => 'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php
                            echo form_label(lang('pawns_fullname') . '* :', 'person_id', array('class' => ' col-sm-3 col-md-3 col-lg-2 control-label '));
                            ?>
                            <div class="col-sm-3 col-md-3 col-lg-4">
                                <select name="person_id" id="person_id" class="form-inps">
                                    <option value=""><?php echo lang('common_please_select_on');?></option>
                                    <?php foreach ($all_people->result() as $p){ ?>
                                    <option <?php echo $p->person_id==$pawn_info->person_id?"selected='selected'":"";?> value="<?php echo $p->person_id;?>"><?php echo $p->first_name.' '.$p->last_name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php echo form_label(lang('pawns_amount') . '* :', 'amount', array('class' => ' col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                            <div class="col-sm-3 col-md-3 col-lg-4">
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control form-inps',
                                    'name'  => 'amount',
                                    'id'    => 'amount',
                                    'value' => $pawn_info->amount)
                                );
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('pawns_rate') . '* :', 'rate', array('class' => ' col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                            <div class="col-sm-3 col-md-3 col-lg-4">
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control form-inps',
                                    'name'  => 'rate',
                                    'id'    => 'rate',
                                    'value' => $pawn_info->rate)
                                );
                                ?>
                            </div>
                            <?php
                            echo form_label(lang('pawns_duration') . '* :', 'duration', array('class' => ' col-sm-3 col-md-3 col-lg-2 control-label '));
                            ?>
                            <div class="col-sm-3 col-md-3 col-lg-4">
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control form-inps',
                                    'name'  => 'duration',
                                    'id'    => 'duration',
                                    'value' => $pawn_info->duration)
                                );
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('pawns_pay_as_day') . '*:', 'pay_as_day', array('class' => ' col-sm-1 col-md-1 col-lg-1 control-label ')); ?>
                            <div class="col-sm-1 col-md-1 col-lg-1">
                                <input <?php echo $pawn_info->pay_type==1?"checked='true'":''; ?> type="radio" value="1" class="pay_type" name="pay_type" id="pay_as_day"/> 
                            </div>
                            <?php echo form_label(lang('pawns_pay_as_month') . '*:', 'pay_as_month', array('class' => ' col-sm-1 col-md-1 col-lg-1 control-label ')); ?>
                            <div class="col-sm-1 col-md-1 col-lg-1">
                                <input <?php echo $pawn_info->pay_type==2?"checked='true'":''; ?> type="radio" value="2" class="pay_type" name="pay_type" id="pay_as_month"/> 
                            </div>
                            <?php echo form_label(lang('pawns_pay_half_month') . '* :', 'currency', array('class' => 'col-sm-1 col-md-1 col-lg-1 control-label ')); ?>
                            <div class="col-sm-1 col-md-1 col-lg-1">
                                <input <?php echo $pawn_info->pay_type==3?"checked='true'":''; ?> type="radio" value="3" class="pay_type" name="pay_type" id="pay_half_month"/> 
                            </div>
                            <?php echo form_label(lang('pawns_currency') . '* :', 'currency', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                            <div class="col-sm-3 col-md-3 col-lg-4">
                                <?php
                                $currency = array('usd'=>lang('pawns_usd'),'reils'=>lang("pawns_reils"));
                                echo form_dropdown('currency', $currency,$pawn_info->currency,'class="form-control"');
                                ?>
                            </div> 
                        </div>
                        <div class="form-group">	
                            <?php echo form_label(lang('pawns_from') . '* :', 'start_date', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                            <div class="col-sm-3 col-md-3 col-lg-4">
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control form-inps',
                                    'name'  => 'start_date',
                                    'id'    => 'start_date',
                                    'value' => $pawn_info->start_date?date('Y/m/d',strtotime($pawn_info->start_date)):''));
                                ?>
                            </div>
                            <?php echo form_label(lang('pawns_to') . '* :', 'end_date', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                            <div class="col-sm-3 col-md-3 col-lg-4">
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control',
                                    'name'  => 'end_date',
                                    'id'    => 'end_date',
                                    'class' => 'form-inps',
                                    'value' => $pawn_info->end_date?date('Y/m/d',strtotime($pawn_info->end_date)):''));
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label('', '', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                            <div class="col-sm-3 col-md-3 col-lg-4">
                                <input <?php echo $pawn_info->is_loan==1?'checked="checked"':''; ?> value="1" id="is_loan" type="checkbox" name="is_loan"/>  <?php echo lang('pawns_generate_schedule_as_loan');?>
                            </div>
                            <?php echo form_label(lang('pawns_product_name') . ':', 'product_name', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                            <div class="col-sm-3 col-md-3 col-lg-4">
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control',
                                    'name'  => 'product_name',
                                    'id'    => 'product_name',
                                    'class' => 'form-inps',
                                    'value' => $pawn_info->product_name));
                                ?>
                            </div>
                        </div>
                        <div class="form-group">	
                             <?php echo form_label(lang('common_comments') . ':', 'comments', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                            <div class="col-sm-8 col-md-8 col-lg-9">
                                    <?php
                                    echo form_textarea(array(
                                        'name' => 'comments',
                                        'id' => 'comments',
                                        'value' => $pawn_info->comments,
                                        'rows' => '5',
                                        'cols' => '17')
                                    );
                                    ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 col-md-3 col-lg-2 control-label">

                            </div> 
                            <div class="col-sm-8 col-md-8 col-lg-9">
                                <label class="btn btn-success" for="my-file-selector">
                                    <input name="upload[]" multiple="multiple" id="my-file-selector" type="file" accept=".docx, .doc, .pdf, .csv, .xlsx, .xls, image/*" style="display:none;">
                                    <i class="fa fa-folder-open"></i> <?php echo lang('pawns_brow');?>
                                </label>
                            </div>
                        </div>
                    </div>
                    


                </div>



                <?php echo form_hidden('redirect_code', $redirect_code); ?>
                
                <div class="form-actions">
                    <a class="btn btn-danger" href="<?php echo site_url('pawns');?>"><i class="fa fa-arrow-left"></i> <?php echo lang('pawns_exit');?></a>
                    <?php
                    echo form_submit(array(
                        'name'  => 'submitf',
                        'id'    => 'submitf',
                        'value' => lang('pawns_generate_schedule'),
                        'class' => ' btn btn-primary')
                    );
                    ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //disable pay loan process when click on day
    $('#pay_as_day,#pay_half_month').click(function(){
        if($(this).is(':checked')) { $('#is_loan').attr('disabled','disabled');}
       
    
    });
    $('#pay_as_month').click(function(){
        if($(this).is(':checked')) { $('#is_loan').removeAttr('disabled');}
    });
    
    
    //check is the duration not equal 15 
    $('#duration').keyup(function(){
        if($(this).val()!=15){
            $('#pay_half_month').attr('disabled','disabled');
        }else{
            $('#pay_half_month').removeAttr('disabled');
        }
            
    });
    
    jQuery('#person_id').select2();
    
    jQuery(function() {
        
        var now = new Date();
        jQuery('#start_date').datetimepicker({
            format:'Y/m/d',
            lang:'kh',
            onShow:function(ct){
            this.setOptions({
                maxDate:false
                })
            },
            timepicker:false
        });
            // end date
        jQuery('#end_date').datetimepicker({
            format:'Y/m/d',
            lang:'kh',
            onShow:function(ct){
            this.setOptions({
                minDate:jQuery('#start_date').val()?jQuery('#start_date').val():false
                })
            },
            timepicker:false
        });
    });
</script>
<!-- Calculate date of pay -->
<script type="text/javascript">
    // When the document is ready
    $(document).ready(function () {
        //for start date
        $("#start_date").blur(function(){
            var start_date = $(this).val();
            var arr = start_date.split("/");
            var duration = $('#duration').val();
            var i= parseFloat(arr[1]);
            var month = parseFloat(duration)+parseFloat(arr[1]);
            var day= parseFloat(duration)+parseFloat(arr[2]);
            var specify_date = parseFloat(arr[1])+parseFloat(1);
            var specify_month = parseFloat(arr[1])+parseFloat(2);
            if(duration==''){
                $('#end_date').val("<?php echo lang('pawns_fill_duration');?>");
            }else if(start_date==''){
                 $('#end_date').val("<?php echo lang('pawns_fill_start_date');?>");
            }else if($('.pay_type:checked').val()==2){
                if(month<=12){
                    if(month==1){ month=13;}
                    $('#end_date').val(arr[0]+'/'+month+'/'+arr[2]);
                }else if(month<=24){
                    if(month==13){ month=24;}
                    $('#end_date').val(parseInt(arr[0])+1+'/'+(month-12)+'/'+arr[2]);
                }else if(month<36){
                     $('#end_date').val(parseInt(arr[0])+2+'/'+(month-24)+'/'+arr[2]);
                }else if(month<48){
                     $('#end_date').val(parseInt(arr[0])+3+'/'+(month-36)+'/'+arr[2]);
                }else if(month<60){
                    $('#end_date').val(parseInt(arr[0])+4+'/'+(month-48)+'/'+arr[2]);
                }else if(month<72){
                    $('#end_date').val(parseInt(arr[0])+5+'/'+(month-60)+'/'+arr[2]);
                }else{
                    $('#end_date').val(parseInt(arr[0])+6+'/'+(month-72)+'/'+arr[2]);
                }
            }else{
                if(day <= 30 && i==12){
                    $('#end_date').val(parseInt(arr[0])+'/'+i+'/'+day);
                }else if(day > 30 && day <60 && i==12){
                    $('#end_date').val(parseInt(arr[0])+1+'/01/'+(duration-(30-parseInt(arr[2]))));
                }else if(day >60 && day <90 && i==12){
                     $('#end_date').val(parseInt(arr[0])+1+'/02/'+(duration-(60-parseInt(arr[2]))));
                }else if(day >90 && i==12){
                     $('#end_date').val(parseInt(arr[0])+1+'/03/'+(duration-(90-parseInt(arr[2]))));
                }else if(day <= 30 && i<12){
                    $('#end_date').val(parseInt(arr[0])+'/'+i+'/'+day);
                }else if(day <= 28 && i==2){
                    $('#end_date').val(parseInt(arr[0])+'/'+i+'/'+day);
                }else if(day >= 28 && day <58 && i==2){
                    $('#end_date').val(parseInt(arr[0])+'/03/'+(duration-(28-parseInt(arr[2]))));
                }else if(day >58 && day <88 && i==2){
                     $('#end_date').val(parseInt(arr[0])+1+'/04/'+(duration-(58-parseInt(arr[2]))));
                }else if(day >88 && i==2){
                     $('#end_date').val(parseInt(arr[0])+1+'/05/'+(duration-(88-parseInt(arr[2]))));
                }else if(day > 30 && day <=60 && i<12){
                     if((i+1)==13){
                         i=0;
                     }
                $('#end_date').val(parseInt(arr[0])+'/'+(i+1)+'/'+(duration-(30-parseInt(arr[2]))));
                }else if(day >60 && day <90 && i<12){
                    if((i+2)==13){
                         $('#end_date').val(parseInt(arr[0])+'/'+2+'/'+(duration-(90-parseInt(arr[2]))));
                     }else{
                         $('#end_date').val(parseInt(arr[0])+'/'+(i+2)+'/'+(duration-(90-parseInt(arr[2]))));
                     }
                }else if(day >90 && i<12){
                     if((i+3)==13){
                         $('#end_date').val(parseInt(arr[0])+'/'+3+'/'+(duration-(90-parseInt(arr[2]))));
                     }else{
                         $('#end_date').val(parseInt(arr[0])+'/'+(i+3)+'/'+(duration-(90-parseInt(arr[2]))));
                     }
                }
            }
          });
          
          //for pay type
          $(".pay_type").change(function(){
            var start_date = $('#start_date').val();
            var arr = start_date.split("/");
            var duration = $('#duration').val();
            var i= parseFloat(arr[1]);
            var month = parseFloat(duration)+parseFloat(arr[1]);
            var day= parseFloat(duration)+parseFloat(arr[2]);
            var specify_date = parseFloat(arr[1])+parseFloat(1);
            var specify_month = parseFloat(arr[1])+parseFloat(2);
            if(duration==''){
                $('#end_date').val("<?php echo lang('pawns_fill_duration');?>");
            }else if(start_date==''){
                 $('#end_date').val("<?php echo lang('pawns_fill_start_date');?>");
            }else if($('.pay_type:checked').val()==2){
                if(month<=12){
                    if(month==1){ month=13;}
                    $('#end_date').val(arr[0]+'/'+month+'/'+arr[2]);
                }else if(month<=24){
                    if(month==13){ month=24;}
                    $('#end_date').val(parseInt(arr[0])+1+'/'+(month-12)+'/'+arr[2]);
                }else if(month<36){
                     $('#end_date').val(parseInt(arr[0])+2+'/'+(month-24)+'/'+arr[2]);
                }else if(month<48){
                     $('#end_date').val(parseInt(arr[0])+3+'/'+(month-36)+'/'+arr[2]);
                }else if(month<60){
                    $('#end_date').val(parseInt(arr[0])+4+'/'+(month-48)+'/'+arr[2]);
                }else if(month<72){
                    $('#end_date').val(parseInt(arr[0])+5+'/'+(month-60)+'/'+arr[2]);
                }else{
                    $('#end_date').val(parseInt(arr[0])+6+'/'+(month-72)+'/'+arr[2]);
                }
            }else{
                if(day <= 30 && i==12){
                    $('#end_date').val(parseInt(arr[0])+'/'+i+'/'+day);
                }else if(day > 30 && day <60 && i==12){
                    $('#end_date').val(parseInt(arr[0])+1+'/01/'+(duration-(30-parseInt(arr[2]))));
                }else if(day >60 && day <90 && i==12){
                     $('#end_date').val(parseInt(arr[0])+1+'/02/'+(duration-(60-parseInt(arr[2]))));
                }else if(day >90 && i==12){
                     $('#end_date').val(parseInt(arr[0])+1+'/03/'+(duration-(90-parseInt(arr[2]))));
                }else if(day <= 30 && i<12){
                    $('#end_date').val(parseInt(arr[0])+'/'+i+'/'+day);
                }else if(day <= 28 && i==2){
                    $('#end_date').val(parseInt(arr[0])+'/'+i+'/'+day);
                }else if(day >= 28 && day <58 && i==2){
                    $('#end_date').val(parseInt(arr[0])+'/03/'+(duration-(28-parseInt(arr[2]))));
                }else if(day >58 && day <88 && i==2){
                     $('#end_date').val(parseInt(arr[0])+1+'/04/'+(duration-(58-parseInt(arr[2]))));
                }else if(day >88 && i==2){
                     $('#end_date').val(parseInt(arr[0])+1+'/05/'+(duration-(88-parseInt(arr[2]))));
                }else if(day > 30 && day <=60 && i<12){
                     if((i+1)==13){
                         i=0;
                     }
                $('#end_date').val(parseInt(arr[0])+'/'+(i+1)+'/'+(duration-(30-parseInt(arr[2]))));
                }else if(day >60 && day <90 && i<12){
                    if((i+2)==13){
                         $('#end_date').val(parseInt(arr[0])+'/'+2+'/'+(duration-(90-parseInt(arr[2]))));
                     }else{
                         $('#end_date').val(parseInt(arr[0])+'/'+(i+2)+'/'+(duration-(90-parseInt(arr[2]))));
                     }
                }else if(day >90 && i<12){
                     if((i+3)==13){
                         $('#end_date').val(parseInt(arr[0])+'/'+3+'/'+(duration-(90-parseInt(arr[2]))));
                     }else{
                         $('#end_date').val(parseInt(arr[0])+'/'+(i+3)+'/'+(duration-(90-parseInt(arr[2]))));
                     }
                }
            }
          });
          
          //for duration 
          $("#duration").keyup(function(){
            var start_date = $('#start_date').val();
            var arr = start_date.split("/");
            var duration = $('#duration').val();
            var i= parseFloat(arr[1]);
            var month = parseFloat(duration)+parseFloat(arr[1]);
            var day= parseFloat(duration)+parseFloat(arr[2]);
            var specify_date = parseFloat(arr[1])+parseFloat(1);
            var specify_month = parseFloat(arr[1])+parseFloat(2);
            if(duration==''){
                $('#end_date').val("<?php echo lang('pawns_fill_duration');?>");
            }else if(start_date==''){
                 $('#end_date').val("<?php echo lang('pawns_fill_start_date');?>");
            }else if($('.pay_type:checked').val()==2){
                if(month<=12){
                    if(month==1){ month=13;}
                    $('#end_date').val(arr[0]+'/'+month+'/'+arr[2]);
                }else if(month<=24){
                    if(month==13){ month=24;}
                    $('#end_date').val(parseInt(arr[0])+1+'/'+(month-12)+'/'+arr[2]);
                }else if(month<36){
                     $('#end_date').val(parseInt(arr[0])+2+'/'+(month-24)+'/'+arr[2]);
                }else if(month<48){
                     $('#end_date').val(parseInt(arr[0])+3+'/'+(month-36)+'/'+arr[2]);
                }else if(month<60){
                    $('#end_date').val(parseInt(arr[0])+4+'/'+(month-48)+'/'+arr[2]);
                }else if(month<72){
                    $('#end_date').val(parseInt(arr[0])+5+'/'+(month-60)+'/'+arr[2]);
                }else{
                    $('#end_date').val(parseInt(arr[0])+6+'/'+(month-72)+'/'+arr[2]);
                }
            }else{
                if(day <= 30 && i==12){
                    $('#end_date').val(parseInt(arr[0])+'/'+i+'/'+day);
                }else if(day > 30 && day <60 && i==12){
                    $('#end_date').val(parseInt(arr[0])+1+'/01/'+(duration-(30-parseInt(arr[2]))));
                }else if(day >60 && day <90 && i==12){
                     $('#end_date').val(parseInt(arr[0])+1+'/02/'+(duration-(60-parseInt(arr[2]))));
                }else if(day >90 && i==12){
                     $('#end_date').val(parseInt(arr[0])+1+'/03/'+(duration-(90-parseInt(arr[2]))));
                }else if(day <= 30 && i<12){
                    $('#end_date').val(parseInt(arr[0])+'/'+i+'/'+day);
                }else if(day <= 28 && i==2){
                    $('#end_date').val(parseInt(arr[0])+'/'+i+'/'+day);
                }else if(day >= 28 && day <58 && i==2){
                    $('#end_date').val(parseInt(arr[0])+'/03/'+(duration-(28-parseInt(arr[2]))));
                }else if(day >58 && day <88 && i==2){
                     $('#end_date').val(parseInt(arr[0])+1+'/04/'+(duration-(58-parseInt(arr[2]))));
                }else if(day >88 && i==2){
                     $('#end_date').val(parseInt(arr[0])+1+'/05/'+(duration-(88-parseInt(arr[2]))));
                }else if(day > 30 && day <=60 && i<12){
                     if((i+1)==13){
                         i=0;
                     }
                $('#end_date').val(parseInt(arr[0])+'/'+(i+1)+'/'+(duration-(30-parseInt(arr[2]))));
                }else if(day >60 && day <90 && i<12){
                    if((i+2)==13){
                         $('#end_date').val(parseInt(arr[0])+'/'+2+'/'+(duration-(90-parseInt(arr[2]))));
                     }else{
                         $('#end_date').val(parseInt(arr[0])+'/'+(i+2)+'/'+(duration-(90-parseInt(arr[2]))));
                     }
                }else if(day >90 && i<12){
                     if((i+3)==13){
                         $('#end_date').val(parseInt(arr[0])+'/'+3+'/'+(duration-(90-parseInt(arr[2]))));
                     }else{
                         $('#end_date').val(parseInt(arr[0])+'/'+(i+3)+'/'+(duration-(90-parseInt(arr[2]))));
                     }
                }
            }
          }); 

    });
</script> 
<!-- end Calculate date of pay -->
<script type='text/javascript'>
           //validation and submit handling
   $(document).ready(function()
    {
            setTimeout(function(){$(":input:visible:first", "#pawn_form").focus(); }, 100);
            var submitting = false;
            $('#pawn_form').validate({
    submitHandler:function(form)
    {
    $.post('<?php echo site_url("pawns/check_duplicate"); ?>', {term: $('#person_id').val()+' '+$('#start_date').val()}, function(data) {
<?php if (!$pawn_info->pawn_id) { ?>
        if (data.duplicate)
        {
        gritter(<?php echo json_encode(lang('common_error')); ?> + ' <?php echo lang('error_adding'); ?>', "<?php echo lang('pawns_duplicate_exists'); ?>", 'gritter-item-error', false, false);
                return false;
        }
<?php
}
else
    
    ?>
    {
    doCustomerSubmit(form);
    }}, "json")
            .error(function() {
    });
    },
            rules:
    {
            person_id: "required",
            amount:{
                required:true,
                number:true
            },
            rate:{
                required:true,
                number:true
            },
            duration:{
                    number:true,
                    required:true
            },
            end_date:{
                    date:true,
                    required:true
            },
            start_date:{
                    date:true,
                    required:true
            },
                    currency:"required"
    },
            errorClass: "text-danger",
            errorElement: "span",
            highlight:function(element, errorClass, validClass) {
    $(element).parents('.form-group').removeClass('has-success').addClass('has-error');
    },
            unhighlight: function(element, errorClass, validClass) {
    $(element).parents('.form-group').removeClass('has-error').addClass('has-success');
    },
            messages:
    {
    person_id: <?php echo json_encode(lang('pawns_customer_required')); ?>,
    amount:{
        required:<?php echo json_encode(lang('pawns_amount_required')); ?>,
        number:<?php echo json_encode(lang('pawns_number_required')); ?>
    },
    rate:{
        required:<?php echo json_encode(lang('pawns_rate_required')); ?>,
        number:<?php echo json_encode(lang('pawns_number_required')); ?>
    },
    duration:{
        required:<?php echo json_encode(lang('pawns_duration_required')); ?>,
        number:<?php echo json_encode(lang('pawns_number_required')); ?>
    },
    start_date:{
        required:<?php echo json_encode(lang('pawns_start_date_required')); ?>,
        date:<?php echo json_encode(lang('pawns_date_required')); ?>
    },
    end_date:{
        required:<?php echo json_encode(lang('pawns_end_date_required')); ?>,
        date:<?php echo json_encode(lang('pawns_date_required')); ?>
    },
    currency:<?php echo json_encode(lang('pawns_currency_required')); ?>,
    }
    });
    });
            var submitting = false;
            function doCustomerSubmit(form)
            {
            $("#form").mask(<?php echo json_encode(lang('common_wait')); ?>);
                    if (submitting) return;
                    submitting = true;
                    $(form).ajaxSubmit({
            success:function(response)
            {
            $("#form").unmask();
                    submitting = false;
                    gritter(response.success ? <?php echo json_encode(lang('common_success')); ?> + ' #' + response.pawn_id : <?php echo json_encode(lang('common_error')); ?>, response.message, response.success ? 'gritter-item-success' : 'gritter-item-error', false, false);
                    if (response.redirect_code == 2 && response.success)
                    {
                    window.location.href = '<?php echo site_url('pawns'); ?>'
                    }
            },
<?php if (!$pawn_info->pawn_id) { ?>
                resetForm: true,
<?php } ?>
            dataType:'json'
            });
            }

</script>
<?php $this->load->view("partial/footer"); ?>
