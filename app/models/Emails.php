<?php 

class Emails extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
	}

	function get($data = ['subject'], $id)
	{
		$res = $this->base->get('emails', ['id' => $id], [], []);
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

	function set($data = [], $id)
	{
		$res = $this->base->set('emails', $data, ['id' => $id]);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>