<?php

class Loan extends Person {
    /*
      Determines if a given person_id is a customer
     */

    function exists($person_id) {
        $this->db->from('customers');
        $this->db->join('people', 'people.person_id = customers.person_id');
        $this->db->where('customers.person_id', $person_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function account_number_exists($account_number) {
        $this->db->from('customers');
        $this->db->where('account_number', $account_number);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function customer_id_from_account_number($account_number) {
        $this->db->from('customers');
        $this->db->where('account_number', $account_number);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row()->person_id;
        }

        return false;
    }
    
    //    this function use for display all customer in dashboard 
    
    function get_pay_now_dashbord($limit){
        // $this->db->select()
        $this->db->from('loan');
        $this->db->join('loan_schedule','loan.id = loan_schedule.loan_id');
        $this->db->join('people','loan.person_id = people.person_id');
        $this->db->where('loan_schedule.pay_date <=',date("Y-m-d"));
        $this->db->where('loan_schedule.status',0);
        $this->db->where('loan_schedule.key >',0);
        $this->db->order_by('loan_schedule.pay_date','DESC');
        if($limit!=0){
          $this->db->limit($limit);  
        }
        return $this->db->get();
    }
    //count all customer in dashboard
    function get_count_all_pay_now_dashbord(){
        $this->db->from('loan');
        $this->db->join('loan_schedule','loan.id = loan_schedule.loan_id');
        $this->db->join('people','loan.person_id = people.person_id');
        $this->db->where('loan_schedule.pay_date <=',date("Y-m-d"));
        $this->db->where('loan_schedule.status',0);
        $this->db->where('loan_schedule.key >',0);
        $this->db->order_by('loan_schedule.pay_date','DESC');
        return $this->db->get()->num_rows();
    }

    /*
      Returns all the customers
     */

    function get_all($limit = 10000, $offset = 0, $col = 'id', $order = 'desc') {
        $people    = $this->db->dbprefix('people');
        $customers = $this->db->dbprefix('customers');
        $loans = $this->db->dbprefix('loan');
        $data      = $this->db->query("SELECT ".$people.".first_name as first_name, ".$people.".person_id as person_id,
            ".$people.".last_name as last_name, ".$people.".phone_number as phone_number,
            ".$loans.".id as loan_id, ".$loans.".amount as amount, ".$loans.".deposit as deposit,
            ".$loans.".rate as rate, ".$loans.".duration as duration, 
            ".$loans.".borrow_date as borrow_date, ".$loans.".currency as currency,
            ".$loans.".comments as comments, ".$loans.".start_date as start_date,".$loans.".end_date as end_date,
            ".$loans.".product_name as product_name
            FROM " . $people . "
            INNER JOIN " . $customers . " ON 										                       
            " . $people . ".person_id = " . $customers . ".person_id
            INNER JOIN " . $loans . " ON 										                       
            " . $people . ".person_id = " . $loans . ".person_id
            WHERE ".$loans.".deleted =0 ORDER BY " . $col . " " . $order . " 
            LIMIT  " . $offset . "," . $limit);

        return $data;
    }

    function count_all() {
        $this->db->from('loan');
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }

    /*
      Gets information about a particular customer
     */

    function get_info($loan_id) {
        $loans = $this->db->dbprefix('loan');
        $people = $this->db->dbprefix('people');
        $customers = $this->db->dbprefix('customers');
        $this->db->select("".$people.".first_name as first_name, ".$people.".person_id as person_id,
            ".$people.".last_name as last_name, ".$people.".phone_number as phone_number,
            ".$loans.".id as loan_id, ".$loans.".amount as amount, ".$loans.".deposit as deposit,
            ".$loans.".rate as rate, ".$loans.".duration as duration, 
            ".$loans.".borrow_date as borrow_date, ".$loans.".currency as currency,
            ".$loans.".comments as comments, ".$loans.".start_date as start_date,".$loans.".end_date as end_date,
            ".$loans.".product_name as product_name");
        $this->db->from('customers');
        $this->db->join('people', 'people.person_id = customers.person_id');
        $this->db->join('loan', 'loan.person_id = customers.person_id');
        $this->db->where('loan.id', $loan_id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        }
        else {
            //Get empty base parent object, as $customer_id is NOT an customer
            $person_obj = parent::get_info(-1);

            //Get all the fields from customer table
            $fields = $this->db->list_fields('customers');

            //append those fields to base parent object, we we have a complete empty object
            foreach ($fields as $field) {
                $person_obj->$field = '';
            }

            return $person_obj;
        }
    }

    /*
      Gets information about multiple customers
     */

    function get_multiple_info($customer_ids) {
        $this->db->from('customers');
        $this->db->join('people', 'people.person_id = customers.person_id');
        $this->db->where_in('customers.person_id', $customer_ids);
        $this->db->order_by("last_name", "asc");
        return $this->db->get();
    }

    /*
      Inserts or updates a customer
     */

    function save($data) {
        $success = $this->db->insert('loan', $data);
        $loan_id = $this->db->insert_id();
        
        $startdate             = $data['start_date'];
        $enddate               = $data['end_date'];
        $rate                  = $data['rate'];
        $amount                = $data['amount'];
        $data_schedule['loan_id']       = $loan_id;
        $data_schedule['pay_total']     = $amount;
        $data_schedule['pay_balance']   = $amount;
        $data_schedule['pay_date'];
        $data_schedule['number_day'];
        $data_schedule['pay_rate'];
        $data_schedule['status']    = 0;
        $duration              = $data['duration'];
        $year_borrow           = date('Y', strtotime($data['borrow_date']));
        $month_borrow          = date('m', strtotime($data['borrow_date']));
        $day_borrow            = date('d', strtotime($data['borrow_date']));

        $year_int  = date('Y', strtotime($startdate));
        $month_int = date('m', strtotime($startdate));
        $day_int   = date('d', strtotime($startdate));
        
        for ($i = 0; $i <= $duration; $i++) {
            $data_schedule['key'] = $i;
            if ($i == 0) {
                $data_schedule['pay_date']      = $data['borrow_date'];
                $data_schedule['pay_principle'] = $amount;
            }else {
                $data_schedule['status']    = 0;
                $data_schedule['pay_principle'] = $amount / $duration;
                if ($i == 1) {
                    if ($month_borrow == $month_int) {
                        $data_schedule['number_day']   = $day_int - $day_borrow;
                        $data_schedule['pay_rate'] = ((($rate / 100) * $amount) / 30) * $data_schedule['number_day'];
                    }else {
                        if ($month_borrow == 2) {
                            $data_schedule['number_day']   = (28 - $day_borrow) + $day_int;
                            $data_schedule['pay_rate'] = ((($rate / 100) * $amount) / 28) * $data_schedule['number_day'];
                        }elseif ($month_borrow == 9 or $month_borrow == 6 or $month_borrow == 4 or $month_borrow == 11) {
                            $data_schedule['number_day']   = (30 - $day_borrow) + $day_int;
                            $data_schedule['pay_rate'] = ((($rate / 100) * $amount) / 30) * $data_schedule['number_day'];
                        }else {
                            $data_schedule['number_day']   = (31 - $day_borrow) + $day_int;
                            $data_schedule['pay_rate'] = ((($rate / 100) * $amount) / 31) * $data_schedule['number_day'];
                        }
                    }
                }else {
                    $data_schedule['number_day']   = 30;
                    $data_schedule['pay_rate'] = ($rate / 100) * $amount;
                }

                $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                if ($month_int % 2 == 0) {
                    if ($month_int == 2) {
                        if ($day_int >= 29) {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-28';
                        }
                        else {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                        }
                    }else {
                        if ($day_int >= 30) {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-30';
                        }
                        else {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                        }
                    }
                }else {
                    if ($month_int == 9 or $month_int == 11) {
                        if ($day_int >= 31) {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-30';
                        }
                        else {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                        }
                    }else {
                        if ($day_int >= 31) {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-31';
                        }
                        else {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                        }
                    }
                }
                if ($month_int == 12) {
                    $year_int++;
                    $month_int = 0;
                }
                $month_int++;
                $data_schedule['pay_total'] = $data_schedule['pay_rate'] + $data_schedule['pay_principle'];
                $data_schedule['pay_balance'] -= $data_schedule['pay_principle'];
            }
            $this->db->insert('loan_schedule', $data_schedule);
        }

        return $loan_id;
    }

    /*
      Deletes one customer
     */

    function delete($loan_id) {
//        $customer_info = $this->Customer->get_info($customer_id);
//
//        if ($customer_info->image_id !== NULL) {
//            $this->Person->update_image(NULL, $customer_id);
//            $this->Appfile->delete($customer_info->image_id);
//        }

        $this->db->where('id', $loan_id);
        return $this->db->update('loan', array('deleted' => 1));
    }

    /*
      Deletes a list of customers
     */

    function delete_list($loan_id) {
//        foreach ($customer_ids as $customer_id) {
//            $customer_info = $this->Customer->get_info($customer_id);
//
//            if ($customer_info->image_id !== NULL) {
//                $this->Person->update_image(NULL, $customer_id);
//                $this->Appfile->delete($customer_info->image_id);
//            }
//        }

        $this->db->where_in('id', $loan_id);
        return $this->db->update('loan', array('deleted' => 1));
    }

    function check_duplicate($term) {
        $this->db->from('loan');
        $this->db->where('deleted', 0);
        $this->db->where("CONCAT(person_id, ' ', DATE_FORMAT(borrow_date,'%Y-%m-%d')) LIKE '%" . $term . "%'", NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        }

        //return false;
    }

    /*
      Get search suggestions to find customers
     */

    function get_search_suggestions($search, $limit = 25) {
        $suggestions = array();
        $loans = $this->db->dbprefix('loan');
        $customers = $this->db->dbprefix('customers');
        $people = $this->db->dbprefix('people');
        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('loan', 'loan.person_id = people.person_id');

        $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		CONCAT(`last_name`,', ',`first_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and ".$loans.".deleted=0");

        $this->db->limit($limit);
        $by_name = $this->db->get();

        $temp_suggestions = array();
        foreach ($by_name->result() as $row) {
            $temp_suggestions[] = $row->last_name . ', ' . $row->first_name;
        }

        sort($temp_suggestions);
        foreach ($temp_suggestions as $temp_suggestion) {
            $suggestions[] = array('label' => $temp_suggestion);
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('loan', 'loan.person_id = people.person_id');
        $this->db->where('loan.deleted', 0);
        $this->db->like("borrow_date", $search);
        $this->db->limit($limit);
        $by_email = $this->db->get();

        $temp_suggestions = array();
        foreach ($by_email->result() as $row) {
            $temp_suggestions[] = date('d-m-Y',strtotime($row->borrow_date));
        }

        sort($temp_suggestions);
        foreach ($temp_suggestions as $temp_suggestion) {
            $suggestions[] = array('label' => $temp_suggestion);
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('loan', 'loan.person_id = people.person_id');
        $this->db->where('loan.deleted', 0);
        $this->db->like("phone_number", $search);
        $this->db->limit($limit);
        $by_phone = $this->db->get();

        $temp_suggestions = array();
        foreach ($by_phone->result() as $row) {
            $temp_suggestions[] = $row->phone_number;
        }

        sort($temp_suggestions);
        foreach ($temp_suggestions as $temp_suggestion) {
            $suggestions[] = array('label' => $temp_suggestion);
        }


        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('loan', 'loan.person_id = people.person_id');
        $this->db->where('loan.deleted', 0);
        $this->db->like("amount", $search);
        $this->db->limit($limit);
        $amount = $this->db->get();

        $temp_suggestions = array();
        foreach ($amount->result() as $row) {
            $temp_suggestions[] = $row->amount;
        }

        sort($temp_suggestions);
        foreach ($temp_suggestions as $temp_suggestion) {
            $suggestions[] = array('label' => $temp_suggestion);
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('loan', 'loan.person_id = people.person_id');
        $this->db->where('loan.deleted', 0);
        $this->db->like("product_name", $search);
        $this->db->limit($limit);
        $product_name = $this->db->get();

        $temp_suggestions = array();
        foreach ($product_name->result() as $row) {
            $temp_suggestions[] = $row->product_name;
        }

        sort($temp_suggestions);
        foreach ($temp_suggestions as $temp_suggestion) {
            $suggestions[] = array('label' => $temp_suggestion);
        }

        //only return $limit suggestions
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }

    /*
      Get search suggestions to find customers
     */

    function get_customer_search_suggestions($search, $limit = 25) {
        $suggestions = array();

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');

        $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%' or
		CONCAT(`last_name`,', ',`first_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");

        $this->db->limit($limit);
        $by_name = $this->db->get();

        $temp_suggestions = array();

        foreach ($by_name->result() as $row) {
            $temp_suggestions[$row->person_id] = $row->last_name . ', ' . $row->first_name;
        }

        asort($temp_suggestions);

        foreach ($temp_suggestions as $key => $value) {
            $suggestions[] = array('value' => $key, 'label' => $value);
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where('deleted', 0);
        $this->db->like("account_number", $search);
        $this->db->limit($limit);
        $by_account_number = $this->db->get();


        $temp_suggestions = array();

        foreach ($by_account_number->result() as $row) {
            $temp_suggestions[$row->person_id] = $row->account_number;
        }

        asort($temp_suggestions);

        foreach ($temp_suggestions as $key => $value) {
            $suggestions[] = array('value' => $key, 'label' => $value);
        }


        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where('deleted', 0);
        $this->db->like("email", $search);
        $this->db->limit($limit);
        $by_email = $this->db->get();

        $temp_suggestions = array();

        foreach ($by_email->result() as $row) {
            $temp_suggestions[$row->person_id] = $row->email;
        }

        asort($temp_suggestions);

        foreach ($temp_suggestions as $key => $value) {
            $suggestions[] = array('value' => $key, 'label' => $value);
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where('deleted', 0);
        $this->db->like("phone_number", $search);
        $this->db->limit($limit);
        $by_phone_number = $this->db->get();


        $temp_suggestions = array();

        foreach ($by_phone_number->result() as $row) {
            $temp_suggestions[$row->person_id] = $row->phone_number;
        }

        asort($temp_suggestions);

        foreach ($temp_suggestions as $key => $value) {
            $suggestions[] = array('value' => $key, 'label' => $value);
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where('deleted', 0);
        $this->db->like("company_name", $search);
        $this->db->limit($limit);
        $by_company_name = $this->db->get();

        $temp_suggestions = array();

        foreach ($by_company_name->result() as $row) {
            $temp_suggestions[$row->person_id] = $row->company_name;
        }

        asort($temp_suggestions);

        foreach ($temp_suggestions as $key => $value) {
            $suggestions[] = array('value' => $key, 'label' => $value);
        }

        for ($k = count($suggestions) - 1; $k >= 0; $k--) {
            if (!$suggestions[$k]['label']) {
                unset($suggestions[$k]);
            }
        }

        $suggestions = array_values($suggestions);


        //only return $limit suggestions
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }

    /*
      Preform a search on customers
     */

    function search($search, $limit = 20, $offset = 0, $column = 'loan_id', $orderby = 'desc') {
        $loans = $this->db->dbprefix('loan');
        $people = $this->db->dbprefix('people');
        $customers = $this->db->dbprefix('customers');
        $this->db->select("".$people.".first_name as first_name, ".$people.".person_id as person_id,
            ".$people.".last_name as last_name, ".$people.".phone_number as phone_number,
            ".$loans.".id as loan_id, ".$loans.".amount as amount,
            ".$loans.".rate as rate, ".$loans.".duration as duration, 
            ".$loans.".borrow_date as borrow_date, ".$loans.".currency as currency");
        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('loan', 'loan.person_id=people.person_id');
        $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			email LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			phone_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			account_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			amount LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			DATE_FORMAT(borrow_date,'%d-%m-%Y') LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			product_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`last_name`,', ',`first_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and ".$loans.".deleted=0");
        $this->db->order_by($column, $orderby);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    function search_count_all($search, $limit = 10000) {
        $loans = $this->db->dbprefix('loan');
        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('loan', 'loan.person_id=people.person_id');
        $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			email LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			phone_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			account_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			amount LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			DATE_FORMAT(borrow_date,'%d-%m-%Y') LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			product_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`last_name`,', ',`first_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and ".$loans.".deleted=0");
        //$this->db->order_by($column, $orderby);
        $this->db->limit($limit);
        $result = $this->db->get();
        return $result->num_rows();
    }

    function cleanup() {
        $customer_data_schedule = array('account_number' => null);
        $this->db->where('deleted', 1);
        return $this->db->update('loans', $customer_data);
    }
    
    
    function get_pay_all_before_the_end($loan_id){
        $this->db->where('loan_id',$loan_id);
        $this->db->where('status',2);
        return $this->db->get('loan_schedule');
    }
    
    function get_pay_all_at_the_end($loan_id){
        $this->db->where('loan_id',$loan_id);
        $this->db->where('status',1);
        return $this->db->get('loan_schedule');
    }
//================================================================        
    //function for get all rate setting 
    function get_rate_setting() {
        return $this->db->get('laon_setting');
    }

    //get all customer to view in loan
    function get_all_people() {
        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where('customers.deleted', 0);
        return $this->db->get();
    }
    
    function insert_attament($data){
        $this->db->insert('product_loan_attachment',$data);
    }
    //this function use to update loan
    function update_loan($loan_id,$data){
        $this->db->where('id',$loan_id);
        return $this->db->update('loan',$data);
    }
    
    function update_loan_and_schedule($loan_id,$data){
        $this->db->where('id',$loan_id);
        $update_loan = $this->db->update('loan',$data);
        
        if($update_loan){
           $this->db->where('loan_id',$loan_id); 
           $this->db->delete('loan_schedule');
        }
        
        $startdate             = $data['start_date'];
        $enddate               = $data['end_date'];
        $rate                  = $data['rate'];
        $amount                = $data['amount'];
        $data_schedule['loan_id']       = $loan_id;
        $data_schedule['pay_total']     = $amount;
        $data_schedule['pay_balance']   = $amount;
        $data_schedule['pay_date'];
        $data_schedule['number_day'];
        $data_schedule['pay_rate'];
        $data_schedule['status']    = 0;
        $duration              = $data['duration'];
        $year_borrow           = date('Y', strtotime($data['borrow_date']));
        $month_borrow          = date('m', strtotime($data['borrow_date']));
        $day_borrow            = date('d', strtotime($data['borrow_date']));

        $year_int  = date('Y', strtotime($startdate));
        $month_int = date('m', strtotime($startdate));
        $day_int   = date('d', strtotime($startdate));
        
        for ($i = 0; $i <= $duration; $i++) {
            $data_schedule['key'] = $i;
            if ($i == 0) {
                $data_schedule['pay_date']      = $data['borrow_date'];
                $data_schedule['pay_principle'] = $amount;
            }else {
                $data_schedule['status']    = 0;
                $data_schedule['pay_principle'] = $amount / $duration;
                if ($i == 1) {
                    if ($month_borrow == $month_int) {
                        $data_schedule['number_day']   = $day_int - $day_borrow;
                        $data_schedule['pay_rate'] = ((($rate / 100) * $amount) / 30) * $data_schedule['number_day'];
                    }else {
                        if ($month_borrow == 2) {
                            $data_schedule['number_day']   = (28 - $day_borrow) + $day_int;
                            $data_schedule['pay_rate'] = ((($rate / 100) * $amount) / 28) * $data_schedule['number_day'];
                        }elseif ($month_borrow == 9 or $month_borrow == 6 or $month_borrow == 4 or $month_borrow == 11) {
                            $data_schedule['number_day']   = (30 - $day_borrow) + $day_int;
                            $data_schedule['pay_rate'] = ((($rate / 100) * $amount) / 30) * $data_schedule['number_day'];
                        }else {
                            $data_schedule['number_day']   = (31 - $day_borrow) + $day_int;
                            $data_schedule['pay_rate'] = ((($rate / 100) * $amount) / 31) * $data_schedule['number_day'];
                        }
                    }
                }else {
                    $data_schedule['number_day']   = 30;
                    $data_schedule['pay_rate'] = ($rate / 100) * $amount;
                }

                $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                if ($month_int % 2 == 0) {
                    if ($month_int == 2) {
                        if ($day_int >= 29) {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-28';
                        }
                        else {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                        }
                    }else {
                        if ($day_int >= 30) {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-30';
                        }
                        else {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                        }
                    }
                }else {
                    if ($month_int == 9 or $month_int == 11) {
                        if ($day_int >= 31) {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-30';
                        }
                        else {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                        }
                    }else {
                        if ($day_int >= 31) {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-31';
                        }
                        else {
                            $data_schedule['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                        }
                    }
                }
                if ($month_int == 12) {
                    $year_int++;
                    $month_int = 0;
                }
                $month_int++;
                $data_schedule['pay_total'] = $data_schedule['pay_rate'] + $data_schedule['pay_principle'];
                $data_schedule['pay_balance'] -= $data_schedule['pay_principle'];
            }
            $this->db->insert('loan_schedule', $data_schedule);
        }

        return $loan_id;
    }
    
    function get_schedule($loan_id){
        $this->db->from('loan_schedule');
        //$this->db->join('loan','loan.id = loan_schedule.loan_id');
        $this->db->where('loan_schedule.loan_id',$loan_id);
        //$this->db->where('deleted',0);
        return $this->db->get();
    }
    
    function get_attachment($loan_id){
        $this->db->where('loan_id',$loan_id);
        $this->db->where('deleted',0);
        return $this->db->get('product_loan_attachment');
    }
    
    function add_comment($id,$data){
        $this->db->where('id',$id);
        return $this->db->update('loan_schedule',array('note'=>$data));
    }
    
    function add_late($id, $number_day, $pay_fine){
        $this->db->where('id',$id);
        return $this->db->update('loan_schedule',array('late'=>$number_day,'pay_fine'=>$pay_fine));
    }
    //check is paid
    function check_is_pay($id){
        $this->db->where('id',$id);
        $this->db->where('status',1);
        return $this->db->get('loan_schedule')->num_rows();
    }
    //update status
    function update_paid($id,$data){
        $this->db->where('id',$id);
       return $this->db->update('loan_schedule',$data);
    }
    
    function get_sum_interest($loan_id,$limit){
        $this->db->select('sum(pay_rate) as sum_rate');
        $this->db->where('loan_id',$loan_id);
        $this->db->where('key <=',$limit);
        $i = $this->db->get('loan_schedule');
        return $i->row()->sum_rate;
    }
    function get_sum_interest_paid($loan_id,$limit){
        $this->db->select('sum(pay_total) as sum_paid_total, sum(pay_rate) as sum_paid_rate');
        $this->db->where('loan_id',$loan_id);
        $this->db->where('key <=',$limit);
        $this->db->where('status',1);
        $i = $this->db->get('loan_schedule');
        return $i->row();
    }
    //get_loan_detail 
    function get_detail_as_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('loan_schedule')->row();
    }
    
    //update not pay 
    function update_not_pay($id,$loan_id) {
        $this->db->where('id >', $id);
        $this->db->where('loan_id', $loan_id);
        $data['paid_princ']= 0;
        $data['paid_rate']= 0;
        $data['status']= 0;
        $data['pay_left']= 0;
        $this->db->update('loan_schedule', $data);
    }
    
    //update paid all 
    function update_pay_all($id,$loan_id) {
        $this->db->where('id >', $id);
        $this->db->where('loan_id', $loan_id);
        $data['paid_princ']= 0;
        $data['paid_rate']= 0;
        $data['status']= 0;
        $data['pay_left']= 0;
        $data['pay_total']= 0;
        $this->db->update('loan_schedule', $data);
    }
    
    function get_sum_principle_new($loan_id){
        $this->db->select('sum(pay_principle) as pay_principle');
        $this->db->where('loan_id',$loan_id);
        $this->db->where('status',0);
        $this->db->where('key >',0);
        $i = $this->db->get('loan_schedule');
        return $i->row();
    }
    
    //We create a temp table that allows us to do easy report/Loans queries
    public function create_loans_temp_table($params){
            //$location_id = $this->Employee->get_logged_in_employee_current_location_id();		
            $where = '';
            if(isset($params['start_date']) && isset($params['end_date']))
            {
                 $where = 'WHERE '.$this->db->dbprefix('loan').'.borrow_date BETWEEN '.$this->db->escape($params['start_date']).' and '.$this->db->escape($params['end_date']).'';
            }

            $return = $this->_create_loans_temp_table_query($where);		
            return $return;
    }
	
	function _create_loans_temp_table_query($where)
	{
		set_time_limit(0);
		$query = $this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('loans_temp')."
		(SELECT ".$this->db->dbprefix('loan').".deleted as deleted, 
                ".$this->db->dbprefix('loan').".borrow_date as borrow_date, 
                ".$this->db->dbprefix('loan').".comments as comments,
                ".$this->db->dbprefix('people').".person_id as person_id,
                concat(".$this->db->dbprefix('people').".first_name,' ',".$this->db->dbprefix('people').".last_name) as borrower,
                ".$this->db->dbprefix('loan').".id as loan_id,
		SUM(".$this->db->dbprefix('loan_schedule').".pay_principle) as pay_principle,
		SUM(".$this->db->dbprefix('loan_schedule').".paid_princ) as paid_princ,
                ".$this->db->dbprefix('loan').".deposit as deposit,
                SUM(".$this->db->dbprefix('loan_schedule').".pay_rate) as pay_rate,
                SUM(".$this->db->dbprefix('loan_schedule').".paid_rate) as paid_rate,
                SUM(".$this->db->dbprefix('loan_schedule').".late) as late,
                SUM(".$this->db->dbprefix('loan_schedule').".pay_fine) as pay_fine,
                SUM(".$this->db->dbprefix('loan_schedule').".pay_fine)+SUM(".$this->db->dbprefix('loan_schedule').".paid_rate) as profit
		FROM ".$this->db->dbprefix('loan')."
		INNER JOIN ".$this->db->dbprefix('loan_schedule')." ON  ".$this->db->dbprefix('loan').'.id = '.$this->db->dbprefix('loan_schedule').'.loan_id'."
		INNER JOIN ".$this->db->dbprefix('people')." ON  ".$this->db->dbprefix('loan').'.person_id = '.$this->db->dbprefix('people').'.person_id'."
		INNER JOIN ".$this->db->dbprefix('customers')." ON  ".$this->db->dbprefix('customers').'.person_id='.$this->db->dbprefix('loan').'.person_id'."
		$where  AND ".$this->db->dbprefix('loan_schedule').".key > 0 GROUP BY ".$this->db->dbprefix('loan').".id)");
                return $query;
	}
        
        function get_all_api(){
            return $this->db->get('loan');
        }

}

?>
