<?php 

class User extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('base');
		$this->load->model('site');
		$this->load->model('mailer');
		$this->load->library('encryption');
	}

	function is_register($email)
	{
		$res = $this->get(['id'], ['email' => $email]);
		if($res !== false)
		{
			return true;
		}
		return false;
	}

	function register($name, $email, $password, $role = 'user', $status = 'active')
	{
		$data['name'] = $name;
		$data['email'] = $email;
		$data['password'] = hash_256($password);
		$data['role'] = $role;
		$data['status'] = $status;
		$data['date'] = time();
		$data['key'] = hash_64(implode(':', $data));
		$data['rec'] = hash_64(implode(':', $data));
		$data['2fa_status'] = 'inactive';
		$data['2fa_key'] = hash_128(implode(':', $data));
		$this->mailer->send('new_user', $email, [
			'user_name' => $name,
			'user_email' => $email,
			'activation_url' => base_url('f/activate_user/'.$data['rec'])
		]);
		return $this->base->new('users', $data);
	}

	function login($email, $password, $days = 1)
	{
		$data = $this->get(['password', 'key', 'rec', 'role'], ['email' => $email]);
		$data = $data[0];
		if(hash_equals($data['password'], hash_256($password)))
		{
			$time = time();
			$token = hash_128($data['rec'].':'.$data['key'].':'.$data['role'].':'.$time);
			$json = json_encode([$email, $token, $time]);
			$gz = gzcompress($json);
			$token = $this->encryption->encrypt($gz);
			set_cookie('role', $data['role'], $days * 86400);
			set_cookie('logged', true, $days * 86400);
			set_cookie('token', $token, $days * 86400);
			return true;
		}
		return false;
	}

	function forget($email)
	{
		if($this->is_register($email))
		{
			$data = $this->get(['name', 'key', 'rec'], ['email' => $email]);
			$data = $data[0];
			$time = time() + 3600;
			$token = $data['rec'];
			$json = json_encode([$email, $token, $time]);
			$gz = gzcompress($json);
			$token = base64_encode($gz);
			$reset_url = base_url('f/reset/'.$token);
			$this->mailer->send('forget_password', $email, [
				'user_name' => $data['name'],
				'user_email' => $email,
				'reset_url' => $reset_url
			]);
			return true;
		}
		return true;
	}

	function reset($email, $password, $token)
	{
		$rec = hash_128($email.':'.hash_256($password).':'.time().':'.$token);
		$res = $this->set(['password' => hash_256($password), 'rec' => $rec], ['email' => $email]);
		if($res !== false)
		{
			return true;
		}
		return false;
	}

	function is_logged($return = false)
	{
		if(get_cookie('logged', true))
		{
			$gz = $this->encryption->decrypt(get_cookie('token', true));
			$json = gzuncompress($gz);
			$data = json_decode($json, true);
			$res = $this->get(['key', 'rec'], ['email' => $data[0], 'role' => get_cookie('role')]);
			if(count($res) > 0)
			{
				$res = $res[0];
				$token = hash_128($res['rec'].':'.$res['key'].':'.get_cookie('role').':'.$data[2]);
				if(hash_equals($token, $data[1]))
				{
					if($return !== false)
					{
						return $data[0];
					}
					return true;
				}
				delete_cookie('role');
				delete_cookie('logged');
				delete_cookie('token');
				return false;
			}
			return false;
		}
		return false;
	}

	function logged_data($data = ['name', 'email', 'role'])
	{
		$email = $this->is_logged(true);
		if(!is_bool($email))
		{
			$res = $this->get($data, ['email' => $email]);
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
		return false;
	}

	function get($data = [], $where = [], $or_where = [])
	{
		$res = $this->base->get('users', $where, $or_where, []);
		if(count($res) > 0)
		{
			return $res;
		}
		return false;
	}

	function set($data = [], $where = [], $or_where = [])
	{
		$res = $this->base->set('users', $data, $where, $or_where);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>