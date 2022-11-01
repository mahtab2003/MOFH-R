<?php 

class Hosting extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
		$this->load->model('mofh');
		$this->load->model('user');
	}

	function check($domain)
	{
		$res = $this->mofh->exec('check', ['domain' => $domain]);
		if(is_string($res))
		{
			return $res;
		}
		elseif($res->isSuccessful() == 0 AND strlen($res->getMessage()) > 1)
		{
			return trim($res->getMessage());
		}
		elseif($res->isSuccessful() == 1 AND $res->getMessage() == 1)
		{
			return true;
		}
		return false;
	}

	function create($label, $domain)
	{
		$user = $this->user->logged_data(['email', 'key']);
		$username = hash_32($label.':'.$domain.':'.$user['email'].':'.time());
		$password = substr(hash_64($username.':'.time()), 0, 15);
		$res = $this->mofh->exec('create', [
			'username' => $username,
			'password' => $password,
			'domain' => $domain,
			'email' => $user['email']
		]);
		if(is_string($res))
		{
			return $res;
		}
		elseif($res->isSuccessful() == 0 AND strlen($res->getMessage()) > 1)
		{
			return trim($res->getMessage());
		}
		elseif($res->isSuccessful() == 1 AND strlen($res->getMessage()) > 1)
		{
			$res = $this->base->new('hosting', [
				'label' => $label,
				'username' => $res->getVpUsername(),
				'password' => $password,
				'status' => 'processing',
				'key' => $username,
				'for' => $user['key'],
				'time' => time(),
				'domain' => $domain,
				'main' => str_replace('cpanel', $username, $this->mofh->get(['cpanel_url']))
			]);
			if($res !== false)
			{
				return true;
			}
			return false;
		}
		return false;
	}

	function passwd($username, $password)
	{
		$res = $this->mofh->exec('passwd', [
			'username' => $username,
			'password' => $password,
			'enabledigest' => 1
		]);
		if(is_string($res))
		{
			return $res;
		}
		elseif($res->isSuccessful() == 0 AND strlen($res->getMessage()) > 1)
		{
			return trim($res->getMessage());
		}
		elseif($res->isSuccessful() == 1 AND strlen($res->getMessage()) > 1)
		{
			$res = $this->set(['password' => $password], ['key' => $username]);
			if($res !== false)
			{
				return true;
			}
			return false;
		}
		return false;
	}

	function suspend($username, $reason)
	{
		$res = $this->mofh->exec('suspend', [
			'username' => $username,
			'reason' => $reason
		]);
		$data = $res->getData();
		$msg = $data['result']['statusmsg'];
		if(is_string($res))
		{
			return $res;
		}
		elseif($res->isSuccessful() == 0 AND !is_array($msg))
		{
			return trim($res->getMessage());
		}
		elseif($res->isSuccessful() == 1 AND is_array($msg))
		{
			$res = $this->set(['status' => 'deactivating'], ['key' => $username]);
			if($res !== false)
			{
				return true;
			}
			return false;
		}
		return false;
	}

	function unsuspend($username)
	{
		$res = $this->mofh->exec('unsuspend', [
			'username' => $username
		]);
		$data = $res->getData();
		$msg = $data['result']['statusmsg'];
		if(is_string($res))
		{
			return $res;
		}
		elseif($res->isSuccessful() == 0 AND !is_array($msg))
		{
			return trim($res->getMessage());
		}
		elseif($res->isSuccessful() == 1 AND is_array($msg))
		{
			$res = $this->set(['status' => 'reactivating'], ['key' => $username]);
			if($res !== false)
			{
				return true;
			}
			return false;
		}
		return false;
	}

	function domains($username)
	{
		$account = $this->get(['password', 'domain']);
		if($account !== false)
		{
			$res = $this->mofh->exec('domains', ['username' => $username]);
			if(is_array($res) AND count($res) > 0)
			{
				foreach ($res as $domain)
				{
					if($domain === $account['domain'])
					{
						$dir = "/htdocs/";
					}
					else
					{
						$dir = "/$domain/htdocs/";
					}
					$config = base64_encode(json_encode([
						't' => 'ftp',
						'c' => [
							'v' => 1,
							'p' => $account['password'],
							'i' => $dir
						]
					]));
					$link = "https://filemanager.ai/new/#/c/ftpupload.net/$username/$config";
					$domains[] = ['domain' => $domain, 'file_manager' => $link];
				}
				return $domains;
			}
			return [];
		}
		return [];
	}

	function get($data = [], $where = [], $or_where = [])
	{
		$res = $this->base->get('hosting', $where, $or_where, $data);
		$return = [];
		if(count($res) > 0)
		{
			return $res;
		}
		return [];
	}

	function set($data = [], $where = [], $or_where = [])
	{
		$res = $this->base->set('hosting', $data, $where, $or_where);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>