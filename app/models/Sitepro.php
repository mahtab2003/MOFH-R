<?php 

class Sitepro extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
		$this->load->library('siteproapi');
	}

	function exec($hostname, $username, $password, $domain, $dir = '/htdocs/')
	{
		$builder = new SiteProApi;
		$builder->setUsername($username);
		$builder->setPassword($password);
		$builder->setDomain($domain);
		$builder->setUploadDir($dir);
		$builder->setHostname($hostname);
		$builder->setApiUrl('ftpupload.net');
		$builder->setApiUsername($this->get(['username']));
		$builder->setApiPassword($this->get(['password']));
		$builder->run();
		return $builder;
	}

	function get($data = [])
	{
		$res = $this->base->get('sitepro', ['id' => 'sitepro']);
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
		$res = $this->base->set('sitepro', $data, ['id' => 'sitepro']);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>