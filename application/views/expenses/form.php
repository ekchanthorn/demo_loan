<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
    <h1 > <i class="fa fa-pencil"></i>  <?php
        if (!$loan_info->person_id) {
            echo lang($controller_name . '_new');
        }
        else {
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
                <h5><?php echo lang("expenses_basic_information"); ?></h5>
            </div>
            <div class="widget-content ">
                <?php echo form_open_multipart('loans/save/' . $loan_info->loan_id, array('id'    => 'loan_form', 'class' => 'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="row">
                    <div class="col-md-12">
                       
                        <div class="form-group">
                            <?php
                            $required = ($controller_name == "suppliers") ? "" : "required";
                            
                            ?>
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <?php
                                echo form_label(lang('common_from') . '*:', 'from', array('class' => ' col-sm-1 col-md-1 col-lg-1 control-label '));
                                echo form_input(array(
                                    'class' => 'form-control',
                                    'name' => 'from',
                                    'id' => 'from',
                                    'value' => $person_info->first_name)
                                );
                                ?>

                            </div>    
                            
                            <div class="col-sm-2 col-md-2 col-lg-2">
                                <?php echo form_label(lang('common_date') . '*:', 'date', array('class' => ' col-sm-1 col-md-1 col-lg-1 control-label '));
                            ?>
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control',
                                    'name' => 'date',
                                    'id' => 'date',
                                    'value' => $person_info->first_name)
                                );
                                ?>

                            </div>    
                           
                            <div class="col-sm-2 col-md-2 col-lg-2">
                                 <?php echo form_label(lang('common_due_date') . ':', 'due_date', array('class' => 'col-sm-2 col-md-2 col-lg-2 control-label'));?>
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control',
                                    'name' => 'due_date',
                                    'id' => 'due_date',
                                    'value' => $person_info->first_name)
                                );
                                ?>

                            </div> 
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <?php echo form_label(lang('common_reference') . ':', 'reference', array('class' => ' col-sm-2 col-md-2 col-lg-2 control-label '));?>
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control',
                                    'name' => 'reference',
                                    'id' => 'reference',
                                    'value' => $person_info->first_name)
                                );
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('loans_amount') . '* :', 'amount', array('class' => ' col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                            <div class="col-sm-3 col-md-3 col-lg-4">
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control form-inps',
                                    'name'  => 'amount',
                                    'id'    => 'amount',
                                    'value' => $loan_info->amount)
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
                                    <i class="fa fa-folder-open"></i> <?php echo lang('loans_brow');?>
                                </label>
                            </div>
                        </div>
                    </div>
                    


                </div>



                <?php echo form_hidden('redirect_code', $redirect_code); ?>
                
                <div class="form-actions">
                    <a class="btn btn-danger" href="<?php echo site_url('loans');?>"><i class="fa fa-arrow-left"></i> <?php echo lang('loans_exit');?></a>
                    <?php
                    echo form_submit(array(
                        'name'  => 'submitf',
                        'id'    => 'submitf',
                        'value' => lang('loans_generate_schedule'),
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
    jQuery('#person_id').select2();
    
    jQuery(function() {
        
        var now = new Date();
        jQuery('#borrow_date').datetimepicker({
            format:'Y/m/d',
            lang:'kh',
            onShow:function(ct){
            this.setOptions({
                maxDate:jQuery('#start_date').val()?jQuery('#start_date').val():false
                })
            },
            timepicker:false
        });
            // end date
        jQuery('#start_date').datetimepicker({
            format:'Y/m/d',
            lang:'kh',
            onShow:function(ct){
            this.setOptions({
                minDate:jQuery('#borrow_date').val()?jQuery('#borrow_date').val():false
                })
            },
            timepicker:false
        });
        jQuery('#end_date').datetimepicker({
               format: 'Y/m/d',
               lang:'kh',
               timepicker: false
        });
    });
</script>

<script type='text/javascript'>
           //validation and submit handling
   $(document).ready(function()
    {
            setTimeout(function(){$(":input:visible:first", "#loan_form").focus(); }, 100);
            var submitting = false;
            $('#loan_form').validate({
    submitHandler:function(form)
    {
    $.post('<?php echo site_url("loans/check_duplicate"); ?>', {term: $('#person_id').val()+' '+$('#borrow_date').val()}, function(data) {
<?php if (!$loan_info->loan_id) { ?>
        if (data.duplicate)
        {
        gritter(<?php echo json_encode(lang('common_error')); ?> + ' <?php echo lang('error_adding'); ?>', "<?php echo lang('loans_duplicate_exists'); ?>", 'gritter-item-error', false, false);
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
            borrow_date:{
                    date:true,
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
    person_id: <?php echo json_encode(lang('loans_customer_required')); ?>,
    amount:{
        required:<?php echo json_encode(lang('loans_amount_required')); ?>,
        number:<?php echo json_encode(lang('loans_number_required')); ?>
    },
    rate:{
        required:<?php echo json_encode(lang('loans_rate_required')); ?>,
        number:<?php echo json_encode(lang('loans_number_required')); ?>
    },
    duration:{
        required:<?php echo json_encode(lang('loans_duration_required')); ?>,
        number:<?php echo json_encode(lang('loans_number_required')); ?>
    },
    borrow_date:{
        required:<?php echo json_encode(lang('loans_borrow_date_required')); ?>,
        date:<?php echo json_encode(lang('loans_date_required')); ?>
    },
    start_date:{
        required:<?php echo json_encode(lang('loans_start_date_required')); ?>,
        date:<?php echo json_encode(lang('loans_date_required')); ?>
    },
    end_date:{
        required:<?php echo json_encode(lang('loans_end_date_required')); ?>,
        date:<?php echo json_encode(lang('loans_date_required')); ?>
    },
    currency:<?php echo json_encode(lang('loans_currency_required')); ?>,
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
                    gritter(response.success ? <?php echo json_encode(lang('common_success')); ?> + ' #' + response.loan_id : <?php echo json_encode(lang('common_error')); ?>, response.message, response.success ? 'gritter-item-success' : 'gritter-item-error', false, false);
                    if (response.redirect_code == 2 && response.success)
                    {
                    window.location.href = '<?php echo site_url('loans'); ?>'
                    }
            },
<?php if (!$loan_info->loan_id) { ?>
                resetForm: true,
<?php } ?>
            dataType:'json'
            });
            }

</script>


<?php 
$one;$two;
    foreach($rate_setting->result() as $row_rate){
        $one=$row_rate->rate_one;
        $two=$row_rate->rate_two;
    }
?>
<script type="text/javascript">
    // When the document is ready
    $(document).ready(function () {
        
        // this event use to handle rate generating 
        $("#deposit,#amount").keyup(function(){
            var amount= parseFloat($('#amount').val());
            var deposit = parseFloat($("#deposit").val());
            if(((deposit*100)/amount)>=20){
                $("#rate").val(<?php echo $one;?>);
            }else{
                $("#rate").val(<?php echo $two;?>);
            }
       });
       // this event use for generate start date and end date
        $("#start_date").blur(function(){
            var start_date = $('#start_date').val();
            var arr = start_date.split("/");
            var borrow_date = $('#borrow_date').val();
            var brr = borrow_date.split("/");
            var duration = $('#duration').val();
            var month = parseFloat(duration)+parseFloat(arr[1]);
            
            if(duration==''){
                $('#end_date').val("<?php echo lang('loans_fill_duration');?>");
            }else if(start_date==''){
                 $('#end_date').val("<?php echo lang('loans_fill_start_date');?>");
            }else if(brr[0]<arr[0]){
                if(month<=12){
                    if(month==1){ month=13;}
                    $('#end_date').val(parseInt(arr[0])+'/'+(month-1)+'/'+arr[2]);
                }else if(month<=24){
                    if(month==13){ month=24;}
                    $('#end_date').val(parseInt(arr[0])+'/'+(month-13)+'/'+arr[2]);
                }else if(month<=36){
                    if(month==25){ month=36;}
                     $('#end_date').val(parseInt(arr[0])+1+'/'+(month-25)+'/'+arr[2]);
                }else if(month<=48){
                     if(month==37){ month=48;}
                     $('#end_date').val(parseInt(arr[0])+2+'/'+(month-37)+'/'+arr[2]);
                }else if(month<=60){
                     if(month==49){ month=60;}
                    $('#end_date').val(parseInt(arr[0])+3+'/'+(month-49)+'/'+arr[2]);
                }else if(month<=72){
                    if(month==61){ month=72;}
                    $('#end_date').val(parseInt(arr[0])+4+'/'+(month-61)+'/'+arr[2]);
                }else{
                    //if(month==73){ month=84;}
                    $('#end_date').val(parseInt(arr[0])+5+'/'+(month-73)+'/'+arr[2]);
                }
            }else{
                if(month<=12){
                    if(month==1){ month=13;}
                    $('#end_date').val(parseInt(arr[0])+'/'+(month-1)+'/'+arr[2]);
                }else if(month<=24){
                    if(month==13){ month=24;}
                    $('#end_date').val(parseInt(arr[0])+1+'/'+(month-13)+'/'+arr[2]);
                }else if(month<=36){
                    if(month==25){ month=36;}
                     $('#end_date').val(parseInt(arr[0])+2+'/'+(month-25)+'/'+arr[2]);
                }else if(month<=48){
                     if(month==37){ month=48;}
                     $('#end_date').val(parseInt(arr[0])+3+'/'+(month-37)+'/'+arr[2]);
                }else if(month<=60){
                     if(month==49){ month=60;}
                    $('#end_date').val(parseInt(arr[0])+4+'/'+(month-49)+'/'+arr[2]);
                }else if(month<=72){
                    if(month==61){ month=72;}
                    $('#end_date').val(parseInt(arr[0])+5+'/'+(month-61)+'/'+arr[2]);
                }else{
                    if(month==73){ month=84;}
                    $('#end_date').val(parseInt(arr[0])+6+'/'+(month-73)+'/'+arr[2]);
                }
            }
          });
          
           $("#duration").keyup(function(){
            var start_date = $('#start_date').val();
            var arr = start_date.split("/");
            var borrow_date = $('#borrow_date').val();
            var brr = borrow_date.split("/");
            var duration = $('#duration').val();
            var month = parseFloat(duration)+parseFloat(arr[1]);
            
            if(duration==''){
                $('#end_date').val("<?php echo lang('loans_fill_duration');?>");
            }else if(start_date==''){
                 $('#end_date').val("<?php echo lang('loans_fill_start_date');?>");
            }else if(brr[0]<arr[0]){
                if(month<=12){
                    if(month==1){ month=13;}
                    $('#end_date').val(parseInt(arr[0])+'/'+(month-1)+'/'+arr[2]);
                }else if(month<=24){
                    if(month==13){ month=24;}
                    $('#end_date').val(parseInt(arr[0])+'/'+(month-13)+'/'+arr[2]);
                }else if(month<=36){
                    if(month==25){ month=36;}
                     $('#end_date').val(parseInt(arr[0])+1+'/'+(month-25)+'/'+arr[2]);
                }else if(month<=48){
                     if(month==37){ month=48;}
                     $('#end_date').val(parseInt(arr[0])+2+'/'+(month-37)+'/'+arr[2]);
                }else if(month<=60){
                     if(month==49){ month=60;}
                    $('#end_date').val(parseInt(arr[0])+3+'/'+(month-49)+'/'+arr[2]);
                }else if(month<=72){
                    if(month==61){ month=72;}
                    $('#end_date').val(parseInt(arr[0])+4+'/'+(month-61)+'/'+arr[2]);
                }else{
                    //if(month==73){ month=84;}
                    $('#end_date').val(parseInt(arr[0])+5+'/'+(month-73)+'/'+arr[2]);
                }
            }else{
                if(month<=12){
                    if(month==1){ month=13;}
                    $('#end_date').val(parseInt(arr[0])+'/'+(month-1)+'/'+arr[2]);
                }else if(month<=24){
                    if(month==13){ month=24;}
                    $('#end_date').val(parseInt(arr[0])+1+'/'+(month-13)+'/'+arr[2]);
                }else if(month<=36){
                    if(month==25){ month=36;}
                     $('#end_date').val(parseInt(arr[0])+2+'/'+(month-25)+'/'+arr[2]);
                }else if(month<=48){
                     if(month==37){ month=48;}
                     $('#end_date').val(parseInt(arr[0])+3+'/'+(month-37)+'/'+arr[2]);
                }else if(month<=60){
                     if(month==49){ month=60;}
                    $('#end_date').val(parseInt(arr[0])+4+'/'+(month-49)+'/'+arr[2]);
                }else if(month<=72){
                    if(month==61){ month=72;}
                    $('#end_date').val(parseInt(arr[0])+5+'/'+(month-61)+'/'+arr[2]);
                }else{
                    if(month==73){ month=84;}
                    $('#end_date').val(parseInt(arr[0])+6+'/'+(month-73)+'/'+arr[2]);
                }
            }
          });
    });
</script> 

<?php $this->load->view("partial/footer"); ?>
