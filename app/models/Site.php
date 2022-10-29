<?php 

class Site extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
	}

	function get($data = ['title'])
	{
		$res = $this->base->get('base', ['id' => 'nxvim'], [], []);
		$return = [];
		if(count($res) > 0)
		{
			if(count($data) > 1)
			{
				for ($i = 0; $i < count($data); $i++) { 
					$return[$data[$i]] = $res[0][$data[$i]] ?? 'NULL';
				}
				return $return;
			}
			return $res[0][$data[0]];
		}
		return false;
	}

	function set($data = [])
	{
		$res = $this->base->set('base', $data, ['id' => 'nxvim']);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>