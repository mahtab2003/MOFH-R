<?php 

class Base extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('cookie');
		$this->load->helper('hash');
	}

	function new($table = '', $data = [])
	{
		$this->load->database();
		$res = $this->db->insert($table, $data);
		$this->db->close();
		if($res !== false)
		{
			return true;
		}
		return false;
	}

	function delete($table = '', $where = [])
	{
		$this->load->database();
		$res = $this->db->delete($table, $where);
		$this->db->close();
		if($res !== false)
		{
			return true;
		}
		return false;
	}

	function get($table, $where = [], $or_where = [], $data = [])
	{
		$this->load->database();
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
		$this->db->close();
		return $res;
	}

	function set($table, $data = [], $where =[], $or_where = [])
	{
		$this->load->database();
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
		$this->db->close();
		return $res;
	}
}

?>