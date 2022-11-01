<?php 
use \InfinityFree\MofhClient\Client;

class Mofh extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
		$this->load->model('mofh_ext');
	}

	function exec($command, $data = [])
	{
		try
		{
			$mofh = $this->get(['username', 'password', 'plan']);
			$api = new Client;
			$api->setApiUsername($mofh['username']);
			$api->setApiPassword($mofh['password']);
			$api->setPlan($mofh['plan']);
			$commands = [
				'check' => 'availability',
				'create' => 'createAccount',
				'passwd' => 'password',
				'suspend' => 'suspend',
				'unsuspend' => 'unsuspend',
				'domains' => 'GetUserDomains'
			];
			foreach ($commands as $key => $value) {
				if($command == $key)
				{
					$req = $api->$value($data);
					$res = $req->send();
					return $res;
				}
			}
			return false;
		}
		catch (Exception $e)
		{
			return strval($e);	
		}
	}

	function get($data = ['username'])
	{
		$res = $this->base->get('mofh', ['id' => 'mofh'], [], []);
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
		$res = $this->base->set('mofh', $data, ['id' => 'mofh']);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>