<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
    <h1 > <i class="fa fa-folder-open"></i>  <?php echo lang('pawns_add_attachment');?>
     </h1>
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
                <h5><?php echo lang("account_basic_information"); ?></h5>
            </div>
            <div class="widget-content ">
                <?php echo form_open_multipart(current_url(), array('id' => '', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')); ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <th><?php echo lang('common_order_no')?></th>
                                <th><?php echo lang('common_file_name')?></th>
                                <th><?php echo lang('pawns_option')?></th>
                            </tr>
                            <?php if($attachment->num_rows()>0){ 
                                $i=1;
                                foreach ($attachment->result() as $att_file){ ?>
                                  <tr>
                                      <td><?php echo $i;?></td>
                                      <td> <a target="_blank" href="<?php echo base_url().'pawn_attachment/'.$this->uri->segment(3).'/'.$att_file->file_name;?>"><?php echo $att_file->file_name;?></a></td>
                                      <td>
                                          <i style="cursor: pointer;" id="<?php echo $att_file->id;?>" class="delete text-danger fa fa-trash-o" mdata-toggle="tooltip" data-placement="right" title="<?php echo lang('common_delete');?>"></i>
                                      </td>
                                  </tr>
                            <?php $i++; } }else{ ?>
                               <tr>
                                   <td colspan="3" href="#"><?php echo lang('common_no_file');?></td>
                               </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                             <?php echo form_label(lang('pawns_add_attachment') . ':', 'name', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            <label class="btn btn-success" for="my-file-selector">
                                    <input name="upload[]" multiple="multiple" id="my-file-selector" type="file" accept=".docx, .doc, .pdf, .csv, .xlsx, .xls, image/*" style="display:none;">
                                    <i class="fa fa-folder-open"></i> <?php echo lang('pawns_brow');?>
                             </label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <input class="btn btn-info" type="submit" name="fsubmit" id="fsubmit" value="<?php echo lang('common_submit');?>" />
                        <a href="<?php echo base_url().'pawns/view_schedule/'.$this->uri->segment(3);?>" class="btn btn-danger"><?php echo lang('common_skip'); ?></a>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
    </div>
</div>
<script>
    $('.delete').hover(function(){
        $(this).tooltip('show');
    }); 
    $('.delete').click(function(){
        if(confirm('<?php echo lang("common_delete_file");?>')){
        $.ajax({
            type: "POST",
            url:"pawns/delete_attachment",
            data:{
                ids:$(this).attr('id')
            },
            success: function(msg) {
               gritter(<?php echo json_encode(lang('common_success') . ' ' . lang('common_add')); ?>,<?php echo json_encode(lang('pawns_attachment')); ?>, 'gritter-item-success', false, false); 
               location.reload();
            },
            error: function (error) {
               gritter(<?php echo json_encode(lang('common_error') . ' ' . lang('common_add')); ?>,<?php echo json_encode(lang('pawns_attachment')); ?>, 'gritter-item-error', false, false); 
            }
        });
        }
    });

</script>
<?php $this->load->view("partial/footer"); ?>
