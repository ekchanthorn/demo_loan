<?php
class Register extends CI_Model
{
	/*
	Gets information about a particular register
	*/
	function get_info($register_id)
	{
		$this->db->from('registers');	
		$this->db->where('register_id',$register_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			$register_obj = new stdClass;
			
			//Get all the fields from registers table
			$fields = $this->db->list_fields('registers');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$register_obj->$field='';
			}
			
			return $register_obj;
		}
	}
	
	function get_register_name($register_id)
	{
		$info = $this->get_info($register_id);
		
		if ($info && $info->name)
		{
			return $info->name;
		}
		
		return false;
	}
	
	/*
	Determines if a given register_id is a register
	*/
	function exists($register_id)
	{
		$this->db->from('registers');	
		$this->db->where('register_id',$register_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	function get_all($location_id = false)
	{
		if (!$location_id)
		{
			$location_id=$this->Employee->get_logged_in_employee_current_location_id();
		}
		
		$this->db->from('registers');
		$this->db->where('location_id', $location_id);
		$this->db->where('deleted', 0);
		$this->db->order_by('register_id');
		return $this->db->get();
	}

	function count_all($location_id = false)
	{
		if (!$location_id)
		{
			$location_id=$this->Employee->get_logged_in_employee_current_location_id();
		}
		
		$this->db->from('registers');
		$this->db->where('location_id', $location_id);
		$this->db->where('deleted', 0);
		return $this->db->count_all_results();
	}
	
	/*
	Inserts or updates a register
	*/
	function save(&$register_data,$register_id=false)
	{
		if (!$register_id or !$this->exists($register_id))
		{
			if($this->db->insert('registers',$register_data))
			{
				$register_data['register_id']=$this->db->insert_id();
				return true;
			}
			return false;
		}

		$this->db->where('register_id', $register_id);
		return $this->db->update('registers',$register_data);
	}
	
	function delete($register_id)
	{
		$this->db->where('register_id', $register_id);
		return $this->db->update('registers', array('deleted' => 1));
	}
}
?>
