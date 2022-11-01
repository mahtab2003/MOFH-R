<?php 

class Gogetssl extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
		$this->load->library('gogetsslapi');
	}

	function exec($command, $data = [])
	{
		try
		{
			$api = new GoGetSSLApi;
			$gogetssl = $this->get(['username', 'password']);
			$api->auth($gogetssl['username'], $gogetssl['password']);
			$commands = [
				'create' => 'addSSLOrder'
			];
			foreach ($commands as $key => $value) {
				if($key === $command)
				{
					$res = $api->$value($data);
					return $res;
				}
			}
			return false;
		}
		catch (Exception $e)
		{
			return $e;
		}
		return false;
	}

	function get($data = [])
	{
		$res = $this->base->get('gogetssl', ['id' => 'gogetssl']);
		if(count($res) > 0)
		{
			return $res[0];
		}
		return false;
	}

	function set($data = [])
	{
		$res = $this->base->set('gogetssl', $data, ['id' => 'gogetssl']);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>