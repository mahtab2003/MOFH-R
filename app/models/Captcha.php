<?php 

class Captcha extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('site');
	}

	function is_active()
	{
		$status = $this->get(['status']);
		if($status === 'active')
		{
			return true;
		}
		return false;
	}

	function verify()
	{
		if($this->is_active())
		{
			if($this->input->post('g-recaptcha-response') OR $this->input->post('h-captcha-response'))
			{
				$captcha = $this->get(['type', 'secret_key']);
				if($captcha['type'] == 'google')
				{
					$token = $this->input->post('g-recaptcha-response');;
			        $res = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$captcha['secret_key']."&response=$token");
			        $res = json_decode($res);
			        if($res->success){
			        	return true;
			        }
			        return false;
				}
				elseif($captcha['type'] == 'human')
				{
					$token = $this->input->post('h-captcha-response');
					$param = http_build_query([
						"secret" => $captcha['secret_key'], 
						"response" => $token
					]);
					$ch = curl_init("https://hcaptcha.com/siteverify");
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$result = curl_exec($ch);
			        $res = json_decode($result);
			        if($res->success){
			        	return true;
			        }
			        return false;
				}
				return false;
			}
			return false;
		}
		return true;
	}

	function captcha()
	{
		if($this->is_active())
		{
			$captcha = $this->get(['type', 'site_key']);
			if($captcha['type'] == 'google')
			{
				return "<div class=\"g-recaptcha\" data-sitekey=".$captcha['site_key']."></div>\n<script src=\"https://www.google.com/recaptcha/api.js\" async defer ></script>";
			}
			elseif($captcha['type'] == 'human')
			{
				return "<div id='captcha' class='h-captcha' data-sitekey=".$captcha['site_key']."></div>\n<script src=\"https://hcaptcha.com/1/api.js\" async defer ></script>";
			}
			return '';
		}
		return '';
	}

	function get($data = ['type'])
	{
		$res = $this->base->get('captcha', ['id' => 'captcha'], [], []);
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
		$res = $this->base->set('captcha', $data, ['id' => 'captcha']);
		if($res !== false)
		{
			return true;
		}
		return false;
	}
}

?>