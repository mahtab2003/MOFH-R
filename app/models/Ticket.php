<?php 

class Ticket extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
		$this->load->model('user');
	}

	function get($table = '', $data = [], $where = [], $or_where = [])
	{
		$res = $this->base->get($table, $where, $or_where, $data);
		if($res)
		{
			return $res;
		}
		return [];
	}

	function set($table = '', $data = [], $where = [])
	{
		$res = $this->base->set($table, $data, $where);
		if($res !== false)
		{
			return true;
		}
		return false;
	}

	function create($table = '', $data = [])
	{
		$data['date'] = time();
		$data['for'] = $this->user->logged_data(['key']);
		$res = $this->base->new($table, $data);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>