<?php 

class Smtp extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
	}

	function get($data = ['status'])
	{
		$res = $this->base->get('smtp', ['id' => 'smtp'], [], []);
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
		$res = $this->base->set('smtp', $data, ['id' => 'smtp']);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>