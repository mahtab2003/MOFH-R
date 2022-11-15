<?php 

class Ssl extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
		$this->load->model('site');
		$this->load->model('selfsigned');
		$this->load->model('gogetssl');
		$this->load->library('encryption');
		$this->ssl_dir = APPPATH.'storage/';
		if(!is_dir($this->ssl_dir))
		{
			mkdir($this->ssl_dir);
		}
	}

	function create($provider, $domain)
	{
		if($provider === 'selfsigned')
		{
			$user = $this->user->logged_data(['email', 'key']);
			$pair = $this->selfsigned->new_csr($domain, $user['email']);
			$res = $this->selfsigned->issue_crt($pair['csr'], $pair['privkey']);
			$key = hash_64($user['key'].':'.$domain.':'.$provider.':'.time());
			$res['status'] = 'active';
			$res['start_date'] = date('d-m-Y');
			$res['end_date'] = date('d-m-Y', time() + 90 * 60);//86400);
			$res['domain'] = $domain;
			$res['email'] = $user['email'];
			if(!is_dir($this->ssl_dir.$domain.'/'))
			{
				mkdir($this->ssl_dir.$domain.'/');
			}
			if(!is_dir($this->ssl_dir.$domain.'/'.$key.'/'))
			{
				mkdir($this->ssl_dir.$domain.'/'.$key.'/');
			}
			$json = json_encode($res);
			$code = $this->encryption->encrypt($json);
			file_put_contents($this->ssl_dir.$domain.'/'.$key.'/information.dat', $code);
			$data = [
				'provider' => $provider,
				'domain' => $domain,
				'key' => $key,
				'for' => $user['key']
			];
			$res = $this->base->new('ssl', $data);
			if($res !== false)
			{
				return true;
			}
			return false;
		}
		if($provider === 'gogetssl')
		{
			$user = $this->user->logged_data(['email', 'key']);
			$pair = $this->selfsigned->new_csr($domain, $user['email']);
			$res = $this->gogetssl->exec('create', [
				'product_id'       => 65,
				'csr' 			   => $pair['csr'],
			    'server_count'     => "-1",
			    'period'           => 3,
			    'approver_email'   => $user['email'],
			    'webserver_type'   => "1",
			    'admin_firstname'  => 'Web',
			    'admin_lastname'   => 'Host',
			    'admin_phone'      => '03000000000',
			    'admin_title'      => "Mr",
			    'admin_email'      => $user['email'],
			    'tech_firstname'   => 'Web',
			    'tech_lastname'    => 'Host',
			    'tech_phone'       => '03000000000',
			    'tech_title'       => "Mr",
			    'tech_email'       => $user['email'],
			    'org_name'         => $this->site->get(['title']),
			    'org_division'     => "Hosting",
			    'org_addressline1' => 'Block# Area#',
			    'org_city'         => 'New York',
			    'org_country'      => 'US',
			    'org_phone'        => '03000000000',
			    'org_postalcode'   => '11001',
			    'org_region'       => "None",
			    'dcv_method'       => "dns"
			]);
			if (is_array($res) AND count($res) > 4)
			{
				$key = hash_64($user['key'].':'.$domain.':'.$provider.':'.time());
				$data['csr'] = $pair['csr'];
				$data['privkey'] = $pair['privkey'];
				$data['status'] = 'pocessing';
				$data['start_date'] = date('d-m-Y');
				$data['end_date'] = date('d-m-Y', time() + 90 * 86400);
				$data['domain'] = $domain;
				$data['email'] = $user['email'];
				$data['dns'] = $res['approver_method']['dns']['record'];
				if(!is_dir($this->ssl_dir.$domain.'/'))
				{
					mkdir($this->ssl_dir.$domain.'/');
				}
				if(!is_dir($this->ssl_dir.$domain.'/'.$key.'/'))
				{
					mkdir($this->ssl_dir.$domain.'/'.$key.'/');
				}
				$json = json_encode($data);
				$code = $this->encryption->encrypt($json);
				file_put_contents($this->ssl_dir.$domain.'/'.$key.'/information.dat', $code);
				$data = [
					'pid' => $res['order_id'],
					'provider' => $provider,
					'domain' => $domain,
					'key' => $key,
					'for' => $user['key']
				];
				$res = $this->base->new('ssl', $data);
				if($res !== false)
				{
					return true;
				}
				return false;
			}
			elseif(is_string($res))
			{
				return $res;
			}
			return false;
		}
		return false;
	}

	function fetch($domain, $key)
	{
		if(is_dir($this->ssl_dir.$domain.'/'.$key.'/'))
		{
			$code = file_get_contents($this->ssl_dir.$domain.'/'.$key.'/information.dat');
			$data = json_decode($this->encryption->decrypt($code), true);
			return $data;
		}
		return false;
	}

	function delete($pid)
	{
		$data = $this->get(['domain', 'key'], ['pid' => $pid]);
		$domain = $data[0]['domain'];
		$key = $data[0]['key'];
		if(is_dir($this->ssl_dir.$domain.'/'.$key.'/'))
		{
			unlink($this->ssl_dir.$domain.'/'.$key.'/information.dat');
			rmdir($this->ssl_dir.$domain.'/'.$key);
			$this->base->delete('ssl', ['pid' => $pid]);
			return true;
		}
		return false;
	}

	function delete_ss($key)
	{
		$data = $this->get(['domain'], ['key' => $key]);
		$domain = $data[0]['domain'];
		$key = $data[0]['key'];
		if(is_dir($this->ssl_dir.$domain.'/'.$key.'/'))
		{
			unlink($this->ssl_dir.$domain.'/'.$key.'/information.dat');
			rmdir($this->ssl_dir.$domain.'/'.$key);
			$this->base->delete('ssl', ['key' => $key]);
			return true;
		}
		return false;
	}

	function update($pid, $param = [])
	{
		$data = $this->get(['domain', 'key'], ['pid' => $pid]);
		if(count($data) > 0)
		{
			$fetch = $this->fetch($data[0]['domain'], $data[0]['key']);
			foreach ($param as $key => $value) {
				$fetch[$key] = $value;
			}
			$json = json_encode($fetch);
			$code = $this->encryption->encrypt($json);
			file_put_contents($this->ssl_dir.$domain.'/'.$key.'/information.dat', $code);
			return true;
		}
		return false;
	}

	function get($data = [], $where = [], $or_where = [])
	{
		$res = $this->base->get('ssl', $where, $or_where, []);
		if(count($res) > 0)
		{
			return $res;
		}
		return [];
	}

	function set($data = [], $where = [], $or_where = [])
	{
		$res = $this->base->set('ssl', $data, $where, $or_where);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>