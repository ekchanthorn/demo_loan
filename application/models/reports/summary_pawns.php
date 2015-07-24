<?php

require_once("report.php");

class Summary_pawns extends Report {

    function __construct() {
        parent::__construct();
    }

    public function getDataColumns() {
        $columns = array();

        $columns[] = array('data'  => lang('reports_no'), 'align' => 'left');
        $columns[] = array('data'  => lang('reports_date'), 'align' => 'left');
        $columns[] = array('data'  => lang('pawns_amount'), 'align' => 'right');
        $columns[] = array('data'  => lang('pawns_pay_amount'), 'align' => 'right');
        $columns[] = array('data'  => lang('pawns_amount_due'), 'align' => 'right');
        //$columns[] = array('data'  => lang('pawns_deposit'), 'align' => 'right');
        $columns[] = array('data'  => lang('pawns_rate_no_percent'), 'align' => 'right');
        $columns[] = array('data'  => lang('pawns_pay_rate'), 'align' => 'right');
        $columns[] = array('data'  => lang('pawns_number_of_day_late'), 'align' => 'right');
        $columns[] = array('data'  => lang('pawns_fine'), 'align' => 'right');

        if ($this->Employee->has_module_action_permission('reports', 'show_profit', $this->Employee->get_logged_in_employee_info()->person_id)) {
            $columns[] = array('data'  => lang('reports_profit'), 'align' => 'right');
        }

        return $columns;
    }

    public function getData() {
        $this->db->select('start_date AS start_date, 
                sum(amount) as amount, 
                sum(paid_princ) as paid_princ, 
                (sum(paid_princ)-sum(amount)) as amount_due,
                sum(pay_rate) as rate,
                sum(paid_rate) as paid_rate,
                sum(late) as late,
                sum(pay_fine) as pay_fine,
                sum(profit) as profit', false);
        $this->db->from('pawns_temp');

        $this->db->where('deleted', 0);
        $this->db->group_by('start_date');
        $this->db->order_by('start_date', 'DESC');

        //If we are exporting NOT exporting to excel make sure to use offset and limit
        if (isset($this->params['export_excel']) && !$this->params['export_excel']) {
            $this->db->limit($this->report_limit);
            $this->db->offset($this->params['offset']);
        }

        return $this->db->get()->result_array();
    }

    function getTotalRows() {
        $this->db->select('COUNT(DISTINCT(start_date)) as pawn_count');
        $this->db->from('pawns_temp');

        $this->db->where('deleted', 0);

        $ret = $this->db->get()->row_array();
        return $ret['pawn_count'];
    }

    public function getSummaryData() {
        $this->db->select('sum(amount) as amount, 
                    sum(paid_princ) as paid_princ, 
                    (sum(paid_princ)-sum(amount)) as amount_due,
                    sum(pay_rate) as rate,
                    sum(paid_rate) as paid_rate,
                    sum(pay_fine) as pay_fine,
                    sum(profit) as profit', false);
        $this->db->from('pawns_temp');

        $this->db->where('deleted', 0);

        $this->db->group_by('pawn_id');

        $return = array(
            'amount'     => 0,
            'paid_princ' => 0,
            'amount_due' => 0,
            'rate'       => 0,
            'paid_rate'  => 0,
            'pay_fine'   => 0,
            'profit'     => 0,
        );

        foreach ($this->db->get()->result_array() as $row) {
            $return['amount'] += to_currency_no_money($row['amount'], 2);
            $return['paid_princ'] += to_currency_no_money($row['paid_princ'], 2);
            $return['amount_due'] += to_currency_no_money($row['amount_due'], 2);
            //$return['deposit'] += to_currency_no_money($row['deposit'], 2);
            $return['rate'] += to_currency_no_money($row['rate'], 2);
            $return['paid_rate'] += to_currency_no_money($row['paid_rate'], 2);
            //$return['late'] += $row['late'];
            $return['pay_fine'] += to_currency_no_money($row['pay_fine'], 2);
            $return['profit'] += to_currency_no_money($row['profit'], 2);
        }
        if (!$this->Employee->has_module_action_permission('reports', 'show_profit', $this->Employee->get_logged_in_employee_info()->person_id)) {
            unset($return['profit']);
        }
        return $return;
    }

}

?>