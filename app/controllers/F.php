<?php 

class F extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('installation');
		if(is_installed() === false)
		{
			redirect('i');
		}
		$this->load->library(['form_validation' => 'fv']);
		$this->load->library('session');
		$this->load->model('site');
		$this->load->model('user');
		$this->load->model('captcha');
		$this->load->model('ui');
	}

	function index()
	{
		$this->login();
	}

	function register()
	{
		if(!$this->user->is_logged())
		{
			if($this->input->post('submit'))
			{
				$this->fv->set_rules('name', $this->ui->text('name_text'), ['trim', 'required', 'valid_name']);
				$this->fv->set_rules('email', $this->ui->text('email_text'), ['trim', 'required', 'valid_email']);
				$this->fv->set_rules('password', $this->ui->text('password_text'), ['trim', 'required']);
				$this->fv->set_rules('confirm_password', $this->ui->text('confirm_password_text'), ['matches[password]', 'trim', 'required']);
				if($this->captcha->verify())
				{
					if($this->fv->run() === true)
					{
						if(!$this->user->is_register($this->input->post('email')))
						{
							$res = $this->user->register(
								$this->input->post('name'),
								$this->input->post('email'),
								$this->input->post('password')
							);
							if($res !== false)
							{
								$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_register_text')]));
								redirect('f/login');
							}
							else
							{
								$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
								redirect('f/register');
							}
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('user_exists_text')]));
							redirect('f/register');
						}
					}
					else
					{
						if(validation_errors() !== '')
						{
							$this->session->set_flashdata('msg', json_encode([0, validation_errors()]));
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('required_fields_text')]));
						}
						redirect('f/register');
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_captcha_response_text')]));
					redirect('f/register');
				}
			}
			else
			{
				$data['title'] = 'register_title';
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/register');
				$this->load->view($this->ui->template_dir().'/includes/footer');
			}
		}
		else
		{
			redirect('n');
		}
	}

	function login()
	{
		if(!$this->user->is_logged())
		{
			if($this->input->post('submit'))
			{
				$this->fv->set_rules('email', $this->ui->text('email_text'), ['trim', 'required', 'valid_email']);
				$this->fv->set_rules('password', $this->ui->text('password_text'), ['trim', 'required']);
				if($this->captcha->verify())
				{
					if($this->fv->run() === true)
					{
						$days = 1;
						if($this->input->post('remember'))
						{
							$days = 30;
						}
						$res = $this->user->login(
							$this->input->post('email'),
							$this->input->post('password'),
							$days
						);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_login_text')]));
							redirect('n');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_credantials_text')]));
							redirect('f/login');
						}
					}
					else
					{
						if(validation_errors() !== '')
						{
							$this->session->set_flashdata('msg', json_encode([0, validation_errors()]));
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('required_fields_text')]));
						}
						redirect('f/login');
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_captcha_response_text')]));
					redirect('f/login');
				}
			}
			else
			{
				$data['title'] = 'login_title';
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/login');
				$this->load->view($this->ui->template_dir().'/includes/footer');
			}
		}
		else
		{
			redirect('n');
		}
	}

	function activate_user($key)
	{
		$res = $this->user->get(['status'], ['rec' => $key]);
		if($res)
		{
			if($res[0]['status'] === 'inactive')
			{
				$rec = hash_128($key.':'.time());
				$this->user->set(['status' => 'active', 'rec' => $rec], ['rec' => $key]);
			}
			$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_verified_text')]));
			redirect('f/login');
		}
		else
		{
			$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_verification_token_text')]));
			redirect('f/login');
		}
	}

	function forget()
	{
		if(!$this->user->is_logged())
		{
			if($this->input->post('submit'))
			{
				$this->fv->set_rules('email', $this->ui->text('email_text'), ['trim', 'required', 'valid_email']);
				if($this->captcha->verify())
				{
					if($this->fv->run() === true)
					{
						$res = $this->user->forget(
							$this->input->post('email')
						);
						$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_login_text')]));
						redirect('f/login');
					}
					else
					{
						if(validation_errors() !== '')
						{
							$this->session->set_flashdata('msg', json_encode([0, validation_errors()]));
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('required_fields_text')]));
						}
						redirect('f/forget');
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_captcha_response_text')]));
					redirect('f/forget');
				}
			}
			else
			{
				$data['title'] = 'forget_title';
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/forget');
				$this->load->view($this->ui->template_dir().'/includes/footer');
			}
		}
		else
		{
			redirect('n');
		}
	}

	function reset($token)
	{
		$json = base64_decode($token);
		$cs = gzuncompress($json);
		$arr = json_decode($cs, true);
		$email = $arr[0];
		$key = $arr[1];
		$time = $arr[2];
		if($time > time())
		{
			if($this->input->post('submit'))
			{
				$this->fv->set_rules('password', $this->ui->text('password_text'), ['trim', 'required']);
				$this->fv->set_rules('confirm_password', $this->ui->text('confirm_password_text'), ['matches[password]', 'trim', 'required']);
				if($this->captcha->verify())
				{
					if($this->fv->run() === true)
					{
						$res = $this->user->reset(
							$email,
							$this->input->post('password'),
							$key
						);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_reset_text')]));
							redirect('f/login');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('f/reset');
						}
					}
					else
					{
						if(validation_errors() !== '')
						{
							$this->session->set_flashdata('msg', json_encode([0, validation_errors()]));
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('required_fields_text')]));
						}
						redirect('f/reset');
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_captcha_response_text')]));
					redirect('f/reset');
				}
			}
			else
			{
				$data['title'] = 'reset_title';
				$data['token'] = $token;
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/reset');
				$this->load->view($this->ui->template_dir().'/includes/footer');
			}
		}
		else
		{
			$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_reset_token_text')]));
			redirect('f/reset');
		}
	}

	function logout($msg = '')
	{
		if($this->user->is_logged())
		{
			delete_cookie('role');
			delete_cookie('logged');
			delete_cookie('token');
			delete_cookie('2fa');
			if($msg === '')
			{
				$msg = $this->ui->text('user_logout_text');
			}
			if($this->input->get('msg'))
			{
				$msg = $this->input->get('msg');
			}
			$this->session->set_flashdata('msg', json_encode([1, $msg]));
			redirect('f/login');	
		}
		else
		{
			$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
			redirect('f/login');
		}
	}

	function fa()
	{
		if($this->user->is_logged())
		{
			if($this->input->post('submit'))
			{
				$this->fv->set_rules('key', $this->ui->text('2fa_text'), ['trim', 'required']);
				if($this->captcha->verify())
				{
					if($this->fv->run() === true)
					{
						if($this->user->logged_data(['2fa_key']) == $this->input->post('key'))
						{
							set_cookie('2fa', 'OK', 30 * 86400);
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_2fa_text')]));
							redirect('n');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_2fa_text')]));
							redirect('f/fa');
						}
					}
					else
					{
						if(validation_errors() !== '')
						{
							$this->session->set_flashdata('msg', json_encode([0, validation_errors()]));
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('required_fields_text')]));
						}
						redirect('f/fa');
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_captcha_response_text')]));
					redirect('f/fa');
				}
			}
			else
			{
				$data['title'] = '2fa_title';
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/2fa');
				$this->load->view($this->ui->template_dir().'/includes/footer');
			}
		}
		else
		{
			$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('login_to_continue_text')]));
			redirect('f/login');
		}
	}
}

?>