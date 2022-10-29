<?php 

class Base extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('cookie');
		$this->load->helper('hash');
	}

	function new($table = '', $data = [])
	{
		$res = $this->db->insert($table, $data);
		if($res !== false)
		{
			return true;
		}
		return false;
	}

	function delete($table = '', $where = [])
	{
		$res = $this->db->delete($table, $where);
		if($res !== false)
		{
			return true;
		}
		return false;
	}

	function get($table, $where = [], $or_where = [], $data = [])
	{
		$this->db->where($where);
		if(count($or_where) > 0)
		{
			foreach ($or_where as $or)
			{
				$this->db->or_where($or);
			}
		}
		$this->db->select($data);
		$this->db->from($table);
		$this->db->order_by('id', 'DESC');
		$res = $this->db->get()->result_array();
		$this->db->reset_query();
		return $res;
	}

	function set($table, $data = [], $where =[], $or_where = [])
	{
		$this->db->where($where);
		if(count($or_where) > 0)
		{
			foreach ($or_where as $or)
			{
				$this->db->or_where($or);
			}
		}
		$this->db->set($data);
		$res = $this->db->update($table);
		$this->db->reset_query();
		return $res;
	}
}

?>