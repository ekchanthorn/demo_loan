<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
    <h1 > <i class="fa fa-calendar"></i>  <?php echo lang("loans_view_schedule").'-> '.$loan_info->first_name.' '.$loan_info->last_name;?></h1>
</div>

<div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
<div class=" pull-right">
    <div class="row">
        <div class="col-md-12 center" style="text-align: center;">					
            <div class="btn-group  ">
                <a class="btn btn-primary email email_inactive" title="<?php echo lang("loans_new"); ?>" id="email" href="<?php echo base_url().'loans/view/-1';?>" >
                    <i title="<?php echo lang('loans_new'); ?>" class="fa fa-plus tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg"><?php echo lang('loans_new'); ?></span>
                </a>
<!--                <a class="btn btn-primary email email_inactive" title="<?php echo lang("common_excel_export"); ?>" id="email" href="<?php echo base_url().'loans/export_schedule/'.$loan_info->id;?>" >
                    <i title="<?php echo lang('common_excel_export'); ?>" class="fa fa-edit tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg"><?php echo lang('common_excel_export'); ?></span>
                </a>-->
                <a class="btn btn-primary email email_inactive" title="<?php echo lang("loans_add_attachment"); ?>" id="email" href="<?php echo base_url().'loans/add_attachment/'.$loan_info->loan_id;?>" >
                    <i title="<?php echo lang('loans_add_attachment'); ?>" class="fa fa-folder-o tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg"><?php echo lang('loans_add_attachment'); ?></span>
                </a>
                <div class="btn-group">
                    <button title="<?php echo lang('loans_attachment'); ?>" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                        <i title="<?php echo lang('loans_attachment'); ?>" class="fa fa-folder-open tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg"><?php echo $attachment->num_rows().' '.lang('loans_attachment'); ?> <i class="caret"></i></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                       <?php if($attachment->num_rows()>0){ 
                       foreach ($attachment->result() as $att_file){ ?>
                         <li><a target="_blank" href="<?php echo base_url().'borrow_attachment/'.$loan_info->loan_id.'/'.$att_file->file_name;?>"><?php echo substr($att_file->file_name, 0, 20) . ' ...';?></a></li>
                      <?php } }else{ ?>
                         <li><a><?php echo lang('common_no_file');?></a></li>
                      <?php } ?>
                    </ul>
                </div>
                <a onclick="return PrintElem('#printEl');" class="btn btn-primary email email_inactive" title="<?php echo lang("common_print"); ?>" id="print" href="<?php echo current_url();?>" >
                    <i title="<?php echo lang('common_print'); ?>" class="fa fa-print tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg"><?php echo lang('common_print'); ?></span>
                </a>
                <a class="btn btn-primary email email_inactive" title="<?php echo lang("common_edit"); ?>" id="email" href="<?php echo base_url().'loans/view/'.$loan_info->loan_id;?>" >
                    <i title="<?php echo lang('common_edit'); ?>" class="fa fa-edit tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg"><?php echo lang('common_edit'); ?></span>
                </a>
                <a class="btn btn-danger email email_inactive" title="<?php echo lang("common_delete"); ?>" id="email" href="<?php echo base_url().'loans/delete/'.$loan_info->loan_id;?>" >
                    <i title="<?php echo lang('common_delete'); ?>" class="fa fa-trash-o tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg"><?php echo lang('common_delete'); ?></span>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row" id="form">
    <div class="col-md-12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fa fa-calendar"></i>									
                </span>
                <h5><?php echo lang("loans_view_schedule"); ?></h5>
            </div>
            <div class="row">
                <div class="col-md-5" style="line-height:30px;"><?php echo lang('loans_fullname');?>: <b><?php echo $loan_info->first_name.' '.$loan_info->last_name;?></b></div>
                <div class="col-md-5" style="line-height:30px;"><?php echo lang('loans_amount');?>: <b><?php echo $loan_info->currency=='usd'?to_currency($loan_info->amount):  to_riel_currency($loan_info->amount);?></b></div>
                <div class="col-md-5" style="line-height:30px;"><?php echo lang('loans_deposit');?>: <b><?php echo $loan_info->currency=='usd'?to_currency($loan_info->deposit):  to_riel_currency($loan_info->deposit);?></b></div>
                <div class="col-md-5" style="line-height:30px;"><?php echo lang('loans_borrow_date');?>: <b><?php echo lang('common_'.date('D',strtotime($loan_info->borrow_date))).', '.date('d',strtotime($loan_info->borrow_date)).'-'.lang('common_'.date('M',strtotime($loan_info->borrow_date))).'-'.date('Y',strtotime($loan_info->borrow_date));?></b></div>
                <div class="col-md-5" style="line-height:30px;"><?php echo lang('loans_rate');?>: <b><?php echo $loan_info->rate;?></b>%</div>
                <div class="col-md-5" style="line-height:30px;"><?php echo lang('loans_duration');?>: <b><?php echo $loan_info->duration.' '.lang('loans_months');?></b></div>
                <div class="col-md-5" style="line-height:30px;"><?php echo lang('loans_from');?>: <b><?php echo lang('common_'.date('D',strtotime($loan_info->start_date))).', '.date('d',strtotime($loan_info->start_date)).'-'.lang('common_'.date('M',strtotime($loan_info->start_date))).'-'.date('Y',strtotime($loan_info->start_date));?></b></div>
                <div class="col-md-5" style="line-height:30px;"><?php echo lang('loans_to');?>: <b><?php echo lang('common_'.date('D',strtotime($loan_info->end_date))).', '.date('d',strtotime($loan_info->end_date)).'-'.lang('common_'.date('M',strtotime($loan_info->end_date))).'-'.date('Y',strtotime($loan_info->end_date));?></b></div>
                <div class="col-md-5" style="line-height:30px;"><?php echo lang('loans_currency');?>: <b><?php echo lang('loans_'.$loan_info->currency);?></b></div>
                <div class="col-md-5" style="line-height:30px;"><?php echo lang('loans_product_name');?>: <b><?php echo $loan_info->product_name;?></b></div>
            </div>
            <br/>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                        <th><?php echo lang('common_order_no');?></th>
                        <th><?php echo lang('loans_pay_date');?></th>
                        <th><?php echo lang('loans_number_of_day');?></th>
                        <th><?php echo lang('loans_principle');?></th>
                        <th><?php echo lang('loans_rate');?></th>
                        <th><?php echo lang('loans_total');?></th>
                        <th><?php echo lang('loans_balance');?></th>
                        <th><?php echo lang('loans_late');?></th>
                        <th><?php echo lang('loans_fine');?></th>
                        <th><?php echo lang('loans_status');?></th>
                        <th><?php echo lang('loans_loans_left');?></th>
                        <th><?php echo lang('common_comments');?></th>
                        </tr>
                    </thead>
                        
                    <tbody>
                    <?php 
                    $this->load->model('Loan');
                    $i=0;
                    $j=0;
                    $total_principle =0;
                    $total_rate =0;
                    //this for calculate total result
                    foreach($schedule->result() as $df){
                      if($j!=0){
                        $total_rate +=  $df->pay_rate; 
                        $total_principle +=  $df->pay_principle; 
                      }
                      $j++;
                    }
                    foreach($schedule->result() as $detail_info){?>
                       <tr>
                         <td><?php echo $i;?></td>  
                         <td><?php echo lang('common_'.date('D',strtotime($detail_info->pay_date))).', '.date('d',strtotime($detail_info->pay_date)).'-'.lang('common_'.date('M',strtotime($detail_info->pay_date))).'-'.date('Y',strtotime($detail_info->pay_date));?></td>  
                         <td><?php echo $detail_info->number_day;?></td>  
                         <td><?php echo $loan_info->currency=='usd'?to_currency($detail_info->pay_principle):to_riel_currency($detail_info->pay_principle);?></td>  
                         <td><?php echo $loan_info->currency=='usd'?to_currency($detail_info->pay_rate):to_riel_currency($detail_info->pay_rate);?></td>
                         <td <?php if($detail_info->status==2){echo 'class="alert-success"';} ?>>
                             <?php 
                             if($detail_info->status!=2){
                                echo $loan_info->currency=='usd'?to_currency($detail_info->pay_principle+$detail_info->pay_rate+$detail_info->pay_fine):to_riel_currency($detail_info->pay_principle+$detail_info->pay_rate+$detail_info->pay_fine);
                             }else{
                                echo $loan_info->currency=='usd'?to_currency($detail_info->pay_total+$detail_info->pay_fine):to_riel_currency($detail_info->pay_total+$detail_info->pay_fine);
                             }?></td>
                         <td><?php echo $loan_info->currency=='usd'?to_currency($detail_info->pay_balance):to_riel_currency($detail_info->pay_balance<0?0:$detail_info->pay_balance);?></td>
                         <td>
                             <?php 
                             $checked = "selected='selected'";
                             if($i!=0){?>
                             <a style="cursor: pointer;" data-html='true' data-container="body" data-toggle="popover" data-placement="left" data-content="
                                <select id='<?php echo $detail_info->id;?>' class='late_rank form-control'>
                                    <option value='0'><?php echo '0 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==1?$checked:''; ?> value='1'><?php echo '1 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==2?$checked:''; ?> value='2'><?php echo '2 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==3?$checked:''; ?> value='3'><?php echo '3 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==4?$checked:''; ?> value='4'><?php echo '4 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==5?$checked:''; ?> value='5'><?php echo '5 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==6?$checked:''; ?> value='6'><?php echo '6 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==7?$checked:''; ?> value='7'><?php echo '7 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==8?$checked:''; ?> value='8'><?php echo '8 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==9?$checked:''; ?> value='9'><?php echo '9 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==10?$checked:''; ?> value='10'><?php echo '10 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==11?$checked:''; ?> value='11'><?php echo '11 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==12?$checked:''; ?> value='12'><?php echo '12 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==13?$checked:''; ?> value='13'><?php echo '13 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==14?$checked:''; ?>value='14'><?php echo '14 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==15?$checked:''; ?> value='15'><?php echo '15 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==16?$checked:''; ?> value='16'><?php echo '16 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==17?$checked:''; ?> value='17'><?php echo '17 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==18?$checked:''; ?> value='18'><?php echo '18 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==19?$checked:''; ?> value='19'><?php echo '19 '.lang('common_day');?></option>
                                    <option <?php echo $detail_info->late==20?$checked:''; ?> value='20'><?php echo '20 '.lang('common_day');?></option>
                                </select>" data-original-title="<?php echo lang('loans_number_of_day_late');?>" title="<i class='fa fa-calendar'></i> <?php echo lang('loans_number_of_day_late');?>"><?php echo $detail_info->late>0?$detail_info->late.' '.lang('common_day'):lang('loans_show');?></a>
                             <?php }?>
                         </td>
                         <td><?php if($i!=0){ 
                             echo $loan_info->currency=='usd'?to_currency($detail_info->pay_fine<0?0:$detail_info->pay_fine):to_riel_currency($detail_info->pay_fine<0?0:$detail_info->pay_fine);
                         } ?>
                         </td>
                         <td>
                             <?php if($i!=0){ ?>
                             <?php if($this->Loan->check_is_pay(($detail_info->id-1))==1 or $i==1){?>
                             <select row='<?php echo $i;?>' pay_total="<?php echo $detail_info->pay_total;?>" class="status" order ="<?php echo $detail_info->key;?>" loan_id="<?php echo $loan_info->loan_id;?>" id="<?php echo $detail_info->id;?>">
                                 <option value='0'><?php echo lang('loans_not_yet_pay');?></option>
                                 <option <?php echo $detail_info->status==1?'selected="selected"':'';?> value='1'><?php echo lang('loans_paid');?></option>
                                 <option <?php echo $detail_info->status==2?'selected="selected"':'';?> value='2'><?php echo lang('loans_pay_all');?></option>
                             </select>
                             <?php } }?>
                         </td>
                         <td>
                             <?php if($i==0){?>
                                <?php echo $loan_info->currency=='usd'?to_currency($total_rate+$total_principle):to_riel_currency($total_rate+$total_principle);?>
                                <input loan_left='<?php echo 'row_'.$i;?>' type='hidden' value='<?php echo $total_rate+$total_principle;?>'/>
                             <?php }else{ ?>
                                <?php echo $loan_info->currency=='usd'?to_currency($detail_info->pay_left):to_riel_currency($detail_info->pay_left);?>
                                <input loan_left='<?php echo 'row_'.$i;?>' type='hidden' value='<?php echo $detail_info->pay_left;?>'/>
                             <?php } ?>
                         </td>
                         <td><a style="cursor: pointer;" data-html='true' data-container="body" data-toggle="popover" data-placement="left" data-content="<textarea id='cmd_<?php echo $detail_info->id;?>'><?php echo $detail_info->note; ?></textarea><br><button id='<?php echo $detail_info->id;?>' class='my_cmd_btn pull-right btn btn-sm btn-success'><?php echo lang('common_submit');?></button>" data-original-title="<?php echo lang('loans_some_cmd');?>" title="<i class='fa fa-book'></i> <?php echo lang('loans_some_cmd');?>"><?php echo lang('loans_show');?></a></td>
                       </tr>
                    <?php $i++;
                    }?>
                    <tr>
                        <td colspan='12'></td>
                    </tr>
                    <tr>
                        <td colspan='3'><?php echo lang('common_total');?></td>
                        <td><?php echo $loan_info->currency=='usd'?to_currency($total_principle):to_riel_currency($total_principle);?></td>
                        <td><?php echo $loan_info->currency=='usd'?to_currency($total_rate):to_riel_currency($total_rate);?></td>
                        <td><?php echo $loan_info->currency=='usd'?to_currency($total_rate+$total_principle):to_riel_currency($total_rate+$total_principle);?></td>
                        <td colspan='6'></td>
                    </tr>
                    </tbody>
                    
                </table>
           </div>
    </div>
    <!-- printing area -->    
    <div id="printEl" class="widget-content nopadding table_holder table-responsive" >
           <body>
               <center><img src="<?php echo $this->Appconfig->get_logo_image();?>" width="100"/></center>
          <div id="background"><?php echo $notify;?></div>
            <center><i><?php echo $location->address;?><br/><?php echo $location->phone;?>, <?php echo $location->email;?></i></center><br/><hr style="border:1px dashed #ddd;"/>
           <center><h2><u><?php echo lang("loans_schedule"); ?></u></h2></center>
           <div class="row">
               <div class="col-md-5" style="line-height:25px"><?php echo lang('loans_fullname');?>: <b><?php echo $loan_info->first_name.' '.$loan_info->last_name;?></b></div>
               <div class="col-md-5" style="line-height:25px"><?php echo lang('loans_amount');?>: <b><?php echo $loan_info->currency=='usd'?to_currency($loan_info->amount):  to_riel_currency($loan_info->amount);?></b></div>
               <div class="col-md-5" style="line-height:25px"><?php echo lang('loans_rate');?>: <b><?php echo $loan_info->rate;?></b>%</div>
               <div class="col-md-5" style="line-height:25px"><?php echo lang('loans_duration');?>: <b><?php echo $loan_info->duration.' '.lang('loans_months');?></b></div>
               <div class="col-md-5" style="line-height:25px"><?php echo lang('loans_from');?>: <b><?php echo lang('common_'.date('D',strtotime($loan_info->start_date))).', '.date('d',strtotime($loan_info->start_date)).'-'.lang('common_'.date('M',strtotime($loan_info->start_date))).'-'.date('Y',strtotime($loan_info->start_date));?></b></div>
               <div class="col-md-5" style="line-height:25px"><?php echo lang('loans_to');?>: <b><?php echo lang('common_'.date('D',strtotime($loan_info->end_date))).', '.date('d',strtotime($loan_info->end_date)).'-'.lang('common_'.date('M',strtotime($loan_info->end_date))).'-'.date('Y',strtotime($loan_info->end_date));?></b></div>
               <div class="col-md-5" style="line-height:25px"><?php echo lang('loans_currency');?>: <b><?php echo lang('loans_'.$loan_info->currency);?></b></div>
               <div class="col-md-5" style="line-height:25px"><?php echo lang('loans_product_name');?>: <b><?php echo $loan_info->product_name;?></b></div>
           </div>

           <table cellspacing="0" class="tablesorter table table-bordered  table-hover" id="sortable_table">
               <tr>
                   <th><?php echo lang('common_order_no');?></th>
                   <th><?php echo lang('loans_pay_date');?></th>
                   <th><?php echo lang('loans_principle');?></th>
                   <th><?php echo lang('loans_rate');?></th>
                   <th><?php echo lang('loans_total');?></th>
                   <th><?php echo lang('loans_balance');?></th>
               </tr>
               <?php
               $i = 0;
               $sum_principle=0;
               $sum_rate=0;
               $sum_total=0;
               foreach ($schedule->result() as $detail_info) {
                   ?>
                   <tr>
                       <td><?php echo $i;?></td>
                       <td><?php echo lang('common_'.date('D',strtotime($detail_info->pay_date))).', '.date('d',strtotime($detail_info->pay_date)).'-'.lang('common_'.date('M',strtotime($detail_info->pay_date))).'-'.date('Y',strtotime($detail_info->pay_date)); ?></td>
                       <td><?php echo $loan_info->currency=='usd'?to_currency($detail_info->pay_principle):to_riel_currency($detail_info->pay_principle); ?></td>
                       <td><?php echo $loan_info->currency=='usd'?to_currency($detail_info->pay_rate):to_riel_currency($detail_info->pay_rate); ?></td>

                       <td><?php 
                             if($detail_info->status!=2){
                                echo $loan_info->currency=='usd'?to_currency($detail_info->pay_principle+$detail_info->pay_rate+$detail_info->pay_fine):to_riel_currency($detail_info->pay_principle+$detail_info->pay_rate+$detail_info->pay_fine);
                             }else{
                                echo $loan_info->currency=='usd'?to_currency($detail_info->pay_total+$detail_info->pay_fine):to_riel_currency($detail_info->pay_total+$detail_info->pay_fine);
                             }?></td>
                       <td><?php echo $loan_info->currency=='usd'?to_currency($detail_info->pay_balance):to_riel_currency($detail_info->pay_balance<0?0:$detail_info->pay_balance); ?></td>
                   </tr>

                   <?php
                   $i++;
                   if($i==1){

                   }else{
                        $sum_principle+=$detail_info->pay_principle;
                        $sum_total+=$detail_info->pay_total;
                   }
                        $sum_rate+=$detail_info->pay_rate;
               }
               ?>
             <tr>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
             </tr>
             <tr>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
             </tr>
             <tr>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
             </tr>
             <tr>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
             </tr>
             <tr>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
                 <td><?php echo "<br>";?></td>
             </tr>
             <tr>
                 <td colspan="12"></td>
             </tr>
             <tr>
                 <td colspan="2"><?php echo lang('common_total');?></td>
                 <td><?php echo $loan_info->currency=='usd'?to_currency($sum_principle):  to_riel_currency($sum_principle); ?></td>
                 <td><?php echo $loan_info->currency=='usd'?to_currency($sum_rate):  to_riel_currency($sum_rate);?></td>
                 <td><?php echo $loan_info->currency=='usd'?to_currency($sum_total):  to_riel_currency($sum_total);?></td>
                 <td></td>
             </tr>
           </table>
           <div class="row">
               <br/>
               <div class="col-md-4"><center><b><?php echo lang('common_party_a');?></b><br/><?php echo lang('common_signatur_a');?></center></div>
               <div class="col-md-4"><center><b><?php echo lang('common_party_b');?></b><br/><?php echo lang('common_signatur_b');?></center></div>
           </div>
    </div>
</div>
    <script type="text/javascript">
        $(document).ready(function(){
            //hide show popover of common
            $('[data-toggle="popover"]').popover();

            $('body').on('click', function (e) {
                $('[data-toggle="popover"]').each(function () {
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        $(this).popover('hide');
                    }
                });
            });
            // ajax for submit data to common
            $(document).on('click','.my_cmd_btn',function(){
                var comment = $('#cmd_'+$(this).attr('id')).val();
                var id = $(this).attr('id');
                $('.table').mask('<?php echo lang('common_wait');?>');
                $.ajax({
                    type:"POST",
                    data:{
                        id:id,
                        comment:comment
                    },url:"loans/add_comment",
                    success:function(result){
                        location.reload();
                    },error:function(result){
                         $('.table').unmask(); 
                        gritter(<?php echo json_encode(lang('common_error').' '.lang('common_add')); ?>,<?php echo json_encode(lang('loans_add_comment')); ?>, 'gritter-item-error', false, false);
                    }
                });
            });
            
            //late tricker 
            $(document).on('change','.late_rank',function(){
              var num_day = $(this).val();
              var id = $(this).attr('id');
               $('.table').mask('<?php echo lang('common_wait');?>');
              $.ajax({
                    type:"POST",
                    data:{
                        id:id,
                        num_day:num_day
                    },url:"loans/add_late",
                    success:function(result){
                        location.reload();
                    },error:function(result){
                         $('.table').unmask(); 
                        gritter(<?php echo json_encode(lang('common_error').' '.lang('common_add')); ?>,<?php echo json_encode(lang('loans_add_comment')); ?>, 'gritter-item-error', false, false);
                    }
                });
            });
        
        $(".status").change(function(){
            var popup = confirm('<?php echo lang("loans_status_change");?>');
            if(popup==1){
              $('.table').mask('<?php echo lang('common_wait');?>');
              var id = $(this).attr('id');
              var order = $(this).attr('order');
              var loan_id = $(this).attr('loan_id');
              var row = $(this).attr('row')-1;
              var pay_total = $(this).attr('pay_total');
              var pay_left = $('[loan_left="row_'+row+'"]').val();
              var status = $(this).val();
              var set_pay = pay_left;
              $.ajax({
                    url: "loans/status",
                    type: "POST",
                    data: ({
                       id:id,
                       set_pay:set_pay,
                       status: status,
                       order: order,
                       loan_id: loan_id
                    }),
                    dataType: "html",
                    async: true,
                    success: function(msg) {
                        gritter(<?php echo json_encode(lang('common_success') . ' ' . lang('common_add')); ?>,<?php echo json_encode(lang('loans_change_status')); ?>, 'gritter-item-success', false, false); 
                        location.reload();
                        //alert(msg);
                    },
                    error: function (error) {
                       $('.table').unmask(); 
                       gritter(<?php echo json_encode(lang('common_error') . ' ' . lang('common_add')); ?>,<?php echo json_encode(lang('loans_change_status')); ?>, 'gritter-item-error', false, false); 
                    }
                });
        }
        });
        
        
    });
    function PrintElem(elem) {
            Popup($(elem).html());
    }

    function Popup(data) {
        var mywindow = window.open('', 'new div', 'height=400,width=800');
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url();?>css/font-awesome.min.css" type="text/css" media="print"/>');
        mywindow.document.write('<style>*{font-size:12px;} .col-md-4{width: 30%; display:inline; float:left; padding-left:10%;} .col-md-5{width: 40%; display:inline; float:left; padding-left:10%;} th,b,h2{color:blue;} table{width:100%;} table{border-bottom:1px solid #000!important;border-right:1px solid #000!important;} th{border-top:1px solid #000!important;border-left:1px solid #000!important; padding:5px;} td{border-top:1px solid #000!important;border-left:1px solid #000!important; padding:5px; text-align:right;} body{overflow:hidden;} #background{position:fixed;top:0;left:0;width:95%;height:95%;overflow:hidden;z-index:-1; color:red !important;}</style>');
        mywindow.document.write('</head>');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.print();
        mywindow.close();
        return false;
    }
 </script>
<script src="<?php echo base_url();?>js/bootstrap-filestyle.min.js" type="text/javascript"></script>
<?php $this->load->view("partial/footer"); ?>