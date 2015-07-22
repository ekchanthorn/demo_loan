<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
    <h1 > <i class="fa fa-desktop"></i>  <?php echo lang('pawns_view_all'); ?>
    </h1>
</div>

<div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>
</div>
<div class="clear"></div>
<div class="col-md-12">
    <div class="widget-box">
        <div class="widget-title">
            <span class="icon">
                <i class="fa fa-align-justify"></i>									
            </span>
            <h5><?php echo lang("pawns_view_all"); ?></h5>
        </div>
        <div class="widget-content ">
            <table class="table table-bordered table-hover table-responsive">
                <tr>
                    <td><?php echo lang('reports_no'); ?></td>
                    <td><?php echo lang('common_fullname'); ?></td>
                    <td><?php echo lang('pawns_print_date'); ?></td>
                    <td><?php echo lang('pawns_late'); ?></td>
                    <td><?php echo lang('pawns_view'); ?></td>
                </tr>
                <?php $i = 1; ?>
                <?php
                if ($pawn_pay_today->num_rows() < 1) {
                    echo "<tr><td colspan='5'><span class='text-center text-warning' >" . lang('common_no_persons_to_display') . "</span></tr>";
                }
                else {
                    ?>
    <?php foreach ($pawn_pay_today->result() as $pawn_row) { ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $pawn_row->first_name . ' ' . $pawn_row->last_name; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($pawn_row->pay_date)); ?></td>
                            <td>
        <?php
        echo date_distant($pawn_row->pay_date, date('Y-m-d'));
        ?>
                            </td>
                            <td><a href="<?php echo base_url() . 'pawns/view_schedule/' . $pawn_row->pawn_id; ?>"><i data-toggle="tooltip" data-placement="top" title="<?php echo lang('pawns_view'); ?>" class="fa fa-expand"></i></a></td>
                        </tr>
        <?php $i++;
    }
} ?>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>js/bootstrap-filestyle.min.js" type="text/javascript"></script>
<?php $this->load->view("partial/footer"); ?>