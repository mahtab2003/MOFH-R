post<?php 

class C extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('site');
		$this->load->model('user');
		$this->load->model('hosting');
		$this->load->model('mailer');
		$this->load->model('ssl');
	}

	function gogetssl()
	{
		if($this->input->get('callback_version'))
		{
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);
			if($data['status'] === 'active')
			{
				$this->ssl->update($data['product_id'], [
					'status' => 'active',
					'start_date' => $data['begin_date'],
					'end_date' => $data['end_date'],
					'crt' => $data['crt_code']
				]);
			}
			elseif($data['status'] === 'expired' OR $data['status'] === 'cancelled')
			{
				$this->ssl->delete($data['product_id']);
			}
		}
	}

	function selfsigned()
	{
		if($this->input->get('corn_version'))
		{
			$res = $this->ssl->get([], ['provider' => 'selfsigned']);
			if(is_array($res) AND count($res) > 0)
			{
				foreach ($res as $ssl) {
					$data = $this->ssl->fetch($ssl['domain'], $ssl['key']);
					if(strtotime($data['end_date']) < time())
					{
						$this->ssl->delete_ss($ssl['key']);
					}
				}
			}
		}
	}

	function mofh()
	{
		if($this->input->post('username'))
		{
			if($this->input->post('username'))
			{
				$username = $this->input->post('username');
				$status = $this->input->post('status');
				$comment = $this->input->post('comments');
				if(file_exists(APPPATH.'logs/mofh_callback.json'))
				{
					$logs = file_get_contents(APPPATH.'logs/mofh_callback.json');
					$logs = json_decode($logs, true);
				}
				else
				{
					$logs = [];
				}
				$callback = [
					'username' => $username,
					'status' => $status,
					'comment' => $comment,
					'time' => date('d-m-Y h:i:s A')
				];
				$logs[] = $callback;
				file_put_contents(APPPATH.'logs/mofh_callback.json', json_encode($logs));
				if(substr($status, 0, 3) === 'sql')
				{
					$this->hosting->set(['sql' => $status, 'status' => 'active'], ['username' => $username]);
					$res = $this->hosting->get([], ['username' => $username]);
					if(count($res) > 0)
					{
						$user = $this->user->get(['name', 'email'], ['key' => $res[0]['for']]);
						$mofh = $this->mofh->get(['cpanel_url', 'ns_1', 'ns_2']);
						$param['user_name'] = $user[0]['name'];
						$param['user_email'] = $user[0]['email'];
						$param['account_username'] = $username;
						$param['account_password'] = $res[0]['password'];
						$param['account_domain'] = $res[0]['domain'];
						$param['main_domain'] = $res[0]['main'];
						$param['sql_server'] = str_replace('cpanel', $res[0]['sql'], $mofh['cpanel_url']);
						$param['account_label'] = $res[0]['label'];
						$param['cpanel_domain'] = $mofh['cpanel_url'];
						$param['nameserver_1'] = $mofh['ns_1'];
						$param['nameserver_2'] = $mofh['ns_2'];
						$this->mailer->send('account_created', $user[0]['email'], $param);
					}
				}
				elseif($status === 'DELETE')
				{
					$res = $this->hosting->get([], ['username' => $username]);
					if(count($res) > 0)
					{
						$user = $this->user->get(['name', 'email'], ['key' => $res[0]['for']]);
						$res = $this->base->delete('hosting', ['username' => $username]);
						if($res !== false)
						{
							$param['user_name'] = $user[0]['name'];
							$param['user_email'] = $user[0]['email'];
							$param['account_username'] = $username;
							$this->mailer->send('delete_account', $user[0]['email'], $param);
						}
					}
				}
				elseif($status === 'REACTIVATE')
				{
					$res = $this->hosting->get([], ['username' => $username]);
					if(count($res) > 0)
					{
						$user = $this->user->get(['name', 'email'], ['key' => $res[0]['for']]);
						$res = $this->hosting->set(['status' => 'active'], ['username' => $username]);
						if($res !== false)
						{
							$param['user_name'] = $user[0]['name'];
							$param['user_email'] = $user[0]['email'];
							$param['account_username'] = $username;
							$this->mailer->send('account_reactivated', $user[0]['email'], $param);
						}
					}
				}
				elseif($status === 'SUSPENDED')
				{
					$res = $this->hosting->get([], ['username' => $username]);
					if(count($res) > 0)
					{
						$user = $this->user->get(['name', 'email'], ['key' => $res[0]['for']]);
						$parse = explode(':', $comment);
						$account_status = 'suspended';
						$comment = 'some reason';
						if(trim($parse[0]) == 'AUTO_IDLE')
						{
							$comment = 'due to inactivity.';
						}
						elseif(trim($parse[0]) == 'RES_CLOSE')
						{
							$account_status = 'deactivated';
							$comment = $parse[1];
						}
						elseif(trim($parse[0]) == 'ADMIN_CLOSE')
						{
							if(trim($parse[1]) == 'DAILY_HIT')
							{
								$comment = 'reached daily hit limit.';
							}
							elseif(trim($parse[1]) == 'DAILY_cpu')
							{
								$comment = 'reached cpu limit.';
							}
							elseif(trim($parse[1]) == 'DAILY_ cpu')
							{
								$comment = 'reached cpu limit.';
							}
							elseif(trim($parse[1]) == 'abuse_complaint LINKED_PHISH_mail')
							{
								$comment = 'absue complaint.';
							}
							elseif(trim($parse[1]) == 'DISPOSABLE_EMAIL')
							{
								$comment = 'using disposable email.';
							}
							elseif(trim($parse[1]) == 'DAILY_IO')
							{
								$comment = 'reached IO limit.';
							}
							else
							{
								$comment = $parse[1];
							}
						}
						elseif(trim($parse[0]) == 'ADMIN_CLOSE; ADMIN_CLOSE')
						{
							if(trim($parse[1]) == 'BAD PHISHING')
							{
								$comment = 'using nulled or illegal script.';
							}
							else
							{
								$comment = $parse[1];
							}
						}
						$res = $this->hosting->set(['status' => $account_status], ['username' => $username]);
						if($res !== false)
						{
							$param['user_name'] = $user[0]['name'];
							$param['user_email'] = $user[0]['email'];
							$param['some_reason'] = $comment;
							$param['account_username'] = $username;
							$this->mailer->send('account_suspended', $user[0]['email'], $param);
						}
					}
				}
			}
		}
	}
}

?>