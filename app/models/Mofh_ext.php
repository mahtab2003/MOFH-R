<?php

class Mofh_ext extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
	}

	function get($data = [], $where = [])
	{
		$res = $this->base->get('mofh_ext', $where, [], $data);
		$return = [];
		if(count($res) > 0)
		{
			return $res;
		}
		return false;
	}

	function set($data = [], $where = [])
	{
		$res = $this->base->set('mofh_ext', $data, $where);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>