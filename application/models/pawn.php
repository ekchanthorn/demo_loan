<?php

class Pawn extends Person {
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
        
        $this->db->from('pawn');
        $this->db->join('pawn_schedule','pawn.id = pawn_schedule.pawn_id');
        $this->db->join('people','pawn.person_id = people.person_id');
        $this->db->where('pawn_schedule.pay_date <=',date("Y-m-d"));
        $this->db->where('pawn_schedule.status',0);
        //$this->db->where('pawn_schedule.key >',0);
        $this->db->order_by('pawn_schedule.pay_date','DESC');
        if($limit!=0){
          $this->db->limit($limit);  
        }
        return $this->db->get();
    }
    //count all customer in dashboard
    function get_count_all_pay_now_dashbord(){
        $this->db->from('pawn');
        $this->db->join('pawn_schedule','pawn.id = pawn_schedule.pawn_id');
        $this->db->join('people','pawn.person_id = people.person_id');
        $this->db->where('pawn_schedule.pay_date <=',date("Y-m-d"));
        $this->db->where('pawn_schedule.status',0);
        $this->db->where('pawn_schedule.key >',0);
        $this->db->order_by('pawn_schedule.pay_date','DESC');
        return $this->db->get()->num_rows();
    }

    /*
      Returns all the customers
     */

    function get_all($limit = 10000, $offset = 0, $col = 'pawn_id', $order = 'desc') {
        $people    = $this->db->dbprefix('people');
        $customers = $this->db->dbprefix('customers');
        $pawns = $this->db->dbprefix('pawn');
        $data      = $this->db->query("SELECT ".$people.".first_name as first_name, ".$people.".person_id as person_id,
            ".$people.".last_name as last_name, ".$people.".phone_number as phone_number,
            ".$pawns.".id as pawn_id, ".$pawns.".amount as amount,
            ".$pawns.".rate as rate, ".$pawns.".duration as duration, 
            ".$pawns.".currency as currency, ".$pawns.".is_loan as is_loan, ".$pawns.".pay_type as pay_type,
            ".$pawns.".comments as comments, ".$pawns.".start_date as start_date,".$pawns.".end_date as end_date,
            ".$pawns.".product_name as product_name
            FROM " . $people . "
            INNER JOIN " . $customers . " ON 										                       
            " . $people . ".person_id = " . $customers . ".person_id
            INNER JOIN " . $pawns . " ON 										                       
            " . $people . ".person_id = " . $pawns . ".person_id
            WHERE ".$pawns.".deleted =0 ORDER BY " . $col . " " . $order . " 
            LIMIT  " . $offset . "," . $limit);

        return $data;
    }

    function count_all() {
        $this->db->from('pawn');
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }

    /*
      Gets information about a particular customer
     */

    function get_info($pawn_id) {
        $pawns = $this->db->dbprefix('pawn');
        $people = $this->db->dbprefix('people');
        $customers = $this->db->dbprefix('customers');
        $this->db->select("".$people.".first_name as first_name, ".$people.".person_id as person_id,
            ".$people.".last_name as last_name, ".$people.".phone_number as phone_number,
            ".$pawns.".id as pawn_id, ".$pawns.".amount as amount,
            ".$pawns.".rate as rate, ".$pawns.".duration as duration,".$pawns.".pay_type as pay_type,  
            ".$pawns.".currency as currency,".$pawns.".is_loan as is_loan,
            ".$pawns.".comments as comments, ".$pawns.".start_date as start_date,".$pawns.".end_date as end_date,
            ".$pawns.".product_name as product_name");
        $this->db->from('customers');
        $this->db->join('people', 'people.person_id = customers.person_id');
        $this->db->join('pawn', 'pawn.person_id = customers.person_id');
        $this->db->where('pawn.id', $pawn_id);
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
        $this->db->insert('pawn', $data);
        $pawn_id = $this->db->insert_id();
        //pay for month and normal 
        if($data['pay_type']==2 && $data['is_loan']==0){
            $this->generat_pawn_sechdule_as_month($pawn_id,$data);
        }
        //pay for month and generate as loan
        else if($data['pay_type']==2 && $data['is_loan']==1){
            $this->generat_pawn_sechdule_as_month_with_loan_process($pawn_id,$data);
        }
        //pay for day and duration is 15 days
        else if($data['pay_type']==3 && $data['duration'] == 15){
            $this->generat_pawn_sechdule_as_day_and_fifteen($pawn_id, $data);
        }
        //pay for day and duration is not equal to 15 days
        else if($data['pay_type']==1){
             $this->generat_pawn_sechdule_as_day($pawn_id, $data);
        }
        
        return $pawn_id;
    }
    
    
    //generate schedule for dairly pay and the duratio == 15
    function generat_pawn_sechdule_as_day_and_fifteen($pawn_id, $param) {
        $data['pawn_id'] = $pawn_id;
       
        $rate = $param['rate'];
        $amount = $param['amount'];
        $data['pay_total'] = $amount;
      
        $duration = $param['duration'];
        
        for ($i = 0; $i < 2; $i++) {
                $data['pay_principle'] = $amount;
                    if($i==0){
                        $data['number_day'] = 0;
                        $data['pay_rate'] = (($rate / 100) * $amount)/2;
                        $data['pay_date'] = $param['start_date'];
                        $data['key']=1;
                    } 
                    else{
                        $data['number_day'] = $duration;
                        $data['pay_rate'] = 0;
                        $data['pay_date'] = $param['end_date'];
                        $data['key']=2;
                    }
                    
                $data['pay_total'] = $data['pay_rate'] + $data['pay_principle'];
                
            $this->db->insert('pawn_schedule', $data);
        }
        return FALSE;
    }
    
    //generate schedule for dairly pay and the duration != 15
    function generat_pawn_sechdule_as_day($pawn_id, $param) {
        $data['pawn_id'] = $pawn_id;
       
        $rate = $param['rate'];
        $amount = $param['amount'];
        $data['pay_total'] = $amount;
      
        $duration = $param['duration'];
        
        for ($i = 0; $i < 2; $i++) {
                $data['pay_principle'] = $amount;
                    if($i==0){
                        $data['number_day'] = 0;
                        $data['pay_rate'] = (($rate / 100) * $amount)*$duration;
                        $data['pay_date'] = $param['start_date'];
                        $data['key']=1;
                    } 
                    else{
                        $data['number_day'] = $duration;
                        $data['pay_rate'] = 0;
                        $data['pay_date'] = $param['end_date'];
                        $data['key']=2;
                    }
                    
                $data['pay_total'] = $data['pay_rate'] + $data['pay_principle'];
                
            $this->db->insert('pawn_schedule', $data);
        }
        return FALSE;
    }
    
    //add amount to principle 
    function add_prince($id,$data){
        $this->db->where('id',$id);
        return $this->db->update('pawn_schedule',$data);
    }
    
    //get bigger id from current row 
    function get_id_biger_than($id, $pawn_id) {
        $this->db->select('*');
        $this->db->from('pawn_schedule');
        $this->db->where('pawn_id', $pawn_id);
        $this->db->where('id', $id + 1);
        $result = $this->db->get();
        return $result->row();
    }
    //get data from schedule by current id
    function get_this_info_for_current_id($id, $pawn_id) {
        $this->db->select('*');
        $this->db->from('pawn_schedule');
        $this->db->where('pawn_id', $pawn_id);
        $this->db->where('id', $id);
        $result = $this->db->get();
        return $result->row();
    }
    
    //count all data that have id bigger that current id
    function count_data_biger_than($id, $pawn_id) {
        $this->db->select("COUNT(*) AS row_of_next_id");
        $this->db->from("pawn_schedule");
        $this->db->where('pawn_id', $pawn_id);
        $this->db->where('id >', $id);
        $result = $this->db->get();
        return $result->row();
    }
    
    function update_re_sechule($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('pawn_schedule', $data);
    }
    
    //generate schedule for monthly pay without pawn process 
     function generat_pawn_sechdule_as_month($pawn_id,$param) {
        $data['pawn_id'] = $pawn_id;
        $startdate = $param['start_date'];
        $rate = $param['rate'];
        $amount = $param['amount'];
        $data['pay_total'] = $amount;
        $data['pay_date'];
        $data['number_day'];
        $data['pay_rate'];
        $data['key'] = 1;
        $duration = $param['duration'];

        $year_int = date('Y', strtotime($startdate));
        $month_int = date('m', strtotime($startdate));
        $day_int = date('d', strtotime($startdate));
        
        for ($i = 0; $i <= $duration; $i++) {
            $data['pay_principle'] = $amount;
                if($i==0){
                    $data['number_day'] = 0;
                    $data['pay_rate'] = ($rate / 100) * $amount;
                }else if($i==$duration){
                    $data['number_day'] = 30;
                    $data['pay_rate'] = 0;
                }else{
                    $data['number_day'] = 30;
                    $data['pay_rate'] = ($rate / 100) * $amount;
                }
            $data['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
            if ($month_int % 2 == 0) {
                if ($month_int == 2) {
                    if ($day_int >= 29) {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-28';
                    } else {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                    }
                } else {
                    if ($day_int >= 30) {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-30';
                    } else {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                    }
                }
            } else {
                if ($month_int == 9 or $month_int == 11) {
                    if ($day_int >= 31) {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-30';
                    } else {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                    }
                } else {
                    if ($day_int >= 31) {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-31';
                    } else {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                    }
                }
            }
            if ($month_int == 12) {
                $year_int++;
                $month_int = 0;
            }
            $month_int++;
            $data['pay_total'] = $data['pay_rate']+$data['pay_principle'];
           
            $this->db->insert('pawn_schedule', $data);
            $data['key']++;
        }
        return FALSE;
    }
    
//generate schedule for monthly pay with loan process 
     function generat_pawn_sechdule_as_month_with_loan_process($pawn_id,$param) {
        $data['pawn_id'] = $pawn_id;
        $startdate = $param['start_date'];
        $rate = $param['rate'];
        $amount = $param['amount'];
        $data['pay_date'];
        $data['number_day'];
        $data['pay_rate'];
        $data['key'] = 1;
        $duration = $param['duration'];
        $paid_princ = $param['amount']/$duration;
        $amount_for_rate = $amount;

        $year_int = date('Y', strtotime($startdate));
        $month_int = date('m', strtotime($startdate));
        $day_int = date('d', strtotime($startdate));
        
        for ($i = 0; $i <= $duration; $i++) {
            $data['pay_principle'] = $amount;
                if($i==0){
                    $data['number_day'] = 0;
                    $data['pay_rate'] = ($rate / 100) * $amount_for_rate;
                }else if($i==$duration){
                    $data['number_day'] = 30;
                    $data['pay_rate'] = 0;
                    $data['paid_princ'] = $paid_princ;
                    
                }else{
                    $data['number_day'] = 30;
                    $data['pay_rate'] = ($rate / 100) * $amount_for_rate;
                    $data['paid_princ'] = $paid_princ;
                   
                }


            $data['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
            if ($month_int % 2 == 0) {
                if ($month_int == 2) {
                    if ($day_int >= 29) {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-28';
                    } else {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                    }
                } else {
                    if ($day_int >= 30) {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-30';
                    } else {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                    }
                }
            } else {
                if ($month_int == 9 or $month_int == 11) {
                    if ($day_int >= 31) {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-30';
                    } else {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                    }
                } else {
                    if ($day_int >= 31) {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-31';
                    } else {
                        $data['pay_date'] = $year_int . '-' . $month_int . '-' . $day_int;
                    }
                }
            }
            if ($month_int == 12) {
                $year_int++;
                $month_int = 0;
            }
            $month_int++;
            $data['pay_total'] = $data['pay_rate'] + $paid_princ;
           
            $this->db->insert('pawn_schedule', $data);
            $data['key']++;
            $amount_for_rate -= $paid_princ;  
            if($i!=0){
              $amount -= $paid_princ;  
            }
        }
        return FALSE;
    }

    /*
      Deletes one customer
     */

    function delete($pawn_id) {
        $this->db->where('id', $pawn_id);
        return $this->db->update('pawn', array('deleted' => 1));
    }

    /*
      Deletes a list of customers
     */

    function delete_list($pawn_id) {
        $this->db->where_in('id', $pawn_id);
        return $this->db->update('pawn', array('deleted' => 1));
    }

    function check_duplicate($term) {
        $this->db->from('pawn');
        $this->db->where('deleted', 0);
        $this->db->where("CONCAT(person_id, ' ', DATE_FORMAT(start_date,'%Y-%m-%d')) LIKE '%" . $term . "%'", NULL, FALSE);
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
        $pawns = $this->db->dbprefix('pawn');
        $customers = $this->db->dbprefix('customers');
        $people = $this->db->dbprefix('people');
        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('pawn', 'pawn.person_id = people.person_id');

        $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		CONCAT(`last_name`,', ',`first_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and ".$pawns.".deleted=0");

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
        $this->db->join('pawn', 'pawn.person_id = people.person_id');
        $this->db->where('pawn.deleted', 0);
        $this->db->like("start_date", $search);
        $this->db->limit($limit);
        $by_email = $this->db->get();

        $temp_suggestions = array();
        foreach ($by_email->result() as $row) {
            $temp_suggestions[] = date('d-m-Y',strtotime($row->start_date));
        }

        sort($temp_suggestions);
        foreach ($temp_suggestions as $temp_suggestion) {
            $suggestions[] = array('label' => $temp_suggestion);
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('pawn', 'pawn.person_id = people.person_id');
        $this->db->where('pawn.deleted', 0);
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
        $this->db->join('pawn', 'pawn.person_id = people.person_id');
        $this->db->where('pawn.deleted', 0);
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
        $this->db->join('pawn', 'pawn.person_id = people.person_id');
        $this->db->where('pawn.deleted', 0);
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

    function search($search, $limit = 20, $offset = 0, $column = 'pawn_id', $orderby = 'desc') {
        $pawns = $this->db->dbprefix('pawn');
        $people = $this->db->dbprefix('people');
        $customers = $this->db->dbprefix('customers');
        $this->db->select("".$people.".first_name as first_name, ".$people.".person_id as person_id,
            ".$people.".last_name as last_name, ".$people.".phone_number as phone_number,
            ".$pawns.".id as pawn_id, ".$pawns.".amount as amount,  ".$pawns.".is_loan as is_loan, ".$pawns.".pay_type as pay_type,
            ".$pawns.".rate as rate, ".$pawns.".duration as duration, 
            ".$pawns.".currency as currency");
        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('pawn', 'pawn.person_id=people.person_id');
        $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			email LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			phone_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			account_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			amount LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			DATE_FORMAT(start_date,'%d-%m-%Y') LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			product_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`last_name`,', ',`first_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and ".$pawns.".deleted=0");
        $this->db->order_by($column, $orderby);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    function search_count_all($search, $limit = 10000) {
        $pawns = $this->db->dbprefix('pawn');
        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->join('pawn', 'pawn.person_id=people.person_id');
        $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			email LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			phone_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			account_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			amount LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			DATE_FORMAT(start_date,'%d-%m-%Y') LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			product_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`last_name`,', ',`first_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and ".$pawns.".deleted=0");
        //$this->db->order_by($column, $orderby);
        $this->db->limit($limit);
        $result = $this->db->get();
        return $result->num_rows();
    }

    function cleanup() {
        $customer_data_schedule = array('account_number' => null);
        $this->db->where('deleted', 1);
        return $this->db->update('pawns', $customer_data);
    }
    
    
    function get_pay_all_before_the_end($pawn_id){
        $this->db->where('pawn_id',$pawn_id);
        $this->db->where('status',2);
        return $this->db->get('pawn_schedule');
    }
    
    function get_pay_all_at_the_end($pawn_id){
        $this->db->where('pawn_id',$pawn_id);
        $this->db->where('status',1);
        return $this->db->get('pawn_schedule');
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
        $this->db->insert('product_pawn_attachment',$data);
    }
    //this function use to update pawn
    function update_pawn($pawn_id,$data){
        $this->db->where('id',$pawn_id);
        return $this->db->update('pawn',$data);
    }
    
    function update_pawn_and_schedule($pawn_id,$data){
        $this->db->where('id',$pawn_id);
        $update_pawn = $this->db->update('pawn',$data);
        if($update_pawn){
           $this->db->where('pawn_id',$pawn_id); 
           $this->db->delete('pawn_schedule');
        }
         //pay for month and normal 
        if($data['pay_type']==2 && $data['is_loan']==0){
            $this->generat_pawn_sechdule_as_month($pawn_id,$data);
        }
        //pay for month and generate as loan
        else if($data['pay_type']==2 && $data['is_loan']==1){
            $this->generat_pawn_sechdule_as_month_with_loan_process($pawn_id,$data);
        }
        //pay for day and duration is 15 days
        else if($data['pay_type']==3 && $data['duration'] == 15){
            $this->generat_pawn_sechdule_as_day_and_fifteen($pawn_id, $data);
        }
        //pay for day and duration is not equal to 15 days
        else if($data['pay_type']==1){
             $this->generat_pawn_sechdule_as_day($pawn_id, $data);
        }
        
        return $pawn_id;
    }
    //get the schedule for pawn
    function get_schedule($pawn_id){
        $this->db->from('pawn_schedule');
        //$this->db->join('pawn','pawn.id = pawn_schedule.pawn_id');
        $this->db->where('pawn_schedule.pawn_id',$pawn_id);
        //$this->db->where('deleted',0);
        return $this->db->get();
    }
    
    function get_attachment($pawn_id){
        $this->db->where('pawn_id',$pawn_id);
        $this->db->where('deleted',0);
        return $this->db->get('product_pawn_attachment');
    }
    
    function add_comment($id,$data){
        $this->db->where('id',$id);
        return $this->db->update('pawn_schedule',array('note'=>$data));
    }
    
    function add_late($id, $number_day, $pay_fine){
        $this->db->where('id',$id);
        return $this->db->update('pawn_schedule',array('late'=>$number_day,'pay_fine'=>$pay_fine));
    }
    //check is paid
    function check_is_pay($id){
        $this->db->where('id',$id);
        $this->db->where('status',1);
        return $this->db->get('pawn_schedule')->num_rows();
    }
    //update status
    function update_paid($id,$data){
       $this->db->where('id',$id);
       return $this->db->update('pawn_schedule',$data);
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
        return $this->db->get('pawn_schedule')->row();
    }
    
    //update not pay 
    function update_not_pay($id,$pawn_id,$data) {
        $this->db->where('id >', $id);
        $this->db->where('pawn_id', $pawn_id);
        $this->db->update('pawn_schedule', $data);
    }
    
    //update paid all 
    function update_pay_all($id,$pawn_id) {
        $this->db->where('id >', $id);
        $this->db->where('pawn_id', $pawn_id);
        $data['paid_princ']= 0;
        $data['status']= 0;
        $this->db->update('pawn_schedule', $data);
    }
    
    function get_sum_principle_new($pawn_id){
        $this->db->select('sum(paid_princ) as paid_princ');
        $this->db->where('pawn_id',$pawn_id);
        $this->db->where('status',0);
        $i = $this->db->get('pawn_schedule');
        return $i->row();
    }
    
    //We create a temp table that allows us to do easy report/Loans queries
    public function create_pawns_temp_table($params){
            //$location_id = $this->Employee->get_logged_in_employee_current_location_id();		
            $where = '';
            if(isset($params['start_date']) && isset($params['end_date']))
            {
                 $where = 'WHERE '.$this->db->dbprefix('pawn').'.start_date BETWEEN '.$this->db->escape($params['start_date']).' and '.$this->db->escape($params['end_date']).'';
            }

            $return = $this->_create_pawns_temp_table_query($where);		
            return $return;
    }
	
	function _create_pawns_temp_table_query($where)
	{
		set_time_limit(0);
		$query = $this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('pawns_temp')."
		(SELECT ".$this->db->dbprefix('pawn').".deleted as deleted, 
                ".$this->db->dbprefix('pawn').".start_date as start_date, 
                ".$this->db->dbprefix('pawn').".amount as amount, 
                ".$this->db->dbprefix('pawn').".comments as comments,
                ".$this->db->dbprefix('people').".person_id as person_id,
                concat(".$this->db->dbprefix('people').".first_name,' ',".$this->db->dbprefix('people').".last_name) as borrower,
                ".$this->db->dbprefix('pawn').".id as pawn_id,
		SUM(".$this->db->dbprefix('pawn_schedule').".pay_principle) as pay_principle,
		SUM(".$this->db->dbprefix('pawn_schedule').".paid_princ) as paid_princ,
                SUM(".$this->db->dbprefix('pawn_schedule').".pay_rate) as pay_rate,
                SUM(".$this->db->dbprefix('pawn_schedule').".paid_rate) as paid_rate,
                SUM(".$this->db->dbprefix('pawn_schedule').".late) as late,
                SUM(".$this->db->dbprefix('pawn_schedule').".pay_fine) as pay_fine,
                SUM(".$this->db->dbprefix('pawn_schedule').".pay_fine)+SUM(".$this->db->dbprefix('pawn_schedule').".paid_rate) as profit
		FROM ".$this->db->dbprefix('pawn')."
		INNER JOIN ".$this->db->dbprefix('pawn_schedule')." ON  ".$this->db->dbprefix('pawn').'.id = '.$this->db->dbprefix('pawn_schedule').'.pawn_id'."
		INNER JOIN ".$this->db->dbprefix('people')." ON  ".$this->db->dbprefix('pawn').'.person_id = '.$this->db->dbprefix('people').'.person_id'."
		INNER JOIN ".$this->db->dbprefix('customers')." ON  ".$this->db->dbprefix('customers').'.person_id='.$this->db->dbprefix('pawn').'.person_id'."
		$where GROUP BY ".$this->db->dbprefix('pawn').".id)");
                return $query;
	}
        
        //allow pawn 
        function allow_fine($id,$check){
            $this->db->where('id',$id);
            return $this->db->update('pawn_schedule',array('check_fine'=>$check));
        }

}

?>
