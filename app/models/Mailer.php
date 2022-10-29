<?php 

class Mailer extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('site');
		$this->load->model('smtp');
		$this->load->model('user');
		$this->load->model('emails');
		$this->load->library('email');

		$smtp = $this->smtp->get(['hostname', 'username', 'password', 'port']);
		
		$email['smtp_host'] = $smtp['hostname'];
		$email['smtp_user'] = $smtp['username'];
		$email['smtp_pass'] = $smtp['password'];
		$email['smtp_port'] = $smtp['port'];

		$this->email->initialize($email);
	}

	function is_active()
	{
		$status = $this->smtp->get(['status']);
		if($status !== 'active')
		{
			return true;
		}
		return false;
	}

	function send($id, $email, $param = [])
	{
		if($this->is_active())
		{
			$template = $this->emails->get(['subject', 'content'], $id);
			if(is_array($template))
			{
				$subject = $template['subject'].' - '.$this->site->get(['title']);
				$content = $template['content'];
				$param['site_name'] = $this->site->get(['title']);
				$param['site_url'] = base_url();
				foreach(array_keys($param) as $key)
				{
					$content = str_replace("{".$key."}", $param[$key], $content);
				}
				$this->email->from($this->smtp->get(['from']), $this->site->get(['title']));
				$this->email->to($email);
				$this->email->subject($subject);
				$this->email->message($content);
				$res = @$this->email->send();
				if($res !== false)
				{
					return true;
				}
				return false;
			}
			return false;
		}
		return false;
	}

	function test_mail()
	{
		$this->email->from($this->smtp->get(['from']), $this->site->get(['title']));
		$this->email->to($this->user->logged_data(['email']));
		$this->email->subject('Test Email');
		$this->email->message('If you have received this email thats mean smtp config is setup correctly.');
		$res = $this->email->send();
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>